<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$gate=$obj['gate'];
$gate1=$gate;
$gate2=$gate;
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
      $staffname=$d3['name'];
      $user=$branch;
      
      
$passvalue=$obj['passvalue'];
$passtype=$obj['passtype'];
$check=$obj['check'];

if(substr($passvalue,0,1)=='s'){
$sid=substr($passvalue,1,strlen($passvalue));
$s01=mysqli_query($host,"select * from students where cid='$user' and id='$sid'");
$d01=mysqli_fetch_array($s01);
$name=$d01['name'];
$mobile=$d01['smobile'];
$amobile=$d01['gmobile'];
$standard=$d01['standard'];
$subject="Student Id Gate Pass";
$desc="Student Entry Via ID Card ";
$regular='yes';
$stype='student';
$s=mysqli_query($host,"select gatepass_person.gatepass_id from gatepass_person INNER JOIN gatepass ON gatepass.id=gatepass_person.gatepass_id where gatepass_person.sid='$sid' and gatepass_person.user='$user' and gatepass.type='student'");    
if(mysqli_num_rows($s)>0){
$d=mysqli_fetch_array($s);
$pa=$d[0];
}else{
$q="insert into gatepass set app_name='$name',app_mobile='$mobile',at_mobile='$amobile',com_subject='$subject',com_desc='$desc',passcode='$passvalue',upload_pics='$fname',type='$stype',user='$user',staffid='$staff',staffname='$staffname',regular='$regular',date='$date',time='$time'";    
mysqli_query($host,$q); 
$s00=mysqli_query($host,"select * from gatepass where user='$user' and staffid='$staff' order by id desc limit 1");
$d00=mysqli_fetch_array($s00);
$tid=$d00['id'];
mysqli_query($host,"insert into gatepass_person set gatepass_id='$tid',sid='$sid',type='$standard',date='$date',time='$time',user='$user',staffid='$staff',staffname='$staffname'");
$pa=$tid;
}    
}else{
$pa = ltrim($passvalue, "0");     
}





 

$q="select * from gatepass where id='$pa' and user='$user' and passcode='$passvalue' and archive IS NULL";
  


$s31=mysqli_query($host,$q);

if(mysqli_num_rows($s31)>0)
{
$d=mysqli_fetch_array($s31);
$typed=$d['type'];
$arr['status']=true;
$arr['message']='Successfully Verified';  
$arr['data']=array();
$row['id']=$d['id']; 
$cid=$d['id'];
$row['name']=datacheck($d['app_name']); 
$row['mobile']=datacheck($d['app_mobile']); 
$row['amobile']=datacheck($d['at_mobile']); 
$ss11=mysqli_query($host,"select * from gatepass_data where gatepass_id='$cid' and cid='$user' and date='$date' order by id desc limit 1");
$dd11=mysqli_fetch_array($ss11);
if($dd11['checkin'])
{
 if($dd11['checkout'])
{
 $row['check']="Check In";
}
else
{
  $row['check']="Check Out";  
}
    
}
else
{
   $row['check']="Check In";  
}
$row['subject']=datacheck($d['com_subject']);
$row['type']=datacheck($d['type']);
$row['desc']=datacheck($d['com_desc']); 
$row['date']=$d['date']; 
$row['time']=$d['time'];
$arr['data'][]=$row;

if($check){


$ss1=mysqli_query($host,"select * from gatepass_data where gatepass_id='$pa' and cid='$user' and date='$date' order by id desc limit 1");
$dd1=mysqli_fetch_array($ss1);

$times=time();
$dates=date();
if($dd1['checkin'])
{
   if($dd1['checkout'])
   {
       $arr['message']='Successfully Check In';
mysqli_query($host,"insert into gatepass_data set gatepass_id='$cid',checkin='$times',checkin_status='$passtype',checkin_gateno='$gate1',type='$typed',staffname='$staffname',staffid='$staff',date='$date',time='$time',cid='$user'"); 

   }else
   {
     $arr['message']='Successfully Check Out';  
     mysqli_query($host,"update gatepass_data set checkout='$times',checkout_status='$passtype',checkout_gateno='$gate1',staffname_sec='$staffname',type='$typed',staffid_sec='$staff',date='$date',time='$time' where gatepass_id='$cid' and date='$date' and cid='$user'"); 

   }   
}else
{
     $arr['message']='Successfully Check In';
mysqli_query($host,"insert into gatepass_data set gatepass_id='$cid',checkin='$times',checkin_status='$passtype',checkin_gateno='$gate1',type='$typed',staffname='$staffname',staffid='$staff',date='$date',time='$time',cid='$user'"); 
}


}

}else
{
    $arr['status']=false;
    $arr['message']='Passcode Not Match';  
    
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