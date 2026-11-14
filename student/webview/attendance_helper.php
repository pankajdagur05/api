<?php
// Returns present/absent/leave stats, day-wise status, holidays and today's status for a given month
function getMonthAttendance($host, $user, $sid, $month, $year) {
    $result = array();

    $q = "SELECT COUNT(*) as total,
                 SUM(IF(status='P',1,0)) as p,
                 SUM(IF(status='A',1,0)) as ab,
                 SUM(IF(status='L',1,0)) as lv
          FROM attendance
          WHERE sid='$sid' AND subdomain='$user'
            AND MONTH(idate)='$month' AND YEAR(idate)='$year'";
    $s = mysqli_query($host, $q);
    $d = mysqli_fetch_assoc($s);

    $total   = (int)$d['total'];
    $present = (int)$d['p'];
    $absent  = (int)$d['ab'];
    $leave   = (int)$d['lv'];
    $percent = $total > 0 ? round(($present * 100) / $total) : 0;

    $result['month']      = (int)$month;
    $result['year']       = (int)$year;
    $result['percentage'] = $percent;
    $result['present']    = $present;
    $result['absent']     = $absent;
    $result['leave']      = $leave;
    $result['total']      = $total;

    // day-wise status: 1 => 'present' / 'absent' / 'leave'
    $days = array();
    $q2 = "SELECT status, idate FROM attendance
           WHERE sid='$sid' AND subdomain='$user'
             AND MONTH(idate)='$month' AND YEAR(idate)='$year'
           ORDER BY idate ASC";
    $s2 = mysqli_query($host, $q2);
    $statusMap = array('P' => 'present', 'A' => 'absent', 'L' => 'leave');
    while ($d2 = mysqli_fetch_assoc($s2)) {
        $day = (int)date('j', strtotime($d2['idate']));
        $days[$day] = isset($statusMap[$d2['status']]) ? $statusMap[$d2['status']] : '';
    }
    $result['days'] = $days;

    // declared holidays from calendar table (range fdate -> tdate)
    $holidays = array();
    $q3 = "SELECT * FROM calender
           WHERE cid='$user'
             AND ((MONTH(fdate)='$month' AND YEAR(fdate)='$year')
               OR (MONTH(tdate)='$month' AND YEAR(tdate)='$year'))
           ORDER BY DAY(fdate) ASC";
    $s3 = mysqli_query($host, $q3);
    while ($d3 = mysqli_fetch_assoc($s3)) {
        $fDay = (int)date('j', strtotime($d3['fdate']));
        $tDay = !empty($d3['tdate']) ? (int)date('j', strtotime($d3['tdate'])) : $fDay;
        for ($dd = $fDay; $dd <= $tDay; $dd++) {
            $holidays[$dd] = $d3['title'];
        }
    }
    $result['holidays'] = $holidays;

    // today's status (only meaningful when the requested month is the current month)
    $todayStatus = '';
    $today = date('Y-m-d');
    $q4 = "SELECT status, time FROM attendance WHERE sid='$sid' AND subdomain='$user' AND idate='$today' LIMIT 1";
    $s4 = mysqli_query($host, $q4);
    if ($d4 = mysqli_fetch_assoc($s4)) {
        $labelMap = array('P' => 'Present', 'A' => 'Absent', 'L' => 'Leave');
        $todayStatus = isset($labelMap[$d4['status']]) ? $labelMap[$d4['status']] : $d4['status'];

        if (!empty($d4['time'])) {
            $timeParts = explode(',', $d4['time']);
            $checkin   = trim($timeParts[0]);
            if ($checkin !== '') {
                $todayStatus .= ' - ' . date('h:i A', strtotime($checkin));
            }
        }
    }
    $result['todayStatus'] = $todayStatus;

    return $result;
}