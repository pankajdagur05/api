<?php
include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$username=$obj['userid'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$search=$obj['search'];
$fromdate=$obj['fromdate'];
$todate=$obj['todate'];
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
  
$arr['status']=true;
    $arr['message']='success';
  $arr['data']=array();
  
    if($fromdate){
       $fr=$fromdate;	
$to=$todate;
  }else{
$fr=date('Y-m-d');	
$to=date('Y-m-d');
  }

    $q="select *,erp_category.name as cname,students.name as sname from feesdue INNER JOIN students INNER JOIN erp_category ON feesdue.sid=students.id and feesdue.cateid=erp_category.id where feesdue.voucher='receive' and feesdue.cid='$user' and feesdue.session='$session'  and  feesdue.mode='cash' and feesdue.cancel='No' and feesdue.rdate between '$fr' and '$to'";	
$ar1=array();
$s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
	$descr=$d['cname']." - ".$d['sname']." - Receipt No. :".$d['invoice'];
	 $row2 = array();
		 $row2['date']=$d['rdate'];
		  $row2['descr']=$descr;
		   $row2['amount']=$d['credit'];
		    $row2['side']='credit';
			$row2['mode']=$d['mode'];

		     $ar1[]=$row2;
}

$s2=mysqli_query($host,"select * from accounts where cid='$user' and mode='cash'  and mode<>'Due' and idate between '$fr' and '$to'");
while($d2=mysqli_fetch_array($s2)){
$row2 = array();
		 $row2['date']=$d2['idate'];
		  $row2['descr']=$d2['type'];
		   $row2['amount']=$d2['amount'];
		    $row2['side']=$d2['side'];
			$row2['mode']=$d2['mode'];

		     $ar1[]=$row2;	
	
}



$s2=mysqli_query($host,"select * from cashbank_voucher where cid='$user' and (debit='cash' or credit='cash') and session='$session' and idate between '$fr' and '$to'");
while($d2=mysqli_fetch_array($s2)){
 if($d2['debit']=='cash'){
$mode='Cash withdrawal from Bank';
$side='credit';
 }else{
$mode='Cash Deposit from Bank';
$side='debit';
 }
$row2 = array();
     $row2['date']=$d2['idate'];
      $row2['descr']=$d2['remark'];
       $row2['amount']=$d2['amount'];
        $row2['side']=$side;
      $row2['mode']=$mode;

         $ar1[]=$row2;  
  
}

function date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}    
usort($ar1, 'date_compare');
     
for($a=0; $a<count($ar1); $a++){ 
    $dt=$ar1[$a]['date']; $timestamp = strtotime($dt);
 $new_date = date("d-M-Y", $timestamp);
 if($ar1[$a]['side']=="debit"){ $p=$ar1[$a]['amount']; $pay+=$p; }
 if($ar1[$a]['side']=="credit"){ $p=$ar1[$a]['amount']; $rec+=$p; }
 $row['date']=$new_date;
 $row['mode']=$ar1[$a]['mode'];
 $row['desc']=$ar1[$a]['descr'];
  $row['debit']=$p;
   $row['credit']=$p;
   $arr['data'][]=$row;
}

$arr['credit']=$rec;
$arr['debit']=$pay;

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