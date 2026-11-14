<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$class=$obj['standard'];
$stream=$obj['stream'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$typess=$type;
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

 
if(strtolower($typess)!="admin"){ $stt=" status='Active' and "; }
 
 if($search){
     $q="select * from students where session='$session' and cid='$branch' and $stt name like '$search%'";
  }else{
      $q="select * from students where session='$session' and cid='$branch' and $stt standard='$class' and stream='$stream'";
  }
$arr['status']=true;
    $arr['message']=$q;
  $arr['data']=array();
 
 $s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
 $sid=$d['id'];
 $row['sid']=$sid;
 $mobile=$d['smobile'];
 $amobile=$d['gmobile'];
 $row['name']=$d['name'];
 $row['fname']=$d['fname'];
 $row['stream']=$d['stream'];
 $row['route']=$d['route'];
 $row['standard']=$d['standard'];
 $row['recording']=array();
 $row['fees']=array();
  $row['remark']=array();
 $row['family']=array();
 $s1=mysqli_query($host,"select * from upload_call where sid='$sid' and branch='$branch'");  
while($d1=mysqli_fetch_array($s1)){
    $row2['date']=date('d M Y',strtotime($d1['date']));
    $row2['path']="https://smartechindia.in/leadpanel/cpanel/upload/call_recarding/".$d1['file'];
    $row['recording'][]=$row2;
  }
  
   $s1=mysqli_query($host,"select * from fees_status where sid='$sid' and cid='$branch'");  
while($d1=mysqli_fetch_array($s1)){
    
    $row3['changedate']=date('d M Y',strtotime($d1['idate']));
    $row3['date']=date('d M Y',strtotime($d1['date']));
    $row3['remark']=$d1['remark'];
    $row['fees'][]=$row3;
  }
  
  
  
    $s1=mysqli_query($host,"select * from remark where sid='$sid' and cid='$branch'");  
while($d1=mysqli_fetch_array($s1)){
if($d1['user']){ 
$id=$d1['user'];    
$s2=mysqli_query($host,"select * from staff where id='$id'");   
$d2=mysqli_fetch_array($s2);
$type=$d2['name'];
}else{
    $type='Admin';
}
    $row3['date']=date('d M Y',strtotime($d1['date']));
     $row3['type']=$type;
    $row3['remark']=$d1['remark'];
    $row['remark'][]=$row3;
  }
  
  
  if($mobile==""){
      $mobile="0";
  }
    if($amobile==""){
      $amobile="0";
  }
  
  
  $q="select * from students where session='$session' and $stt cid='$branch' and id<>'$sid' and (smobile='$mobile' or gmobile='$mobile' or smobile='$amobile' or gmobile='$amobile')";
  $s1=mysqli_query($host,$q);  
while($d1=mysqli_fetch_array($s1)){
    $sidd=$d1['id'];
    $q="select sum(feesdue.debit),sum(feesdue.credit),feesdue.sid,students.*,feesdue.* from feesdue INNER JOIN students ON students.id=feesdue.sid where feesdue.cid='$branch' and feesdue.rdate<='$date' and feesdue.cancel='No' and feesdue.session='$session' and students.id='$sidd' 
";
 $q.=" group by feesdue.sid";
 $s6=mysqli_query($host,$q);
$d6=mysqli_fetch_array($s6);
$over=$d6[0]-$d6[1];
    $row5=array();
    $row5['name']=$d1['name'];
    $row5['standard']=$d1['stream']." - ".$d1['standard'];
    $row5['sid']=$d1['id'];
    $row5['overdue']=$over;
    $row['family'][]=$row5;
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