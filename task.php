<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$username=$obj['userid'];
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){


$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $user=$branch;



 
$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$s=mysqli_query($host,"select *,task.id as ttid from task INNER JOIN taskmember ON taskmember.tid=task.id where taskmember.sid='$username' and task.user='$user' order by task.id desc");
while($d=mysqli_fetch_array($s)){
   
$tid=$d['ttid'];
$row['category']=$d['category']; 
$row['title']=$d['title']; 
$row['fromdate']=date('d m Y',strtotime($d['fromdate']));   
$row['todate']=date('d m Y',strtotime($d['todate']));  
$row['descr']=$d['descr']; 
$row['date']=$d['date']; 
$row['status']=$d['status']; 
$row['time']=$d['time']; 
$row['type']=$d['type']; 
$row['id']=$d['ttid'];
$row['finishdate']=$d['finishdate']?date('d m Y',strtotime($d['finishdate'])):""; 
$row['fdate']=$d['finishdate']?date('d m Y',strtotime($d['finishdate'])):""; 
$row['data']=array();

$s1=mysqli_query($host,"select * from taskmember INNER JOIN staff ON staff.id=taskmember.sid where taskmember.tid='$tid' and taskmember.user='$user'");
while($d1=mysqli_fetch_array($s1)){ 
    $sid=$d1['sid'];  

$row1['name']=$d1['name'];
$row1['sid']=$d1['sid'];
$row1['type']=$d1['type'];
$row1['data']=array();

$s2=mysqli_query($host,"select * from tasksms where resp='$sid' and user='$user' and tid='$tid'");
while($d2=mysqli_fetch_array($s2)){
$row2['msg']=$d2['msg'];
$row2['id']=$d2['id'];
$row2['date']=date('d m Y',$d2['date']);
if($d2['cdate']=='Pending')
{
$row2['cdate']="Pending";   
}else
{
$row2['cdate']=$d2['cdate']==''?'':date('d M Y',strtotime($d2['cdate']));
}
$row2['finish']=$d2['fdate']==''?'':date('d M Y',strtotime($d2['fdate']));
$row1['data'][]=$row2;
}
$row['data'][]=$row1;



}
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