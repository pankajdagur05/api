<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #a78bfa; /* Soft Purple from your image */
            --bg-glass: rgba(255, 255, 255, 0.9);
            --icon-default: #94a3b8;
            --icon-active: #6366f1;
            --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        body { margin: 0; background: transparent; }

        .footer-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 400px;
            background: var(--bg-glass);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            height: 70px;
            border-radius: 21px;
            display: flex;
            align-items: center;
            justify-content: space-around;
            padding: 0 10px;
            box-shadow: var(--shadow);
            border: 1px solid rgba(255, 255, 255, 0.4);
            z-index: 10000;
        }

        .nav-item {
            text-decoration: none;
            color: var(--icon-default);
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: all 0.3s ease;
            position: relative;
            flex: 1;
        }

        .nav-item i {
            font-size: 20px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Active State */
        .nav-item.active i {
            color: var(--icon-active);
            transform: translateY(-2px);
        }

        /* Central Floating Button (The + Button) */
        .fab-button {
            position: relative;
            top: -25px; /* Lifts the button up */
            width: 60px;
            height: 60px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 8px 20px rgba(167, 139, 250, 0.4);
            border: 5px solid #f8fafc; /* Matches page background to create "cutout" look */
            transition: all 0.4s ease;
            cursor: pointer;
        }

        .fab-button:hover {
            transform: scale(1.1) rotate(90deg);
            box-shadow: 0 12px 25px rgba(167, 139, 250, 0.6);
        }

        .fab-button i {
            font-size: 22px;
            color: white;
        }

        /* Tooltip style label (Optional - shows on long press/hover) */
        .nav-item::after {
            content: attr(data-label);
            position: absolute;
            bottom: -15px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            opacity: 0;
            transition: 0.3s;
        }

        .nav-item.active::after {
            opacity: 1;
            bottom: 5px;
            color: var(--icon-active);
        }

        /* Animation for click */
        .nav-item:active i {
            transform: scale(0.8);
        }
    </style>
</head>
<body>

    <nav class="footer-nav">
        <a href="index.html" class="nav-item active">
            <i class="fa-solid fa-list-ul"></i>
        </a>

        <a href="files.html" class="nav-item" data-label="Docs">
            <i class="fa-regular fa-file-lines"></i>
        </a>

        <div class="fab-button" onclick="window.location.href='home.php'">
            <i class="fa-solid fa-plus"></i>
        </div>

        <a href="support.php" class="nav-item" data-label="Send">
            <i class="fa-regular fa-paper-plane"></i>
        </a>

        <a href="profile.php" class="nav-item" data-label="Profile">
            <i class="fa-regular fa-user"></i>
        </a>
    </nav>

</body>
</html>