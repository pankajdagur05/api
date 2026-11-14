<?php
include('auth.php'); 

$examData = array(); // examName => ['subjects'=>[...], 'obtain'=>x, 'outoff'=>y]

$att = mysqli_query($host, "SELECT ur.* FROM uploadresult ur 
                                    INNER JOIN exam e ON e.name = ur.exam 
                                    WHERE ur.cid='$user' AND ur.sid='$sid' AND e.etype='Major' AND e.user='$user'");
while ($row = mysqli_fetch_assoc($att)) {
    $exam = $row['exam'];

    if (!isset($examData[$exam])) {
        $examData[$exam] = array(
            'subjects' => array(),
            'obtain'   => 0,
            'outoff'   => 0
        );
    }

    $examData[$exam]['subjects'][] = array(
        'subject' => $row['subject'],
        'obtain'  => (float)$row['obtain'],
        'outoff'  => (float)$row['outoff']
    );

    $examData[$exam]['obtain'] += (float)$row['obtain'];
    $examData[$exam]['outoff'] += (float)$row['outoff'];
}

// percentage + grade + gpa per exam (grade/gpa not stored in table, so derived from %)
foreach ($examData as $exam => $data) {
    $percent = $data['outoff'] > 0 ? round(($data['obtain'] / $data['outoff']) * 100, 1) : 0;
    $examData[$exam]['percentage'] = $percent;

    if ($percent >= 91)      { $grade = 'A1'; $gpa = 10; }
    elseif ($percent >= 81)  { $grade = 'A2'; $gpa = 9;  }
    elseif ($percent >= 71)  { $grade = 'B1'; $gpa = 8;  }
    elseif ($percent >= 61)  { $grade = 'B2'; $gpa = 7;  }
    elseif ($percent >= 51)  { $grade = 'C1'; $gpa = 6;  }
    elseif ($percent >= 41)  { $grade = 'C2'; $gpa = 5;  }
    elseif ($percent >= 33)  { $grade = 'D';  $gpa = 4;  }
    else                     { $grade = 'E';  $gpa = 0;  }

    $examData[$exam]['grade'] = $grade;
    $examData[$exam]['gpa']   = $gpa;
}

$examNames   = array_keys($examData);
$defaultExam = isset($examNames[0]) ? $examNames[0] : '';

// JSON for client-side tab switching (no page reload, same as original UI behaviour)
$examDataJson = json_encode($examData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Report Card</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary: #6C5CE7;
            --success: #00B894;
            --warning: #FDCB6E;
            --danger: #D63031;
            --bg: #F0F3F7;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px;
            color: #2D3436;
        }

        .header { margin-bottom: 20px; }
        .header h2 { margin: 0; font-weight: 700; color: #130f40; }

        .exam-tabs {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding: 10px 0;
            scrollbar-width: none;
        }
        .exam-tabs::-webkit-scrollbar { display: none; }

        .tab {
            background: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.3s;
        }
        .tab.active { background: var(--primary); color: white; }

        .score-card {
            background: linear-gradient(135deg, #6C5CE7, #A29BFE);
            border-radius: 25px;
            padding: 10px;
            color: white;
            text-align: center;
            margin-bottom: 25px;
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.3);
            animation: zoomIn 0.5s ease-out;
        }

        @keyframes zoomIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .score-main { font-size: 38px; font-weight: 800; margin: 0; padding:0;}
        .score-label { font-size: 14px; opacity: 0.9; margin: 0; padding:0;}

        /*.stats-row {*/
        /*    display: flex;*/
        /*    justify-content: space-around;*/
        /*    margin-top: 20px;*/
        /*    border-top: 1px solid rgba(255,255,255,0.2);*/
        /*    padding-top: 15px;*/
        /*}*/

        .subject-card {
            background: white;
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            animation: slideUp 0.5s ease-out forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .sub-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .sub-name { font-weight: 600; font-size: 15px; }
        .sub-marks { font-weight: 700; color: var(--primary); }

        .progress-container {
            background: #F1F2F6;
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 1s ease-in-out;
        }

        .remark-card {
            background: #E8F5E9;
            border-left: 5px solid var(--success);
            padding: 15px;
            border-radius: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .remark-card h4 { margin: 0 0 5px 0; font-size: 14px; color: #2E7D32; }
        .remark-card p { margin: 0; font-size: 12px; color: #444; line-height: 1.5; }

        .btn-download {
            width: 100%;
            background: white;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 12px;
            border-radius: 15px;
            font-weight: 700;
            margin-top: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.3s;
        }
        .btn-download:active { background: var(--primary); color: white; }

        .no-data { text-align:center; padding: 40px 10px; color:#888; font-size:13px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>Major Report Card</h2>
        <div class="exam-tabs" id="examTabs">
            <?php if (empty($examNames)): ?>
                <div class="tab active">No Exam</div>
            <?php else: ?>
                <?php foreach ($examNames as $exam): ?>
                    <div class="tab <?php echo ($exam === $defaultExam) ? 'active' : ''; ?>"
                         onclick="switchExam(this, '<?php echo htmlspecialchars(addslashes($exam)); ?>')">
                        <?php echo htmlspecialchars($exam); ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div id="reportBody">
        <?php if (empty($examNames)): ?>
            <div class="no-data">No result data found for this student.</div>
        <?php endif; ?>
    </div> 

    <script>
        // Data injected from DB (grouped by exam)
        const examData = <?php echo $examDataJson; ?>;
        const defaultExam = <?php echo json_encode($defaultExam); ?>;

        function getBarColor(percent) {
            if (percent >= 85) return 'var(--success)';
            if (percent >= 60) return 'var(--primary)';
            if (percent >= 40) return 'var(--warning)';
            return 'var(--danger)';
        }

        function renderExam(examName) {
            const data = examData[examName];
            const body = document.getElementById('reportBody');

            if (!data) {
                body.innerHTML = '<div class="no-data">No result data found for this exam.</div>';
                return;
            }

            let html = '';

            html += `
                <div class="score-card">
                    <p class="score-label">Overall Percentage</p>
                    <h1 class="score-main">${data.percentage}%</h1>
                </div>
            `;

            data.subjects.forEach((sub, i) => {
                const pct = sub.outoff > 0 ? (sub.obtain / sub.outoff) * 100 : 0;
                html += `
                    <div class="subject-card" style="animation-delay: ${i * 0.1}s;">
                        <div class="sub-header">
                            <span class="sub-name">${sub.subject}</span>
                            <span class="sub-marks">${sub.obtain}/${sub.outoff}</span>
                        </div>
                        <div class="progress-container">
                            <div class="progress-bar" style="width: ${pct}%; background: ${getBarColor(pct)};"></div>
                        </div>
                    </div>
                `;
            });

            html += `
                <div class="remark-card">
                    <h4><i class="fas fa-comment-dots"></i> Class Teacher's Remarks</h4>
                    <p>No remarks added yet.</p>
                </div>
            `;

            body.innerHTML = html;
        }

        function switchExam(element, examName) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            element.classList.add('active');
            renderExam(examName);
        }

        // initial load
        if (defaultExam) {
            renderExam(defaultExam);
        }
    </script>
</body>
</html>