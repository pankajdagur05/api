<?php
include("../db.php");
$json = file_get_contents('php://input');
$obj=json_decode($json,true);  
$token=$obj['token'];
$qr=$obj['qr'];
$qrarr=explode(",",$qr);
$bid=$qrarr[0];
$qrscan=$qrarr[1];
$sid=$obj['userid'];
$lat=$obj['lat']==""?0:$obj['lat'];
$lon=$obj['lon']==""?0:$obj['lon']; 
$date=date('Y-m-d');
$time=date('h:i:s a');
$tim=time();
if($bid){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    
  
    
    if($r==1){ 
$wname=strtolower(date('D'));
   $s3=mysqli_query($host,"select sub_domain,branch,name,$wname from staff where id='$sid'");
$d3=mysqli_fetch_array($s3);
$branch=$d3['branch'];      
$name=$d3['name'];
$subdomain=$d3['sub_domain'];
$attend=explode(",",$d3[$wname]);
if(strtolower($attend[0])=='yes'){
$fromtime=$attend[1]; 
$totime=$attend[2]; 
}

$s1=mysqli_query($host,"select qr,sub_domain from branch_category where id='$bid'");
$d1=mysqli_fetch_array($s1);
$qrcode=$d1['qr'];
if($qr==$qrcode and $d3['sub_domain']==$d1['sub_domain']){
 
$ss=mysqli_query($host,"select status,time from staff_attendance where sid='$sid' and idate='$date'"); 
$dd=mysqli_fetch_array($ss);
if(mysqli_num_rows($ss)>0 and strtolower($dd['status'])=='p'){

$count=explode(",",$dd['time']);
if(count($count)%2==0){
    $msg="Thanks ".$name." You Have Checked In "; 
    $msg2=$name."  Check In Recently ".$time;
}else{
    $msg="Thanks ".$name." You Have Checked Out ";  
    $msg2=$name."  Check Out Recently ".$time;
}
mysqli_query($host,"insert into notification set msg='Attendance Notification',mode='$msg2',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");
mysqli_query($host,"update staff_attendance set time=concat(time,',$time'),uploadby=concat(uploadby,',self'),punch=concat(punch,',QR'),lat=concat(lat,',$lat'),lon=concat(lon,',$lon') where sid='$sid' and subdomain='$branch' and idate='$date'");    
}else{
    
if(strtolower($dd['status'])=='a'){
  mysqli_query($host,"delete from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$date'");   
  $a='Not';
}       
$msg="Thanks ".$name." You Have Checked In "; 
 $msg2=$name."  Check In Recently ".$time;
mysqli_query($host,"insert into notification set msg='Attendance Notification',mode='$msg2',date='$date',time='$tim',sid='$sid',sub_domain='$subdomain',type='admin'");

mysqli_query($host,"insert into staff_attendance set fromtime='$fromtime',totime='$totime',sid='$sid',status='P',idate='$date',date='$date',time='$time',uploadby='self',lat='$lat',lon='$lon',subdomain='$branch',punch='QR'");    
}
$arr['status']=true;
$arr['message']=$msg; 

}else{
 $arr['status']=false;
    $arr['message']="QR Code Did Not Match";    
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