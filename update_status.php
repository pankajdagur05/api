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



$time=time('h:i:s');

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $user=$d3['id'];
      
mysqli_query($host,"update $mode set status='$status' where branch='$branch' and id='$idd' and sub_domain='$subdomain'");	
$q="insert into update_status set remark='$remark',typeid='$idd',status='$status',date='$date',time='$time',branch='$branch',type='other',user='$user',mode='$mode',sub_domain='$subdomain'";
mysqli_query($host,$q);
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