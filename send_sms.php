<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$msg=$obj['msg'];
$lang=$obj['lang'];
$num=count($data);
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
      
$strlen=strlen($msg);
if($lang=='English' or $lang=='english'){
   $count=ceil($strlen/160); 
}else{
   $count=ceil($strlen/60); 
}


$output['token']="b32968c745ceb6a5467fea8e73f0a866";
$output['type']=1;
$output['temp']='DALERT';
$output['lang']=$lang;
$output['data']=array();
for($a=0; $a<$num; $a++){
    $mobile=$data[$a]['mobile'];
$row['mobile']=$data[$a]['mobile'];
$row['msg']=$msg;
$output['data'][]=$row;
mysqli_query($host,"insert into sms_send set msg='$msg',mobile='$mobile',branch='$branch',date='$date',countmsg='$count',type='$lang',status='sent',lngh='$strlen'");
    
}

include("../bulk-sms.php");

$arr3['status']=true;
$arr3['message']='success'; 

}else{
    $arr3['status']=false;
    $arr3['message']='Token Not Match';
    $arr3['data']='';
}

echo $json = json_encode($arr3);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>