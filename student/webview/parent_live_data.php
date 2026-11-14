<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/lib_transport.php';

session_start();

date_default_timezone_set('Asia/Kolkata');

function parent_json_error(string $message, int $status): void
{
    transport_json_output(array(
        'success' => false,
        'message' => $message,
    ), $status);
}

function parent_normalize(string $value): string
{
    return strtolower(trim($value));
}

function parent_find_student_stop_index(array $stops, ?string $stopName, ?int $stopIndex): ?int
{
    if ($stopIndex !== null && $stopIndex >= 1 && $stopIndex <= count($stops)) {
        return $stopIndex - 1;
    }

    if ($stopName !== null && $stopName !== '') {
        $needle = parent_normalize($stopName);
        foreach ($stops as $index => $stop) {
            $stopValue = parent_normalize((string) ($stop['name'] ?? ''));
            if (
                $stopValue === $needle
                || strpos($stopValue, $needle) !== false
                || strpos($needle, $stopValue) !== false
            ) {
                return $index;
            }
        }
    }

    return null;
}

function parent_fetch_student(mysqli $host, int $studentId, string $companyId): ?array
{
    $studentStmt = $host->prepare('SELECT id, name, route, stopage FROM students WHERE id = ? AND cid = ? LIMIT 1');
    if (!$studentStmt) {
        return null;
    }

    $studentStmt->bind_param('is', $studentId, $companyId);
    $studentStmt->execute();
    $studentStmt->bind_result($id, $name, $route, $stopage);

    $student = null;
    if ($studentStmt->fetch()) {
        $student = array(
            'id' => (int) $id,
            'name' => (string) $name,
            'route' => (string) $route,
            'stopage' => (string) $stopage,
        );
    }

    $studentStmt->close();
    return $student;
}

function parent_fetch_route_vehicle(mysqli $host, string $routeName, string $companyId): ?string
{
    $routeStmt = $host->prepare('SELECT vechile FROM route WHERE UPPER(TRIM(name)) = UPPER(TRIM(?)) AND cid = ? LIMIT 1');
    if (!$routeStmt) {
        return null;
    }

    $routeStmt->bind_param('ss', $routeName, $companyId);
    $routeStmt->execute();
    $routeStmt->bind_result($vehicleNumber);

    $vehicle = null;
    if ($routeStmt->fetch()) {
        $vehicle = trim((string) $vehicleNumber);
    }

    $routeStmt->close();
    return $vehicle !== '' ? $vehicle : null;
}

function parent_bootstrap_student_session(mysqli $host): array
{
    $headerUsername = $_SERVER['HTTP_USERNAME'] ?? null;
    $headerToken = $_SERVER['HTTP_TOKEN'] ?? null;
    $headerSid = $_SERVER['HTTP_ID'] ?? null;

    if ($headerUsername && $headerSid) {
        session_unset();
        session_destroy();
        session_start();

        $_SESSION['username'] = $headerUsername;
        $_SESSION['token'] = $headerToken;
        $_SESSION['sid'] = $headerSid;

        session_regenerate_id(true);
    }

    $username = $_SESSION['username'] ?? null;
    $token = $_SESSION['token'] ?? null;
    $sid = $_SESSION['sid'] ?? null;

    if (!$username || !$token) {
        $debugStudent = parent_fetch_student($host, 194514, '163');
        if ($debugStudent) {
            return array(
                'student_id' => (int) $debugStudent['id'],
                'company_id' => '163',
                'student_name' => (string) $debugStudent['name'],
                'standard' => '',
                'route' => (string) $debugStudent['route'],
                'stopage' => (string) $debugStudent['stopage'],
                'branch_name' => '',
                'username' => '',
                'is_debug' => true,
            );
        }
        parent_json_error('Access Denied', 401);
    }

    $validStmt = $host->prepare('SELECT id FROM apk_token WHERE user = ? AND token = ? LIMIT 1');
    if (!$validStmt) {
        parent_json_error('Token validation unavailable.', 500);
    }

    $validStmt->bind_param('ss', $username, $token);
    $validStmt->execute();
    $validStmt->store_result();
    $isValid = $validStmt->num_rows > 0;
    $validStmt->close();

    if (!$isValid) {
        session_destroy();
        parent_json_error('Invalid Token', 401);
    }

    $studentStmt = $host->prepare('SELECT id, name, standard, cid, route, stopage FROM students WHERE smobile = ? AND id = ? LIMIT 1');
    if (!$studentStmt) {
        parent_json_error('Student lookup unavailable.', 500);
    }

    $sidInt = (int) $sid;
    $studentStmt->bind_param('si', $username, $sidInt);
    $studentStmt->execute();
    $studentStmt->bind_result($id, $name, $standard, $cid, $route, $stopage);

    $student = null;
    if ($studentStmt->fetch()) {
        $student = array(
            'id' => (int) $id,
            'name' => (string) $name,
            'standard' => (string) $standard,
            'cid' => (string) $cid,
            'route' => (string) $route,
            'stopage' => (string) $stopage,
            'username' => (string) $username,
        );
    }
    $studentStmt->close();

    if (!$student) {
        session_destroy();
        parent_json_error('No Student Found', 404);
    }

    $_SESSION['id'] = $student['id'];
    $_SESSION['name'] = $student['name'];
    $_SESSION['standard'] = $student['standard'];
    $_SESSION['username'] = $username;
    $_SESSION['token'] = $token;
    $_SESSION['type'] = 'other';
    $_SESSION['branch'] = $student['cid'];

    $sessionStmt = $host->prepare('SELECT name FROM session WHERE cid = ? ORDER BY id DESC LIMIT 1');
    if ($sessionStmt) {
        $sessionStmt->bind_param('s', $student['cid']);
        $sessionStmt->execute();
        $sessionStmt->bind_result($sessionName);
        if ($sessionStmt->fetch()) {
            $_SESSION['session'] = (string) $sessionName;
        }
        $sessionStmt->close();
    }

    $branchStmt = $host->prepare('SELECT iname FROM branch_category WHERE id = ? LIMIT 1');
    if ($branchStmt) {
        $branchStmt->bind_param('s', $student['cid']);
        $branchStmt->execute();
        $branchStmt->bind_result($branchName);
        if ($branchStmt->fetch()) {
            $_SESSION['branch_name'] = (string) $branchName;
        }
        $branchStmt->close();
    }

    return array(
        'student_id' => (int) $_SESSION['id'],
        'company_id' => (string) $_SESSION['branch'],
        'student_name' => (string) $_SESSION['name'],
        'standard' => (string) ($_SESSION['standard'] ?? ''),
        'route' => $student['route'],
        'stopage' => $student['stopage'],
        'branch_name' => (string) ($_SESSION['branch_name'] ?? ''),
        'username' => (string) $_SESSION['username'],
        'is_debug' => false,
    );
}

function parent_state_file(): string
{
    return __DIR__ . '/runtime/parent_notification_state.json';
}

function parent_read_state(): array
{
    $path = parent_state_file();
    if (!is_file($path)) {
        return array();
    }

    $decoded = json_decode((string) file_get_contents($path), true);
    return is_array($decoded) ? $decoded : array();
}

function parent_write_state(array $state): void
{
    file_put_contents(parent_state_file(), json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function parent_send_notification(string $taskTo, string $title, string $body): bool
{
    $notificationFile = dirname(__DIR__) . '/api/send_notification.php';
    if (!is_file($notificationFile) || $taskTo === '') {
        return false;
    }

    include_once $notificationFile;
    if (!function_exists('sendAppNotification')) {
        return false;
    }

    $host2 = $GLOBALS['host2'] ?? null;
    sendAppNotification($taskTo, $title, $body, $host2);
    return true;
}

function parent_maybe_notify(array $vehicle, array $studentStop, string $taskTo, string $studentKey): array
{
    if ($taskTo === '') {
        return array();
    }

    $state = parent_read_state();
    $vehicleKey = ($vehicle['routeName'] ?? $vehicle['vehicleNumber'] ?? 'vehicle') . '|' . $studentKey;
    if (!isset($state[$vehicleKey])) {
        $state[$vehicleKey] = array(
            'route_started' => false,
            'near_stop' => false,
            'arrived_stop' => false,
            'lastSentAt' => null,
        );
    }

    $sent = array();
    $title = 'Transport Alert';
    $busLabel = (string) ($vehicle['vehicleNumber'] ?? 'Bus');
    $routeLabel = (string) ($vehicle['routeName'] ?? 'Route');
    $studentStopName = (string) ($studentStop['name'] ?? 'your stop');
    $studentDistance = isset($studentStop['distance']) && $studentStop['distance'] !== null ? (float) $studentStop['distance'] : null;
    $studentStatus = (string) ($studentStop['status'] ?? 'upcoming');

    if (!$state[$vehicleKey]['route_started'] && isset($vehicle['stops'][0]) && ($vehicle['stops'][0]['status'] ?? '') === 'departed') {
        $body = "Hi, {$busLabel} {$routeLabel} ke start point se nikal chuki hai.";
        if (parent_send_notification($taskTo, $title, $body)) {
            $state[$vehicleKey]['route_started'] = true;
            $sent[] = 'route_started';
        }
    }

    if (!$state[$vehicleKey]['near_stop'] && $studentDistance !== null && $studentDistance <= 1.0 && in_array($studentStatus, array('next', 'upcoming', 'current'), true)) {
        $body = "Hi, {$busLabel} aapke stop {$studentStopName} se lagbhag 1 km door hai.";
        if (parent_send_notification($taskTo, $title, $body)) {
            $state[$vehicleKey]['near_stop'] = true;
            $sent[] = 'near_stop';
        }
    }

    if (!$state[$vehicleKey]['arrived_stop'] && $studentStatus === 'current') {
        $body = "Hi, {$busLabel} aapke stop {$studentStopName} par pahunch gayi hai.";
        if (parent_send_notification($taskTo, $title, $body)) {
            $state[$vehicleKey]['arrived_stop'] = true;
            $sent[] = 'arrived_stop';
        }
    }

    if ($sent) {
        $state[$vehicleKey]['lastSentAt'] = date(DATE_ATOM);
        parent_write_state($state);
    }

    return $sent;
}

$sessionStudent = parent_bootstrap_student_session($host);
$studentId = (int) $sessionStudent['student_id'];
$companyId = (string) $sessionStudent['company_id'];
$route = (string) $sessionStudent['route'];
$stopName = (string) $sessionStudent['stopage'];
$stopIndex = null;
$taskTo = trim((string) ($_GET['taskto'] ?? ''));
$studentName = (string) $sessionStudent['student_name'];

if ($route === '' || ($stopName === '' && $stopIndex === null)) {
    $host->close();
    transport_json_output(array(
        'success' => false,
        'message' => 'Student route or stopage is missing.',
    ), 422);
}

$payload = transport_build_feed($host);

if (!$payload['success']) {
    $host->close();
    transport_json_output($payload, 502);
}

$mappedVehicleNumber = parent_fetch_route_vehicle($host, $route, $companyId);
$host->close();

$searchVehicleNumber = strtoupper(trim((string) ($mappedVehicleNumber ?: '')));
$vehicle = null;
foreach ($payload['data']['list'] as $item) {
    $vehicleNumber = strtoupper(trim((string) ($item['vehicleNumber'] ?? '')));
    $routeLabel = strtoupper(trim((string) ($item['routeName'] ?? '')));

    if (
        ($searchVehicleNumber !== '' && $vehicleNumber === $searchVehicleNumber)
        || $routeLabel === strtoupper(trim($route))
        || $vehicleNumber === strtoupper(trim($route))
    ) {
        $vehicle = $item;
        break;
    }
}

if (!$vehicle) {
    transport_json_output(array(
        'success' => false,
        'message' => 'Vehicle not found.',
        'debug' => array(
            'route' => $route,
            'mappedVehicle' => $mappedVehicleNumber,
        ),
    ), 404);
}

$studentStopIndex = parent_find_student_stop_index($vehicle['stops'] ?? array(), $stopName !== '' ? $stopName : null, $stopIndex !== false ? $stopIndex : null);
if ($studentStopIndex === null) {
    transport_json_output(array('success' => false, 'message' => 'Student stop not found in this route.'), 404);
}

$visibleStops = array_slice($vehicle['stops'], 0, $studentStopIndex + 1);
$studentStop = $visibleStops[$studentStopIndex];
$visiblePath = array_slice($vehicle['routePath'] ?? array(), 0, $studentStopIndex + 1);

$departedCount = count(array_filter($visibleStops, function ($stop) {
    return isset($stop['status']) && $stop['status'] === 'departed';
}));
$studentStatusText = $studentStop['status'] === 'current'
    ? 'Bus aapke stop par aa chuki hai'
    : ($studentStop['distance'] !== null ? 'Bus aapke stop ki taraf aa rahi hai' : 'Bus abhi route par hai');

$etaMinutes = $studentStop['etaMin'] ?? null;
$arrivalTime = $studentStop['arrivalTime'] ?? null;
$notifications = parent_maybe_notify(
    $vehicle,
    $studentStop,
    $taskTo,
    (string) $studentId
);

$response = array(
    'success' => true,
    'generatedAt' => date(DATE_ATOM),
    'student' => array(
        'id' => $studentId ?: null,
        'name' => $studentName,
        'stopName' => $studentStop['name'],
        'stopIndex' => $studentStopIndex + 1,
        'statusText' => $studentStatusText,
        'distanceKm' => $studentStop['distance'],
        'etaMin' => $etaMinutes,
        'arrivalTime' => $arrivalTime,
        'routeName' => $route,
        'companyId' => $companyId,
        'standard' => $sessionStudent['standard'],
        'branchName' => $sessionStudent['branch_name'],
        'debugMode' => $sessionStudent['is_debug'] ?? false,
    ),
    'vehicle' => array_merge($vehicle, array(
        'stops' => $visibleStops,
        'routePath' => $visiblePath,
        'studentStop' => $studentStop,
        'progressSummary' => array(
            'coveredStops' => $departedCount,
            'visibleStops' => count($visibleStops),
            'remainingToStudent' => max(count($visibleStops) - $departedCount - (($studentStop['status'] === 'current') ? 1 : 0), 0),
        ),
    )),
    'notifications' => $notifications,
);

transport_json_output($response, 200);
