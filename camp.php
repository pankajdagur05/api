<?php error_reporting(4);
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$username=$obj['userid'];
$token=$obj['token'];
$from=$obj['from'];
$to=$obj['to'];
$type='staff';
$date=date('Y-m-d');
$dates=date('Y-m-d');
$time=date('h:i:s');
$today=date('Y-m-d');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $user=$branch;
$arr['status']=true;


$xlo="type='other' and user='$staff' and ";
  
$arr['data']=array();

$s=mysqli_query($host,"select count(*),SUM(CASE WHEN lead_close='yes' THEN 1 ELSE 0 END) as tot,SUM(CASE WHEN lead_convert='yes' THEN 1 ELSE 0 END) as tott from lead where user='$staff' and cam IS NULL and type='other' and archive IS NULL and branch='$user' order by id desc");
$d=mysqli_fetch_array($s);
  $ss1=mysqli_query($host,"select * from lead where appoint<='$dates' and archive IS NULL and user='$staff' and cam IS NULL and type='other' and branch='$user'");
  $dd1=mysqli_num_rows($ss1);
  

$st=mysqli_query($host,"select count(*),SUM(CASE WHEN lead_close='yes' THEN 1 ELSE 0 END) as tot from lead where user='$staff' and cam IS NULL and type='other' and date='$today' and branch='$user' order by id desc");
$dt=mysqli_fetch_array($st);
  $ss1t=mysqli_query($host,"select * from lead where appoint<='$dates' and archive IS NULL and user='$staff' and cam IS NULL and type='other' and date='$today' and branch='$user'");
  $dd1t=mysqli_num_rows($ss1t);  
  
  
  
   
$ttlead+=$d[0];
$ttclead+=$d['tot'];
$ttalead+=$dd1;

   
$tttlead+=$dt[0];
$tttclead+=$dt['tot'];
$tttalead+=$dd1t;


$row['id']=0; 
$row['name']='No Compaign';
$row['pappoint']=$dd1;
$row['lead']=$d[0];
$row['convert']=$d['tott'];
$row['clead']=$d['tot'];
$arr['data'][]=$row;  

$s=mysqli_query($host,"select * from compaign_allot where sid='$staff' and cid='$user'");
while($d=mysqli_fetch_array($s)){
$cam=$d['name'];
$type=$d['type'];
$row['allotee']=array(); 
if($type=='TeleCaller'){

  
 $s1=mysqli_query($host,"select lead.* from lead INNER JOIN lead_allot ON lead_allot.lid=lead.id where lead.cam='$cam' and lead.archive IS NULL and lead.branch='$user' and lead_allot.status='yes' and lead_allot.sid='$username' order by lead.id desc");
$d1=mysqli_fetch_array($s1);
$q00="select count(lead.id) from lead INNER JOIN  lead_allot ON lead_allot.lid=lead.id  where lead_allot.status='yes' and lead.appoint<='$dates'and lead_allot.sid='$username' and lead.archive IS NULL and lead.cam='$cam' and lead.type='other' and lead.branch='$user' ";
 $sss1=mysqli_query($host,$q00);
  $ddd1=mysqli_fetch_array($sss1);   
  $ddd1=$ddd1[0];
  $s3=mysqli_query($host,"select * from lead INNER JOIN  lead_allot ON lead_allot.lid=lead.id  where lead.cam='$cam' and lead.archive IS NULL and lead.branch='$user' and lead_allot.sid='$username' and lead.lead_convert='yes' order by lead.id desc");

  $s2=mysqli_query($host,"select * from lead INNER JOIN  lead_allot ON lead_allot.lid=lead.id  where lead.cam='$cam' and lead.archive IS NULL and lead.branch='$user' and lead_allot.sid='$username' and lead.lead_close='yes' order by lead.id desc");



  
}else{
 if($type=='Manager' or $type=='Viewer'){
$s1=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and sub_domain='$subdomain' and branch='$user' order by id desc");
$d1=mysqli_fetch_array($s1);
$r1=mysqli_num_rows($s1);
$sss1=mysqli_query($host,"select count(id) from lead where appoint<='$dates' and archive IS NULL and cam='$cam' and type='other' and branch='$user'");
$ddd1=mysqli_fetch_array($sss1);
$ddd1=$ddd1[0];
$ssssss1=mysqli_query($host,"select count(lead.id) from lead INNER JOIN lead_allot ON lead.id=lead_allot.lid where lead.branch='$user' and  lead.cam='$cam' and  lead.sub_domain='$subdomain' and  lead.archive IS NULL group by lead.id order by  lead.id desc");  
$r2=mysqli_num_rows($ssssss1);
$mdddd1=$r1-$r2;

  
$s2=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and branch='$user'  and lead_close='yes' order by id desc");
$s3=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and branch='$user'  and lead_convert='yes' order by id desc");


$s011=mysqli_query($host,"select *,staff.name as sname from compaign_allot INNER JOIN staff ON compaign_allot.sid=staff.id where compaign_allot.name='$cam' and compaign_allot.cid='$user' and compaign_allot.type='TeleCaller'");
while($d011=mysqli_fetch_array($s011)){
$row2['id']=$d011['sid'];
$row2['name']=$d011['sname'];
$row['allotee'][]=$row2;
}
     
     
 }else{
     
     $s1=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and type='other' and user='$username' order by id desc");
$d1=mysqli_fetch_array($s1);

$s2=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and branch='$user' and type='other' and user='$username' and lead_close='yes' order by id desc");
  $s3=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and branch='$user' and type='other' and user='$username' and lead_convert='yes' order by id desc");

$q00="select count(id) from lead where appoint<='$dates' and archive IS NULL and user='$username' and cam IS '$cam' and type='other' and branch='$user'";
 $sss1=mysqli_query($host,$q00);
  $ddd1=mysqli_fetch_array($sss1);
  $ddd1=$ddd1[0];


$s1t=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and branch='$user' and type='other' and user='$username' and date='$today' order by id desc");
$d1t=mysqli_fetch_array($s1t);

$s2t=mysqli_query($host,"select * from lead where cam='$cam' and archive IS NULL and branch='$user' and type='other' and user='$username' and lead_close='yes' and date='$today'  order by id desc");


 $sss1t=mysqli_query($host,"select * from lead where appoint<='$dates' and archive IS NULL and user='$username'  and cam IS '$cam' and type='other' and date='$today'  and branch='$user'");
  $ddd1t=mysqli_num_rows($sss1t);
  


}   
}

$arr['message']="success";
$tlead=mysqli_num_rows($s1);
$ttlead+=$tlead;
$tclead=mysqli_num_rows($s2);
$ttclead+=$tclead;
$ttalead+=$ddd1;

$tttlead=mysqli_num_rows($s1t);
$tttlead+=$tlead;
$ttclead=mysqli_num_rows($s2t);
$tttclead+=$ttclead;
$tttalead+=$ddd1;


$row['id']=$d['id']; 
$row['name']=$d['name'];
$row['hide']=$d['hide'];
if($type=='Manager' or $type=='Viewer')
{
    $row['pappoint']=$mdddd1==NULL?0:$mdddd1;
}else
{
    $row['pappoint']=$ddd1==NULL?0:$ddd1;
}

$row['lead']=$tlead;
$row['clead']=$tclead;
$row['convert']=mysqli_num_rows($s3);
$row['type']=$type;
$arr['data'][]=$row;    
}


$arr['total']=$ttlead==""?0:$ttlead;
$arr['closed']=$ttclead==""?0:$ttclead;
$arr['ueng']=$ttlead-($ttalead+$ttclead);
$arr['appo']=$ttalead==""?0:$ttalead;

$arr['ttotal']=$tttlead==""?0:$tttlead;
$arr['tclosed']=$tttclead==""?0:$tttclead;
$arr['tueng']=$tttlead-($tttalead+$tttclead);;
$arr['tappo']=$tttalead==""?0:$tttalead;



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