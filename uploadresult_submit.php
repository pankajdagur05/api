<?php
include("../db.php");
error_reporting(0);
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$msg=$obj['msg'];
$outoff=$obj['outoff'];
$subject=$obj['subject'];
$standard=$obj['standard'];
$exam=$obj['exam'];
$outoff=$obj['outoff'];
$num=count($data);
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select id from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select branch from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      
       $s6=mysqli_query($host,"select name from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
       $session=$d6['name'];
      
      $ss=mysqli_query($host,"select * from subject_with_exam_marks where cid='$branch' and standard='$standard' and subject='$subject' and exam='$exam' and session='$session'");
      $dd=mysqli_fetch_array($ss);
      
      
      $oraloutoff=$dd['oral'];
      $practicalout=$dd['practical'];
      $thoeryout=$dd['theory'];
      $sectionalout=$dd['sectional'];
      //$outoff=$dd['overall'];
      $default_outoff = $dd['overall'];
      
      
$updatequery = "";
$insert = "insert into uploadresult (exam,obtain,subject,standard,outoff,sid,date,cid,oral,practical,theory,sectional,outoff_oral,outoff_practical,outoff_theory,outoff_sectional,session,upload_by) VALUES";

// ✅ Marks format function
function formatMarks($val) {
    $val = trim($val);

    if ($val === "AB" || $val === "NC") {
        return $val;
    } elseif ($val === "00") {
        return "AB";
    } elseif ($val === "000") {
        return "NC";
    } elseif ($val === "0") {
        return "0";
    } elseif ($val === "") {
        return "";
    } else {
        return $val;
    }
}

// ✅ Calculate obtain intelligently
function calculateObtain($m1, $m2, $m3, $m4) {
    $arr = [$m1, $m2, $m3, $m4];
    $total = 0;
    $hasNumeric = false;
    $hasAB = false;
    $hasNC = false;

    foreach ($arr as $val) {
        if (is_numeric($val)) {
            $total += $val;
            $hasNumeric = true;
        } elseif ($val === "AB") {
            $hasAB = true;
        } elseif ($val === "NC") {
            $hasNC = true;
        }
    }

    // Priority: numeric > AB > NC
    if ($hasNumeric) {
        return $total;
    } elseif ($hasAB) {
        return "AB";
    } elseif ($hasNC) {
        return "NC";
    } else {
        return "";
    }
}

// ✅ Main loop
for($a=0; $a<$num; $a++){
    $sid = $data[$a]['sid'];

    // Format sub-marks
    $marks1 = formatMarks($data[$a]['marks1']);
    $marks2 = formatMarks($data[$a]['marks2']);
    $marks3 = formatMarks($data[$a]['marks3']);
    $marks4 = formatMarks($data[$a]['marks4']);

    // Calculate obtain
    $marks = calculateObtain($marks1, $marks2, $marks3, $marks4);

    // Only proceed if at least one sub-mark exists
    if($marks1 !== "" || $marks2 !== "" || $marks3 !== "" || $marks4 !== ""){

        $s = mysqli_query($host,"SELECT id,outoff FROM uploadresult WHERE sid='$sid' AND subject='$subject' AND exam='$exam' AND cid='$branch'");

        // if(mysqli_num_rows($s) > 0){
        //     if($outoff == ""){
        //         $d1 = mysqli_fetch_array($s); 
        //         $outoff = $d1['outoff'];
        //     }
        
        
        $current_outoff = $default_outoff;

if($current_outoff == "" || $current_outoff == NULL){
    $d1 = mysqli_fetch_array($s); 
    $current_outoff = $d1['outoff'] ?? 0;
}

$current_outoff = $current_outoff ?: 0;
        
        if(mysqli_num_rows($s) > 0){ 

            // Update query
            $updatequery .= "UPDATE uploadresult SET 
                outoff='$current_outoff',
                oral='$marks1',
                outoff_oral='$oraloutoff',
                outoff_practical='$practicalout',
                outoff_theory='$thoeryout',
                outoff_sectional='$sectionalout',
                practical='$marks2',
                theory='$marks3',
                sectional='$marks4',
                obtain='$marks',
                date='$date' 
                WHERE sid='$sid' AND exam='$exam' AND subject='$subject' AND cid='$branch'; \n";  

        } else {
            // Insert query
            $insert .= "('$exam','$marks','$subject','$standard','$current_outoff','$sid','$date','$branch',
                '$marks1','$marks2','$marks3','$marks4',
                '$oraloutoff','$practicalout','$thoeryout','$sectionalout','$session','$username'),";    
        }
    }
}

// Remove last comma from insert
$insert = rtrim($insert, ",");
 
$result_insert=mysqli_query($host,$insert);
if(!$result_insert)
{
  $error = "Query failed: " . mysqli_error($host) . " | Query: " . $insert;
  logError($error);
    
}

if($updatequery)
{
$result_update=mysqli_multi_query($host,$updatequery);

if(!$result_update)
{
  $error = "Query failed: " . mysqli_error($host) . " | Query: " . $updatequery;
  logError($error);
    
}
}
 
$arr3['status']=true;
$arr3['message']="Success"; 

}else{
    $arr3['status']=false;
    $arr3['message']='Token Not Match';
    $arr3['data']='';
}

echo $json = json_encode($arr3);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}

?>