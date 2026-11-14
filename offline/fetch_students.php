<?php
include 'config.php';
$file = 'log.txt';
if(empty($_GET['standard_id']))
{
  
    $subdomain=$_GET['userId'];

    //$query = "SELECT  a.id,a.name,b.id as standard_id,a.fname as father FROM students a,standard b WHERE a.cid ='".$cid."' and b.name=a.standard limit 500";
       // $query = "SELECT  a.id,a.name,b.id as standard_id,a.fname as father FROM students a,standard b WHERE a.cid ='".$cid."' and b.name=a.standard";
  $query = "SELECT  a.id,a.name,b.id as standard_id,a.fname as father,a.cid FROM students a,standard b,branch_category c WHERE a.cid =c.id and b.name=a.standard and c.sub_domain='".$subdomain."' and a.status='Active'";

     $stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result(); // Get result set

// Fetch all rows
$students = $result->fetch_all(MYSQLI_ASSOC);

   
 


}
else
{
$standard_id = $_GET['standard_id'];



$query = "SELECT id,name,standard,fname FROM students WHERE standard_id = :standard_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':standard_id', $standard_id);
$stmt->execute();
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

echo json_encode(["status" => "success", "data" => $students]);
?>
