<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* --- 1. VARIABLES & RESET --- */
        :root {
            --primary: #4f46e5;      /* Indigo */
            --primary-light: #eef2ff;
            --secondary: #64748b;    /* Slate */
            --success: #10b981;      /* Green */
            --danger: #f43f5e;       /* Red */
            --bg: #f3f5f9;
            --card-bg: #ffffff;
            --text-main: #1e293b;
            --text-light: #94a3b8;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: var(--bg); color: var(--text-main); height: 100vh; overflow: hidden; }
        a { text-decoration: none; }
        button { border: none; background: none; cursor: pointer; }

        /* --- 2. LAYOUT & GRID --- */
        .app-container { display: flex; height: 100vh; }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            overflow-y: auto;
            position: relative;
            transition: margin-left 0.3s;
        }

        .container {
            padding: 12px;
            max-width: 1000px;
            margin: 0 auto;
        }

        /* Grid System for Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 Columns */
            gap: 15px;
            margin-bottom: 20px;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 2fr; /* 1:2 Ratio */
            gap: 15px;
            margin-bottom: 20px;
        }

        /* --- 3. COMPONENTS --- */
        
        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #0f172a;
            color: white;
            height: 100%;
            position: fixed;
            left: -260px; /* Hidden by default on mobile */
            top: 0;
            z-index: 1000;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar.active { transform: translateX(260px); } /* Slide in */
        
        .logo { padding: 25px; font-size: 20px; font-weight: bold; letter-spacing: 1px; border-bottom: 1px solid #1e293b; }
        .logo span { color: var(--primary); }
        
        .nav-links { padding: 20px; flex: 1; }
        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 8px;
            border-radius: 12px;
            color: #94a3b8;
            font-size: 14px;
            font-weight: 500;
            transition: 0.3s;
        }
        .nav-item:hover, .nav-item.active { background: rgba(255,255,255,0.1); color: white; }
        .nav-item i { width: 25px; margin-right: 10px; }

        .user-profile { padding: 20px; border-top: 1px solid #1e293b; display: flex; gap: 10px; align-items: center; }
        .avatar { width: 40px; height: 40px; border-radius: 50%; }

        /* Header */
        .header {
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 1px solid #e2e8f0;
        }
        .menu-btn { font-size: 20px; color: var(--secondary); margin-right: 15px; }

        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: var(--radius);
            padding: 7px 9px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            transition: transform 0.3s;
        }
        .card:hover { transform: translateY(-5px); }

        .card-label { font-size: 11px; font-weight: 700; color: var(--text-light); text-transform: uppercase; letter-spacing: 0.5px; }
        .card-value { font-size: 17px; font-weight: 800; color: var(--text-main); margin: 5px 0; }
        .card-trend { font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 5px; }
        .trend-up { color: var(--success); }
        .trend-down { color: var(--danger); }

        /* Corner Shape Decoration */
        .card::after {
            content: ''; position: absolute; top: -10px; right: -10px;
            width: 60px; height: 60px; border-radius: 50%; opacity: 0.1;
        }
        .c-indigo::after { background: var(--primary); }
        .c-orange::after { background: orange; }
        .c-green::after { background: var(--success); }
        .c-red::after { background: var(--danger); }

        /* Action Buttons Grid */
        .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        .action-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 10px; border-radius: 12px; background: #f8fafc;
            border: 1px solid #e2e8f0; text-align: left; transition: 0.2s;
        }
        .action-btn:hover { background: var(--primary-light); border-color: var(--primary); }
        .action-icon {
            width: 32px; height: 32px; border-radius: 8px; background: white;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary); font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .action-text { font-size: 11px; font-weight: 700; color: var(--text-main); line-height: 1.2; }

        /* List Items */
        .list-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 10px 0; border-bottom: 1px solid #f1f5f9;
        }
        .list-item:last-child { border: none; }
        .item-left { display: flex; gap: 10px; align-items: center; }
        .item-info h4 { font-size: 12px; margin-bottom: 2px; }
        .item-info p { font-size: 10px; color: var(--text-light); }
        
        /* Banner */
        .banner {
            background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
            color: white; padding: 15px; border-radius: var(--radius);
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
        }
        .banner-btn { background: white; color: var(--primary); font-size: 10px; padding: 6px 12px; border-radius: 6px; font-weight: bold; }

        /* Overlay */
        .overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            z-index: 900; opacity: 0; pointer-events: none; transition: 0.3s;
        }
        .overlay.active { opacity: 1; pointer-events: all; }

        /* --- 4. RESPONSIVENESS --- */
        @media (min-width: 1024px) {
            .sidebar { left: 0; transform: none; } /* Sidebar always visible on Desktop */
            .main-content { margin-left: 260px; }
            .menu-btn { display: none; }
        }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr 1fr; } /* 2 Columns on Tablet */
            .content-grid { grid-template-columns: 1fr; } /* Stack on Mobile */
        }

        /* --- 5. ANIMATIONS --- */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        
        .animate { animation: fadeInUp 0.6s ease forwards; opacity: 0; }
        .d-1 { animation-delay: 0.1s; }
        .d-2 { animation-delay: 0.2s; }
        .d-3 { animation-delay: 0.3s; }

        /* Preloader */
        #preloader {
            position: fixed; inset: 0; background: white; z-index: 9999;
            display: flex; justify-content: center; align-items: center; flex-direction: column;
            transition: opacity 0.5s;
        }
        .loader {
            width: 40px; height: 40px; border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary); border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

    </style>
</head>
<body>

    <div id="preloader">
        <div class="loader"></div>
        <p style="margin-top: 15px; font-size: 12px; font-weight: bold; letter-spacing: 2px; color: var(--primary);">LOADING...</p>
    </div>

    <div class="app-container">
       

        <main class="main-content"> 

            <div class="container">
                
                <div class="stats-grid">
                    <div class="card c-indigo animate d-1">
                        <div class="card-label">Students</div>
                        <div class="card-value counter" data-target="1245">0</div>
                        <div class="card-trend trend-up"><i class="fa-solid fa-arrow-trend-up"></i> +12 this week</div>
                    </div>
                    <div class="card c-orange animate d-2">
                        <div class="card-label">Teachers</div>
                        <div class="card-value counter" data-target="86">0</div>
                        <div class="card-trend"><span style="color:var(--text-light)">82 Present Today</span></div>
                    </div>
                    <div class="card c-green animate d-3">
                        <div class="card-label">Revenue</div>
                        <div class="card-value">₹<span class="counter" data-target="450">0</span>k</div>
                        <div style="width: 100%; background: #f1f5f9; height: 4px; border-radius: 4px; margin-top: 5px;">
                            <div style="width: 75%; background: var(--success); height: 100%; border-radius: 4px;"></div>
                        </div>
                    </div>
                    <div class="card c-red animate d-3">
                        <div class="card-label">Absentees</div>
                        <div class="card-value" style="color: var(--danger);">18</div>
                        <button style="font-size: 10px; background: #fff1f2; color: var(--danger); padding: 4px 8px; border-radius: 4px; font-weight: bold;">View List</button>
                    </div>
                </div>

                <div class="content-grid animate d-2">
                    
                    <div class="card">
                        <div class="card-label" style="margin-bottom: 15px;">Quick Management</div>
                        <div class="actions-grid">
                            <button class="action-btn">
                                <div class="action-icon"><i class="fa-solid fa-user-plus"></i></div>
                                <div class="action-text">New<br>Student</div>
                            </button>
                            <button class="action-btn">
                                <div class="action-icon" style="color: var(--success);"><i class="fa-solid fa-coins"></i></div>
                                <div class="action-text">Collect<br>Fees</div>
                            </button>
                            <button class="action-btn">
                                <div class="action-icon" style="color: #9333ea;"><i class="fa-solid fa-bullhorn"></i></div>
                                <div class="action-text">Send<br>SMS</div>
                            </button>
                            <button class="action-btn">
                                <div class="action-icon" style="color: var(--danger);"><i class="fa-solid fa-file-waveform"></i></div>
                                <div class="action-text">Exam<br>Report</div>
                            </button>
                        </div>
                    </div>

                    <div class="card">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                            <div class="card-label">Live Activity</div>
                            <div style="display: flex; gap: 5px; align-items: center;">
                                <div style="width: 8px; height: 8px; background: var(--success); border-radius: 50%; animation: pulse 1s infinite;"></div>
                                <span style="font-size: 10px; font-weight: bold; color: var(--success);">LIVE</span>
                            </div>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <div class="list-item">
                                <div class="item-left">
                                    <img src="https://ui-avatars.com/api/?name=R+S&background=random" style="width: 32px; height: 32px; border-radius: 50%;">
                                    <div class="item-info">
                                        <h4>Rohan Sharma</h4>
                                        <p>Paid Tuition Fee (Class 10)</p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-size: 11px; font-weight: bold; color: var(--success);">+₹4,500</div>
                                    <div style="font-size: 9px; color: var(--text-light);">2m ago</div>
                                </div>
                            </div>

                            <div class="list-item">
                                <div class="item-left">
                                    <div style="width: 32px; height: 32px; background: #fff7ed; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: orange;">
                                        <i class="fa-solid fa-bus" style="font-size: 12px;"></i>
                                    </div>
                                    <div class="item-info">
                                        <h4>Bus Route 05</h4>
                                        <p>Arrived at Campus</p>
                                    </div>
                                </div>
                                <div style="font-size: 9px; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; color: var(--secondary);">On Time</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="banner animate d-3">
                    <div>
                        <div style="font-size: 14px; font-weight: 800;">Academic Calendar</div>
                        <div style="font-size: 11px; opacity: 0.8;">Next: Sports Day (24th Jan)</div>
                    </div>
                    <button class="banner-btn">VIEW FULL</button>
                </div>

            </div>
        </main>
    </div>
    
    <?php 
    //include("footer.php"); 
    ?>

    <script>
        // 1. Loading Logic
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('preloader').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('preloader').style.display = 'none';
                    startCounting(); // Start animations only after load
                }, 500);
            }, 800);
        });
 
        // 3. Number Counter Animation
        function startCounting() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                counter.innerText = '0';
                const target = +counter.getAttribute('data-target');
                const updateCounter = () => {
                    const c = +counter.innerText;
                    const increment = target / 30; // Speed
                    if (c < target) {
                        counter.innerText = `${Math.ceil(c + increment)}`;
                        setTimeout(updateCounter, 30);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCounter();
            });
        }
    </script>
</body>
</html>