<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$staff=$obj['sid'];
$mode=$obj['mode'];
$ccm=$obj['ccm'];
$num=count($data);
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $user=$branch;
      $name=$d3['name'];
      
      
      $s33=mysqli_query($host,"select * from staff where id='$staff'");
      $d33=mysqli_fetch_array($s33);
      $names=$d33['name'];

if($ccm){
  if($mode==2){
   
    $q="insert into lead_allot (lid,sid,byname,status,cid,date,time)VALUES";
    $rt="select id from lead where cam='$ccm' and branch='$user' and archive IS NULL and id NOT IN (select lid from lead_allot where status='yes' and cid='$user')";
$ss=mysqli_query($host,$rt); 
while($dd=mysqli_fetch_array($ss)){
    $lid=$dd['id'];
$q.="('$lid','$staff','$name','yes','$user','$date','$time'),";    
}
$q=rtrim($q,",");
mysqli_query($host,$q);
}else{
    
$ss=mysqli_query($host,"select id from lead where cam='$ccm' and branch='$user' and archive IS NULL and id IN (select lid from lead_allot where status='yes' and cid='$user')");
while($dd=mysqli_fetch_array($ss)){
    $lid=$dd['id'];
mysqli_query($host,"update lead_allot set sid='$staff',status='yes' where lid='$lid' and cid='$branch' ");    
}
}
    
}else{      

for($a=0; $a<$num; $a++){
$lid=$data[$a]['lid'];
$s=mysqli_query($host,"select * from lead_allot where lid='$lid'");
if(mysqli_num_rows($s)>0){
mysqli_query($host,"update lead_allot set sid='$staff',status='yes' where lid='$lid' and cid='$branch'");  
mysqli_query($host,"insert into complaint_status set comp_id='$lid',update_date='$date',upd_sts='Lead Allotment',sts_remark='Lead Allot to $names',user='$branch',staffid='$username',staffname='$name',date='$date',time='$time'");
}else{ 
mysqli_query($host,"insert into lead_allot set lid='$lid',sid='$staff',byname='$name',status='yes',date='$date',time='$time',cid='$branch'");
}

}
}

$arr3['status']=true;
$arr3['message']='success'.$q; 

}else{
    $arr3['status']=false;
    $arr3['message']='Token Not Match';
    $arr3['data']='';
}

echo $json = json_encode($arr3);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>