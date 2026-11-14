<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_REQUEST['delete'];
$username=$_REQUEST['userid'];
$subdomain=$_REQUEST['subdomain'];
$token=$_REQUEST['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
    
$sid=$_REQUEST['sid'];
$remark=$_REQUEST['remark'];
$idate=$_REQUEST['idate'];
$respo=$_REQUEST['respo'];
$itype=$_REQUEST['itype'];

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staffid=$d3['name'];
     $q="insert into  fees_status set remark='$remark',date='$date',cid='$branch',sid='$sid',type='staff',uid='$staffid',respo='$respo',itype='$itype',staff='$username'";
      if($idate){
          $q.=",idate='$idate'";
      }

if(mysqli_query($host,$q)){
if($idate){    
 $s03=mysqli_query($host,"select * from fees_status where sid='$sid' and cid='$branch'");
$ro=mysqli_num_rows($s03);
if($ro>0){
$s04=mysqli_query($host,"select * from lable where level='$ro'");
$d04=mysqli_fetch_array($s04);
$lbl=$d04['name'];
mysqli_query($host,"update students set feesflag='$lbl' where id='$sid' and cid='$branch'");
}   
}
  $arr['status']=true;
$arr['message']='Successfully Update';  
}else{
  $arr['status']=false;
$arr['message']='Something Went Wrong';  
}

 








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