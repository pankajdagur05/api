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
$fromdate=$obj['fromdate'];
$direct=$obj['direct'];
$todate=$obj['todate'];
$subject=$obj['subject'];
$standard=$obj['standard'];
$staff=$obj['staff']; 
$update=$obj['update'];
$close=$obj['close'];
$slotid=$obj['slotid'];
$stype=$obj['stype'];
$title=$obj['title'];
$edate=$obj['edate'];
$ptime=$obj['time'];
$period=$obj['period'];
$notes=$obj['notes'];
$qa=$obj['qa'];
$task=$obj['task'];
$manager=$obj['manager'];


if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token' and type='$type' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
$arr['status']=true;
$arr['message']='success';        
 
if($fromdate){
    if($update){
    
mysqli_query($host,"update lession_slot set  manager='$manager',direct='$direct',fromdate='$fromdate',todate='$todate',staff='$staff',date='$date',notes1='$notes',qa1='$qa',task1='$task' where id='$update' and cid='$user'");
    
    }else{
          $s0=mysqli_query($host,"select * from lession_slot where session='$session' and cid='$user' and fromdate<='$fromdate' and todate>='$fromdate' and status<>'closed' and subject='$subject' and standard='$standard'");
        if(mysqli_num_rows($s0)==0){
    $ss=mysqli_query($host,"select * from lession_slot where cid='$user' and subject='$subject' and standard='$standard'");  
    $seq=mysqli_num_rows($ss)+1;
     $q="insert into lession_slot set manager='$manager',direct='$direct',fromdate='$fromdate',period='$period',ptime='$ptime',todate='$todate',seq='$seq',session='$session',subject='$subject',staff='$staff',status='pending',cordi='$userid',cid='$user',date='$date',notes1='$notes',qa1='$qa',task1='$task'";
mysqli_query($host,$q);
$s09=mysqli_query($host,"select id from lession_slot where cid='$user' order by id desc limit 1");
$d09=mysqli_fetch_array($s09);
$slid=$d09[0];
$standard=explode(",",$standard);
for($a=0; $a<count($standard); $a++){
$stand=$standard[$a];
mysqli_query($host,"insert into lession_standard set name='$stand',sid='$slid',cid='$user'");

}
}else{
  $arr['status']=false;
$arr['message']='Already Created Slot In THis Date';  
}


}
}

if($close){
mysqli_query($host,"update lession_slot set status='closed' where id='$close'");    
}

if($delete){
mysqli_query($host,"delete from lession_slot where id='$delete'");    
}

if($slotid){
mysqli_query($host,"insert into lession_exam set title='$title',type='$stype',idate='$date',cid='$user',submitby='$userid',date='$date'");    
}



$arr['data']=array();
$arr['staff']=array();
if($obj['teacher']){
$teacher=$obj['teacher'];
$q="select lession_slot.*,lession_standard.name as sname from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.cid='$user' and staff='$teacher' and lession_standard.cid='$user' order by lession_slot.id desc";    
}else{
$q="select lession_slot.*,lession_standard.name as sname from lession_slot INNER JOIN lession_standard ON lession_standard.sid=lession_slot.id where lession_slot.cid='$user'  and lession_slot.subject='$subject' and lession_standard.name='$standard' and lession_standard.cid='$user' order by lession_slot.id desc";    
}
$s=mysqli_query($host,$q);
while($d=mysqli_fetch_array($s)){
    
    if($d['direct']==1){
    
        $start = strtotime($d['fromdate']);
$end = strtotime($d['todate']);
$count = 0;
$holidays=array();
while(date('Y-m-d', $start) <= date('Y-m-d', $end)){
    $mydate=date('Y-m-d',$start);
  $count += date('N', $start) <=6 ? 1 : 0;
  $start = strtotime("+1 day", $start);
   $s0=mysqli_query($host,"select * from calender where fdate<='$mydate' and tdate>='$mydate' and cid='$user' and mode='holiday'");
    if(mysqli_num_rows($s0)>0){
    array_push($holidays,$mydate);    
    }
}
$days=$count-count($holidays);
$row['days']=$days;
    }else{
        $row['days']=0;
    }    
    
$sid=$d['id'];    
if($d['fromdate']<=$date and $d['todate']>=$date){ $sts='On Going'; }else{ if($d['fromdate']>$date){ $sts='Upcoming';  }else{ $sts='Past';  } }   
$s0=mysqli_query($host,"select count(id),sum(if(length(idate)>0,1,0)) from lession_slot_plan where cid='$user' and slotid='$sid'");
$d0=mysqli_fetch_array($s0);
$staff=$d['staff'];
$s01=mysqli_query($host,"select name from staff where id='$staff'");
$d01=mysqli_fetch_array($s01);
$row['fromdate']=date('d-M-Y',strtotime($d['fromdate']));
$row['todate']=date('d-M-Y',strtotime($d['todate']));
$row['todate2']=$d['todate'];
$row['fromdate2']=$d['fromdate'];
$row['subject']=$d['subject']; 
$row['time']=$d['ptime']; 
$row['period']=$d['period']; 
$row['standard']=$d['sname'];
$row['notes']=$d['notes']; 
$row['qa']=$d['qa']; 
$row['task']=$d['task'];
$row['direct']=$d['direct']; 
$row['notes']=$d['notes1'];
$row['qa']=$d['qa1'];
$row['task']=$d['task1'];
$row['seq']=$d['seq'];
$row['staff']=$d['staff'];
$row['sname']=$d01['name'];
$row['status']=$d['status'];
$row['sts']=$sts;
$row['remark']=$d['remark'];
$row['topic']=$d0[0];

$row['ctopic']=$d0[1];
$row['date']=date('d-M-Y',strtotime($d['date']));
$row['id']=$d['id'];
$arr['data'][]=$row;
}

$s1=mysqli_query($host,"select staff.id,staff.name from timetabledata INNER JOIN staff ON timetabledata.sid=staff.id where timetabledata.standard='$standard' and timetabledata.subject='$subject' and timetabledata.cid='$user' order by timetabledata.id desc limit 1");
$d1=mysqli_fetch_array($s1);
if($d1['id']){
$row17['name']=$d1['name']." (As per Time Table)";
$row17['id']=$d1['id'];
$arr['staff'][]=$row17;
}



$s03=mysqli_query($host,"SELECT * FROM `staff` WHERE sub_domain='$subdomain' and branch='$user' and status='Active' order by name asc");
$r=mysqli_num_rows($s03);
while($d03=mysqli_fetch_array($s03)){
 $row17['id']=$d03['id'];
 $row17['name']=$d03['name']; 
 $arr['staff'][]=$row17;
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