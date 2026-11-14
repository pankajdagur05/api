<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrackMyBus - Live Tracking</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4338ca;
            --accent: #10b981;
            --danger: #ef4444;
            --bg: #f1f5f9;
            --card: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 0;
            color: #1e293b;
        }

        /* Live Map Mockup */
        .map-container {
            width: 100%;
            height: 45vh;
            background: #e5e7eb url('https://api.mapbox.com/styles/v1/mapbox/streets-v11/static/75.81,26.91,12/800x600?access_token=YOUR_TOKEN') no-repeat center;
            background-size: cover;
            position: relative;
        }

        .bus-marker {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            background: var(--primary);
            color: white;
            padding: 10px; border-radius: 50%;
            box-shadow: 0 0 20px rgba(67, 56, 202, 0.5);
            animation: pulse-bus 2s infinite;
        }

        /* Floating Info Card */
        .tracking-sheet {
            background: var(--card);
            border-radius: 35px 35px 0 0;
            padding: 25px;
            margin-top: -40px;
            position: relative;
            box-shadow: 0 -10px 30px rgba(0,0,0,0.05);
            min-height: 55vh;
            animation: slideUp 0.7s ease-out;
            margin-bottom: 77px;
        }

        .eta-badge {
            background: #ecfdf5;
            color: var(--accent);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px; font-weight: 700;
            display: inline-block;
        }

        .bus-info-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-top: 15px;
        }

        /* Stop Timeline */
        .timeline {
            margin-top: 30px;
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute; left: 5px; top: 5px;
            width: 2px; height: 80%;
            background: #e2e8f0;
        }

        .stop-item { position: relative; margin-bottom: 25px; }
        .stop-dot {
            position: absolute; left: -30px; top: 5px;
            width: 12px; height: 12px; border-radius: 50%;
            background: #cbd5e1; border: 2px solid white;
        }
        .stop-item.active .stop-dot { background: var(--primary); box-shadow: 0 0 10px var(--primary); }
        .stop-item.active b { color: var(--primary); }

        /* Action Buttons */
        .action-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 15px; margin-top: 30px;
        }
        .btn-call {
            padding: 15px; border-radius: 20px;
            border: none; font-weight: 700;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: 0.3s;
        }
        .btn-driver { background: #eef2ff; color: var(--primary); }
        .btn-sos { background: #fef2f2; color: var(--danger); }

        /* Animations */
        @keyframes pulse-bus {
            0% { box-shadow: 0 0 0 0 rgba(67, 56, 202, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(67, 56, 202, 0); }
            100% { box-shadow: 0 0 0 0 rgba(67, 56, 202, 0); }
        }
        @keyframes slideUp { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</head>
<body>

    <div class="map-container">
        <div class="bus-marker">
            <i class="fas fa-bus"></i>
        </div>
        <div style="position: absolute; bottom: 60px; left: 20px; background: white; padding: 10px 15px; border-radius: 15px; font-size: 11px; font-weight: 700; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-tachometer-alt" style="color:var(--accent);"></i> Speed: 35 km/h
        </div>
    </div>

    <div class="tracking-sheet">
        <div style="width: 40px; height: 4px; background: #e2e8f0; border-radius: 10px; margin: 0 auto 20px;"></div>
        
        <div class="eta-badge">Arriving in 8 mins</div>
        
        <div class="bus-info-header">
            <div>
                <h2 style="margin:0; font-size: 22px;">Route B-42</h2>
                <p style="margin:5px 0 0 0; color: #64748b; font-size: 13px;">Marudhar School &rarr; Vaishali Nagar</p>
            </div>
            <div style="text-align: right;">
                <b style="display:block; font-size: 14px;">RJ 14 PC 55xx</b>
                <span style="font-size: 11px; color: #94a3b8;">Bus Number</span>
            </div>
        </div>

        <div class="timeline">
            <div class="stop-item">
                <div class="stop-dot"></div>
                <b style="font-size: 14px; color: #94a3b8;">Main Gate - School</b>
                <p style="margin:0; font-size: 11px; color: #cbd5e1;">Departed 02:45 PM</p>
            </div>
            <div class="stop-item active">
                <div class="stop-dot"></div>
                <b style="font-size: 14px;">Mansarovar Metro</b>
                <p style="margin:0; font-size: 11px; color: var(--accent);">On the way (Next Stop)</p>
            </div>
            <div class="stop-item">
                <div class="stop-dot"></div>
                <b style="font-size: 14px; color: #94a3b8;">Vaishali Circle (Your Stop)</b>
                <p style="margin:0; font-size: 11px; color: #cbd5e1;">ETA 03:15 PM</p>
            </div>
        </div>

        <div class="action-grid">
            <button class="btn-call btn-driver">
                <i class="fas fa-phone-alt"></i> Call Driver
            </button>
            <button class="btn-call btn-sos">
                <i class="fas fa-exclamation-triangle"></i> SOS Alert
            </button>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; gap: 12px;">
            <img src="https://ui-avatars.com/api/?name=Rajesh+Kumar&background=4338ca&color=fff" style="width: 45px; height: 45px; border-radius: 50%;">
            <div style="flex:1;">
                <b style="font-size: 13px;">Rajesh Kumar</b>
                <p style="margin:0; font-size: 10px; color: #64748b;">Verified Driver • 4.9 <i class="fas fa-star" style="color: #f1c40f;"></i></p>
            </div>
            <i class="fas fa-shield-alt" style="color: var(--accent); font-size: 20px;"></i>
        </div>
    </div>
    
      <?php //include("footer.php"); ?>

</body>
</html>