<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
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
    $s=mysqli_query($host,"select * from apk_token where token='$token' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
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
    
mysqli_query($host,"insert into remark set sid='$sid',remark='$remark',date='$date',cid='$branch',user='$username'");
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