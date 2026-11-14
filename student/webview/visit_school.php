<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultimate School Profile Modal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --accent: #6366f1;
            --accent-light: #818cf8;
            --bg-dark: #0f172a;
            --glass: rgba(255, 255, 255, 0.95);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: var(--bg-dark);
            background-image: radial-gradient(circle at 20% 20%, #4338ca 0%, transparent 40%),
                              radial-gradient(circle at 80% 80%, #a855f7 0%, transparent 40%);
            height: 100vh;
            overflow: hidden; /* No scroll on body */
            display: flex;
            justify-content: center;
        }

        /* Modal Layout */
        .modal-container {
            margin-top: 40px; /* Top Spacing as requested */
            width: 90%;
            max-width: 380px;
            height: auto;
            max-height: 90vh; /* Fits within screen */
            animation: cardEntrance 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        @keyframes cardEntrance {
            from { opacity: 0; transform: scale(0.9) translateY(30px); filter: blur(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        }

        .profile-card {
            background: var(--glass);
            border-radius: 35px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.4);
            position: relative;
        }

        /* Top Header */
        .card-header {
            background: linear-gradient(135deg, #6366f1, #a855f7);
            height: 90px;
            display: flex;
            justify-content: space-between;
            padding: 15px 20px;
        }

        .header-icon {
            color: white;
            font-size: 1.2rem;
            opacity: 0.8;
            transition: 0.3s;
            cursor: pointer;
        }
        .header-icon:hover { opacity: 1; transform: scale(1.2); }

        /* Floating Logo Area */
        .logo-wrap {
            position: absolute;
            top: 45px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 5;
        }

        .logo-inner {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 28px;
            padding: 4px;
            box-shadow: 0 12px 24px rgba(0,0,0,0.15);
            transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .logo-inner:hover {
            transform: rotate(10deg) scale(1.1);
        }

        .logo-inner img {
            width: 100%;
            height: 100%;
            border-radius: 24px;
            object-fit: cover;
        }

        /* Content Area */
        .content {
            padding: 55px 20px 20px;
            text-align: center;
        }

        .title {
            font-weight: 800;
            font-size: 1.4rem;
            color: #1e293b;
            letter-spacing: -0.5px;
        }

        .loc-text {
            font-size: 0.8rem;
            color: #64748b;
            margin-bottom: 15px;
            display: block;
        }

        /* Optimized Contact Stack (Compact) */
        .action-stack {
            display: grid;
            gap: 8px;
        }

        .action-link {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            background: #f1f5f9;
            border-radius: 18px;
            text-decoration: none;
            color: #334155;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.3s;
            border: 1px solid transparent;
        }

        .action-link:hover {
            background: white;
            border-color: var(--accent);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.1);
        }

        .icon-box {
            width: 30px;
            height: 30px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
            font-size: 12px;
        }

        /* Compact Map */
        .map-mini {
            margin-top: 15px;
            height: 100px;
            border-radius: 20px;
            overflow: hidden;
            border: 2px solid white;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
        }

        /* Bottom Button */
        .btn-main {
            margin-top: 15px;
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 16px;
            background: #1e293b;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-main:hover {
            background: var(--accent);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.4);
        }

        /* Subtle Item Animation */
        .reveal-item {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }
        @keyframes fadeIn { to { opacity: 1; } }

    </style>
</head>
<body>

    <div class="modal-container">
        <div class="profile-card">
            
            <div class="card-header">
                <div style="display: flex; gap: 15px;">
                    <i class="fab fa-facebook-f header-icon"></i>
                    <i class="fab fa-instagram header-icon"></i>
                </div>
                <div style="display: flex; gap: 15px;">
                    <i class="fab fa-youtube header-icon"></i>
                    <i class="fab fa-whatsapp header-icon"></i>
                </div>
            </div>

            <div class="logo-wrap">
                <div class="logo-inner">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT6-6R-pU0Dq7R4vI7S2-1wA_n-8G9D-X7uWQ&s" alt="School">
                </div>
            </div>

            <div class="content">
                <h2 class="title reveal-item" style="animation-delay: 0.2s;">Cambridge School</h2>
                <span class="loc-text reveal-item" style="animation-delay: 0.3s;">
                    <i class="fas fa-location-arrow" style="color:var(--accent)"></i> Sodala, Jaipur, Rajasthan
                </span>

                <div class="action-stack">
                    <a href="tel:8982276988" class="action-link reveal-item" style="animation-delay: 0.4s;">
                        <div class="icon-box" style="background: #10b981;"><i class="fas fa-phone"></i></div>
                        <span>+91 89822 76988</span>
                        <i class="fas fa-chevron-right" style="margin-left: auto; font-size: 10px; opacity: 0.4;"></i>
                    </a>

                    <a href="#" class="action-link reveal-item" style="animation-delay: 0.5s;">
                        <div class="icon-box" style="background: #ef4444;"><i class="fas fa-envelope"></i></div>
                        <span>info@cambridge.com</span>
                        <i class="fas fa-chevron-right" style="margin-left: auto; font-size: 10px; opacity: 0.4;"></i>
                    </a>

                    <a href="#" class="action-link reveal-item" style="animation-delay: 0.6s;">
                        <div class="icon-box" style="background: #3b82f6;"><i class="fas fa-globe"></i></div>
                        <span>www.cambridgejaipur.com</span>
                        <i class="fas fa-chevron-right" style="margin-left: auto; font-size: 10px; opacity: 0.4;"></i>
                    </a>
                </div>

                <div class="map-mini reveal-item" style="animation-delay: 0.7s;">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3557.5123456789!2d75.7890!3d26.9124!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjbCsDU0JzQ0LjYiTiA3NcKwNDcnMjAuNCJF!5e0!3m2!1sen!2sin!4v1625000000000" 
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
                    </iframe>
                </div>

                <button class="btn-main reveal-item" style="animation-delay: 0.8s;">Get Admission Info</button>
            </div>
        </div>
    </div>

</body>
</html>