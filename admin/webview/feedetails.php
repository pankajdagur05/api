<?php
include("../../../db.php");
include("../../../session.php");

// Get parameters from URL
$subdomain = $_GET['subdomain'] ?? '';
$token = $_GET['token'] ?? '';
$branchid = $_GET['branchid'] ?? '';
$type = $_GET['type'] ?? '';
$session = $_GET['session'] ?? '';
$userid = $_GET['userid'] ?? '';

// Set connection parameters
$user = $branchid;
$cid = $user;
$subdomain = mysqli_real_escape_string($host, $subdomain);

// Verify token
if(!verifyToken($subdomain, $token)) {
    die("Invalid access");
}

// Get data grouped by class
$q = "SELECT students.standard, 
            students.id, students.name, students.fname, 
            SUM(feesdue.debit) AS total_due,
            SUM(feesdue.credit) AS total_received
        FROM students
        LEFT JOIN feesdue ON students.id = feesdue.sid 
        WHERE students.cid='$cid'
        AND students.session='$session'
        AND feesdue.session='$session'
        GROUP BY students.standard, students.id
        ORDER BY students.standard ASC, students.name ASC";

$s = mysqli_query($host, $q);

// Organize data by class
$classes = [];
while($row = mysqli_fetch_assoc($s)) {
    $class = $row['standard'];
    if(!isset($classes[$class])) {
        $classes[$class] = [
            'students' => [],
            'total_due' => 0,
            'total_received' => 0,
            'total_balance' => 0
        ];
    }
    $balance = $row['total_due'] - $row['total_received'];
    
    $classes[$class]['students'][] = $row;
    $classes[$class]['total_due'] += $row['total_due'];
    $classes[$class]['total_received'] += $row['total_received'];
    $classes[$class]['total_balance'] += $balance;
}

// Calculate grand totals
$grand_total_due = 0;
$grand_total_received = 0;
$grand_total_balance = 0;
?>

<div class="c_back" id="cbb" style="display:block;">
    <div class="c_pop" id="repfeesreg" style="height:100%; width:100%; display:block;">
        <div class="c_pophead">
            <div class="c_pophead1">
                <div class="c_pophead10">Fees Details Report</div>
                <div class="c_pophead11">Financial year <?= htmlspecialchars($session) ?></div>
            </div>
        </div>

        <table id="reptff">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>S No.</th>
                    <th>Name</th>
                    <th>Father's Name</th>
                    <th>Total Due</th>
                    <th>Total Received</th>
                    <th>Balance</th>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach($classes as $class => $data): 
                    $grand_total_due += $data['total_due'];
                    $grand_total_received += $data['total_received'];
                    $grand_total_balance += $data['total_balance'];
                ?>
                <tr class="class-header">
                    <td colspan="7" style="background:#f5f5f5; font-weight:bold">
                        <?= htmlspecialchars($class) ?> 
                        (Total Due: <?= $data['total_due'] ?> | 
                        Received: <?= $data['total_received'] ?> | 
                        Balance: <?= $data['total_balance'] ?>)
                    </td>
                </tr>
                
                <?php $counter = 1; foreach($data['students'] as $student): 
                    $balance = $student['total_due'] - $student['total_received'];
                ?>
                <tr>
                    <td></td>
                    <td><?= $counter++ ?></td>
                    <td><?= htmlspecialchars($student['name']) ?></td>
                    <td><?= htmlspecialchars($student['fname']) ?></td>
                    <td><?= $student['total_due'] ?></td>
                    <td><?= $student['total_received'] ?></td>
                    <td><?= $balance ?></td>
                </tr>
                <?php endforeach; ?>
                
                <?php endforeach; ?>
                
                <!-- Grand Total Row -->
                <tr style="background:#e0e0e0; font-weight:bold">
                    <td colspan="4">Grand Total</td>
                    <td><?= $grand_total_due ?></td>
                    <td><?= $grand_total_received ?></td>
                    <td><?= $grand_total_balance ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
function verifyToken($subdomain, $token) {
    // Implement actual token verification logic here
    return true;
}
?>
