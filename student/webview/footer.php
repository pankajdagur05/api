<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --active-bg: #eef2ff;
            --text-gray: #94a3b8;
            --white: #ffffff;
        }

        body { 
            margin: 0; 
            background: #f8fafc; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }

        /* The Liquid Footer Bar */
        .navigation {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 78px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(25px) saturate(180%);
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 0 10px;
            box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.04);
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 35px 35px 0 0;
            z-index: 9999;
        }

        .nav-link {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 45px;
            height: 90%;
            text-decoration: none;
            z-index: 1;
        }

        /* Floating Ed+ Hub */
        .ed-plus-center {
            position: relative;
            top: -30px;
            width: 70px;
            height: 70px;
            background: var(--white);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 15px 35px rgba(79, 70, 229, 0.25);
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .ed-inner-circle {
            width: 58px; height: 58px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 26px;
            transition: 0.3s;
        }

        /* Active Indicator Bubble */
        .indicator {
            position: absolute;
            top: 15px;
            width: 55px; height: 55px;
            background: var(--active-bg);
            border-radius: 20px;
            z-index: -1;
            transition: 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 0;
        }

        /* Icon Styling */
        .nav-link i {
            font-size: 22px;
            color: var(--text-gray);
            transition: 0.4s;
        }

        .nav-link span {
            font-size: 10px;
            color: var(--text-gray);
            margin-top: 5px;
            font-weight: 700;
            transition: 0.3s;
        }

        /* Active States */
        .nav-link.active i {
            color: var(--primary);
            transform: translateY(-4px);
        }

        .nav-link.active span {
            color: var(--primary);
            opacity: 1;
        }

        .nav-link.active ~ .indicator {
            opacity: 1;
        }

        /* Hover/Click Wave Effect */
        .nav-link:active i {
            transform: scale(0.8);
        }

        /* Ed+ Open Animation */
        .ed-plus-center:hover {
            transform: translateY(-5px) scale(1.05);
        }
        
    </style>
</head>
<body>

    <nav class="navigation">
        <a href="dashboard.php" class="nav-link active" onclick="moveNav(this, 0)">
            <i class="fas fa-grip-horizontal"></i>
            <span>Home</span>
        </a>

        <a href="reports.php" class="nav-link" onclick="moveNav(this, 1)">
            <i class="fas fa-chart-line"></i>
            <span>Report</span>
        </a>

 <a href="index.php" class="nav-link" onclick="moveNav(this, 1)">
        <div class="ed-plus-center" id="mainAction">
            <div class="ed-inner-circle">
                <i class="fas fa-plus"></i>
            </div>
        </div>
        </a>

        <a href="social.php" class="nav-link" onclick="moveNav(this, 3)">
            <i class="fas fa-comment-dots"></i>
            <span>Social</span>
        </a>

        <a href="transport_dashboard.php" class="nav-link" onclick="moveNav(this, 4)">
            <i class="fas fa-location-arrow"></i>
            <span>Track</span>
        </a>

        <div class="indicator" id="bubble"></div>
    </nav>

    <script>
        const bubble = document.getElementById('bubble');
        const links = document.querySelectorAll('.nav-link');

        function moveNav(el, index) {
            links.forEach(link => link.classList.remove('active'));
            el.classList.add('active');
            
            // Calculate Position for the Liquid Bubble
            const position = el.offsetLeft + (el.offsetWidth / 2) - (bubble.offsetWidth / 2);
            bubble.style.left = position + 'px';
            bubble.style.opacity = "1";
        }

        // Initialize at Home
        window.onload = () => moveNav(links[0], 0);

        // Center Button Animation
        document.getElementById('mainAction').onclick = function() {
            this.style.transform = "translateY(-10px) rotate(90deg)";
            setTimeout(() => {
                this.style.transform = "translateY(-5px) rotate(0deg)";
                alert("Smart Ed+ Hub Opening...");
            }, 300);
        };
    </script>
</body>
</html>