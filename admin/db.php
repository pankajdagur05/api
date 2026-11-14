<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "marudhar_lms";
$host=mysqli_connect("localhost","marudhar_lms","~Rxkoutds1Kj","marudhar_lms");


if ($host->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}
?>
