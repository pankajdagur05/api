<?php include("../../../db.php"); 
$sid=$_GET['sid'];
$id=$sid;
$token=$_GET['token'];
$username=$_GET['username'];
$s=mysqli_query($host,"select * from apk_token where token='$token' and user='$username'");    
if(mysqli_num_rows($s)==0){
?>
<script>window.location="404.html";</script> 
<?php
}
	$s=mysqli_query($host,"select * from students where id='$id'");
	$d=mysqli_fetch_array($s);
	$sid=$d['id'];
	$user=$d['cid'];
	$session=$d['session'];
	$sql=mysqli_query($host,"select * from branch_category where id='$user'");
	$like=mysqli_fetch_array($sql);
	
	$s2=mysqli_query($host,"select * from  feesdue where voucher='receive' and cid='$user' and sid='$sid' and session='$session' order by id desc");	
$d2=mysqli_fetch_array($s2);
	$save=$_GET['token'].".pdf";
	
	
	$image='../../admin-panel/cpanel/logo/'.$user.'.png';

// Read image path, convert to base64 encoding
$imageData=base64_encode(file_get_contents($image));

// Format the image SRC:  data:{mime};base64,{data};
$src = 'data:'.mime_content_type($image).';base64,'.$imageData;

$docs="<table style='border-collapse:collapse; border:1px solid #ccc; height:90px; width:98%; margin-left:6px'>
<tr>
<th width='100' id='tt'>Date</th>
<th width='200' id='tt'>Fees head</th>
<th width='100' id='tt'>Payable Amount</th>
<th width='100' id='tt'>Deposit Amount</th>
</tr>";    
mysqli_query($host,$q);
$q2="select *,feesdue.id as idd,erp_group.name as gname,erp_category.name as cname from feesdue INNER JOIN erp_category INNER JOIN erp_group ON erp_group.id=erp_category.type and erp_category.id=feesdue.cateid where feesdue.cid='$user' and feesdue.sid='$sid' and feesdue.cancel='No' order by feesdue.rdate asc"; $s3=mysqli_query($host,$q2);  
while($d3=mysqli_fetch_array($s3)){
if($d3['mode']!='Concession'){                     
 $docs.="<tr>
  <td id='tabb1'>".date('d-m-Y',strtotime($d3['rdate']))."</td>   
             <td id='tabb1'><b>".$d3['gname'].' - '.$d3['cname']."</b></td>   
              <td id='tabb1'>".$d3['debit']."</td>  
             <td id='tabb1' row-span='2'>".$d3['credit']."</td>

</tr>";
$pay+=$d3['debit'];
$rece+=$d3['credit']; 
}else{
	$con+=$d3['credit'];
}
}
$bal=$pay-($rece+$con);
$docs.="<tr  style='font-weight: bold; background:#f00;'>
  <th id='tabb1' colspan='2'>Total</th>     
              <th id='tabb1'>".$pay."</th>  
             <th id='tabb1' row-span='2'>".$rece."</th>

</tr>";
if($con){
$docs.="<tr  style='font-weight: bold; background:#f00;'>
  <th id='tabb1' colspan='2'>Concession</th>     
              <th id='tabb1'></th>  
             <th id='tabb1' row-span='2'>".$con."</th>

</tr>";
}
$docs.="<tr  style='font-weight: bold; background:#f00;'>
  <th id='tabb1' colspan='2'>Balance</th>     
              <th id='tabb1' colspan='2'>".$bal."</th>  
            

</tr>
</table>"; 

?>

 

<?php

$html="<style>

*{margin:0px; padding:0px;}

body{ height:auto; width:100%; }

.main{ height:auto; overflow:hidden; width:100%; margin:auto; background-color: #fff; border:0px solid #cc1b1b85;}

.c{ height:285mm; width:200mm; margin:auto; border:1px solid #fff; }
.c_a{ height:95%; width:95%; margin:auto; border:0px solid #CCC; margin-top:5% }
.had{ height:auto; overflow:hidden; width:100%; float:left;border:0px solid #e9d9ef; font-family:arial}
.secti{ height:auto; overflow:hidden; min-height:30px; width:97%; float:left;border:0px solid #e9d9ef;  }
.had1{ height:auto; overflow:hidden; color:#000; border:0px solid #666; font-family:Arial; text-align:center;font-size:17px; line-height:34px; text-transform:uppercase; font-weight:bold; background-color: rgba(255,255,0,1)}
.had11{ height:auto; overflow:hidden; color:#444; border:0px solid #666; font-family:Arial; text-align:center;font-size:13px; line-height:20px; text-transform:capitalize}
.had111{height: auto;
    overflow: hidden;
    width: 98%;
    color: rgb(255, 255, 255);
    border: 0px solid #666;
    font-family: Arial;
    text-align: left;
    font-size: 14px;
    line-height: 20px;
    text-transform: uppercase;
    font-weight: bold;
    background-color: #006837;
    padding: 5px;}
.h1{ height:34px; width:100%; float:left;border:0px solid #e9d9ef; font-family:arial; text-transform:uppercase; line-height:30px; text-align:left;
font-size:14px; }

.c1{ height:auto; overflow:hidden; width:100%; float:left;border:0px solid #666;}


#table1{ border-collapse:collapse; width:100%}
th{    height: auto;
    padding: 4px;
    color: #444;
    border: 0px solid #e0e0e0;
    background-color: #fff;
    font-family: Arial, Gadget, sans-serif;
    text-align: left;
    font-size: 12px;
    line-height: 20px;
    background: #eee;
    text-transform: capitalize;}
td{height: auto;
    color: #666;
    border: 0px solid #e0e0e0;
    background-color: #fff;
    font-family: Arial, Gadget, sans-serif;
    text-align: left;
    font-size: 12px;
    padding: 4px;
    line-height: 20px;
    text-transform: capitalize;
    background: #f9f7f7;}
#cent{ text-align:center}

#j11{ padding:1px;color:#5192eb; border:0px solid #e9d9ef; font-family:Arial, Gadget, sans-serif; text-align:left; text-indent:3px;font-size:13px}

.c_l2{ height:55px; width:55px; position:fixed; bottom:30px; right:30px; border:0px solid #CCC; }
.c_l21{ height:55px; width:55px; background:rgba(51,102,204,1) url(img/printer.png) no-repeat center; float:right; border:0px solid #069;font-family:arial; font-size:15px; font-style:italic; text-align:center;line-height:122px; color:#069; margin-top:1px; cursor:pointer; border-radius:3px }

@media all {
 .page-break { display: none; }
}

@media print {
 .page-break { display: block; page-break-before: always; }
}

@page {
  size: A4 portrait;
  margin: 0;
}
.h1{ height:30px; width:100%; float:left;border:0px solid #666; margin-top:2px}
.h11{ height:15px; width:160px; float:right;border:0px solid #666; font-family:arial; font-size:12px; font-weight:bold; text-align:center; color:#3571a7; line-height:17px}
.h12{ height:15px; width:160px; float:right;border:0px solid #666; font-family:arial; font-size:12px; font-weight:bold;text-align:center; color:#3571a7; line-height:17px}

.h2{ height:140px; width:100%; float:left;border:0px solid #666; margin-top:3px}
.h21{ height:100px; width:100px; float:left;border:0px solid #666; margin-left:10px; margin-top:6px}
.h220{ height:auto; overflow:hidden; width:83%; float:left;border:0px solid #666;}
.h22{ height:auto; overflow:hidden; min-height:45px; width:100%; float:left;border:0px solid #666; font-family:'Trebuchet MS'; font-size:31px; font-weight:bold; text-align:center; color:rgb(45, 72, 101); line-height:33px; margin-top:10px; text-transform:uppercase}
.h22t{ height:20px; width:100%; float:left;border:0px solid #666; font-family:'Trebuchet MS'; font-size:11px; font-weight:bold; text-align:center; color:rgb(45, 72, 101); line-height:20px;}
.h24{ height:100px; width:100px; float:left;border:0px solid #666;margin-top:6px}

</style> 
<table id='table1'>
<tr>
<td rowspan='3'></td>
<td colspan='3' style='font-size:22px; text-align:center;'><b>".$like['iname']."</b></td>
</tr>
<tr>
<td colspan='3' style='font-size:12px; text-align:center;'>".$like['address'].'E-mail '.$like['email'].'Mobile '.$like['mobile']."</td>
</tr>

<tr>
<td colspan='3' style='font-size:15px; text-align:center;'><b>Session : ".$d['session']."</b></td>
</tr>
</table>

<table id='table1'>
<tr>
<th width='100'>Sr No.</th>
<td width='100'>".$d['stu_id']."</td>
<th width='100'>Invoice No.</th>
<td width='100'>".$d2['invoice']."</td>
</tr>
<tr>
<th width='100'>student name</th>
<td width='100'>".$d['name']."</td>
<th width='100'>father's name</th>
<td width='100'>".$d['fname']."</td>
</tr>
</table>
";
$html.=$docs;
$html.="<br/><br/><p style='font-size:10px;'><center>This Receipt Generated by Computer So No Need Any Seal & Sign.</center></p>";
?>
<?php
$path="../save/".$save;
require_once 'dompdf/autoload.inc.php'; 
// Reference the Dompdf namespace 
use Dompdf\Dompdf; 
 
// Instantiate and use the dompdf class 
$dompdf = new Dompdf();
$dompdf->loadHtml($html); 
 
// (Optional) Setup the paper size and orientation 
$dompdf->setPaper('A4', 'landscape'); 
 
// Render the HTML as PDF 
$dompdf->render(); 
 
// Output the generated PDF (1 = download and 0 = preview) 
file_put_contents($path, $dompdf->output());
$arr['status']=true;
$arr['link']="https://edpluss.com/school/admin-panel/api/student/save/".$_GET['token'].'.pdf';
echo json_encode($arr);
?>


 
 