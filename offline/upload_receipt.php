<?php
include 'config.php';

$file = 'log.txt';
// Open the file to get existing content

$sql_lst="hello";

file_put_contents($file,$sql_lst);





// Get raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (
    empty($data['receipt_id']) ||
    empty($data['cateid']) ||
    empty($data['sid']) ||
    empty($data['amount']) ||
    empty($data['payment_mode']) ||
    empty($data['payment_head']) ||
    empty($data['student_name']) ||
    empty($data['father']) ||
    empty($data['standard_name']) ||
    empty($data['date'])
) {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    exit();
}

// Sanitize and assign input values
$receipt_id = intval($data['receipt_id']);
$cateid = intval($data['cateid']);
$sid = intval($data['sid']);
$cid=0;
$session="";
///
$sql_student_info="select * from students where id='".$sid."' order by id desc limit 1";
file_put_contents($file,$sql_student_info);

$result = mysqli_query($conn, $sql_student_info);

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $cid=$row['cid'];
    $session=$row['session'];
}





$amount = floatval($data['amount']);
$payment_mode = $data['payment_mode'];
$payment_head = $data['payment_head'];
$student_name = $data['student_name'];
$father = $data['father'];
$standard_name = $data['standard_name'];
$date =date('Y-m-d', strtotime($data['date']));
$bank_name = !empty($data['bank_name']) ? $data['bank_name'] : null;
$transaction_id = !empty($data['transaction_id']) ? $data['transaction_id'] : null;
$today=date('y-m-d');

$sql_lst="INSERT INTO `feesdue` (`sid`, `cateid`, `voucher`, `debit`, `credit`, `invoice`, `rdate`, `memo`, `mode`, `type`, `remark`, `bdetails`, `bankno`, `cid`, `date`, `session`, `userby`, `cancel`, `dele`, `tid`) 
VALUES ($sid, $cateid, 'receive', NULL,$amount, NULL,'".$date."', NULL,'".$payment_mode."', NULL, NULL,'".$bank_name."', NULL, '".$cid."', '".$today."', '".$session."', NULL, NULL, NULL,'".$transaction_id."')";


//file_put_contents($file,$sql_lst);
// Insert/Update Receipt into Database
//$stmt = mysqli_prepare($con,$sql_lst);

if ($conn->query($sql_lst) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Receipt uploaded successfully", "receipt_id" => $receipt_id]);

} else {
    echo json_encode(["status" => "error", "message" => "Error uploading receipt: " . $conn->error]);
}




$conn->close();
?>



