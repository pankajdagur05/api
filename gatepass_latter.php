<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];  
$type='other';
$gid=$obj['gid'];
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
$s3=mysqli_query($host,"select * from staff where id='$username'");
$d3=mysqli_fetch_array($s3);
$branch=$d3['branch'];
$user=$branch;

$arr['status']=true;
$arr['message']="success"; 
$timealert=strtotime("+15 minutes",time());
mysqli_query($host,"update gatepass_data set timealert='$timealert' where id='$gid' and cid='$user'");

}else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
    $arr['data']='';
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