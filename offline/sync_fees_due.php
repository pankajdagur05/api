<?php
include 'config.php';

$cid = $_GET['cid'];
$lastYear = date("Y", strtotime("-1 year"));
$lastUpdate = $_GET['last_update_date']; 
$syncDatetime = str_replace('T', ' ', substr($lastUpdate, 0, 26));

$query = "SELECT a.* FROM feesdue a,branch_category b WHERE a.cid=b.id and  b.sub_domain='".$cid."' and Year(a.rdate)>=$lastYear and a.credit IS NOT NULL";

$stmt = $conn->prepare($query);
$stmt->execute();

$result = $stmt->get_result(); // Get result set

// Fetch all rows
$fees_due = $result->fetch_all(MYSQLI_ASSOC);




echo json_encode(["status" => "success", "data" => $fees_due]);
?>
