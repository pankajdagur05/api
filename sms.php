<?php 
$smstoken="b32968c745ceb6a5467fea8e73f0a866";
 $ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"https://smartechindia.in/smspanel/api/sendsms.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,
            "token=".$smstoken."&sms=".$sms."&type=1&temp=DALERT&lang=English&mobile=".$mobile);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$server_output = curl_exec($ch);

curl_close ($ch);

?>