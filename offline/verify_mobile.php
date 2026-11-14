<?php header('Content-Type: application/json');
include_once('config.php');
$file="log.txt";
$content="hello in mobile verify23";
file_put_contents($file,$content);


$mobile = $_POST['mobile'];

$query = $conn->prepare("SELECT cid FROM offline_receipt_auth WHERE mobile_number = ?");
$query->bind_param('s', $mobile);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode(['status' => 'success', 'cid' => $user['cid']]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Mobile not found']);
}
?>
