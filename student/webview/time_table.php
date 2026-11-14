<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Time Table</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6C5CE7;
            --accent: #A29BFE;
            --bg: #F0F3F7;
            --card: #FFFFFF;
            --text: #2D3436;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 0; color: var(--text);
            overflow-x: hidden;
        }

        /* Header & Day Selector */
        .header {
            background: var(--primary);
            padding: 30px 20px 60px 20px;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            color: white;
            text-align: center;
        }

        .day-selector {
            display: flex;
            justify-content: space-around;
            background: white;
            margin: -40px 20px 20px 20px;
            padding: 10px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .day-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 12px 15px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 50px;
        }

        .day-btn span:first-child { font-size: 10px; text-transform: uppercase; color: #b2bec3; }
        .day-btn span:last-child { font-size: 16px; font-weight: 700; }

        .day-btn.active {
            background: var(--primary);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(108, 92, 231, 0.4);
        }
        .day-btn.active span:first-child { color: rgba(255,255,255,0.8); }

        /* Timeline UI */
        .timeline-container {
            padding: 20px;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .period-card {
            display: flex;
            background: var(--card);
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            border-left: 6px solid #dfe6e9;
            transition: 0.3s;
        }

        .period-card.ongoing {
            border-left-color: #00b894;
            background: #f0fff4;
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 184, 148, 0.1);
        }

        .time-slot {
            min-width: 75px;
            border-right: 1px solid #eee;
            margin-right: 15px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .time-slot span { font-size: 12px; font-weight: 600; color: #636e72; }
        .time-slot .end-time { font-weight: 400; color: #b2bec3; }

        .subject-details { flex: 1; }
        .subject-details h3 { margin: 0; font-size: 16px; color: var(--text); }
        .teacher-info { display: flex; align-items: center; margin-top: 5px; font-size: 12px; color: #888; }
        .teacher-info i { margin-right: 5px; color: var(--primary); }

        .room-tag {
            position: absolute;
            top: 15px; right: 15px;
            background: #f1f2f6;
            padding: 2px 8px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Lunch Break Style */
        .break-card {
            background: #fff9db;
            text-align: center;
            padding: 10px;
            border-radius: 15px;
            margin: 20px 0;
            border: 2px dashed #f1c40f;
            color: #d4a017;
            font-weight: 600;
            font-size: 14px;
        }

        .live-dot {
            width: 8px; height: 8px; background: #00b894;
            border-radius: 50%; display: inline-block;
            margin-right: 5px; animation: blink 1s infinite;
        }

        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0;">Weekly Schedule</h2>
        <p style="opacity: 0.8; font-size: 13px;">Class 10-A | Session 2025</p>
    </div>

    <div class="day-selector">
        <div class="day-btn active" onclick="changeDay(this, 'Mon')">
            <span>MON</span><span>26</span>
        </div>
        <div class="day-btn" onclick="changeDay(this, 'Tue')">
            <span>TUE</span><span>27</span>
        </div>
        <div class="day-btn" onclick="changeDay(this, 'Wed')">
            <span>WED</span><span>28</span>
        </div>
        <div class="day-btn" onclick="changeDay(this, 'Thu')">
            <span>THU</span><span>29</span>
        </div>
        <div class="day-btn" onclick="changeDay(this, 'Fri')">
            <span>FRI</span><span>30</span>
        </div>
    </div>

    <div class="timeline-container" id="schedule-list">
        
        <div class="period-card ongoing">
            <div class="time-slot">
                <span>09:00 AM</span>
                <span class="end-time">09:45 AM</span>
            </div>
            <div class="subject-details">
                <span style="font-size: 10px; color: #00b894; font-weight: 700;">
                    <span class="live-dot"></span>HAPPENING NOW
                </span>
                <h3>Mathematics (Calculus)</h3>
                <div class="teacher-info">
                    <i class="fas fa-user-tie"></i> Dr. Rajesh Kumar
                </div>
            </div>
            <div class="room-tag">ROOM 102</div>
        </div>

        <div class="period-card">
            <div class="time-slot">
                <span>09:45 AM</span>
                <span class="end-time">10:30 AM</span>
            </div>
            <div class="subject-details">
                <h3>Physics - Light</h3>
                <div class="teacher-info">
                    <i class="fas fa-user-tie"></i> Ms. Anita Sharma
                </div>
            </div>
            <div class="room-tag">LAB 02</div>
        </div>

        <div class="break-card">
            <i class="fas fa-utensils"></i> LUNCH BREAK (10:30 AM - 11:00 AM)
        </div>

        <div class="period-card" style="border-left-color: #a29bfe;">
            <div class="time-slot">
                <span>11:00 AM</span>
                <span class="end-time">11:45 AM</span>
            </div>
            <div class="subject-details">
                <h3>English Literature</h3>
                <div class="teacher-info">
                    <i class="fas fa-user-tie"></i> Mr. David Wilson
                </div>
            </div>
            <div class="room-tag">ROOM 105</div>
        </div>

    </div>

    <script>
        function changeDay(btn, day) {
            // Remove active class from all
            document.querySelectorAll('.day-btn').forEach(d => d.classList.remove('active'));
            // Add active class to clicked
            btn.classList.add('active');

            // Animation effect for schedule update
            const list = document.getElementById('schedule-list');
            list.style.opacity = '0';
            
            setTimeout(() => {
                // Yahan aap AJAX se data fetch krke content update kr skte hain
                // Abhi ke liye sirf animation dikha rha hu
                list.style.opacity = '1';
            }, 300);
        }
    </script>
</body>
</html>