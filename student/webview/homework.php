<?php
include('auth.php');

// find student's standard
$stdRes = mysqli_query($host, "SELECT standard FROM students WHERE id='$sid'");
$stdRow = mysqli_fetch_assoc($stdRes);
$standard = $stdRow ? $stdRow['standard'] : '';

$hwData = array(); // 'Y-m-d' => array of homework rows

$att = mysqli_query($host, "SELECT * FROM notes 
                                    WHERE cid='$user' AND standard='$standard' AND type='homework' 
                                    ORDER BY date DESC");

                                    
while ($row = mysqli_fetch_assoc($att)) {
    $d = date('Y-m-d', strtotime($row['date']));
    if (!isset($hwData[$d])) {
        $hwData[$d] = array();
    }
    $hwData[$d][] = array(
        'title'       => $row['title'],
        'chapter'     => $row['chapter'],
        'description' => $row['description'],
        'path'        => $row['path'],
        'subject'     => $row['subject']
    );
}

$dateKeys = array_keys($hwData); // already sorted desc since query is desc and array insertion preserves order
$today = date('Y-m-d');
$defaultDate = in_array($today, $dateKeys) ? $today : (isset($dateKeys[0]) ? $dateKeys[0] : '');

$hwDataJson = json_encode($hwData);

// subject badge color palette (cycled since actual color isn't stored)
$badgePalette = array(
    array('bg' => '#E3F2FD', 'fg' => '#2196F3'),
    array('bg' => '#FFF3E0', 'fg' => '#FF9800'),
    array('bg' => '#F3E5F5', 'fg' => '#9C27B0'),
    array('bg' => '#E8F5E9', 'fg' => '#00B894'),
    array('bg' => '#FFEBEE', 'fg' => '#D63031'),
    array('bg' => '#E0F7FA', 'fg' => '#00ACC1'),
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homework Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #6C5CE7;
            --warning: #FAB1A0;
            --danger: #D63031;
            --success: #00B894;
            --bg: #F8F9FD;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px;
            color: #2D3436;
        }

        .date-tabs {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 5px 0 15px 0;
            scrollbar-width: none;
            margin-bottom: 10px;
        }
        .date-tabs::-webkit-scrollbar { display: none; }

        .date-tab {
            background: white;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.3s;
            color: #B2BEC3;
        }
        .date-tab.active {
            background: var(--primary);
            color: white;
        }

        .hw-card {
            background: white;
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 15px;
            position: relative;
            border: 1px solid #EDF2F7;
            animation: fadeIn 0.5s ease-out;
            transition: 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hw-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .subject-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 8px;
            text-transform: uppercase;
        }

        .due-date {
            font-size: 11px;
            font-weight: 600;
            color: var(--danger);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .hw-title {
            font-size: 16px;
            font-weight: 600;
            margin: 12px 0 5px 0;
            color: #2D3436;
        }

        .hw-chapter {
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .hw-desc {
            font-size: 12px;
            color: #636E72;
            line-height: 1.5;
            margin-bottom: 15px;
        }

        .hw-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-top: 15px;
            border-top: 1px dashed #EEE;
        }

        .btn-attach {
            background: #F1F2F6;
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .no-data { text-align:center; padding: 40px 10px; color:#888; font-size:13px; }
        .hidden { display: none; }
    </style>
</head>
<body>

    <h2 style="margin-bottom: 10px;">Home Work</h2>

    <div class="date-tabs" id="dateTabs">
        <?php if (empty($dateKeys)): ?>
            <div class="date-tab active">No Homework</div>
        <?php else: ?>
            <?php foreach ($dateKeys as $d): ?>
                <div class="date-tab <?php echo ($d === $defaultDate) ? 'active' : ''; ?>"
                     onclick="switchDate('<?php echo $d; ?>', this)">
                    <?php echo ($d === $today) ? 'Today' : date('d M', strtotime($d)); ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="hw-list">
        <?php if (empty($dateKeys)): ?>
            <div class="no-data">No homework found.</div>
        <?php endif; ?>
    </div>

    <script>
        const hwData = <?php echo $hwDataJson; ?>;
        const defaultDate = <?php echo json_encode($defaultDate); ?>;
        const todayDate = <?php echo json_encode($today); ?>;
        const badgePalette = <?php echo json_encode($badgePalette); ?>;

        function formatLabel(dateStr) {
            if (dateStr === todayDate) return 'Today';
            const dt = new Date(dateStr);
            const opts = { day: '2-digit', month: 'short' };
            return dt.toLocaleDateString('en-GB', opts);
        }

        function renderDate(dateStr) {
            const list = document.getElementById('hw-list');
            const items = hwData[dateStr];

            if (!items || items.length === 0) {
                list.innerHTML = '<div class="no-data">No homework found for this date.</div>';
                return;
            }

            let html = '';
            items.forEach((hw, i) => {
                const badge = badgePalette[i % badgePalette.length];
                html += `
                    <div class="hw-card" style="animation-delay: ${i * 0.1}s;">
                        <div class="hw-header">
                            <span class="subject-badge" style="background:${badge.bg}; color:${badge.fg};">${hw.subject || ''}</span>
                            <span class="due-date" style="color:#94A3B8;"><i class="fas fa-calendar-alt"></i> ${formatLabel(dateStr)}</span>
                        </div>
                        <div class="hw-title">${hw.title || ''}</div>
                        ${hw.chapter ? `<div class="hw-chapter">Chapter: ${hw.chapter}</div>` : ''}
                        <div class="hw-desc">${hw.description || ''}</div>
                        <div class="hw-footer">
                            ${hw.path ? `<a class="btn-attach" href="${hw.path}" target="_blank" download><i class="fas fa-paperclip"></i> View / Download</a>` : ''}
                        </div>
                    </div>
                `;
            });

            list.innerHTML = html;
        }

        function switchDate(dateStr, element) {
            document.querySelectorAll('.date-tab').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
            renderDate(dateStr);
        }

        if (defaultDate) {
            renderDate(defaultDate);
        }
    </script>
</body>
</html>