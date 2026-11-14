<?php
include 'config.php';
$cid=$_GET['userId'];
$query = "SELECT id,name FROM standard where cid='".$cid."' and status='Inactive'";

$query = "SELECT s.id AS id, s.name ,st.name AS stream FROM standard s JOIN stream st ON s.type = st.id where s.cid='".$cid."' and s.status!='Inactive' ORDER BY st.num, s.num;";
$query = "SELECT s.id AS id, s.name ,st.name AS stream, s.cid FROM standard s JOIN stream st ON s.type = st.id  JOIN branch_category bc ON s.cid = bc.id  where bc.sub_domain='".$cid."' and s.status='Inactive' ORDER BY st.num, s.num;";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result(); // Get result set

// Fetch all rows
$standards = $result->fetch_all(MYSQLI_ASSOC);


echo json_encode(["status" => "success", "data" => $standards]);
?>
