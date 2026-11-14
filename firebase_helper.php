<?php

function base64UrlEncode($data)
{
    return str_replace(
        ['+', '/', '='],
        ['-', '_', ''],
        base64_encode($data)
    );
}

function getGoogleAccessToken($jsonFile)
{
    if (!file_exists($jsonFile)) {
        return false;
    }

    $data = json_decode(file_get_contents($jsonFile), true);

    $header = base64UrlEncode(json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT'
    ]));

    $iat = time();
    $exp = $iat + 3600;

    $payload = base64UrlEncode(json_encode([
        'iss'   => $data['client_email'],
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'aud'   => 'https://oauth2.googleapis.com/token',
        'exp'   => $exp,
        'iat'   => $iat
    ]));

    openssl_sign(
        "$header.$payload",
        $signature,
        $data['private_key'],
        OPENSSL_ALGO_SHA256
    );

    $jwt = $header . "." . $payload . "." . base64UrlEncode($signature);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
        'assertion'  => $jwt
    ]));

    $response = curl_exec($ch);

    curl_close($ch);

    $tokenData = json_decode($response, true);

    return $tokenData['access_token'] ?? false;
}


/*
|--------------------------------------------------------------------------
| Single User Notification
|--------------------------------------------------------------------------
*/

function sendAppNotification(
    $cid,
    $user_id,
    $title,
    $message,
    $conn
)
{
    $jsonFile = __DIR__ .
    '/team-up-app-ee9f2-firebase-adminsdk-fbsvc-c081268eda.json';

    $accessToken = getGoogleAccessToken($jsonFile);

    if (!$accessToken) {
        return false;
    }

    $firebaseData = json_decode(
        file_get_contents($jsonFile),
        true
    );

    $projectId = $firebaseData['project_id'];

    $tokens = [];

    $sql = "
    SELECT token
    FROM user_fcm_tokens
    WHERE cid='$cid'
    AND user_id='$user_id'
    AND token!=''
    ";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $tokens[] = $row['token'];
    }

    if (empty($tokens)) {
        return false;
    }

    foreach ($tokens as $token) {

        $payload = [
            "message" => [

                "token" => $token,

                "notification" => [
                    "title" => $title,
                    "body"  => $message
                ],

                "data" => [
                    "click_action" =>
                    "FLUTTER_NOTIFICATION_CLICK"
                ]
            ]
        ];

        $url =
        "https://fcm.googleapis.com/v1/projects/"
        .$projectId.
        "/messages:send";

        $headers = [
            "Authorization: Bearer ".$accessToken,
            "Content-Type: application/json"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode($payload)
        );

        curl_exec($ch);

        curl_close($ch);
    }

    return true;
}


/*
|--------------------------------------------------------------------------
| Role Wise Notification
|--------------------------------------------------------------------------
*/

function sendRoleNotification(
    $cid,
    $role,
    $title,
    $message,
    $conn
)
{
    $jsonFile = __DIR__ .
    '/team-up-app-ee9f2-firebase-adminsdk-fbsvc-c081268eda.json';

    $accessToken = getGoogleAccessToken($jsonFile);

    if (!$accessToken) {
        return false;
    }

    $firebaseData = json_decode(
        file_get_contents($jsonFile),
        true
    );

    $projectId = $firebaseData['project_id'];

    $tokens = [];

    $sql = "
    SELECT token
    FROM user_fcm_tokens
    WHERE cid='$cid'
    AND role='$role'
    AND token!=''
    ";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $tokens[] = $row['token'];
    }

    if (empty($tokens)) {
        return false;
    }

    foreach ($tokens as $token) {

        $payload = [
            "message" => [

                "token" => $token,

                "notification" => [
                    "title" => $title,
                    "body"  => $message
                ],

                "data" => [
                    "click_action" =>
                    "FLUTTER_NOTIFICATION_CLICK"
                ]
            ]
        ];

        $url =
        "https://fcm.googleapis.com/v1/projects/"
        .$projectId.
        "/messages:send";

        $headers = [
            "Authorization: Bearer ".$accessToken,
            "Content-Type: application/json"
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            json_encode($payload)
        );

        curl_exec($ch);

        curl_close($ch);
    }

    return true;
}