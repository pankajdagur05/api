<?php
/**
 * transport_config.php
 * Saari transport files isi ko include karti hain.
 * subdomain.php se $host (mysqli), $user (cid/branch), $session, $subdomain aate hain.
 * School lat/lng, tokens, keys, timings — sab transport_settings table se (cid ke hisaab se).
 */
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');

// Cron / test scripts (send_bus_alerts, save_ping, sms_test) me login nahi hota,
// isliye unke liye auth.php nahi, sirf subdomain.php ($host, $user, $session) load hota hai.
if (defined('TRANSPORT_CRON')) {
    $subdomainFile = null;
    foreach ([__DIR__ . '/auth.php', __DIR__ . '/../auth.php', __DIR__ . '/../../auth.php', __DIR__ . '/../../../auth.php', __DIR__ . '/../../../../auth.php'] as $f) {
        if (is_file($f)) {
            $subdomainFile = $f;
            break;
        }
    }
    if ($subdomainFile === null) {
        http_response_code(500);
        exit("subdomain.php nahi mili — transport_config.php me sahi path likhein\n");
    }
    require_once $subdomainFile;
} else {
    require_once __DIR__ . '/auth.php';
}

// ------------------------------------------------------------------
// Sirf ye 3 values ek baar badlein
// ------------------------------------------------------------------
const TRANSPORT_SECRET = 'CHANGE-ME-long-random-secret-at-least-32-chars';
const CRON_KEY         = 'CHANGE-ME-cron-key';
const PUSH_HELPER_FILE = '/home/marudhar/edpluss.com/school/admin-panel/api/fcm_helper.php';
const PARENT_PUSH_ROLE = 'student';                              // push_tokens.role parents ke liye

// TESTING: true = parent page sirf ?sid= se khul jayega (token zaroori nahi)
// LIVE karne se pehle false kar dein, warna koi bhi sid badal kar kisi bhi bacche ki bus dekh sakta hai
const TRANSPORT_ALLOW_SID_ONLY = true;

// ---------------- SMS (par-ken gateway) ----------------
const SMS_API_URL     = 'https://sms.par-ken.com/api/smsapi';
const SMS_API_KEY     = 'f25f700f41917b39e538a898cb2114a1';
const SMS_ROUTE       = '1';
const SMS_TYPE        = '1';                    // Hindi "???" aaye to gateway se unicode type puchh kar badlein
const SMS_SENDER      = 'EDPLSA';
const SMS_TEMPLATE_ID = '1707176528141697075';  // wahi purana DLT template
// TESTING: bhara ho to SAARE SMS sirf isi number par jayenge. Live par '' kar dein.
const SMS_TEST_MOBILE = '7297870877';

// Login hua student ka id auth.php me kis naam se aata hai.
// Pehle global variable ($sid, $student_id ...) dekha jayega, phir $_SESSION['...'].
// Aapke auth.php me naam alag ho to yaha sabse upar likh dein.
const TRANSPORT_SID_KEYS = ['sid', 'student_id', 'studentid', 'stu_id', 'std_id', 'id'];

/**
 * Login hue student ka id (auth.php / session se). Na mile to ''.
 */
function transport_logged_in_sid(): string
{
    foreach (TRANSPORT_SID_KEYS as $k) {
        if ($k !== 'id' && isset($GLOBALS[$k]) && is_scalar($GLOBALS[$k]) && trim((string) $GLOBALS[$k]) !== '') {
            return trim((string) $GLOBALS[$k]);
        }
    }
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
    foreach (TRANSPORT_SID_KEYS as $k) {
        if (isset($_SESSION[$k]) && is_scalar($_SESSION[$k]) && trim((string) $_SESSION[$k]) !== '') {
            return trim((string) $_SESSION[$k]);
        }
    }
    return '';
}

if (!isset($host) || !($host instanceof mysqli)) {
    http_response_code(500);
    exit('Database connection not available');
}
$host->set_charset('utf8mb4');

if (is_file(PUSH_HELPER_FILE)) {
    require_once PUSH_HELPER_FILE;
}

function transport_db(): mysqli
{
    global $host;
    return $host;
}

function transport_cid(): string
{
    global $user;
    return trim((string) ($user ?? ''));
}

function transport_session(): string
{
    global $session;
    return trim((string) ($session ?? ''));
}

function transport_subdomain(): string
{
    global $subdomain;
    return (string) ($subdomain ?? '');
}

function transport_base_url(): string
{
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $hostName = $_SERVER['HTTP_HOST'] ?? 'edpluss.com';
    $dir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/transport/index.php')), '/');
    return ($https ? 'https' : 'http') . '://' . $hostName . $dir . '/';
}

function transport_branch_name(): string
{
    static $name = null;
    if ($name !== null) {
        return $name;
    }
    $name = transport_subdomain();
    try {
        $cid = transport_cid();
        $stmt = transport_db()->prepare("SELECT name FROM branch_category WHERE id = ? AND type = 'branch' LIMIT 1");
        $stmt->bind_param('s', $cid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row && trim((string) $row['name']) !== '') {
            $name = (string) $row['name'];
        }
    } catch (Throwable $e) {
        error_log('[transport] branch name: ' . $e->getMessage());
    }
    return $name;
}

/**
 * Current school (cid) ki settings. Row na mile to defaults.
 */
function transport_settings(): array
{
    static $cfg = null;
    if ($cfg !== null) {
        return $cfg;
    }

    $cfg = [
        'school_name'        => 'Marudhar School',
        'school_lat'         => 0.0,
        'school_lng'         => 0.0,
        'wheelseye_token'    => '618877ef-2843-4f76-bc57-52acbc9792f8',
        'google_routes_key'  => 'AIzaSyA4DcfhR1ziaFXYe6Eilip65TldRpSjSHQ',
        'google_maps_js_key' => 'AIzaSyA4DcfhR1ziaFXYe6Eilip65TldRpSjSHQ',
        'sms_key'            => 'f25f700f41917b39e538a898cb2114a1',
        'sms_sender_id'      => 'EDPLSA',
        'sms_entity_id'      => '',
        'sms_template_id'    => '1707176528141697075',
        'sms_test_mobile'    => '7297870877',
        'sms_enabled'        => 1,
        'push_enabled'       => 1,
        'pickup_start'       => '06:00',
        'pickup_end'         => '11:00',
        'drop_start'         => '12:30',
        'drop_end'           => '18:30',
        'alert_eta_min'      => 10,
        'alert_radius_km'    => 2.0,
        'on_the_way_eta_min' => 30,
        'near_stop_km'       => 0.15,
        'avg_speed_kmh'      => 22.0,
        'stop_dwell_min'     => 1.0,
        'stale_after_min'    => 10,
        'parent_show_driver' => 1,
        'road_factor'        => 1.3,  // seedhi doori -> sadak ki doori (fallback)
        'feed_cache_sec'     => 8,    // Wheelseye feed kitne sec cache ho
        'configured'         => false,
    ];

    try {
        $cid = transport_cid();
        $stmt = transport_db()->prepare('SELECT * FROM transport_settings WHERE cid = ? LIMIT 1');
        $stmt->bind_param('s', $cid);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row) {
            foreach ($row as $k => $v) {
                if ($v !== null && $v !== '' && array_key_exists($k, $cfg)) {
                    $cfg[$k] = $v;
                }
            }
            $cfg['configured'] = true;
        }
    } catch (Throwable $e) {
        error_log('[transport] settings: ' . $e->getMessage());
    }

    foreach (['school_lat', 'school_lng', 'alert_radius_km', 'near_stop_km', 'avg_speed_kmh', 'stop_dwell_min', 'road_factor'] as $k) {
        $cfg[$k] = (float) $cfg[$k];
    }
    foreach (['sms_enabled', 'push_enabled', 'alert_eta_min', 'on_the_way_eta_min', 'stale_after_min', 'parent_show_driver', 'feed_cache_sec'] as $k) {
        $cfg[$k] = (int) $cfg[$k];
    }
    foreach (['pickup_start', 'pickup_end', 'drop_start', 'drop_end'] as $k) {
        $cfg[$k] = substr((string) $cfg[$k], 0, 5);
    }
    if ($cfg['school_name'] === '') {
        $cfg['school_name'] = transport_branch_name();
    }

    return $cfg;
}

/**
 * Admin pages ke liye. Apne ERP ka login check yaha lagayein.
 */
function transport_require_admin(): void
{
    if (isset($_GET['key']) && hash_equals(CRON_KEY, (string) $_GET['key'])) {
        return;
    }
    if (session_status() === PHP_SESSION_NONE) {
        @session_start();
    }
    // TODO: apne ERP ke admin session ka check lagayein, jaise:
    // if (empty($_SESSION['admin_id'])) { http_response_code(401); exit('Login required'); }
}

function transport_require_cron(): void
{
    if (PHP_SAPI === 'cli') {
        return;
    }
    if (!hash_equals(CRON_KEY, (string) ($_GET['key'] ?? ''))) {
        http_response_code(403);
        exit('Forbidden');
    }
}