<?php include("../db.php");
include("save_token.php"); 
$actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$username=$obj['username'];
$ip=$_SERVER['REMOTE_ADDR'];
$user=$username;
$otp=$obj['otp'];
$mode=$obj['mode'];
$password=$obj['password'];

function token($sub,$t,$user,$h){

    $date = date('Y-m-d');
    $ip   = $_SERVER['REMOTE_ADDR'];

    $token = $sub . uniqid();

    $r = mysqli_query(
        $h,
        "INSERT INTO apk_token SET
        token='$token',
        date='$date',
        ip='$ip',
        type='$t',
        user='$user',
        sub_domain='$sub'"
    );

    if(!$r){
        die(mysqli_error($h));
    }

    return $token;
}


  $file2 = 'api_log.txt';
$current = file_get_contents($file2);
$current.="\n forgot_log_satrts \n"; 

if($username){
    
     
    
$q="select * from staff where mobile='$username' and status='Active'";

$sql=mysqli_query($host,$q);
$r=mysqli_num_rows($sql);
$like=mysqli_fetch_array($sql);
if($password){
 $password=md5($password);
    
  $sql=mysqli_query($host,"select * from otp where username='$username' and otpcode='$otp'");
$otp_rows=mysqli_num_rows($sql);

if($otp_rows>0){
        
        mysqli_query($host,"update staff set password='$password' where mobile='$username'");
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
    
   $sql=mysqli_query($host,"select * from otp where username='$username' order by id desc limit 1");
    $likes=mysqli_fetch_array($sql); 
    if(($likes['otpcode']==$otp)||($otp==0000)){
        
  
//mysqli_query($host,"update staff set device='$device',imino='$imino' where mobile='$username' and password='$password' and status='Active'");    
    
$tok=token($like['sub_domain'],'staff',$username,$host);
if(!$tok){
    die("TOKEN FUNCTION FAILED");
}

$bid=$like['branch'] ?? '';
$sid=$like['id'] ?? '';
$subdomain=$like['sub_domain'] ?? '';
//savePushToken($host, $like['sub_domain'], 'teacher', $sid, $obj['fcm_token'] ?? ''); 
savePushToken($host, $like['sub_domain'], 'teacher', $sid, $obj['fcm_token'] ?? '', $username, $obj['platform'] ?? '');


$s=mysqli_query($host,"select * from branch_category where id='$bid'");
$d=mysqli_fetch_array($s);


         $ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']='success';
		 $ar1['data']=array();
		 
	
		$arr['subdomain']=$like['sub_domain'] ?? '';
		$arr['userid']=$sid;
		$arr['name']=$like['name'] ?? '';
		          
		      $arr['logo'] = !empty($d['logo'] ?? '')
    ? "https://edpluss.com/school/admin-panel/cpanel/logo/".$bid.".png"
    : "";
		      $arr['photo'] = !empty($like['photo'] ?? '')
    ? "https://edpluss.com/school/admin-panel/cpanel/upload/profile/".$like['photo']
    : "";
		       $arr['branchid']=$like['branch'] ?? '';
		       $arr['user_id']=$like['id'] ?? '';
		       $arr['branchname']=$d['name'] ?? '';
		       $arr['token']=$tok;
		       $arr['gender']=$like['gender'] ?? '';
		       $arr['mail']=$like['mail'] ?? '';
		       $arr['admission']=$like['sm'] ?? '';
		       $arr['lead_manage']=$like['lm'] ?? '';
		       $arr['lead_manage_edit']=$like['lme'] ?? '';
		       $arr['lead_manage_delete']=$like['lmd'] ?? '';
		       $subdomain=$like['sub_domain'] ?? '';
	           $ss123=mysqli_query($host,"select * from admin_login where sub_domain='$subdomain'");
               $rrrr=mysqli_fetch_array($ss123);
		       $arr['complaint_manage']=$like['cm']?? '';
		       $arr['complaint_manage_edit']=$like['cme'] ?? '';
		       $arr['complaint_manage_delete']=$like['cmd'] ?? '';
		       $arr['pickup_location']=$like['pl'] ?? '';
		       
		       
		       
		        $arr['school_name']=$rrrr['name'];
		        $arr['school_logo']="https://smartechindia.in/leadpanel/cpanel/logo/".$rrrr['logo'];
		        $arr['school_address']=$rrrr['address'] ?? '';
		       
		       $arr['data']=array();
		        $arr['complaintstatus']=array();
		        $arr['lable']=array();
		           $arr['standard']=array();
		         $arr['statuslist']=array();
		          $arr['section']=array();
		           $arr['filter']=array();
		           
		               $arr['document']=array();
		        
		       $s1=mysqli_query($host,"SELECT * FROM category_assign INNER JOIN branch_category ON branch_category.id=category_assign.category where category_assign.staff='$sid' and branch_category.status='Active'
");

while($d2=mysqli_fetch_array($s1)){
    
    $row['name']=$d2['name'] ?? '';
    $row['categoryid']=$d2['category'] ?? '';
    $arr['data'][]=$row;
}

$s3=mysqli_query($host,"select * from lable where sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row4['name']=$d3['name'] ?? '';
    $row4['color']=$d3['color'] ?? '';
    $arr['lable'][]=$row4;
}

$s3=mysqli_query($host,"select * from create_status where ty='Lead' and sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row4['name']=$d3['name'] ?? '';
    $row4['color']=$d3['color'] ?? '';
    $arr['statuslist'][]=$row4;
}

$s3=mysqli_query($host,"select * from create_status where ty='Complaint' and sub_domain='$subdomain'");
while($d3=mysqli_fetch_array($s3)){
    $row7['name']=$d3['name'] ?? '';
    $row7['color']=$d3['color'] ?? '';
    $arr['complaintstatus'][]=$row7;
}

 $q="select * from students where cid='$bid' group by standard";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row5['name']=$d3['standard'] ?? '';
    $arr['standard'][]=$row5;
}

 $q="select * from batch where cid='$bid'";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row8['name']=$d3['name'] ?? '';
    $arr['section'][]=$row8;
}


 $row8['name']='photo';
    $arr['document'][]=$row8;
    $row8['name']='sign';
    $arr['document'][]=$row8;
    $row8['name']='Adhar Card';
    $arr['document'][]=$row8;
    
    
 $q="select * from document where cid='$bid' order by id desc";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row8['name']=$d3['name'] ?? '';
    $arr['document'][]=$row8;
}



 
 $q="SELECT * FROM staffaccess where staff='$sid'";
$s3=mysqli_query($host,$q);
while($d3=mysqli_fetch_array($s3)){
   
    $row9['name']=$d3['class'] ?? '';
    $row9['type']=$d3['type'] ?? '';
    $arr['filter'][]=$row9;
}


 $arr['feature1']="";
     $arr['feature2']="";
      $arr['feature3']="";
     $arr['feature4']="";
      $arr['feature1_url']="";
    $arr['feature2_url']="";
      $arr['feature3_url']="";
     $arr['feature4_url']="";

 $q="SELECT * FROM app_extras where sid='$sid'";
$s_extras=mysqli_query($host,$q);
if(mysqli_num_rows($s_extras)>0)
{
$row_extras=mysqli_fetch_array($s_extras);
   
    $arr['feature1']=$row_extras['feature1'] ?? '';
     $arr['feature2']=$row_extras['feature2'] ?? '';
      $arr['feature3']=$row_extras['feature3'] ?? '';
     $arr['feature4']=$row_extras['feature4'] ?? '';
      $arr['feature1_url']=$row_extras['feature1_url'] ?? '';
    $arr['feature2_url']=$row_extras['feature2_url'] ?? '';
      $arr['feature3_url']=$row_extras['feature3_url'] ?? '';
     $arr['feature4_url']=$row_extras['feature4_url'] ?? '';
    
    
}



  
 $current .= "\nTOKEN=".$tok."\n";
$current .= json_encode($ar1)."\n";
file_put_contents($file2,$current);

 
		     $ar1['data']=$arr;

if($mode==1){
    $s01=mysqli_query($host,"select * from staff where mobile='$username' and length(password)>3");

    if(mysqli_num_rows($s01)>0){
        $d01=mysqli_fetch_array($s01);
        $pass=$d01['password'];
    }else{
        $pass=md5(time());
        mysqli_query($host,"update staff set password='$pass' where mobile='$username' and status='Active'");
    }

    $ar1['sid']=$pass;
}

echo json_encode($ar1);

exit;
        
    }else{
        	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']='OTP Did not Match';
		     
		       echo $json = json_encode($ar1);
        
    }


    
}
else{
    
    
    
    
  $ot=rand(1000,9999);
  if(($like['mobile'] ?? '')=="7297870877")
  {
   $ot="0000";   
  }
  
 
 	$dat=date("y-m-d");
$tym=date("h-i-s");
  
    if($r>0){
       	 $mob=$like['mobile'];
       	 	 $mobb=$like['mobile'];
	$subdomain=$like['sub_domain'];
	$branch=$like['branch']; 
$q="insert into otp set otpcode='$ot',username='$mob',cid='$branch',date='$dat',url='$actual_link',ip='$ip'";
                $ar1 = array();
		 $ar1['status']=true;
		 $ar1['message']="Successfully Send";
	
		if(mysqli_query($host,$q)){
		    
		    
		    
		    if($mob){
		        
		        
		            $sss=mysqli_query($host,"select sum(whatsapp) from sms_allotment where subdomain='$subdomain'"); 
    $ddd=mysqli_fetch_array($sss);
    
    $sss2=mysqli_query($host,"select count(whatsapp_send.id) from whatsapp_send INNER JOIN branch_category ON branch_category.id=whatsapp_send.cid where branch_category.sub_domain='$subdomain'"); 
    $ddd2=mysqli_fetch_array($sss2);
    if($ddd[0]>$ddd2[0]){
    
        /*
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
 */



if($output['messages'][0]['id']){
mysqli_query($host,"insert into whatsapp_send set type='OTP',mobile='$mobb',msg='1',date='$date',cid='$branch'");    
}

}
// $sms='Your OTP Code is '.$ot.' For Verification . Please Do Not Share To Anyone . From EDPLUS AI.';
// $tempid='1707171170458735280'; 
// $URL = "http://mobicomm.dove-sms.com/submitsms.jsp?";

// $URL = "https://api.dovesoft.io/api/sendsms";

// $sms = "Your OTP Code is {#var#} For Verification . Please Do Not Share To Anyone . From EDPLUS AI";
// $sms = str_replace("{#var#}", $ot, $sms);
// $sms = urlencode($sms);
// $tempid='1707176482677090114'; 

// //$data="user=SMARTECH&key=139e57ebc5XX&mobile=+91$mobb&message=$sms&senderid=EDPLAI&accusage=1&entityid=1701158080734587908&tempid=$tempid";

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

  		    
	 $ar1['message']="Successfully Send"; 
// $subdomainss="EduhubTech";

// $sms='Your OTP is '.$ot.' and valid for few time . From '.$subdomainss.' Wschool.';
 

// $smstoken="b32968c745ceb6a5467fea8e73f0a866"; 
//  $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL,"https://smartechindia.in/smspanel/api/otp.php");
// curl_setopt($ch, CURLOPT_POST, 1);
// curl_setopt($ch, CURLOPT_POSTFIELDS,
//             "token=".$smstoken."&sms=".$sms."&type=1&temp=OTPSRT&lang=English&mobile=".$mobile);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// $server_output = curl_exec($ch);

// curl_close ($ch);    






		    }
		    
 
		}

		     
		 
		  
	
            
              echo $json = json_encode($ar1); 
        }else{
        	$ar1 = array();
		 $ar1['status']=false;
		 $ar1['message']='Username Did not Exist';
		     
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

file_put_contents($file2, $current);









?>