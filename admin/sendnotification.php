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
$startdate=$obj['date'];
if($startdate==""){ $startdate=$date; }
$mode=$obj['mode'];
$desc=$obj['desc'];
$delete=$obj['delete'];
$update=$obj['update'];
$sendtime=$obj['time'];
$typed=$obj['typed'];
$placement=$obj['placement'];
$fromdate=$obj['fromdate'];
$todate=$obj['todate'];
$parent=$obj['parent']; 
$student=$obj['student']; 
$staff=$obj['staff']; 
if($sendtime==""){ $sendtime=time(); }else{ $sendtime=strtotime("$startdate $sendtime");  }


if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token' and type='$type' and sub_domain='$subdomain'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
$s=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'"); 
$d=mysqli_fetch_array($s);
$sid=$d['id'];

if($delete){
mysqli_query($host,"delete from sendnotification where id='$delete'");    
}

if($title){
    if($update){
    
mysqli_query($host,"update sendnotification set mode='$mode',typed='$typed',placement='$placement',title='$title',ndate='$startdate',ntime='$sendtime',descr='$desc',date='$date' where id='$update' and sub_domain='$subdomain'");

$noti=$update;
if($parent){
mysqli_query($host,"delete from sendnotification_member where id='$update' and cid='$user' and sendto='parent'");    
mysqli_query($host,"insert into sendnotification_member set sendto='parent',filter='$parent',notification='$noti',cid='$user'");    
}

if($student){
mysqli_query($host,"delete from sendnotification_member where id='$update' and cid='$user' and sendto='student'");     
mysqli_query($host,"insert into sendnotification_member set sendto='student',filter='$student',notification='$noti',cid='$user'"); 

}

if($staff){
mysqli_query($host,"delete from sendnotification_member where id='$update' and cid='$user' and sendto='staff'");     
mysqli_query($host,"insert into sendnotification_member set sendto='staff',filter='$staff',notification='$noti',cid='$user'"); 

}

    
    }else{
        
       
        
     $q="insert into sendnotification set mode='$mode',typed='$typed',placement='$placement',title='$title',ndate='$startdate',ntime='$sendtime',descr='$desc',status='Pending',date='$date',time='$time',cid='$cid',sub_domain='$subdomain'";
mysqli_query($host,$q);
$s01=mysqli_query($host,"select id from sendnotification where cid='$user' order by id desc limit 1");
$d01=mysqli_fetch_array($s01);
$noti=$d01['id'];
if($parent){
mysqli_query($host,"insert into sendnotification_member set sendto='parent',filter='$parent',notification='$noti',cid='$user'");    
}

if($student){
mysqli_query($host,"insert into sendnotification_member set sendto='student',filter='$student',notification='$noti',cid='$user'");    
++$xy;
    
}

if($staff){
mysqli_query($host,"insert into sendnotification_member set sendto='staff',filter='$staff',notification='$noti',cid='$user'");    
++$xy;
    
}
}
}
if($xy==2){ $st='Both'; }else{ if($xy==1){ if($staff){ $st='Staff';  }else{  $st='Student';   }  }else{ $st='Both'; } } 


 if(strtolower($typed)=='holiday'){
            mysqli_query($host,"insert into calender set title='$title',descr='$desc',fdate='$fromdate',tdate='$todate',mode='Holiday',date='$date',cid='$user',type='$st'");
        }
        
$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$s=mysqli_query($host,"select * from sendnotification where cid='$cid' and sub_domain='$subdomain' order by id desc");
while($d=mysqli_fetch_array($s)){
$row['title']=$d['title'];
$row['desc']=$d['descr'];
$row['typed']=$d['typed'];
$row['placement']=$d['placement'];
$row['date']=$d['ndate'];
$row['status']=$d['status'];
$row['time']=date('h:i',strtotime($d['ntime']));
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