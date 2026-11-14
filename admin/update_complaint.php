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
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    
     
    $r=mysqli_num_rows($s);
    
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        

$idd=$obj['id'];
$remark=$obj['remark'];
$status=$obj['status'];
$mode=$obj['mode'];



$time=date('h:i:s');

$branch=$branchid;
$user=$branch;
$staffname=$type; 
$update_date=date('Y-m-d');
if($mode=="lead")
{
    mysqli_query($host,"update lead set status='$status' where branch='$branch' and id='$idd' and sub_domain='$subdomain'");	

}
$stime=$_POST['stime'];
$sdate=$_POST['sdate']; 
if($sdate){
$dt=$sdate." ".$stime;    
$sch=strtotime($dt);  
mysqli_query($host,"update complaint set schedule='$sch' where id='$idd' and user='$user'");
}
$ins ="insert into complaint_status2 set comp_id='$idd',update_date='$update_date',upd_sts='$status',sts_remark='$remark',user='$branch',staffid='$staffid',staffname='$staffname',date='$date',time='$time' ";
mysqli_query($host,$ins);
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