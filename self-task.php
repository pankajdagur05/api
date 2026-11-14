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


$title=$obj['title'];
$sname=$obj['sname'];
$descr=$obj['desc'];
$fdate=$obj['fdate'];
$tdate=$obj['tdate'];
$level=$obj['pri'];
$status=$obj['status'];
$sname=$obj['sname'];
$tid=$obj['update'];
$category=$obj['category'];
$tim=$obj['time'];
$sid=$obj['sid'];
$cid=$obj['cid'];
 
if($title){
if($tid){

$q="update selftask set title='$title',descr='$descr',fdate='$fdate',tdate='$tdate',createon='$time',sid='$sid',time='$tim',category='$category',level='$level' where id='$tid' and cid='$user'";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='successfully Updated';
}else{   
    if($tdate)
      {
          $tt="tdate='$tdate',";
         
      }else
      {
           $tt="";
      }
      
       if($sid)
      {
          $sidd="sid='$sid',";
         
      }else
      {
           $sidd="";
      }
$q="insert into selftask set title='$title',descr='$descr',fdate='$fdate', $tt createon='$time',time='$tim',category='$category', $sidd noti='1',staff='$username',status='Active',level='$level',cid='$user'";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='successfully Inserted'.$q;
 
} 
}else{

if($delete){
mysqli_query($host,"delete from selftask where id='$delete' and cid='$user'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{
if($status){
mysqli_query($host,"update selftask set status='$sname' where id='$status' and cid='$user'");
$arr['status']=true;
$arr['message']='Task '.$sname;
}else{
if($cid){
mysqli_query($host,"update selftask set status='complete' where id='$cid' and cid='$user'");
$arr['status']=true;
$arr['message']='Sub-Task Completed';
}else{
$arr['status']=true;
$arr['message']='success';  
}
}
  
}
 
 
 
$arr['data']=array();
 
$s=mysqli_query($host,"select * from selftask where staff='$username' and cid='$user' and (sid IS NULL or sid='') order by level asc");
while($d=mysqli_fetch_array($s)){

$d1 = new DateTime($d['tdate']);
$d2 = new DateTime(date('Y-m-d'));
$interval = $d1->diff($d2);   
if(strtotime($d['tdate'])>=strtotime(date('Y-m-d'))){
    $st="Remaining";
}else{
    
    $st="Ago";
}   
if($interval->d==0){
$txt="Today";   
}else{
$txt=$interval->d." Days ".$st;    
}
$tid=$d['id'];
$row['title']=$d['title']; 
$row['desc']=$d['descr']; 
$row['category']=$d['category']; 
$row['time']=$d['time']; 
$row['fdate']=$d['fdate']?date('d-m-Y',strtotime($d['fdate'])):""; 
$row['tdate']=$d['tdate']?date('d-m-Y',strtotime($d['tdate'])):""; 
$row['status']=$d['status']; 
$row['pri']=$d['level'];
$row['ago']=$txt;
$row['type']=$st;
$row['createon']=date('d-m-Y',strtotime($d['createon']));  
$row['id']=$tid; 
$row['data']=array();
 
$s0=mysqli_query($host,"select * from selftask where staff='$username' and cid='$user' and sid='$tid' order by level asc"); 
while($d0=mysqli_fetch_array($s0)){
    
$d1 = new DateTime($d0['tdate']);
$d2 = new DateTime(date('Y-m-d'));
$interval = $d1->diff($d2);   
if(strtotime($d0['tdate'])>=strtotime(date('Y-m-d'))){
    $st="Remaining";
}else{
    $st="Ago";
}       
$row2['title']=$d0['title']; 
$row2['desc']=$d0['descr']; 
$row2['category']=$d0['category']; 
$row2['time']=$d0['time']; 
$row2['fdate']=$d0['fdate']?date('d-m-Y',strtotime($d0['fdate'])):""; 
$row2['tdate']=$d0['tdate']?date('d-m-Y',strtotime($d0['tdate'])):""; 
$row2['status']=$d0['status']; 
$row2['pri']=$d0['level'];
$row2['ago']=$interval->d." Days ".$st;
$row2['type']=$st;
$row2['createon']=date('d-m-Y',strtotime($d0['createon'])); ; 
$row2['id']=$d0['id'];  
$row['data'][]=$row2;

}

$arr['data'][]=$row;
    
}

$s3=mysqli_query($host,"select sum(if(status='active',1,0)) as active,sum(if(status='incomplete',1,0)) as incomplete,sum(if(status='complete',1,0)) as complete from selftask where staff='$username' and cid='$user' and  fdate<='$date' and tdate>='$date'");
$d3=mysqli_fetch_array($s3);
$arr['active']=$d3[0];
$arr['incomplete']=$d3[1];
$arr['complete']=$d3[2];

$s3=mysqli_query($host,"select count(id) from selftask where staff='$username' and cid='$user' and  fdate>'$date'");
$d3=mysqli_fetch_array($s3);
$arr['upcoming']=$d3[0];
 
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