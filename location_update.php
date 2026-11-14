<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$username=$obj['userid'];
$type='staff'; 
$lat=$obj['lat']; 
$lon=$obj['lon']; 
$location=$lat.','.$lon;
$date=date('Y-m-d');
$time=date('h:i:s a');
$tim=time();
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
         
    mysqli_query($host,"update staff set mylocation='$location',locatime='$time' where id='$username' and sub_domain='$subdomain'");  
     $arr['status']=true;
    $arr['message']='Location Updated';
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