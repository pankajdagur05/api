<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$branchid=$obj['branchid'];
$type=$obj['typefirst'];
$session=$obj['session'];
$userid=$obj['userid'];
$cid=$branchid; 
$user=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s'); 
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){

$arr['status']=true; 
$arr['message']="success";
$arr['erp']=array();
$arr['attendance']=array();
$arr['gatepass']=array();
$arr['student']=array();
$ss=mysqli_query($host,"select * from branch_category where sub_domain='$subdomain' and type='branch' and (status='Active' or status IS NULL)"); while($dd=mysqli_fetch_array($ss)){ $bid=$dd['id'];
$row['name']=$dd['iname'];
$row['subdomain']=$dd['name'];
$q="select sum(credit) from feesdue where cid='$bid' and cancel='No' and rdate='$date' and voucher='receive' and mode<>'Concession'"; 	$s4=mysqli_query($host,$q); 
	$d4=mysqli_fetch_array($s4);
	$dpo=$d4[0];
	 $s00=mysqli_query($host,"select sum(amount) from accounts where cid='$bid' and side='credit' and mode<>'due' and  idate='$date'");
 $d00=mysqli_fetch_array($s00);
 $fee=$d4[0]+$d00[0]; 
   $totalr+=$fee; 
   
$row['trece']=$fee; 

$s4=mysqli_query($host,"select sum(amount) from accounts where cid='$bid' and side='debit' and mode<>'due' and  idate='$date' "); 
	$d4=mysqli_fetch_array($s4);
	$fee=$d4[0]==NULL?0:$d4[0]; 

$row['texp']=$fee;


 $month=date('m');
$pmonth=date('m', strtotime(date('Y-m')." -1 month"));
$year=date('Y');
$day=date('d');
$s4=mysqli_query($host,"select sum(credit) from feesdue where cid='$bid' and cancel='No'  and month(rdate)='$month' and year(rdate)='$year' and mode<>'Concession'"); 
	$d4=mysqli_fetch_array($s4);
	$dpo=$d4[0];
 $s00=mysqli_query($host,"select sum(amount) from accounts where cid='$bid' and side='credit' and mode<>'due' and  idate='$date'");
 $d00=mysqli_fetch_array($s00);
 $fee=$d4[0]+$d00[0];  
 
$row['mrece']=$fee;
 
  $s=mysqli_query($host,"select * from feesdue where rdate='$date' and cid='$user' group by invoice"); $invoice=mysqli_num_rows($s);                 
  $q="select sum(amount) from accounts where cid='$bid' and side='debit' and mode<>'due' and  month(idate)='$month' and year(idate)='$year' ";
  	$s4=mysqli_query($host,$q); 
	$d4=mysqli_fetch_array($s4);
	$fee=$d4[0];
 
$row['mexp']=$fee;

$arr['erp'][]=$row;

}

 $ss=mysqli_query($host,"select * from branch_category where sub_domain='$subdomain' and type='branch' and (status='Active' or status IS NULL)"); while($dd=mysqli_fetch_array($ss)){ $bid=$dd['id']; 
                
                $ss1=mysqli_query($host,"select * from session where cid='$bid' order by id desc limit 1");
                $dd1=mysqli_fetch_array($ss1);
                $sess=$dd1['name'];
                $row1['name']=$dd['iname'];
                $row1['subdomain']=$dd['name'];
                
    $s=mysqli_query($host,"select * from staff_attendance where subdomain='$bid' and idate='$date' and status='p' group by sid");
    $s1=mysqli_query($host,"Select id from staff where branch='$bid' and status='Active'");
    $r1=mysqli_num_rows($s1);
    $row1['staff']=mysqli_num_rows($s);
    $row1['totalstaff']=$r1;
    
     $s=mysqli_query($host,"select students.standard from attendance INNER JOIN students ON students.id=attendance.sid where students.cid='$bid' and students.session='$sess' and attendance.idate='$date' group by students.standard");  
$pclass=mysqli_num_rows($s);
$s1=mysqli_query($host,"select standard from students where session='$sess' and status='Active' and cid='$bid' group by standard");
$r1=mysqli_num_rows($s1);


    $row1['classs']=$pclass;
    $row1['totalclasss']=$r1;
    
     $s=mysqli_query($host,"select sum(if(status='p',1,0)) as p from attendance where idate='$date' and subdomain='$bid'");   
$d=mysqli_fetch_array($s); 

$s2=mysqli_query($host,"select * from students where cid='$bid' and session='$sess' and status='Active'");
$r2=mysqli_num_rows($s2);

$row1['students']=$d[0];
$row1['totalstudents']=$r2;
    
    $arr['attendance'][]=$row1;
    
                
                
 }
 
 $g1=0; $g2=0;$g3=0;$ss=mysqli_query($host,"select * from branch_category where sub_domain='$subdomain' and type='branch' and (status='Active' or status IS NULL)");
 while($dd=mysqli_fetch_array($ss)){ 
     $bid=$dd['id'];
  $s=mysqli_query($host,"SELECT COUNT(id),sum(if((checkout='' or checkout IS NULL),1,0)) FROM `gatepass_data` WHERE cid='$bid' and date='$date'");
$d=mysqli_fetch_array($s); 
$out=$d[0]-$d[1];
 $row2['name']=$dd['iname'];
$row2['subdomain']=$dd['name'];
 $row2['total']=$d[0];
 $row2['outside']=$out;
 $row2['inside']=$d[1];
 $arr['gatepass'][]=$row2;
 
 }
 
 $ss=mysqli_query($host,"select * from branch_category where sub_domain='$subdomain' and type='branch' and (status='Active' or status IS NULL)"); while($dd=mysqli_fetch_array($ss)){ $bid=$dd['id']; 
                
                $ss1=mysqli_query($host,"select * from session where cid='$bid' order by id desc limit 1");
                $dd1=mysqli_fetch_array($ss1);
                $sess=$dd1['name'];
                 $s=mysqli_query($host,"SELECT sum(if(status='active',1,0)) as active,count(id),sum(if(rte='yes',1,0)) as rte,sum(if(length(tc_date)>0,1,0)) as tc_date,sum(if(admission='new',1,0)) as new FROM `students` WHERE cid='$bid' and session='$sess';");	
$d=mysqli_fetch_array($s);
$row3['name']=$dd['iname'];
$row3['subdomain']=$dd['name'];
$row3['total']=$d[1];
$row3['active']=$d[0];
$row3['inactive']=$d[1]-$d[0];
$row3['rte']=$d[2];
$row3['tc']=$d[3];
$row3['news']=$d['new'];
$arr['student'][]=$row3;

                
                
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