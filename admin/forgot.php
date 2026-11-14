<?php include("../../db.php");
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['username'];
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$ip=$_SERVER['REMOTE_ADDR'];
$user=$username;
$otp=$obj['otp'];
$mode=$obj['mode']; 
if(is_numeric($username)){
 $type='other';   
}else{
    $type='admin';
}
 
if($username){
    if(strtolower($type)=='admin'){
 $q="select * from admin_login where username='$username' ";   
}else{
 $q="select staff.* from staff INNER JOIN staffaccessadmin ON staffaccessadmin.sid=staff.id where staff.mobile='$username' and staff.status='Active'";   
} 
$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
$like=mysqli_fetch_array($sql);
if($password){
 $password=md5($password);
    
    $sql=mysqli_query($host,"select * from otp where username='$username' and otpcode='$otp'");
    
    if($r>0){
        if(strtolower($type)=='admin'){
 
        mysqli_query($host,"update admin_login set password='$password' where username='$username'");
}else{
 
        mysqli_query($host,"update staff set password='$password' where mobile='$username' and status='Active'");  
}
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


    
   
    
}else{

if($otp){
    $mob=$like['mobile'];
    $sql=mysqli_query($host,"select * from otp where username='$mob' order by id desc limit 1");
    $likes=mysqli_fetch_array($sql); 
    if($likes['otpcode']==$otp){
        	$ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']='success';
		 if($mode==1)
		 {
if(strtolower($type)=='admin'){
 
       $s01=mysqli_query($host,"select * from admin_login where username='$username'");
}else{
 
       $s01=mysqli_query($host,"select * from staff where mobile='$username' and status='Active'"); 
}
		       
		       $d01=mysqli_fetch_array($s01);
		       $pass=$d01['password'];
		       $ar1['sid']=$pass;
		 }
		     
		       echo $json = json_encode($ar1);
        
        
    }else{
        	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']='OTP Did not Match';
		     
		       echo $json = json_encode($ar1);
        
    }


    
}
else{
    
  $ot=rand(1000,9999); 
 	$dat=date("y-m-d");
$tym=date("h-i-s");

    if($r>0){
                $ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']="Successfully Send";
		 $mob=$like['mobile'];
	$subdomain=$like['sub_domain'];
	$branch=$like['id'];
$q="insert into otp set otpcode='$ot',username='$mob',cid='$branch',date='$dat',url='$actual_link',ip='$ip'";
		if(mysqli_query($host,$q)){
 
 if($mob){
    
    $sss=mysqli_query($host,"select sum(whatsapp) from sms_allotment where subdomain='$subdomain'"); 
    $ddd=mysqli_fetch_array($sss);
    
    $sss2=mysqli_query($host,"select count(whatsapp_send.id) from whatsapp_send INNER JOIN branch_category ON branch_category.id=whatsapp_send.cid where branch_category.sub_domain='$subdomain'"); 
    $ddd2=mysqli_fetch_array($sss2);
    if($ddd[0]>$ddd2[0]){
    
     
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://graph.facebook.com/v16.0/114613924941917/messages',
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
    "to": "'.$mob.'",
    "type": "template",
    "template": {
        "name": "otp",
        "language": {
            "code": "en"
        },
        "components": [
            {
                "type": "body",
                "parameters": [
                    {
                        "type": "text",
                        "text": "'.$ot.'"
                    }
                ]
            }
        ]
    }
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer '
  ),
));

$response = curl_exec($curl); 
$output=json_decode($response,true);
curl_close($curl);   
 

if($output['messages'][0]['id']){
mysqli_query($host,"insert into whatsapp_send set type='OTP',mobile='$mob',msg='1',date='$date',cid='$branch'");    
}

}
  		    
	 $ar1['message']="Successfully OTP Send"; 
   
// $sms='Your OTP Code is '.$ot.' For Verification . Please Do Not Share To Anyone . From EDPLUS AI';
// $tempid='1707171170458735280'; 
// $URL = "http://mobicomm.dove-sms.com/submitsms.jsp?"; 

// $URL = "https://api.dovesoft.io/api/sendsms";

// $sms = "Your OTP Code is {#var#} For Verification . Please Do Not Share To Anyone . From EDPLUS AI";
// $sms = str_replace("{#var#}", $ot, $sms);
// $sms = urlencode($sms);
// $tempid='1707176482677090114'; 

// //$data="user=SMARTECH&key=139e57ebc5XX&mobile=+91$mob&message=$sms&senderid=EDPLAI&accusage=1&entityid=1701158080734587908&tempid=$tempid";

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

mysqli_query($host,"insert into sms_send set msg='$sms',mobile='$mob',branch='$branch',date='$date',countmsg='1',type='English',status='sent',subdomain='$subdomain',lngh='140',mode='otp'");


 }
 
 
  
		}

		     
		 
		  
	
            
              echo $json = json_encode($ar1); 
        }else{
        	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']="Username Not Exist !";
		     
		       echo $json = json_encode($ar1);
        
    }

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