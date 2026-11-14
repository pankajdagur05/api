<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$sid=$obj['sid'];
$amnt=$obj['amount'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
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

$ss1=mysqli_query($host,"select name from staff where branch='$user' and id='$username'");
           $dd1=mysqli_fetch_array($ss1);
 
$bamount=$amnt;
$s=mysqli_query($host,"select * from feesdue where  sid='$sid' and voucher='due' and session='$session' order by rdate asc");
while($d=mysqli_fetch_array($s)){
$did=$d['id'];
$amount=$d['debit'];
$rdate=$d['rdate'];
$s1=mysqli_query($host,"select sum(credit) from feesdue where type='$did' and voucher='receive' and sid='$sid' and cid='$user' and cancel='No' and session='$session'");	
$d1=mysqli_fetch_array($s1);
$am=$d1[0];
if($amount>$am){
	$dueamount=$amount-$am;	
if($bamount>0){	
if($bamount<=$dueamount){
 $mamounr=$bamount;
 $bamount=0;
}else{
 $mamounr=$dueamount;
 $bamount=$bamount-$dueamount; 
}
$cateid=$d['cateid'];
$user=$d['cid'];
$uname=$dd1[0];
if($uname=="")
{
    $uname="Admin";
}
$ses=$d['session'];
 $q="insert into feesdue set sid='$sid',cateid='$cateid',voucher='receive',credit='$mamounr',rdate='$date',mode='Concession',type='$did',cid='$user',userby='$uname',date='$date',session='$ses',cancel='No'";
mysqli_query($host,$q);
}

}
}

$arr['status']=true;
    $arr['message']='success';

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