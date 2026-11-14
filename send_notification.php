<?php
//send_notification.php file

include("../db.php");
include("fcm_helper.php");
$json = file_get_contents('php://input');
$obj = json_decode($json, true);
$role       = $obj['role'] ?? '';
$user_id    = $obj['user_id'] ?? '';
$mobile     = $obj['mobile'] ?? '';        // 👈 naya field add kiya
$sub_domain = $obj['sub_domain'] ?? '';
$title      = $obj['title'] ?? '';
$body       = $obj['body'] ?? '';
$data       = $obj['data'] ?? [];

$q = "SELECT fcm_token FROM push_tokens WHERE 1=1";
if($sub_domain) $q .= " AND sub_domain='$sub_domain'";
if($role) $q .= " AND role='$role'";
if($mobile) $q .= " AND mobile='$mobile'";   // 👈 mobile se bhi filter kar sakte ho
if($user_id) $q .= " AND user_id='$user_id'";

$result = mysqli_query($host, $q);
$tokens = [];
while($row = mysqli_fetch_array($result)){
    $tokens[] = $row['fcm_token'];
}
if(count($tokens) > 0){
    $res = sendPushNotification($tokens, $title, $body, $data);
    echo json_encode(['status' => true, 'sent_to' => count($tokens), 'response' => $res]);
}else{
    echo json_encode(['status' => false, 'message' => 'No tokens found', 'query' => $q]);
}
?>