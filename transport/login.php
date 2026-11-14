<?php
include("../../db.php");
$json=file_get_contents('php://input');
$obj=json_decode($json,true);
$username=$obj['username'];
$ppass=$obj['sid'];
$device=$obj['device'];
$sid=$obj['ids'];
if($ppass){
$password=$ppass; 
}else{
if($sid){
$password=$obj['password']; 
}else{
$password=md5($obj['password']);  
}
}
function token($sub,$t,$user,$h){
    $date=date('Y-m-d');
    $time=date('h:i:s');
    $ip=$_SERVER['REMOTE_ADDR'];
    $token=$sub;
     $token.=uniqid();
    if(mysqli_query($h,"insert into apk_token set token='$token',date='$date',ip='$ip',type='$t',user='$user',sub_domain='$sub'")){
        
        return $token;
    }
    
}
if($username){

$arr=array();

$q="select * from driver where status='Active' and mobile='$username' and password='$password'";
if($sid){
    $q.=" and id='$sid'";
}
$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
$like=mysqli_fetch_array($sql);
if($r>0){
$row2['status']=true;
$row2['message']="success";
$row2['token']=token($like['cid'],$type,$username,$host);

}else{
       $row2['status']=false;
		 $row2['message']="Userid & Password Did Not Match"; 
}
    
echo $json = json_encode($row2);
}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>