<?php $host = "localhost";
$user = "root";
$password = "";
$dbname = "marudhar_lms";
$host=mysqli_connect("localhost","marudhar_lms","~Rxkoutds1Kj","marudhar_lms");

$branch=$_REQUEST['branch'];
$session=$_REQUEST['session'];
$cid=0;

//$current = file_get_contents($file2);


$total_student=0;
     $total_staff=0;
     $total_standard=0;
     
        $present_student=0;
     $present_staff=0;
     $present_standard=0;
    
    $income="";
    $expense="";
    $appeal_total=0;
       $appeal_pending=0;
       $appeal_completed=0;
       
          $gatepass_total=0;
       $gatepass_inside=0;
       $gatepass_outside=0;
       $total_leads=0;
          $lead_completed=0;
           $lead_pending=0;


$sql="select * from branch_category where name='".$branch."' and session='".$session."'";

$result_session=mysqli_query($host,$sql);
if(mysqli_num_rows($result_session)>0)
{
    $row_branch=mysqli_fetch_array($result_session);
    $cid= $row_branch['id'];
    $sql1 = "SELECT COUNT(*) AS total FROM standard WHERE cid='$cid' AND status='Inactive'";
$result1 = mysqli_query($host, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$total_standard= $row1['total'];

// Query 2: Count from staff
$sql2 = "SELECT COUNT(*) AS total FROM staff WHERE branch='$cid' AND status != 'Inactive'";
$result2 = mysqli_query($host, $sql2);
$row2 = mysqli_fetch_assoc($result2);
 $total_staff = $row2['total'];

// Query 3: Count from students
$sql3 = "SELECT COUNT(*) AS total FROM students WHERE cid='$cid' AND session='$session' AND status != 'Inactive'";
$result3 = mysqli_query($host, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$total_student = $row3['total'];

// Query 4: Count from attendance
$sql4 = "SELECT COUNT(*) AS total FROM attendance WHERE subdomain='$cid' AND status='P' AND DATE(idate) = CURDATE()";
$result4 = mysqli_query($host, $sql4);
$row4 = mysqli_fetch_assoc($result4);
$present_student = $row4['total'];

// Query 5: Count from staff_attendance
$sql5 = "SELECT COUNT(*) AS total FROM staff_attendance WHERE subdomain='$cid' AND status='P' AND DATE(idate) = CURDATE()";
$result5 = mysqli_query($host, $sql5);
$row5 = mysqli_fetch_assoc($result5);
 $present_staff = $row5['total'];

    
    
    
   


 // file_put_contents($file2,  $current);     
    
$sql_staff_attend="select id from staff_attendance where subdomain='".$cid."' and status='P'and DATE(idate) = CURDATE()";
    $sql_expense="SELECT SUM(CASE WHEN side = 'credit' THEN amount ELSE 0 END) AS total_income,SUM(CASE WHEN side = 'debit' THEN amount ELSE 0 END) AS total_expense FROM accounts
WHERE cid='$cid' and  DATE(idate) = CURDATE()";
$result_expense_income=mysqli_query($host,$sql_expense);
$row_expense=mysqli_fetch_array($result_expense_income);
 $income=$row_expense['total_income'];
    $expense=$row_expense['total_expense'];
    /*
     $sql_appeal="SELECT SUM(CASE WHEN side = 'credit' THEN amount ELSE 0 END) AS total_income,SUM(CASE WHEN side = 'debit' THEN amount ELSE 0 END) AS total_expense FROM complaint_status2
WHERE cid='$cid' and  DATE(date) = CURDATE()";
$result_appeal=mysqli_query($host,$sql_appeal);
$row_expense=mysqli_fetch_array($result_expense_income);
 $income=$row_expense['total_income'];
$expense=$row_expense['total_expense'];
    */
    


     
  
     $present_standard=0;


    
    
    
    
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mobile Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
    }
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      color: #333;
    }
    .container {
      padding: 10px;
    }
    .section-title {
      font-size: 16px;
      font-weight: bold;
      margin: 15px 0 8px;
    }
    .row {
      display: flex;
      flex-wrap: nowrap;
      overflow-x: auto;
      gap: 10px;
    }
    .card {
      min-width: 100px;
      background: white;
      border-radius: 10px;
      padding: 10px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      text-align: center;
      flex-shrink: 0;
    }
    .card .icon {
      font-size: 20px;
      margin-bottom: 5px;
    }
 
    .attendance-box {
      background: white;
      border-radius: 5px;
      padding: 6px 9px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
    }
    .attendance-box h3 {
      margin: 0 0 15px;
      font-size: 14px;
      color: #666;
      font-weight: normal;
    }
    .attendance-row {
      display: flex;
      justify-content: space-between;
      text-align: center; 
          gap: 10px;
    }
    .attendance-card {
      flex: 1;
      padding-bottom: 5px;
    }
    .attendance-card:not(:last-child) {
      border-right: 1px solid #eee;
    }
  
    .blue { background: #2196F3; }
    .orange { background: #FF9800; }
    .pink { background: #E91E63; }
    .attendance-count {
      font-size: 16px;
      color: #34495e;
    }
    .attendance-label {
      font-size: 12px;
    margin-top: 3px;
    color: #999;
    letter-spacing: 0.5px;
    text-align: right;
    margin-right: 5px;

    }

    @media(max-width: 600px) {
       
       
      .attendance-card:last-child {
        border-bottom: none;
      }
    }

     .appeal-box {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      display: flex;
      flex-direction: column;
    }

    .appeal-box h3 {
      margin: 0 0 15px;
      font-size: 18px;
      color: #666;
      font-weight: normal;
    }

    .appeal-row {
      display: flex;
      justify-content: space-between;
      text-align: center;
      border-top: 1px solid #eee;
    }

    .appeal-card {
      flex: 1;
      padding: 10px;
    }

    .appeal-card:not(:last-child) {
      border-right: 1px solid #eee;
    }

    .icon-box {
      display: inline-block;
    padding: 8px 10px;
    border-radius: 4px;
    color: white;
    font-size: 8px;
    margin-bottom: 4px;
        margin-right: 8px;
    }

    .black { background: #000; }
    .green { background: #4CAF50; }
    .red { background: #F44336; }

    .appeal-count {
      font-size: 16px;
      color: #34495e;
    }

    .appeal-label {
      font-size: 12px;
      margin-top: 3px;
      color: #999;
      letter-spacing: 0.5px;
    }

    @media(max-width: 600px) {
      .appeal-row {
        flex-direction: column;
      }
      .appeal-card {
        border-right: none;
        border-bottom: 1px solid #eee;
      }
      .appeal-card:last-child {
        border-bottom: none;
      }
    }

    .student-section {
      margin-bottom: 20px;
    }

    .student-section h3 {
      font-size: 14px;
      color: #666;
      margin-bottom: 15px;
      font-weight: normal;
    }

    .student-cards {
      display: flex;
      gap: 10px; 
    }

    .student-card {
      flex: 1;
    min-width: 82px;
    background: #fff;
    border-radius: 3px;
    padding: 5px 7px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    }

    .student-card .card-title {
      font-size: 11px;
    font-weight: 500;
    margin-bottom: 5px;
    color: #222;
    }

    .student-card .card-count {
      display: flex
;
    align-items: center;
    font-size: 12px;
    font-weight: bold;
    color: #333;
    }

    .student-card .card-count i {
     margin-right: 6px;
    font-size: 12px;
    color: #1d3557;
    }

    /* Different background colors */
    .bg-green { background-color: #e6f9ec; }
    .bg-red { background-color: #fdeeee; }
    .bg-blue { background-color: #eef1fe; }
    .bg-yellow { background-color: #fff8dc; }

     .finance-section {
      margin-bottom: 20px;
    }

    .finance-section h3 {
      font-size: 14px;
      color: #666;
      margin-bottom: 15px;
      font-weight: normal;
    }

    .finance-cards {
      display: flex;
      gap: 15px; 
    }

    .finance-card {
        background: #fff;
    flex: 1;
    min-width: 171px;
    border-radius: 10px;
    padding: 7px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    position: relative;
    }

    .finance-card h4 {
      font-size: 11px;
    color: #555;
    margin-bottom: 5px;
    margin-top: 2px;
    }

    .finance-card .icon {
      position: absolute;
      top: 15px;
      right: 15px;
      font-size: 18px;
    }

    .icon.green {
      color: #ffffff;
    border-radius: 18px;
    width: 25px;
    height: 25px;
    padding: 2px 7px;
    font-size: 13px;
    }

    .icon.blue {
        color: #ffffff;
    border-radius: 18px;
    width: 25px;
    height: 25px;
    padding: 3px 8px;
    font-size: 13px;
    }

    .amount {
      font-size: 16px;
    font-weight: 400;
    color: #222;
    }

    .percent {
      margin-top: 8px;
      font-size: 13px;
      color: #999;
    }

    .percent i {
      color: red;
      margin-right: 5px;
    }

    .percent .green {
      color: #1ba94c;
    }

    .financegreen {
      color: #1ba94c;
    }

  </style>
</head>
<body>
  <div class="container">
    <div class="student-section">
    <h3>STUDENT</h3>
    <div class="student-cards">
      <div class="student-card bg-green">
        <div class="card-title">Active</div>
        <div class="card-count"><i class="fas fa-user-graduate"></i><?php echo($_GET['branch']); ?> <?php echo($_GET['session']); ?></div>
      </div>
      <div class="student-card bg-red">
        <div class="card-title">Inactive</div>
        <div class="card-count"><i class="fas fa-user-graduate"></i> 4</div>
      </div>
      <div class="student-card bg-blue">
        <div class="card-title">New</div>
        <div class="card-count"><i class="fas fa-user-graduate"></i> 0</div>
      </div>
      <div class="student-card bg-yellow">
        <div class="card-title">TC</div>
        <div class="card-count"><i class="fas fa-user-graduate"></i> 6</div>
      </div>
    </div>
  </div>

      <div class="attendance-box">
    <h3>ATTENDANCE</h3>
    <div class="attendance-row">
      <div class="attendance-card">
        <div style="display: flex;">
          <div class="icon-box blue"><i class="fas fa-star"></i></div>
        <div class="attendance-count"><?php echo($present_standard); ?>/<?php echo($total_standard); ?></div>
        </div>
        <div class="attendance-label">STANDARD</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box orange"><i class="fas fa-user-graduate"></i></div>
        <div class="attendance-count"><?php echo($present_student); ?>/<?php echo($total_student); ?></div>
      </div>
        <div class="attendance-label">STUDENT</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box pink"><i class="fas fa-user-tie"></i></div>
        <div class="attendance-count"><?php echo($present_staff); ?>/<?php echo($total_staff); ?></div>
      </div>
        <div class="attendance-label">EMPLOYEE</div>
      </div>
    </div>
  </div>

   <div class="finance-section">
    <h3>FINANCE</h3>
    <div class="finance-cards">
      <div class="finance-card">
        <h4>Today Collection</h4>
        <div class="amount"><?php echo($income); ?></div>
        <div class="icon green"><i class="fas fa-rupee-sign"></i></div>
        <div class="percent"><i class="fas fa-arrow-down"></i> <span class="financegreen">0%</span> From Yesterday</div>
      </div>
      <div class="finance-card">
        <h4>Total Expenses</h4>
        <div class="amount"><?php echo($expense); ?></div>
        <div class="icon blue"><i class="fas fa-dollar-sign"></i></div>
        <div class="percent"><i class="fas fa-arrow-down"></i> <span class="financegreen">0%</span> From Yesterday</div>
      </div>
    </div>
  </div>


  <div class="attendance-box" style="margin-bottom: 20px;">
    <h3>Appeal</h3>
    <div class="attendance-row">
      <div class="attendance-card">
        <div style="display: flex;">
          <div class="icon-box blue"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($appeal_total); ?></div>
        </div>
        <div class="attendance-label">Total</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box orange"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($appeal_completed); ?></div>
      </div>
        <div class="attendance-label">Complete</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box pink"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($appeal_pending); ?></div>
      </div>
        <div class="attendance-label">Pending</div>
      </div>
    </div>
  </div>

    
     <div class="attendance-box" style="margin-bottom: 20px;">
    <h3>Gatepass</h3>
    <div class="attendance-row">
      <div class="attendance-card">
        <div style="display: flex;">
          <div class="icon-box blue"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($gatepass_total); ?></div>
        </div>
        <div class="attendance-label">Total</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box orange"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($gatepass_outside); ?></div>
      </div>
        <div class="attendance-label">Outside</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box pink"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($gatepass_inside); ?></div>
      </div>
        <div class="attendance-label">Inside</div>
      </div>
    </div>
  </div>

    <div class="attendance-box" style="margin-bottom: 20px;">
    <h3>Lead</h3>
    <div class="attendance-row">
      <div class="attendance-card">
        <div style="display: flex;">
          <div class="icon-box blue"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($total_leads); ?></div>
        </div>
        <div class="attendance-label">Total</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box orange"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($total_completed); ?></div>
      </div>
        <div class="attendance-label">Complete</div>
      </div>
      <div class="attendance-card">
        <div style="display: flex;">
        <div class="icon-box pink"><i class="fas fa-user-group"></i></div>
        <div class="attendance-count"><?php echo($lead_pending); ?></div>
      </div>
        <div class="attendance-label">Pending</div>
      </div>
    </div>
  </div>

  </div>
</body>
</html>
