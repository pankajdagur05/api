<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$bid=$obj['bid'];
$token=$obj['token'];
if($bid){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        $qr=$bid.','.time();
        mysqli_query($host,"update branch_category set qr='$qr' where id='$bid'");
        $arr['status']=true;
    $arr['message']=$qr;
        
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