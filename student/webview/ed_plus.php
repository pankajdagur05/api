<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ED+ Advanced Tools</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --accent: #f59e0b;
            --bg: #f8fafc;
            --glass: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px;
            color: #1e293b;
            padding-bottom: 50px;
        }

        /* Top Header with Coins */
        .top-nav {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 5px; margin-bottom: 20px;
        }

        .coin-pill {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 6px 15px;
            border-radius: 50px;
            display: flex; align-items: center; gap: 8px;
            font-weight: 700; color: var(--accent);
            box-shadow: 0 4px 10px rgba(245, 158, 11, 0.1);
        }

        /* Streak Card */
        .streak-card {
            background: linear-gradient(135deg, #f59e0b, #ef4444);
            border-radius: 24px;
            padding: 20px;
            color: white;
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 25px;
            box-shadow: 0 15px 30px rgba(239, 68, 68, 0.2);
            animation: slideIn 0.8s ease;
        }

        .streak-info h3 { margin: 0; font-size: 18px; }
        .streak-info p { margin: 5px 0 0 0; font-size: 12px; opacity: 0.9; }
        .fire-icon { font-size: 40px; animation: flame 1s infinite alternate; }

        /* Bento Grid */
        .ed-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .ed-item {
            background: white;
            border-radius: 24px;
            padding: 20px;
            display: flex; flex-direction: column;
            align-items: center; text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.02);
            border: 1px solid #f1f5f9;
            transition: 0.3s;
        }
        .ed-item:active { transform: scale(0.95); }
        .ed-item.large { grid-column: span 2; flex-direction: row; text-align: left; gap: 15px; }

        .icon-box {
            width: 50px; height: 50px;
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-bottom: 12px;
        }
        .ed-item.large .icon-box { margin-bottom: 0; }

        /* Floating AI Buddy */
        .ai-buddy {
            position: fixed;
            bottom: 30px; right: 20px;
            width: 60px; height: 60px;
            background: var(--primary);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 24px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
            z-index: 100;
            cursor: pointer;
            animation: bounce 2s infinite;
        }

        /* Animations */
        @keyframes flame { from { transform: scale(1); filter: drop-shadow(0 0 5px orange); } to { transform: scale(1.1); filter: drop-shadow(0 0 15px red); } }
        @keyframes slideIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

        .section-title { font-size: 16px; font-weight: 700; margin: 25px 0 15px 5px; }
    </style>
</head>
<body>

    <div class="top-nav">
        <h2 style="margin:0;">ED+ Center</h2>
        <div class="coin-pill">
            <i class="fas fa-coins"></i> 1,250
        </div>
    </div>

    <div class="streak-card">
        <div class="streak-info">
            <h3>7 Days Streak!</h3>
            <p>You're on fire! Don't miss today's goal.</p>
        </div>
        <div class="fire-icon">🔥</div>
    </div>

    <div class="section-title">Smart Learning Tools</div>

    <div class="ed-grid">
        <div class="ed-item large" style="background: #f5f3ff;">
            <div class="icon-box" style="background: white; color: var(--primary);">
                <i class="fas fa-brain"></i>
            </div>
            <div>
                <b style="font-size:14px;">AI Doubt Buddy</b>
                <p style="font-size:11px; color:#64748b; margin:3px 0 0 0;">Snap a photo & get instant help</p>
            </div>
        </div>

        <div class="ed-item">
            <div class="icon-box" style="background: #ecfdf5; color: #10b981;">
                <i class="fas fa-vial"></i>
            </div>
            <b style="font-size:13px;">Quiz Zone</b>
            <p style="font-size:10px; color:#64748b; margin-top:5px;">Win coins & ranks</p>
        </div>

        <div class="ed-item">
            <div class="icon-box" style="background: #eff6ff; color: #3b82f6;">
                <i class="fas fa-book-open"></i>
            </div>
            <b style="font-size:13px;">E-Library</b>
            <p style="font-size:10px; color:#64748b; margin-top:5px;">500+ PDF & Audio</p>
        </div>

        <div class="ed-item large" style="background: #fff7ed;">
            <div class="icon-box" style="background: white; color: #f59e0b;">
                <i class="fas fa-compass"></i>
            </div>
            <div>
                <b style="font-size:14px;">Career Compass</b>
                <p style="font-size:11px; color:#64748b; margin:3px 0 0 0;">Personalized roadmap for you</p>
            </div>
        </div>
    </div>

    <div class="section-title">Today's Missions</div>
    <div style="background:white; padding:15px; border-radius:24px; display:flex; align-items:center; gap:15px; border:1px solid #f1f5f9;">
        <div style="width:45px; height:45px; border-radius:50%; border:3px solid #6366f1; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700;">
            75%
        </div>
        <div style="flex:1;">
            <b style="font-size:13px;">Vocabulary Challenge</b>
            <p style="margin:0; font-size:11px; color:#64748b;">Learn 5 new words today</p>
        </div>
        <i class="fas fa-chevron-right" style="color:#cbd5e1;"></i>
    </div>

    <div class="ai-buddy" id="aiBuddy">
        <i class="fas fa-robot"></i>
    </div>
    
      <?php include("footer.php"); ?>

    <script>
        document.getElementById('aiBuddy').addEventListener('click', () => {
            alert("AI Doubt Buddy: Hello Piyush! Scan your question or type here.");
        });
    </script>

</body>
</html>