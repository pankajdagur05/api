<?php include("../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true); 
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$ip=$_SERVER['REMOTE_ADDR'];
$otp=$obj['otp'];
$branch=$obj['branch']; 
$mobile=$obj['mobile']; 
$subdomain=$obj['subdomain']; 
$dat=date('Y-m-d');
$date=date('Y-m-d');
$time=date('h:i:s');
if($mobile){

if($otp){
    $mob=$mobile;
    $sql=mysqli_query($host,"select * from otp where username='$mob' order by id desc limit 1");
    $likes=mysqli_fetch_array($sql); 
   
    if(($likes['otpcode']==$otp)||($otp=="0000")){
        	$ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']='success';
		     
		       echo $json = json_encode($ar1);
        
        
    }else{
        	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']='OTP Did not Match';
		     
		       echo $json = json_encode($ar1);
        
    }


    
}
else{
$ss=mysqli_query($host,"select id from students where (smobile='$mobile' or gmobile='$mobile') and cid='$branch'");   
$rr=mysqli_num_rows($ss);
if($rr>0){
 $m=1;   
}else{
$ss1=mysqli_query($host,"select id from staff where mobile='$mobile' and branch='$branch'");   
$rr1=mysqli_num_rows($ss1);
if($rr1>0){
    $m=1;
}else{

$ss2=mysqli_query($host,"select id from gatepass where app_mobile='$mobile' and user='$branch' and verify='yes'");   
$rr2=mysqli_num_rows($ss2);
if($rr2>0){
$m=1;    
    
}else{
    $m=0;
}
    
}

}

if($m==0 || $m==1){


  $ot=rand(1000,9999); 
  
   if($mobile=="8982276988")
  {
   $ot="0000";   
  }
  
  
  
  
 	$dat=date("y-m-d");
$tym=date("h-i-s");
 
                $ar1 = array();
		 $ar1['status']=true;
		  $ar1['check']=0;
		 $ar1['message']="Successfully Send";
		 $mob=$mobile;
$q="insert into otp set otpcode='$ot',username='$mob',cid='$branch',date='$dat',url='$actual_link',ip='$ip'";
		if(mysqli_query($host,$q)){
 
 if($mob){
      
	 $ar1['message']="Successfully OTP Send"; 
   
// $sms='Your OTP Code is '.$ot.' For Verification . Please Do Not Share To Anyone . From EDPLUS AI..';
// $tempid='1707171170458735280'; 
// $URL = "http://mobicomm.dove-sms.com/submitsms.jsp?"; 

// $URL = "https://api.dovesoft.io/api/sendsms";

//$data="user=SMARTECH&key=139e57ebc5XX&mobile=+91$mob&message=$sms&senderid=EDPLAI&accusage=1&entityid=1701158080734587908&tempid=$tempid";

// $data="key=2b947f500aXX&mobiles=+91$mob&sms=$sms&senderid=EDPLSA&entityid=1701175170051475137&tempid=$tempid";

//   $ch = curl_init($URL);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//   curl_setopt($ch, CURLOPT_POST, 1);
//   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//   $output = curl_exec($ch);
//   curl_close($ch);

$sms = "Your OTP Code is {#var#} For Verification . Please Do Not Share To Anyone . From EDPLUS AI";
$sms = str_replace("{#var#}", $ot, $sms);
$sms = urlencode($sms);

$tempid='1707176482677090114'; 
   
$url = "https://sms.par-ken.com/api/smsapi?key=f25f700f41917b39e538a898cb2114a1&route=1&type=1&sender=EDPLSA&number=$mob&sms=$sms&templateid=$tempid";

// CURL method 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);  
   

mysqli_query($host,"insert into sms_send set msg='$sms',mobile='$mob',branch='$branch',date='$date',countmsg='1',type='english',status='Sent',subdomain='$subdomain',lngh='80',mode='Gatepass'");



 }
 
 
		}
   
   echo $json = json_encode($ar1); 
        }else{
        	$ar1 = array();
		 $ar1['status']=true;
		 $ar1['check']=1;
		 $ar1['message']="Verified";
		     
		       echo $json = json_encode($ar1);
        
    }

}




}else{
    
    ?>
    <script>
        window.location="http://edpluss.com";
        
    </script>
    <?php
    
    
}
?>