<?php
include 'config.php';
$cid=$_GET['userId'];

$query = "SELECT id,name FROM erp_category where cid='".$cid."'";
$query = "SELECT a.id,a.name,a.cid FROM erp_category a,branch_category b where a.cid=b.id and b.sub_domain='".$cid."'";
$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result(); // Get result set

// Fetch all rows
$payment_heads = $result->fetch_all(MYSQLI_ASSOC);


echo json_encode(["status" => "success", "data" => $payment_heads]);
?>
