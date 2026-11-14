<?php
include('auth.php');
include('attendance_helper.php');

header('Content-Type: application/json');

$month = isset($_POST['month']) ? (int)$_POST['month'] : (int)date('n');
$year  = isset($_POST['year'])  ? (int)$_POST['year']  : (int)date('Y');

echo json_encode(getMonthAttendance($host, $user, $sid, $month, $year));