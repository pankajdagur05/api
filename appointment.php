<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$times=date('h:i:s');
if($username){
    
     
    
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        

$idd=$obj['id'];
$appoint=$obj['appoint'];
$sendtime=strtotime($obj['sendtime']);
$sendtimes=$obj['sendtime'];
$newremark=$obj['newremark'];
$close=$obj['close'];
$sts=$obj['sts'];
$typed=$obj['typed'];
$responce=$obj['responce'];
if(strtolower($close)=='yes'){
$close=",lead_close='yes'";    
}

$tim=date('h:i:s');
$time=time();
$unique=uniqid();
$path=$date.$time.$unique;
$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staffid=$d3['id'];
      $staffname=$d3['name'];
      $update_date=strtotime($appoint." ".$sendtimes);
if($close or $appoint){      
mysqli_query($host,"update lead set alert='0',sendtime='$sendtime',appoint='$appoint'$close where branch='$branch' and id='$idd' and sub_domain='$subdomain'");	
}
$ins ="insert into complaint_status set comp_id='$idd',update_date='$update_date',upd_sts='$sts',responce='$responce',intraction='$typed',sts_remark='$newremark',user='$branch',staffid='$staffid',staffname='$staffname',date='$date',time='$times'";
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