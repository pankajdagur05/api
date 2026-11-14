<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
    
$sid=$_REQUEST['sid'];
$remark=$_REQUEST['remark'];
$idate=$_REQUEST['idate'];
 
      $s3=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");
      $d3=mysqli_fetch_array($s3);
      $adminid=$d3['name'];
$q="insert into  fees_status set idate='$idate',remark='$remark',date='$date',cid='$branch',sid='$sid',type='$type',uid='$adminid'";
mysqli_query($host,$q);  

 
$s03=mysqli_query($host,"select * from fees_status where sid='$sid' and cid='$branch'");
$ro=mysqli_num_rows($s03);
if($ro>0){
$s04=mysqli_query($host,"select * from lable where level='$ro'");
$d04=mysqli_fetch_array($s04);
$lbl=$d04['name'];
mysqli_query($host,"update students set feesflag='$lbl' where id='$sid' and cid='$branch'");
}
$arr['status']=true;
$arr['message']='success';






}else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}

echo $json = json_encode($arr);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>