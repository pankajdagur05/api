<?php
declare(strict_types=1);

function transport_json_output(array $payload, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function transport_haversine_distance(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $earthRadius = 6371.0;
    $deltaLat = deg2rad($lat2 - $lat1);
    $deltaLon = deg2rad($lon2 - $lon1);
    $a = sin($deltaLat / 2) ** 2
        + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($deltaLon / 2) ** 2;
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
}

function transport_route_metric(float $originLat, float $originLon, float $destLat, float $destLon): array
{
    $apiKey = getenv('GOOGLE_ROUTES_API_KEY') ?: 'AIzaSyA4DcfhR1ziaFXYe6Eilip65TldRpSjSHQ';
    $distanceKm = transport_haversine_distance($originLat, $originLon, $destLat, $destLon);
    $fallbackMin = $distanceKm > 0 ? (int) ceil(($distanceKm / 24) * 60) : 0;

    if ($apiKey === '') {
        return array('distanceKm' => round($distanceKm, 2), 'etaMin' => $fallbackMin, 'source' => 'fallback');
    }

    $payload = array(
        'origins' => array(array('waypoint' => array('location' => array('latLng' => array('latitude' => $originLat, 'longitude' => $originLon))))),
        'destinations' => array(array('waypoint' => array('location' => array('latLng' => array('latitude' => $destLat, 'longitude' => $destLon))))),
        'travelMode' => 'DRIVE',
        'routingPreference' => 'TRAFFIC_AWARE',
    );

    $ch = curl_init('https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix');
    curl_setopt_array($ch, array(
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . $apiKey,
            'X-Goog-FieldMask: distanceMeters,duration,status',
        ),
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_TIMEOUT => 20,
    ));

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = json_decode((string) $response, true);
    if ($status >= 400 || !is_array($decoded) || empty($decoded[0]['distanceMeters'])) {
        return array('distanceKm' => round($distanceKm, 2), 'etaMin' => $fallbackMin, 'source' => 'fallback');
    }

    $seconds = (int) preg_replace('/[^0-9]/', '', (string) ($decoded[0]['duration'] ?? '0s'));
    return array(
        'distanceKm' => round(((int) $decoded[0]['distanceMeters']) / 1000, 2),
        'etaMin' => $seconds > 0 ? (int) ceil($seconds / 60) : 0,
        'source' => 'google_routes',
    );
}

function transport_minutes_to_clock(?int $minutes): ?string
{
    return $minutes === null ? null : date('h:i A', time() + ($minutes * 60));
}

function transport_api_get_json(string $url): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTPHEADER => array('Cache-Control: no-cache'),
    ));
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false || $status >= 400) {
        return null;
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : null;
}

function transport_build_feed(mysqli $host, array $options = array()): array
{
    $wheelseyeToken = $options['wheelseyeToken'] ?? (getenv('WHEELSEYE_ACCESS_TOKEN') ?: '618877ef-2843-4f76-bc57-52acbc9792f8');
    $wheelseyeBase = $options['wheelseyeBase'] ?? 'https://api.wheelseye.com';
    $companyId = (string) ($options['companyId'] ?? (getenv('TRANSPORT_COMPANY_ID') ?: '163'));

    $vehicleFilter = trim((string) ($_GET['vehicle'] ?? ''));
    $fromTime = trim((string) ($_GET['fromTime'] ?? ''));
    $toTime = trim((string) ($_GET['toTime'] ?? ''));
    $feedUrl = $fromTime !== '' && $toTime !== ''
        ? sprintf('%s/currentLocV2?accessToken=%s&searchText=%s&fromTime=%s&toTime=%s', $wheelseyeBase, urlencode($wheelseyeToken), rawurlencode($vehicleFilter), rawurlencode($fromTime), rawurlencode($toTime))
        : sprintf('%s/currentLoc?accessToken=%s', $wheelseyeBase, urlencode($wheelseyeToken));

    $data = transport_api_get_json($feedUrl);
    if (!$data || !isset($data['data']['list']) || !is_array($data['data']['list'])) {
        return array(
            'success' => false,
            'message' => 'Unable to fetch live vehicle feed.',
            'generatedAt' => date(DATE_ATOM),
            'data' => array('list' => array()),
        );
    }

    $hour = (int) date('H');
    $routeOrder = ($hour >= 13 && $hour < 19) ? 'DESC' : 'ASC';
    $nearThreshold = 0.12;

    $routeStmt = $host->prepare('SELECT driver, name FROM route WHERE vechile = ? AND cid = ? LIMIT 1');
    $driverStmt = $host->prepare('SELECT name FROM driver WHERE mobile = ? LIMIT 1');
    $stopStmt = $host->prepare('SELECT id, name, priority, stopage_lat, stopage_lng FROM route_stopage WHERE UPPER(TRIM(route)) = UPPER(TRIM(?)) AND cid = ? ORDER BY priority ' . $routeOrder);

    foreach ($data['data']['list'] as &$vehicle) {
        $vehicleNumber = (string) ($vehicle['vehicleNumber'] ?? '');
        $vehicleLat = isset($vehicle['latitude']) ? (float) $vehicle['latitude'] : 0.0;
        $vehicleLon = isset($vehicle['longitude']) ? (float) $vehicle['longitude'] : 0.0;

        $vehicle['driverName'] = 'Unknown';
        $vehicle['driverMobile'] = 'N/A';
        $vehicle['routeName'] = $vehicleNumber;
        $vehicle['stops'] = array();
        $vehicle['currentStop'] = null;
        $vehicle['nextStop'] = null;
        $vehicle['nextStopETA'] = '';
        $vehicle['routePath'] = array();
        $vehicle['routeSummary'] = null;

        if ($vehicleNumber === '' || !$routeStmt || !$driverStmt || !$stopStmt) {
            continue;
        }

        $routeStmt->bind_param('ss', $vehicleNumber, $companyId);
        $routeStmt->execute();
      $routeStmt->bind_result($driverMobile, $routeName);

if (!$routeStmt->fetch()) {
    continue;
}

$routeStmt->free_result();
        $vehicle['routeName'] = $routeName;
        $vehicle['driverMobile'] = $driverMobile !== '' ? $driverMobile : 'N/A';

        if ($driverMobile !== '') {
            $driverStmt->bind_param('s', $driverMobile);
            $driverStmt->execute();
           $driverStmt->bind_result($driverName);

if ($driverStmt->fetch()) {
    $vehicle['driverName'] = (string) $driverName;
}

$driverStmt->free_result();
        }

        $stopStmt->bind_param('ss', $routeName, $companyId);
        $stopStmt->execute();
        $stopResult = $stopStmt->get_result();
        $stops = array();

        while ($stopResult && ($stop = $stopResult->fetch_assoc())) {
            $stop['stopage_lat'] = (float) ($stop['stopage_lat'] ?? 0);
            $stop['stopage_lng'] = (float) ($stop['stopage_lng'] ?? 0);
            $stop['distance'] = round(transport_haversine_distance($vehicleLat, $vehicleLon, $stop['stopage_lat'], $stop['stopage_lng']), 2);
            $stop['status'] = 'upcoming';
            $stop['etaMin'] = null;
            $stop['arrivalTime'] = null;
            $stops[] = $stop;
        }

        if (!$stops) {
            continue;
        }

        $vehicle['routeSummary'] = array(
            'start' => $stops[0]['name'],
            'end' => $stops[count($stops) - 1]['name'],
            'stopCount' => count($stops),
            'direction' => $routeOrder === 'ASC' ? 'Pickup Run' : 'Drop Run',
        );

        $nearestIndex = 0;
        $nearestDistance = PHP_FLOAT_MAX;
        foreach ($stops as $index => $stop) {
            if ($stop['distance'] < $nearestDistance) {
                $nearestDistance = $stop['distance'];
                $nearestIndex = $index;
            }
        }

        $currentIndex = $nearestDistance <= $nearThreshold ? $nearestIndex : null;
        $nextIndex = $currentIndex !== null ? min($currentIndex + 1, count($stops) - 1) : $nearestIndex;

        foreach ($stops as $index => &$stop) {
            if ($currentIndex !== null) {
                if ($index < $currentIndex) {
                    $stop['status'] = 'departed';
                    $stop['distance'] = null;
                } elseif ($index === $currentIndex) {
                    $stop['status'] = 'current';
                    $stop['distance'] = 0.0;
                    $stop['etaMin'] = 0;
                    $stop['arrivalTime'] = date('h:i A');
                }
            } else {
                if ($index < $nextIndex) {
                    $stop['status'] = 'departed';
                    $stop['distance'] = null;
                } elseif ($index === $nextIndex) {
                    $stop['status'] = 'next';
                }
            }
        }
        unset($stop);

        $runningEtaMin = 0;
        foreach ($stops as $index => &$stop) {
            if ($index < $nextIndex || $stop['status'] === 'departed' || $stop['status'] === 'current') {
                continue;
            }

            if ($index === $nextIndex) {
                $metric = transport_route_metric($vehicleLat, $vehicleLon, (float) $stop['stopage_lat'], (float) $stop['stopage_lng']);
                $runningEtaMin = (int) $metric['etaMin'];
                $stop['distance'] = $metric['distanceKm'];
                $stop['etaMin'] = $runningEtaMin;
                $stop['arrivalTime'] = transport_minutes_to_clock($runningEtaMin);
                continue;
            }

            $previous = $stops[$index - 1];
            $segmentDistance = transport_haversine_distance((float) $previous['stopage_lat'], (float) $previous['stopage_lng'], (float) $stop['stopage_lat'], (float) $stop['stopage_lng']);
            $runningEtaMin += $segmentDistance > 0 ? (int) ceil(($segmentDistance / 22) * 60) : 0;
            $stop['distance'] = round(transport_haversine_distance($vehicleLat, $vehicleLon, (float) $stop['stopage_lat'], (float) $stop['stopage_lng']), 2);
            $stop['etaMin'] = $runningEtaMin;
            $stop['arrivalTime'] = transport_minutes_to_clock($runningEtaMin);
        }
        unset($stop);

        $vehicle['stops'] = $stops;
        $vehicle['currentStop'] = $currentIndex !== null ? array('name' => $stops[$currentIndex]['name'], 'distance' => 0, 'arrivalTime' => date('h:i A')) : null;
        $vehicle['nextStop'] = isset($stops[$nextIndex]) ? array(
            'name' => $stops[$nextIndex]['name'],
            'distance' => $stops[$nextIndex]['distance'],
            'etaMin' => $stops[$nextIndex]['etaMin'],
            'arrivalTime' => $stops[$nextIndex]['arrivalTime'],
        ) : null;
        $vehicle['nextStopETA'] = isset($stops[$nextIndex]['etaMin']) ? (string) $stops[$nextIndex]['etaMin'] : '';
        $vehicle['routePath'] = array_map(static function (array $stop): array {
            return array(
                'name' => $stop['name'],
                'lat' => (float) $stop['stopage_lat'],
                'lng' => (float) $stop['stopage_lng'],
                'status' => $stop['status'],
            );
        }, $stops);
    }
    unset($vehicle);

   if ($routeStmt instanceof mysqli_stmt) {
    $routeStmt->close();
}

if ($driverStmt instanceof mysqli_stmt) {
    $driverStmt->close();
}

if ($stopStmt instanceof mysqli_stmt) {
    $stopStmt->close();
}

    return array(
        'success' => true,
        'generatedAt' => date(DATE_ATOM),
        'data' => $data['data'],
    );
}
