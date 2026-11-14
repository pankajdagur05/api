<?php
include('auth.php');
include('attendance_helper.php');

$month = (int)date('n');
$year  = (int)date('Y');

$data = getMonthAttendance($host, $user, $sid, $month, $year);
$dataJson = json_encode($data);

$monthNames = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Attendance Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --present: #00b894;
            --absent: #d63031;
            --holiday: #fdcb6e;
            --leave: #0984e3;
            --primary: #6c5ce7;
            --bg: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px;
            color: #1e293b;
        }

        .stats-container {
            background: white;
            border-radius: 24px;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            animation: fadeIn 0.8s ease;
        }

        .chart-box {
            position: relative;
            width: 100px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-box svg {
            transform: rotate(-90deg);
        }

        .chart-box circle {
            fill: none;
            stroke-width: 8;
            stroke-linecap: round;
        }

        .percentage {
            position: absolute;
            font-size: 18px;
            font-weight: 700;
            color: var(--primary);
        }

        .stat-details h4 {
            margin: 0;
            font-size: 22px;
            color: var(--present);
        }

        .stat-details p {
            margin: 0;
            font-size: 12px;
            color: #64748b;
        }

        .calendar-card {
            background: white;
            border-radius: 24px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
        }

        .cal-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 20px;
        }

        .nav-btn {
            width: 35px; height: 35px; border-radius: 10px;
            border: none; background: #f1f5f9; cursor: pointer;
            transition: 0.3s;
        }

        .nav-btn:disabled { opacity: 0.3; cursor: not-allowed; }

        .cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
            text-align: center;
        }

        .day-name { font-size: 11px; font-weight: 700; color: #94a3b8; padding-bottom: 10px; }

        .date-cell {
            aspect-ratio: 1;
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            font-size: 13px; font-weight: 600;
            border-radius: 12px;
            position: relative;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
        }

        .date-cell.present { background: #f0fdf4; color: var(--present); border-color: #dcfce7; }
        .date-cell.absent { background: #fef2f2; color: var(--absent); border-color: #fee2e2; }
        .date-cell.holiday { background: #fffbeb; color: var(--holiday); border-color: #fef3c7; }
        .date-cell.leave { background: #eff6ff; color: var(--leave); border-color: #dbeafe; }

        .status-dot { width: 4px; height: 4px; border-radius: 50%; margin-top: 2px; }
        .present .status-dot { background: var(--present); }
        .absent .status-dot { background: var(--absent); }
        .leave .status-dot { background: var(--leave); }

        #monthYear { font-size: 16px; font-weight: 700; color: var(--primary); }

        .today-alert {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            color: white;
            padding: 15px 20px;
            border-radius: 20px;
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<div class="header" style="margin-bottom: 20px;">
        <h2 style="margin:0;">Attendance</h2>
        <p style="font-size: 12px; color: #64748b;" id="monthSummaryLabel"><?php echo $monthNames[$month] . ' ' . $year; ?> Summary</p>
    </div>

    <div class="stats-container">
        <div class="chart-box">
            <svg width="100" height="100">
                <circle cx="50" cy="50" r="40" stroke="#f1f5f9"></circle>
                <circle id="progressCircle" cx="50" cy="50" r="40" stroke="var(--primary)"></circle>
            </svg>
            <div class="percentage" id="percentageText"><?php echo $data['percentage']; ?>%</div>
        </div>
        <div class="stat-details">
            <h4 id="presentCount"><?php echo $data['present'] . ' / ' . $data['total']; ?></h4>
            <p>Total Present Days</p>
            <div style="display:flex; gap:10px; margin-top:10px;">
                <span style="color:var(--absent); font-size:11px;" id="absentCount"><i class="fas fa-circle"></i> <?php echo $data['absent']; ?> Absent</span>
                <span style="color:var(--leave); font-size:11px;" id="leaveCount"><i class="fas fa-circle"></i> <?php echo $data['leave']; ?> Leave</span>
            </div>
        </div>
    </div>

    <div class="calendar-card">
        <div class="cal-header">
            <button class="nav-btn" onclick="changeMonth(-1)"><i class="fas fa-chevron-left"></i></button>
            <div id="monthYear"><?php echo $monthNames[$month] . ' ' . $year; ?></div>
            <button class="nav-btn" id="nextBtn" onclick="changeMonth(1)" disabled><i class="fas fa-chevron-right"></i></button>
        </div>

        <div class="cal-grid" id="calendarGrid"></div>
    </div>

   <div class="today-alert">
        <div>
            <div style="font-size: 10px; opacity: 0.8; text-transform: uppercase;">Today's Status</div>
            <div style="font-weight: 600;" id="todayStatusText"><?php echo $data['todayStatus'] !== '' ? $data['todayStatus'] : 'No Record'; ?></div>
        </div>
        <i class="fas fa-fingerprint fa-2x" style="opacity:0.5;"></i>
    </div>

    <script>
        const monthNames = ["", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let currentDate = new Date(<?php echo $year; ?>, <?php echo $month - 1; ?>, 1);
        const realCurrentDate = new Date();
        let currentData = <?php echo $dataJson; ?>;

        const circleCircumference = 2 * Math.PI * 40;

        function updateStats(data) {
            document.getElementById('percentageText').innerHTML = data.percentage + '%';

            const offset = circleCircumference - (data.percentage / 100) * circleCircumference;
            const circle = document.getElementById('progressCircle');
            circle.setAttribute('stroke-dasharray', circleCircumference);
            circle.setAttribute('stroke-dashoffset', offset);

            document.getElementById('presentCount').innerHTML = data.present + ' / ' + data.total;
            document.getElementById('absentCount').innerHTML = '<i class="fas fa-circle"></i> ' + data.absent + ' Absent';
            document.getElementById('leaveCount').innerHTML = '<i class="fas fa-circle"></i> ' + data.leave + ' Leave';

            document.getElementById('todayStatusText').innerHTML = data.todayStatus ? data.todayStatus : 'No Record';
        }

        function updateHeader() {
            const label = monthNames[currentDate.getMonth() + 1] + ' ' + currentDate.getFullYear();
            document.getElementById('monthYear').innerHTML = label;
            document.getElementById('monthSummaryLabel').innerHTML = label + ' Summary';

            const nextBtn = document.getElementById('nextBtn');
            if (currentDate.getMonth() === realCurrentDate.getMonth() && currentDate.getFullYear() === realCurrentDate.getFullYear()) {
                nextBtn.disabled = true;
            } else {
                nextBtn.disabled = false;
            }
        }

        function renderCalendar(data) {
            const grid = document.getElementById('calendarGrid');
            grid.innerHTML = '';

            ["M", "T", "W", "T", "F", "S", "S"].forEach(day => {
                grid.innerHTML += `<div class="day-name">${day}</div>`;
            });

            const firstDayIndex = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
            const lastDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

            const padding = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

            for (let i = 0; i < padding; i++) {
                grid.innerHTML += `<div></div>`;
            }

            for (let d = 1; d <= lastDate; d++) {
                let statusClass = "";
                let dot = "";

                const dayOfWeek = new Date(currentDate.getFullYear(), currentDate.getMonth(), d).getDay();

                if (data.holidays && data.holidays[d]) {
                    statusClass = "holiday";
                } else if (data.days && data.days[d]) {
                    statusClass = data.days[d];
                    dot = '<div class="status-dot"></div>';
                } else if (dayOfWeek === 0) {
                    statusClass = "holiday";
                }

                grid.innerHTML += `<div class="date-cell ${statusClass}">${d}${dot}</div>`;
            }
        }

        function changeMonth(offset) {
            currentDate.setMonth(currentDate.getMonth() + offset);
            const m = currentDate.getMonth() + 1;
            const y = currentDate.getFullYear();

            fetch('attendance_ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'month=' + m + '&year=' + y
            })
            .then(res => res.json())
            .then(data => {
                currentData = data;
                renderCalendar(data);
                updateStats(data);
                updateHeader();
            });
        }

        // initial render (uses server-computed data for current month, no extra request)
        renderCalendar(currentData);
        updateStats(currentData);
        updateHeader();
    </script>
</body>
</html>