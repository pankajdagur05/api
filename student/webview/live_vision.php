<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Vision - Light Mode</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --live: #eb4d4b;
            --primary: #6c5ce7;
            --bg: #f6f9fc;
            --white: #ffffff;
            --text-main: #2d3436;
            --text-muted: #636e72;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px;
            color: var(--text-main);
        }

        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 20px; font-weight: 700; color: #130f40; }

        /* Modern Video Container */
        .video-box {
            width: 100%;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            border: 4px solid var(--white);
        }

        .live-tag {
            position: absolute;
            top: 15px; left: 15px;
            background: var(--live);
            color: white;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            display: flex; align-items: center; gap: 6px;
            animation: pulse 2s infinite;
            z-index: 5;
        }

        @keyframes pulse {
            0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(235, 77, 75, 0.7); }
            70% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(235, 77, 75, 0); }
            100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(235, 77, 75, 0); }
        }

        /* Video Overlay Controls */
        .video-controls {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            padding: 20px;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: white;
        }

        /* Interaction Panel */
        .interaction-panel {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 5px;
            margin-top: 10px;
        }

        .react-group { display: flex; gap: 10px; }
        .react-btn {
            background: var(--white);
            border: none;
            width: 42px; height: 42px;
            border-radius: 14px;
            font-size: 18px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            cursor: pointer;
            transition: 0.3s;
        }
        .react-btn:active { transform: scale(0.9); }

        /* Channel Scroll */
        .section-label { font-size: 14px; font-weight: 700; color: #1e293b; margin: 20px 0 12px 0; }
        
        .channel-row {
            display: flex; gap: 12px; overflow-x: auto; padding-bottom: 10px;
            scrollbar-width: none;
        }
        .channel-row::-webkit-scrollbar { display: none; }

        .cam-card {
            min-width: 130px;
            background: var(--white);
            padding: 12px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 2px solid transparent;
            transition: 0.3s;
        }
        .cam-card.active {
            border-color: var(--primary);
            background: #f5f3ff;
        }
        .cam-card i { font-size: 20px; color: var(--primary); margin-bottom: 6px; display: block; }
        .cam-card b { font-size: 12px; color: var(--text-main); }

        /* Past Stream List */
        .past-stream-card {
            background: var(--white);
            border-radius: 20px;
            padding: 10px;
            display: flex;
            gap: 15px;
            margin-bottom: 12px;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }

        .stream-thumb {
            width: 90px; height: 60px;
            background: #e2e8f0;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-size: 18px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Live Vision</h2>
        <div style="background: #eef2ff; color: var(--primary); padding: 5px 12px; border-radius: 10px; font-size: 11px; font-weight: 700;">
            12 Active Streams
        </div>
    </div>

    <div class="video-box">
        <div class="live-tag">
            <i class="fas fa-circle" style="font-size: 8px;"></i> LIVE
        </div>
        
        <img src="https://img.freepik.com/free-photo/studio-arrangement-work_23-2151976836.jpg?ga=GA1.1.770284485.1764749469&semt=ais_hybrid&w=740&q=80" 
             style="width:100%; height:100%; object-fit:cover;" alt="Live Class">

        <div class="video-controls">
            <div style="font-size: 14px; font-weight: 600;">Cultural Fest - Day 2</div>
            <div style="font-size: 10px; opacity: 0.8;"><i class="fas fa-eye"></i> 256 Students Watching</div>
        </div>
    </div>

    <div class="interaction-panel">
        <div class="react-group">
            <button class="react-btn">❤️</button>
            <button class="react-btn">👏</button>
            <button class="react-btn">🔥</button>
        </div>
        <button class="react-btn" style="color: var(--primary);"><i class="fas fa-share-alt"></i></button>
    </div>

    <div class="section-label">Switch Camera</div>
    <div class="channel-row">
        <div class="cam-card active">
            <i class="fas fa-video"></i>
            <b>Main Stage</b>
        </div>
        <div class="cam-card">
            <i class="fas fa-microscope"></i>
            <b>Bio Lab</b>
        </div>
        <div class="cam-card">
            <i class="fas fa-table-tennis"></i>
            <b>Sports Hall</b>
        </div>
        <div class="cam-card">
            <i class="fas fa-book-reader"></i>
            <b>Library</b>
        </div>
    </div>

    <div class="section-label">Recent Replays</div>
    
    <div class="past-stream-card">
        <div class="stream-thumb"><i class="fas fa-play-circle"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 700;">Morning Prayer</div>
            <div style="font-size: 11px; color: var(--text-muted);">Dec 24 • 15:20 min</div>
        </div>
    </div>

    <div class="past-stream-card">
        <div class="stream-thumb"><i class="fas fa-play-circle"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 700;">Inter-School Quiz</div>
            <div style="font-size: 11px; color: var(--text-muted);">Dec 22 • 45:00 min</div>
        </div>
    </div>

</body>
</html>