<?php
// fcm_helper.php file 

function getFCMAccessToken() {
    $jsonKeyFile = '/home/marudhar/secure_fcm/firebase-key.json'; // apna path daalo
    $keyData = json_decode(file_get_contents($jsonKeyFile), true);
    
    $now = time();
    $header = ['alg' => 'RS256', 'typ' => 'JWT'];
    $claim = [
        'iss'   => $keyData['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'exp'   => $now + 3600,
        'iat'   => $now
    ];
    
    $base64UrlHeader = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '=');
    $base64UrlClaim  = rtrim(strtr(base64_encode(json_encode($claim)), '+/', '-_'), '=');
    
    $signatureInput = $base64UrlHeader . "." . $base64UrlClaim;
    
    openssl_sign($signatureInput, $signature, $keyData['private_key'], 'SHA256');
    $base64UrlSignature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');
    
    $jwt = $signatureInput . "." . $base64UrlSignature;
    
    // Ab is JWT ko Google ke paas bhejo access token ke liye
    $ch = curl_init("https://oauth2.googleapis.com/token");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion'  => $jwt
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $result = json_decode($response, true);
    return $result['access_token'] ?? null;
}

function sendPushNotification($tokens, $title, $body, $data = []) {
    $projectId = "edplus-lite-app";
    $accessToken = getFCMAccessToken();
    
    if(!$accessToken){
        return ['error' => 'Access token generate nahi hua'];
    }
    
    $results = [];
    foreach ($tokens as $fcmToken) {
        $message = [
            "token" => $fcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body
            ]
        ];
        
        // sirf tabhi data field add karo jab kuch ho
        if(!empty($data)){
            // saari values string honi chahiye FCM ke liye
            $message["data"] = array_map('strval', $data);
        }
        
        $payload = ["message" => $message];
        
        $ch = curl_init("https://fcm.googleapis.com/v1/projects/$projectId/messages:send");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer $accessToken",
            "Content-Type: application/json"
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        $response = curl_exec($ch);
        curl_close($ch);
        $results[] = $response;
    }
    
    return $results;
}
?>