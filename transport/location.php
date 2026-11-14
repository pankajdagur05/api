<?php error_reporting(4);
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$username=$obj['userid']; 
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s a');
$tim=time();
$route=$obj['route'];
$lat=$obj['lat'];
$lon=$obj['lon'];
$location=$lat.','.$lon;
$bid=$obj['branch'];
if($username){
     $s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");
    $r=mysqli_num_rows($s);
    if($r==1){ 
       
       mysqli_query($host,"update driver set lat='$lat',lon='$lon',update_time='$tim' where mobile='$username'");
       if($route){
       mysqli_query($host,"insert into trip_location set location='$location',date='$date',time='$tim',route='$route',username='$username'");
       }
         $ar1['status']=true;
		 $ar1['message']="insert into trip_location set location='$location',date='$date',time='$tim',route='$route',username='$username'";
		 echo $json=json_encode($ar1); 
        
    }else{
         $ar1['status']=false;
		 $ar1['message']='Token Did Not Match';
		 echo $json=json_encode($ar1); 
    }
    
}else
{
    
    ?>
    <script>
        window.location="http://edpluss.com";        
    </script>
    <?php
}
?>