<?php
include "db.php";

header("Content-Type: application/json");

$data = [];

// Fetch Standards
$standards = [];
$result = $conn->query("SELECT * FROM standards ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $standards[] = $row;
}
$data['standards'] = $standards;

// Fetch Students
$students = [];
$result = $conn->query("SELECT * FROM students ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}
$data['students'] = $students;

// Fetch Fee Dues
$fee_dues = [];
$result = $conn->query("SELECT * FROM fee_dues ORDER BY student_id ASC");
while ($row = $result->fetch_assoc()) {
    $fee_dues[] = $row;
}
$data['fee_dues'] = $fee_dues;

// Fetch Payment Heads
$payment_heads = [];
$result = $conn->query("SELECT * FROM payment_heads ORDER BY name ASC");
while ($row = $result->fetch_assoc()) {
    $payment_heads[] = $row;
}
$data['payment_heads'] = $payment_heads;

echo json_encode(["status" => "success", "data" => $data]);

$conn->close();
?>
