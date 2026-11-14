<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Pro - Smart Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --bg: #f8fafc;
            --glass: rgba(255, 255, 255, 0.95);
        }

        body { 
            margin: 0; background: var(--bg); 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b; padding: 15px; padding-top: 80px;
        }

        /* Fixed Search Header */
        .search-container {
            position: fixed; top: 0; left: 0; right: 0;
            background: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(15px);
            padding: 15px; z-index: 1000;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .search-wrapper {
            position: relative;
            background: white; border-radius: 18px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex; align-items: center; padding: 0 15px;
        }
        .search-wrapper i { color: var(--primary); font-size: 18px; }
        .search-wrapper input {
            border: none; padding: 12px; width: 100%;
            outline: none; font-family: inherit; font-weight: 600;
            font-size: 14px; background: transparent;
        }

        /* Section Styling */
        .section-box { margin-bottom: 20px; transition: 0.3s; }
        .section-header {
            font-size: 11px; font-weight: 800; color: #94a3b8;
            text-transform: uppercase; letter-spacing: 0.8px;
            margin: 0 0 12px 5px; display: flex; align-items: center; gap: 8px;
        }
        .section-header i { font-size: 12px; color: var(--primary); opacity: 0.7; }

        /* Grid System */
        .feature-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;
        }

        /* Compact Feature Card */
        .f-card {
            background: var(--glass); border-radius: 20px;
            padding: 12px 5px; text-align: center;
            display: flex; flex-direction: column; align-items: center;
            justify-content: center; transition: 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.02);
        }
        .f-card:active { transform: scale(0.9); }

        .f-icon {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; margin-bottom: 8px;
        }

        .f-card label {
            font-size: 9px; font-weight: 700; color: #475569;
            line-height: 1.1; height: 20px;
        }

        /* Color Variations */
        .c-blue { background: #eef2ff; color: #4f46e5; }
        .c-orange { background: #fff7ed; color: #f59e0b; }
        .c-green { background: #f0fdf4; color: #10b981; }
        .c-purple { background: #f5f3ff; color: #8b5cf6; }
        .c-red { background: #fff1f2; color: #f43f5e; }

        /* Hide logic for search */
        .hidden { display: none !important; }
        .empty-state { text-align: center; padding: 40px; color: #94a3b8; display: none; }
    </style>
</head>
<body>

    <div class="search-container">
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="featureSearch" placeholder="Search features (e.g. Marks, Fees...)" onkeyup="filterFeatures()">
        </div>
    </div>

    <div id="noResult" class="empty-state">
        <i class="fas fa-search-minus" style="font-size: 40px; margin-bottom: 10px;"></i>
        <p>No features found...</p>
    </div>

    <div class="section-box" id="sec-student">
        <div class="section-header"><i class="fas fa-user-circle"></i> Management</div>
        <div class="feature-grid">
            <div class="f-card" data-name="student manage"><div class="f-icon c-blue"><i class="fas fa-users-cog"></i></div><label>Student<br>Manage</label></div>
            <div class="f-card" data-name="student attendance"><div class="f-icon c-blue"><i class="fas fa-user-check"></i></div><label>Attendance</label></div>
            <div class="f-card" data-name="lead manage"><div class="f-icon c-orange"><i class="fas fa-filter"></i></div><label>Lead<br>Manage</label></div>
            <div class="f-card" data-name="fees calling due report"><div class="f-icon c-orange"><i class="fas fa-phone-alt"></i></div><label>Fees Calling</label></div>
        </div>
    </div>

    <div class="section-box" id="sec-academic">
        <div class="section-header"><i class="fas fa-book"></i> Academic</div>
        <div class="feature-grid">
            <div class="f-card" data-name="evaluate notebooks"><div class="f-icon c-red"><i class="fas fa-book-open"></i></div><label>Notebooks</label></div>
            <div class="f-card" data-name="marks upload"><div class="f-icon c-red"><i class="fas fa-file-upload"></i></div><label>Marks</label></div>
            <div class="f-card" data-name="exam setup"><div class="f-icon c-red"><i class="fas fa-edit"></i></div><label>Exam Setup</label></div>
            <div class="f-card" data-name="homework"><div class="f-icon c-red"><i class="fas fa-pen-nib"></i></div><label>Homework</label></div>
        </div>
    </div>

    <div class="section-box" id="sec-transport">
        <div class="section-header"><i class="fas fa-bus"></i> Transport</div>
        <div class="feature-grid">
            <div class="f-card" data-name="transport register"><div class="f-icon c-green"><i class="fas fa-id-card"></i></div><label>Register</label></div>
            <div class="f-card" data-name="transport report"><div class="f-icon c-green"><i class="fas fa-file-alt"></i></div><label>Report</label></div>
            <div class="f-card" data-name="vehicle maintenance"><div class="f-icon c-green"><i class="fas fa-tools"></i></div><label>Maint.</label></div>
        </div>
    </div>

    <div class="section-box" id="sec-special">
        <div class="section-header"><i class="fas fa-bolt"></i> Edplus Special</div>
        <div class="feature-grid">
            <div class="f-card" data-name="mis report"><div class="f-icon c-purple"><i class="fas fa-chart-pie"></i></div><label>MIS</label></div>
            <div class="f-card" data-name="join training"><div class="f-icon c-purple"><i class="fas fa-video"></i></div><label>Training</label></div>
            <div class="f-card" data-name="appeal box"><div class="f-icon c-purple"><i class="fas fa-envelope"></i></div><label>Appeal</label></div>
            <div class="f-card" data-name="gatepass system"><div class="f-icon c-purple"><i class="fas fa-door-open"></i></div><label>Gatepass</label></div>
            <div class="f-card" data-name="chatbot ai"><div class="f-icon c-purple"><i class="fas fa-robot"></i></div><label>Chatbot AI</label></div>
        </div>
    </div>

    <script>
        function filterFeatures() {
            let input = document.getElementById('featureSearch').value.toLowerCase();
            let cards = document.getElementsByClassName('f-card');
            let sections = document.getElementsByClassName('section-box');
            let foundCount = 0;

            for (let card of cards) {
                let name = card.getAttribute('data-name');
                if (name.includes(input)) {
                    card.classList.remove('hidden');
                    foundCount++;
                } else {
                    card.classList.add('hidden');
                }
            }

            // Hide sections if all cards inside are hidden
            for (let sec of sections) {
                let visibleCards = sec.querySelectorAll('.f-card:not(.hidden)');
                if (visibleCards.length === 0) {
                    sec.classList.add('hidden');
                } else {
                    sec.classList.remove('hidden');
                }
            }

            // Show empty state if nothing found
            document.getElementById('noResult').style.display = (foundCount === 0) ? 'block' : 'none';
        }
    </script>
</body>
</html>