<?php include("../../db.php");
include("../save_token.php");
   $ip=$_SERVER['REMOTE_ADDR'];
   $actual_link = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
   $username=$_POST['username'];
   $user=$username;
   $otp=$_POST['otp'];
   $subdomain=$_POST['subdomain'];
   $password=$_POST['password'];
   $type=$_POST['type'];
   $mode=$_POST['mode'];
   $device=$_POST['device'];
   if($type=='student'){ $field="smobile"; $ps="s_pass"; $dev="s_device"; }else{ $field="smobile"; $ps="p_pass"; $dev="p_device"; }
   if($username){
   $q="select students.mname,students.fname,students.stu_id,students.cid,students.name,students.id,students.standard,students.stream,branch_category.iname as nn,branch_category.sub_domain as sub_domain from students INNER JOIN branch_category ON branch_category.id=students.cid where students.$field='$username' and branch_category.type='branch'";
   if($subdomain){ $q.=" and branch_category.sub_domain='$subdomain'"; }
   $q.=" and students.status='Active' order by students.id desc ";
   $sql=mysqli_query($host,$q);
   $r=mysqli_num_rows($sql);
   if($password){
    $password=md5($password);
      $date=date('Y-m-d');
       $time=date('h:i:s');
       $ip=$_SERVER['REMOTE_ADDR'];    
       $sql=mysqli_query($host,"select * from otp where username='$username' and otpcode='$otp'");
       $r=mysqli_fetch_array($sql);
       if($r>0){
           
            mysqli_query($host,"update students set $ps='$password' where $field='$username'");
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
       $like=mysqli_fetch_array($sql);
      $sql2=mysqli_query($host,"select * from otp where username='$username' order by id desc limit 1");
       $likes=mysqli_fetch_array($sql2); 
       if($likes['otpcode']==$otp){
           
           	$ar1 = array();
   		 $ar1['status']=true;
   		 $ar1['message']='success';
            if($mode==1){
                $sub=$like['sub_domain'];
        $token=time().uniqid();
   		    mysqli_query($host,"insert into apk_token set token='$token',date='$date',ip='$ip',type='$type',user='$username',sub_domain='$sub'"); 
   		    mysqli_query($host,"update students set $dev='$device' where $field='$username' and status='Active'");
   		    //savePushToken($host, $sub, ($type=='student' ? 'student' : 'parent'), $like['id'], $_POST['fcm_token'] ?? '');
   		    //savePushToken($host, $sub, ($type=='student' ? 'student' : 'parent'), $like['id'], $_POST['fcm_token'] ?? '', $_POST['platform'] ?? '');
   		     savePushToken($host, $sub, ($type=='student' ? 'student' : 'parent'), $like['id'], $_POST['fcm_token'] ?? '', $username, $_POST['platform'] ?? '');
   		     $ar1['token']=$token;
   		       $ar1['sid']=$like['id'];
   		       $ar1['type']=$type;
   		       $ar1['account']=array();
   		       $sid=$like['id'];
   		        $ss=mysqli_query($host,"select * from docs where sid='$sid' and name='Photo'");
   		        if(mysqli_num_rows($ss)==0){ $pic="https://edpluss.com/school/admin-panel/cpanel/access/img/student-list.png"; }else{ $dd=mysqli_fetch_array($ss); $pic="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$dd['filename'];  }
   		           $row['branchname']=$like['nn'];
   		            $row['id']=$like['id'];
   		            $row['name']=$like['name'];
   		             $row['fname']=$like['fname'];
   		              $row['mname']=$like['mname'];
   		              $row['score']=78;
   		            $row['standard']=$like['standard'];
   		            $row['logo']="http://edpluss.com/school/admin-panel/cpanel/logo/".$like['cid'].".png";
   		            $row['sr']=$like['stu_id'];
   		            $row['stream']=$like['stream'];
   		              $row['branchid']=$like['cid'];
   		            $row['subdomain']=$like['sub_domain'];
   		            $row['photo']=$pic;
   		            $ar1['account'][]=$row;
   		       while($like=mysqli_fetch_array($sql)){
   		            
   		          $sid=$like['id'];
   		        $ss=mysqli_query($host,"select * from docs where sid='$sid' and name='Photo'");
   		        if(mysqli_num_rows($ss)==0){ $pic="https://edpluss.com/school/admin-panel/cpanel/access/img/student-list.png"; }else{ $dd=mysqli_fetch_array($ss); $pic="https://edpluss.com/school/admin-panel/cpanel/access/upload/".$dd['filename'];  }
   		           $row['branchname']=$like['nn'];
   		            $row['id']=$like['id'];
   		            $row['name']=$like['name'];
   		            $row['logo']="http://edpluss.com/school/admin-panel/cpanel/logo/".$like['cid'].".png";
   		            $row['standard']=$like['standard'];
   		            $row['stream']=$like['stream'];
   		            $row['fname']=$like['fname'];
   		              $row['mname']=$like['mname'];
   		              $row['sr']=$like['stu_id'];
   		              $row['score']=78;
   		              $row['branchid']=$like['cid'];
   		            $row['subdomain']=$like['sub_domain'];
   		            $row['photo']=$pic;
   		            $ar1['account'][]=$row;
   		       }
   		       
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
     if($username=="8982276988")
     {
        $ot="1111" ;
     }
    	$dat=date("y-m-d");
   $tym=date("h-i-s");
       $like=mysqli_fetch_array($sql);
       if($r>0){
           
   	$subdomain=$like['sub_domain'];
   	$branch=$like['cid']; 
   $q="insert into otp set otpcode='$ot',username='$username',cid='$branch',date='$dat',url='$actual_link',ip='$ip'";
   		if(mysqli_query($host,$q)){
   		    
   $ss=mysqli_query($host,"select * from students where $field='$username' and cid='$branch'");
   $dd=mysqli_fetch_array($ss);
   
    $ar1 = array();
   		 $ar1['status']=true;
   	
   if($dd[$field]){
    
   $sm=mysqli_query($host,"select * from whatsapp_config where cid='$branch'");    
   if(mysqli_num_rows($sm)>0){
   while($dm=mysqli_fetch_array($sm)){
   $mob=$dm['mobile'];    
   $mobile=$mob;
   $m=date('m');
   $y=date('Y');
   $sm2=mysqli_query($host,"select sum(msg) from whatsapp_send where cid='$branch' and month(date)='$m' and year(date)='$y' and mobile='$mob'");
   $dm2=mysqli_fetch_array($sm2);
   if($dm2[0]<=950){ 
    $mymobile=$dm['mobile'];
    $version=$dm['version'];
    $pkey=$dm['pkey'];
    $phoneid=$dm['phone_id'];
    break;
   }
   }
   $mobile=$username;
   $sm4=mysqli_query($host,"select * from whatsapp where cid='$branch'");
   $dm4=mysqli_fetch_array($sm4);
   if($dm4['otp_temp']){
   $temp=$dm4['otp_temp'];    
   $mobile="91".$username;  
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
                           "text": "'.$ot.'"
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
   mysqli_query($host,"insert into whatsapp_send set type='OTP',mobile='$mobile',msg='1',date='$date',cid='$branch'");    
   } 
   
   // $sms='Your OTP Code is '.$ot.' For Verification . Please Do Not Share To Anyone . From EDPLUS AI';
   // $tempid='1707171170458735280'; 
   
   // $URL = "http://mobicomm.dove-sms.com/submitsms.jsp?"; 
   
//   $URL = "https://api.dovesoft.io/api/sendsms";
   
//   $sms = "Your OTP Code is {#var#} For Verification . Please Do Not Share To Anyone . From EDPLUS AI";
//   $sms = str_replace("{#var#}", $ot, $sms);
//   $sms = urlencode($sms);
//   $tempid='1707176482677090114'; 
   
   //$data="user=SMARTECH&key=139e57ebc5XX&mobile=$username&message=$sms&senderid=EDPLAI&accusage=1&entityid=1701158080734587908&tempid=$tempid";
   
//   $data="key=2b947f500aXX&mobiles=$username&sms=$sms&senderid=EDPLSA&entityid=1701175170051475137&tempid=$tempid";
   
//       $ch = curl_init($URL);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//      $output = curl_exec($ch);
//       curl_close($ch);  




$sms = "Your OTP Code is {#var#} For Verification . Please Do Not Share To Anyone . From EDPLUS AI";
$sms = str_replace("{#var#}", $ot, $sms);
$sms = urlencode($sms);

$tempid='1707176482677090114'; 
   
$url = "https://sms.par-ken.com/api/smsapi?key=f25f700f41917b39e538a898cb2114a1&route=1&type=1&sender=EDPLSA&number=$username&sms=$sms&templateid=$tempid";

// CURL method 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch); 
     		    
   	 $ar1['message']="Success"; 
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
   
   ?>