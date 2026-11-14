<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Buzz - Social Screen</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6c5ce7;
            --heart: #ff7675;
            --bg: #f8fafc;
            --white: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 0;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* Stories Section */
        .stories-container {
            display: flex;
            gap: 15px;
            padding: 20px 15px;
            overflow-x: auto;
            scrollbar-width: none;
            background: var(--white);
        }
        .stories-container::-webkit-scrollbar { display: none; }

        .story-item { text-align: center; min-width: 75px; }
        .story-ring {
            width: 68px; height: 68px;
            border-radius: 50%;
            padding: 3px;
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            display: flex; align-items: center; justify-content: center;
        }
        .story-img {
            width: 100%; height: 100%;
            border-radius: 50%;
            border: 2px solid white;
            object-fit: cover;
        }
        .story-item span { font-size: 10px; font-weight: 600; color: #64748b; margin-top: 5px; display: block; }

        /* Appreciation Badge Section */
        .star-banner {
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            margin: 15px; border-radius: 25px;
            padding: 20px; color: white;
            display: flex; align-items: center; gap: 15px;
            box-shadow: 0 10px 20px rgba(108, 92, 231, 0.2);
            animation: bounceIn 0.8s ease;
        }
        .star-avatar { width: 50px; height: 50px; border-radius: 15px; border: 2px solid #ffd700; }

        /* Social Posts */
        .post-card {
            background: var(--white);
            margin: 20px 15px;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            animation: slideUp 0.6s ease forwards;
            opacity: 0;
        }

        .post-header {
            padding: 15px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .user-info { display: flex; align-items: center; gap: 10px; }
        .user-info b { font-size: 13px; }
        .user-info p { margin: 0; font-size: 11px; color: #94a3b8; }

        .post-image-container { position: relative; width: 100%; aspect-ratio: 4/3; }
        .post-img { width: 100%; height: 100%; object-fit: cover; }

        .post-actions {
            padding: 15px 20px;
            display: flex; gap: 20px; font-size: 18px;
        }
        .action-btn { transition: 0.3s; cursor: pointer; }
        .action-btn:active { transform: scale(1.3); }
        .action-btn.liked { color: var(--heart); }

        .post-caption { padding: 0 20px 20px 20px; font-size: 12px; line-height: 1.6; }

        /* Animations */
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes bounceIn { 0% { transform: scale(0.9); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
    </style>
</head>
<body>

    <div class="stories-container">
        <div class="story-item">
            <div class="story-ring" style="background: #e2e8f0;"><div class="story-img" style="background:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; color:var(--primary);"><i class="fas fa-plus"></i></div></div>
            <span>Your Post</span>
        </div>
        <div class="story-item">
            <div class="story-ring"><img src="https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?q=80&w=100&auto=format&fit=crop" class="story-img"></div>
            <span>Yoga Day</span>
        </div>
        <div class="story-item">
            <div class="story-ring"><img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=100&auto=format&fit=crop" class="story-img"></div>
            <span>Quiz Final</span>
        </div>
        <div class="story-item">
            <div class="story-ring"><img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?q=80&w=100&auto=format&fit=crop" class="story-img"></div>
            <span>Art Fest</span>
        </div>
    </div>

    <div class="star-banner">
        <img src="https://ui-avatars.com/api/?name=Piyush+Vyas&background=fff&color=6c5ce7" class="star-avatar">
        <div style="flex:1;">
            <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; opacity: 0.8;">Star Performance</div>
            <div style="font-size: 15px; font-weight: 700;">Student of the Week</div>
            <div style="font-size: 11px;">Congratulations, Piyush Vyas!</div>
        </div>
        <i class="fas fa-crown fa-2x" style="color: #ffd700;"></i>
    </div>

    <div class="post-card" style="animation-delay: 0.2s;">
        <div class="post-header">
            <div class="user-info">
                <div style="width:35px; height:35px; border-radius:50%; background:var(--primary); color:white; display:flex; align-items:center; justify-content:center; font-size:12px;">M</div>
                <div>
                    <b>Marudhar Admin</b>
                    <p>2 hours ago</p>
                </div>
            </div>
            <i class="fas fa-ellipsis-h" style="color:#cbd5e1;"></i>
        </div>
        <div class="post-image-container">
            <img src="https://img.freepik.com/premium-photo/crowd-market_1048944-6420883.jpg?ga=GA1.1.770284485.1764749469&semt=ais_hybrid&w=740&q=80" class="post-img" alt="School Event">
        </div>
        <div class="post-actions">
            <i class="far fa-heart action-btn" onclick="toggleLike(this)"></i>
            <i class="far fa-comment action-btn"></i>
            <i class="far fa-paper-plane action-btn"></i>
        </div>
        <div class="post-caption">
            <b>Marudhar Admin</b> Memories from the Annual Science Exhibition 2025. It was amazing to see our young scientists in action! 🧪✨ #ScienceExhibition #SchoolLife
        </div>
    </div>

    <div class="post-card" style="animation-delay: 0.4s;">
        <div class="post-header">
            <div class="user-info">
                <div style="width:35px; height:35px; border-radius:50%; background:#2ecc71; color:white; display:flex; align-items:center; justify-content:center; font-size:12px;">S</div>
                <div>
                    <b>Sports Dept</b>
                    <p>Yesterday</p>
                </div>
            </div>
            <i class="fas fa-ellipsis-h" style="color:#cbd5e1;"></i>
        </div>
        <div class="post-image-container">
            <img src="https://images.unsplash.com/photo-1574629810360-7efbbe195018?q=80&w=800&auto=format&fit=crop" class="post-img" alt="Sports Day">
        </div>
        <div class="post-actions">
            <i class="far fa-heart action-btn" onclick="toggleLike(this)"></i>
            <i class="far fa-comment action-btn"></i>
            <i class="far fa-paper-plane action-btn"></i>
        </div>
        <div class="post-caption">
            <b>Sports Dept</b> Registration for Inter-School Football starts tomorrow! Are you ready to win? ⚽🏆
        </div>
    </div>
    
      <?php //include("footer.php"); ?>

    <script>
        function toggleLike(btn) {
            btn.classList.toggle('fas');
            btn.classList.toggle('far');
            btn.classList.toggle('liked');
        }
    </script>
</body>
</html>