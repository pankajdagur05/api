<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$invoice=$obj['invoice'];
$idate=$obj['date'];
$remark=$obj['remark'];
$sid=$obj['sid'];
$sms=$obj['sms'];
$num=count($data);
 $branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];

$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){


      $branch=$branchid;
      $user=$branch;
      $userby=$type;
      
      
 

for($a=0; $a<$num; $a++){

$entry=$data[$a]['entry'];
$mode=$data[$a]['mode'];
$amount=$data[$a]['amount'];
$tid=$data[$a]['tid'];
$bank=$data[$a]['bank'];
if($bank)
{
$bdetails=ltrim($bank,"Bank : "); 
$pos1=strpos($bank,'-');
$str=substr($bank,$pos1+2,300);
$pos2=strpos($str,'-');
$baccount=substr($str,0,$pos2-1);
}

$s1=mysqli_query($host,"select * from feesdue where id='$entry' and cid='$branch'");
$d1=mysqli_fetch_array($s1);
$cateid=$d1['cateid'];
$session=$d1['session'];
if(mysqli_query($host,"insert into feesdue set sid='$sid',cateid='$cateid',credit='$amount',voucher='receive',cancel='No',remark='$remark',type='$entry',rdate='$idate',invoice='$invoice',cid='$branch',userby='$userby',bdetails='$bdetails',bankno='$baccount',date='$date',mode='$mode',session='$session'")){
    
}else{
    ++$x;
}
 
    
}


if($x>0){
  $arr3['status']=false;
$arr3['message']='Something Went Wrong';   
} else{
    if($sms){
        //sms code write here
    }
  $arr3['status']=true;
$arr3['message']='Succesfully Submit';   
}



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