<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today Report</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4A90E2;
            --success: #2ECC71;
            --bg: #F8F9FE;
            --card-bg: #FFFFFF;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0;
            padding: 20px;
            color: #333;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 { font-size: 24px; margin: 0; color: #2C3E50; }
        .header p { color: #7F8C8D; margin-top: 5px; }

        /* Attendance Card with Glassmorphism */
        .status-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 20px;
            color: white;
            box-shadow: 0 10px 20px rgba(118, 75, 162, 0.2);
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Progress Circle Section */
        .report-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-box {
            background: var(--card-bg);
            padding: 15px;
            border-radius: 18px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
        }

        .info-box:active { transform: scale(0.95); }

        .icon-circle {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        /* Schedule Timeline */
        .timeline {
            margin-top: 25px;
            background: white;
            padding: 20px;
            border-radius: 20px;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-left: 2px dashed #E0E0E0;
            margin-left: 10px;
            padding-left: 20px;
            position: relative;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            width: 10px;
            height: 10px;
            background: var(--primary);
            border-radius: 50%;
        }

        .time { font-size: 12px; color: #999; }
        .subject { font-weight: 600; display: block; }

        /* Smooth Progress Bar */
        .progress-container {
            height: 8px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            margin-top: 15px;
        }
        .progress-bar {
            width: 75%; /* Dynamic */
            height: 100%;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px white;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Today's Report</h1>
        <p>Friday, 27 Oct 2023</p>
    </div>

    <div class="status-card">
        <div style="display:flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="opacity: 0.8; font-size: 14px;">Current Status</span>
                <h2 style="margin: 5px 0;">Present</h2>
            </div>
            <i class="fas fa-check-circle fa-2x"></i>
        </div>
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>
        <p style="font-size: 12px; margin-top: 10px;">Day Progress: 75% completed</p>
    </div>

    <div class="report-grid">
        <div class="info-box">
            <div class="icon-circle" style="background: #E3F2FD; color: #2196F3;">
                <i class="fas fa-book-open"></i>
            </div>
            <span style="font-size: 12px; color: #888;">Homework</span>
            <div style="font-weight: 600;">2 Pending</div>
        </div>
        <div class="info-box">
            <div class="icon-circle" style="background: #FFF3E0; color: #FF9800;">
                <i class="fas fa-star"></i>
            </div>
            <span style="font-size: 12px; color: #888;">Class Test</span>
            <div style="font-weight: 600;">A+ (9/10)</div>
        </div>
    </div>

    <div class="timeline">
        <h3 style="margin-top:0; font-size: 18px;">Today's Schedule</h3>
        
        <div class="timeline-item">
            <div>
                <span class="time">09:00 AM - 10:00 AM</span>
                <span class="subject">Mathematics - Algebra</span>
                <span style="font-size: 12px; color: var(--success);">Completed</span>
            </div>
        </div>

        <div class="timeline-item" style="border-left-color: var(--primary);">
            <div>
                <span class="time">10:15 AM - 11:15 AM</span>
                <span class="subject">Physics - Light & Optics</span>
                <span style="font-size: 12px; color: var(--primary);">Ongoing Now</span>
            </div>
        </div>

        <div class="timeline-item">
            <div>
                <span class="time">11:30 AM - 12:30 PM</span>
                <span class="subject">English Literature</span>
                <span style="font-size: 12px; color: #999;">Upcoming</span>
            </div>
        </div>
    </div>

</body>
</html>