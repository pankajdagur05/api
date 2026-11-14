<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token']; 
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$lat=$obj['lat'];
$lon=$obj['lon'];
$meter=$obj['meter'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 
 mysqli_query($host,"insert into location_history set lat='$lat',lon='$lon',meter='$meter',cid='$branch',userby='$username',date='$date',time='$time'");
 mysqli_query($host,"update branch_category set lat='$lat',lon='$lon',meter='$meter' where id='$branch'");

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