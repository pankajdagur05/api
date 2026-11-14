<?php
/**
 * transport_lib.php
 * Common logic: Wheelseye feed, ETA, stop progress, parent token, SMS, push.
 * Pehle transport_config.php include hona chahiye.
 */
declare(strict_types=1);

/* ================================================================== */
/* Generic helpers                                                     */
/* ================================================================== */

function json_out(array $payload, int $status = 200): void
{
    if (!headers_sent()) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store, no-cache, must-revalidate');
    }
    // Hindi/latin1 data me kharab UTF-8 ho to bhi JSON fail na ho
    $flags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) {
        $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
    }
    $json = json_encode($payload, $flags);
    if ($json === false) {
        $json = json_encode(['success' => false, 'message' => 'JSON error: ' . json_last_error_msg()]);
    }
    echo $json;
    exit;
}

function haversine_km(float $lat1, float $lon1, float $lat2, float $lon2): float
{
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
    return 6371.0 * 2 * atan2(sqrt($a), sqrt(1 - $a));
}

function normalize_vehicle(string $no): string
{
    return strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', $no));
}

function normalize_mobile(?string $mobile): string
{
    $d = (string) preg_replace('/\D/', '', (string) $mobile);
    if (strlen($d) > 10) {
        $d = substr($d, -10);
    }
    return strlen($d) === 10 ? $d : '';
}

function stop_key(?string $name): string
{
    return mb_strtolower(trim((string) preg_replace('/\s+/u', ' ', (string) $name)));
}

function minutes_to_clock(?int $minutes): ?string
{
    return $minutes === null ? null : date('h:i A', time() + $minutes * 60);
}

/* ------------------------- file cache ----------------------------- */

function tcache_file(string $key): string
{
    $dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'edplus_transport';
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    return $dir . DIRECTORY_SEPARATOR . sha1($key) . '.json';
}

function tcache_get(string $key, int $ttlSec): ?array
{
    $f = tcache_file($key);
    if (!is_file($f) || (time() - (int) @filemtime($f)) > $ttlSec) {
        return null;
    }
    $d = json_decode((string) @file_get_contents($f), true);
    return is_array($d) ? $d : null;
}

function tcache_set(string $key, array $value): void
{
    @file_put_contents(tcache_file($key), json_encode($value, JSON_UNESCAPED_UNICODE), LOCK_EX);
}

/** Aakhri error (debug ke liye) */
function transport_last_error(?string $set = null): string
{
    static $err = '';
    if ($set !== null) {
        $err = $set;
        error_log('[transport] ' . $set);
    }
    return $err;
}

function http_get_json(string $url, int $timeout = 30): ?array
{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => $timeout,
        CURLOPT_CONNECTTIMEOUT => 15,
        CURLOPT_HTTPHEADER     => ['Cache-Control: no-cache'],
    ]);
    $body = curl_exec($ch);
    if ($body === false) {
        transport_last_error('cURL error: ' . curl_error($ch));
        curl_close($ch);
        return null;
    }
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    $snippet = mb_substr(trim((string) $body), 0, 300);
    if ($status >= 400) {
        transport_last_error('HTTP ' . $status . ' from ' . parse_url($url, PHP_URL_HOST) . ' | response: ' . $snippet);
        return null;
    }
    $data = json_decode((string) $body, true);
    if (!is_array($data)) {
        transport_last_error('Invalid JSON from ' . parse_url($url, PHP_URL_HOST) . ' | response: ' . $snippet);
        return null;
    }
    return $data;
}

/* ================================================================== */
/* Shift (Pickup / Drop)                                               */
/* ================================================================== */

function in_time_range(string $now, string $start, string $end): bool
{
    return $start <= $end ? ($now >= $start && $now <= $end) : ($now >= $start || $now <= $end);
}

/** PICKUP / DROP / null (service time nahi hai). ?shift=pickup|drop se testing override. */
function current_shift(array $cfg, ?string $override = null): ?string
{
    $override = strtoupper(trim((string) $override));
    if (in_array($override, ['PICKUP', 'DROP'], true)) {
        return $override;
    }
    $now = date('H:i');
    if (in_time_range($now, $cfg['pickup_start'], $cfg['pickup_end'])) {
        return 'PICKUP';
    }
    if (in_time_range($now, $cfg['drop_start'], $cfg['drop_end'])) {
        return 'DROP';
    }
    return null;
}

/* ================================================================== */
/* Distance / ETA                                                      */
/* ================================================================== */

/** Google Routes (traffic aware) se distance + ETA, 60 sec cache, fail par fallback. */
function route_metric(array $cfg, float $oLat, float $oLng, float $dLat, float $dLng): array
{
    $straight = haversine_km($oLat, $oLng, $dLat, $dLng);
    if ($straight < 0.05) {
        return ['distanceKm' => 0.0, 'etaMin' => 0, 'source' => 'at_stop'];
    }
    $roadKm = $straight * $cfg['road_factor'];
    $fallback = [
        'distanceKm' => round($roadKm, 2),
        'etaMin'     => (int) ceil($roadKm / max(5.0, $cfg['avg_speed_kmh']) * 60),
        'source'     => 'fallback',
    ];

    $apiKey = trim((string) $cfg['google_routes_key']);
    if ($apiKey === '') {
        return $fallback;
    }

    $cacheKey = sprintf('rm|%.4f,%.4f|%.4f,%.4f', $oLat, $oLng, $dLat, $dLng);
    $cached = tcache_get($cacheKey, 60);
    if ($cached !== null) {
        return $cached;
    }

    $payload = [
        'origins'      => [['waypoint' => ['location' => ['latLng' => ['latitude' => $oLat, 'longitude' => $oLng]]]]],
        'destinations' => [['waypoint' => ['location' => ['latLng' => ['latitude' => $dLat, 'longitude' => $dLng]]]]],
        'travelMode'        => 'DRIVE',
        'routingPreference' => 'TRAFFIC_AWARE',
    ];

    $ch = curl_init('https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'X-Goog-Api-Key: ' . $apiKey,
            'X-Goog-FieldMask: distanceMeters,duration,status',
        ],
        CURLOPT_POSTFIELDS     => json_encode($payload),
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);
    $resp = curl_exec($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $decoded = is_string($resp) ? json_decode($resp, true) : null;
    if ($status >= 400 || !is_array($decoded) || empty($decoded[0]['distanceMeters'])) {
        return $fallback;
    }

    $seconds = (int) preg_replace('/[^0-9]/', '', (string) ($decoded[0]['duration'] ?? '0s'));
    $result = [
        'distanceKm' => round(((int) $decoded[0]['distanceMeters']) / 1000, 2),
        'etaMin'     => $seconds > 0 ? (int) ceil($seconds / 60) : 0,
        'source'     => 'google_routes',
    ];
    tcache_set($cacheKey, $result);
    return $result;
}

/* ================================================================== */
/* DB lookups                                                          */
/* ================================================================== */

/** cid ke saare routes: [normalized vehicle no => row] */
function load_routes_map(string $cid): array
{
    $sqls = [
        'SELECT name, vechile, driver, bus_color FROM route WHERE cid = ?',
        "SELECT name, vechile, driver, '' AS bus_color FROM route WHERE cid = ?", // agar bus_color column abhi nahi bana
    ];
    foreach ($sqls as $sql) {
        try {
            $stmt = transport_db()->prepare($sql);
            if (!$stmt) {
                continue;
            }
            $stmt->bind_param('s', $cid);
            $stmt->execute();
            $res = $stmt->get_result();
            $map = [];
            while ($row = $res->fetch_assoc()) {
                $k = normalize_vehicle((string) $row['vechile']);
                if ($k !== '') {
                    $map[$k] = $row;
                }
            }
            $stmt->close();
            return $map;
        } catch (Throwable $e) {
            continue;
        }
    }
    return [];
}

function lookup_driver_name(string $mobile): string
{
    static $cache = [];
    if ($mobile === '') {
        return 'Unknown';
    }
    if (isset($cache[$mobile])) {
        return $cache[$mobile];
    }
    $name = 'Unknown';
    try {
        $stmt = transport_db()->prepare('SELECT name FROM driver WHERE mobile = ? LIMIT 1');
        $stmt->bind_param('s', $mobile);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row && trim((string) $row['name']) !== '') {
            $name = (string) $row['name'];
        }
    } catch (Throwable $e) {
        error_log('[transport] driver: ' . $e->getMessage());
    }
    return $cache[$mobile] = $name;
}

/** Route ke stops, shift ke order me (Pickup = ASC, Drop = DESC). */
function load_route_stops(string $cid, string $routeName, string $shift): array
{
    $order = $shift === 'DROP' ? 'DESC' : 'ASC';
    $stops = [];
    try {
        $stmt = transport_db()->prepare(
            "SELECT id, name, priority, stopage_lat, stopage_lng
             FROM route_stopage
             WHERE UPPER(TRIM(route)) = UPPER(TRIM(?)) AND cid = ?
             ORDER BY CAST(priority AS DECIMAL(10,2)) $order, id $order"
        );
        $stmt->bind_param('ss', $routeName, $cid);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $stops[] = [
                'id'          => (int) $row['id'],
                'name'        => (string) $row['name'],
                'priority'    => $row['priority'],
                'stopage_lat' => (float) $row['stopage_lat'],
                'stopage_lng' => (float) $row['stopage_lng'],
                'distance'    => null,
                'status'      => 'upcoming',
                'etaMin'      => null,
                'arrivalTime' => null,
            ];
        }
        $stmt->close();
    } catch (Throwable $e) {
        error_log('[transport] stops: ' . $e->getMessage());
    }
    return $stops;
}

/* ================================================================== */
/* Live progress                                                       */
/* ================================================================== */

/**
 * Bus ki position se har stop ka status (departed/current/next/upcoming), doori aur ETA.
 */
function compute_stop_progress(array $stops, float $lat, float $lng, array $cfg): array
{
    $n = count($stops);
    $nearest = 0;
    $nearestD = PHP_FLOAT_MAX;

    foreach ($stops as $i => $s) {
        $d = haversine_km($lat, $lng, $s['stopage_lat'], $s['stopage_lng']);
        $stops[$i]['distance'] = round($d, 2);
        if ($d < $nearestD) {
            $nearestD = $d;
            $nearest = $i;
        }
    }

    $current = null;
    if ($nearestD <= $cfg['near_stop_km']) {
        $current = $nearest;
        $next = $nearest + 1 < $n ? $nearest + 1 : null;
    } else {
        $next = $nearest;
        // Kya bus sabse paas wale stop ko cross kar chuki hai?
        if ($nearest + 1 < $n) {
            $a = $stops[$nearest];
            $b = $stops[$nearest + 1];
            $segment = haversine_km($a['stopage_lat'], $a['stopage_lng'], $b['stopage_lat'], $b['stopage_lng']);
            $busToB = haversine_km($lat, $lng, $b['stopage_lat'], $b['stopage_lng']);
            if ($busToB < $segment) {
                $next = $nearest + 1;
            }
        }
    }

    $anchor = $current ?? $next ?? $n;
    foreach ($stops as $i => &$s) {
        if ($i < $anchor) {
            $s['status'] = 'departed';
            $s['distance'] = null;
        } elseif ($current !== null && $i === $current) {
            $s['status'] = 'current';
            $s['distance'] = 0.0;
            $s['etaMin'] = 0;
            $s['arrivalTime'] = date('h:i A');
        } elseif ($next !== null && $i === $next) {
            $s['status'] = 'next';
        } else {
            $s['status'] = 'upcoming';
        }
    }
    unset($s);

    if ($next !== null) {
        $m = route_metric($cfg, $lat, $lng, $stops[$next]['stopage_lat'], $stops[$next]['stopage_lng']);
        $running = (int) $m['etaMin'];
        $roadKm = (float) $m['distanceKm'];
        $stops[$next]['distance'] = round($roadKm, 2);
        $stops[$next]['etaMin'] = $running;
        $stops[$next]['arrivalTime'] = minutes_to_clock($running);
        $stops[$next]['etaSource'] = $m['source'];

        $speed = max(5.0, $cfg['avg_speed_kmh']);
        for ($i = $next + 1; $i < $n; $i++) {
            $p = $stops[$i - 1];
            $seg = haversine_km($p['stopage_lat'], $p['stopage_lng'], $stops[$i]['stopage_lat'], $stops[$i]['stopage_lng']) * $cfg['road_factor'];
            $roadKm += $seg;
            $running += (int) ceil(($seg / $speed) * 60 + $cfg['stop_dwell_min']);
            $stops[$i]['distance'] = round($roadKm, 2);
            $stops[$i]['etaMin'] = $running;
            $stops[$i]['arrivalTime'] = minutes_to_clock($running);
        }
    }

    return ['stops' => $stops, 'currentIndex' => $current, 'nextIndex' => $next];
}

function fetch_wheelseye_list(array $cfg): ?array
{
    $token = trim((string) $cfg['wheelseye_token']);
    if ($token === '') {
        transport_last_error('wheelseye_token khali hai (transport_settings)');
        return null;
    }
    // Purane code jaisa: token bina encode kiye (agar DB me pehle se encoded token ho to double-encode na ho)
    $data = http_get_json('https://api.wheelseye.com/currentLoc?accessToken=' . $token, 45);
    if ($data === null) {
        return null;
    }
    if (!isset($data['data']['list']) || !is_array($data['data']['list'])) {
        transport_last_error('Wheelseye response me data.list nahi hai | response: '
            . mb_substr((string) json_encode($data, JSON_UNESCAPED_UNICODE), 0, 300));
        return null;
    }
    return $data['data']['list'];
}

function enrich_vehicle(array $vehicle, array $routes, string $shift, array $cfg, string $cid): array
{
    $vno = (string) ($vehicle['vehicleNumber'] ?? '');
    $lat = (float) ($vehicle['latitude'] ?? 0);
    $lng = (float) ($vehicle['longitude'] ?? 0);

    $epoch = (float) ($vehicle['createdDate'] ?? 0);
    if ($epoch > 1e12) {
        $epoch /= 1000; // milliseconds
    }
    $age = $epoch > 0 ? max(0, (int) floor((time() - $epoch) / 60)) : null;
    $stale = $age !== null && $age > $cfg['stale_after_min'];

    $vehicle = array_merge($vehicle, [
        'driverName'   => 'Unknown',
        'driverMobile' => '',
        'routeName'    => $vno,
        'busColor'     => '',
        'mapped'       => false,
        'stops'        => [],
        'currentStop'  => null,
        'nextStop'     => null,
        'nextStopETA'  => '',
        'routePath'    => [],
        'routeSummary' => null,
        'currentIndex' => null,
        'nextIndex'    => null,
        'gpsAgeMin'    => $age,
        'isStale'      => $stale,
        'moving'       => !$stale && (float) ($vehicle['speed'] ?? 0) > 0,
    ]);

    $route = $routes[normalize_vehicle($vno)] ?? null;
    if (!$route) {
        return $vehicle;
    }

    $vehicle['mapped'] = true;
    $vehicle['routeName'] = (string) ($route['name'] ?: $vno);
    $vehicle['driverMobile'] = (string) ($route['driver'] ?? '');
    $vehicle['driverName'] = lookup_driver_name($vehicle['driverMobile']);
    $vehicle['busColor'] = (string) ($route['bus_color'] ?? '');

    $stops = load_route_stops($cid, $vehicle['routeName'], $shift);
    if (!$stops) {
        return $vehicle;
    }

    $vehicle['routeSummary'] = [
        'start'     => $stops[0]['name'],
        'end'       => $stops[count($stops) - 1]['name'],
        'stopCount' => count($stops),
        'direction' => $shift === 'DROP' ? 'Drop Run' : 'Pickup Run',
    ];

    if ($lat != 0.0 || $lng != 0.0) {
        $p = compute_stop_progress($stops, $lat, $lng, $cfg);
        $stops = $p['stops'];
        $vehicle['currentIndex'] = $p['currentIndex'];
        $vehicle['nextIndex'] = $p['nextIndex'];

        if ($p['currentIndex'] !== null) {
            $vehicle['currentStop'] = ['name' => $stops[$p['currentIndex']]['name'], 'distance' => 0, 'arrivalTime' => date('h:i A')];
        }
        if ($p['nextIndex'] !== null) {
            $ns = $stops[$p['nextIndex']];
            $vehicle['nextStop'] = ['name' => $ns['name'], 'distance' => $ns['distance'], 'etaMin' => $ns['etaMin'], 'arrivalTime' => $ns['arrivalTime']];
            $vehicle['nextStopETA'] = (string) $ns['etaMin'];
        }
    }

    $vehicle['stops'] = $stops;
    $vehicle['routePath'] = array_map(static fn(array $s): array => [
        'name'   => $s['name'],
        'lat'    => $s['stopage_lat'],
        'lng'    => $s['stopage_lng'],
        'status' => $s['status'],
    ], $stops);

    return $vehicle;
}

/**
 * Poori fleet ka enriched live feed (cid + shift ke hisaab se cache).
 * @throws RuntimeException
 */
function build_live_feed(string $shift, bool $useCache = true): array
{
    $cid = transport_cid();
    $cfg = transport_settings();
    $ck = "feed|$cid|$shift";

    if ($useCache) {
        $c = tcache_get($ck, max(3, $cfg['feed_cache_sec']));
        if ($c !== null) {
            return $c;
        }
    }

    $raw = fetch_wheelseye_list($cfg);
    if ($raw === null) {
        $stale = tcache_get($ck, 600);
        if ($stale !== null) {
            $stale['stale'] = true;
            return $stale;
        }
        throw new RuntimeException('Wheelseye feed nahi mila: ' . (transport_last_error() ?: 'unknown'));
    }

    $routes = load_routes_map($cid);
    $list = [];
    foreach ($raw as $vehicle) {
        if (is_array($vehicle)) {
            $list[] = enrich_vehicle($vehicle, $routes, $shift, $cfg, $cid);
        }
    }
    usort($list, static function (array $a, array $b): int {
        return [$b['mapped'] ? 1 : 0, $a['routeName']] <=> [$a['mapped'] ? 1 : 0, $b['routeName']];
    });

    $out = ['list' => $list, 'shift' => $shift, 'generatedAt' => date(DATE_ATOM), 'stale' => false];
    tcache_set($ck, $out);
    return $out;
}

function find_vehicle(array $list, string $vehicleNo): ?array
{
    $k = normalize_vehicle($vehicleNo);
    foreach ($list as $v) {
        if (normalize_vehicle((string) ($v['vehicleNumber'] ?? '')) === $k) {
            return $v;
        }
    }
    return null;
}

/* ================================================================== */
/* Parent tracking link (login-free, student-specific)                 */
/* ================================================================== */

function student_track_token(string $studentId): string
{
    return substr(hash_hmac('sha256', $studentId . '|' . transport_cid(), TRANSPORT_SECRET), 0, 16);
}

function verify_track_token(string $studentId, string $token = ''): bool
{
    if ($studentId === '') {
        return false;
    }
    if ($token === '') {
        return defined('TRANSPORT_ALLOW_SID_ONLY') && TRANSPORT_ALLOW_SID_ONLY; // testing mode
    }
    return hash_equals(student_track_token($studentId), $token);
}

function track_url(string $studentId): string
{
    return transport_base_url() . 'parent_live.php?sid=' . rawurlencode($studentId) . '&t=' . student_track_token($studentId);
}

/* ================================================================== */
/* SMS + Push                                                          */
/* ================================================================== */

/** DLT template (purana wala) — text bilkul same rehna chahiye */
function build_bus_sms(string $busColor, string $vno, string $km, string $stopName, string $eta, string $link): string
{
    return "प्रिय अभिभावक, आपके बच्चे की {$busColor} रंग वाली बस नंबर {$vno} अभी {$km} किलोमीटर दूर है और {$stopName} स्टॉप के पास पहुँच रही है। अनुमानित आगमन समय {$eta} मिनट। अभी ट्रैक करें: {$link} - Edplus Ai";
}

/** SMS ka asli number: testing me SMS_TEST_MOBILE, warna parent ka */
function sms_target_mobile(string $parentMobile): string
{
    return SMS_TEST_MOBILE !== '' ? normalize_mobile(SMS_TEST_MOBILE) : normalize_mobile($parentMobile);
}

/** par-ken gateway se SMS (GET request) */
function send_sms(array $cfg, string $mobile, string $text): array
{
    $mobile = normalize_mobile($mobile);
    if ($mobile === '') {
        return ['ok' => false, 'response' => 'Mobile number galat hai'];
    }
    $url = SMS_API_URL . '?' . http_build_query([
        'key'        => SMS_API_KEY,
        'route'      => SMS_ROUTE,
        'type'       => SMS_TYPE,
        'sender'     => SMS_SENDER,
        'number'     => $mobile,
        'sms'        => $text,
        'templateid' => SMS_TEMPLATE_ID,
    ]);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 20,
        CURLOPT_CONNECTTIMEOUT => 10,
    ]);
    $out = curl_exec($ch);
    $err = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($out === false) {
        error_log('[transport] SMS cURL error: ' . $err);
        return ['ok' => false, 'response' => $err];
    }
    $ok = $status < 400;
    if (!$ok) {
        error_log('[transport] SMS HTTP ' . $status . ': ' . mb_substr((string) $out, 0, 200));
    }
    return ['ok' => $ok, 'response' => (string) $out];
}

/** Parent ke mobile ke saare FCM tokens par push. Return = kitne tokens. */
function send_parent_push(string $mobile, string $title, string $body, array $data): int
{
    if ($mobile === '') {
        return 0;
    }
    if (!function_exists('sendPushNotification')) {
        error_log('[transport] sendPushNotification() not found - PUSH_HELPER_FILE check karein');
        return 0;
    }
    $tokens = [];
    try {
        $role = PARENT_PUSH_ROLE;
        $stmt = transport_db()->prepare("SELECT DISTINCT fcm_token FROM push_tokens WHERE role = ? AND mobile = ? AND fcm_token <> ''");
        $stmt->bind_param('ss', $role, $mobile);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $tokens[] = (string) $row['fcm_token'];
        }
        $stmt->close();
    } catch (Throwable $e) {
        error_log('[transport] push tokens: ' . $e->getMessage());
        return 0;
    }
    if (!$tokens) {
        return 0;
    }
    // FCM data payload me sab values string honi chahiye
    $data = array_map(static fn($v) => (string) $v, $data);
    sendPushNotification($tokens, $title, $body, $data);
    return count($tokens);
}