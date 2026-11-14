<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true); 
$subdomain=$_POST['subdomain'];
$token=$_POST['token'];
$type='admin';
$date=date('Y-m-d');
$time=date('h:i:s');
$title=$_POST['subject'];
$fdate=$_POST['fdate'];
$descr=$_POST['descr'];
$mode=$_POST['type'];
$tdate=$_POST['tdate'];
$standard=explode(",",$_POST['standard']);
$delete=$_POST['delete'];
$update=$_POST['update'];
 
$branchid=$_POST['branchid'];
$type=$_POST['typefirst'];
$session=$_POST['session'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token' and type='$type' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
  
if($delete){
mysqli_query($host,"delete from calender where id='$delete' and cid='$user'");    
}

if($title){
    if($update){
    if($tdate){
        $key=",tdate='$tdate'";
    }else{
        $key=",tdate='$fdate'";
    }
    
mysqli_query($host,"update calender set mode='$mode',title='$title',fdate='$fdate',descr='$descr' $key,date='$date' where id='$update' and cid='$user'");
    
    }else{
         if($tdate){
        $key=",tdate='$tdate'";
    }else{
        $key=",tdate='$fdate'";
    }
     $q="insert into calender set mode='$mode',title='$title',fdate='$fdate',descr='$descr' $key,date='$date',cid='$user'";
mysqli_query($host,$q);
$s09=mysqli_query($host,"select id from calender where cid='$user' order by id desc limit 1");
$d09=mysqli_fetch_array($s09);
$update=$d09[0];
}

mysqli_query($host,"delete from calender_attachment where cid='$user' and cal='$update'");
for($k=0; $k<count($standard); $k++){
$name=$standard[$k];
if($name){
mysqli_query($host,"insert into calender_attachment set cal='$update',standard='$name',cid='$user'");   
}
}

}

$arr['status']=true;
$arr['message']="success";
$arr['data']=array();
$s=mysqli_query($host,"select * from calender where cid='$user' and tdate>='$date' order by fdate,tdate asc");
while($d=mysqli_fetch_array($s)){
$update=$d['id'];
$row['subject']=$d['title'];
$row['descr']=$d['descr'];
$row['date']=date('d M Y',strtotime($d['date']));
$row['fdate']=date('d M Y',strtotime($d['fdate']));
$row['ffdate']=$d['fdate'];
$row['ttdate']=$d['tdate'];
$row['tdate']=$d['tdate']==$d['fdate']?"":date('d M Y',strtotime($d['tdate'])); 
$row['type']=$d['mode'];
$row['id']=$d['id'];
$row['standard']=array();
$s90=mysqli_query($host,"select * from calender_attachment where cid='$user' and cal='$update'");
while($d90=mysqli_fetch_array($s90)){
$row2['name']=$d90['standard'];
$row['standard'][]=$row2;
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