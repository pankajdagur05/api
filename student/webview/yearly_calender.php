<?php
include('auth.php');

$date = date('Y-m-d');

// student's institution + standard
$s0 = mysqli_query($host, "SELECT cid, standard FROM students WHERE id='$sid'");
$d0 = mysqli_fetch_assoc($s0);
$user  = $d0['cid'];
$class = $d0['standard'];

function isVisibleForStandard($host, $user, $class, $calId) {
    $s0 = mysqli_query($host, "SELECT * FROM calendar_attachment WHERE cid='$user' AND cal='$calId'");
    if (mysqli_num_rows($s0) == 0) {
        return true; // no attachment restriction => visible to all standards
    }
    $s1 = mysqli_query($host, "SELECT * FROM calendar_attachment WHERE cid='$user' AND standard='$class' AND cal='$calId'");
    return mysqli_num_rows($s1) > 0;
}

function buildEventRow($row) {
    $mode = strtolower(trim($row['mode']));

    if ($mode === 'holiday') {
        $class    = 'holiday';
        $tagHtml  = '<span class="tag tag-holiday">Holiday</span>';
    } elseif ($mode === 'activity') {
        $class    = 'activity';
        $tagHtml  = '<span class="tag tag-activity">Activity</span>';
    } else {
        $class    = 'event';
        $tagHtml  = '<span class="tag" style="background:#eef2ff; color:var(--event);">School Event</span>';
    }

    $description = $row['descr'];
    if (!empty($row['tdate']) && $row['tdate'] !== $row['fdate']) {
        $description .= ($description ? ' — ' : '') . 'Till ' . date('d M Y', strtotime($row['tdate']));
    }

    return array(
        'class'       => $class,
        'tagHtml'     => $tagHtml,
        'title'       => $row['title'],
        'description' => $description,
        'day'         => date('d', strtotime($row['fdate'])),
        'weekday'     => date('D', strtotime($row['fdate'])),
        'monthLabel'  => date('F Y', strtotime($row['fdate'])),
        'fdate'       => $row['fdate']
    );
}

// ---- Upcoming events ----
$upcomingEvents = array();
$s = mysqli_query($host, "SELECT * FROM calender WHERE cid='$user' AND tdate>='$date' ORDER BY fdate, tdate ASC");
while ($d = mysqli_fetch_assoc($s)) {
    if (isVisibleForStandard($host, $user, $class, $d['id'])) {
        $upcomingEvents[] = buildEventRow($d);
    }
}

// ---- Past events (latest 15) ----
$pastEvents = array();
$s = mysqli_query($host, "SELECT * FROM calender WHERE cid='$user' AND tdate<'$date' ORDER BY fdate, tdate ASC LIMIT 15");
while ($d = mysqli_fetch_assoc($s)) {
    if (isVisibleForStandard($host, $user, $class, $d['id'])) {
        $pastEvents[] = buildEventRow($d);
    }
}

// group a flat event list into month => events, preserving order
function groupByMonth($events) {
    $grouped = array();
    foreach ($events as $e) {
        $grouped[$e['monthLabel']][] = $e;
    }
    return $grouped;
}

$upcomingGrouped = groupByMonth($upcomingEvents);
$pastGrouped     = groupByMonth($pastEvents);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Academic Calendar</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --holiday: #ff4757;
            --event: #3742fa;
            --activity: #2ed573;
            --bg: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 0;
            color: #1e293b;
        }

        .header {
            background: white;
            padding: 20px 20px 10px 20px;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .tab-container {
            display: flex;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
            margin-top: 15px;
        }

        .tab {
            flex: 1;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            color: #64748b;
        }

        .tab.active {
            background: white;
            color: var(--primary);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .calendar-body {
            padding: 20px;
        }

        .month-label {
            font-size: 18px;
            font-weight: 700;
            margin: 20px 0 15px 5px;
            color: #334155;
            display: flex;
            align-items: center;
        }

        .month-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
            margin-left: 15px;
        }

        .event-card {
            background: white;
            border-radius: 16px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            border-left: 5px solid transparent;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .date-box {
            text-align: center;
            min-width: 50px;
            margin-right: 15px;
        }

        .date-box .day { font-size: 20px; font-weight: 700; line-height: 1; }
        .date-box .weekday { font-size: 11px; color: #94a3b8; text-transform: uppercase; }

        .event-info { flex: 1; }
        .event-info h4 { margin: 0; font-size: 15px; font-weight: 600; }
        .event-info p { margin: 2px 0 0 0; font-size: 12px; color: #64748b; }

        .holiday { border-left-color: var(--holiday); }
        .holiday .date-box .day { color: var(--holiday); }

        .activity { border-left-color: var(--activity); }
        .activity .date-box .day { color: var(--activity); }

        .event { border-left-color: var(--event); }
        .event .date-box .day { color: var(--event); }

        .tag {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
            float: right;
            text-transform: uppercase;
        }

        .tag-holiday { background: #fff1f2; color: var(--holiday); }
        .tag-activity { background: #f0fdf4; color: var(--activity); }

        .no-data { text-align:center; padding: 40px 10px; color:#94a3b8; font-size:13px; }

        .hidden { display: none; }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0;">Academic Calendar</h2>
        <div class="tab-container">
            <div class="tab active" onclick="switchTab('upcoming')">Upcoming</div>
            <div class="tab" onclick="switchTab('past')">Past Events</div>
        </div>
    </div>

    <div class="calendar-body">

        <div id="upcoming-content">
            <?php if (empty($upcomingGrouped)): ?>
                <div class="no-data">No upcoming events found.</div>
            <?php else: ?>
                <?php foreach ($upcomingGrouped as $monthLabel => $events): ?>
                    <div class="month-label"><?php echo htmlspecialchars($monthLabel); ?></div>
                    <?php foreach ($events as $e): ?>
                        <div class="event-card <?php echo $e['class']; ?>">
                            <div class="date-box">
                                <div class="day"><?php echo htmlspecialchars($e['day']); ?></div>
                                <div class="weekday"><?php echo htmlspecialchars($e['weekday']); ?></div>
                            </div>
                            <div class="event-info">
                                <?php echo $e['tagHtml']; ?>
                                <h4><?php echo htmlspecialchars($e['title']); ?></h4>
                                <p><?php echo htmlspecialchars($e['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div id="past-content" class="hidden">
            <?php if (empty($pastGrouped)): ?>
                <div class="no-data">No past events found.</div>
            <?php else: ?>
                <?php foreach ($pastGrouped as $monthLabel => $events): ?>
                    <div class="month-label"><?php echo htmlspecialchars($monthLabel); ?></div>
                    <?php foreach ($events as $e): ?>
                        <div class="event-card <?php echo $e['class']; ?>" style="opacity: 0.7;">
                            <div class="date-box">
                                <div class="day"><?php echo htmlspecialchars($e['day']); ?></div>
                                <div class="weekday"><?php echo htmlspecialchars($e['weekday']); ?></div>
                            </div>
                            <div class="event-info">
                                <h4><?php echo htmlspecialchars($e['title']); ?></h4>
                                <p><?php echo htmlspecialchars($e['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <script>
        function switchTab(type) {
            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');

            const upcoming = document.getElementById('upcoming-content');
            const past = document.getElementById('past-content');

            if (type === 'upcoming') {
                upcoming.classList.remove('hidden');
                past.classList.add('hidden');
            } else {
                upcoming.classList.add('hidden');
                past.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>