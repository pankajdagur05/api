<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "marudhar_lms";
$conn=mysqli_connect("localhost","marudhar_lms","~Rxkoutds1Kj","marudhar_lms");


if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}
?>
