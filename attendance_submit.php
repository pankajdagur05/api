<?php error_reporting(4);
   include("../db.php");
   
function sendVoiceCall($mobile)
{
    $baseUrl = "https://voicecall.par-ken.com/vb-api/v2/broadcasting";

    $params = array(
        "userId" => "2u7l0blgl3",
        "userToken" => "9Sbx8a1BNtaN0dnFu6DVPTM7",
        "apiType" => "broadcasting",
        "announcementId" => "12491",
        "planId" => "7",
        "planType" => "A",
        "inputWaitTime" => "0",
        "cli" => "[]",
        "includesCountryCode" => "N",
        "contacts" => json_encode(array(
            array(
                "mobile" => $mobile
            )
        )),
        "callback_url" => "",
        "extraParameters" => "{}",
        "extraParametersIndex" => "N"
    );

    $url = $baseUrl . "?" . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $response = json_encode(array(
            "status" => "error",
            "message" => curl_error($ch)
        ));
    }

    curl_close($ch);

    return $response;
}

   $json = file_get_contents('php://input');
   $obj = json_decode($json,true);
   $username=$obj['userid'];
   $subdomain=$obj['subdomain'];
   $token=$obj['token'];
   $data=$obj['data'];
   $msg=$obj['msg'];
   $idate=$obj['idate'];
   $num=count($data);
   $type='other';
   $date=date('Y-m-d');
   $time=date('h:i:s');
   if($username){
       $s=mysqli_query($host,"select * from apk_token where token='$token'");
       $r=mysqli_num_rows($s);
       if($r==1){
           
           
   
   $s3=mysqli_query($host,"select branch,name from staff where id='$username'");
         $d3=mysqli_fetch_array($s3);
         $branch=$d3['branch'];
         $user=$branch;
       
         $sm='No';
         $s09=mysqli_query($host,"Select smscode,attendance from branch_category where id='$user'");
         $d09=mysqli_fetch_array($s09);
         $s08=mysqli_query($host,"Select sum(sms) from sms_allotment where subdomain='$subdomain'");
         $d08=mysqli_fetch_array($s08);
         
            
         if($d08[0]>0){
            
         $s07=mysqli_query($host,"Select sum(countmsg) from sms_send where subdomain='$subdomain'");
         $d07=mysqli_fetch_array($s07);
         
        //  if($d07[0]){
        //      $remaining=$d08[0];
        //  }else{
        //      $remaining=$d08[0]-$d07[0];
        //  }
        
        $total_sms = $d08[0] ?? 0;
$used_sms  = $d07[0] ?? 0;

$remaining = $total_sms - $used_sms;
         
         if($remaining>0){
           
         
         if($d09['smscode'] and $d09['attendance']=='yes'){
             $sm='yes';
             $smscode=$d09['smscode'];
         }
         
         }
         
         }
         
$count = 0;         
         
   $title="Attendance Status : ";
   $desc="Attendance Status Is Absent/Leave on ".date('d M Y',strtotime($idate));
   $x=0;
   $iidate=date('d-M-Y',strtotime($idate));
   $token=array();
   $s09=mysqli_query($host,"select * from whatsapp where cid='$user'");    
   $d099=mysqli_fetch_array($s09);
   $insert="insert into attendance (idate,status,staff,sid,time,date,subdomain) VALUES ";
   if($d099['attend_temp']){
       $insert2="insert into whatsapp_send (type,mobile,msg,date,cid,status,textmark) VALUES";
   }else{
   $insert2="insert into sms_send (msg,mobile,branch,date,countmsg,type,status,subdomain,lngh,mode) VALUES";
   }
   
   $count = 0;
   $pp = 0;
   $updatequery = "";
   for($a=0; $a<$num; $a++){
       $pp = 0;
       
    //       if($count >= $remaining){
    //     break;  
    // }
    
    if(!is_array($data[$a]) || !isset($data[$a]['sid']) || !isset($data[$a]['status'])){
    continue;
}
    
   $sid=$data[$a]['sid'];
   $status=$data[$a]['status'];
   $s=mysqli_query($host,"select id from attendance where sid='$sid' and idate='$idate' and subdomain='$branch'");
   if(mysqli_num_rows($s)>0){
      $updatequery.="update attendance set status='$status',date='$date',time='$time',staff='$username' where sid='$sid' and idate='$idate' and subdomain='$branch'; \n";
     
   }else{
   $insert.="('$idate','$status','$username','$sid','$time','$date','$branch'),";   
   $pp=1;
   }
   if(strtolower($status)=='a' or strtolower($status)=='l'){
   $s88=mysqli_query($host,"select name,smobile from students where id='$sid'");
   $d88=mysqli_fetch_array($s88);
   $mobile=$d88['smobile'];    
   $name=$d88['name'];
   
   // ================= VOICE CALL START ================= //
if (strtolower($status) == 'a' && ($user == 163 || $user == 250 || $user == 192 || $user == 66)){
    
    // ✅ ONLY A + specific user

    if(!empty($mobile) && strlen($mobile)==10){

        // ❌ Duplicate check (same day)
        $chk = mysqli_query($host,"SELECT id FROM voice_call_logs 
        WHERE student_id='$sid' AND call_date='$idate'");

        if(mysqli_num_rows($chk)==0){

            $response = sendVoiceCall($mobile);
            $res = json_decode($response, true);

$status_api = $res['data'][0]['status'] ?? 'error';
$unique_id  = $res['data'][0]['uniqueId'] ?? '';

            $response_safe = mysqli_real_escape_string($host, $response);
            $call_time = date('H:i:s'); 
            
            mysqli_query($host,"INSERT INTO voice_call_logs 
        (student_id, mobile, call_date, call_time, status, cid, response, unique_id)
        VALUES 
        ('$sid','$mobile','$idate','$call_time','$status_api', '$user','$response_safe','$unique_id')");
        }
    }
}
// ================= VOICE CALL END ================= //
   
   if($d099['attend_temp']){
       
   $insert2.="('attendance','$mobile','1','$date','$user','pending','$name'),";    
   
       
   }else{
   
   if($sm=='yes' and $smscode and $pp==1 && $remaining > 0){
       
      if($count >= $remaining){
        // SMS nahi bhejna, but loop continue karega
    } else {   

    if(!empty($mobile) && strlen($mobile)==10 && is_numeric($mobile)){

    //     $sms="Dear ".$name.", Your School Attendance Status is Absent On ".$iidate." From ".$smscode." EDPLUS AI";
    //     $encoded_sms = urlencode($sms);

    //     $tempid = '1707169442070660005';
    //     $URL = "https://api.dovesoft.io/api/sendsms";

    //     //$data="key=2b947f500aXX&mobiles=+91$mobile&sms=$encoded_sms&senderid=EDPLAI&entityid=1701158080734587908&tempid=$tempid";
    //   $smsdata="key=2b947f500aXX&mobiles=+91$mobile&sms=$encoded_sms&senderid=EDPLAI&entityid=1701158080734587908&tempid=$tempid";
       
    //     $ch = curl_init($URL);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     curl_setopt($ch, CURLOPT_POST, 1);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $smsdata);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    //     $output = curl_exec($ch);
    //     curl_close($ch);

    //     //$status = (strpos($output, 'success') !== false) ? 'sent' : 'failed';
        
        //$status = 'failed';
        
        
//$sms="Dear ".$name.", Your School Attendance Status is Absent On ".$iidate." From ".$smscode." EDPLUS AI";

$sms="Dear ".$name.", Your School Attendance Status is Absent On ".$iidate." From ".$smscode." EDPLUS AI";
$encoded_sms = urlencode($sms);

$tempid='1777178555819341882'; 
   
$url = "https://sms.par-ken.com/api/smsapi?key=f25f700f41917b39e538a898cb2114a1&route=1&type=1&sender=EDPLSA&number=$mobile&sms=$encoded_sms&templateid=$tempid";

// CURL method 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);  

        $insert2.="('$sms','$mobile','$user','$date','1','English','sent','$subdomain','".strlen($sms)."','Attendance'),";
        
        $count++;
        $remaining--;
        
    }
}
}
   }
   }
       
   } 
   
   
   $insert=rtrim($insert,',');
   $insert2=rtrim($insert2,',');
   //mysqli_query($host,$update);  
   mysqli_query($host,$insert);
   mysqli_query($host,$insert2);
   if($updatequery)
   {
   mysqli_multi_query($host,$updatequery);
   }
   
   
   $arr3['status']=true;
   $arr3['message']="success"; 
   
   }else{
       $arr3['status']=false;
       $arr3['message']='Token Not Match';
       $arr3['data']='';
   }
   
   echo $json = json_encode($arr3);
   
   }else{
       
       ?>
<script>
   window.location="http://edpluss.com";
   
</script>
<?php
   }
    
   ?>