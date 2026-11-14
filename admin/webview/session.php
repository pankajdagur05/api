<?php include("../../../db.php");  
$subdomain=$_GET['subdomain'];
$token=$_GET['token'];
$branchid=$_GET['branchid'];
$type=$_GET['type'];
$session=$_GET['session'];
$userid=$_GET['userid'];
$user=$branchid;
$cid=$user;
$s=mysqli_query($host,"select * from apk_token where token='$token' and sub_domain='$subdomain'");
$r=mysqli_num_rows($s);
if($r==1){
 
}else{
header("Location: ../../../../index.php?mode=r");
exit();       
}
?>
