<?php 
include("auth.php");  
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php'; 
use Razorpay\Api\Api;

$key_id = "rzp_live_CRKdqNaGzPfKwJ"; 
$key_secret = "soNcU5Ylk7nKokOXPBj5HdPY"; 
$api = new Api($key_id, $key_secret);

$input = json_decode(file_get_contents('php://input'), true);

$sid = $input['sid'];
$cid = $input['cid'];
$cateid = $input['cateid'];
$amount = floatval($input['amount']); // Decimal handle karega ₹200.50

// Student ki detail nikalna mobile aur name ke liye
$student_query = mysqli_query($host, "SELECT name, smobile, standard FROM students WHERE id = '$sid' LIMIT 1");
$student_data = mysqli_fetch_assoc($student_query);
$mobile_number = $student_data['smobile'] ?? '0000000000';
$name = $student_data['name'] ?? 'Student';
$standard = $student_data['standard'] ?? '';

try {
    // Razorpay Order create karein
    $receipt = 'receipt_' . $cid . '_' . $sid . '_' . $cateid . '_' . time();
    
    $orderData = [
        'receipt' => $receipt,
        'amount' => $amount * 100, // Paise me convert
        'currency' => 'INR',
        'notes' => [
            'sid' => $sid,
            'cid' => $cid,
            'cateid' => $cateid,
            'student_name' => $name,
            'standard' => $standard
        ]
    ];

    $razorpayOrder = $api->order->create($orderData);
    
    // Database me pending log save
    $order_id = $razorpayOrder['id'];
    $sql = "INSERT INTO payment_logs (order_id, sid, cid, cateid, amount, status) 
            VALUES ('$order_id', '$sid', '$cid', '$cateid', '$amount', 'Pending')";
    
    if (mysqli_query($host, $sql)) {
        echo json_encode([
            'status' => 'success',
            'order_id' => $order_id,
            'key' => $key_id,
            'amount' => $amount,
            'name' => $name,
            'mobile' => $mobile_number
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'DB Insert Failed: ' . mysqli_error($host)
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Order Creation Failed: ' . $e->getMessage()
    ]);
}
?>
