<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$sid=$obj['sid'];
$type='other';
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


$s04=mysqli_query($host,"select * from students where id='$sid'");
$d04=mysqli_fetch_array($s04); 
$session=$d04['session'];

      
        
$arr['status']=true;
    
  $arr['data']=array();
  $arr['remark']=array();
  $arr['extend']=array();
  $arr['session']=array();
  
  
  for($a=1; $a<=1; $a++){
$s1=mysqli_query($host,"select * from students where id='$sid' order by id desc"); 
$d1=mysqli_fetch_array($s1); 
$row5['name']=$d1['session'];
$row5['sid']=$d1['id'];
$arr['session'][]=$row5;
}  
  
  
$q2="select *,feesdue.id as idd,erp_group.name as gname,erp_category.name as cname,feesdue.userby from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.cid='$user' and feesdue.sid='$sid' and feesdue.session='$session' and  feesdue.cancel='No' order by feesdue.rdate asc"; $s3=mysqli_query($host,$q2);  

$arr['message']='success';

while($d3=mysqli_fetch_array($s3)){
     if($d3['cancel']=='Yes'){ $cancel=$d3['date']; }else{ $rece+=$d3['credit']; $pay+=$d3['debit']; } 
     if($d3['transport']=="Yes"){ $grp=$d3['gname']." - ".$section; }else{  $grp=$d3['gname']; }
 $row['date']=date('d M Y',strtotime($d3['rdate']));
 $row['group']=$grp;
 $row['ledger']=$d3['cname'];
 $row['byname']=$d3['userby'];
 $row['cancel']=$cancel;
 $row['mode']=$d3['mode']==""?" ":$d3['mode'];
 $row['debit']=$d3['debit'];
  $row['credit']=$d3['credit'];
    $arr['data'][]=$row;
    
}
     
$arr['debit']=$pay;
$arr['credit']=$rece;
$s03=mysqli_query($host,"select * from fees_status where sid='$sid'");	
 while($d03=mysqli_fetch_array($s03)){ 
  
  $row2['date']=date("d M Y",strtotime($d03['date']));
  $row2['by']=$d03['uid']." - ".$d03['type'];
  $row2['idate']=date("d M Y",strtotime($d03['idate']));
  $row2['remark']=$d03['remark'];
  $arr['extend'][]=$row2;
     
 }
 
 $s04=mysqli_query($host,"select * from remark where sid='$sid'");
  while($d04=mysqli_fetch_array($s04)){
if($d04['user']){
$uid=$d04['user'];
$s=mysqli_query($host,"select * from staff where id='$uid'");    
$d=mysqli_fetch_array($s);
$type=$d['name'];
}else{
    $type="Admin";
}

 $row3['date']=date("d M Y",strtotime($d04['date']));
  $row3['type']=$d04['type']; 
  $row3['remark']=$d04['remark'];
  $arr['remark'][]=$row3;
  
  

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