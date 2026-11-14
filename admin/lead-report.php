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
$tp=$obj['tp'];
$cid=$branchid; 
$user=$branchid;
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
if($r==1){
    
$arr['status']=true;
$arr['message']="ss ".$tp;
$arr['data']=array();
if(strtolower($tp)=='marketing'){

$s=mysqli_query($host,"select staff.name,staff.id as sid,lead.cam from lead INNER JOIN staff ON staff.id=lead.user where lead.branch='$user' and lead.archive IS NULL and lead.type='other' group by lead.user"); while($d=mysqli_fetch_array($s)){ $sid=$d['sid'];

$row['staff']=$d['name']; 
$row['camp']=array();
$q="select lead.cam,count(lead.id),sum(if(lead.lead_convert='yes',1,0)) as con,sum(if(lead.date='$date',1,0)) as today,sum(if(lead.lead_close='yes',1,0)) as close from lead where lead.branch='$user' and lead.user='$sid' and lead.type='other'  and lead.archive IS NULL group by lead.cam";$s1=mysqli_query($host,$q); while($d1=mysqli_fetch_array($s1)){ $camp=$d1[0];
   
$row2['camp']=$camp;
$row2['total']=$d1[1];
$row2['close']=$d1['close'];
$row2['con']=$d1['con'];
$row2['today']=$d1['today'];
$row['camp'][]=$row2;   
} 
$arr['data'][]=$row;
}        
    
    
}else{
if(strtolower($tp)=='manager'){

 $s=mysqli_query($host,"select staff.name,compaign_allot.sid from compaign_allot INNER JOIN staff ON staff.id=compaign_allot.sid where compaign_allot.cid='$user' and compaign_allot.type='manager' group by compaign_allot.sid order by compaign_allot.id desc"); while($d=mysqli_fetch_array($s)){ $sid=$d['sid'];
     
$row['staff']=$d['name']; 
$row['camp']=array();  
 $q="select lead.cam,count(lead.id),sum(if(lead.lead_convert='yes',1,0)) as con,sum(if(lead.lead_close='yes',1,0)) as close from compaign_allot INNER JOIN lead on lead.cam=compaign_allot.name where lead.branch='$user'  and compaign_allot.cid='$user' and compaign_allot.sid='$sid' and compaign_allot.type='manager'  and lead.archive IS NULL group by lead.cam order by compaign_allot.id desc";$s1=mysqli_query($host,$q); while($d1=mysqli_fetch_array($s1)){ $camp=$d1[0];
 
$s2=mysqli_query($host,"select count(lead.id),sum(if(lead_allot.date='$date',1,0)) as today from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead_allot.cid='$user' and lead.branch='$user' and lead.cam='$camp' and lead_allot.status='yes'  and lead.archive IS NULL ");
$d2=mysqli_fetch_array($s2); 
   
$row2['camp']=$camp;
$row2['total']=$d1[1];
$row2['close']=$d1['close'];
$row2['con']=$d1['con'];
$row2['today']=$d2['today'];
$row2['allot']=$d2[0];
$row2['unallot']=$d1[1]-$d2[0];
$row['camp'][]=$row2;   
} 
$arr['data'][]=$row;
     
     
 } 
 
    
}else{
 

 $s=mysqli_query($host,"select staff.name,compaign_allot.sid,compaign_allot.name as camp,count(compaign_allot.id) as num,staff.id as idd from compaign_allot INNER JOIN staff ON staff.id=compaign_allot.sid where compaign_allot.cid='$user' and compaign_allot.type='TeleCaller' group by compaign_allot.sid order by compaign_allot.id desc"); 
     while($d=mysqli_fetch_array($s)){ $sid=$d['sid']; $staffid=$d['idd'];
$row['staff']=$d['name']; 
$row['camp']=array();  

$q="select lead.cam,count(lead.id),sum(if(lead.lead_convert='yes',1,0)) as con,sum(if(lead.lead_close='yes',1,0)) as close from compaign_allot INNER JOIN lead on lead.cam=compaign_allot.name where lead.branch='$user' and compaign_allot.sid='$sid' and compaign_allot.type='TeleCaller'  and lead.archive IS NULL group by lead.cam order by compaign_allot.id desc"; $s1=mysqli_query($host,$q); while($d1=mysqli_fetch_array($s1)){ $camp=$d1[0];
 
$s2=mysqli_query($host,"select lead.id from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead_allot.cid='$user' and  lead.branch='$user' and lead_allot.status='yes' and lead.sub_domain='$subdomain' and lead_allot.sid='$staffid' and lead.cam='$camp' and lead.archive IS NULL ");
$r2=mysqli_num_rows($s2);  
 
$s22=mysqli_query($host,"select lead.id from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead_allot.cid='$user' and  lead.branch='$user' and lead_allot.status='yes' and lead.sub_domain='$subdomain' and lead.appoint<='$date' and lead_close<>'yes' and lead_allot.sid='$staffid' and lead.cam='$camp' and lead.archive IS NULL ");
$r22=mysqli_num_rows($s22); 
 $q="select count(complaint_status.id) from complaint_status INNER JOIN lead ON lead.id=complaint_status.comp_id  where complaint_status.user='$user' and complaint_status.date='$date' and lead.cam='$camp' and complaint_status.staffid='$staffid' ";
$s3=mysqli_query($host,$q);
$d3=mysqli_fetch_array($s3);
if($r22==""){$r22=0;}   
$row2['camp']=$camp;
$row2['total']=$r2;
$row2['close']=$d1['close'];
$row2['con']=$d1['con'];
$row2['today']=$d3[0];
$row2['pending']=$r22;
$row2['upcoming']=$r2-$r22;
$row['camp'][]=$row2;   
} 
$arr['data'][]=$row;
     
     
 } 
 
    
 
  
    
}     
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