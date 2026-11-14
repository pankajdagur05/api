<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$fid=$_POST['fid'];
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$date=date('Y-m-d');
$time=date('h:i:s');
include("task-alert.php");
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){



 $s0=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");
$d0=mysqli_fetch_array($s0);
$sid=$d0['id'];        
   

function rows($h,$qr){
$s=mysqli_query($h,$qr);
return mysqli_num_rows($s);
}
$mid=$_REQUEST['mid'];
$msg=$_REQUEST['msg'];
$id=$_REQUEST['id'];
if($mid){
$id=$mid;    
$today=time();    
mysqli_query($host,"insert into tasksms set tid='$mid',msg='$msg',sender='$sid',type='$type',date='$today',user='$branch'");   

send_notification("Task",$msg,$mid,$subdomain,$host);
    
}

if($fid){
 $today=time();    
$msg=$_REQUEST['msg'];
if($msg){
mysqli_query($host,"insert into tasksms set tid='$fid',msg='$msg',sender='$sid',type='$type',date='$today',user='$branch'"); 
send_notification("Task",$msg,$fid,$subdomain,$host);
}
mysqli_query($host,"update task set finishdate='$today',status='complete' where id='$fid'");
    
}






if($_POST['taskid']){

$taskid=$_REQUEST['taskid'];
$id=$taskid;
$descr=$_REQUEST['descr'];
$fend=$_REQUEST['fend'];   
$resp=explode(',',$_REQUEST['resp']);
$today=time();

for($i=0;$i<count($resp);$i++)
{
$respss=$resp[$i];    
$q="insert into tasksms set tid='$taskid',msg='$descr',sender='$sid',type='$type',fdate='$fend',resp='$respss',date='$today',user='$branch'";
mysqli_query($host,$q);

}
send_notification("Task",$descr,$taskid,$subdomain,$host);
}


if($_POST['subid']){

$taskid=$_REQUEST['subid'];
$id=$_REQUEST['id'];
$id=$taskid;
$fdate=$_REQUEST['fdate'];
$today=time();
$q="insert into tasksms set tid='$taskid',msg='$descr',sender='$sid',type='$type',fdate='$fend',resp='$resp',date='$today',user='$branch'";
mysqli_query($host,$q);


send_notification("Task",'Sub Task Allotment',$taskid,$subdomain,$host);

}

if($_POST['ftask']){

$taskid=$_REQUEST['ftask'];
  
$q="update task set finishdate='$date' where id='$taskid' and user='$branch'";
mysqli_query($host,$q);

}

if($_FILES['uploadexcel']['tmp_name']){
$file=$_FILES['uploadexcel'];
$id=$_POST['taid'];
$today=time();
if($_FILES['uploadexcel']['tmp_name']){
	$sourcePath=$_FILES['uploadexcel']['tmp_name'];
$extension=pathinfo($_FILES['uploadexcel']['name'], PATHINFO_EXTENSION);
$targetPath =time().$id.".".$extension;
move_uploaded_file($sourcePath,"../../cpanel/task/".$targetPath);
mysqli_query($host,"insert into tasksms set tid='$id',msg='$targetPath',fomt='file',sender='$sid',type='$type',date='$today',user='$branch'");   
send_notification("Task",'File Sent In Review',$id,$subdomain,$host);
}

}
 
 
 

 
$arr['status']=true;
$arr['message']="success";
$arr['data']=array(); 

if($id){

$s0=mysqli_query($host,"select * from taskmember WHERE tid='$id' and sid='$sid' and user='$branch'");    
$d0=mysqli_fetch_array($s0);   

 $s=mysqli_query($host,"select * from tasksms where tid='$id' and user='$branch' order by id asc"); while($d=mysqli_fetch_array($s)){
          $ssid=$d['sender'];
      if($d['type']=='admin'){
        $s1=mysqli_query($host,"select * from admin_login where id='$ssid'");
        $d1=mysqli_fetch_array($s1);
        $name=$d1['name'];
         $typed="admin";
      }else{
          $s1=mysqli_query($host,"select * from staff where id='$ssid'");
        $d1=mysqli_fetch_array($s1);
        $name=$d1['name'];
        $typed="staff";
      }
      
      if($d['resp']){
          $ssi=$d['resp'];
           $s1=mysqli_query($host,"select * from staff where id='$ssi'");
        $d1=mysqli_fetch_array($s1);
        $respname=$d1['name'];
        
      }else{
           $respname="";
      }
      if($d['sender']==$sid){ $tt="received"; }else{ $tt="sent"; }

$row['responsible']=$respname;
$row['typed']=$typed;
$row['name']=$name;
$row['msgtype']=$tt;
$row['file']=$d['fomt'];
$row['id']=$d['id'];
$row['cdate']=date('d M Y',strtotime($d['cdate']));
$row['fdate']=date('d M Y',strtotime($d['fdate']));
$row['msg']=$d['fomt']=='file'?'https://smartechindia.in/leadpanel/cpanel/task/'.$d['msg']:$d['msg'];
$row['date']=date("d-m-Y", $d['date']);
$row['time']=date("h:i:s", $d['date']);
$arr['data'][]=$row;




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