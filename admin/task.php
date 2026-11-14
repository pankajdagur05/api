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
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 

 
$fromdate=$obj['fromdate'];
$todate=$obj['todate'];
$trip=$obj['trip'];
$ctype=$obj['ctype'];
$types=$obj['type'];
if($types==""){ $types="other";}
$descrip=$obj['descrip'];
$tid=$obj['update'];
if($trip){
if($tid){
$q="update task set category='$ctype',title='$trip',fromdate='$fromdate',todate='$todate',descr='$descrip',date='$date',time='$time' where id='$tid' and user='$user'";    
mysqli_query($host,$q);   
}else{    
$q="insert into task set category='$ctype',title='$trip',type='$types',fromdate='$fromdate',todate='$todate',descr='$descrip',date='$date',status='ongoing',time='$time',user='$user'";    
mysqli_query($host,$q);
$s3=mysqli_query($host,"select * from task where user='$user' order by id desc");
$d3=mysqli_fetch_array($s3);
$id=$d3['id'];
$today=time();
mysqli_query($host,"insert into tasksms set msg='$descrip',tid='$id',sender='$sid',type='$type',date='$today',user='$branch'");
} 
}

if($delete){
mysqli_query($host,"delete from task where id='$delete' and user='$user'");    
}
 
 
 

 
$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$s=mysqli_query($host,"select * from task where user='$user' order by id desc");
while($d=mysqli_fetch_array($s)){
   
$tid=$d['id'];
$row['category']=$d['category']; 
$row['title']=$d['title']; 
$row['fromdate']=$d['fromdate']; 
$row['todate']=$d['todate']; 
$row['descr']=$d['descr']; 
$row['date']=date('d M Y',strtotime($d['date'])); 
$row['status']=$d['status']; 
$row['time']=$d['time']; 
$row['id']=$d['id'];
$row['finishdate']=$d['finishdate'];
$row['data']=array();

$s1=mysqli_query($host,"select * from taskmember INNER JOIN staff ON staff.id=taskmember.sid where taskmember.tid='$tid' and taskmember.user='$user' group by staff.id");
while($d1=mysqli_fetch_array($s1)){ $sid=$d1['sid'];  

$row1['name']=$d1['name'];
$row1['sid']=$d1['sid'];
$row1['type']=$d1['type'];
$row1['data']=array();

$s2=mysqli_query($host,"select * from tasksms where resp='$sid' and user='$user' and tid='$tid'"); while($d2=mysqli_fetch_array($s2)){
$row2['msg']=$d2['msg'];
$row2['date']=date('D M Y',$d2['date']);
$row2['cdate']=$d2['cdate']==''?'':date('D M Y',strtotime($d2['cdate']));
$row2['finish']=$d2['fdate']==''?'':date('D M Y',strtotime($d2['fdate']));
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