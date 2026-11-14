<?php
include("../db.php");
function next_attendance_operation($attend_wise)
{
    $time_array=explode(",",$attend_wise);
    $len_time=sizeof($time_array);
    $temp_chk=$len_time%2;
   if($temp_chk==0)
   {
      return "Sign"; 
       
   }
   else
   {
      return "Signout";  
   }

}
$json = file_get_contents('php://input');
$obj=json_decode($json,true);
$delete=$obj['delete'];
$subdomain=$obj['subdomain'];
$token=$obj['token'];
$username=$obj['userid'];
$type='staff';
$sid=$obj['sid'];
$lat=$obj['lat'];
$ntype='GPS';
$lon=$obj['lon']; 
$date=date('Y-m-d');
$time=date('h:i:s a');
if($subdomain){
    $s=mysqli_query($host,"select * from apk_token where token='$token'");
    $r=mysqli_num_rows($s);
    $wname=strtolower(date('D'));
    $s1=mysqli_query($host,"select branch,attendance,gps,$wname from staff where id='$sid'");
    $d1=mysqli_fetch_array($s1);
    $branch=$d1['branch'];
    $attend=explode(",",$d1[$wname]);
if(strtolower($attend[0])=='yes'){
$fromtime=$attend[1]; 
$totime=$attend[2]; 
}
    $s2=mysqli_query($host,"select * from branch_category where id='$branch'");
    $d2=mysqli_fetch_array($s2);
    
    
    if($r==1){ 
        
        if($d1['gps']=='yes'){
        
    function distance($lat1, $lon1, $lat2, $lon2) {
  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
    return 0;
  }
  else {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

     
      return $miles*1609.34;
    
  }
}


 

if($lat and $lon){
    
     

if($d1[1]=='yes'){
$punch=0;
$ff=mysqli_query($host,"select * from branch_category where sub_domain='$subdomain' and length(lat)>0");
while($rr=mysqli_fetch_array($ff)){

if($rr['lat'] and $rr['lon']){
$dis=distance($rr['lat'],$rr['lon'],$lat,$lon);
if($dis<=$d2['meter']){
  ++$punch;  
    
}
   
   
} 
    
} 

if($punch>0){
    

$ss=mysqli_query($host,"select status,time,id from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$date'"); 
$dd=mysqli_fetch_array($ss);
if(mysqli_num_rows($ss)>0 and strtolower($dd['status'])=='p'){
mysqli_query($host,"update staff_attendance set time=concat(time,',$time'),uploadby=concat(uploadby,',self'),lat=concat(lat,',$lat'),lon=concat(lon,',$lon') where sid='$sid' and subdomain='$branch' and idate='$date'");    
}else{
if(strtolower($dd['status'])=='a'){
  mysqli_query($host,"delete from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$date'");   
}    
mysqli_query($host,"insert into staff_attendance set fromtime='$fromtime',totime='$totime',sid='$sid',status='P',idate='$date',date='$date',time='$time',uploadby='self',lat='$lat',lon='$lon',subdomain='$branch',punch='$ntype'");    
}
$arr['nxt_attend_opertaion']=next_attendance_operation($dd['time']);
$arr['status']=true;
$arr['message']='Attendance Punched'; 


}else{
   $arr['status']=false;
  $arr['message']='Location is not correct. Kindly punch at the desired location';
}
    
}else{
    
  
if($d2['lat'] and $d2['lon'] and $d2['meter']){
    
$dis=distance($d2['lat'],$d2['lon'],$lat,$lon);
if($dis<=$d2['meter']){




$ss=mysqli_query($host,"select * from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$date'"); 
$dd=mysqli_fetch_array($ss);
if(mysqli_num_rows($ss)>0 and strtolower($dd['status'])=='p'){
mysqli_query($host,"update staff_attendance set time=concat(time,',$time'),uploadby=concat(uploadby,',self'),lat=concat(lat,',$lat'),lon=concat(lon,',$lon') where sid='$sid' and subdomain='$branch' and idate='$date'");    
}else{
if(strtolower($dd['status'])=='a'){
  mysqli_query($host,"delete from staff_attendance where sid='$sid' and subdomain='$branch' and idate='$date'");   
}        
mysqli_query($host,"insert into staff_attendance set fromtime='$fromtime',totime='$totime',sid='$sid',status='P',idate='$date',date='$date',time='$time',uploadby='self',lat='$lat',lon='$lon',subdomain='$branch',punch='$ntype'");    
}
$arr['nxt_attend_opertaion']=next_attendance_operation($dd['time']);
$arr['status']=true;
$arr['message']='Attendance Punched'; 

}else{
    
  
$arr['status']=false;
$arr['message']='Location is not correct. Kindly punch at the desired location';
$loc=$lat.','.$lon;
mysqli_query($host,"insert into location set loc='$loc',date='$date',time='$time',user='$sid'");
}
}else{
 $arr['status']=false;
$arr['message']='Branch Cordinates Not Updated';      
}
    
  
}
    
}else{
 $arr['status']=false;
    $arr['message']='Location Cordinate Not Recevied';    
}

        }else{
               $arr['status']=false;
    $arr['message']='Please Scan QR Code/Face Attendance Only Access';
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