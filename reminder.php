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
        
if($delete){
mysqli_query($host,"delete from reminder where id='$delete'");    
}

if($title){
    if($update){
    
mysqli_query($host,"update reminder set mode='$mode',title='$title',startdate='$startdate',sendtime='$sendtime',date='$date',enddate='$enddate' where id='$update' and sub_domain='$subdomain'");
    
    }else{
    
mysqli_query($host,"insert into reminder set mode='$mode',title='$title',startdate='$startdate',sendtime='$sendtime',date='$date',enddate='$enddate',type='$type',sid='$username',sub_domain='$subdomain'");
}
}


$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$s=mysqli_query($host,"select * from reminder where type='$type' and sid='$username' and sub_domain='$subdomain' order by startdate desc");
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