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

$sid=$obj['sid'];
if($username){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
 $branch=$branchid;
      $user=$branch;
      $userby=$type;
      
$arr['status']=true;
$arr['message']='success';
$arr['data']=array();
$arr['ladger']=array();
$arr['bank']=array();

$s1=mysqli_query($host,"SELECT *,feesdue.id as fid FROM `feesdue` INNER JOIN erp_category ON erp_category.id=feesdue.cateid WHERE feesdue.cid='$user' and feesdue.sid='$sid' and voucher='due'");
while($d1=mysqli_fetch_array($s1))
{
$id=$d1['fid'];
$s2=mysqli_query($host,"SELECT sum(credit) FROM `feesdue` WHERE cid='$user' and sid='$sid' and voucher='receive' and type='$id' and cancel='No'");
$d2=mysqli_fetch_array($s2);
if($d1['debit']>$d2[0]){
$row['id']=$id;
$row['ledger']=$d1['name'];
$row['date']=$d1['rdate'];
$row['amount']=$d1['debit']-$d2[0];
$arr['data'][]=$row;


}
}

$s3=mysqli_query($host,"SELECT erp_group.name as gname,erp_category.name as cname FROM `erp_group` INNER JOIN erp_category ON erp_category.type=erp_group.id where erp_group.cid='$user' and erp_category.cid='$user'");
while($d3=mysqli_fetch_array($s3)){
$row2['cname']=$d3[1];    
$row2['gname']=$d3[0];    
$arr['ladger'][]=$row2;
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