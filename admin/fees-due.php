<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$data=$obj['data'];
$sid=$obj['sid'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];

$num=count($data); 
$date=date('Y-m-d');
$time=date('h:i:s');
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

 
      $branch=$branchid;
      $user=$branch;
      
      $userby=$type;
      
$s3=mysqli_query($host,"select * from students where id='$sid'");
      $d3=mysqli_fetch_array($s3); 
      $session=$d3['session'];

for($a=0; $a<$num; $a++){

$ladger=$data[$a]['ladger'];
$amount=$data[$a]['amount'];
$idate=$data[$a]['date'];
$remark=$data[$a]['remark'];
 
$s1=mysqli_query($host,"select * from erp_category where name='$ladger' and cid='$branch'");
$d1=mysqli_fetch_array($s1);
$cateid=$d1['id'];



if(mysqli_query($host,"insert into feesdue set sid='$sid',cateid='$cateid',debit='$amount',voucher='due',cancel='No',remark='$remark',rdate='$idate',cid='$branch',userby='$userby',date='$date',session='$session'")){
    
}else{
    ++$x;
}
 
    
}


if($x>0){
  $arr3['status']=false;
$arr3['message']='Something Went Wrong';   
} else{
    
  $arr3['status']=true;
$arr3['message']='Successfully Submit';   
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