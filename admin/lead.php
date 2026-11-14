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
$arr['message']='success';

$s=mysqli_query($host,"select count(lead.id),sum(if(lead.lead_convert='yes',1,0)) as con,sum(if(lead.lead_close='yes',1,0)) as close,sum(if(lead.appoint<='$date',1,0)) as appoint from lead where lead.branch='$user' and lead.archive IS NULL ");
$d=mysqli_fetch_array($s);
$arr['total']=$d[0];
$arr['close']=$d[2];
$arr['covert']=$d[1];
$s1=mysqli_query($host,"select count(id) from lead_allot where cid='$user' and lead_allot.status='yes'");
$d1=mysqli_fetch_array($s1);
$arr['allot']=$d1[0];
$arr['unallot']=$d[0]-$d1[0];
$arr['engage']=$d['appoint'];
$arr['notengage']=$d[0]-$d['appoint'];
$s1=mysqli_query($host,"select count(id) from complaint_status where user='$user' and date='$date'");
$d1=mysqli_fetch_array($s1);
$arr['today']=$d1[0];

$arr['data']=array();
$s=mysqli_query($host,"select count(lead.id),sum(if(lead.lead_convert='yes',1,0)) as con,sum(if(lead.lead_close='yes',1,0)) as close,sum(if(lead.appoint<='$date',1,0)) as appoint,cam from lead where lead.branch='$user' and lead.archive IS NULL group by cam");
while($d=mysqli_fetch_array($s)){
$camp=$d['cam']; 
$row['camp']=$camp;
$row['total']=$d[0];
$row['close']=$d[2];
$row['covert']=$d[1];
$s1=mysqli_query($host,"select count(lead_allot.id) from lead_allot INNER JOIN lead ON lead.id=lead_allot.lid where lead_allot.cid='$user' and lead.cam='$camp' and lead_allot.status='yes'");
$d1=mysqli_fetch_array($s1);
$row['allot']=$d1[0];
$row['unallot']=$d[0]-$d1[0];
$row['engage']=$d['appoint'];
$row['notengage']=$d[0]-$d['appoint'];
$s1=mysqli_query($host,"select count(complaint_status.id) from complaint_status INNER JOIN lead ON lead.id=complaint_status.comp_id  where complaint_status.user='$user' and complaint_status.date='$date' and lead.cam='$camp'");
$d1=mysqli_fetch_array($s1);
$row['today']=$d1[0];
$arr['data'][]=$row;
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