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

// Query to fetch data
$date = date('Y-m-d');
$fr = $_REQUEST['fr'];
$to = $_REQUEST['to'];

$q = "SELECT students.name AS name, students.id AS id, COUNT(students.id) AS count, students.standard AS standard FROM students INNER JOIN standard ON standard.name = students.standard WHERE standard.cid = '$user' AND students.status = 'Active' AND (standard.status = 'Active' OR standard.status = '' OR standard.status IS NULL) AND students.session = '$session' AND students.cid = '$user' AND LENGTH(students.standard) > 0 AND students.tc_date IS NULL";

if (count($to) > 0) {
    $q .= " AND (";
    for ($a = 0; $a < count($to); $a++) {
        $q .= " students.standard = '$to[$a]' ";
        if ($a < count($to) - 1) {
            $q .= " OR ";
        }
    }
    $q .= ")";
}

$q .= " GROUP BY students.standard ORDER BY standard.num ASC";

// Fetch data
$s = mysqli_query($host, $q);
$ar1 = array();

while ($d = mysqli_fetch_array($s)) {
    $standard = $d['standard'];

    $q1 = "SELECT SUM(debit) FROM students INNER JOIN feesdue ON feesdue.sid = students.id WHERE students.session = '$session' AND feesdue.voucher = 'Due' AND students.cid = '$user' AND students.status = 'Active' AND students.standard = '$standard'";
    $s1 = mysqli_query($host, $q1);
    $d1 = mysqli_fetch_array($s1);

    $q2 = "SELECT SUM(debit) FROM students INNER JOIN feesdue INNER JOIN erp_category ON feesdue.sid = students.id AND erp_category.id = feesdue.cateid WHERE students.session = '$session' AND feesdue.voucher = 'Due' AND erp_category.name = 'Balance B/D' AND students.cid = '$user' AND students.standard = '$standard'";
    $s3 = mysqli_query($host, $q2);
    $d3 = mysqli_fetch_array($s3);

    $q4 = "SELECT SUM(credit) FROM students INNER JOIN feesdue INNER JOIN erp_category ON feesdue.sid = students.id AND erp_category.id = feesdue.cateid WHERE students.session = '$session' AND feesdue.voucher = 'receive' AND erp_category.name = 'Balance B/D' AND feesdue.mode <> 'Concession' AND feesdue.cancel <> 'yes' AND students.cid = '$user' AND students.standard = '$standard'";
    $s4 = mysqli_query($host, $q4);
    $d4 = mysqli_fetch_array($s4);

    $q5 = "SELECT SUM(credit) FROM students INNER JOIN feesdue ON feesdue.sid = students.id WHERE students.session = '$session' AND feesdue.voucher = 'receive' AND students.cid = '$user' AND feesdue.mode <> 'Concession' AND feesdue.cancel <> 'yes' AND students.standard = '$standard'";
    $s2 = mysqli_query($host, $q5);
    $d2 = mysqli_fetch_array($s2);

    $row2 = array();
    $row2['date'] = $date;
    $row2['standard'] = $d['standard'];
    $row2['count'] = $d['count'];
    $row2['bd'] = $d3[0];
    $row2['other'] = $d1[0] - $d3[0];
    $row2['total'] = $d1[0];
    $row2['rbd'] = $d4[0];
    $row2['rc'] = $d2[0] - $d4[0];
    $row2['totalReceived'] = $d2[0];
    $row2['outstanding'] = $d1[0] - $d2[0];

    $ar1[] = $row2;
}

// Display data in HTML table
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Wise Fees Report</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
</head>
<body>
    <div class="container">
        <h2>Class Wise Fees Report</h2>
        <div class="row">
            <div class="col-md-6">
                <select id="f5" name="f5" data-placeholder="Select Class ..." multiple class="chosen-select">
                    <?php
                    $s3 = mysqli_query($host, "SELECT * FROM stream WHERE cid = '$user'");
                    while ($d3 = mysqli_fetch_array($s3)) {
                        $gid = $d3['id'];
                    ?>
                        <optgroup label="<?php echo $d3['name']; ?>">
                            <?php
                            $s4 = mysqli_query($host, "SELECT * FROM standard WHERE cid = '$user' AND type = '$gid'");
                            while ($d4 = mysqli_fetch_array($s4)) {
                            ?>
                                <option value="<?php echo $d4['name']; ?>"><?php echo $d4['name']; ?></option>
                            <?php
                            }
                            ?>
                        </optgroup>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <div id="reportrange1" class="datran">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
        </div>
        <input type="submit" class="btn btn-primary" value="Submit" onclick="filter(13);" />
        
        <table id="reptm" class="table table-striped">
            <thead>
                <tr>
                    <th width="120" id="cent">Date</th>
                    <th width="200">Standard</th>
                    <th width="100">No. Of Students</th>
                    <th width="100">Opening Balance (B/D)</th>
                    <th width="100">Current Year Fees</th>
                    <th width="200">Total Fees</th>
                    <th width="200">B/D Received</th>
                    <th width="200">Current Year Received</th>
                    <th width="200">Total Received</th>
                    <th width="200">Total Outstanding</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ar1 as $row) { ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['standard']; ?></td>
                        <td><?php echo $row['count']; ?></td>
                        <td><?php echo $row['bd']; ?></td>
                        <td><?php echo $row['other']; ?></td>
                        <td><?php echo $row['total']; ?></td>
                        <td><?php echo $row['rbd']; ?></td>
                        <td><?php echo $row['rc']; ?></td>
                        <td><?php echo $row['totalReceived']; ?></td>
                        <td><?php echo $row['outstanding']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $totalStudents = 0;
                $totalBD = 0;
                $totalOther = 0;
                $totalFees = 0;
                $totalRBD = 0;
                $totalRC = 0;
                $totalReceived = 0;
                $totalOutstanding = 0;

                foreach ($ar1 as $row) {
                    $totalStudents += $row['count'];
                    $totalBD += $row['bd'];
                    $totalOther += $row['other'];
                    $totalFees += $row['total'];
                    $totalRBD += $row['rbd'];
                    $totalRC += $row['rc'];
                    $totalReceived += $row['totalReceived'];
                    $totalOutstanding += $row['outstanding'];
                }
                ?>
                <tr>
                    <th colspan="2">Total</th>
                    <th><?php echo $totalStudents; ?></th>
                    <th><?php echo $totalBD; ?></th>
                    <th><?php echo $totalOther; ?></th>
                    <th><?php echo $totalFees; ?></th>
                    <th><?php echo $totalRBD; ?></th>
                    <th><?php echo $totalRC; ?></th>
                    <th><?php echo $totalReceived; ?></th>
                    <th><?php echo $totalOutstanding; ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen({
                no_results_text: "Oops, nothing found!"
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                fr = start.format('YYYY-MM-DD');
                to = end.format('YYYY-MM-DD');
                $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#reportrange1').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            cb(start, end);

            function filter(type) {
                var classes = $('#f5').val();
                var fr = $('#reportrange1 span').text().split(' - ')[0];
                var to = $('#reportrange1 span').text().split(' - ')[1];

                $.ajax({
                    type: 'POST',
                    url: 'classwisefeesreportt.php',
                    data: {
                        fr: fr,
                        to: to,
                        classes: classes
                    },
                    success: function(data) {
                        $('#reptm').html(data);
                    }
                });
            }
        });
    </script>
</body>
</html>
