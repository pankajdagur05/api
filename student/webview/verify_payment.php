<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("auth.php"); 
require __DIR__ . '/vendor/autoload.php'; 

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

$key_id = "rzp_live_CRKdqNaGzPfKwJ"; 
$key_secret = "soNcU5Ylk7nKokOXPBj5HdPY"; 
$api = new Api($key_id, $key_secret);

// POST data from Checkout JS handler
$razorpay_order_id = $_POST['razorpay_order_id'] ?? '';
$razorpay_payment_id = $_POST['razorpay_payment_id'] ?? '';
$razorpay_signature = $_POST['razorpay_signature'] ?? '';

if (empty($razorpay_order_id) || empty($razorpay_payment_id) || empty($razorpay_signature)) {
    die("Error: Missing Razorpay data.");
}

try {
    // ✅ MANDATORY: Signature verification
    $attributes = [
        'razorpay_order_id' => $razorpay_order_id,
        'razorpay_payment_id' => $razorpay_payment_id,
        'razorpay_signature' => $razorpay_signature
    ];
    $api->utility->verifyPaymentSignature($attributes);
    
    // ✅ Payment details fetch karein
    $payment = $api->payment->fetch($razorpay_payment_id);
    $order = $api->order->fetch($razorpay_order_id);
    
    $actual_amount_paid = $payment->amount / 100; // Paise se Rupee
    $notes = json_decode(json_encode($order->notes), true); // Order notes se data
    
    $sid = $notes['sid'];
    $cid = $notes['cid'];
    $cateid = $notes['cateid'];
    $bank_tid = $payment->acquirer_data->bank_transaction_id ?? $razorpay_payment_id;
    
    // ✅ Database log check
    $check_q = mysqli_query($host, "SELECT * FROM payment_logs WHERE order_id = '$razorpay_order_id' AND status = 'Pending' LIMIT 1");
    $log = mysqli_fetch_assoc($check_q);
    
    if ($log) {
        $today = date('Y-m-d');
        
        // Student details for userby
        $student_q = mysqli_query($host, "SELECT name, standard FROM students WHERE id = '$sid'");
        $student = mysqli_fetch_assoc($student_q);
        $userby = $ssname . '-' . ($student['standard'] ?? '');
        
        // Invoice serial
        $setting_q = mysqli_query($host, "SELECT serial FROM erp_setting WHERE cid='$cid' LIMIT 1");
        $setting_data = mysqli_fetch_assoc($setting_q);
        $new_invoice = ($setting_data['serial'] ?? 0) + 1;
        
        // ✅ Feesdue entry
        $insert = "INSERT INTO feesdue (voucher, sid, cid, cateid, invoice, tid, credit, date, rdate, mode, session, userby) 
                   VALUES ('receive', '$sid', '$cid', '$cateid', '$new_invoice', '$bank_tid', '$actual_amount_paid', '$today', '$today', 'Online Payment', '$session', '$userby')";
        
        if (mysqli_query($host, $insert)) {
            // Serial update
            mysqli_query($host, "UPDATE erp_setting SET serial = '$new_invoice' WHERE cid='$cid'");
            
            // Log update
            mysqli_query($host, "UPDATE payment_logs SET status='Success', payment_id='$razorpay_payment_id', amount='$actual_amount_paid' WHERE order_id='$razorpay_order_id'");
            
            // Success response
            echo "<div style='text-align:center; margin-top:50px; font-family: Arial;'>
                    <h2 style='color:green;'>✅ Payment Successful!</h2>
                    <p>Amount: <b>₹" . number_format($actual_amount_paid, 2) . "</b></p>
                    <p>Transaction ID: <b>$bank_tid</b></p>
                    <p>Payment ID: <b>$razorpay_payment_id</b></p>
                    <p>Redirecting to Fees...</p>
                  </div>
                  <script>
                    setTimeout(function(){
                        window.location.href = 'fees.php?cid=$cid&sid=$sid';
                    }, 3000);
                  </script>";
            exit();
        } else {
            echo "Database Error: " . mysqli_error($host);
        }
    } else {
        echo "Payment log not found.";
    }
    
} catch (SignatureVerificationError $e) {
    echo "❌ Payment Signature Invalid! Possible fraud attempt.";
} catch (Exception $e) {
    echo "❌ Razorpay Error: " . $e->getMessage();
}
?>
