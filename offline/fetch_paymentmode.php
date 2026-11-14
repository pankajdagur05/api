<?php
include 'config.php';

$query = "SELECT modename.value AS mode_name,  
                 CONCAT(bank.bank, ' ', bank.name) AS bank_name, 
                 bank.account AS account_no ,bank.cid
          FROM bank
          JOIN branch_category c ON bank.cid = c.id
          CROSS JOIN (
              SELECT 'UPI' AS value 
              UNION ALL 
              SELECT 'BANK TRANSFER' 
              UNION ALL 
              SELECT 'Cheque' 
          ) AS modename
          WHERE c.sub_domain = ?";  // Use '?' placeholder

$stmt = $conn->prepare($query);
$cid = $_GET['userId'];  // Dynamic value
$stmt->bind_param("s", $cid); // 'i' denotes an integer

$stmt->execute();
$result = $stmt->get_result();
$modes = $result->fetch_all(MYSQLI_ASSOC); // Correct fetch method for MySQLi

// Return JSON response
echo json_encode(["status" => "success", "data" => $modes]);
?>