<?php

include("db.php");
session_start();

date_default_timezone_set('Asia/Kolkata');
$date = date('Y-m-d');
$time = date('H:i:s');

/* ===============================
   READ HEADERS FROM WEBVIEW
================================ */
$header_username   = $_SERVER['HTTP_USERNAME']   ?? null;
$header_token      = $_SERVER['HTTP_TOKEN']      ?? null;
$header_sid        = $_SERVER['HTTP_ID']         ?? null;

/* ===============================
   FORCE SESSION RESET ON NEW REQUEST
================================ */
if ($header_username && $header_sid) {

    session_unset();
    session_destroy();
    session_start();

    $_SESSION['username']  = $header_username;
    $_SESSION['token']     = $header_token;
    $_SESSION['sid']       = $header_sid;

    session_regenerate_id(true);
}

/* ===============================
   FINAL SESSION VALUES
================================ */
$username = $_SESSION['username'] ?? null;
$token    = $_SESSION['token']    ?? null;
$sid      = $_SESSION['sid']      ?? null;

if (!$username || !$token) {
    echo "<h3 style='color:red;padding:20px;'>Access Denied</h3>";
    exit;
}

$usernameE = mysqli_real_escape_string($host, (string)$username);
$tokenE    = mysqli_real_escape_string($host, (string)$token);
$sidE      = (int)$sid;

/* ===============================
   TOKEN VALIDATION (ONLY CHECK)
================================ */
$valid = mysqli_query($host, "SELECT id FROM apk_token WHERE user='$usernameE' AND token='$tokenE' LIMIT 1");

if (!$valid || mysqli_num_rows($valid) == 0) {
    session_destroy();
    echo "<h3 style='color:red;padding:20px;'>Invalid Token</h3>";
    exit;
}

/* ===============================
   STUDENT (PARENT LOGIN)
================================ */
$q = mysqli_query($host, "SELECT * FROM students WHERE smobile='$usernameE' AND id='$sidE'");

if (!$q || mysqli_num_rows($q) == 0) {
    session_destroy();
    exit('No Student Found');
}

$u = mysqli_fetch_assoc($q);

$_SESSION['id']        = $u['id'];
$_SESSION['name']      = $u['name'];
$_SESSION['username']  = $username;
$_SESSION['token']     = $token;
$_SESSION['type']      = 'other';
$_SESSION['branch']    = $u['cid'];

$cidTmp = mysqli_real_escape_string($host, (string)$_SESSION['branch']);

$s  = mysqli_query($host, "SELECT name FROM session WHERE cid='$cidTmp' ORDER BY id DESC LIMIT 1");
$sd = $s ? mysqli_fetch_assoc($s) : null;
$_SESSION['session'] = $sd['name'] ?? '';

$bq = mysqli_query($host, "SELECT iname FROM branch_category WHERE id='$cidTmp'");
$bd = $bq ? mysqli_fetch_assoc($bq) : null;
$_SESSION['branch_name'] = $bd['iname'] ?? null;

/* ===============================
   FINAL VARIABLES (USE EVERYWHERE)
================================ */
$branch      = $_SESSION['branch'];
$type        = $_SESSION['type'];
$sid         = (int)$_SESSION['id'];
$ssname      = $_SESSION['name'];
$username    = $_SESSION['username'];
$branch_name = $_SESSION['branch_name'];
$user        = $branch;
$userE       = mysqli_real_escape_string($host, (string)$user);

/* ===============================
   SETTINGS
================================ */
/* Folder of student photos (as seen from THIS page).
   Admin panel shows photos from "access/upload/<file>". Change if photo doesn't show. */
$PHOTO_BASE = 'access/upload/';

/* Optional quick links shown at the bottom. Add only pages that work in the parent app.
   Example: array('label' => 'Fees', 'icon' => 'fa-wallet', 'url' => 'fees.php', 'color' => 'orange') */
$QUICK_LINKS = array(
    // array('label' => 'Attendance', 'icon' => 'fa-calendar-check', 'url' => 'attendance_record.php', 'color' => 'green'),
    // array('label' => 'Fees',       'icon' => 'fa-wallet',         'url' => 'fees.php',              'color' => 'orange'),
    // array('label' => 'Major Exam', 'icon' => 'fa-file-signature', 'url' => 'major_exam.php',        'color' => 'blue'),
    // array('label' => 'Minor Exam', 'icon' => 'fa-file-lines',     'url' => 'minor_exam.php',        'color' => 'purple'),
);

/* ===============================
   HELPERS
================================ */
function pd_rows($host, $sql){ $o = array(); try { $r = mysqli_query($host, $sql); if ($r) { while ($x = mysqli_fetch_assoc($r)) $o[] = $x; } } catch (Throwable $e) {} return $o; }
function pd_row($host, $sql){ $r = pd_rows($host, $sql); return $r ? $r[0] : array(); }
function pd_inr($n){
    $n = (int)round((float)$n); $neg = $n < 0; $s = (string)abs($n);
    if (strlen($s) > 3) { $last = substr($s, -3); $rest = substr($s, 0, -3); $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest); $s = $rest.','.$last; }
    return ($neg ? '-' : '').$s;
}
function pd_pct($a, $b){ $b = (float)$b; return $b > 0 ? (int)round((float)$a * 100 / $b) : 0; }

/* ===============================
   DASHBOARD DYNAMIC DATA
================================ */
$standard  = isset($u['standard']) ? $u['standard'] : '';
$standardE = mysqli_real_escape_string($host, (string)$standard);
$stream    = isset($u['stream']) ? $u['stream'] : '';
$rollNo    = isset($u['rollno']) && $u['rollno'] !== '' ? $u['rollno'] : null;
$srNo      = isset($u['stu_id']) && $u['stu_id'] !== '' ? $u['stu_id'] : null;
$fatherName = isset($u['fname']) ? $u['fname'] : '';
$firstName = trim(explode(' ', trim((string)$ssname))[0]);
$session   = $_SESSION['session'];

/* ---- Photo: latest docs photo -> students.photo ---- */
$photo = null;
$ph = pd_row($host, "SELECT filename FROM docs WHERE cid='$userE' AND sid='$sid' AND LOWER(name)='photo' AND filename<>'' ORDER BY id DESC LIMIT 1");
if (!empty($ph['filename'])) $photo = $PHOTO_BASE.$ph['filename'];
elseif (!empty($u['photo']))  $photo = $PHOTO_BASE.$u['photo'];

/* ---- Greeting ---- */
$hr = (int)date('G');
$greeting = $hr < 12 ? 'Good Morning' : ($hr < 17 ? 'Good Afternoon' : 'Good Evening');

/* ---- Attendance: today ---- */
$todayAtt = pd_row($host, "SELECT status FROM attendance WHERE sid='$sid' AND subdomain='$userE' AND DATE(date)='$date' ORDER BY id DESC LIMIT 1");
$todayStatus = isset($todayAtt['status']) ? strtoupper(trim($todayAtt['status'])) : '';
$todayMap = array(
    'P' => array('Present today',  'fa-circle-check',       'green'),
    'A' => array('Absent today',   'fa-circle-xmark',       'red'),
    'L' => array('On leave today', 'fa-umbrella-beach',     'amber'),
    ''  => array('Not marked yet', 'fa-hourglass-half',     'gray'),
);
$todayInfo = isset($todayMap[$todayStatus]) ? $todayMap[$todayStatus] : $todayMap[''];

/* ---- Attendance: current month (existing helper) ---- */
include('attendance_helper.php');
$currentMonth = (int)date('n');
$currentYear  = (int)date('Y');
$attData = getMonthAttendance($host, $user, $sid, $currentMonth, $currentYear);
$attendancePercent = (int)$attData['percentage'];
$attPresent = (int)$attData['present'];
$attAbsent  = (int)$attData['absent'];
$attLeave   = (int)$attData['leave'];
$attMonthTotal = $attPresent + $attAbsent + $attLeave;
$ringCircumference = 2 * M_PI * 46;
$ringOffset = $ringCircumference - (min(100, max(0, $attendancePercent)) / 100) * $ringCircumference;

/* ---- Attendance: whole session ---- */
$attAll = pd_row($host, "SELECT COUNT(*) t, SUM(status='P') p FROM attendance WHERE sid='$sid' AND subdomain='$userE'");
$attSessionTotal = (int)($attAll['t'] ?? 0);
$attSessionPct   = pd_pct($attAll['p'] ?? 0, $attSessionTotal);

if (!$attMonthTotal)            { $attendanceNote = 'Attendance not marked yet this month.'; $attTone = 'gray'; }
elseif ($attendancePercent >= 90) { $attendanceNote = 'Excellent attendance! Keep it up.';   $attTone = 'green'; }
elseif ($attendancePercent >= 75) { $attendanceNote = 'Good attendance. Keep it up.';         $attTone = 'green'; }
else                              { $attendanceNote = 'Attendance is low. Please send your child daily.'; $attTone = 'red'; }

/* ---- Homework: today + upcoming ---- */
$todayHomework = pd_rows($host, "SELECT * FROM notes WHERE cid='$userE' AND standard='$standardE' AND type='homework' AND date='$date' ORDER BY id DESC");
$homeworkCount = count($todayHomework);

$upcomingHomework = array();
foreach (pd_rows($host, "SELECT * FROM notes WHERE cid='$userE' AND standard='$standardE' AND type='homework' AND date>'$date' ORDER BY date ASC LIMIT 5") as $uh) {
    $upcomingHomework[] = array(
        'title'    => $uh['title'],
        'subtitle' => !empty($uh['subject']) ? $uh['subject'] : ($uh['chapter'] ?? ''),
        'date'     => $uh['date'],
    );
}

/* ---- Fees ---- */
$fee = pd_row($host, "SELECT SUM(debit) due, SUM(credit) paid,
                             SUM(IF(rdate<='$date', debit, 0)) due_now,
                             SUM(IF(rdate<='$date', credit, 0)) paid_now
                      FROM feesdue WHERE sid='$sid' AND cid='$userE' AND cancel='No'");
$feeTotal   = (float)($fee['due'] ?? 0);
$feePaid    = (float)($fee['paid'] ?? 0);
$feeBalance = max(0, $feeTotal - $feePaid);
$feeOverdue = max(0, (float)($fee['due_now'] ?? 0) - (float)($fee['paid_now'] ?? 0));
$feePaidPct = $feeTotal > 0 ? min(100, pd_pct($feePaid, $feeTotal)) : 0;

$nextDue = pd_row($host, "SELECT MIN(rdate) d FROM feesdue WHERE sid='$sid' AND cid='$userE' AND cancel='No' AND voucher='due' AND rdate>'$date'");
$nextDueDate = !empty($nextDue['d']) ? $nextDue['d'] : null;
$nextDueAmt  = 0;
if ($nextDueDate) {
    $nd = pd_row($host, "SELECT SUM(debit) a FROM feesdue WHERE sid='$sid' AND cid='$userE' AND cancel='No' AND voucher='due' AND rdate='$nextDueDate'");
    $nextDueAmt = (float)($nd['a'] ?? 0);
    if ($feeBalance <= 0) $nextDueDate = null;   // everything already paid in advance
}

/* ---- Latest exam result (absent subjects not counted) ---- */
$latestExam = null;
$lx = pd_row($host, "SELECT exam, MAX(id) mx FROM uploadresult WHERE sid='$sid' AND cid='$userE' AND TRIM(obtain)<>'' GROUP BY exam ORDER BY mx DESC LIMIT 1");
if (!empty($lx['exam'])) {
    $exE = mysqli_real_escape_string($host, $lx['exam']);
    $subs = array(); $ob = 0; $ou = 0; $abs = 0; $seen = array();
    foreach (pd_rows($host, "SELECT subject, obtain, outoff FROM uploadresult WHERE sid='$sid' AND cid='$userE' AND exam='$exE' AND TRIM(obtain)<>'' ORDER BY id ASC") as $r) {
        if (isset($seen[$r['subject']])) continue; $seen[$r['subject']] = 1;
        $o = trim((string)$r['obtain']); $isAbs = !is_numeric($o);
        $subs[] = array('subject' => $r['subject'], 'obtain' => $o, 'outoff' => (float)$r['outoff'], 'absent' => $isAbs);
        if ($isAbs) { $abs++; continue; }
        $ob += (float)$o; $ou += (float)$r['outoff'];
    }
    $latestExam = array('name' => $lx['exam'], 'subjects' => $subs, 'obtain' => $ob, 'outoff' => $ou, 'absent' => $abs, 'pct' => pd_pct($ob, $ou));
}

/* ---- Upcoming list (homework + next fee date) ---- */
$upcomingItems = array();
if ($nextDueDate) $upcomingItems[] = array('kind' => 'fee', 'title' => 'Fee due: ₹'.pd_inr($nextDueAmt), 'subtitle' => 'Next installment', 'date' => $nextDueDate);
foreach ($upcomingHomework as $h) $upcomingItems[] = array_merge($h, array('kind' => 'homework'));
usort($upcomingItems, function($a, $b){ return strcmp($a['date'], $b['date']); });
$upcomingItems = array_slice($upcomingItems, 0, 5);
?>
<?php
function pd_e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function pd_tone($p){ return $p >= 75 ? 'green' : ($p >= 50 ? 'amber' : 'red'); }
function pd_fmt($v){ $v = (float)$v; return $v == floor($v) ? (string)(int)$v : rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.'); }
$initials = strtoupper(substr($firstName, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<meta name="theme-color" content="#f5f7fb">
<title>Parent Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
:root{
  --bg:#f5f7fb; --card:#fff; --ink:#0f172a; --muted:#64748b; --faint:#94a3b8; --line:#edf0f6; --soft:#f8fafc;
  --brand:#4f46e5; --brand-soft:#eef2ff;
  --green:#16a34a; --green-soft:#ecfdf3;
  --red:#dc2626;   --red-soft:#fef2f2;
  --amber:#d97706; --amber-soft:#fffbeb;
  --orange:#ea580c;--orange-soft:#fff4ec;
  --purple:#7c3aed;--purple-soft:#f5f3ff;
  --blue:#2563eb;  --blue-soft:#eff6ff;
  --gray:#64748b;  --gray-soft:#f1f5f9;
  --sh:0 1px 2px rgba(15,23,42,.04),0 8px 24px rgba(15,23,42,.06);
}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{margin:0;padding:0;max-width:100%;overflow-x:hidden;-webkit-text-size-adjust:100%}
body{font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--ink);font-size:14px;line-height:1.45;-webkit-font-smoothing:antialiased}
.wrap{max-width:520px;margin:0 auto;padding:7px 14px calc(28px + env(safe-area-inset-bottom));padding-top:calc(10px + env(safe-area-inset-top))}
.c-green{color:var(--green)} .c-red{color:var(--red)} .c-amber{color:var(--amber)} .c-gray{color:var(--gray)} .c-orange{color:var(--orange)} .c-purple{color:var(--purple)} .c-blue{color:var(--blue)} .c-brand{color:var(--brand)}
.bg-green{background:var(--green-soft)} .bg-red{background:var(--red-soft)} .bg-amber{background:var(--amber-soft)} .bg-gray{background:var(--gray-soft)} .bg-orange{background:var(--orange-soft)} .bg-purple{background:var(--purple-soft)} .bg-blue{background:var(--blue-soft)} .bg-brand{background:var(--brand-soft)}
.f-green{background:#22c55e} .f-amber{background:#f59e0b} .f-red{background:#ef4444} .f-gray{background:#cbd5e1}

/* header */
.top{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:14px}
.top .school{font-size:12px;font-weight:700;color:var(--brand);text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.top h1{margin:2px 0 0;font-size:21px;font-weight:800;letter-spacing:-.02em}
.top .date{font-size:12.5px;color:var(--muted);margin-top:2px}
.top .bell{width:42px;height:42px;border-radius:13px;background:#fff;box-shadow:var(--sh);display:grid;place-items:center;color:var(--brand);font-size:16px;flex-shrink:0}

/* child card */
.child{position:relative;border-radius:22px;padding:16px;color:#fff;overflow:hidden;margin-bottom:12px;
  background:linear-gradient(135deg,#6366f1 0%,#4f46e5 55%,#7c3aed 100%);box-shadow:0 12px 28px rgba(79,70,229,.28)}
.child:before{content:"";position:absolute;right:-40px;top:-40px;width:160px;height:160px;border-radius:50%;background:rgba(255,255,255,.08)}
.child:after{content:"";position:absolute;right:30px;bottom:-60px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06)}
.child-top{position:relative;z-index:1;display:flex;gap:14px;align-items:center}
.ph{position:relative;width:66px;height:66px;border-radius:20px;background:rgba(255,255,255,.18);border:2px solid rgba(255,255,255,.5);display:grid;place-items:center;font-size:24px;font-weight:800;overflow:hidden;flex-shrink:0}
.ph img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.child-info{min-width:0;flex:1}
.child-info .nm{font-size:18px;font-weight:800;line-height:1.2;overflow-wrap:anywhere}
.child-info .cl{font-size:13px;opacity:.9;margin-top:3px;font-weight:600}
.child-tags{position:relative;z-index:1;display:flex;flex-wrap:wrap;gap:6px;margin-top:12px}
.child-tags span{background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.22);border-radius:99px;padding:4px 10px;font-size:11.5px;font-weight:600;white-space:nowrap}
.child-tags i{margin-right:4px;opacity:.85}

/* today strip */
.today{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin-bottom:12px}
.tday{background:#fff;border-radius:16px;box-shadow:var(--sh);padding:12px 8px;text-align:center;min-width:0}
.tday .ic{width:38px;height:38px;border-radius:12px;display:grid;place-items:center;margin:0 auto 8px;font-size:16px}
.tday .lb{font-size:10.5px;color:var(--muted);font-weight:700;text-transform:uppercase;letter-spacing:.04em}
.tday .vl{font-size:13.5px;font-weight:800;margin-top:2px;overflow-wrap:anywhere;line-height:1.25}

/* alert */
.alert{display:flex;gap:12px;align-items:center;border-radius:18px;padding:14px;margin-bottom:12px;background:linear-gradient(135deg,#fff1f2,#fee2e2);border:1px solid #fecaca}
.alert .ic{width:42px;height:42px;border-radius:13px;background:#ef4444;color:#fff;display:grid;place-items:center;font-size:17px;flex-shrink:0;box-shadow:0 6px 14px rgba(239,68,68,.3)}
.alert b{display:block;font-size:15px;color:#b91c1c}
.alert span{font-size:12.5px;color:#7f1d1d}
.alert.ok{background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0}
.alert.ok .ic{background:#22c55e;box-shadow:0 6px 14px rgba(34,197,94,.3)}
.alert.ok b{color:#15803d} .alert.ok span{color:#14532d}

/* cards */
.card{background:#fff;border-radius:20px;box-shadow:var(--sh);padding:16px;margin-bottom:12px}
.card-h{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:14px}
.card-h h3{margin:0;font-size:15.5px;font-weight:800;display:flex;align-items:center;gap:9px}
.card-h h3 .hi{width:30px;height:30px;border-radius:9px;display:grid;place-items:center;font-size:13px}
.card-h small{font-size:12px;color:var(--muted);font-weight:600}
.pill{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:99px;font-size:11.5px;font-weight:700;white-space:nowrap}

/* fees */
.fee-big{display:flex;align-items:flex-end;justify-content:space-between;gap:10px}
.fee-big .k{font-size:12px;color:var(--muted);font-weight:600}
.fee-big .v{font-size:26px;font-weight:800;letter-spacing:-.02em;line-height:1.1}
.bar{height:9px;border-radius:99px;background:#eef1f6;overflow:hidden;margin:12px 0 6px}
.bar span{display:block;height:100%;border-radius:99px}
.bar-l{display:flex;justify-content:space-between;font-size:11.5px;color:var(--muted);font-weight:600}
.fee-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:8px;margin-top:14px}
.fee-grid div{background:var(--soft);border-radius:12px;padding:9px 6px;text-align:center;min-width:0}
.fee-grid span{display:block;font-size:10.5px;color:var(--faint);font-weight:700;text-transform:uppercase;letter-spacing:.04em}
.fee-grid b{display:block;font-size:13.5px;font-weight:800;margin-top:2px;overflow-wrap:anywhere}
.next-due{display:flex;align-items:center;gap:10px;margin-top:12px;padding:10px 12px;border-radius:12px;background:var(--blue-soft);font-size:12.5px;color:#1e3a8a}
.next-due i{color:var(--blue)}

/* attendance */
.att{display:flex;align-items:center;gap:14px}
.ring{position:relative;width:110px;height:110px;flex-shrink:0}
.ring svg{transform:rotate(-90deg)}
.ring circle{fill:none;stroke-width:11;stroke-linecap:round}
.ring .tr{stroke:#eef1f6}
.ring-c{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.ring-c b{font-size:24px;font-weight:800;line-height:1}
.ring-c small{font-size:11px;color:var(--muted);margin-top:2px}
.att-chips{flex:1;display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:6px;min-width:0}
.att-chips div{border-radius:12px;padding:9px 4px;text-align:center}
.att-chips b{display:block;font-size:18px;font-weight:800;line-height:1.1}
.att-chips span{font-size:10.5px;color:var(--muted);font-weight:600}
.note{display:flex;gap:8px;align-items:center;margin-top:12px;padding:10px 12px;border-radius:12px;font-size:12.5px;font-weight:600}
.att-sess{margin-top:8px;font-size:12px;color:var(--muted);display:flex;justify-content:space-between}

/* lists */
.li{display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--line)}
.li:last-child{border-bottom:0;padding-bottom:0}
.li:first-child{padding-top:0}
.li .ic{width:40px;height:40px;border-radius:12px;display:grid;place-items:center;font-size:15px;flex-shrink:0}
.li .bd{flex:1;min-width:0}
.li .bd b{display:block;font-size:13.5px;font-weight:700;overflow-wrap:anywhere}
.li .bd small{display:block;font-size:12px;color:var(--muted);overflow-wrap:anywhere}
.li .dt{text-align:center;flex-shrink:0;min-width:42px;border-radius:10px;padding:4px 6px}
.li .dt b{display:block;font-size:16px;font-weight:800;line-height:1.1}
.li .dt span{font-size:10.5px;font-weight:700;text-transform:uppercase}
.hw-desc{font-size:12.5px;color:var(--ink);margin-top:4px;overflow-wrap:anywhere}
.empty{text-align:center;padding:16px 10px;color:var(--faint);font-size:13px}
.empty i{display:block;font-size:24px;margin-bottom:6px;color:#cbd5e1}

/* result */
.res-top{display:flex;align-items:center;gap:12px;margin-bottom:12px}
.res-score{width:64px;height:64px;border-radius:18px;display:grid;place-items:center;flex-shrink:0;text-align:center}
.res-score b{display:block;font-size:20px;font-weight:800;line-height:1}
.res-score small{font-size:10px;font-weight:700}
.res-top .t{min-width:0;flex:1}
.res-top .t b{display:block;font-size:15px;overflow-wrap:anywhere}
.res-top .t small{font-size:12px;color:var(--muted)}
.sub{padding:8px 0;border-bottom:1px dashed var(--line)}
.sub:last-child{border-bottom:0}
.sub-h{display:flex;justify-content:space-between;gap:10px;font-size:13px}
.sub-h span:first-child{min-width:0;overflow-wrap:anywhere;font-weight:600}
.sub-h b{white-space:nowrap}
.sub .bar{height:6px;margin:6px 0 0}

/* quick links */
.ql{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:8px}
.ql a{text-decoration:none;color:var(--ink);background:#fff;border-radius:16px;box-shadow:var(--sh);padding:12px 4px;text-align:center;min-width:0}
.ql .ic{width:38px;height:38px;border-radius:12px;display:grid;place-items:center;margin:0 auto 6px;font-size:15px}
.ql span{font-size:11px;font-weight:700;display:block;overflow-wrap:anywhere}

.foot{text-align:center;font-size:11.5px;color:var(--faint);margin-top:6px}

@media (max-width:360px){
  .wrap{padding-left:10px;padding-right:10px}
  .top h1{font-size:18px}
  .ph{width:56px;height:56px;border-radius:16px;font-size:20px}
  .child-info .nm{font-size:16px}
  .ring{width:92px;height:92px} .ring svg{width:92px;height:92px}
  .ring-c b{font-size:20px}
  .att{gap:10px}
  .att-chips b{font-size:15px}
  .fee-big .v{font-size:22px}
  .tday .vl{font-size:12px}
  .card{padding:14px}
}
</style>
</head>
<body>
<div class="wrap">

  <!-- ============ HEADER ============ -->
  <div class="top">
    <div style="min-width:0">
      <h1><?php echo $greeting; ?> 👋</h1>
      <div class="date"><?php echo date('l, d F Y'); ?></div>
    </div>
    <div class="bell"><i class="fas fa-graduation-cap"></i></div>
  </div>

  <!-- ============ TODAY ============ -->
  <div class="today">
    <div class="tday">
      <div class="ic bg-<?php echo $todayInfo[2]; ?> c-<?php echo $todayInfo[2]; ?>"><i class="fas <?php echo $todayInfo[1]; ?>"></i></div>
      <div class="lb">Today</div>
      <div class="vl c-<?php echo $todayInfo[2]; ?>"><?php echo $todayInfo[0]; ?></div>
    </div>
    <div class="tday">
      <div class="ic bg-purple c-purple"><i class="fas fa-book"></i></div>
      <div class="lb">Homework</div>
      <div class="vl c-purple"><?php echo $homeworkCount ? $homeworkCount.' today' : 'None today'; ?></div>
    </div>
    <div class="tday">
      <?php $ft = $feeBalance > 0 ? 'red' : 'green'; ?>
      <div class="ic bg-<?php echo $ft; ?> c-<?php echo $ft; ?>"><i class="fas fa-indian-rupee-sign"></i></div>
      <div class="lb">Fees</div>
      <div class="vl c-<?php echo $ft; ?>"><?php echo $feeBalance > 0 ? '₹'.pd_inr($feeBalance).' pending' : ($feeTotal > 0 ? 'All paid' : 'No fees'); ?></div>
    </div>
  </div>

  <!-- ============ FEE ALERT ============ -->
  <?php if ($feeBalance > 0) { ?>
  <div class="alert">
    <div class="ic"><i class="fas fa-bell"></i></div>
    <div><b>Fees pending: ₹<?php echo pd_inr($feeBalance); ?></b><span>Please pay the due fees at the school office at the earliest.</span></div>
  </div>
  <?php } ?>

  <!-- ============ FEES ============ -->
  <?php if ($feeTotal > 0) { ?>
  <div class="card">
    <div class="card-h">
      <h3><span class="hi bg-orange c-orange"><i class="fas fa-wallet"></i></span>Fees</h3>
      <span class="pill <?php echo $feeBalance <= 0 ? 'bg-green c-green' : 'bg-orange c-orange'; ?>"><?php echo $feeBalance <= 0 ? '<i class="fas fa-check"></i> Fully paid' : $feePaidPct.'% paid'; ?></span>
    </div>
    <div class="fee-big">
      <div><div class="k">Remaining balance</div><div class="v <?php echo $feeBalance > 0 ? 'c-orange' : 'c-green'; ?>">₹<?php echo pd_inr($feeBalance); ?></div></div>
    </div>
    <div class="bar"><span class="<?php echo $feeBalance <= 0 ? 'f-green' : 'f-amber'; ?>" style="width:<?php echo $feePaidPct; ?>%"></span></div>
    <div class="bar-l"><span>Paid ₹<?php echo pd_inr($feePaid); ?></span><span>Total ₹<?php echo pd_inr($feeTotal); ?></span></div>
    <div class="fee-grid">
      <div><span>Total</span><b>₹<?php echo pd_inr($feeTotal); ?></b></div>
      <div><span>Paid</span><b class="c-green">₹<?php echo pd_inr($feePaid); ?></b></div>
      <div><span>Due now</span><b class="<?php echo $feeOverdue > 0 ? 'c-red' : 'c-green'; ?>">₹<?php echo pd_inr($feeOverdue); ?></b></div>
    </div>
    <?php if ($nextDueDate) { ?>
    <div class="next-due"><i class="fas fa-calendar-day"></i><span>Next installment <b>₹<?php echo pd_inr($nextDueAmt); ?></b> on <b><?php echo date('d M Y', strtotime($nextDueDate)); ?></b></span></div>
    <?php } ?>
  </div>
  <?php } ?>

  <!-- ============ ATTENDANCE ============ -->
  <div class="card">
    <div class="card-h">
      <h3><span class="hi bg-green c-green"><i class="fas fa-calendar-check"></i></span>Attendance</h3>
      <small><?php echo date('F Y'); ?></small>
    </div>
    <div class="att">
      <div class="ring">
        <svg width="110" height="110" viewBox="0 0 110 110">
          <circle class="tr" cx="55" cy="55" r="46"></circle>
          <circle cx="55" cy="55" r="46" stroke="<?php echo $attMonthTotal ? ($attendancePercent >= 75 ? '#22c55e' : ($attendancePercent >= 50 ? '#f59e0b' : '#ef4444')) : '#cbd5e1'; ?>"
                  stroke-dasharray="<?php echo round($ringCircumference, 2); ?>" stroke-dashoffset="<?php echo round($ringOffset, 2); ?>"></circle>
        </svg>
        <div class="ring-c"><b><?php echo $attMonthTotal ? $attendancePercent.'%' : '—'; ?></b><small>this month</small></div>
      </div>
      <div class="att-chips">
        <div class="bg-green"><b class="c-green"><?php echo $attPresent; ?></b><span>Present</span></div>
        <div class="bg-red"><b class="c-red"><?php echo $attAbsent; ?></b><span>Absent</span></div>
        <div class="bg-amber"><b class="c-amber"><?php echo $attLeave; ?></b><span>Leave</span></div>
      </div>
    </div>
    <div class="note bg-<?php echo $attTone; ?> c-<?php echo $attTone; ?>"><i class="fas <?php echo $attTone == 'red' ? 'fa-triangle-exclamation' : ($attTone == 'green' ? 'fa-thumbs-up' : 'fa-circle-info'); ?>"></i><?php echo pd_e($attendanceNote); ?></div>
    <?php if ($attSessionTotal) { ?>
    <div class="att-sess"><span>Full session attendance</span><b class="c-<?php echo pd_tone($attSessionPct); ?>"><?php echo $attSessionPct; ?>%</b></div>
    <?php } ?>
  </div>

  <!-- ============ TODAY'S HOMEWORK ============ -->
  <div class="card">
    <div class="card-h">
      <h3><span class="hi bg-purple c-purple"><i class="fas fa-book"></i></span>Today's Homework</h3>
      <?php if ($homeworkCount) { ?><span class="pill bg-purple c-purple"><?php echo $homeworkCount; ?></span><?php } ?>
    </div>
    <?php if (!$todayHomework) { ?>
      <div class="empty"><i class="fas fa-mug-hot"></i>No homework for today.</div>
    <?php } else { foreach ($todayHomework as $hw) {
        $sub = !empty($hw['subject']) ? $hw['subject'] : ''; $ch = !empty($hw['chapter']) ? $hw['chapter'] : ''; ?>
      <div class="li">
        <div class="ic bg-purple c-purple"><i class="fas fa-pen-to-square"></i></div>
        <div class="bd">
          <b><?php echo pd_e($hw['title'] ?: ($sub ?: 'Homework')); ?></b>
          <?php if ($sub || $ch) { ?><small><?php echo pd_e(trim($sub.($sub && $ch ? ' · ' : '').$ch)); ?></small><?php } ?>
          <?php if (!empty($hw['description'])) { ?><div class="hw-desc"><?php echo nl2br(pd_e($hw['description'])); ?></div><?php } ?>
        </div>
      </div>
    <?php } } ?>
  </div>

  <!-- ============ LATEST RESULT ============ -->
  <?php if ($latestExam) { $allAbs = $latestExam['outoff'] <= 0; $rt = $allAbs ? 'gray' : pd_tone($latestExam['pct']); ?>
  <div class="card">
    <div class="card-h">
      <h3><span class="hi bg-blue c-blue"><i class="fas fa-award"></i></span>Latest Result</h3>
    </div>
    <div class="res-top">
      <div class="res-score bg-<?php echo $rt; ?> c-<?php echo $rt; ?>"><div><b><?php echo $allAbs ? '—' : $latestExam['pct'].'%'; ?></b><small><?php echo $allAbs ? 'Absent' : 'Score'; ?></small></div></div>
      <div class="t">
        <b><?php echo pd_e($latestExam['name']); ?></b>
        <small><?php echo pd_fmt($latestExam['obtain']); ?> / <?php echo pd_fmt($latestExam['outoff']); ?> marks<?php if ($latestExam['absent']) echo ' · <span class="c-red">'.$latestExam['absent'].' absent</span>'; ?></small>
      </div>
    </div>
    <?php foreach ($latestExam['subjects'] as $sb) { $sp = $sb['absent'] ? 0 : pd_pct($sb['obtain'], $sb['outoff']); $st = $sb['absent'] ? 'gray' : pd_tone($sp); ?>
    <div class="sub">
      <div class="sub-h"><span><?php echo pd_e($sb['subject']); ?></span>
        <?php if ($sb['absent']) { ?><span class="pill bg-gray c-gray"><?php echo pd_e($sb['obtain']); ?></span>
        <?php } else { ?><b class="c-<?php echo $st; ?>"><?php echo pd_fmt($sb['obtain']); ?> <span style="color:var(--faint);font-weight:600">/ <?php echo pd_fmt($sb['outoff']); ?></span></b><?php } ?>
      </div>
      <div class="bar"><span class="f-<?php echo $st; ?>" style="width:<?php echo min(100, $sp); ?>%"></span></div>
    </div>
    <?php } ?>
  </div>
  <?php } ?>

  <!-- ============ UPCOMING ============ -->
  <div class="card">
    <div class="card-h">
      <h3><span class="hi bg-brand c-brand"><i class="fas fa-calendar-days"></i></span>Upcoming</h3>
    </div>
    <?php if (!$upcomingItems) { ?>
      <div class="empty"><i class="fas fa-calendar"></i>Nothing upcoming right now.</div>
    <?php } else { foreach ($upcomingItems as $it) { $isFee = $it['kind'] === 'fee'; $c = $isFee ? 'orange' : 'purple'; ?>
      <div class="li">
        <div class="ic bg-<?php echo $c; ?> c-<?php echo $c; ?>"><i class="fas <?php echo $isFee ? 'fa-indian-rupee-sign' : 'fa-clipboard-list'; ?>"></i></div>
        <div class="bd">
          <b><?php echo pd_e($it['title']); ?></b>
          <?php if (!empty($it['subtitle'])) { ?><small><?php echo pd_e($it['subtitle']); ?></small><?php } ?>
        </div>
        <div class="dt bg-<?php echo $c; ?> c-<?php echo $c; ?>"><b><?php echo date('d', strtotime($it['date'])); ?></b><span><?php echo date('M', strtotime($it['date'])); ?></span></div>
      </div>
    <?php } } ?>
  </div>

  <!-- ============ QUICK LINKS (optional) ============ -->
  <?php if ($QUICK_LINKS) { ?>
  <div class="ql">
    <?php foreach ($QUICK_LINKS as $l) { ?>
    <a href="<?php echo pd_e($l['url']); ?>"><div class="ic bg-<?php echo pd_e($l['color']); ?> c-<?php echo pd_e($l['color']); ?>"><i class="fas <?php echo pd_e($l['icon']); ?>"></i></div><span><?php echo pd_e($l['label']); ?></span></a>
    <?php } ?>
  </div>
  <?php } ?>

  <div class="foot">Updated <?php echo date('d M Y, h:i A'); ?></div>
</div>
</body>
</html>