<?php
function savePushToken($host, $sub_domain, $role, $user_id, $fcm_token, $mobile = '', $platform = ''){
    if(empty($fcm_token)) return;
    
    $sub_domain = mysqli_real_escape_string($host, $sub_domain);
    $role       = mysqli_real_escape_string($host, $role);
    $user_id    = mysqli_real_escape_string($host, $user_id);
    $fcm_token  = mysqli_real_escape_string($host, $fcm_token);
    $mobile     = mysqli_real_escape_string($host, $mobile);
    $platform   = mysqli_real_escape_string($host, $platform);
    
    // mobile + role ke basis pe check karo (branch-independent)
    $check = mysqli_query($host, "SELECT id FROM push_tokens WHERE mobile='$mobile' AND role='$role'");
    
    if(mysqli_num_rows($check) > 0){
        mysqli_query($host, "UPDATE push_tokens SET fcm_token='$fcm_token', platform='$platform', user_id='$user_id', sub_domain='$sub_domain', updated_at=NOW() WHERE mobile='$mobile' AND role='$role'");
    } else {
        $q = "INSERT INTO push_tokens (sub_domain, role, mobile, user_id, fcm_token, platform, updated_at)
            VALUES ('$sub_domain','$role','$mobile','$user_id','$fcm_token','$platform', NOW())";
        $result = mysqli_query($host, $q);
        
        if(!$result){
            file_put_contents(__DIR__ . '/token_debug.txt', 
                date('Y-m-d H:i:s') . " | Query: $q | Error: " . mysqli_error($host) . "\n", 
                FILE_APPEND);
        }
    }
}
?>