<?php
require 'config.php';
$cid=$_GET['userId'];
//$query = "SELECT id, username, password,cid,schoolname,address,mobile FROM users where cid='".$cid."'";
$query = "SELECT id, username, password,cid,schoolname,address,mobile FROM users where cid='".$cid."'";
/*
$result = mysqli_query($conn, $query);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}
*/

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result(); // Get result set

// Fetch all rows
$users = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($users);
?>
