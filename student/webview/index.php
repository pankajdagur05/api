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
$header_sid   = $_SERVER['HTTP_ID']   ?? null; 

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
$sid    = $_SESSION['sid']    ?? null;

if (!$username || !$token) {
    echo "<h3 style='color:red;padding:20px;'>Access Denied</h3>";
    exit;
}

/* ===============================
   TOKEN VALIDATION (ONLY CHECK)
================================ */
$valid = mysqli_query($host,"SELECT id FROM apk_token WHERE user='$username' AND token='$token' LIMIT 1");

if (mysqli_num_rows($valid) == 0) {
    session_destroy();
    echo "<h3 style='color:red;padding:20px;'>Invalid Token</h3>";
    exit;
}

/* ===============================
   USER TYPE DETECT
================================ */
$type = ctype_digit($username) ? 'other' : 'admin';

/* ===============================
   STAFF LOGIN
================================ */
//if ($type === 'other') {

$q = mysqli_query($host,"SELECT * FROM students WHERE smobile='$username' and id='$sid'");

    if (!$q || mysqli_num_rows($q) == 0) {
        session_destroy();
        exit('No Student Found');
    }

    $u = mysqli_fetch_assoc($q);

    $_SESSION['id']        = $header_staff_id  ?? $u['id'];
    $_SESSION['name']      = $u['name'];
    $_SESSION['standard']      = $u['standard'];
    $_SESSION['username']  = $username;
    $_SESSION['token']     = $token; 
    $_SESSION['type']      = 'other';
    $_SESSION['branch']    = $header_branch_id ?? $u['cid'];

    $sid = $_SESSION['branch'];

    $s = mysqli_query(
        $host,
        "SELECT name FROM session WHERE cid='$sid' ORDER BY id DESC LIMIT 1"
    );
    $sd = mysqli_fetch_assoc($s);
    $_SESSION['session'] = $sd['name'] ?? '';
    
      $bq = mysqli_query(
            $host,
            "SELECT iname FROM branch_category WHERE id='$sid'"
        );
        $bd = mysqli_fetch_assoc($bq);
        $_SESSION['branch_name'] = $bd['iname'] ?? null;

 

/* ===============================
   FINAL VARIABLES (USE EVERYWHERE)
================================ */ 
$branch    = $_SESSION['branch'];
$type      = $_SESSION['type'];
$session      = $_SESSION['session'];
$sid   = $_SESSION['id'];
$ssname    = $_SESSION['name'];
$standard = $_SESSION['standard'];
$username    = $_SESSION['username'];
$branch_name    = $_SESSION['branch_name'];
$user      = $branch;

/* ===============================
   DEBUG (REMOVE AFTER TEST)
================================ */

// echo "<pre style='background:#111;color:#0ff;padding:15px;'>";
// echo "LOGIN SUCCESS\n";
// echo "Name      : $ssname\n";
// echo "BRANCH ID : $branch\n";
// echo "STUDENT ID  : $sid\n";
// echo "BRANCH NAME  : $branch_name\n";
// echo "SESSION   : ".$_SESSION['session']."\n";
// echo "</pre>";


?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student App -Dashboard</title>
  <style>
    /* Reset */
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}
    body{
      font-family: 'Segoe UI', Roboto, Arial, sans-serif;
      background: #f4f7f9; 
      background-image: url('/mnt/data/WhatsApp Image 2025-11-21 at 10.56.33_cba12438.jpg');
      background-size: 2200px; /* scale down the pattern so it stays subtle */
      background-repeat: repeat;
      padding: 0;
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
    }

    .wrap{max-width:420px;margin:0 auto}

    /* Card style used by each section */
    .card{
      background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(250,250,251,0.98));
      border-radius: 14px;
      padding: 22px 20px 18px;
      box-shadow: 0 6px 18px rgba(30,40,50,0.06), inset 0 1px 0 rgba(255,255,255,0.6);
      margin-bottom:18px;
      border: 1px solid rgba(30,40,50,0.03);
      backdrop-filter: blur(2px);
    }

    .section-title{
      font-family: Georgia, 'Times New Roman', serif;
      color:#333;
      font-size:17px;
      margin-bottom:14px;
      letter-spacing:0.2px;
    }

    .icons{
      display:grid;
      grid-template-columns: repeat(4, 1fr);
      gap:14px 6px;
    }

    .icon{
      display:flex;
      flex-direction:column;
      align-items:center;
      text-align:center;
      font-size:13px;
      color:#2b3b45;
      min-height:78px;
      justify-content:center;
      gap:6px;
    }

    .icon svg{width:25px;height:25px;display:block}
    .label{font-size:12px;color:#2b3b45}

    /* small lock badge */
    .lock-badge{
      position:relative;
    }
    .lock-badge .lock{
      position:absolute;right:-6px;top:-6px;width:16px;height:16px;display:grid;place-items:center;background:#fff;border-radius:50%;box-shadow:0 1px 2px rgba(0,0,0,0.08)
    }
    .lock svg{width:12px;height:12px}

    /* locked items dimmed slightly + no pointer confusion */
    .locked{cursor:pointer}
    .locked .icon svg{opacity:0.55}
    .locked .label{opacity:0.55}

    /* Smaller sections have 3 items centered */
    .icons-3{grid-template-columns: repeat(3, 1fr)}

    /* Right red vertical tab */
    .tutorial-tab{
      position:fixed;right:8px;top:50%;transform:translateY(-50%);
      background:#c62828;color:#fff;padding:12px 8px;border-radius:6px 6px 6px 6px;writing-mode:vertical-rl;text-orientation:mixed;font-weight:600;box-shadow:0 6px 18px rgba(0,0,0,0.12);
    }

    /* Responsive */
    @media (max-width:420px){
      .wrap{padding:0 12px;max-height: 200vh;
        height: 109vh;}
      .icons{gap:12px}
      .card{padding:12px}
    }

    /* Decorative subtle separator (to match image tone) */
    .card + .card{margin-top:6px}
    a{text-decoration:none !important;}

    /* Simple center toast/modal for locked click message */
    #lockMsgOverlay{
      display:none;position:fixed;inset:0;background:rgba(0,0,0,0.35);
      align-items:center;justify-content:center;z-index:9999;
    }
    #lockMsgBox{
      background:#fff;padding:22px 28px;border-radius:12px;
      box-shadow:0 10px 30px rgba(0,0,0,0.2);text-align:center;
      max-width:260px;
    }
    #lockMsgBox p{color:#c62828;font-weight:600;font-size:15px;margin-bottom:14px}
    #lockMsgBox button{
      background:#2b8fb3;color:#fff;border:none;padding:8px 18px;
      border-radius:6px;font-size:13px;cursor:pointer;
    }
  </style>
</head>
<body>
    
    <?php //include("header.php"); 
    
    $q="select * from students where id='$sid'";
    $qq=mysqli_query($host,$q);
    $sql = mysqli_fetch_array($qq);
    
    
    ?>
    
  <div class="wrap">

    <div class="card">
      <div class="section-title">Academic</div>
      <div class="icons">

        <a href="javascript:void(0)" onclick="showLockMsg()" class="locked">
        <div class="icon lock-badge">
          <!-- graduation cap svg -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M12 12v5"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Today Report</div>
        </div>
        </a>
        
   <a href="syllabus.php">
        <div class="icon">
          <!-- clipboard svg -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="4" rx="1"></rect><path d="M21 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7"></path></svg>
          <div class="label">Syllabus</div>
        </div>
         </a>

 <a href="yearly_calender.php">
        <div class="icon">
          <!-- calendar svg -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M16 2v4M8 2v4M3 10h18"></path></svg>
          <div class="label">Yearly Calender</div>
        </div>
         </a>

 <a href="javascript:void(0)" onclick="showLockMsg()" class="locked">
        <div class="icon lock-badge">
          <!-- time table icon (list) -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M3 12h18"></path><path d="M3 18h18"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Time Table</div>
        </div>
        </a>

<a href="javascript:void(0)" onclick="showLockMsg()" class="locked">
        <div class="icon lock-badge">
          <!-- books svg -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 7v13a2 2 0 0 0 2 2h14"></path><path d="M22 7v13a2 2 0 0 1-2 2H6"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Study Material</div>
        </div>
        </a>

<a href="javascript:void(0)" onclick="showLockMsg()" class="locked">
        <div class="icon lock-badge">
          <!-- notes svg -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15V6a2 2 0 0 0-2-2H7"></path><path d="M3 8v13a1 1 0 0 0 1 1h14"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Notes</div>
        </div>
        </a>

<a href="homework.php">
        <div class="icon">
          <!-- homework icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20v-6"></path><path d="M20 8H8l-4 4v6h16V8z"></path></svg>
          <div class="label">Home Work</div>
        </div>
        </a>

<a href="javascript:void(0)" onclick="showLockMsg()" class="locked">
        <div class="icon lock-badge">
          <!-- certificate icon -->
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="14" rx="2"></rect><path d="M8 21v-3"></path><path d="M16 21v-3"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Certificate</div>
        </div>
      </div>
    </div>
    </a>

    <div class="card">
      <div class="section-title">Examination</div>
      <div class="icons icons-3">
     <a href="exam_report.php">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M3 10h18"></path></svg>
          <div class="label">Exam Report</div>
        </div>
        </a>
        
     <a href="major_exam.php">    
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M3 10h18"></path></svg>
          <div class="label">Major</div>
        </div>
        </a>
        
         <a href="minor_exam.php">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"></rect><path d="M3 10h18"></path></svg>
          <div class="label">Minor</div>
        </div>
        </a>
        
      </div>
    </div>

    <div class="card">
      <div class="section-title">Amenities</div>
      <div class="icons icons-3">
    <a href="attendance_record.php">
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"></rect><path d="M8 2v4"></path></svg>
          <div class="label">Attendance</div>
        </div>
        </a>
        
     <a href="fees.php">    
        <div class="icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="8"></circle><path d="M21 12h-3"></path></svg>
          <div class="label">Fees Manage</div>
        </div>
          </a>
    
     <a href="javascript:void(0)" onclick="showLockMsg()" class="locked">         
        <div class="icon lock-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a4 4 0 0 1 4 4v3"></path><path d="M6 21v-2a4 4 0 0 1 4-4h4"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Appeal</div>
        </div>
      </div>
      </a>

      <div style="height:10px"></div>

      <div class="icons icons-3">
    <a href="javascript:void(0)" onclick="showLockMsg()" class="locked">   
        <div class="icon lock-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"></rect><path d="M7 8h10"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Review</div>
        </div>
        </a>
    
      <a href="javascript:void(0)" onclick="showLockMsg()" class="locked">     
        <div class="icon lock-badge">
          <svg viewBox="0 0 24 24" fill="none" stroke="#2b8fb3" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6"></path><path d="M7 10l5 3 5-3"></path></svg>
          <div class="lock"><svg viewBox="0 0 24 24" fill="none" stroke="#c62828" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"></rect><path d="M7 11V8a5 5 0 0 1 10 0v3"></path></svg></div>
          <div class="label">Live Vision</div>
        </div>
    </a>
        
        <div class="icon">
          <!-- empty placeholder to keep grid symmetry -->
          <div style="height:36px"></div>
          <div class="label">&nbsp;</div>
        </div>
      </div>

    </div>

  </div>

  <!-- Locked item click message -->
  <div id="lockMsgOverlay" onclick="hideLockMsg()">
    <div id="lockMsgBox" onclick="event.stopPropagation()">
      <p>Data Not Found</p>
      <button onclick="hideLockMsg()">OK</button>
    </div>
  </div>

  <script>
    function showLockMsg(){
      document.getElementById('lockMsgOverlay').style.display = 'flex';
    }
    function hideLockMsg(){
      document.getElementById('lockMsgOverlay').style.display = 'none';
    }
  </script>
  
   <?php //include("footer.php"); ?>

</body>
</html>