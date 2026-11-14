<?php
include("../db.php");
header('Content-Type: application/json');

$json = file_get_contents('php://input');
$obj = json_decode($json,true);
$branchid = $obj['branchid']; 
// Note: App ko yahan se login array wali ID bhejni hogi (e.g. 2331)
$new_userid = $obj['userid']; 

if($branchid && $new_userid){

    // 1. Naye staff record se data nikalein
    $sql = mysqli_query($host, "SELECT * FROM staff WHERE id='$new_userid' AND branch='$branchid' AND status='Active'");
    $staff_data = mysqli_fetch_array($sql);

    if($staff_data){
        $subdomain = $staff_data['sub_domain'];
        $mobile = $staff_data['mobile'];

        // 2. Naya Token Generate Karein (Taki dashboard authenticate ho sake)
        function generateNewToken($sub, $user, $h){
            $date = date('Y-m-d');
            $ip = $_SERVER['REMOTE_ADDR'];
            $token = $sub . uniqid();
            mysqli_query($h, "INSERT INTO apk_token SET token='$token', date='$date', ip='$ip', type='staff', user='$user', sub_domain='$sub'");
            return $token;
        }

        $newToken = generateNewToken($subdomain, $mobile, $host);

        // 3. Response jo Dashboard ko redirect/refresh karega
        $ro['status'] = true;
        $ro['message'] = "Switched Successfully";
        $ro['data'] = array(
            "userid" => $new_userid,      // Ab ye 2331 jayega
            "token" => $newToken,         // Nayi branch ka token
            "subdomain" => $subdomain,    // Naya subdomain (mda)
            "branchid" => $branchid       // Nayi branch ID (163)
        );
    } else {
        $ro['status'] = false;
        $ro['message'] = "Staff record not active in this branch";
    }
} else {
    $ro['status'] = false;
    $ro['message'] = "Missing Parameters";
}

echo json_encode($ro);
?>