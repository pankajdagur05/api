<?php
include("../../../db.php");
include("../../../session.php");

$subdomain = $_GET['subdomain'];
$token = $_GET['token'];
$branchid = $_GET['branchid'];
$type = $_GET['type'];
$session = $_GET['session'];
$userid = $_GET['userid'];
$user = $branchid;
$cid = $user;

// Query to fetch overdue expenses
$q = "SELECT *, SUM(amount) AS atype FROM accounts WHERE cid='$user' AND mode='Due' GROUP BY type ORDER BY type ASC";

$s = mysqli_query($host, $q);
$ar1 = array();
$totalAmount = 0; // Initialize variable to store total amount

while($d = mysqli_fetch_array($s)) {
    $aid = $d['id'];
    $a1 = $d['atype'];
    $type = $d['type'];
    
    $s1 = mysqli_query($host, "SELECT SUM(amount) FROM accounts WHERE type='$type' AND dueid>0 AND cid='$user'");
    $d1 = mysqli_fetch_array($s1);
    $over = $a1 - $d1[0];
    
    if($a1 > $d1[0]) {
        $row2 = array();
        $row2['date'] = date('d M Y', strtotime($d['idate']));
        $row2['name'] = $d['type'];
        $row2['descr'] = $d['descr'];
        $row2['amount'] = $over;
        $ar1[] = $row2;
        
        // Add amount to total
        $totalAmount += $over;
    }
}

// Display data in HTML table
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overdue Expenses Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Overdue Expenses Report</h2>
        <table id="reptm" class="table table-striped">
            <thead>
                <tr>
                    <th width="120" id="cent">Date</th>
                    <th width="200">Type</th>
                    <th width="500">Description</th>
                    <th width="100">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ar1 as $row){ ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['descr']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total Overdue Amount:</th>
                    <th><?php echo $totalAmount; ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
