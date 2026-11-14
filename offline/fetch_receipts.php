<?php
include 'config.php';

$query = "SELECT receipts.*, students.name AS student_name FROM receipts 
          INNER JOIN students ON receipts.student_id = students.id
          ORDER BY receipts.date DESC";
$stmt = $conn->prepare($query);
$stmt->execute();
$receipts = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "data" => $receipts]);
?>
