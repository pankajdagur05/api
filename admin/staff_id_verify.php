<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$scan=$obj['scan'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
        $s1=mysqli_query($host,"select status from staff where id='$scan' and sub_domain='$subdomain'");
        if(mysqli_num_rows($s1)==1){
            $d1=mysqli_fetch_array($s1);
            if(strtolower($d1['status'])=='inactive'){
                 $arr['status']=false;
    $arr['message']='Your Account Is Inactive';
            }else{
            $arr['status']=true;
    $arr['message']='Verified Please Ready For Photo';
    $arr['sid']=$scan;
            }
        }else{
            $arr['status']=false;
    $arr['message']='Not Found';
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