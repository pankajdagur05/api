<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$subject=$obj['subject'];
$outoff=$obj['outoff'];
$idate=$obj['idate'];
$classtest=$obj['classtest'];
$num=count($data);
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
 

for($a=0; $a<$num; $a++){
$sid=$data[$a]['sid'];
$mark=$data[$a]['marks'];
$s=mysqli_query($host,"select * from classtest where sid='$sid' and idate='$idate' and subject='$subject' and cid='$branch'");
if(mysqli_num_rows($s)>0){
  
mysqli_query($host,"update classtest set mark='$mark' where sid='$sid' and idate='$idate' and subject='$subject' and cid='$branch'");    
}else{
    
mysqli_query($host,"insert into classtest set idate='$idate', mark='$mark',sid='$sid',time='$time',date='$date',cid='$branch',subject='$subject',outoff='$outoff',classtest='$classtest'");
 
}
    
} 
$arr3['status']=true;
$arr3['message']='success'; 

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