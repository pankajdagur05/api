<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$stype=$obj['type'];
 $branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$data=$obj['arr'];
$sid=$obj['sid'];
$ty=$obj['type'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
$s=mysqli_query($host,"select * from apk_token where token='$token'");
$r=mysqli_num_rows($s);
if($r==1){

$branch=$branchid;
      $user=$branch;
      $userby=$type;

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];



$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
  if($sid){
      for($a=0; $a<count($data); $a++){
          
          $bid=$data[$a]['bid'];
          $return=$data[$a]['returns'];
          $ee="insert into book_issue set bid='$bid',sid='$sid',type='$ty',date='$date',time='$time',cid='$user',returndate='$return',status='Issued'";
          mysqli_query($host,$ee);
          
          
          
      }
    $arr['message']='Successfully Issued'.$ee;
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