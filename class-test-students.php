<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$standard=$obj['standard'];
$idate=$obj['idate'];
$classtest=$obj['classtest'];
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
      $staff=$d3['id'];

$s6=mysqli_query($host,"select * from session where cid='$branch' order by id desc limit 1");
       $d6=mysqli_fetch_array($s6);
        $session=$d6['name'];

$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
 $arr['tag'][]=array();



$q="select * from students where standard='$standard' and cid='$branch' and status='Active' and session='$session'";
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
     $sid=$d['id'];
      $row['sid']=$sid;
 $row['name']=$d['name'];
 $row['fname']=$d['fname'];
 $s1=mysqli_query($host,"select * from classtest where classtest='$classtest' and sid='$sid'");
 $d1=mysqli_fetch_array($s1);
 $row['mark']=$d1['mark'];
  $row['outoff']=$d1['outoff'];
     
     $arr['data'][]=$row;
     
     
      
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