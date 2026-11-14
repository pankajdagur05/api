<?php
/**
 * test_alert.php — STANDALONE TEST (SMS + Push)
 * URL hit karte hi 1 SMS + 1 push notification jayega, aur har step ka result screen par dikhega.
 * Neeche "STATIC DATA" me apni values bharein. Test ke baad server se hata dein.
 */

// include("../subdomain.php");   // TEST: band
// include("auth.php");           // TEST: band

ini_set('display_errors', '1');
error_reporting(E_ALL);
date_default_timezone_set('Asia/Kolkata');
header('Content-Type: text/plain; charset=utf-8');

/* ======================== STATIC DATA (yaha update karein) ======================== */
$user       = 163;                     // cid / branch id
$sid        = 185521;                     // student id
$subdomain  = 'mda';
$branch_name = 'Marudhar School';

$student_name = 'Pankaj';
$stop_name    = 'बस स्टैंड';
$vno          = 'RJ14AB1234';          // bus number
$bus_color    = 'पीली';
$distance_km  = '1.5';
$eta_min      = '5';
$trackLink    = 'https://edpluss.com/transport/parent_live.php?sid=' . $sid;

// ---- SMS (par-ken) ----
$sms_mobile = '7297870877';            // SMS isi number par jayega
$sms_key    = 'f25f700f41917b39e538a898cb2114a1';
$sms_sender = 'EDPLSA';
$sms_route  = '1';
$sms_type   = '1';                     // Hindi "???" aaye to unicode wala type daalein
$tempid     = '1707176528141697075';   // purana DLT template

// ---- Push notification ----
$push_helper_file = '/home/marudhar/edpluss.com/school/admin-panel/api/fcm_helper.php'; // jis file me sendPushNotification() hai
$parent_mobile    = '7297870877';      // is mobile ke push_tokens dhoondhe jayenge (DB on ho to)
$parent_role      = 'student';          // push_tokens.role
// DB use nahi karna to FCM token seedha yaha daal dein (app ke push_tokens table se copy karke):
$static_fcm_tokens = [
     'd_ElkRxPQEOqHKFxH5_56p:APA91bECBAtLqroLzKWNNbcojvMzdxCtuinuLvMbZnB1hPV1rRqGED2HGLlzn9ArlcEQWa9OXLTA2QQdtl5rQaSg7p0whV0goEVT_J0H5-cfMH1BUDTZAZI',
];

// ---- DB (optional) — sirf FCM token push_tokens se lene ke liye ----
$use_db  = false;                      // true karein to neeche wali DB details bharein
$db_host = 'localhost';
$db_user = 'DB_USER';
$db_pass = 'DB_PASS';
$db_name = 'DB_NAME';
/* ================================================================================= */

function line($s) { echo $s . "\n"; @ob_flush(); @flush(); }

line("===== TEST ALERT | " . date('d-m-Y h:i:s A') . " =====");
line("user=$user | sid=$sid | bus=$vno | stop=$stop_name\n");

/* ------------------------------- 1) SMS ------------------------------- */
line("---------- 1) SMS ----------");
$sms = "प्रिय अभिभावक, आपके बच्चे की {$bus_color} रंग वाली बस नंबर {$vno} अभी {$distance_km} किलोमीटर दूर है और {$stop_name} स्टॉप के पास पहुँच रही है। अनुमानित आगमन समय {$eta_min} मिनट। अभी ट्रैक करें: {$trackLink} - Edplus Ai";

$url = "https://sms.par-ken.com/api/smsapi?" . http_build_query([
    'key'        => $sms_key,
    'route'      => $sms_route,
    'type'       => $sms_type,
    'sender'     => $sms_sender,
    'number'     => $sms_mobile,
    'sms'        => $sms,
    'templateid' => $tempid,
]);

line("Number : $sms_mobile");
line("SMS    : $sms");
line("Length : " . mb_strlen($sms) . " chars");

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

line("HTTP   : $httpCode");
if ($response === false) {
    line("RESULT : FAIL — cURL error: $curlErr");
} else {
    line("GATEWAY RESPONSE: " . $response);
}

/* ------------------------------ 2) PUSH ------------------------------ */
line("\n---------- 2) PUSH NOTIFICATION ----------");

if (is_file($push_helper_file)) {
    require_once $push_helper_file;
    line("Helper file : mili ($push_helper_file)");
} else {
    line("Helper file : NAHI MILI — \$push_helper_file ka path sahi karein ($push_helper_file)");
}

$tokens = $static_fcm_tokens;

if ($use_db) {
    $host = @mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$host) {
        line("DB          : connect FAIL — " . mysqli_connect_error());
    } else {
        mysqli_set_charset($host, 'utf8mb4');
        $m = mysqli_real_escape_string($host, $parent_mobile);
        $r = mysqli_real_escape_string($host, $parent_role);
        $q = mysqli_query($host, "SELECT fcm_token FROM push_tokens WHERE role='$r' AND mobile='$m'");
        if (!$q) {
            line("DB query    : FAIL — " . mysqli_error($host));
        } else {
            while ($row = mysqli_fetch_array($q)) {
                if (!empty($row['fcm_token'])) $tokens[] = $row['fcm_token'];
            }
            line("DB tokens   : " . mysqli_num_rows($q) . " row(s) role='$parent_role' mobile='$parent_mobile'");
            if (mysqli_num_rows($q) === 0) {
                // madad ke liye: is mobile ke kaun-kaun se role hain
                $q2 = mysqli_query($host, "SELECT role, COUNT(*) c FROM push_tokens WHERE mobile='$m' GROUP BY role");
                $roles = [];
                while ($q2 && ($x = mysqli_fetch_array($q2))) $roles[] = $x['role'] . '(' . $x['c'] . ')';
                line("              is mobile ke roles: " . ($roles ? implode(', ', $roles) : 'koi token nahi'));
            }
        }
    }
}

$tokens = array_values(array_unique(array_filter($tokens)));
line("Tokens      : " . count($tokens));

if (!function_exists('sendPushNotification')) {
    line("RESULT      : FAIL — sendPushNotification() function nahi mila");
} elseif (!$tokens) {
    line("RESULT      : SKIP — koi FCM token nahi. \$static_fcm_tokens me token daalein ya \$use_db = true karein");
} else {
    $title = "🚌 बस पास आ गई है - $branch_name";
    $body  = "बस $vno अभी $distance_km km दूर है, लगभग $eta_min मिनट में $stop_name पहुँचेगी। कृपया $student_name को तैयार रखें।";
    $data  = [
        "screen"      => "BusTracking",
        "role"        => "parent",
        "stage"       => "NEAR",
        "student_id"  => (string) $sid,
        "track_url"   => $trackLink,
        "vehicle"     => $vno,
        "stop"        => $stop_name,
        "eta_min"     => (string) $eta_min,
        "sub_domain"  => $subdomain,
        "branch_id"   => (string) $user,
        "branch_name" => $branch_name,
    ];
    line("Title       : $title");
    line("Body        : $body");

    ob_start();
    $ret = sendPushNotification($tokens, $title, $body, $data);
    $printed = ob_get_clean();

    line("Return      : " . (is_scalar($ret) || $ret === null ? var_export($ret, true) : json_encode($ret, JSON_UNESCAPED_UNICODE)));
    if (trim((string) $printed) !== '') {
        line("Output      : " . trim($printed));
    }
}

line("\n===== DONE =====");