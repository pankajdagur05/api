<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];  
$type='other';
$lid=$obj['lid'];
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
$arr['data']=array();

$s1=mysqli_query($host,"select * from complaint_status where user='$user' and comp_id='$lid' order by id desc");
while($d1=mysqli_fetch_array($s1)){
$row['date']=$d1['update_date'];
$row['status']=$d1['upd_sts'];
$row['intraction']=$d1['intraction'];
$row['responce']=$d1['responce'];
$row['sts_remark']=$d1['sts_remark'];
$row['staffname']=$d1['staffname'];
$arr['data'][]=$row;
}
 

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