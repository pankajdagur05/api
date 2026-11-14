<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$type='other';
$date=date('Y-m-d');
$time=date('h:i:s');
$param=$obj['type'];

$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$branch=$cid;

$sid=$obj['sid'];
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$names=$obj['name'];      

if($names)
{
    $idate=$obj['date'];
$amount=$obj['amount'];

$type=$obj['type'];
$mode=$obj['mode'];
$dueid=$obj['did'];
$descr=$obj['remark'];
if($type=="Receive")
{
 $side ="credit";  
}else
{
  $side="debit";  
}
if(substr($mode,0,4)=='Bank' or substr($mode,0,4)=='bank'){
$bdetails=ltrim($mode,"Bank : "); 
$pos1=strpos($mode,'-');
$str=substr($mode,$pos1+2,300);
$pos2=strpos($str,'-');
$baccount=substr($str,0,$pos2-1);
$mode='Bank'; 
}

$nam=explode(",",$names);
$names=$nam[0];
$dueid=$nam[1];
    mysqli_query($host,"insert into accounts set idate='$idate',date='$date',cid='$user',amount='$amount',side='$side',type='$names',descr='$descr',bdetails='$bdetails',bankno='$baccount',session='$session',mode='$mode',dueid='$dueid'");
}
      
      
      
$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$arr['group']=array();
$arr['due']=array();
$arr['bank']=array();
$s=mysqli_query($host,"select * from exp where cid='$user'"); while($d=mysqli_fetch_array($s)){ 
 
$row['name']=$d['name']; 
$row['type']=$d['type'];
$row['send']=$d['name']; 
$arr['data'][]=$row; 


}

$s=mysqli_query($host,"select * from exp_group where cid='$user'"); while($d=mysqli_fetch_array($s)){ 
$row4['name']=$d['name'];
$row4['type']=$d['type'];
$arr['group'][]=$row4;
    
}
 $s=mysqli_query($host,"select sum(amount),type,id from accounts where cid='$user' and mode='Due' group by type"); while($d=mysqli_fetch_array($s)){
$aid=$d['id'];
$a1=$d[0];
$type=$d['type'];
$s1=mysqli_query($host,"select sum(amount) from accounts where length(dueid)>0 and cid='$user' and type='$type'");
$d1=mysqli_fetch_array($s1);
if($a1>$d1[0]){
$am=$a1-$d1[0];    
$s01=mysqli_query($host,"select * from exp where name='$type' and cid='$user'");  
$d01=mysqli_fetch_array($s01);

$row2['id']=$d01['id'];
$row2['name']=$d['type']." (".$d01['ctype']." - ".$am.")";
$row2['type']=$d01['type'];
$row2['amount']=$a1-$d1[0];
$row2['descr']=$d['descr'];
$row2['send']=$d['type'].",".$aid;
$arr['due'][]=$row2;


}


}
 
$s3=mysqli_query($host,"SELECT * from bank where cid='$user'");
while($d3=mysqli_fetch_array($s3)){
$row3['name']=$d3['name'];    
$row3['account']=$d3['account']; 
$row3['bank']=$d3['bank']; 
$arr['bank'][]=$row3;
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