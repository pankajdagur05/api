<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Study Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #FF7675;
            --secondary: #6C5CE7;
            --bg: #F7F9FC;
            --card: #FFFFFF;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 0;
            color: #2D3436;
        }

        .header {
            background: linear-gradient(135deg, var(--secondary), #8E44AD);
            padding: 30px 20px 60px 20px;
            color: white;
        }

        .search-box {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 15px;
    padding: 12px 20px;
    display: flex;
    align-items: center;
    margin-top: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

       .search-box input {
    background: none;
    border: none;
    color: white;
    outline: none;
    width: 100%;
    margin-left: 10px;
}

.search-box input::placeholder { color: rgba(255,255,255,0.7); }

        /* Subject Scroller */
        .subject-scroll {
            display: flex;
            overflow-x: auto;
            padding: 20px;
            margin-top: -40px;
            gap: 12px;
            scrollbar-width: none;
        }
        .subject-scroll::-webkit-scrollbar { display: none; }

        .subject-pill {
            background: white;
            padding: 10px 22px;
            border-radius: 50px;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
            color: #636e72;
        }
        .subject-pill.active {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* Content Area */
        .content-body { padding: 0 20px 20px 20px; }

        .material-card {
            background: white;
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f1f1;
            /* Initial state for animation */
            opacity: 1;
            transform: scale(1);
        }

        /* Hidden state for filtering */
        .material-card.hidden {
            display: none;
            opacity: 0;
            transform: scale(0.9);
        }

        .file-icon {
            width: 50px; height: 50px; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; margin-right: 15px;
        }

        .info { flex: 1; }
        .info h4 { margin: 0; font-size: 14px; color: #333; }
        .info p { margin: 2px 0; font-size: 11px; color: #999; }

        .tag {
            padding: 2px 8px; border-radius: 5px; font-size: 10px;
            font-weight: 700; display: inline-block; margin-top: 5px;
        }

        .download-btn {
            width: 38px; height: 38px; border-radius: 50%;
            background: #f1f2f6;
            display: flex; align-items: center; justify-content: center;
            color: var(--secondary);
        }
    </style>
</head>
<body>

    <div class="header">
        <h2 style="margin:0;">Study Library</h2>
        <p style="opacity: 0.8; font-size: 13px;">Explore notes, books &amp; papers</p>
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Search for chapters or topics...">
        </div>
    </div>

    <div class="subject-scroll">
        <div class="subject-pill active" onclick="filterSelection('all')">All Material</div>
        <div class="subject-pill" onclick="filterSelection('physics')">Physics</div>
        <div class="subject-pill" onclick="filterSelection('chemistry')">Chemistry</div>
        <div class="subject-pill" onclick="filterSelection('maths')">Mathematics</div>
        <div class="subject-pill" onclick="filterSelection('biology')">Biology</div>
    </div>

    <div class="content-body" id="materialContainer">
        
        <div class="material-card filter-item physics">
            <div class="file-icon" style="background: #FFF3F3; color: #FF7675;"><i class="fas fa-file-pdf"></i></div>
            <div class="info">
                <h4>Quantum Mechanics Notes</h4>
                <p>Physics • 4.2 MB</p>
                <div class="tag" style="background:#ffebee; color:#e74c3c;">PDF</div>
            </div>
            <div class="download-btn"><i class="fas fa-download"></i></div>
        </div>

        <div class="material-card filter-item chemistry">
            <div class="file-icon" style="background: #E8F5E9; color: #2ECC71;"><i class="fas fa-flask"></i></div>
            <div class="info">
                <h4>Periodic Table Chart</h4>
                <p>Chemistry • 1.5 MB</p>
                <div class="tag" style="background:#e8f5e9; color:#2ecc71;">IMAGE</div>
            </div>
            <div class="download-btn"><i class="fas fa-download"></i></div>
        </div>

        <div class="material-card filter-item maths">
            <div class="file-icon" style="background: #E3F2FD; color: #2196F3;"><i class="fas fa-calculator"></i></div>
            <div class="info">
                <h4>Trigonometry Formulas</h4>
                <p>Mathematics • 2.1 MB</p>
                <div class="tag" style="background:#e3f2fd; color:#2196f3;">PDF</div>
            </div>
            <div class="download-btn"><i class="fas fa-download"></i></div>
        </div>

        <div class="material-card filter-item biology">
            <div class="file-icon" style="background: #F3E5F5; color: #9C27B0;"><i class="fas fa-dna"></i></div>
            <div class="info">
                <h4>Human Anatomy Guide</h4>
                <p>Biology • 5.8 MB</p>
                <div class="tag" style="background:#f3e5f5; color:#9c27b0;">DOC</div>
            </div>
            <div class="download-btn"><i class="fas fa-download"></i></div>
        </div>

    </div>

    <script>
        function filterSelection(category) {
            const items = document.getElementsByClassName("filter-item");
            const pills = document.getElementsByClassName("subject-pill");

            // Update Active Pill
            for (let i = 0; i < pills.length; i++) {
                pills[i].classList.remove("active");
                if (pills[i].innerText.toLowerCase().includes(category) || 
                   (category === 'all' && pills[i].innerText.includes('All'))) {
                    pills[i].classList.add("active");
                }
            }

            // Filter Logic
            for (let i = 0; i < items.length; i++) {
                // Pehle card ko reset karein (aniamtion ke liye)
                items[i].classList.add("hidden");
                
                if (category === "all" || items[i].classList.contains(category)) {
                    // Thoda delay taaki smooth animation dikhe
                    setTimeout(() => {
                        items[i].classList.remove("hidden");
                    }, 50);
                }
            }
        }

        // Simple Search Logic
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let cards = document.getElementsByClassName('material-card');

            for (let card of cards) {
                let title = card.querySelector('h4').innerText.toLowerCase();
                if (title.includes(filter)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>