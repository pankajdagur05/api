<?php
include 'config.php';
$file = 'log.txt';
if(empty($_GET['standard_id']))
{
    $subdomain=$_GET['userId'];



    $query = "SELECT id,name ,id as cid from branch_category where sub_domain='".$subdomain."' and type='branch' and typeid='school'";
    $stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result(); // Get result set

// Fetch all rows
$students = $result->fetch_all(MYSQLI_ASSOC);
   /// $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }


echo json_encode(["status" => "success", "data" => $students]);
?>
