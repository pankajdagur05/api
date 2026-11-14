<?php
include('auth.php');

$minorRows = array();

$att = mysqli_query($host, "SELECT ur.* FROM uploadresult ur 
                                    INNER JOIN exam e ON e.name = ur.exam 
                                    WHERE ur.cid='$user' AND ur.sid='$sid' AND e.etype='Minor' group by ur.exam
                                    ORDER BY ur.date DESC");
                                    
while ($row = mysqli_fetch_assoc($att)) {
    $obtain = (float)$row['obtain'];
    $outoff = (float)$row['outoff'];
    $percent = $outoff > 0 ? ($obtain / $outoff) * 100 : 0;

    if ($percent >= 75) {
        $trendColor = '';
        $trendIcon  = 'fa-arrow-up';
        $trendText  = 'Improved';
    } elseif ($percent >= 50) {
        $trendColor = 'style="color:#fab1a0;"';
        $trendIcon  = 'fa-minus';
        $trendText  = 'Average';
    } else {
        $trendColor = 'style="color:#d63031;"';
        $trendIcon  = 'fa-arrow-down';
        $trendText  = 'Needs Focus';
    }

    $row['percent']     = $percent;
    $row['trend_color'] = $trendColor;
    $row['trend_icon']  = $trendIcon;
    $row['trend_text']  = $trendText;

    $minorRows[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minor Exams Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --accent: #6c5ce7; --bg: #f0f2f5; }
        body { font-family: 'Poppins', sans-serif; background: var(--bg); margin: 0; padding: 20px; }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }

        .minor-list { display: flex; flex-direction: column; gap: 12px; }

        .minor-card {
            background: white;
            border-radius: 18px;
            padding: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border-left: 5px solid var(--accent);
            animation: slideLeft 0.5s ease;
        }
        .date-badge {
            background: #f1f0ff;
            color: var(--accent);
            padding: 10px;
            border-radius: 12px;
            text-align: center;
            min-width: 45px;
            margin-right: 15px;
        }
        .date-badge b { display: block; font-size: 18px; }
        .date-badge span { font-size: 10px; text-transform: uppercase; }
        .info { flex: 1; }
        .info h4 { margin: 0; font-size: 14px; color: #2d3436; }
        .info p { margin: 2px 0 0 0; font-size: 11px; color: #b2bec3; }
        .score-trend {
            text-align: right;
        }
        .score-val { font-weight: 700; color: #2d3436; font-size: 14px; }
        .trend-up { color: #00b894; font-size: 10px; }
        .no-data { text-align:center; padding: 40px 10px; color:#888; font-size:13px; }
        @keyframes slideLeft { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h2 style="margin:0;">Minor Tests</h2>
            <p style="font-size:12px; color:#636e72;">Weekly Performance Tracker</p>
        </div>
        <i class="fas fa-chart-line" style="color:var(--accent); font-size: 24px;"></i>
    </div>
    <div class="minor-list">
        <?php if (empty($minorRows)): ?>
            <div class="no-data">No minor test data found.</div>
        <?php else: ?>
            <?php foreach ($minorRows as $i => $row): ?>
                <?php
                    $isAlt = ($i % 2 === 1);
                    $borderColor = $isAlt ? '#00cec9' : '#6c5ce7';
                    $badgeBg     = $isAlt ? 'background:#e0fcfb; color:#00cec9;' : '';
                    $day   = date('d', strtotime($row['date']));
                    $month = date('M', strtotime($row['date']));
                ?>
                <div class="minor-card" style="border-left-color: <?php echo $borderColor; ?>; animation-delay: <?php echo $i * 0.1; ?>s;">
                    <div class="date-badge" style="<?php echo $badgeBg; ?>">
                        <b><?php echo htmlspecialchars($day); ?></b><span><?php echo htmlspecialchars($month); ?></span>
                    </div>
                    <div class="info">
                        <h4><?php echo htmlspecialchars($row['exam']); ?>: <?php echo htmlspecialchars($row['subject']); ?></h4>
                        <p>Standard: <?php echo htmlspecialchars($row['standard']); ?></p>
                    </div>
                    <div class="score-trend">
                        <div class="score-val"><?php echo htmlspecialchars($row['obtain']); ?>/<?php echo htmlspecialchars($row['outoff']); ?></div>
                        <div class="trend-up" <?php echo $row['trend_color']; ?>>
                            <i class="fas <?php echo $row['trend_icon']; ?>"></i> <?php echo $row['trend_text']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>