<?php
/**
 * send_bus_alerts.php
 * Cron: har 1 minute
 *   * * * * * wget -q -O /dev/null "https://YOUR-DOMAIN/.../send_bus_alerts.php?key=CRON_KEY"
 *
 * Har student ko uske stop ke hisaab se 3 stage alerts (har stage din/shift me sirf ek baar):
 *   ON_THE_WAY -> push           (ETA <= on_the_way_eta_min)
 *   NEAR       -> SMS + push     (ETA <= alert_eta_min  ya  doori <= alert_radius_km)
 *   ARRIVED    -> push           (bus stop par pahunch gayi)
 *
 * TESTING params:
 *   &shift=pickup|drop   -> kisi bhi time chalayein
 *   &force=1             -> har bus ke PEHLE student ko NEAR alert bhej do (doori/ETA/log check nahi)
 *   &debug=1             -> har stop ki ETA/doori aur skip hone ki wajah dikhao
 */
declare(strict_types=1);

define('TRANSPORT_CRON', true); // config ko batata hai: login (auth.php) zaroori nahi

ini_set('display_errors', '1');
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/transport_config.php';
require_once __DIR__ . '/transport_lib.php';

transport_require_cron();

$force = !empty($_GET['force']);
$debug = !empty($_GET['debug']) || $force;

function out(string $msg): void
{
    echo $msg . "\n";
    @ob_flush();
    @flush();
}

try {
    $cfg = transport_settings();
    $cid = transport_cid();
    $sess = transport_session();
    $db = transport_db();
    $today = date('Y-m-d');

    out("== Bus alerts | " . date('d-m-Y h:i A') . " | cid=$cid | session=" . ($sess ?: '(khali)'));
    out("   SMS -> " . (SMS_TEST_MOBILE !== '' ? 'TEST number ' . SMS_TEST_MOBILE : 'parents ke number')
        . " | push helper: " . (function_exists('sendPushNotification') ? 'mila' : 'NAHI MILA (PUSH_HELPER_FILE check karein)'));
    if ($force) {
        out("   FORCE mode: har bus ke pehle student ko NEAR alert jayega");
    }

    if ($cid === '') {
        out("STOP: cid (\$user) khali hai — subdomain/auth se school id nahi aayi");
        exit;
    }

    $shift = current_shift($cfg, isset($_GET['shift']) ? (string) $_GET['shift'] : null);
    if ($shift === null) {
        out("STOP: abhi pickup ({$cfg['pickup_start']}-{$cfg['pickup_end']}) / drop ({$cfg['drop_start']}-{$cfg['drop_end']}) ka time nahi.");
        out("      Testing ke liye URL me &shift=pickup ya &shift=drop lagayein.");
        exit;
    }
    out("   Shift: $shift");

    // ---------------- Holiday check (holidays table ho to) ----------------
    try {
        $h = $db->prepare('SELECT id FROM holidays WHERE date = ? AND cid = ? LIMIT 1');
        $h->bind_param('ss', $today, $cid);
        $h->execute();
        if ($h->get_result()->fetch_assoc() && !$force) {
            out("STOP: aaj holiday hai");
            exit;
        }
        $h->close();
    } catch (Throwable $e) {
        // holidays table nahi hai to ignore
    }

    // ---------------- Tables check ----------------
    try {
        $db->query('SELECT 1 FROM bus_alert_log LIMIT 1');
    } catch (Throwable $e) {
        out("STOP: bus_alert_log table nahi mili — install.sql chalayein. (" . $e->getMessage() . ")");
        exit;
    }

    try {
        $feed = build_live_feed($shift, false);
    } catch (Throwable $e) {
        out("STOP: live GPS feed nahi mila — " . $e->getMessage());
        exit;
    }
    out("   Feed: " . count($feed['list']) . " vehicles\n");

    $branchName = transport_branch_name();

    $stuSql = "SELECT s.id, s.name, s.smobile, s.stopage
               FROM students s
               INNER JOIN route_stopage rs ON LOWER(TRIM(rs.name)) = LOWER(TRIM(s.stopage)) AND rs.cid = s.cid
               WHERE UPPER(TRIM(rs.route)) = UPPER(TRIM(?)) AND s.cid = ?
                 AND (s.status IN ('Active','active') OR s.status IS NULL)";
    if ($sess !== '') {
        $stuSql .= ' AND s.session = ?';
    }
    $stuStmt = $db->prepare($stuSql);

    $logStmt = $db->prepare(
        'INSERT IGNORE INTO bus_alert_log (cid, student_id, alert_date, shift, stage, stop_name, vehicle_no, eta_min, distance_km, created_at)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())'
    );
    $logUpd = $db->prepare('UPDATE bus_alert_log SET sms_sent = ?, push_count = ? WHERE cid = ? AND student_id = ? AND alert_date = ? AND shift = ? AND stage = ?');

    $STAGE_RANK = ['ON_THE_WAY' => 1, 'NEAR' => 2, 'ARRIVED' => 3];

    /** true = naya stage (pehle nahi bheja) */
    $claim = static function (string $sid, string $stage, string $stop, string $vno, ?int $eta, ?float $km) use ($logStmt, $cid, $today, $shift): bool {
        $eta = $eta ?? 0;
        $km = $km ?? 0.0;
        $logStmt->bind_param('sssssssid', $cid, $sid, $today, $shift, $stage, $stop, $vno, $eta, $km);
        $logStmt->execute();
        return $logStmt->affected_rows === 1;
    };

    $sentSmsThisRun = [];
    $summary = ['buses' => 0, 'alerts' => 0, 'sms' => 0, 'push' => 0];

    foreach ($feed['list'] as $bus) {
        $vno = (string) ($bus['vehicleNumber'] ?? '?');

        if (empty($bus['mapped'])) {
            if ($debug) out("-- $vno: SKIP — kisi route se mapped nahi (route.vechile)");
            continue;
        }
        $routeName = (string) $bus['routeName'];
        if (empty($bus['stops'])) {
            out("-- $vno ($routeName): SKIP — route ke stops nahi mile");
            continue;
        }
        if ((float) ($bus['latitude'] ?? 0) == 0.0) {
            out("-- $vno ($routeName): SKIP — GPS location 0,0");
            continue;
        }
        if (!empty($bus['isStale']) && !$force) {
            out("-- $vno ($routeName): SKIP — GPS {$bus['gpsAgeMin']} min purana (stale)");
            continue;
        }
        $summary['buses']++;
        out("-- $vno ($routeName): " . count($bus['stops']) . " stops"
            . ($bus['currentStop'] ? " | abhi: {$bus['currentStop']['name']}" : '')
            . ($bus['nextStop'] ? " | next: {$bus['nextStop']['name']} {$bus['nextStop']['distance']}km {$bus['nextStop']['etaMin']}min" : ''));

        $busColor = ($bus['busColor'] ?? '') !== '' ? (string) $bus['busColor'] : 'पीली';

        // Is route ke saare students, stop ke hisaab se group
        if ($sess !== '') {
            $stuStmt->bind_param('sss', $routeName, $cid, $sess);
        } else {
            $stuStmt->bind_param('ss', $routeName, $cid);
        }
        $stuStmt->execute();
        $res = $stuStmt->get_result();
        $byStop = [];
        $stuCount = 0;
        while ($row = $res->fetch_assoc()) {
            $byStop[stop_key($row['stopage'])][] = $row;
            $stuCount++;
        }
        if (!$byStop) {
            out("   SKIP — is route ke stops par koi active student nahi (students.stopage / session check karein)");
            continue;
        }
        out("   $stuCount students, " . count($byStop) . " stops par");

        $forcedDone = false;
        foreach ($bus['stops'] as $stop) {
            $stopName = (string) $stop['name'];
            $students = $byStop[stop_key($stopName)] ?? [];
            $eta = $stop['etaMin'] !== null ? (int) $stop['etaMin'] : null;
            $km = $stop['distance'] !== null ? (float) $stop['distance'] : null;
            $info = "   [$stopName] status={$stop['status']} km=" . ($km ?? '-') . " eta=" . ($eta ?? '-') . " students=" . count($students);

            if (!$students) {
                if ($debug) out($info . " -> koi student nahi");
                continue;
            }

            if ($force && !$forcedDone) {
                $stage = 'NEAR';
                $eta = $eta ?? 5;
                $km = $km ?? 1.0;
            } elseif ($stop['status'] === 'departed') {
                if ($debug) out($info . " -> bus nikal chuki");
                continue;
            } elseif ($stop['status'] === 'current') {
                $stage = 'ARRIVED';
            } elseif ($eta !== null && ($eta <= $cfg['alert_eta_min'] || ($km !== null && $km > 0 && $km <= $cfg['alert_radius_km']))) {
                $stage = 'NEAR';
            } elseif ($eta !== null && $eta <= $cfg['on_the_way_eta_min']) {
                $stage = 'ON_THE_WAY';
            } else {
                if ($debug) out($info . " -> abhi door hai (alert {$cfg['on_the_way_eta_min']} min / {$cfg['alert_eta_min']} min / {$cfg['alert_radius_km']} km par)");
                continue;
            }
            out($info . " -> $stage");

            $kmTxt = $km !== null ? number_format($km, 1) : '0';
            $etaTxt = (string) ($eta ?? 0);

            foreach ($students as $stu) {
                if ($force && $forcedDone) {
                    break;
                }
                $sid = (string) $stu['id'];
                $name = (string) $stu['name'];

                if (!$force) {
                    if (!$claim($sid, $stage, $stopName, $vno, $eta, $km)) {
                        if ($debug) out("      $name: $stage pehle hi bhej chuke (aaj)");
                        continue;
                    }
                    // Neeche wale stages bhi block (GPS jitter se ulta alert na jaye)
                    foreach ($STAGE_RANK as $s => $rank) {
                        if ($rank < $STAGE_RANK[$stage]) {
                            $claim($sid, $s, $stopName, $vno, $eta, $km);
                        }
                    }
                } else {
                    $claim($sid, $stage, $stopName, $vno, $eta, $km); // force me bhi log me likho
                    $forcedDone = true;
                }
                $summary['alerts']++;

                $parentMobile = normalize_mobile($stu['smobile'] ?? '');
                $smsMobile = sms_target_mobile($parentMobile);
                $link = track_url($sid);

                // ---------- Push text ----------
                if ($stage === 'ARRIVED') {
                    $title = "🚌 बस स्टॉप पर पहुँच गई - $branchName";
                    $body = $shift === 'DROP'
                        ? "बस $vno $stopName स्टॉप पर पहुँच गई है। कृपया $name को रिसीव करें।"
                        : "बस $vno $stopName स्टॉप पर पहुँच गई है। कृपया $name को बस में बैठाएँ।";
                } elseif ($stage === 'NEAR') {
                    $title = "🚌 बस पास आ गई है - $branchName";
                    $body = "बस $vno अभी $kmTxt km दूर है, लगभग $etaTxt मिनट में $stopName पहुँचेगी। "
                        . ($shift === 'DROP' ? 'कृपया स्टॉप पर पहुँचें।' : "कृपया $name को तैयार रखें।");
                } else {
                    $title = "🚌 बस रास्ते में है - $branchName";
                    $body = "$name की बस $vno लगभग $etaTxt मिनट में $stopName पहुँचेगी।";
                }

                // ---------- Push ----------
                $pushCount = 0;
                if ($cfg['push_enabled'] && $parentMobile !== '') {
                    $pushCount = send_parent_push($parentMobile, $title, $body, [
                        'screen'      => 'BusTracking',
                        'role'        => 'parent',
                        'stage'       => $stage,
                        'shift'       => $shift,
                        'student_id'  => $sid,
                        'track_url'   => $link,
                        'vehicle'     => $vno,
                        'stop'        => $stopName,
                        'eta_min'     => $etaTxt,
                        'sub_domain'  => transport_subdomain(),
                        'branch_id'   => $cid,
                        'branch_name' => $branchName,
                    ]);
                    $summary['push'] += $pushCount;
                }
                $pushNote = $parentMobile === ''
                    ? 'student ka smobile khali/galat'
                    : ($pushCount ? "$pushCount device" : "0 device (push_tokens me role='" . PARENT_PUSH_ROLE . "' mobile=$parentMobile nahi)");

                // ---------- SMS (sirf NEAR) ----------
                $smsSent = 0;
                $smsNote = 'sirf NEAR par jata hai';
                if ($stage === 'NEAR') {
                    $smsKey = $smsMobile . '|' . $stopName;
                    if (!$cfg['sms_enabled']) {
                        $smsNote = 'sms_enabled = 0';
                    } elseif ($smsMobile === '') {
                        $smsNote = 'mobile number nahi';
                    } elseif (isset($sentSmsThisRun[$smsKey])) {
                        $smsNote = 'is run me isi number par bhej chuke';
                    } else {
                        $sms = build_bus_sms($busColor, $vno, $kmTxt, $stopName, $etaTxt, $link);
                        $r = send_sms($cfg, $smsMobile, $sms);
                        $smsSent = $r['ok'] ? 1 : 0;
                        $sentSmsThisRun[$smsKey] = true;
                        $summary['sms'] += $smsSent;
                        $smsNote = "$smsMobile -> gateway: " . mb_substr(trim($r['response']), 0, 150);
                    }
                }

                $logUpd->bind_param('iisssss', $smsSent, $pushCount, $cid, $sid, $today, $shift, $stage);
                $logUpd->execute();

                out("      $name (id $sid): $stage | push: $pushNote | sms: $smsNote");
            }
        }
    }

    out("\nDone. Buses: {$summary['buses']}, Alerts: {$summary['alerts']}, SMS: {$summary['sms']}, Push: {$summary['push']}");
} catch (Throwable $e) {
    out("ERROR: " . $e->getMessage() . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')');
}