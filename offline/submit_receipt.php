<?php
//include 'config.php';

$data = json_decode(file_get_contents("php://input"), true);
$file = 'log.txt';
// Open the file to get existing content
$current = file_get_contents($file);
// Append a new person to the file
$current .= "John Smith\n";
// Write the contents back to the file
file_put_contents($file, $data);

$student_id = $data['student_id'];
$amount = $data['amount'];
$date = $data['date'];
$payment_mode = $data['payment_mode'];

$query = "INSERT INTO receipts (student_id, amount, date, payment_mode) VALUES (:student_id, :amount, :date, :payment_mode)";
$stmt = $conn->prepare($query);
$stmt->bindParam(':student_id', $student_id);
$stmt->bindParam(':amount', $amount);
$stmt->bindParam(':date', $date);
$stmt->bindParam(':payment_mode', $payment_mode);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Receipt saved"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save receipt"]);
}
?>
