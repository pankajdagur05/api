<?php

header('Content-Type: application/json');

include("../db.php");

$cid     = mysqli_real_escape_string($host,$_POST['cid'] ?? '');
$user_id = mysqli_real_escape_string($host,$_POST['user_id'] ?? '');
$role    = mysqli_real_escape_string($host,$_POST['role'] ?? '');
$device  = mysqli_real_escape_string($host,$_POST['device'] ?? '');
$token   = mysqli_real_escape_string($host,$_POST['token'] ?? '');

if(empty($cid) || empty($user_id) || empty($role) || empty($token))
{
    echo json_encode([
        "status" => false,
        "message" => "Missing Parameters"
    ]);
    exit;
}

$q = mysqli_query($host,"
SELECT id
FROM user_fcm_tokens
WHERE cid='$cid'
AND user_id='$user_id'
AND device='$device'
");

if(mysqli_num_rows($q)>0)
{
    mysqli_query($host,"
    UPDATE user_fcm_tokens
    SET token='$token',
        role='$role'
    WHERE cid='$cid'
    AND user_id='$user_id'
    AND device='$device'
    ");

    echo json_encode([
        "status" => true,
        "message" => "Token Updated"
    ]);
}
else
{
    mysqli_query($host,"
    INSERT INTO user_fcm_tokens
    (cid,user_id,role,device,token)
    VALUES
    ('$cid','$user_id','$role','$device','$token')
    ");

    echo json_encode([
        "status" => true,
        "message" => "Token Saved"
    ]);
}