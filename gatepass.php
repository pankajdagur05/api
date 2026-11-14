<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$username=$_POST['userid'];
$timd=time();
$token=$_POST['token'];
$gate1="1";
$gate2="2";
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){
        
 $mm=mysqli_query($host,"select expire from admin_login where sub_domain='$subdomain'");
        $dd=mysqli_fetch_array($mm);
        if(strtotime($dd[0])>time()){
        

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffname=$d3['name'];
      $user=$branch;
      $mobile=$d3['mobile'];
      $arr['account']=array();
      $ss=mysqli_query($host,"select staff.id,branch_category.iname,branch_category.*,branch_category.id as branch from staff INNER JOIN branch_category ON branch_category.id=staff.branch where staff.mobile='$mobile' and staff.status='Active'");
      while($dd=mysqli_fetch_array($ss))
      {
           $ro['name']=$dd['iname'];
           $ro['logo']=$dd['logo']==""?"":"https://edpluss.com/school/admin-panel/cpanel/logo/".$dd['id'].".png";
           $ro['address']=$dd['address'];
           $ro['sid']=$dd[0];
           $ro['branch']=$dd['branch'];
           $ro['gatepass_everify']=$dd['gatepass_everify'];
           $ro['gatepass_photo']=$dd['gatepass_photo'];
           $arr['account'][]=$ro;
      }
       
$tid=$_POST['update'];
$delete=$_POST['delete'];
$subject=$_POST['subject'];
$desc=$_POST['desc'];
$stype=$_POST['type']; 
$file=$_FILES['uplm']; 
if($file['name']){
$file2=$_FILES["uplm"]["tmp_name"];
$ex2=$_FILES['uplm']['name'];
$extn2=pathinfo($ex2, PATHINFO_EXTENSION);	
$path=date().time();
$fname=$path.".png";
$path3="../cpanel/uploads/".$fname;
move_uploaded_file($file2,$path3);    
}else{
if($tid){
  $s01=mysqli_query($host,"select * from gatepass where id='$tid' and user='$branch'");
  $d01=mysqli_fetch_array($s01);
  $fname=$d01['upload_pics'];
}else{
    $fname="";
}    

}



if($subject){
if($tid){

     $name=$_POST['name'];
     $mobile=$_POST['mobile'];
      $amobile=$_POST['amobile'];
      $sms=$_POST['sms'];
      $regular=$_POST['regular'];
    $q="update gatepass set app_name='$name',app_mobile='$mobile',at_mobile='$amobile',com_subject='$subject',com_desc='$desc',upload_pics='$fname',type='$stype',staffid='$staff',staffname='$staffname',date='$date',regular='$regular',time='$time' where id='$tid' and user='$branch' ";    

mysqli_query($host,$q);
$arr['status']=true;

$sids=explode(',',$_POST['sid']);
$types=explode(',',$_POST['types']);
if(count($sids)-1==0)
{
    mysqli_query($host,"delete from gatepass_person where gatepass_id='$tid'");

}else
{
 for($a=0; $a<count($sids)-1; $a++){
$typed=$types[$a];
$sid=$sids[$a];
mysqli_query($host,"insert into gatepass_person set gatepass_id='$tid',sid='$sid',type='$typed',date='$date',time='$time',user='$branch',staffid='$staff',staffname='$staffname'");
}
}
$arr['message']='success Updated'; 
}else{  

    $name=$_POST['name'];
    $sids=explode(',',$_POST['sid']);
    $stuid=$sids[0];
    $mobile=$_POST['mobile'];
    $timealert=strtotime("+15 minutes",time());
    $amobile=$_POST['amobile'];
$sms=$_POST['sms'];
$regular=$_POST['regular'];
$checkin=$_POST['checkin'];

$q="insert into gatepass set app_name='$name',app_mobile='$mobile',at_mobile='$amobile',com_subject='$subject',com_desc='$desc',upload_pics='$fname',type='$stype',user='$branch',staffid='$staff',staffname='$staffname',regular='$regular',date='$date',time='$time'";    
if(mysqli_query($host,$q)){
$s00=mysqli_query($host,"select * from gatepass where user='$branch' order by id desc limit 1");    
$d00=mysqli_fetch_array($s00);
$tid=$d00['id'];
$passtype='Direct';
$times=time();
$date=date('Y-m-d');
if($stype=='student' and strtolower($subject)=='early checkout' ){
 mysqli_query($host,"insert into gatepass_data set checkout='$times',checkout_status='$passtype',checkout_gateno='$gate1',staffname_sec='$staffname',staffid_sec='$staff',checkin='$times',checkin_status='$passtype',checkin_gateno='$gate1',staffname='$staffname',staffid='$staff', gatepass_id='$tid' ,time='$time',date='$date',cid='$user'");  
  $s22=mysqli_query($host,"select id from attendance where subdomain='$user' and sid='$stuid' and idate='$date'");
  if(mysqli_num_rows($s22)>0){
     mysqli_query($host,"update attendance set status='T' where subdomain='$user' and sid='$stuid' and idate='$date'"); 
  }else{
      mysqli_query($host,"insert into attendance set status='T',subdomain='$user',sid='$stuid',idate='$date',date='$date',time='$time',remark='$subject'"); 
  }
}else{
 mysqli_query($host,"insert into gatepass_data set checkin='$times',timealert='$timealert',checkin_status='$passtype',checkin_gateno='$gate1',staffname='$staffname',staffid='$staff', gatepass_id='$tid' ,time='$time',date='$date',cid='$user'");  
}
    
} 


$s00=mysqli_query($host,"select * from gatepass where user='$branch' and staffid='$staff' order by id desc limit 1");
$d00=mysqli_fetch_array($s00);


$types=explode(',',$_POST['types']);
$arr['status']=true;
$arr['message']="update attendance set status='LT' where subdomain='$user' and sid='$stuid' and idate='$date'";
$cid=$d00['id'];
$othernames=explode(',',$_POST['othername']);
 for($a=0; $a<count($othernames)-1; $a++){
$othernamess=$othernames[$a];
if($othernamess)
{
$q.="insert into gatepass_other set gatepass_id='$cid',name='$othernamess',date='$date',user='$branch'";
mysqli_query($host,"insert into gatepass_other set gatepass_id='$cid',name='$othernamess',date='$date',user='$branch'");
++$ttp;
}
}
$s=6-strlen($cid);
for($x=0; $x<$s; $x++){
$ot.="0";
}
$ot.=$cid;
$arr['message']="success";
  
  
  
mysqli_query($host,"update gatepass set passcode='$ot' where user='$user' and id='$cid'");


$sm=mysqli_query($host,"select * from whatsapp_config where cid='$user'");    
if(mysqli_num_rows($sm)>0){
$dm=mysqli_fetch_array($sm);
 $mymobile=$dm['mobile'];
 $version=$dm['version'];
 $pkey=$dm['pkey'];
 $phoneid=$dm['phone_id'];
$sm4=mysqli_query($host,"select * from whatsapp where cid='$user'");
$dm4=mysqli_fetch_array($sm4);
if($dm4['com_temp']){
    
$temp=$dm4['com_temp'];    

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://graph.facebook.com/'.$version.'/'.$phoneid.'/messages',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "messaging_product": "whatsapp",
    "recipient_type": "individual",
    "to": "'.$mobile.'",
    "type": "template",
    "template": {
        "name": "'.$temp.'",
        "language": {
            "code": "en"
        },
        "components": [
            {
                "type": "body",
                "parameters": [
                    {
                        "type": "text",
                        "text": "'.$name.'"
                    }
                ]
            }
        ]
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$pkey
  ),
));

$response = curl_exec($curl);
$output=json_decode($response,true);
curl_close($curl); 


}


    
    
}


if($output['messages'][0]['id']){
mysqli_query($host,"insert into whatsapp_send set type='gatepass',mobile='$mobile',msg='1',date='$date',cid='$user'");    
}else{
 
  $s08=mysqli_query($host,"Select sum(sms) from sms_allotment where subdomain='$subdomain'");
      $d08=mysqli_fetch_array($s08);
      if($d08[0]>0){
      $s07=mysqli_query($host,"Select sum(countmsg) from sms_send where subdomain='$subdomain'");
      $d07=mysqli_fetch_array($s07);
      if($d07[0]){
          $remaining=$d08[0];
      }else{
          $remaining=$d08[0]-$d07[0];
      }
      
      if($remaining>0){
         $s09=mysqli_query($host,"Select smscode,gatepass from branch_category where id='$user'");
      $d09=mysqli_fetch_array($s09);
      
      if($d09['smscode'] and $d09['gatepass']=='yes'){
          $tim=date('h:i A');
           $sms='Dear '.$name.' , Welcome You Have Checked In at '.$tim.' Our Compus  In '.$d09['smscode'].' By EDPLUS AI';
           $sms2=$name." Is Check In Now For ".$subject;
        mysqli_query($host,"insert into notification set msg='Gatepass Check In',mode='$sms2',date='$date',time='$timd',sid='0',sub_domain='$subdomain',type='admin'");

$len=strlen($sms);
$tempid='1707169777386735560'; 
$URL = "http://mobicomm.dove-sms.com/mobicomm//submitsms.jsp?"; 

$data="user=SMARTECH&key=139e57ebc5XX&mobile=$mobile&message=$sms&senderid=EDPLAI&accusage=1&entityid=1701158080734587908&tempid=$tempid";

   $ch = curl_init($URL);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
   curl_close($ch);
     mysqli_query($host,"insert into sms_send set msg='$sms',mobile='$mobile',branch='$user',date='$date',countmsg='1',type='English',status='sent',subdomain='$subdomain',lngh='$len',mode='gatepass'");     
          
      }
      
      }
      
      }
   

}

for($a=0; $a<count($sids)-1; $a++){
$typed=$types[$a];
$sid=$sids[$a];
mysqli_query($host,"insert into gatepass_person set gatepass_id='$cid',sid='$sid',type='$typed',date='$date',time='$time',user='$branch',staffid='$staff',staffname='$staffname'");
}

   
 
} 
}else{

if($delete){
    
if($_POST['types'])    
{
    if($_POST['types']=="RS")
    {

        $arr['status']=true;
        $arr['message']='Successfully Message Send !'; 
    }else
    {
    $passtype='Direct';
    $times=time();
     
    if($_POST['types']=="CI") 
    {
      $asd="insert into gatepass_data set checkin='$times',checkin_status='$passtype',checkin_gateno='$gate1',staffname='$staffname',staffid='$staff', gatepass_id='$delete' ,time='$time',date='$date' ,cid='$user'";  
      $mgg='Successfully CheckIn';
    }else
    {
         $asd="update gatepass_data set checkout='$times',checkout_status='$passtype',checkout_gateno='$gate1',staffname_sec='$staffname',staffid_sec='$staff' where gatepass_id='$delete' and date='$date' and cid='$user'";
        $mgg='Successfully Checkout';
    }
     mysqli_query($host,$asd); 
        $arr['status']=true;
        $arr['message']=$mgg; 
    }
}else
{
mysqli_query($host,"update gatepass set archive='1' where id='$delete' and user='$branch'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}
    
}else{

$arr['status']=true;
$arr['message']='success';  
}


$search=$_POST['search'];
$xsearch="";
if($search) 
{
    $xsearch="gatepass.app_name LIKE '%$search%' and ";
}

$date2=$_POST['date2'];
$date1=$_POST['date1'];
 
 $filterdate="";  
 
    $checkfilter=" and gatepass_data.date='$date'";
if($date1 and $date2)
{
    $qquery="select *,id as idd from gatepass where $xsearch user='$user' and archive IS NULL and gatepass.date>='$date1' and gatepass.date<='$date2' order by id desc";
    $reports="  gatepass.date>='$date1' and gatepass.date<='$date2' and ";
    $checkfilter=" and  gatepass_data.date>='$date1' and gatepass_data.date<='$date2' ";
}else
{
$report=$_POST['report'];
if($report) 
{
     $qquery="select *,gatepass.date as date,gatepass.type as type,gatepass.time as time,gatepass_data.checkin as gtime,gatepass_data.checkout as gtime2,gatepass_data.date as gdate,gatepass.id as idd,gatepass_data.id as gid from gatepass inner join gatepass_data on gatepass.id=gatepass_data.gatepass_id where $xsearch gatepass.user='$user' and gatepass.archive IS NULL and gatepass_data.date='$date' group by gatepass.id order by gatepass_data.id desc";
        $reports="";
}
else
{
     
     $qquery="select *,gatepass.date as date,gatepass.type as type,gatepass.time as time,gatepass_data.checkin as gtime,gatepass_data.checkout as gtime2,gatepass_data.date as gdate,gatepass.id as idd,gatepass_data.id as gid from gatepass inner join gatepass_data on gatepass.id=gatepass_data.gatepass_id where $xsearch gatepass.user='$user' and gatepass.archive IS NULL and  gatepass_data.checkout IS NULL and gatepass_data.date='$date' group by gatepass.id order by gatepass_data.id desc";
   $reports=" gatepass.date='$date' and ";
    
}
}

$arr['version']="2.2"; 
$arr['subject']=array();
$arr['subject2']=array();
$arr2['subject3']=array();

$file2 = 'gatepasslog.txt';
$current = file_get_contents($file2);
$current.="select * from gatepass_subject where user='$user' or user='0' order by name asc";



$s31=mysqli_query($host,"select * from gatepass_subject where user='$user' or user='0' order by name asc");
while($d3=mysqli_fetch_array($s31)){
$row['id']=$d3['id']; 
$row['name']=datacheck($d3['name']);
$row['time']='15';
//$row['student']=$d3['student']=='Student'?'yes':'no';
$row['student']=$d3['student'];
$sub=$d3['name'];
$row['number']="0"; 
$row['otp']="yes"; 
$arr['subject'][]=$row;  
$arr2['subject3']=$d3['name'];
}

$current.="\n".json_encode($arr);

file_put_contents($file2, $current);

$s31=mysqli_query($host,"select gatepass.com_subject,count(gatepass.id) as mycount from gatepass where $reports gatepass.archive IS NULL and gatepass.user='$user' group by com_subject order by mycount desc");
while($d3=mysqli_fetch_array($s31)){
$row3['name']=datacheck($d3['com_subject']);
 $row3['number']=$d3['mycount']; 
$arr['subject2'][]=$row3;    
}
  
$arr['data']=array();
$s=mysqli_query($host,$qquery);
$s7=mysqli_query($host,"select count(*) as checkin_row , sum(case when gatepass_data.checkout IS NULL then 0 else 1 end) as checkout_row from gatepass_data inner join gatepass on gatepass.id=gatepass_data.gatepass_id where gatepass.archive IS NULL and gatepass_data.cid='$user' $checkfilter");
$s77=mysqli_fetch_array($s7);
$arr['checkin']=$s77['checkin_row'];
if($s77['checkout_row']==""){ $arr['checkout']="0"; }else{$arr['checkout']=$s77['checkout_row']; }
$arr['inside']=$s77['checkin_row']-$s77['checkout_row'];
while($d=mysqli_fetch_array($s)){
$id=$d['idd'];   
$row['id']=$d['idd']; 
$row['name']=datacheck($d['app_name']); 
$row['mobile']=datacheck($d['app_mobile']); 
$row['amobile']=datacheck($d['at_mobile']); 
$row['subject']=datacheck($d['com_subject']);
$row['type']=datacheck($d['type']);
$row['alerttime']=datacheck($d['timealert']);
$row['etime']=time();
$row['gid']=$d['gid'];
$row['passcode']=datacheck($d['passcode']);
$row['regular']=$d['regular'];
$row['sms']="yes";
$row['desc']=datacheck($d['com_desc']); 
$row['photo']='https://smartechindia.in/leadpanel/cpanel/uploads/'.$d['upload_pics']; 
$row['date']=date('d M Y', strtotime($d['gdate']))." , ".date('h:i A',$d['gtime']);

if($d['gtime2'])
{
$row['time']=date('d M Y', strtotime($d['gdate']))." , ".date('h:i A',$d['gtime'])." To ".date('h:i A',$d['gtime2']);
}
else
{
$row['time']=date('d M Y', strtotime($d['gdate']))." , ".date('h:i A',$d['gtime']);
}


$row['data']=array();
$s001=mysqli_query($host,"select * from gatepass_person where gatepass_id='$id' and user='$branch'");
while($d001=mysqli_fetch_array($s001)){
$idd=$d001['sid'];
$s10=mysqli_query($host,"select * from students where id='$idd' and cid='$branch'");    
$d10=mysqli_fetch_array($s10);
$row2['name']=datacheck($d10['name']);
$row2['fname']=datacheck($d10['fname']);
$row2['standard']=datacheck($d10['standard']);
$row2['gender']=datacheck($d10['gender']);  
$row['data'][]=$row2;
}

$s001=mysqli_query($host,"select * from gatepass_other where gatepass_id='$id' and user='$branch'");
while($d001=mysqli_fetch_array($s001)){
if($d001['name'])
{
$row2['name']=datacheck($d001['name']);
$row2['fname']="NA";
$row2['standard']="NA";
$row2['gender']="NA"; 
$row['data'][]=$row2;
}
   
}
$arr['data'][]=$row;

}



 
}

}else{
  $arr['status']=false;
    $arr['message']='Plan Expire Please Cordinate Admin';  
}


}else{
    $arr['status']=false;
    $arr['message']='Token Not Match';
}

//$arr['subject'][]=$arr2['subject3'];
echo $json = json_encode($arr);

$current.="\n\n".$json;

file_put_contents($file2, $current);

}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}










?>