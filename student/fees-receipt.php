<?php include("../../db.php");
$sid=$_GET['sid'];
$token=$_GET['token'];
$username=$_GET['username'];

if($sid==""){
 
}else{

$s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");    
if(mysqli_num_rows($s)==0){
?>
 
<?php
}
    
$q="select * from students where id='$sid'";
$s1=mysqli_query($host,$q);	
$d1=mysqli_fetch_array($s1);
$user=$d1['cid'];
$s4=mysqli_query($host,"select * from  erp_setting where cid='$user'");
$r4=mysqli_num_rows($s4);	
$d4=mysqli_fetch_array($s4); 


$ss=mysqli_query($host,"select * from branch_category where id='$user'");
$mybranch=mysqli_fetch_array($ss);
 
}
 ?>
 
<style>
/* content*/

.c{ height:200mm; width:145mm; float:left; border:0px solid #CCC; }

.c_head{height:25px; width:100%; float:left; border-bottom:2px solid #999;font-family:arial; font-size:18px; line-height:25px; 
text-align:center; color:#069; text-transform:uppercase}
.c_ldown{ height:98%; overflow:hidden; width:140mm; float:right; border:1px dashed #CCC; margin-bottom:5px; margin-top:9px; margin-left:10px }
.c_l1{ height:auto; overflow:hidden; width:99%; float:left; border:0px solid #ccc; }
.c_lm{ height:10px; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:10px;text-align:center;
line-height:10px; }
.c_lh{ height:auto; overflow:hidden; width:100%; float:left; border:0px solid #CCC; }
.c_lh1{ height:20px; width:100%; float:left; border:0px solid #CCC; margin-top:10px}
.c_lh11{ height:20px; width:50%; float:left; border:0px solid #CCC;font-family:arial; font-size:11px; font-weight:bold; text-align:left; text-indent:5px;
line-height:20px;  }
.c_lh111{ height:20px; width:140px; float:left; border:0px solid #CCC;font-family:arial; font-size:11px; font-weight:bold; text-indent:44px;
line-height:20px;  }
.c_lh112{ height:20px; width:186px; float:right; border:0px solid #CCC;font-family:arial; font-size:11px; font-weight:bold; text-align:right;
line-height:20px; margin-right:11px  }
.c_lh2{ height:auto; overflow:hidden; width:100%; float:left; border-top:0px solid #666;border-bottom:0px solid #666;margin-top:10px}
.c_lh21{ height:55px; width:55px; float:left; border:0px solid #CCC; margin-top:3px; margin-left:10px;}
.c_lh22{ height:auto; overflow:hidden; width:85%; float:left; border:0px solid #CCC;}
.c_lh221{ height:24px; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:16px; font-weight:bold; text-align:center;
line-height:24px; text-transform:uppercase }
.c_lh222{ height:auto; overflow:hidden; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:10px; text-align:center;line-height:16px;  }
.c_lh230{height:auto; overflow:hidden; width:100%; float:left; border:0px solid #CCC; margin-bottom:10px; margin-top:10px}
.c_lh23{height:auto; overflow:hidden; width:99%; float:left; border:0px solid #CCC;}
.c_lh23h{ height:18px; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:12px; font-weight:bold; text-align:center;
line-height:18px ; text-transform:uppercase }
.c_lh23t{height:auto; overflow:hidden; width:98%; margin-left:4px; float:left; border:0px solid #CCC;}
.c_lh24{height:100px; width:40%; float:right; border:0px solid #CCC;}
.c_lh24h{ height:20px; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:12px; font-weight:bold; text-align:center;
line-height:20px ; text-transform:uppercase }
.c_lh24t{height:80px; width:100%; float:left; border:0px solid #CCC;}
.c_lh24t1{height:50px; width:60px; float:left; border:1px solid #eee;}
.c_lh24t2{height:80px; width:66%; float:right; border:0px solid #CCC;}



.c_lh3{ height:auto; overflow:hidden; width:100%; float:left; border-bottom:0px solid #999;}
.c_lh31{ height:70px; width:100%; float:left; border-bottom:0px solid #999; margin-top:10px}
.c_lh32{ height:auto; overflow:hidden;     min-height: 437px; width:100%; float:left; border:0px solid #999;}
.c_lh33{ height:auto; overflow:hidden; width:100%; float:left; border:0px solid #CCC;}
.c_lh4{ height:auto; overflow:hidden; width:100%; float:left; border:0px solid #CCC;}
.c_lh41{ height:5px; width:99%; float:left; border-bottom:0px solid #CCC;font-family:arial; font-size:11px; font-style:italic; text-align:end;
line-height:20px;  }
.c_lh42{ height:16px; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:9px; font-style:italic; text-align:center;
line-height:14px; margin-top:0px;  }
.c_lh5{ height:40px; width:95%; float:left; border:0px solid #CCC;margin-top:0px}
.c_lh51{ height:20px; width:99%; float:left; border-bottom:0px solid #CCC;font-family:arial; font-size:12px; text-align:left; text-indent:10px;line-height:20px; font-weight:bold;  }
.c_lh52{ height:15px; width:100%; float:left; border:0px solid #CCC;font-family:arial; font-size:11px; font-style:italic; text-align:left; text-indent:10px;
line-height:15px;  }
.c_lh50{ height:20px; width:25%; float:right; border:0px solid #CCC; margin-top:16px}

.c_l2{ height:70px; width:100%; position:fixed; bottom:10px; right:10px; border:0px solid #CCC; }
.c_l21{ height:70px; width:60px; background:url(../../img/backend/print.png) no-repeat center; float:right; border:0px solid #CCC;font-family:arial; font-size:15px; font-style:italic; text-align:center;line-height:122px; color:#069; margin-top:1px; cursor:pointer }


.c_text{ font-family:arial; color:#333; text-align:center; font-size:15px; font-weight:bold}

th{ padding:1px;color:#333; background-color:rgba(255,255,255,1); font-family:arial; font-size:11px;    text-align: left;}
td{ color:#666;background-color:rgba(255,255,255,1);font-family:arial; text-align:center;font-size:11px;    text-align: left;}
tr:nth-child(odd) { background-color: rgba(227, 235, 239, 0.62);}
#tt{ padding:1px;color:#333; background-color:; border:1px solid #eee; font-family:arial; font-size:11px;    text-align: center;}

#vv1{ cursor:pointer; background-color:rgba(0,0,51,1); color:#fff}
a{ text-decoration:none; color:rgba(0, 102, 153, 0.67);}
a:hover{color:rgba(0, 102, 153, 1)}

td#tabb{ border:1px solid #e0e0e0; line-height:20px; text-indent:10px}
td#tabb1{ border:1px solid #e0e0e0; line-height:20px; text-align:center}

.arrow{ height: 30px; width: 40px;float: right;border: 0px solid #666; background:url(../img/backend/cancel.png) no-repeat center; cursor:pointer}
.main{
        width: 290mm;
    margin: auto;
    height: auto;
    overflow: hidden;
}
</style>
   
</head>

<body>
<span id="demo"></span>    
<div class="c_load"></div>
<div class="main" id="contentToPrint" style="">

<?php
 
$s=mysqli_query($host,"select * from  feesdue where voucher='receive' and cid='$user' and sid='$sid' order by id desc");	
$d=mysqli_fetch_array($s);


?>

<div class="c">
<div class="c_ldown">
<div class="c_l1">
<div class="c_lh">
<div class="c_lh1"><div class="c_lh11"> Parent copy </div>
<div class="c_lh112">Reg No. : <?php echo $mybranch['rno']; ?> </div></div>
<div class="c_lh2">
<div class="c_lh21"><center><img src="https://edpluss.com/school/admin-panel/cpanel/logo/<?php echo $user; ?>.png" height="50" width="50" style="margin-top:3px"/></center></div>
<div class="c_lh22">
<div class="c_lh221"><?php echo $mybranch['iname']; ?></div>
<div class="c_lh222"><br><?php echo $mybranch['address']; ?>  Email : <?php echo $mybranch['email']; ?> </div></div>
<div class="c_lh230">
<div class="c_lh23">
<div class="c_lh23h">FEES RECEIPT CUM LEDGER SESSION <?php echo $like['session']; ?></div>
<div class="c_lh23t">
  
<table>
<tr>
<th width="120">Name / Enr. No.</th>
<td width="460">: <?php echo $d1['name']; ?></td>
<th width="140">Receipt No.</th>
<td width="120">: <?php echo $d['invoice']; ?></td>
</tr>
<tr>
<th width="120">Father's Name</th>
<td width="460">: <?php echo $d1['fname']; ?></td>
<th width="140">Date</th>
<td width="140">:  <?php echo $d['rdate']; ?> </td>
</tr>
<tr>
<th width="120">Stream/Standard</th>
<td width="460">: <?php echo $d1['stream']."/".$d1['standard']; ?> </td>
<th width="140">Contact</th>
<td width="140">: <?php echo $d1['smobile']; ?> </td>
</tr>
</table></div>
</div>
</div>
</div>
<div class="c_lh3">
<div class="c_lh32">
<table style="border-collapse:collapse; border:1px solid #ccc; height:90px; width:98%; margin-left:6px">
<tr>
<th width="100" id="tt">Date</th>
<th width="200" id="tt">Fees head</th>
<th width="100" id="tt">Payable Amount</th>
<th width="100" id="tt">Deposit Amount</th>
</tr>    
<?php
mysqli_query($host,$q);
$q2="select *,feesdue.id as idd,erp_group.name as gname,erp_category.name as cname from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.cid='$user' and feesdue.sid='$sid' order by feesdue.rdate asc"; $s3=mysqli_query($host,$q2);  
while($d3=mysqli_fetch_array($s3)){
if($d3['mode']!='Concession'){
?>                     
<tr>
  <td id="tabb1"><?php echo $d3['rdate']; ?></td>   
             <td id="tabb1"><b><?php echo $d3['gname']."/".$d3['cname']; ?> </b></td>   
              <td id="tabb1"><?php echo $d3['debit']; $pay+=$d3['debit']; ?></td>  
             <td id="tabb1" row-span='2'><?php echo $d3['credit']; $rece+=$d3['credit'] ?></td>

</tr>
<?php
}else{
	$con+=$d3['credit'];
}
}
?>

<tr style="font-weight: bold;">
             <td id="tabb1"></td>   
             <td id="tabb1"><b>Total </b></td>   
              <td id="tabb1"><?php echo $pay; ?></td>  
             <td id="tabb1" row-span='2'><?php echo $rece; ?></td>   
              
            </tr>
            
            <tr>
             <td id="tabb1"></td>   
             <td id="tabb1"><b>Concession </b></td>   
              <td id="tabb1"><b> </b></td>  
             <td id="tabb1" row-span='2'><?php echo $con; ?></td>   
              
            </tr>
<tr>
             <td id="tabb1"></td>   
             <td id="tabb1"><b>Balance </b></td>   
              <td id="tabb1"><b> </b></td>  
             <td id="tabb1" row-span='2'><?php echo $pay-($con+$rece); ?></td>   
              
            </tr>
</table>

</div>
</div>


<div class="c_lh4">
<div class="c_lh41"></div>
<div class="c_lh5">
<div class="c_lh52">

</div>
</div>
<div class="c_lh50">
<div class="c_lh51">Auth. Signatory</div>
</div>

<div class="c_lh42">This is computer generated receipt, it does not need any signature. generated on <?php echo date('d-m-Y'); ?> at <?php echo date('h:i'); ?></div></div>

</div>

</div>


</div>

<?php
$pay=0; $rece=0; $con=0;

?>




</div>
<?php
++$m;
if(count($arr)>1 and count($arr)==$m){
?>
<table>
<tr>
<td width="15%">Total Fees </td>    
<td width="15%"><?php echo $b1; ?> </td>   
<td width="15%">Received  </td>    
<td width="15%"><?php echo $b2; ?> </td>   
<td width="15%">Concession </td>    
<td width="15%"><?php echo $b4; ?> </td>   
<td width="15%">Balance </td>    
<td width="15%"><?php echo $b3; ?> </td>   
</tr>    
</table>
<?php
}
?>

<div style="page-break-after: always;"></div>

 
</div>
 

<?php if($_GET['from']=='b'){  ?> <script></script>  <?php } ?>
<script src="../../cpanel/js/jquery-3.1.1.min.js"></script>
<script src="../../cpanel/js/html2canvas.min.js"></script>
<script src="../../cpanel/js/jspdf.umd.js"></script>
<script>
window.jsPDF = window.jspdf.jsPDF;

 
	var doc = new jsPDF();
	
	// Source HTMLElement or a string containing HTML.
	var elementHTML = document.querySelector("#contentToPrint");

	doc.html(elementHTML, {
		callback: function(doc) {
			// Save the PDF
		//	doc.save('data/fees-statement.pdf');
		 doc.save('Fees-Statement.pdf');
		 
		},
		margin: [10, 10, 10, 10],
		autoPaging: 'text',
		x: 0,
		y: 0,
		width: 190, // Target width in the PDF document
		windowWidth: 675 // Window width in CSS pixels
	});

 
</script>

  

