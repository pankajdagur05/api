<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced Syllabus Tracker</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4A90E2;
            --success: #2ECC71;
            --bg: #F4F7FE;
            --text: #2D3436;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px; color: var(--text);
            padding-bottom: 50px;
        }
        
        /* Top Progress Summary */
        .overall-card {
            background: white;
            padding: 20px;
            border-radius: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .progress-circle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: conic-gradient(#4facfe 65%, #eee 0);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .progress-circle::after {
            content: "65%";
            position: absolute;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        /* Subject Card */
        .subject-card {
            background: white;
            border-radius: 20px;
            margin-bottom: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #edf2f7;
        }

        .subject-header {
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            background: white;
        }

        .subject-icon {
            width: 45px; height: 45px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin-right: 15px; font-size: 20px;
        }

        /* Chapters List (Hidden by default) */
        .chapters-list {
            display: none;
            padding: 0 18px 18px 18px;
            background: #fafbff;
            border-top: 1px solid #f1f1f1;
        }

        .chapter-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: white;
            margin-top: 10px;
            border-radius: 12px;
            border: 1px solid #f0f0f0;
            cursor: pointer;
            transition: 0.2s;
        }

        .chapter-item:active { transform: scale(0.98); background: #f9f9f9; }

        .status-badge {
            width: 8px; height: 8px; border-radius: 50%; margin-right: 12px;
        }

        /* Bottom Sheet (Topic Detail) */
        .bottom-sheet {
            position: fixed;
            bottom: -100%;
            left: 0;
            width: 100%;
            height: 80%;
            background: white;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.1);
            z-index: 1000;
            transition: bottom 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            padding: 25px;
            box-sizing: border-box;
            overflow-y: auto;
        }

        .bottom-sheet.active { bottom: 0; }

        .overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); display: none; z-index: 999;
        }

        .drag-handle {
            width: 40px; height: 5px; background: #ddd;
            border-radius: 10px; margin: -10px auto 20px;
        }

        /* Topic & Subtopic UI */
        .topic-box { margin-bottom: 20px; }
        .topic-title { font-weight: 600; color: var(--primary); display: block; margin-bottom: 8px; }
        
        .subtopic-list {
            border-left: 2px dashed #d1d8e0;
            margin-left: 10px;
            padding-left: 20px;
        }
        .subtopic-item {
            font-size: 13px; color: #636e72;
            padding: 5px 0;
            position: relative;
        }
        .subtopic-item::before {
            content: ''; position: absolute; left: -20px; top: 15px;
            width: 10px; height: 2px; background: #d1d8e0;
        }

    </style>
</head>
<body>
    
    <div class="overall-card">
        <div>
            <h2 style="margin:0; font-size: 20px;">Course Progress</h2>
            <p style="color: #888; font-size: 13px; margin: 5px 0 0 0;">12 out of 18 Topics Covered</p>
        </div>
        <div class="progress-circle"></div>
    </div>

    <h2 style="margin-bottom: 20px;">Curriculum</h2>

    <div class="subject-card">
        <div class="subject-header" onclick="toggleChapters(this)">
            <div style="display: flex; align-items: center;">
                <div class="subject-icon" style="background: #E3F2FD; color: #2196F3;"><i class="fas fa-square-root-alt"></i></div>
                <div>
                    <span style="font-weight:600; display:block;">Mathematics</span>
                    <span style="font-size:12px; color:#888;">12 Chapters • 60% Complete</span>
                </div>
            </div>
            <i class="fas fa-chevron-down arrow-icon" style="color:#ccc;"></i>
        </div>
        
        <div class="chapters-list">
            <div class="chapter-item" onclick="openTopics('Trigonometry')">
                <div class="status-badge" style="background: var(--success);"></div>
                <div style="flex:1; font-size:14px;">Chapter 1: Trigonometry</div>
                <i class="fas fa-chevron-right" style="font-size:12px; color:#eee;"></i>
            </div>
            <div class="chapter-item" onclick="openTopics('Algebra')">
                <div class="status-badge" style="background: #f1c40f;"></div>
                <div style="flex:1; font-size:14px;">Chapter 2: Linear Algebra</div>
                <i class="fas fa-chevron-right" style="font-size:12px; color:#eee;"></i>
            </div>
        </div>
    </div>

    <div class="subject-card">
        <div class="subject-header" onclick="toggleChapters(this)">
            <div style="display: flex; align-items: center;">
                <div class="subject-icon" style="background: #F3E5F5; color: #9C27B0;"><i class="fas fa-flask"></i></div>
                <div>
                    <span style="font-weight:600; display:block;">Science</span>
                    <span style="font-size:12px; color:#888;">8 Chapters • 20% Complete</span>
                </div>
            </div>
            <i class="fas fa-chevron-down arrow-icon" style="color:#ccc;"></i>
        </div>
        
        <div class="chapters-list">
            <div class="chapter-item" onclick="openTopics('Physics')">
                <div class="status-badge" style="background: #dfe6e9;"></div>
                <div style="flex:1; font-size:14px;">Chapter 1: Laws of Motion</div>
                <i class="fas fa-chevron-right" style="font-size:12px; color:#eee;"></i>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay" onclick="closeTopics()"></div>
    <div class="bottom-sheet" id="bottomSheet">
        <div class="drag-handle"></div>
        <h3 id="modalTitle">Chapter Topics</h3>
        <hr style="border:0; border-top:1px solid #eee; margin: 20px 0;">
        
        <div class="topic-box">
            <span class="topic-title">1.1 Introduction to Sine & Cosine</span>
            <div class="subtopic-list">
                <div class="subtopic-item">Definition of Ratios</div>
                <div class="subtopic-item">Unit Circle Concepts</div>
                <div class="subtopic-item">Pythagorean Identities</div>
            </div>
        </div>

        <div class="topic-box">
            <span class="topic-title">1.2 Trigonometric Graphs</span>
            <div class="subtopic-list">
                <div class="subtopic-item">Amplitude and Period</div>
                <div class="subtopic-item">Phase Shift Analysis</div>
            </div>
        </div>
        
        <button onclick="closeTopics()" style="width:100%; padding:15px; border-radius:15px; border:none; background:var(--primary); color:white; font-weight:600; margin-top:20px;">Got it!</button>
    </div>

    <script>
        // Function to toggle accordion
        function toggleChapters(element) {
            const list = element.nextElementSibling;
            const arrow = element.querySelector('.arrow-icon');
            
            if (list.style.display === "block") {
                list.style.display = "none";
                arrow.style.transform = "rotate(0deg)";
            } else {
                list.style.display = "block";
                arrow.style.transform = "rotate(180deg)";
            }
        }

        // Function to open Bottom Sheet
        function openTopics(name) {
            document.getElementById('modalTitle').innerText = name + " Breakdown";
            document.getElementById('bottomSheet').classList.add('active');
            document.getElementById('overlay').style.display = 'block';
        }

        // Function to close Bottom Sheet
        function closeTopics() {
            document.getElementById('bottomSheet').classList.remove('active');
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

</body>
</html>