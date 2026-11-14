<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
$remark=$obj['remark'];
$sid=$obj['sid'];

$delete=$obj['delete'];
$update=$obj['update'];
$sendtime=strtotime($obj['sendtime']);
if($enddate==""){
$enddate=$startdate;    
}

if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token' and type='$type' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
        $s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
        
if($delete){
mysqli_query($host,"delete from remark where id='$delete'");  
$arr['status']=true;
$arr['message']='Successfully Deleted';
}

if($remark){
    if($update){
    
mysqli_query($host,"update remark set remark='$remark',date='$date' where id='$update'");
    $arr['status']=true;
$arr['message']='Successfully Updated';
    }else{
    $q="insert into remark set sid='$sid',remark='$remark',date='$date',cid='$branch',user='$username'";
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='Successfully Inserted';
        
    }
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