<?php
/**
 * parent_api.php
 * Sirf ek student ka data: uski bus + shuru se uske stop tak ke stops.
 * Auth: parent_api.php?sid=STUDENT_ID&t=TOKEN   (token = student_track_token($sid))
 */
declare(strict_types=1);

require_once __DIR__ . '/transport_config.php';
require_once __DIR__ . '/transport_lib.php';

header('Access-Control-Allow-Origin: *'); // App WebView / fetch ke liye

try {
    // ---------------- Student ID: login (auth.php) se ----------------
    $sid = transport_logged_in_sid();
    $sidSource = 'login';
    // Testing: login na mile to ?sid= se bhi chalega
    if ($sid === '' && TRANSPORT_ALLOW_SID_ONLY && isset($_GET['sid'])) {
        $sid = trim((string) $_GET['sid']);
        $sidSource = 'url';
    }
    if ($sid === '') {
        $out = ['success' => false, 'message' => 'Login nahi mila — student id nahi mili. Dobara login karein.'];
        if (TRANSPORT_ALLOW_SID_ONLY) {
            // Testing ke liye: session me kaun-kaun si keys hain (values nahi)
            $out['debug'] = [
                'dhoondhi_gayi_keys' => TRANSPORT_SID_KEYS,
                'session_keys'       => array_keys($_SESSION ?? []),
            ];
        }
        json_out($out, 401);
    }

    $cfg = transport_settings();
    $cid = transport_cid();
    $sess = transport_session();
    $db = transport_db();

    // ---------------- Student ----------------
    $sql = 'SELECT id, name, stopage FROM students WHERE id = ? AND cid = ?';
    $types = 'ss';
    $params = [$sid, $cid];
    if ($sess !== '') {
        $sql .= ' AND session = ?';
        $types .= 's';
        $params[] = $sess;
    }
    $stmt = $db->prepare($sql . ' LIMIT 1');
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$student) {
        // Kyu nahi mila — id galat hai ya cid/session match nahi kar raha?
        $reason = "Student id '$sid' ($sidSource) students table me nahi mili";
        $stmt = $db->prepare('SELECT cid, session FROM students WHERE id = ? LIMIT 5');
        $stmt->bind_param('s', $sid);
        $stmt->execute();
        $res = $stmt->get_result();
        $found = [];
        while ($r = $res->fetch_assoc()) {
            $found[] = 'cid=' . $r['cid'] . ', session=' . $r['session'];
        }
        $stmt->close();
        if ($found) {
            $reason = "Student id '$sid' mili, par match nahi hua. DB me: [" . implode(' | ', $found)
                . "] — abhi chal raha hai: cid=$cid, session=" . ($sess !== '' ? $sess : '(khali)');
        }
        json_out(['success' => false, 'message' => 'Student nahi mila: ' . $reason], 404);
    }
    $stopName = trim((string) $student['stopage']);
    if ($stopName === '') {
        json_out(['success' => false, 'message' => 'Is student ko transport assign nahi hai'], 404);
    }

    // ---------------- Stop -> Route -> Bus ----------------
    $stmt = $db->prepare('SELECT route FROM route_stopage WHERE cid = ? AND LOWER(TRIM(name)) = LOWER(TRIM(?)) LIMIT 1');
    $stmt->bind_param('ss', $cid, $stopName);
    $stmt->execute();
    $rs = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$rs) {
        json_out(['success' => false, 'message' => 'Stop kisi route me nahi mila'], 404);
    }

    $routeKey = (string) $rs['route'];
    $stmt = $db->prepare('SELECT name, vechile FROM route WHERE cid = ? AND UPPER(TRIM(name)) = UPPER(TRIM(?)) LIMIT 1');
    $stmt->bind_param('ss', $cid, $routeKey);
    $stmt->execute();
    $route = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$route) {
        json_out(['success' => false, 'message' => 'Route nahi mila'], 404);
    }

    $routeName = (string) $route['name'];
    $vehicleNo = (string) $route['vechile'];

    // ---------------- Live data ----------------
    $shift = current_shift($cfg, isset($_GET['shift']) ? (string) $_GET['shift'] : null);
    $serviceActive = $shift !== null;
    $orderShift = $shift ?? 'PICKUP';

    // Admin view jaisa: bus ki position hamesha laao (service time ke bahar bhi map par dikhe)
    $vehicle = null;
    $feedError = '';
    try {
        $feed = build_live_feed($orderShift);
        $vehicle = find_vehicle($feed['list'], $vehicleNo);
        if ($vehicle === null) {
            $feedError = "Bus $vehicleNo live GPS feed me nahi mili";
        }
    } catch (Throwable $e) {
        $vehicle = null;
        $feedError = $e->getMessage();
    }

    $stops = ($vehicle && !empty($vehicle['stops'])) ? $vehicle['stops'] : load_route_stops($cid, $routeName, $orderShift);

    $childIndex = null;
    $childKey = stop_key($stopName);
    foreach ($stops as $i => $s) {
        if (stop_key((string) $s['name']) === $childKey) {
            $childIndex = (int) $i;
            break;
        }
    }
    if ($childIndex === null) {
        json_out(['success' => false, 'message' => 'Stop route me nahi mila'], 404);
    }

    $child = $stops[$childIndex];

    // hasPosition = map par bus dikhegi; online = GPS taaza hai
    $hasPosition = $vehicle !== null
        && ((float) ($vehicle['latitude'] ?? 0) != 0.0 || (float) ($vehicle['longitude'] ?? 0) != 0.0)
        && !empty($vehicle['stops']);
    $online = $hasPosition && empty($vehicle['isStale']);

    if (!$hasPosition) {
        $childStatus = 'unknown';
    } elseif ($child['status'] === 'current') {
        $childStatus = 'arrived';
    } elseif ($child['status'] === 'departed') {
        $childStatus = 'passed';
    } else {
        $childStatus = 'coming';
    }

    $stopsAway = null;
    if ($childStatus === 'coming') {
        $from = $vehicle['currentIndex'] ?? $vehicle['nextIndex'] ?? null;
        if ($from !== null) {
            $stopsAway = max(0, $childIndex - (int) $from);
        }
    }

    // ---------------- Sirf shuru se bacche ke stop tak (simple loop, koi arrow fn nahi) ----------------
    $visibleStops = [];
    for ($i = 0; $i <= $childIndex; $i++) {
        $s = $stops[$i];
        $visibleStops[] = [
            'name'        => (string) $s['name'],
            'lat'         => (float) $s['stopage_lat'],
            'lng'         => (float) $s['stopage_lng'],
            'status'      => $hasPosition ? (string) $s['status'] : 'upcoming',
            'etaMin'      => $hasPosition ? ($s['etaMin'] ?? null) : null,
            'arrivalTime' => $hasPosition ? ($s['arrivalTime'] ?? null) : null,
            'distance'    => $hasPosition ? ($s['distance'] ?? null) : null,
            'isChild'     => $i === $childIndex,
        ];
    }

    // Bus abhi kahan hai (bacche ke stop tak ki list ke hisaab se)
    $currentStopName = null;
    $nextStopInfo = null;
    if ($hasPosition) {
        $ci = $vehicle['currentIndex'] ?? null;
        $ni = $vehicle['nextIndex'] ?? null;
        if ($ci !== null && isset($stops[(int) $ci])) {
            $currentStopName = (string) $stops[(int) $ci]['name'];
        }
        if ($ni !== null && (int) $ni <= $childIndex && isset($stops[(int) $ni])) {
            $ns = $stops[(int) $ni];
            $nextStopInfo = [
                'name'        => (string) $ns['name'],
                'distance'    => $ns['distance'] ?? null,
                'etaMin'      => $ns['etaMin'] ?? null,
                'arrivalTime' => $ns['arrivalTime'] ?? null,
            ];
        }
    }

    json_out([
        'success'       => true,
        'generatedAt'   => date(DATE_ATOM),
        'serviceActive' => $serviceActive,
        'shift'         => $orderShift,
        'feedError'     => $feedError,
        'school'        => ['name' => $cfg['school_name'], 'lat' => $cfg['school_lat'], 'lng' => $cfg['school_lng']],
        'student'       => ['id' => (string) $student['id'], 'name' => (string) $student['name'], 'stop' => $stopName],
        'bus'           => [
            'online'        => $online,
            'onMap'         => $vehicle !== null && ((float) ($vehicle['latitude'] ?? 0) != 0.0),
            'isStale'       => (bool) ($vehicle['isStale'] ?? false),
            'ignition'      => $vehicle ? (bool) ($vehicle['ignition'] ?? false) : false,
            'currentStop'   => $currentStopName,
            'nextStop'      => $nextStopInfo,
            'vehicleNumber' => $vehicleNo,
            'routeName'     => $routeName,
            'busColor'      => (string) ($vehicle['busColor'] ?? ''),
            'driverName'    => $cfg['parent_show_driver'] ? (string) ($vehicle['driverName'] ?? '') : '',
            'driverMobile'  => $cfg['parent_show_driver'] ? (string) ($vehicle['driverMobile'] ?? '') : '',
            'lat'           => $vehicle ? (float) ($vehicle['latitude'] ?? 0) : null,
            'lng'           => $vehicle ? (float) ($vehicle['longitude'] ?? 0) : null,
            'speed'         => $vehicle ? (float) ($vehicle['speed'] ?? 0) : 0,
            'moving'        => $vehicle ? (bool) ($vehicle['moving'] ?? false) : false,
            'gpsAgeMin'     => $vehicle['gpsAgeMin'] ?? null,
            'location'      => (string) ($vehicle['location'] ?? ''),
        ],
        'child'         => [
            'stopName'    => (string) $child['name'],
            'status'      => $childStatus,          // coming / arrived / passed / unknown
            'distanceKm'  => $childStatus === 'coming' ? ($child['distance'] ?? null) : null,
            'etaMin'      => $childStatus === 'coming' ? ($child['etaMin'] ?? null) : null,
            'arrivalTime' => $childStatus === 'coming' ? ($child['arrivalTime'] ?? null) : null,
            'stopsAway'   => $stopsAway,
            'lat'         => (float) $child['stopage_lat'],
            'lng'         => (float) $child['stopage_lng'],
        ],
        'totalStops'    => count($visibleStops),
        'stops'         => $visibleStops,
    ]);
} catch (Throwable $e) {
    json_out([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')',
    ], 500);
}