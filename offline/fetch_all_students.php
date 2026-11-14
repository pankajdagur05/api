<?php
include 'config.php';


$query = "SELECT id,name,standard as standard_id,fname FROM students ";
$stmt = $conn->prepare($query);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "data" => $students]);
?>
