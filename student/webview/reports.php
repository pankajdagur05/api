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
/* Folder of student photos (as seen from THIS page). Admin panel uses "access/upload/<file>". */
if (!defined('SX_PHOTO_BASE')) define('SX_PHOTO_BASE', 'access/upload/');

/* ----------------------------- helpers ----------------------------- */
function mr_e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function mr_pct($a, $b){ $b = (float)$b; return $b > 0 ? (int)round(((float)$a * 100) / $b) : 0; }
function mr_w($p){ return max(0, min(100, (int)$p)); }
function mr_tone($p){ if($p >= 80) return 'success'; if($p >= 60) return 'info'; if($p >= 45) return 'warning'; return 'danger'; }
function mr_row($host, $sql){ try { $r = mysqli_query($host, $sql); if(!$r) return array(); $x = mysqli_fetch_assoc($r); return $x ? $x : array(); } catch (Throwable $e) { return array(); } }
function mr_rows($host, $sql){ $o = array(); try { $r = mysqli_query($host, $sql); if($r){ while($x = mysqli_fetch_assoc($r)) $o[] = $x; } } catch (Throwable $e) {} return $o; }
function mr_num($v){ return is_numeric($v) ? (float)$v : 0; }
function mr_fmt($v){ $v = (float)$v; return ($v == floor($v)) ? (string)(int)$v : rtrim(rtrim(number_format($v, 2, '.', ''), '0'), '.'); }
function mr_inr($n){
    $n = (int)round((float)$n); $neg = $n < 0; $s = (string)abs($n);
    if (strlen($s) > 3) {
        $last = substr($s, -3); $rest = substr($s, 0, -3);
        $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
        $s = $rest . ',' . $last;
    }
    return ($neg ? '-' : '') . $s;
}
function mr_date($d, $f = 'd M Y'){ return ($d && strtotime($d)) ? date($f, strtotime($d)) : '—'; }
function mr_initials($name){
    $w = preg_split('/\s+/', trim((string)$name)); $i = '';
    foreach ($w as $x) { if ($x === '') continue; $i .= function_exists('mb_substr') ? mb_substr($x, 0, 1, 'UTF-8') : substr($x, 0, 1); if (strlen($i) >= 2) break; }
    return strtoupper($i ? $i : '?');
}
function mr_delta($cur, $prev, $goodUp = true){
    if ($prev === null) return '';
    $cur = (float)$cur; $prev = (float)$prev;
    if ($prev == 0) return '<span class="rp-delta neutral">'.($cur > 0 ? 'New this session' : 'Same as last').'</span>';
    $ch = (int)round(($cur - $prev) * 100 / $prev);
    if ($ch == 0) return '<span class="rp-delta neutral">Same as last session</span>';
    $up = $ch > 0; $good = ($up === $goodUp);
    return '<span class="rp-delta '.($good ? 'good' : 'bad').'"><i class="fas fa-arrow-'.($up ? 'up' : 'down').'"></i> '.abs($ch).'% vs last session</span>';
}
function mr_att($host, $sid, $u, $extra = ''){
    $r = mr_row($host, "SELECT COUNT(*) total, SUM(status='P') p, SUM(status='A') a, SUM(status='L') l
                         FROM attendance WHERE sid='$sid' AND subdomain='$u' $extra");
    return array('total' => (int)@$r['total'], 'p' => (int)@$r['p'], 'a' => (int)@$r['a'], 'l' => (int)@$r['l']);
}
function mr_calendar($y, $m, $map, $show){
    $first  = mktime(0, 0, 0, $m, 1, $y);
    $days   = (int)date('t', $first);
    $offset = (int)date('N', $first) - 1;
    $h = '<div class="rp-cal" data-m="'.sprintf('%04d-%02d', $y, $m).'"'.($show ? '' : ' style="display:none"').'><div class="rp-cal-grid">';
    foreach (array('M','T','W','T','F','S','S') as $w) $h .= '<span class="rp-cal-w">'.$w.'</span>';
    for ($i = 0; $i < $offset; $i++) $h .= '<span></span>';
    for ($d = 1; $d <= $days; $d++) {
        $k = sprintf('%04d-%02d-%02d', $y, $m, $d);
        $s = isset($map[$k]) ? $map[$k] : '';
        $c = $s == 'P' ? 'p' : ($s == 'A' ? 'a' : ($s == 'L' ? 'l' : ''));
        $h .= '<span class="rp-day '.$c.'">'.$d.'</span>';
    }
    return $h.'</div></div>';
}
function mr_status_tone($s){
    $s = strtolower(trim($s));
    if ($s == 'pending')  return 'info';
    if ($s == 'complete') return 'success';
    if ($s == 'reject')   return 'danger';
    return 'warning';
}
function mr_complaints($host, $fromWhere){
    $lat = "(SELECT %s FROM complaint_status cs WHERE cs.comp_id=c.id ORDER BY cs.id DESC LIMIT 1)";
    $sql = "SELECT c.id, c.date, c.com_subject, c.com_desc,
                   ".sprintf($lat, 'sts_remark')."  AS last_remark,
                   ".sprintf($lat, 'update_date')." AS last_date,
                   ".sprintf($lat, 'upd_sts')."     AS last_status
            $fromWhere ORDER BY c.date DESC";
    return mr_rows($host, $sql);
}
function mr_comp_list($rows, $empty){
    if (!$rows) return '<div class="rp-note ok"><i class="fas fa-check-circle"></i><span>'.$empty.'</span></div>';
    $h = '';
    foreach ($rows as $r) {
        $has    = $r['last_status'] !== null;
        $remark = $has ? $r['last_remark'] : 'No updates yet';
        $udate  = $has ? $r['last_date'] : $r['date'];
        $status = $has ? $r['last_status'] : 'under review';
        $h .= '<div class="rp-comp">'
            . '<div class="rp-comp-h"><b>'.mr_e($r['com_subject']).'</b><span class="rp-pill '.mr_status_tone($status).'">'.mr_e(ucfirst($status)).'</span></div>'
            . ($r['com_desc'] ? '<p>'.mr_e($r['com_desc']).'</p>' : '')
            . '<div class="rp-comp-u"><i class="fas fa-reply"></i><div>'.mr_e($remark).'<small>'.mr_date($udate).'</small></div></div>'
            . '<div class="rp-comp-d"><i class="far fa-calendar"></i> Registered '.mr_date($r['date']).'</div>'
            . '</div>';
    }
    return $h;
}

if (!function_exists('sx_rows')) {

    function sx_rows($host, $sql){
        $o = array();
        try { $r = mysqli_query($host, $sql); if ($r) { while ($x = mysqli_fetch_assoc($r)) $o[] = $x; } }
        catch (Throwable $e) {}
        return $o;
    }

    function sx_idlist($ids){
        $c = array();
        foreach ((array)$ids as $i) { $i = (int)$i; if ($i > 0) $c[$i] = $i; }
        return $c ? implode(',', $c) : '';
    }

    /** sid => photo URL (only students that have a photo) */
    function sx_photos($host, $cid, $ids){
        $list = sx_idlist($ids);
        if ($list === '') return array();
        $cidE = mysqli_real_escape_string($host, (string)$cid);
        $out  = array();

        /* latest uploaded photo from docs */
        foreach (sx_rows($host, "SELECT sid, filename FROM docs
                                 WHERE cid='$cidE' AND sid IN ($list) AND LOWER(name)='photo'
                                   AND filename IS NOT NULL AND filename<>''
                                 ORDER BY id ASC") as $r) {
            $out[(int)$r['sid']] = SX_PHOTO_BASE . $r['filename'];      // later rows overwrite = latest
        }
        /* fallback: students.photo */
        $missing = array();
        foreach (explode(',', $list) as $i) if (!isset($out[(int)$i])) $missing[] = (int)$i;
        if ($missing) {
            foreach (sx_rows($host, "SELECT id, photo FROM students
                                     WHERE cid='$cidE' AND id IN (".implode(',', $missing).")
                                       AND photo IS NOT NULL AND photo<>''") as $r) {
                $out[(int)$r['id']] = SX_PHOTO_BASE . $r['photo'];
            }
        }
        return $out;
    }

    /** sid => array of sibling sids (same parent group, same cid + session) */
    function sx_sibling_map($host, $cid, $ids){
        $list = sx_idlist($ids);
        if ($list === '') return array();
        $cidE = mysqli_real_escape_string($host, (string)$cid);
        $map  = array();
        foreach (sx_rows($host, "SELECT DISTINCT p1.sid AS me, p2.sid AS sib
                                 FROM parentgroup_student p1
                                 INNER JOIN parentgroup_student p2
                                         ON p2.name = p1.name AND p2.cid = p1.cid
                                        AND (p2.session = p1.session OR p1.session IS NULL OR p1.session = '')
                                        AND p2.sid <> p1.sid
                                 WHERE p1.cid = '$cidE' AND p1.sid IN ($list)") as $r) {
            $map[(int)$r['me']][(int)$r['sib']] = (int)$r['sib'];
        }
        foreach ($map as $k => $v) $map[$k] = array_values($v);
        return $map;
    }

    /** full sibling details for one student */
    function sx_siblings($host, $cid, $sid){
        $m = sx_sibling_map($host, $cid, array($sid));
        if (empty($m[(int)$sid])) return array();
        $sibIds = $m[(int)$sid];
        $list   = implode(',', $sibIds);
        $cidE   = mysqli_real_escape_string($host, (string)$cid);

        /* name from group table (fallback) + group name */
        $grp = array();
        foreach (sx_rows($host, "SELECT sid, sname, name FROM parentgroup_student WHERE cid='$cidE' AND sid IN ($list)") as $r) {
            $grp[(int)$r['sid']] = $r;
        }
        $det = array();
        foreach (sx_rows($host, "SELECT id, name, fname, mname, standard, stream, stu_id, status, smobile
                                 FROM students WHERE cid='$cidE' AND id IN ($list)") as $r) {
            $det[(int)$r['id']] = $r;
        }
        $photos = sx_photos($host, $cid, $sibIds);

        $out = array();
        foreach ($sibIds as $i) {
            $d = isset($det[$i]) ? $det[$i] : array();
            $out[] = array(
                'id'       => $i,
                'name'     => !empty($d['name']) ? $d['name'] : (isset($grp[$i]['sname']) ? $grp[$i]['sname'] : 'Student #'.$i),
                'fname'    => isset($d['fname']) ? $d['fname'] : '',
                'mname'    => isset($d['mname']) ? $d['mname'] : '',
                'standard' => isset($d['standard']) ? $d['standard'] : '',
                'stream'   => isset($d['stream']) ? $d['stream'] : '',
                'stu_id'   => isset($d['stu_id']) ? $d['stu_id'] : '',
                'status'   => isset($d['status']) ? $d['status'] : '',
                'mobile'   => isset($d['smobile']) ? $d['smobile'] : '',
                'group'    => isset($grp[$i]['name']) ? $grp[$i]['name'] : '',
                'photo'    => isset($photos[$i]) ? $photos[$i] : '',
            );
        }
        return $out;
    }
}

/* --------------------------- this student only --------------------------- */
/* $u (student row from login) -> report variables. Student is fixed by login (mobile + id),
   nothing is taken from the URL, so a parent can only ever see their own child. */
$like = $u;
$sid  = (int)$like['id'];
$cidv = $user;
$u    = mysqli_real_escape_string($host, (string)$cidv);

$sid2  = (int)@$like['oldid'];
$today = date('Y-m-d');

$photoMap = sx_photos($host, $cidv, array($sid));
$photo    = isset($photoMap[$sid]) ? $photoMap[$sid] : '';
$siblings = sx_siblings($host, $cidv, $sid);
$mobile = isset($like['smobile']) ? trim($like['smobile']) : '';

/* ---------------------------- attendance ---------------------------- */
$att     = mr_att($host, $sid, $u);
$attPct  = mr_pct($att['p'], $att['total']);
$prevAtt = $sid2 > 0 ? mr_att($host, $sid2, $u) : null;
if ($prevAtt && $prevAtt['total'] == 0) $prevAtt = null;

$monthly = mr_rows($host, "SELECT YEAR(date) y, MONTH(date) m, COUNT(*) total,
                                  SUM(status='P') p, SUM(status='A') a, SUM(status='L') l
                           FROM attendance WHERE sid='$sid' AND subdomain='$u'
                           GROUP BY YEAR(date), MONTH(date) ORDER BY y, m");
$attMap = array();
foreach (mr_rows($host, "SELECT date, status FROM attendance WHERE sid='$sid' AND subdomain='$u'") as $r) {
    $attMap[date('Y-m-d', strtotime($r['date']))] = $r['status'];
}

/* ------------------------------ finance ----------------------------- */
$fin = mr_row($host, "SELECT SUM(IF(voucher='due',debit,0)) due,
                             SUM(IF(voucher='receive',credit,0)) rec,
                             SUM(IF(voucher='due' AND rdate<='$today',debit,0)) due_now,
                             SUM(IF(voucher='receive' AND rdate<='$today',credit,0)) rec_now
                      FROM feesdue WHERE sid='$sid' AND cid='$u' AND cancel='No'");
$finDue     = (float)@$fin['due'];
$finRec     = (float)@$fin['rec'];
$finPending = $finDue - $finRec;
$finOver    = max(0, (float)@$fin['due_now'] - (float)@$fin['rec_now']);
$finance0   = mr_pct($finRec, $finDue);

$ledger = mr_rows($host, "SELECT f.*, g.name gname, c.name cname
                          FROM feesdue f
                          LEFT JOIN erp_category c ON c.id=f.cateid
                          LEFT JOIN erp_group g ON g.id=c.type
                          WHERE f.cid='$u' AND f.sid='$sid' ORDER BY f.rdate DESC, f.id DESC");

$cats = mr_rows($host, "SELECT f.cateid, MAX(c.name) cname, MAX(g.name) gname,
                               SUM(IF(f.voucher='due' AND f.cancel='No',f.debit,0)) due,
                               SUM(IF(f.voucher='receive' AND f.cancel='No',f.credit,0)) rec,
                               SUM(IF(f.voucher='due' AND f.cancel='No' AND f.rdate<='$today',f.debit,0)) due_now,
                               SUM(IF(f.voucher='receive' AND f.cancel='No' AND f.rdate<='$today',f.credit,0)) rec_now,
                               MIN(f.rdate) first_date
                        FROM feesdue f
                        LEFT JOIN erp_category c ON c.id=f.cateid
                        LEFT JOIN erp_group g ON g.id=c.type
                        WHERE f.cid='$u' AND f.sid='$sid'
                        GROUP BY f.cateid ORDER BY first_date");

$extends = mr_rows($host, "SELECT * FROM fees_status WHERE sid='$sid' ORDER BY date DESC");
$remarks = mr_rows($host, "SELECT r.*, s.name staff_name FROM remark r LEFT JOIN staff s ON s.id=r.user WHERE r.sid='$sid' ORDER BY r.date DESC");

/* ------------------------------ review ------------------------------ */
$revTot = mr_row($host, "SELECT SUM(a.outoff) outoff, SUM(a.score) score
                         FROM review_submit s INNER JOIN review_answer a ON a.reviewsubmit_id=s.id
                         WHERE s.student_id='$sid' AND s.cid='$u'");
$revPct = mr_pct(@$revTot['score'], @$revTot['outoff']);

$reviews = mr_rows($host, "SELECT s.id, s.date, q.name slot, st.name staff,
                                  SUM(a.score) score, SUM(a.outoff) outoff
                           FROM review_submit s
                           INNER JOIN create_review cr ON cr.id=s.review_id
                           INNER JOIN qslot q ON q.id=cr.slot
                           LEFT JOIN staff st ON st.id=cr.res_person
                           LEFT JOIN review_answer a ON a.reviewsubmit_id=s.id
                           WHERE s.student_id='$sid' AND s.cid='$u'
                           GROUP BY s.id, s.date, q.name, st.name
                           ORDER BY s.date DESC");
$answers = array();
foreach (mr_rows($host, "SELECT a.*, qn.qstn, s.id rid
                         FROM review_submit s
                         INNER JOIN review_answer a ON a.reviewsubmit_id=s.id
                         INNER JOIN review_question qn ON qn.id=a.qtn
                         WHERE s.student_id='$sid' AND s.cid='$u'") as $r) {
    $answers[$r['rid']][] = $r;
}

/* ------------------------------- exams ------------------------------ */
$exams = array(); $exObt = 0; $exOut = 0;
foreach (mr_rows($host, "SELECT exam, subject, obtain, outoff FROM uploadresult WHERE sid='$sid' AND cid='$u'") as $r) {
    if (trim((string)$r['obtain']) === '') continue;
    $ex = $r['exam'];
    if (!isset($exams[$ex])) $exams[$ex] = array('obtain' => 0, 'outoff' => 0, 'subjects' => array());
    if (isset($exams[$ex]['subjects'][$r['subject']])) continue;
    $absent = !is_numeric(trim((string)$r['obtain']));
    $ob = $absent ? 0 : mr_num(trim((string)$r['obtain'])); $ou = mr_num($r['outoff']);
    $exams[$ex]['subjects'][$r['subject']] = array('obtain' => $r['obtain'], 'outoff' => $ou, 'absent' => $absent);
    if ($absent) {                       // absent subject -> not part of total (37/40, not 37/120)
        $exams[$ex]['absent'] = (isset($exams[$ex]['absent']) ? $exams[$ex]['absent'] : 0) + 1;
        continue;
    }
    $exams[$ex]['obtain'] += $ob; $exams[$ex]['outoff'] += $ou;
    $exObt += $ob; $exOut += $ou;
}
$examPct = mr_pct($exObt, $exOut);

/* ---------------------------- complaints ---------------------------- */
$cc   = mr_row($host, "SELECT SUM(p.type='parents') parents, SUM(p.type='student') student
                       FROM perm_comp p INNER JOIN complaint c ON c.id=p.complain_id
                       WHERE p.user='$u' AND p.sid='$sid' AND c.archive IS NULL");
$cfor = mr_row($host, "SELECT COUNT(*) n FROM complaint_resposible r INNER JOIN complaint c ON c.id=r.complaint_id
                       WHERE r.cid='$u' AND r.sid='$sid' AND c.archive IS NULL");
$cParents = (int)@$cc['parents']; $cStudent = (int)@$cc['student']; $cFor = 0;   // parents app: complaints "against student" not shown
$cTotal   = $cParents + $cStudent + $cFor;

$compParents = $cParents ? mr_complaints($host, "FROM perm_comp p INNER JOIN complaint c ON c.id=p.complain_id WHERE p.user='$u' AND p.sid='$sid' AND p.type='parents' AND c.archive IS NULL") : array();
$compStudent = $cStudent ? mr_complaints($host, "FROM perm_comp p INNER JOIN complaint c ON c.id=p.complain_id WHERE p.user='$u' AND p.sid='$sid' AND p.type='student' AND c.archive IS NULL") : array();
$compFor     = $cFor ? mr_complaints($host, "FROM complaint_resposible r INNER JOIN complaint c ON c.id=r.complaint_id WHERE r.cid='$u' AND r.sid='$sid' AND c.archive IS NULL") : array();

/* ---------------------------- score point --------------------------- */
$parts = array(
    array('Attendance', $attPct,   0, 'fa-calendar-check',  $att['total'] > 0,          'attendance'),
    array('Fees',       $finance0, 0, 'fa-rupee-sign',      $finDue > 0,                'fees'),
    array('Review',     $revPct,   0, 'fa-clipboard-check', (float)@$revTot['outoff'] > 0, 'review'),
    array('Exams',      $examPct,  0, 'fa-graduation-cap',  $exOut > 0,                 'exams'),
);
$avail = 0; foreach ($parts as $p) if ($p[4]) $avail++;
$tot2 = 0;
foreach ($parts as $i => $p) {
    if (!$p[4]) continue;
    $parts[$i][2] = 100 / $avail;
    $tot2 += ($p[1] * $parts[$i][2]) / 100;
}
$score      = $avail > 0 ? (int)round($tot2) : null;
$scoreLabel = $score === null ? 'Not enough data' : ($score >= 80 ? 'Excellent' : ($score >= 60 ? 'Good' : ($score >= 45 ? 'Average' : 'Needs attention')));
$scoreTone  = $score === null ? 'none' : mr_tone($score);
$ringC      = 2 * M_PI * 40;
$ringOff    = $ringC * (1 - (($score === null ? 0 : min(100, $score)) / 100));

$active = strtolower((string)@$like['status']) != 'inactive';

/* last exam for insights */
$lastExamName = null; $lastExamPct = null;
foreach ($exams as $n => $e) { if ($e['outoff'] <= 0) continue; $lastExamName = $n; $lastExamPct = mr_pct($e['obtain'], $e['outoff']); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
<meta name="theme-color" content="#f4f6fb">
<title>Report – <?php echo mr_e($like['name']); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{margin:0;padding:0;max-width:100%;overflow-x:hidden;-webkit-text-size-adjust:100%}
body{font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;background:#f4f6fb;color:#0f172a;font-size:14px;line-height:1.45;-webkit-font-smoothing:antialiased}
button{font-family:inherit;cursor:pointer}
.muted{color:#64748b}
.c-success{color:#10b981!important} .c-danger{color:#ef4444!important} .c-warning{color:#d97706!important} .c-info{color:#0ea5e9!important} .c-primary{color:#4f46e5!important} .c-none{color:#64748b!important}
.f-success{background:#10b981} .f-info{background:#0ea5e9} .f-warning{background:#f59e0b} .f-danger{background:#ef4444} .f-none{background:#cbd5e1}
.icon-btn{width:38px;height:38px;border-radius:12px;border:0;background:#f8fafc;color:#64748b;font-size:15px;flex-shrink:0}
:root{
  --brand:#4f46e5; --brand-soft:#eef2ff;
  --success:#10b981; --success-soft:#ecfdf5;
  --info:#0ea5e9;    --info-soft:#f0f9ff;
  --warning:#f59e0b; --warning-soft:#fffbeb;
  --danger:#ef4444;  --danger-soft:#fef2f2;
  --bg:#f4f6fb; --card:#fff; --line:#e9edf5; --soft:#f8fafc;
  --text:#0f172a; --muted:#64748b; --faint:#94a3b8;
  --r:18px; --sh:0 1px 2px rgba(15,23,42,.04),0 6px 18px rgba(15,23,42,.06);
}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{margin:0;padding:0}
body{font-family:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;background:var(--bg);color:var(--text);font-size:14px;line-height:1.45;-webkit-font-smoothing:antialiased;padding-bottom:calc(20px + env(safe-area-inset-bottom))}
button{font-family:inherit;cursor:pointer}
.muted{color:var(--muted)}
.c-success{color:var(--success)!important} .c-danger{color:var(--danger)!important} .c-warning{color:#d97706!important} .c-info{color:var(--info)!important} .c-primary{color:var(--brand)!important} .c-none{color:var(--muted)!important}
.f-success{background:var(--success)} .f-info{background:var(--info)} .f-warning{background:var(--warning)} .f-danger{background:var(--danger)} .f-none{background:#cbd5e1}


.page-head{position:sticky;top:0;z-index:20;background:rgba(244,246,251,.92);backdrop-filter:blur(8px);-webkit-backdrop-filter:blur(8px);padding:12px 14px;padding-top:calc(12px + env(safe-area-inset-top));display:flex;align-items:center;gap:10px;border-bottom:1px solid var(--line)}
.page-head .ic{width:38px;height:38px;border-radius:12px;background:var(--brand);color:#fff;display:grid;place-items:center;font-size:15px;box-shadow:0 6px 14px rgba(79,70,229,.25)}
.page-head b{display:block;font-size:17px;font-weight:800}
.page-head small{display:block;font-size:12px;color:var(--muted)}
.rp-page{max-width:560px;margin:0 auto;padding:14px 14px calc(96px + env(safe-area-inset-bottom))}
@keyframes fadeUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
.rp-hero{background:#fff;border-radius:var(--r);box-shadow:var(--sh);padding:16px;margin-bottom:12px}
.rp-hero-top{display:flex;gap:14px;align-items:center}
.rp-av{position:relative;width:68px;height:68px;border-radius:20px;background:var(--brand-soft);color:var(--brand);display:grid;place-items:center;font-size:22px;font-weight:800;overflow:hidden;flex-shrink:0;box-shadow:0 6px 16px rgba(79,70,229,.15)}
.rp-av img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.rp-hero-info{min-width:0;flex:1}
.rp-name{font-size:18px;font-weight:800;letter-spacing:-.01em;line-height:1.2}
.rp-cls{color:var(--brand);font-weight:600;font-size:13px;margin-top:2px}
.rp-tags{display:flex;flex-wrap:wrap;gap:6px;margin-top:8px}
.rp-parents{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:14px}
.rp-parents div{background:var(--soft);border-radius:12px;padding:8px 12px;min-width:0}
.rp-parents span{display:block;font-size:10.5px;text-transform:uppercase;letter-spacing:.05em;color:var(--faint);font-weight:700}
.rp-parents b{display:block;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.rp-callbtn{display:flex;align-items:center;justify-content:center;gap:8px;margin-top:12px;height:42px;border-radius:12px;background:#dcfce7;color:#15803d;font-weight:700;font-size:13px;text-decoration:none}

.rp-score{display:flex;align-items:center;gap:16px;background:#fff;border-radius:var(--r);box-shadow:var(--sh);padding:14px 16px;margin-bottom:12px}
.rp-ring{position:relative;width:104px;height:104px;flex-shrink:0}
.rp-ring svg{transform:rotate(-90deg)}
.rp-ring-v{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center}
.rp-ring-v b{font-size:28px;font-weight:800;line-height:1}
.rp-ring-v small{font-size:11px;color:var(--muted)}
.rp-score-k{font-size:11.5px;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);font-weight:700}
.rp-score-l{font-size:19px;font-weight:800;margin:2px 0}
.rp-score-s{font-size:12px;color:var(--muted)}

.rp-tabs{position:sticky;top:-14px;z-index:5;display:flex;gap:8px;overflow-x:auto;scrollbar-width:none;padding:10px 14px;margin:0 -14px 12px;background:var(--bg)}
.rp-tabs::-webkit-scrollbar{display:none}
.rp-tab{flex-shrink:0;border:1.5px solid var(--line);background:#fff;color:var(--muted);border-radius:99px;height:38px;padding:0 14px;font-size:13px;font-weight:700;display:flex;align-items:center;gap:7px;transition:.2s}
.rp-tab.on{background:var(--brand);border-color:var(--brand);color:#fff;box-shadow:0 6px 14px rgba(79,70,229,.25)}
.rp-tab .cnt{background:var(--danger);color:#fff;border-radius:99px;font-size:10.5px;min-width:18px;height:18px;padding:0 5px;display:grid;place-items:center}
.rp-panel{display:none}
.rp-panel.on{display:block;animation:fadeUp .25s ease}

.rp-pill{display:inline-flex;align-items:center;gap:4px;padding:3px 9px;border-radius:99px;font-size:11px;font-weight:700;white-space:nowrap}
.rp-pill.success{background:var(--success-soft);color:#047857}
.rp-pill.info{background:var(--info-soft);color:#0369a1}
.rp-pill.warning{background:var(--warning-soft);color:#b45309}
.rp-pill.danger{background:var(--danger-soft);color:#b91c1c}
.rp-pill.primary{background:var(--brand-soft);color:var(--brand)}
.rp-pill.neutral,.rp-pill.none{background:#f1f5f9;color:var(--muted)}

.rp-grid2{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px}
.rp-grid3{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px}
.rp-ico{width:34px;height:34px;border-radius:10px;display:inline-grid;place-items:center;font-size:14px}
.rp-ico.primary{background:var(--brand-soft);color:var(--brand)} .rp-ico.success{background:var(--success-soft);color:var(--success)}
.rp-ico.info{background:var(--info-soft);color:var(--info)} .rp-ico.warning{background:var(--warning-soft);color:var(--warning)}
.rp-ico.danger{background:var(--danger-soft);color:var(--danger)} .rp-ico.neutral{background:#f1f5f9;color:var(--faint)}

.rp-kpi{border:0;background:#fff;border-radius:16px;box-shadow:var(--sh);padding:14px;text-align:left;color:var(--text)}
.rp-kpi:active{transform:scale(.98)}
.rp-kpi-h{display:flex;justify-content:space-between;align-items:center}
.rp-kpi-h .go,.rp-note .go{font-size:11px;color:var(--faint)}
.rp-kpi-l{font-size:12px;color:var(--muted);font-weight:600;margin-top:10px}
.rp-kpi-v{font-size:22px;font-weight:800;letter-spacing:-.02em;margin:2px 0 8px}

.rp-tile{background:#fff;border-radius:16px;box-shadow:var(--sh);padding:14px;min-width:0}
.rp-tile.c{text-align:center;padding:12px 8px}
.rp-tile-l{font-size:11.5px;color:var(--muted);font-weight:600;margin-top:8px}
.rp-tile.c .rp-tile-l{margin-top:0}
.rp-tile-v{font-size:19px;font-weight:800;letter-spacing:-.02em;line-height:1.2;overflow:hidden;text-overflow:ellipsis}
.rp-tile.c .rp-tile-v{margin-top:6px}
.rp-delta{display:block;font-size:10.5px;font-weight:700;margin-top:3px}
.rp-delta.good{color:#059669}.rp-delta.bad{color:#dc2626}.rp-delta.neutral{color:var(--faint)}

.rp-bar{height:7px;border-radius:99px;background:#e9edf5;overflow:hidden;margin:8px 0 6px}
.rp-bar.lg{height:10px} .rp-bar.sm{height:5px;margin:6px 0 2px}
.rp-bar span{display:block;height:100%;border-radius:99px;transition:width .6s ease}

.rp-card{background:#fff;border-radius:16px;box-shadow:var(--sh);padding:14px;margin-bottom:12px}
.rp-card.nopad{padding:0}
.rp-row-sb{display:flex;justify-content:space-between;align-items:center;gap:10px}
.rp-row-sb small{display:block;font-size:11.5px;color:var(--muted)}
.rp-row-sb .tr{text-align:right;flex-shrink:0}
.rp-row-sb b.big{font-size:18px}
.rp-sec-t{font-size:12px;text-transform:uppercase;letter-spacing:.06em;font-weight:800;color:var(--muted);margin:16px 4px 8px}

.rp-led{padding:12px 14px;border-bottom:1px solid #f1f4f9}
.rp-led:last-child{border-bottom:0}
.rp-led-f{display:flex;flex-wrap:wrap;gap:6px 12px;font-size:11.5px;color:var(--muted);font-weight:600;align-items:center}

.rp-acc{background:#fff;border-radius:16px;box-shadow:var(--sh);margin-bottom:12px;overflow:hidden}
.rp-acc>summary{list-style:none;padding:14px;display:flex;justify-content:space-between;align-items:center;font-weight:700;font-size:13.5px;cursor:pointer}
.rp-acc>summary::-webkit-details-marker{display:none}
.rp-acc>summary span i{color:var(--brand);margin-right:6px}
.rp-acc>summary>i{color:var(--faint);transition:transform .2s}
.rp-acc[open]>summary>i{transform:rotate(180deg)}
.rp-acc-in{border-top:1px solid var(--line);max-height:60vh;overflow-y:auto}

.rp-tx{display:flex;gap:10px;align-items:flex-start;padding:12px 14px;border-bottom:1px solid #f1f4f9}
.rp-tx:last-child{border-bottom:0}
.rp-tx.cancelled{opacity:.5;background:repeating-linear-gradient(-45deg,#fff,#fff 6px,#fef2f2 6px,#fef2f2 12px)}
.rp-tx-ic{width:32px;height:32px;border-radius:10px;display:grid;place-items:center;font-size:12px;flex-shrink:0}
.rp-tx-ic.cr{background:var(--success-soft);color:var(--success)} .rp-tx-ic.dr{background:var(--info-soft);color:var(--info)}
.rp-tx-m{flex:1;min-width:0}
.rp-tx-m b{display:block;font-size:13px}
.rp-tx-m small{display:block;font-size:11.5px;color:var(--muted)}
.rp-tx-m small.rm{color:var(--text);font-weight:600}
.rp-tx-a{text-align:right;font-weight:800;font-size:13.5px;white-space:nowrap}
.rp-tx-a small{display:block;font-size:10.5px;color:var(--faint);font-weight:600}

.rp-simple{padding:12px 14px;border-bottom:1px solid #f1f4f9}
.rp-simple:last-child{border-bottom:0}
.rp-simple small{font-size:11.5px;color:var(--muted)}
.rp-simple p{margin:4px 0 0;font-size:13px}

.rp-legend{display:flex;align-items:center;gap:6px;font-size:11.5px;color:var(--muted);margin-bottom:10px;flex-wrap:wrap}
.rp-legend .dot{width:9px;height:9px;border-radius:3px;margin-left:6px}
.rp-legend .dot:first-child{margin-left:0}
.dot.p,.rp-stack .p{background:var(--success)} .dot.a,.rp-stack .a{background:#f87171} .dot.l,.rp-stack .l{background:#38bdf8}
.rp-mrow{padding:8px 0;border-bottom:1px dashed var(--line)}
.rp-mrow:last-child{border-bottom:0}
.rp-mrow b{font-size:13px}
.rp-stack{display:flex;height:9px;border-radius:99px;overflow:hidden;background:#e9edf5;margin-top:6px}

.rp-calm-row{display:flex;gap:6px;overflow-x:auto;scrollbar-width:none;margin-bottom:12px}
.rp-calm-row::-webkit-scrollbar{display:none}
.rp-calm{flex-shrink:0;border:1.5px solid var(--line);background:#fff;border-radius:10px;padding:6px 10px;font-size:12px;font-weight:700;color:var(--muted)}
.rp-calm.on{background:var(--brand-soft);border-color:#c7d2fe;color:var(--brand)}
.rp-cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:5px;text-align:center}
.rp-cal-w{font-size:10.5px;font-weight:700;color:var(--faint)}
.rp-day{font-size:12.5px;height:36px;line-height:36px;border-radius:10px;color:var(--muted);background:var(--soft)}
.rp-day.p{background:var(--success-soft);color:#047857;font-weight:800}
.rp-day.a{background:var(--danger-soft);color:#b91c1c;font-weight:800}
.rp-day.l{background:var(--info-soft);color:#0369a1;font-weight:800}

.rp-trend{display:flex;gap:10px;align-items:flex-end;margin-top:14px;overflow-x:auto;padding-bottom:2px}
.rp-trend-c{flex:1;min-width:52px;text-align:center}
.rp-trend-c small{font-size:11px;font-weight:800}
.rp-trend-b{height:90px;background:var(--soft);border-radius:10px;display:flex;align-items:flex-end;overflow:hidden;margin:4px 0}
.rp-trend-b span{display:block;width:100%;border-radius:10px 10px 0 0}
.rp-trend-c em{font-style:normal;font-size:10.5px;color:var(--muted);display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}

.rp-exam{background:#fff;border-radius:16px;box-shadow:var(--sh);margin-bottom:10px;overflow:hidden}
.rp-exam>summary{list-style:none;padding:14px;cursor:pointer}
.rp-exam>summary::-webkit-details-marker{display:none}
.rp-exam>summary .rp-bar{margin-bottom:0}
.rp-exam[open]>summary{border-bottom:1px solid var(--line)}
.rp-exam-in{padding:6px 14px 12px}
.rp-subj{padding:8px 0;border-bottom:1px dashed var(--line);font-size:13px}
.rp-subj:last-child{border-bottom:0}

.rp-notes{display:grid;gap:8px}
.rp-note{display:flex;align-items:center;gap:10px;width:100%;border:0;border-radius:14px;padding:12px 14px;font-size:13px;text-align:left;font-family:inherit;color:var(--text);margin-bottom:0}
.rp-note>span{flex:1}
.rp-note>i:first-child{font-size:15px}
.rp-note.ok{background:var(--success-soft)} .rp-note.ok>i:first-child{color:var(--success)}
.rp-note.bad{background:var(--danger-soft)} .rp-note.bad>i:first-child{color:var(--danger)}
.rp-note.warn{background:var(--warning-soft)} .rp-note.warn>i:first-child{color:var(--warning)}
.rp-note.info{background:var(--info-soft)} .rp-note.info>i:first-child{color:var(--info)}
.rp-panel>.rp-note{margin-bottom:10px}

.rp-comp{background:#fff;border-radius:16px;box-shadow:var(--sh);padding:14px;margin-bottom:10px}
.rp-comp-h{display:flex;justify-content:space-between;gap:10px;align-items:flex-start}
.rp-comp p{margin:6px 0 0;font-size:13px;color:var(--muted)}
.rp-comp-u{display:flex;gap:8px;background:var(--soft);border-radius:10px;padding:8px 10px;margin-top:10px;font-size:12.5px}
.rp-comp-u i{color:var(--brand);margin-top:3px;font-size:11px}
.rp-comp-u small{display:block;color:var(--faint);font-size:11px}
.rp-comp-d{font-size:11px;color:var(--faint);margin-top:8px}

.rp-empty{text-align:center;padding:36px 16px;color:var(--faint);font-size:13.5px;background:#fff;border-radius:16px;border:1.5px dashed var(--line);margin-bottom:12px}
.rp-empty i{display:block;font-size:26px;margin-bottom:8px;color:#cbd5e1}

.rp-tab .cnt.gray{background:#94a3b8}
.rp-voice{display:flex;align-items:center;gap:8px;margin-top:10px;background:var(--soft);border-radius:12px;padding:6px 10px}
.rp-voice i{color:#dc2626}
.rp-voice audio{flex:1;height:34px;min-width:0}
.rp-addrm{width:100%;height:42px;border:1.5px dashed #fcd34d;background:#fffbeb;color:#b45309;border-radius:12px;font-weight:700;font-size:13px;margin-bottom:12px}

/* ---------- photo / siblings / call recording (v3) ---------- */
.sc-av{position:relative;overflow:hidden}
.sc-av img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.sc-name-row{display:flex;align-items:center;gap:6px;min-width:0}
.sc-name-row .sc-name{min-width:0}
.sc-sib{flex-shrink:0;display:inline-flex;align-items:center;gap:4px;background:#f5f3ff;color:#7c3aed;border-radius:99px;padding:2px 7px;font-size:10px;font-weight:700;white-space:nowrap}
.sc-sib i{font-size:9px}
.rp-pill.sib{border:0;background:#f5f3ff;color:#7c3aed;font-family:inherit;cursor:pointer}
.sib-wrap{position:fixed;inset:0;z-index:960;display:none;align-items:flex-end;justify-content:center}
.sib-wrap.show{display:flex}
.sib-bg{position:absolute;inset:0;background:rgba(15,23,42,.45)}
.sib-box{position:relative;background:#fff;width:100%;max-height:80vh;border-radius:24px 24px 0 0;display:flex;flex-direction:column;animation:sibUp .25s ease;padding-bottom:env(safe-area-inset-bottom)}
@keyframes sibUp{from{transform:translateY(40px);opacity:0}to{transform:none;opacity:1}}
.sib-head{display:flex;align-items:center;gap:12px;padding:16px 18px 14px;border-bottom:1px solid var(--line)}
.sib-head .ic{width:40px;height:40px;border-radius:12px;background:#f5f3ff;color:#7c3aed;display:grid;place-items:center}
.sib-head .t{flex:1;min-width:0}
.sib-head .t b{display:block;font-size:16px}
.sib-head .t small{display:block;font-size:12.5px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sib-list{overflow-y:auto;padding:12px 16px 18px}
.sib-item{display:flex;gap:12px;align-items:flex-start;padding:12px;border:1px solid var(--line);border-radius:14px;margin-bottom:10px}
.sib-av{position:relative;width:44px;height:44px;border-radius:12px;background:var(--brand-soft);color:var(--brand);display:grid;place-items:center;font-weight:800;font-size:14px;overflow:hidden;flex-shrink:0}
.sib-av img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.sib-info{flex:1;min-width:0}
.sib-info>b{display:block;font-size:14.5px}
.sib-cls{font-size:12.5px;color:var(--brand);font-weight:600;margin-top:2px}
.sib-f{font-size:12px;color:var(--muted);margin-top:3px}
.sib-f i{width:14px;color:var(--faint)}
.sib-f b{color:var(--text);font-weight:600}
.rm-btns{display:flex;flex-wrap:wrap;gap:8px}
.rm-upl{display:inline-flex;align-items:center;gap:7px;height:36px;padding:0 14px;border-radius:99px;border:1.5px solid #bfdbfe;background:#eff6ff;color:#1d4ed8;font-size:12.5px;font-weight:700;cursor:pointer;position:relative;overflow:hidden}
.rm-upl input{position:absolute;inset:0;opacity:0;cursor:pointer}
.rm-call{display:flex;align-items:center;gap:8px;background:#eff6ff;border-radius:12px;padding:6px 8px;margin-top:8px}
.rm-call .lbl{font-size:11px;font-weight:700;color:#1d4ed8;white-space:nowrap}
.rm-call audio{flex:1;height:36px;min-width:0}
.rm-call button{width:32px;height:32px;border-radius:9px;border:0;background:#fff;color:#dc2626;flex-shrink:0}
.rm-call-name{font-size:11px;color:var(--muted);margin-top:4px;word-break:break-all}
.rp-voice.call{background:#eff6ff}
.rp-voice.call i{color:#1d4ed8}
.rp-voice .lbl{font-size:11px;font-weight:700;color:#1d4ed8}

.rp-pill.sib{border:0;background:#f5f3ff;color:#7c3aed;font-family:inherit;cursor:pointer}
.sib-wrap{position:fixed;inset:0;z-index:960;display:none;align-items:flex-end;justify-content:center}
.sib-wrap.show{display:flex}
.sib-bg{position:absolute;inset:0;background:rgba(15,23,42,.45)}
.sib-box{position:relative;background:#fff;width:100%;max-height:80vh;border-radius:24px 24px 0 0;display:flex;flex-direction:column;animation:sibUp .25s ease;padding-bottom:env(safe-area-inset-bottom)}
@keyframes sibUp{from{transform:translateY(40px);opacity:0}to{transform:none;opacity:1}}
.sib-head{display:flex;align-items:center;gap:12px;padding:16px 18px 14px;border-bottom:1px solid var(--line)}
.sib-head .ic{width:40px;height:40px;border-radius:12px;background:#f5f3ff;color:#7c3aed;display:grid;place-items:center}
.sib-head .t{flex:1;min-width:0}
.sib-head .t b{display:block;font-size:16px}
.sib-head .t small{display:block;font-size:12.5px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.sib-list{overflow-y:auto;padding:12px 16px 18px}
.sib-item{display:flex;gap:12px;align-items:flex-start;padding:12px;border:1px solid var(--line);border-radius:14px;margin-bottom:10px}
.sib-av{position:relative;width:44px;height:44px;border-radius:12px;background:var(--brand-soft);color:var(--brand);display:grid;place-items:center;font-weight:800;font-size:14px;overflow:hidden;flex-shrink:0}
.sib-av img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover}
.sib-info{flex:1;min-width:0}
.sib-info>b{display:block;font-size:14.5px}
.sib-cls{font-size:12.5px;color:var(--brand);font-weight:600;margin-top:2px}
.sib-f{font-size:12px;color:var(--muted);margin-top:3px}
.sib-f i{width:14px;color:var(--faint)}
.sib-f b{color:var(--text);font-weight:600}

.rp-tabs{top:calc(62px + env(safe-area-inset-top));margin:0 -14px 12px;padding:10px 14px}

</style>
</head>
<body>
<?php //include("header.php"); ?>

<div class="page-head">
  <span class="ic"><i class="fas fa-chart-pie"></i></span>
  <div style="min-width:0"><b>Report Card</b><small><?php echo mr_e($like['name']); ?><?php if (!empty($_SESSION['session'])) echo ' · Session '.mr_e($_SESSION['session']); ?></small></div>
</div>

<div class="rp-page" id="reportBody">
<!-- ================= PROFILE ================= -->
<div class="rp-hero">
  <div class="rp-hero-top">
    <div class="rp-av">
      <?php if ($photo) { ?><img src="<?php echo mr_e($photo); ?>" alt="" onerror="this.remove()"><?php } ?>
      <span><?php echo mr_e(mr_initials($like['name'])); ?></span>
    </div>
    <div class="rp-hero-info">
      <div class="rp-name"><?php echo mr_e($like['name']); ?></div>
      <div class="rp-cls"><?php echo mr_e($like['standard']); ?><?php if (!empty($like['stream'])) echo ' · '.mr_e($like['stream']); ?></div>
      <div class="rp-tags">
        <?php if (!empty($like['stu_id'])) { ?><span class="rp-pill primary">SR #<?php echo mr_e($like['stu_id']); ?></span><?php } ?>
        <?php if ($active) { ?><span class="rp-pill success"><i class="fas fa-check-circle"></i> Active</span>
        <?php } else { ?><span class="rp-pill danger"><i class="fas fa-times-circle"></i> Inactive</span><?php } ?>
        <?php if (!empty($like['session'])) { ?><span class="rp-pill neutral"><?php echo mr_e($like['session']); ?></span><?php } ?>
        <?php if ($siblings) { ?><button type="button" class="rp-pill sib" data-sib-open><i class="fas fa-user-friends"></i> Siblings (<?php echo count($siblings); ?>) <i class="fas fa-chevron-right" style="font-size:9px"></i></button><?php } ?>
      </div>
    </div>
  </div>
  <div class="rp-parents">
    <?php if (!empty($like['fname'])) { ?><div><span>Father</span><b><?php echo mr_e($like['fname']); ?></b></div><?php } ?>
    <?php if (!empty($like['mname'])) { ?><div><span>Mother</span><b><?php echo mr_e($like['mname']); ?></b></div><?php } ?>
  </div>
</div>

<!-- ================= SCORE ================= -->
<div class="rp-score">
  <div class="rp-ring">
    <svg width="104" height="104" viewBox="0 0 104 104">
      <defs><linearGradient id="rpg" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#6366f1"/><stop offset="1" stop-color="#10b981"/></linearGradient></defs>
      <circle cx="52" cy="52" r="40" fill="none" stroke="#eef1f6" stroke-width="10"/>
      <circle cx="52" cy="52" r="40" fill="none" stroke="url(#rpg)" stroke-width="10" stroke-linecap="round"
              stroke-dasharray="<?php echo round($ringC, 2); ?>" stroke-dashoffset="<?php echo round($ringOff, 2); ?>"/>
    </svg>
    <div class="rp-ring-v"><b><?php echo $score === null ? '—' : $score; ?></b><small>/ 100</small></div>
  </div>
  <div class="rp-score-r">
    <div class="rp-score-k">Overall Score</div>
    <div class="rp-score-l c-<?php echo $scoreTone; ?>"><?php echo $scoreLabel; ?></div>
    <div class="rp-score-s"><?php echo $avail ? 'Average of '.$avail.' categor'.($avail == 1 ? 'y' : 'ies') : 'No data recorded yet'; ?></div>
  </div>
</div>

<!-- ================= TABS ================= -->
<div id="rpTabsAnchor"></div>
<div class="rp-tabs">
  <button type="button" class="rp-tab on" data-tab="overview"><i class="fas fa-th-large"></i> Overview</button>
  <button type="button" class="rp-tab" data-tab="fees"><i class="fas fa-wallet"></i> Fees</button>
  <button type="button" class="rp-tab" data-tab="attendance"><i class="fas fa-calendar-check"></i> Attendance</button>
  <button type="button" class="rp-tab" data-tab="exams"><i class="fas fa-graduation-cap"></i> Exams</button>
  <button type="button" class="rp-tab" data-tab="review"><i class="fas fa-clipboard-check"></i> Review</button>
  <button type="button" class="rp-tab" data-tab="complaints"><i class="fas fa-comment-dots"></i> Complaints<?php if ($cTotal) { ?><span class="cnt"><?php echo $cTotal; ?></span><?php } ?></button>
</div>

<!-- ================= OVERVIEW ================= -->
<div class="rp-panel on" data-panel="overview">
  <div class="rp-grid2">
    <?php foreach ($parts as $p) { $on = $p[4]; $tn = $on ? mr_tone($p[1]) : 'none'; ?>
    <button type="button" class="rp-kpi" data-goto="<?php echo $p[5]; ?>">
      <div class="rp-kpi-h"><span class="rp-ico <?php echo $on ? $tn : 'neutral'; ?>"><i class="fas <?php echo $p[3]; ?>"></i></span><i class="fas fa-chevron-right go"></i></div>
      <div class="rp-kpi-l"><?php echo $p[0]; ?></div>
      <div class="rp-kpi-v <?php echo $on ? 'c-'.$tn : 'muted'; ?>"><?php echo $on ? $p[1].'%' : '—'; ?></div>
      <div class="rp-bar"><span class="f-<?php echo $tn; ?>" style="width:<?php echo $on ? mr_w($p[1]) : 0; ?>%"></span></div>
    </button>
    <?php } ?>
  </div>

  <div class="rp-sec-t">Quick insights</div>
  <div class="rp-notes">
    <?php $any = false;
    if ($finOver > 0) { $any = true; ?>
      <button type="button" class="rp-note bad" data-goto="fees"><i class="fas fa-exclamation-triangle"></i><span><b>₹<?php echo mr_inr($finOver); ?></b> fees overdue till today</span><i class="fas fa-chevron-right go"></i></button>
    <?php } elseif ($finDue > 0 && $finPending <= 0) { $any = true; ?>
      <div class="rp-note ok"><i class="fas fa-check-circle"></i><span>All fees paid</span></div>
    <?php } elseif ($finPending > 0) { $any = true; ?>
      <button type="button" class="rp-note info" data-goto="fees"><i class="fas fa-hourglass-half"></i><span><b>₹<?php echo mr_inr($finPending); ?></b> pending (not yet due)</span><i class="fas fa-chevron-right go"></i></button>
    <?php }
    if ($att['total'] && $attPct < 75) { $any = true; ?>
      <button type="button" class="rp-note warn" data-goto="attendance"><i class="fas fa-user-clock"></i><span>Attendance <b><?php echo $attPct; ?>%</b> — below 75%</span><i class="fas fa-chevron-right go"></i></button>
    <?php }
    if ($lastExamName !== null) { $any = true; ?>
      <button type="button" class="rp-note <?php echo $lastExamPct >= 60 ? 'ok' : 'warn'; ?>" data-goto="exams"><i class="fas fa-graduation-cap"></i><span>Latest exam <b><?php echo mr_e($lastExamName); ?></b>: <?php echo $lastExamPct; ?>%</span><i class="fas fa-chevron-right go"></i></button>
    <?php }
    if ($cFor) { $any = true; ?>
      <button type="button" class="rp-note bad" data-goto="complaints"><i class="fas fa-exclamation-circle"></i><span><b><?php echo $cFor; ?></b> complaint<?php echo $cFor == 1 ? '' : 's'; ?> against student</span><i class="fas fa-chevron-right go"></i></button>
    <?php }
    if ($cParents || $cStudent) { $any = true; ?>
      <button type="button" class="rp-note info" data-goto="complaints"><i class="fas fa-comment-dots"></i><span><?php echo $cParents + $cStudent; ?> complaint<?php echo ($cParents + $cStudent) == 1 ? '' : 's'; ?> raised by parents/student</span><i class="fas fa-chevron-right go"></i></button>
    <?php }
    if (!$any) { ?>
      <div class="rp-note ok"><i class="fas fa-smile"></i><span>Everything looks good</span></div>
    <?php } ?>
  </div>
</div>

<!-- ================= FEES ================= -->
<div class="rp-panel" data-panel="fees">
  <div class="rp-grid2">
    <div class="rp-tile"><span class="rp-ico primary"><i class="fas fa-rupee-sign"></i></span><div class="rp-tile-l">Total fees</div><div class="rp-tile-v">₹<?php echo mr_inr($finDue); ?></div></div>
    <div class="rp-tile"><span class="rp-ico success"><i class="fas fa-money-check"></i></span><div class="rp-tile-l">Received</div><div class="rp-tile-v c-success">₹<?php echo mr_inr($finRec); ?></div></div>
    <div class="rp-tile"><span class="rp-ico warning"><i class="fas fa-hourglass-half"></i></span><div class="rp-tile-l"><?php echo $finPending < 0 ? 'Advance' : 'Pending'; ?></div><div class="rp-tile-v c-warning">₹<?php echo mr_inr(abs($finPending)); ?></div></div>
    <div class="rp-tile"><span class="rp-ico danger"><i class="fas fa-exclamation-triangle"></i></span><div class="rp-tile-l">Overdue</div><div class="rp-tile-v <?php echo $finOver > 0 ? 'c-danger' : ''; ?>">₹<?php echo mr_inr($finOver); ?></div></div>
  </div>

  <?php if ($finDue > 0) { ?>
  <div class="rp-card">
    <div class="rp-row-sb"><b>Payment progress</b><span class="rp-pill <?php echo mr_tone($finance0); ?>"><?php echo $finance0; ?>% paid</span></div>
    <div class="rp-bar lg"><span class="f-<?php echo mr_tone($finance0); ?>" style="width:<?php echo mr_w($finance0); ?>%"></span></div>
  </div>
  <?php } ?>

  <?php if ($cats) { ?>
  <div class="rp-sec-t">Ledger-wise</div>
  <div class="rp-card nopad">
    <?php foreach ($cats as $c) { $ov = max(0, $c['due_now'] - $c['rec_now']); $bal = $c['due'] - $c['rec']; $cp = mr_pct($c['rec'], $c['due']); ?>
    <div class="rp-led">
      <div class="rp-row-sb">
        <div><b><?php echo mr_e($c['cname'] ? $c['cname'] : 'Other'); ?></b><?php if ($c['gname']) { ?><small><?php echo mr_e($c['gname']); ?></small><?php } ?></div>
        <div class="tr"><b class="<?php echo $bal > 0 ? 'c-danger' : 'c-success'; ?>">₹<?php echo mr_inr($bal); ?></b><small>balance</small></div>
      </div>
      <div class="rp-bar"><span class="f-<?php echo $c['due'] > 0 ? mr_tone($cp) : 'none'; ?>" style="width:<?php echo mr_w($cp); ?>%"></span></div>
      <div class="rp-led-f">
        <span>Payable ₹<?php echo mr_inr($c['due']); ?></span>
        <span class="c-success">Paid ₹<?php echo mr_inr($c['rec']); ?></span>
        <?php if ($ov > 0) { ?><span class="rp-pill danger">Overdue ₹<?php echo mr_inr($ov); ?></span><?php } ?>
      </div>
    </div>
    <?php } ?>
  </div>

  <details class="rp-acc">
    <summary><span><i class="fas fa-list-ul"></i> All transactions (<?php echo count($ledger); ?>)</span><i class="fas fa-chevron-down"></i></summary>
    <div class="rp-acc-in">
      <?php foreach ($ledger as $l) { $cancel = ($l['cancel'] == 'Yes'); $isCr = (float)$l['credit'] > 0; ?>
      <div class="rp-tx <?php echo $cancel ? 'cancelled' : ''; ?>">
        <span class="rp-tx-ic <?php echo $isCr ? 'cr' : 'dr'; ?>"><i class="fas fa-arrow-<?php echo $isCr ? 'down' : 'up'; ?>"></i></span>
        <div class="rp-tx-m">
          <b><?php echo mr_e(trim(($l['cname'] ? $l['cname'] : '').($l['gname'] ? ' · '.$l['gname'] : ''), ' ·')); ?></b>
          <small><?php echo mr_date($l['rdate'], 'd M Y'); ?><?php if (!empty($l['mode'])) echo ' · '.mr_e($l['mode']); ?></small>
          <?php if (!empty($l['remark'])) { ?><small class="rm"><?php echo mr_e($l['remark']); ?></small><?php } ?>
          <?php if ($cancel) { ?><small class="c-danger">Cancelled<?php if (!empty($l['cancelremark'])) echo ' · '.mr_e($l['cancelremark']); ?></small><?php } ?>
        </div>
        <div class="rp-tx-a <?php echo $isCr ? 'c-success' : 'c-info'; ?>">
          <?php echo $isCr ? '+ ₹'.mr_inr($l['credit']) : '₹'.mr_inr($l['debit']); ?>
          <small><?php echo $isCr ? 'Received' : 'Due'; ?></small>
        </div>
      </div>
      <?php } ?>
    </div>
  </details>
  <?php } else { ?>
    <div class="rp-empty"><i class="fas fa-receipt"></i>No fee entries for this session.</div>
  <?php } ?>

  <?php if ($extends) { ?>
  <details class="rp-acc">
    <summary><span><i class="fas fa-calendar-plus"></i> Date extensions (<?php echo count($extends); ?>)</span><i class="fas fa-chevron-down"></i></summary>
    <div class="rp-acc-in">
      <?php foreach ($extends as $x) { ?>
      <div class="rp-simple">
        <div class="rp-row-sb"><b>Extended to <?php echo mr_date($x['idate']); ?></b><small><?php echo mr_date($x['date']); ?></small></div>
        <small><?php echo mr_e(trim($x['uid'].' – '.$x['type'], ' –')); ?></small>
        <?php if (!empty($x['remark'])) { ?><p><?php echo mr_e($x['remark']); ?></p><?php } ?>
      </div>
      <?php } ?>
    </div>
  </details>
  <?php } ?>

  <?php if ($remarks) { ?>
  <details class="rp-acc">
    <summary><span><i class="fas fa-sticky-note"></i> Remarks (<?php echo count($remarks); ?>)</span><i class="fas fa-chevron-down"></i></summary>
    <div class="rp-acc-in">
      <?php foreach ($remarks as $r) { ?>
      <div class="rp-simple">
        <div class="rp-row-sb"><b><?php echo mr_e(!empty($r['user']) && $r['staff_name'] ? $r['staff_name'] : 'Admin'); ?></b><small><?php echo mr_date($r['date']); ?></small></div>
        <p><?php echo mr_e($r['remark']); ?></p>
      </div>
      <?php } ?>
    </div>
  </details>
  <?php } ?>
</div>

<!-- ================= ATTENDANCE ================= -->
<div class="rp-panel" data-panel="attendance">
  <?php if (!$att['total']) { ?>
    <div class="rp-empty"><i class="fas fa-calendar"></i>No attendance recorded yet.</div>
  <?php } else { ?>
  <div class="rp-card">
    <div class="rp-row-sb"><b>Overall attendance</b><span class="rp-pill <?php echo mr_tone($attPct); ?>"><?php echo $attPct; ?>%</span></div>
    <div class="rp-bar lg"><span class="f-<?php echo mr_tone($attPct); ?>" style="width:<?php echo mr_w($attPct); ?>%"></span></div>
    <small class="muted"><?php echo $att['p']; ?> present out of <?php echo $att['total']; ?> working days</small>
  </div>

  <div class="rp-grid2">
    <div class="rp-tile"><span class="rp-ico primary"><i class="fas fa-calendar-alt"></i></span><div class="rp-tile-l">Working days</div><div class="rp-tile-v"><?php echo $att['total']; ?></div><?php echo mr_delta($att['total'], $prevAtt ? $prevAtt['total'] : null, true); ?></div>
    <div class="rp-tile"><span class="rp-ico success"><i class="fas fa-thumbs-up"></i></span><div class="rp-tile-l">Present</div><div class="rp-tile-v c-success"><?php echo $att['p']; ?></div><?php echo mr_delta($att['p'], $prevAtt ? $prevAtt['p'] : null, true); ?></div>
    <div class="rp-tile"><span class="rp-ico danger"><i class="fas fa-thumbs-down"></i></span><div class="rp-tile-l">Absent</div><div class="rp-tile-v c-danger"><?php echo $att['a']; ?></div><?php echo mr_delta($att['a'], $prevAtt ? $prevAtt['a'] : null, false); ?></div>
    <div class="rp-tile"><span class="rp-ico info"><i class="fas fa-umbrella-beach"></i></span><div class="rp-tile-l">Leave</div><div class="rp-tile-v c-info"><?php echo $att['l']; ?></div><?php echo mr_delta($att['l'], $prevAtt ? $prevAtt['l'] : null, false); ?></div>
  </div>

  <div class="rp-sec-t">Month-wise</div>
  <div class="rp-card">
    <div class="rp-legend"><span class="dot p"></span>Present <span class="dot a"></span>Absent <span class="dot l"></span>Leave</div>
    <?php foreach ($monthly as $m) { $t = max(1, (int)$m['total']); $mp = mr_pct($m['p'], $m['total']); ?>
    <div class="rp-mrow">
      <div class="rp-row-sb"><b><?php echo date('M Y', mktime(0, 0, 0, (int)$m['m'], 1, (int)$m['y'])); ?></b><small><span class="c-success"><?php echo (int)$m['p']; ?>P</span> · <span class="c-danger"><?php echo (int)$m['a']; ?>A</span> · <span class="c-info"><?php echo (int)$m['l']; ?>L</span> · <b><?php echo $mp; ?>%</b></small></div>
      <div class="rp-stack">
        <span class="p" style="width:<?php echo ($m['p'] * 100 / $t); ?>%"></span><span class="a" style="width:<?php echo ($m['a'] * 100 / $t); ?>%"></span><span class="l" style="width:<?php echo ($m['l'] * 100 / $t); ?>%"></span>
      </div>
    </div>
    <?php } ?>
  </div>

  <div class="rp-sec-t">Day-wise calendar</div>
  <div class="rp-card">
    <div class="rp-calm-row">
      <?php $lastI = count($monthly) - 1; foreach ($monthly as $i => $m) { $key = sprintf('%04d-%02d', $m['y'], $m['m']); ?>
        <button type="button" class="rp-calm <?php echo $i == $lastI ? 'on' : ''; ?>" data-m="<?php echo $key; ?>"><?php echo date('M y', mktime(0, 0, 0, (int)$m['m'], 1, (int)$m['y'])); ?></button>
      <?php } ?>
    </div>
    <?php foreach ($monthly as $i => $m) echo mr_calendar((int)$m['y'], (int)$m['m'], $attMap, $i == $lastI); ?>
    <div class="rp-legend" style="margin:10px 0 0"><span class="dot p"></span>Present <span class="dot a"></span>Absent <span class="dot l"></span>Leave</div>
  </div>
  <?php } ?>
</div>

<!-- ================= EXAMS ================= -->
<div class="rp-panel" data-panel="exams">
  <?php if (!$exams) { ?>
    <div class="rp-empty"><i class="fas fa-file-alt"></i>No exam results uploaded yet.</div>
  <?php } else { ?>
  <div class="rp-card">
    <div class="rp-row-sb"><b>Overall</b><span class="rp-pill <?php echo mr_tone($examPct); ?>"><?php echo $examPct; ?>%</span></div>
    <div class="rp-bar lg"><span class="f-<?php echo mr_tone($examPct); ?>" style="width:<?php echo mr_w($examPct); ?>%"></span></div>
    <small class="muted"><?php echo mr_fmt($exObt); ?> / <?php echo mr_fmt($exOut); ?> marks in <?php echo count($exams); ?> exam<?php echo count($exams) == 1 ? '' : 's'; ?></small>

    <?php if (count($exams) > 1) { ?>
    <div class="rp-trend">
      <?php foreach ($exams as $name => $e) { if ($e['outoff'] <= 0) continue; $p = mr_pct($e['obtain'], $e['outoff']); ?>
      <div class="rp-trend-c"><small><?php echo $p; ?>%</small><div class="rp-trend-b"><span class="f-<?php echo mr_tone($p); ?>" style="height:<?php echo max(4, mr_w($p)); ?>%"></span></div><em><?php echo mr_e($name); ?></em></div>
      <?php } ?>
    </div>
    <?php } ?>
  </div>

  <div class="rp-sec-t">Exam-wise · tap to see subjects</div>
  <?php $prevP = null; foreach ($exams as $name => $e) { $allAbs = $e['outoff'] <= 0; $abs = isset($e['absent']) ? (int)$e['absent'] : 0; $p = mr_pct($e['obtain'], $e['outoff']); $tn = $allAbs ? 'none' : mr_tone($p); ?>
  <details class="rp-exam">
    <summary>
      <div class="rp-row-sb">
        <div><b><?php echo mr_e($name); ?></b><small><?php echo count($e['subjects']); ?> subject<?php echo count($e['subjects']) == 1 ? '' : 's'; ?> · <?php echo mr_fmt($e['obtain']); ?>/<?php echo mr_fmt($e['outoff']); ?><?php if ($abs) { ?> · <span class="c-danger"><?php echo $abs; ?> absent</span><?php } ?></small></div>
        <div class="tr">
          <?php if ($allAbs) { ?><span class="rp-pill neutral">Absent</span><?php } else { ?><b class="big c-<?php echo $tn; ?>"><?php echo $p; ?>%</b><?php } ?>
          <?php if ($prevP !== null && !$allAbs) { $upx = $p >= $prevP; ?><small class="<?php echo $upx ? 'c-success' : 'c-danger'; ?>"><i class="fas fa-arrow-<?php echo $upx ? 'up' : 'down'; ?>"></i> <?php echo abs($p - $prevP); ?>%</small><?php } ?>
        </div>
      </div>
      <div class="rp-bar"><span class="f-<?php echo $tn; ?>" style="width:<?php echo mr_w($p); ?>%"></span></div>
    </summary>
    <div class="rp-exam-in">
      <?php foreach ($e['subjects'] as $sub => $s) { $sp = $s['absent'] ? 0 : mr_pct($s['obtain'], $s['outoff']); $st = $s['absent'] ? 'none' : mr_tone($sp); ?>
      <div class="rp-subj">
        <div class="rp-row-sb"><span><?php echo mr_e($sub); ?></span>
          <span><?php if ($s['absent']) { ?><span class="rp-pill neutral"><?php echo mr_e($s['obtain']); ?></span><?php } else { ?><b class="c-<?php echo $st; ?>"><?php echo mr_fmt($s['obtain']); ?></b><?php } ?> <small class="muted">/ <?php echo mr_fmt($s['outoff']); ?></small></span>
        </div>
        <div class="rp-bar sm"><span class="f-<?php echo $st; ?>" style="width:<?php echo mr_w($sp); ?>%"></span></div>
      </div>
      <?php } ?>
    </div>
  </details>
  <?php if (!$allAbs) $prevP = $p; } ?>
  <?php } ?>
</div>

<!-- ================= REVIEW ================= -->
<div class="rp-panel" data-panel="review">
  <?php if (!$reviews) { ?>
    <div class="rp-empty"><i class="fas fa-clipboard"></i>No reviews submitted yet.</div>
  <?php } else { ?>
  <div class="rp-card">
    <div class="rp-row-sb"><b>Average review</b><span class="rp-pill <?php echo mr_tone($revPct); ?>"><?php echo $revPct; ?>%</span></div>
    <div class="rp-bar lg"><span class="f-<?php echo mr_tone($revPct); ?>" style="width:<?php echo mr_w($revPct); ?>%"></span></div>
    <small class="muted"><?php echo mr_fmt(@$revTot['score']); ?> / <?php echo mr_fmt(@$revTot['outoff']); ?> points in <?php echo count($reviews); ?> review<?php echo count($reviews) == 1 ? '' : 's'; ?></small>
  </div>

  <div class="rp-sec-t">All reviews · tap for details</div>
  <?php foreach ($reviews as $r) { $p = mr_pct($r['score'], $r['outoff']); $tn = mr_tone($p); $rows = isset($answers[$r['id']]) ? $answers[$r['id']] : array(); ?>
  <details class="rp-exam">
    <summary>
      <div class="rp-row-sb">
        <div><b><?php echo mr_e($r['slot']); ?></b><small><?php echo mr_date($r['date']); ?><?php if ($r['staff']) echo ' · by '.mr_e($r['staff']); ?></small></div>
        <div class="tr"><b class="big c-<?php echo $tn; ?>"><?php echo $p; ?>%</b><small><?php echo mr_fmt($r['score']); ?>/<?php echo mr_fmt($r['outoff']); ?></small></div>
      </div>
      <div class="rp-bar"><span class="f-<?php echo $tn; ?>" style="width:<?php echo mr_w($p); ?>%"></span></div>
    </summary>
    <div class="rp-exam-in">
      <?php if (!$rows) { ?><small class="muted">No question details.</small><?php } ?>
      <?php foreach ($rows as $a) { $ap = mr_pct($a['score'], $a['outoff']); ?>
      <div class="rp-subj">
        <div class="rp-row-sb"><span><?php echo mr_e($a['qstn']); ?></span><span><b class="c-primary"><?php echo mr_fmt($a['score']); ?></b> <small class="muted">/ <?php echo mr_fmt($a['outoff']); ?></small></span></div>
        <div class="rp-bar sm"><span class="f-<?php echo mr_tone($ap); ?>" style="width:<?php echo mr_w($ap); ?>%"></span></div>
        <?php if (!empty($a['remark'])) { ?><small class="muted"><i class="fas fa-quote-left"></i> <?php echo mr_e($a['remark']); ?></small><?php } ?>
      </div>
      <?php } ?>
    </div>
  </details>
  <?php } ?>
  <?php } ?>
</div>

<!-- ================= COMPLAINTS ================= -->
<div class="rp-panel" data-panel="complaints">
  <div class="rp-grid2">
    <div class="rp-tile c"><span class="rp-ico warning"><i class="fas fa-user-friends"></i></span><div class="rp-tile-v"><?php echo $cParents; ?></div><div class="rp-tile-l">By parents</div></div>
    <div class="rp-tile c"><span class="rp-ico primary"><i class="fas fa-user"></i></span><div class="rp-tile-v"><?php echo $cStudent; ?></div><div class="rp-tile-l">By student</div></div>
    
  </div>
  <?php if (!$cTotal) { ?>
    <div class="rp-note ok"><i class="fas fa-check-circle"></i><span>All clear — no complaints</span></div>
  <?php } else { ?>
    <?php if ($cParents) { ?><div class="rp-sec-t">By parents</div><?php echo mr_comp_list($compParents, 'No complaints by parents.'); } ?>
    <?php if ($cStudent) { ?><div class="rp-sec-t">By student</div><?php echo mr_comp_list($compStudent, 'No complaints by student.'); } ?>
    <?php if ($cFor) { ?><div class="rp-sec-t">Against student</div><?php echo mr_comp_list($compFor, 'No complaints against student.'); } ?>
  <?php } ?>
</div>

<?php if ($siblings) { ?>
<!-- ================= SIBLINGS MODAL ================= -->
<div class="sib-wrap" id="sibModal">
  <div class="sib-bg" data-sib-close></div>
  <div class="sib-box">
    <div class="sib-head">
      <span class="ic"><i class="fas fa-user-friends"></i></span>
      <div class="t"><b>Siblings</b><small><?php echo mr_e($like['name']); ?><?php if (!empty($siblings[0]['group'])) echo ' · '.mr_e($siblings[0]['group']); ?></small></div>
      <button type="button" class="icon-btn" data-sib-close aria-label="Close"><i class="fas fa-times"></i></button>
    </div>
    <div class="sib-list">
      <?php foreach ($siblings as $sb) { $sbIn = strtolower((string)$sb['status']) == 'inactive'; ?>
      <div class="sib-item">
        <div class="sib-av"><?php if ($sb['photo']) { ?><img src="<?php echo mr_e($sb['photo']); ?>" alt="" onerror="this.remove()"><?php } ?><?php echo mr_e(mr_initials($sb['name'])); ?></div>
        <div class="sib-info">
          <b><?php echo mr_e($sb['name']); ?></b>
          <div class="sib-cls"><?php echo $sb['standard'] !== '' ? mr_e($sb['standard']) : '—'; ?><?php if ($sb['stream'] !== '') echo ' · '.mr_e($sb['stream']); ?><?php if ($sb['stu_id'] !== '') echo ' · SR '.mr_e($sb['stu_id']); ?></div>
          <?php if ($sb['fname'] !== '') { ?><div class="sib-f"><i class="fas fa-user-tie"></i> Father: <b><?php echo mr_e($sb['fname']); ?></b></div><?php } ?>
          <?php if ($sb['mname'] !== '') { ?><div class="sib-f"><i class="fas fa-female"></i> Mother: <b><?php echo mr_e($sb['mname']); ?></b></div><?php } ?>
        </div>
        <span class="rp-pill <?php echo $sbIn ? 'danger' : 'success'; ?>"><?php echo $sbIn ? 'Inactive' : 'Active'; ?></span>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
</div>

<?php //include("footer.php"); ?>

<script>
(function(){
  function qa(s, r){ return Array.prototype.slice.call((r || document).querySelectorAll(s)); }
  var anchor = document.getElementById('rpTabsAnchor');

  function openTab(t){
    qa('.rp-tab').forEach(function(b){ b.classList.toggle('on', b.getAttribute('data-tab') === t); });
    qa('.rp-panel').forEach(function(p){ p.classList.toggle('on', p.getAttribute('data-panel') === t); });
    var btn = document.querySelector('.rp-tab[data-tab="' + t + '"]');
    if (btn && btn.scrollIntoView) btn.scrollIntoView({ inline: 'center', block: 'nearest', behavior: 'smooth' });
    if (anchor) {
      var y = anchor.getBoundingClientRect().top + window.pageYOffset - 70;
      if (window.pageYOffset > y) window.scrollTo({ top: y, behavior: 'smooth' });
    }
  }
  document.addEventListener('click', function(e){
    var t = e.target.closest ? e.target : null; if (!t) return;
    var tab = t.closest('.rp-tab');        if (tab)  { openTab(tab.getAttribute('data-tab')); return; }
    var go  = t.closest('[data-goto]');    if (go)   { openTab(go.getAttribute('data-goto')); return; }
    var cm  = t.closest('.rp-calm');
    if (cm) {
      var m = cm.getAttribute('data-m');
      qa('.rp-calm').forEach(function(b){ b.classList.toggle('on', b === cm); });
      qa('.rp-cal').forEach(function(c){ c.style.display = c.getAttribute('data-m') === m ? '' : 'none'; });
      return;
    }
    if (t.closest('[data-sib-open]'))  { var s = document.getElementById('sibModal'); if (s) s.classList.add('show'); return; }
    if (t.closest('[data-sib-close]')) { var s2 = document.getElementById('sibModal'); if (s2) s2.classList.remove('show'); }
  });
})();
</script>
</body>
</html>