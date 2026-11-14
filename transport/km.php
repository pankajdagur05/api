<?php include("../../db.php");

$json = file_get_contents('php://input');
$obj= json_decode($json,true); 
$username=$obj['userid']; 
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
 
 $time=date('h:i:s a');
$route=$obj['route'];
$bid=$obj['branch']; 
$km=$obj['km'];
if($username){   
  
      
     $s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
        mysqli_query($host,"update trip set km='$km' where cid='$bid' and route='$route'");
        $ar1['status']=true;
		 $ar1['message']="update trip set km='$km' where cid='$bid' and route='$route'";
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