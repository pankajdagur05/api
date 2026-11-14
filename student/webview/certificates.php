<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Achievements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
    
    <style>
        :root {
            --gold: #D4AF37;
            --silver: #C0C0C0;
            --bronze: #CD7F32;
            --primary: #4834d4;
            --bg: #F0F2F5;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 20px;
            color: #2D3436;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 { margin: 0; font-weight: 700; color: #130f40; }
        .header p { font-size: 13px; color: #686de0; }

        /* Certificate Card Style */
        .cert-card {
            background: white;
            border-radius: 25px;
            overflow: hidden;
            margin-bottom: 25px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            position: relative;
            transition: transform 0.3s ease;
            animation: slideUp 0.6s ease-out forwards;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* The 'Certificate' Visual Part */
        .cert-visual {
            height: 180px;
            background: linear-gradient(135deg, #1e3799, #4834d4);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            color: white;
            text-align: center;
            border-bottom: 5px solid var(--gold);
        }

        .cert-visual i {
            font-size: 42px;
            opacity: 0.15;
            position: absolute;
        }

        .badge-icon {
            z-index: 1;
            background: white;
            color: var(--gold);
            width: 60px; height: 60px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            border: 2px solid var(--gold);
        }

        /* Content Part */
        .cert-info {
            padding: 20px;
            text-align: center;
        }

        .cert-info h3 { margin: 0; font-size: 18px; color: #130f40; }
        .cert-info p { font-size: 12px; color: #636e72; margin: 8px 0 15px 0; }

        .cert-meta {
            display: flex;
            justify-content: space-around;
            border-top: 1px solid #f1f1f1;
            padding-top: 15px;
            margin-top: 10px;
        }

        .meta-item { text-align: center; }
        .meta-item span { display: block; font-size: 10px; color: #999; text-transform: uppercase; }
        .meta-item b { font-size: 12px; color: #2d3436; }

        /* Action Buttons */
        .action-row {
            display: flex;
            gap: 10px;
            padding: 0 20px 20px 20px;
        }

        .btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            border: none;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s;
        }

        .btn-download { background: #f1f2f6; color: #2d3436; }
        .btn-share { background: #25D366; color: white; }
        .btn:active { transform: scale(0.95); }

        /* Confetti Button Float */
        .celebrate-fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--gold);
            color: white;
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
            border: none; z-index: 100;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Wall of Fame</h2>
        <p>Celebrating your achievements & milestones</p>
    </div>

    <div class="cert-card">
        <div class="cert-visual">
            <i class="fas fa-award"></i>
            <div class="badge-icon">
                <i class="fas fa-star"></i>
            </div>
        </div>
        <div class="cert-info">
            <h3>Academic Excellence</h3>
            <p>For securing 1st Position in the 1st Terminal Examination of Class 10-A.</p>
            
            <div class="cert-meta">
                <div class="meta-item">
                    <span>Issued Date</span>
                    <b>20 Oct 2025</b>
                </div>
                <div class="meta-item">
                    <span>Category</span>
                    <b>Academic</b>
                </div>
            </div>
        </div>
        <div class="action-row">
            <button class="btn btn-download"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-share"><i class="fab fa-whatsapp"></i> Share</button>
        </div>
    </div>

    <div class="cert-card" style="animation-delay: 0.2s;">
        <div class="cert-visual" style="background: linear-gradient(135deg, #f0932b, #eb4d4b); border-bottom-color: #f1c40f;">
            <i class="fas fa-running"></i>
            <div class="badge-icon" style="color: #f0932b;">
                <i class="fas fa-trophy"></i>
            </div>
        </div>
        <div class="cert-info">
            <h3>Sports Meet Champion</h3>
            <p>Winner of the 100m Sprint Gold Medal during the Annual Sports Meet 2025.</p>
            
            <div class="cert-meta">
                <div class="meta-item">
                    <span>Issued Date</span>
                    <b>15 Nov 2025</b>
                </div>
                <div class="meta-item">
                    <span>Category</span>
                    <b>Sports</b>
                </div>
            </div>
        </div>
        <div class="action-row">
            <button class="btn btn-download"><i class="fas fa-file-pdf"></i> PDF</button>
            <button class="btn btn-share"><i class="fab fa-whatsapp"></i> Share Proudly</button>
        </div>
    </div>

    <button class="celebrate-fab" onclick="fireConfetti()">
        <i class="fas fa-magic"></i>
    </button>

    <script>
        // Confetti Effect
        function fireConfetti() {
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#D4AF37', '#4834d4', '#25D366']
            });
        }

        // Auto-fire on load
        window.onload = function() {
            setTimeout(fireConfetti, 500);
        };
    </script>
</body>
</html>