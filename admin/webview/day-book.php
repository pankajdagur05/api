<?php  include("session.php"); ?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> 
<meta name="viewport" content="width=device-width, initial-scale=0.6" />
<?php
if($fr){	
}else{
$fr=date('Y-m-d');	
$to=date('Y-m-d');
}
if($fr){
	
	$q3="select sum(amount) from cashbank_voucher where cid='$user' and debit='bank' and idate between '$fr' and '$to'";
	
	 $q="select feesdue.*,sum(credit) as scredit,erp_category.name as cname,students.name as sname,students.standard as standard from feesdue INNER JOIN students INNER JOIN erp_category ON feesdue.sid=students.id and feesdue.cateid=erp_category.id where feesdue.voucher='receive' and feesdue.cid='$user'  and feesdue.cancel='No' and feesdue.mode<>'Concession' and feesdue.rdate between '$fr' and '$to' group by invoice,rdate,sid";	
$ar1=array();
$s=mysqli_query($host,$q); while($d=mysqli_fetch_array($s)){
	$descr=$d['cname']." - ".$d['sname']." (<span style='color:green;'><b>".$d['standard']."</b></span>) - Receipt No. :".$d['invoice'];
	 $row2 = array();
		 $row2['date']=$d['rdate'];
		  $row2['descr']=$descr;
		    $row2['remark']=$d['remark'];
		      $row2['userby']=$d['userby'];
		   $row2['amount']=$d['scredit'];
		    $row2['side']='credit';
			$row2['mode']=$d['mode']; 
			$row2['tid']=$d['tid']; 
			$row2['id']=$d['id'];
				$row2['bdetails']=$d['bdetails'];

		     $ar1[]=$row2;
}

}



$s2=mysqli_query($host,"select * from accounts where cid='$user'  and mode<>'due' and idate between '$fr' and '$to'");
while($d2=mysqli_fetch_array($s2)){
$row2 = array();
		 $row2['date']=$d2['idate'];
		  $row2['descr']=$d2['type'];
		  $row2['remark']=$d2['descr'];
		   $row2['amount']=$d2['amount'];
		    $row2['side']=$d2['side'];
			$row2['mode']=$d2['mode'];
			$row2['tid']='';
			$row2['id']=$d2['id'];
			$row2['bdetails']=$d2['bdetails'];

		     $ar1[]=$row2;	
	
}

function date_compare($a, $b)
{
    $t1 = strtotime($a['date']);
    $t2 = strtotime($b['date']);
    return $t1 - $t2;
}    
usort($ar1, 'date_compare');




?>
 <table id="reptm" class="table table-striped">
<thead>
<tr>
<th width="120" id="cent">Date</th>
<th width="100">Mode</th>
<th width="770">Particular</th>
<th width="100">Debit</th>
<th width="100">Credit</th>
</tr></thead>
<tbody>
<?php for($a=0; $a<count($ar1); $a++){  
if($ar1[$a]['mode']=='cash' or $ar1[$a]['mode']=='Cash' or $ar1[$a]['mode']=='Select mode'){
?>
<tr>
    
<td id="<?php echo $ar1[$a]['id']; ?>"><?php $dt=$ar1[$a]['date']; $timestamp = strtotime($dt);
echo $new_date = date("d-M-Y", $timestamp); ?></td>

<td><?php echo $ar1[$a]['mode'].' '.$ar1[$a]['tid']." ".$ar1[$a]['bdetails']; ?></td>

<td><?php echo $ar1[$a]['descr']; if($ar1[$a]['remark']){ echo " - ".$ar1[$a]['remark']; } echo "<b> - ".$ar1[$a]['userby']."</b>"; ?></td>
<td><?php if($ar1[$a]['side']=="debit"){ echo $p=$ar1[$a]['amount']; $pay+=$p; }?></td>
<td><?php if($ar1[$a]['side']=="credit"){ echo $p=$ar1[$a]['amount']; $rec+=$p; }?></td>
</tr>

<?php
}
}

$s3=mysqli_query($host,$q3);
$d3=mysqli_fetch_array($s3);

?>


<tr>
<th colspan="3"><center> Balance </center></th>
<th><?php echo $pay; ?></th>
<th><?php echo $rec; ?></th>

</tr>
<tr>
<th colspan="3"><center> Cash In Hand <?php if($d001['bdetails']){ echo '(Bank Deposit '.$d001[0].' - '.$d001['bdetails'].')';  } ?></center></th>
<th><?php echo $so=($rec-$pay);  ?></th>
<th></th>

</tr>


</tbody>

<tr>
<th colspan="3" id="bsp">Total</th>
<th><?php echo $t=$rec; ?></th>
<th><?php echo  $re=$rec; ?></th>

</tr>


<tbody>
<?php $pay=0;  $rec=0; for($a=0; $a<count($ar1); $a++){  if($ar1[$a]['mode']=='bank' or $ar1[$a]['mode']=='Bank' or $ar1[$a]['mode']=='Bank' or $ar1[$a]['mode']=='UPI' or $ar1[$a]['mode']=='Cheque' or $ar1[$a]['mode']=='Netbanking' or $ar1[$a]['mode']=='Payment Gateway'){ ?>
<tr>
<td id="<?php echo $ar1[$a]['id']; ?>"><?php $dt=$ar1[$a]['date']; $timestamp = strtotime($dt);
echo $new_date = date("d-M-Y", $timestamp); ?></td>
<td><?php echo $ar1[$a]['mode']; ?></td>
<td><?php echo $ar1[$a]['descr']; if($ar1[$a]['remark']){ echo " - ".$ar1[$a]['remark']; }  echo "<b> - ".$ar1[$a]['userby']."</b> <b>(".$ar1[$a]['mode'].'-'.$ar1[$a]['tid'].")</b>";  ?></td>
<td><?php if($ar1[$a]['side']=="debit"){ echo $p=$ar1[$a]['amount']; $pay+=$p; }?></td>
<td><?php if($ar1[$a]['side']=="credit"){ echo $p=$ar1[$a]['amount']; $rec+=$p; }?></td>
</tr>

<?php
}
}
$s3=mysqli_query($host,$q3);
$d3=mysqli_fetch_array($s3);


?>


<tr>
<th colspan="3"><center> Payment By Bank - (Balance - <?php echo $Bank; ?> )</center></th>
<th><?php echo $rec; ?></th>
<th><?php echo $rec; $tot=$rec-$pay;?></th>

</tr>


<tr>
<th colspan="3" id="bsp">Total</th>
<th><?php echo $tot+$re; ?></th>
<th><?php echo  $tot+$re; ?></th>

</tr>
</tbody>


<?php 
$s001=mysqli_query($host,"select amount,bdetails from cashbank_voucher where cid='$user' and idate between '$fr' and '$to' and credit='cash'");
while($d001=mysqli_fetch_array($s001)){
    ?>
    <tr>
<th colspan="4"><?php echo $d001['bdetails']; ?></th> 
<th><?php echo  $d001['amount']; ?></th>

</tr>
    <?php
}
?>

</table>
