<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type=$obj['type'];
$type2=$obj['type2'];
$name=$obj['name'];

$branchid=$obj['branchid'];
$typess=$obj['typefirst'];
$session=$obj['session'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;

$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
       
      $q="insert into exp set name='$name',type='$type',date='$date',ctype='$type2',cid='$branch'";
        mysqli_query($host,$q);
        $arr['status']=true;
    $arr['message']="success";
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