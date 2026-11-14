<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
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
$title=$obj['title'];
$startdate=$obj['startdate'];
$mode=$obj['mode'];
$enddate=$obj['enddate'];
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
        
$s=mysqli_query($host,"select id from admin_login where sub_domain='$subdomain'"); 
$d=mysqli_fetch_array($s);
$sid=$d['id'];

if($delete){
mysqli_query($host,"delete from reminder where id='$delete'");    
}

if($title){
    if($update){
    
mysqli_query($host,"update reminder set mode='$mode',title='$title',startdate='$startdate',sendtime='$sendtime',enddate='$enddate',date='$date' where id='$update' and sub_domain='$subdomain'");
    
    }else{
     $q="insert into reminder set mode='$mode',title='$title',startdate='$startdate',sendtime='$sendtime',enddate='$enddate',date='$date',type='$type',sid='$sid',sub_domain='$subdomain'";
mysqli_query($host,$q);
}
}

$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$s=mysqli_query($host,"select * from reminder where type='$type' and sid='$sid' and sub_domain='$subdomain' order by startdate desc");
while($d=mysqli_fetch_array($s)){
$row['title']=$d['title'];
$row['startdate']=$d['startdate'];
$row['enddate']=$d['enddate'];
$row['sendtime']=date('h:i:s',$d['sendtime']);
$row['mode']=$d['mode'];
$row['id']=$d['id'];
$arr['data'][]=$row;
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