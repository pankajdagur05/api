<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$tim=time();
function send_notification($title,$desc,$token,$key,$m)
{

#prep the bundle
     $msg = array
          (
        'body'  => $desc,
        'title' => $title,
        'click_action' => $m,
                
          );
          
         
    $fields = array
            (
                'registration_ids'  => $token,
                'notification'  => $msg
            );
    
    
    $headers = array
            (
                'Authorization: key=' . $key,
                'Content-Type: application/json'
            );
            
          
             
#Send Reponse To FireBase Server    
        $ch = curl_init();
        curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
        curl_setopt( $ch,CURLOPT_POST, true );
        curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
        curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
           $result = curl_exec($ch );
        curl_close( $ch );
        
           
        
       
}
$delete=$_POST['delete'];
$subdomain=$_POST['subdomain'];
$username=$_POST['userid'];
$token=$_POST['token'];
$level=$_POST['level'];
$mode=$_POST['mode'];
$stime=$_POST['stime'];
$sdate=$_POST['sdate'];
$department=$_POST['department'];
if($sdate){
$dt=$sdate." ".$stime;    
$sch=strtotime($dt);    
}
$myname=array();
$mymob=array();
if($level=="1")
{
  $level="regural";  
}else
{
  $level="urgent";   
}

if($mode=="1")
{
  $mode="Query";  
}else
{
  
if($mode=="2")
{
  $mode="Suggestion";  
}else
{
  $mode="Complaint";   
}  
}
$type='staff';
$date=date('Y-m-d');
$time=date('h:i:s');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    if($r==1){

$s3=mysqli_query($host,"select * from staff where id='$username'");
      $d3=mysqli_fetch_array($s3);
      $branch=$d3['branch'];
      $staff=$d3['id'];
      $staffname=$d3['name'];
      $user=$branch;
      
      
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
  $s01=mysqli_query($host,"select * from complaint where id='$tid' and user='$branch'");
  $d01=mysqli_fetch_array($s01);
  $fname=$d01['upload_pics'];
}else{
    $fname="";
}    

}



if($subject){
if($tid){
if($stype=='other'){
     $name=$_POST['name'];
     $mobile=$_POST['mobile'];
      $amobile=$_POST['amobile'];
    $q="update complaint set app_name='$name',app_mobile='$mobile',mode='$mode',level='$level',at_mobile='$amobile',com_subject='$subject',com_desc='$desc',upload_pics='$fname',type='$stype',staffid='$staff',staffname='$staffname',date='$date',time='$time' where id='$tid' and user='$branch' ";    
mysqli_query($host,$q);
$arr['status']=true;
$arr['message']='success Updated'; 
}else{
  $q="update complaint set com_subject='$subject',com_desc='$desc',mode='$mode',department='$department',level='$level',upload_pics='$fname',type='$stype',staffid='$staff',staffname='$staffname',date='$date',time='$time' where id='$tid' and user='$branch' ";    
mysqli_query($host,$q);   
$arr['status']=true;
$arr['message']='success Updated'; 
}
 
}else{  
if($stype=='other'){
    $name=$_POST['name'];
     $mobile=$_POST['mobile'];
      $amobile=$_POST['amobile'];
}
    
$s09=mysqli_query($host,"select id from complaint where user='$user'");
$comid=mysqli_num_rows($s09)+1;

$q="insert into complaint set app_name='$name',department='$department',mode='$mode',level='$level',cno='$comid',app_mobile='$mobile',at_mobile='$amobile',com_subject='$subject',com_desc='$desc',upload_pics='$fname',type='$stype',user='$branch',staffid='$staff',staffname='$staffname',date='$date',time='$time' ";    
mysqli_query($host,$q); 
$s00=mysqli_query($host,"select * from complaint where user='$branch' and staffid='$staff' order by id desc limit 1");
$d00=mysqli_fetch_array($s00);
$sids=explode(',',$_POST['sid']);
$types=explode(',',$_POST['types']);

 $token=array();
         $s23=mysqli_query($host,"select staff.admin_device as admin_device,staff.id as id from staff INNER JOIN setting ON setting.appeal_respo=staff.id where length(staff.admin_device)>0 and setting.cid='$user'");
if(mysqli_num_rows($s23)>0){
    
$d23=mysqli_fetch_array($s23);
  $token[0]=$d23['admin_device'];
  $sid=$d23['id'];

$s=mysqli_query($host,"select * from global where name='Admin Key'");
$d=mysqli_fetch_array($s);
$tkey=$d['val'];
$title="Appeal Registered  by ".$staffname;
$descr="Please Give The Solution Appeal No. ".$comid;
    send_notification($title,$descr,$token,$tkey,"complaint");
    mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='staff'");
       mysqli_query($host,"insert into notification set msg='$title',mode='$descr',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");
}




$arr['status']=true;



$arr['message']='success inserted';
$cid=$d00['id'];

for($a=0; $a<count($sids)-1; $a++){
$typed=$types[$a];
$sid=$sids[$a];
mysqli_query($host,"insert into perm_comp set complain_id='$cid',sid='$sid',type='$typed',date='$date',time='$time',user='$branch',staffid='$staff',staffname='$staffname'");

 if($mobile=="")
      {
       if($typed=='staff'){
           $ss1=mysqli_query($host,"select * from staff where branch='$user' and id='$sid'");
           $dd1=mysqli_fetch_array($ss1);
           array_push($myname,$dd1['name']);
           array_push($mymob,$dd1['mobile']);
       }else{
            $ss1=mysqli_query($host,"select * from students where cid='$user' and id='$sid'");
           $dd1=mysqli_fetch_array($ss1);
           if($typed=='student'){ $nm=$dd1['name'];  }else{  $nm=$dd1['fname'];  }
           array_push($myname,$nm);
           array_push($mymob,$dd1['smobile']);
       }   
      }else{
           array_push($myname,$name);
           array_push($mymob,$mobile);
      }



}



if($mode=='Complaint' or $mode=='complaint'){

$sm=mysqli_query($host,"select * from whatsapp_config where cid='$user'");    
if(mysqli_num_rows($sm)>0){
while($dm=mysqli_fetch_array($sm)){
$mob=$dm['mobile'];     
$m=date('m');
$y=date('Y');
$sm2=mysqli_query($host,"select sum(msg) from whatsapp_send where cid='$user' and month(date)='$m' and year(date)='$y' and mobile='$mob'");
$dm2=mysqli_fetch_array($sm2);
if($dm2[0]<=950){ 
 $mymobile=$dm['mobile'];
 $version=$dm['version'];
 $pkey=$dm['pkey'];
 $phoneid=$dm['phone_id'];
 break;
}
}
$sm4=mysqli_query($host,"select * from whatsapp where cid='$user'");
$dm4=mysqli_fetch_array($sm4);
if($dm4['com_temp']){
$temp=$dm4['com_temp'];    
 
$idate=date('d l Y',strtotime($rdate));

for($b=0; $b<count($mymob); $b++){
$mobile='91'.$mymob[$b];
$name=$myname[$b];
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
                        "text": "'.$comid.'"
                    },
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

curl_close($curl); 
mysqli_query($host,"insert into whatsapp_send set type='complaint',mobile='$mobile',msg='1',date='$date',cid='$user'");
}
}
}
  
}
   
 
} 
}else{

if($delete){
mysqli_query($host,"update complaint set archive='1' where id='$delete' and user='$branch'"); 
$arr['status']=true;
$arr['message']='successfully deleted';
}else{



$resp=$_POST['resp']; 
if($resp){
$sids=explode(',',$_POST['sid']);
$types=explode(',',$_POST['types']);
$arr['status']=true;

$arr['message']='success inserted';
$cid=$d00['id'];

for($a=0; $a<count($sids)-1; $a++){
$typed=$types[$a];
$sid=$sids[$a];
$kkl=mysqli_query($host,"select * from complaint_resposible where sid='$sid' and complaint_id='$resp' and type='$typed'");
if(mysqli_num_rows($kkl)==0)
{
$ins = "insert into complaint_resposible set sid='$sid',complaint_id='$resp',type='$typed',date='$date',cid='$user' ";
}
mysqli_query($host,$ins);
}



}    
    
$arr['status']=true;
$arr['message']='success';  
}
 
$arr['data']=array();
$arr['department']=array();
$rom['name']='Acedemics';
$arr['department'][]=$rom;
$rom['name']='Finance';
$arr['department'][]=$rom;
$rom['name']='Examination';
$arr['department'][]=$rom;
$rom['name']='Transport';
$arr['department'][]=$rom;
$rom['name']='Other';
$arr['department'][]=$rom;
$s09=mysqli_query($host,"select * from department where cid='$user'");
while($d09=mysqli_fetch_array($s09)){
$rom['name']=$d09['name'];
$arr['department'][]=$rom;
}
$s=mysqli_query($host,"select * from complaint where user='$user' and archive IS NULL and staffid='$staff' order by id desc");
while($d=mysqli_fetch_array($s)){
$id=$d['id'];   
$row['id']=$d['id']; 
$row['cno']=$d['cno']; 
$row['name']=$d['app_name']; 
$row['mobile']=$d['app_mobile']; 
$row['amobile']=$d['at_mobile']; 
$row['subject']=$d['com_subject'];
$row['department']=$d['department'];
$row['mode']=$d['mode'];
$row['level']=$d['level'];
$row['stime']=date('h:i:s A',$d['schedule']);
$row['sdate']=date('d-m-Y',$d['schedule']);
$row['statuss']="no";
$row['desc']=$d['com_desc']; 
$row['photo']='https://edpluss.com/school/admin-panel/cpanel/uploads/'.$d['upload_pics']; 
$row['date']=date('d-m-Y',strtotime($d['date'])); 
$row['time']=$d['time'];
$row['data']=array();
$s001=mysqli_query($host,"select * from perm_comp where complain_id='$id' and user='$branch'");
while($d001=mysqli_fetch_array($s001)){
$idd=$d001['sid'];
if($d001['type']=='staff'){
$s10=mysqli_query($host,"select * from staff where id='$idd' and branch='$branch'");    
$d10=mysqli_fetch_array($s10);
$row2['name']=$d10['name'];
$row2['fname']=$d10['gname'];
$row2['standard']="";
$row2['type']='staff';
}else{
$s10=mysqli_query($host,"select * from students where id='$idd' and cid='$branch'");    
$d10=mysqli_fetch_array($s10);
$row2['name']=$d10['name'];
$row2['fname']=$d10['fname'];
$row2['standard']=$d10['standard'];
$row2['type']=$d001['type'];  
}  
$row['data'][]=$row2;
}


$row['status']=array();
$s2=mysqli_query($host,"select * from complaint_status2 where comp_id='$id' and user='$user' and archive IS NULL order by id desc");
while($d2=mysqli_fetch_array($s2)){
    $row2['remark']=$d2['sts_remark'];
     $row2['status']=$d2['upd_sts'];
      $row2['date']= date("d M Y", strtotime($d2['date']));
       $row2['time']=date('h:i:s',strtotime($d2['time']));
       $row['status'][]=$row2;
}

$row['responsible']=array();
$s2=mysqli_query($host,"select * from complaint_resposible where complaint_id='$id' and cid='$user'  order by id desc");
while($d2=mysqli_fetch_array($s2)){
 $idd=$d2['sid'];
if($d2['type']=='staff'){
$s10=mysqli_query($host,"select * from staff where id='$idd' and branch='$branch'");    
$d10=mysqli_fetch_array($s10);
$row2['name']=$d10['name'];
$row2['fname']=$d10['gname'];
$row2['standard']="";
$row2['type']='staff';
}else{
$s10=mysqli_query($host,"select * from students where id='$idd' and cid='$branch'");    
$d10=mysqli_fetch_array($s10);
$row2['name']=$d10['name'];
$row2['fname']=$d10['fname'];
$row2['standard']=$d10['standard'];
$row2['type']=$d2['type'];    
}  
       $row['responsible'][]=$row2;
}

$arr['data'][]=$row;

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