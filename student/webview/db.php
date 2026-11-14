<?php 
$host=mysqli_connect("localhost","marudhar_lms","~Rxkoutds1Kj","marudhar_lms");

$host2=mysqli_connect("localhost","marudhar_management","CH3_KaJ7JlcA","marudhar_management");
date_default_timezone_set('Asia/Kolkata');
$date=date('Y-m-d');
$time=date('h:i:s');
function datacheck($data)
{
return ucfirst($data);    
}
function checking($var){
if(strpos($var,"'") === false){

if(strpos($var,"php") === false){
if(strpos($var,"script") === false){

if(strpos($var,"or") === false){

return $var;

}else{
return NULL;
}


}else{
return NULL;
}

}else{
return NULL;
}


}else{
return NULL;
}

}
function logError($error) {
    $logFile = 'mysqli_errors.log'; // Path to the log file
    $currentDate = date('Y-m-d H:i:s');
    $errorMessage = "[$currentDate] $error\n";
    file_put_contents($logFile, $errorMessage, FILE_APPEND);
}


function activity_log($host,$activity_name,$branch_id,$user_id)
{
    $sql_act="INSERT INTO `activity_log` ( `activity_name`, `cid`, `user_id`) VALUES ('".$activity_name."', '".$branch_id."', '".$user_id."')";
     $q=mysqli_query($host,$sql_act);
    
}

?>