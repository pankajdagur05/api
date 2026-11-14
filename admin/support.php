<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true); 
$subdomain=$_POST['subdomain'];
$token=$_POST['token']; 
$date=date('Y-m-d');
$time=date('h:i:s');
$type=$_POST['type'];
$branchid=$_POST['branchid']; 
$session=$_POST['session'];
$typed=$_POST['typefirst'];
$userid=$_POST['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;
$department=$_POST['department'];
$reason=$_POST['reason']; 
$description=$_POST['descr'];
$subject=explode(",",$_POST['subject']);
$comid=$_POST['comid'];
$sts=$_POST['sts'];
if($subdomain){
    $q="select * from apk_token where token='$token'  and sub_domain='$subdomain'";
    $s=mysqli_query($host,$q);
    $r=mysqli_num_rows($s);
    if($r==1){
        
        if($description){
    if($_FILES['attachment']['tmp_name']){

if(checking($_FILES['attachment']['name'])){
$ex=pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
$sourcePath=$_FILES['attachment']['tmp_name'];

$attachment =uniqid().time().".".$ex;

move_uploaded_file($sourcePath,"../../../../management/upload/".$attachment);
}
}
if(strtolower($typed)=='admin'){
$comby="Admin";    
}else{
$s=mysqli_query($host,"select name from staff where id='$userid'");    
$d=mysqli_fetch_array($s);
$comby=$d['name'];  
}
$ssname=$comby;

$s=mysqli_query($host,"select iname from branch_category where id='$branch'"); 
$d=mysqli_fetch_array($s);
$name=$d['iname'];


$mob=$d['mobile'];
$amob=$d['amobile'];
$email=$d['email'];




if(strtolower($type)=='support'){ 
if(strtolower($department)=='training' or strtolower($department)=='other'){
$modeto='operation';     
}else{
    
$platform=$_POST['platform'];
if(strtolower($platform)=='web panel'){
  $modeto='it'; 
}else{
 $modeto='mdeveloper';   
}

	}

}else{
$modeto='operation';    
}

if(strtolower($department)=='sales'){
 $modeto='sales';   
}

if($modeto){
$ss=mysqli_query($host2,"select * from add_employee where $modeto='yes'");
$dd=mysqli_fetch_array($ss);
$sendto=$dd['mobile'];
$sentname=$dd['name'];
$step='allot';
}

$q="insert into complaint set type='$type',type_by='$typed',userid='$userid',department='$department',reason='$reason',description='$description',attachment='$attachment',name='$name',complaint_by='$comby',mobile='$mob',amobile='$amob',email='$email',status='Pending',subdomain='$subdomain',date='$date',time='$time',cid='$user'";
mysqli_query($host2,$q);
$s1=mysqli_query($host2,"select id from complaint where subdomain='$subdomain' order by id desc limit 1");
$d1=mysqli_fetch_array($s1);
$id=$d1['id'];
for($a=0; $a<count($subject); $a++){
$pname=$subject[$a]; 
if($pname){
$q="";
if($step){
    $q.="insert into complaint_subject set cid='$id',name='$pname',status='pending'";
    $q.=",step='$step',allot='$sendto',allotname='$sentname'";
    mysqli_query($host2,"insert into complaint_track set cid='$id',date='$date',time='$time',step='Transfer',remark='Transfer To $sentname by EDPLUS AI',uploadby='$ssname'");
}    
mysqli_query($host2,$q);   
}

 
 

}
    
        }
        
        if($comid){
$remark="Reopen by ".$ssname;
mysqli_query($host2,"insert into complaint_track set cid='$comid',date='$date',time='$time',step='Reopen',remark='$remark',uploadby='$ssname'");
mysqli_query($host2,"update complaint_subject set status='$sts',giventime=NULL,step='allot',rdate=NULL  where cid='$comid'");
        }
        
    $arr['status']=true;
    $arr['message']='success';
    $arr['data']=array();
    $arr['data2']=array();
    if($typed=='admin' or $typed=='Admin'){
        $q="select * from complaint where subdomain='$subdomain' order by id desc limit 100";
    }else{
              $q="select * from complaint where subdomain='$subdomain' and type_by='$typed' and userid='$userid' order by id desc limit 100";
  
    }
    
    $s132=mysqli_query($host2,"select sum(case when complaint_subject.status='confirm' then 1 else 0 end),sum(case when complaint_subject.status='pending' then 1 else 0 end),sum(case when complaint_subject.status='solved' then 1 else 0 end) from complaint INNER JOIN complaint_subject ON complaint_subject.cid=complaint.id where complaint.cid='$user' order by complaint_subject.id asc");
    $d132=mysqli_fetch_array($s132);
    $arr['pending']=$d132[1];
    $arr['solve']=$d132[2];
    $arr['confirm']=$d132[0];
    $s1=mysqli_query($host2,$q);
    while($d1=mysqli_fetch_array($s1)){
        $cid=$d1['id'];
        $row['id']=$d1['id'];
        $row['name']=$d1['name'];
        $row['type']=$d1['type'];
        $row['department']=$d1['department'];
        $row['reason']=$d1['reason'];
        $row['description']=$d1['description'];
        $row['attachment']="https://edpluss.com/management/upload/".$d1['attachment'];
        $row['date']=date('d M Y',strtotime($d1['date']));
        $row['subject']=array();
        $s2=mysqli_query($host2,"select * from complaint_subject where cid='$cid'");
        while($d2=mysqli_fetch_array($s2)){
            $row2['name']=$d2['name'];
            $row2['status']=$d2['status'];
            $row2['giventime']=$d2['giventime']==""?"":date('d M Y',strtotime($d2['giventime']));
            $row2['allotname']=$d2['allotname'];
            $row2['rdate']=$d2['rdate']==NULL?"":date('d M Y',strtotime($d2['rdate'])); 
            $row['subject'][]=$row2;
        }
        $row['track']=array();
        $s2=mysqli_query($host2,"select * from complaint_track where cid='$cid' order by id asc");
        while($d2=mysqli_fetch_array($s2)){
            $row2['remark']=$d2['remark'];
            $row2['uploadby']=$d2['uploadby'];
            if(strpos($d['time'],':')){  $ft=$d['time']; }else{ $ft=date('h:i A',$d['time']); }
            $row2['time']=$ft;
            $row2['step']=$d2['step'];
            $row2['date']=$d2['date']==NULL?"":date('d M Y',strtotime($d2['date'])); 
            $row['track'][]=$row2;
        }
        $arr['data'][]=$row;
    }
    
    
    $s0=mysqli_query($host,"select * from features where status='Active' group by mode");
    while($d0=mysqli_fetch_array($s0)){
        $nm=$d0['mode'];
        $row1['name']=$d0['mode'];
        $row1['data']=array();
        $s2=mysqli_query($host,"select * from features where status='Active' and mode='$nm' group by name");
    while($d2=mysqli_fetch_array($s2)){
        $row2['name']=$d2['name'];
           $row2['modal']=$d2['modual'];
           $row1['data'][]=$row2;
        
    }
        $arr['data2'][]=$row1;
        
    }
    
      $row1['name']="Admin App";
        $row1['modal']="Mobile";
        $arr['data2'][]=$row1;
        
         $row1['name']="Teacher App";
        $row1['modal']="Mobile";
        $arr['data2'][]=$row1;
        
        
         $row1['name']="Gatepass App";
        $row1['modal']="Mobile";
        $arr['data2'][]=$row1;
        
         $row1['name']="Student/Parent App";
        $row1['modal']="Mobile";
        $arr['data2'][]=$row1;
        
        
         $row1['name']="Other";
        $row1['modal']="Other";
        $arr['data2'][]=$row1;
        
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