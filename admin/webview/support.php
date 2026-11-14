<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg: #f8faff;
            --primary: #0284c7;
            --text-main: #1e293b;
            --text-light: #64748b;
            --white: #ffffff;
            --accent-red: #ef4444;
            --accent-green: #22c55e;
            --success: #10b981;
            --danger: #ef4444; 
            --pending: #f59e0b;
            --text-dark: #1e293b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: var(--bg); color: var(--text-main); padding-bottom: 20px; }

        /* Header */
        .header { 
            padding: 20px; display: flex; justify-content: space-between; align-items: center; 
            background: var(--bg); position: sticky; top: 0; z-index: 10;
        }
        .header h1 { font-size: 22px; font-weight: 800; }
        .add-btn { width: 40px; height: 40px; border-radius: 50%; border: 1.5px solid #cbd5e1; display: flex; align-items: center; justify-content: center; cursor: pointer; background: white; transition: 0.3s; }
        .add-btn:active { transform: scale(0.9); }

        /* Best Tab UI */
        .tabs-container { padding: 0 20px 20px; display: flex; gap: 8px; }
        .tab-btn { 
            flex: 1; padding: 10px; border-radius: 12px; border: none; font-size: 12px; 
            font-weight: 700; background: #e2e8f0; color: var(--text-light); cursor: pointer;
            transition: all 0.3s ease;
        }
        .tab-btn.active { background: #075985; color: white; box-shadow: 0 4px 10px rgba(7, 89, 133, 0.2); }

        /* Ticket Card UI */
        .ticket-list { padding: 0 16px; display: flex; flex-direction: column; gap: 16px; }
        
        .ticket-card { 
            background: var(--white); border-radius: 20px; padding: 18px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02);
        }

        .card-head { display: flex; gap: 15px; margin-bottom: 12px; align-items: flex-start; }
        .icon-box { 
            width: 48px; height: 48px; border-radius: 14px; background: #fff1f2; 
            display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--accent-red);
            border: 1px solid #ffe4e6;
        }
        
        .title-info h3 { font-size: 15px; font-weight: 800; margin-bottom: 2px; }
        .sub-tag { font-size: 11px; color: var(--text-light); font-weight: 600; }
        .tag-outline { padding: 2px 8px; border: 1px solid #bae6fd; border-radius: 6px; color: #0369a1; margin-left: 5px; }

        .desc-text { font-size: 12px; color: #475569; line-height: 1.6; margin: 12px 0; }
        .meta-info { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; color: var(--text-light); font-size: 11px; font-weight: 600; }

        /* --- INLINE TIMELINE UI --- */
        .inline-timeline { display: flex; justify-content: space-between; position: relative; margin: 25px 0 15px 0; }
        .timeline-line { position: absolute; top: 12px; left: 0; width: 100%; height: 2px; background: #e2e8f0; z-index: 1; }
        .progress-line { position: absolute; top: 12px; left: 0; width: 33%; height: 2px; background: var(--accent-green); z-index: 1; transition: 0.5s; }

        .step { position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; width: 25%; }
        .step-circle { 
            width: 24px; height: 24px; border-radius: 50%; background: #e2e8f0; 
            display: flex; align-items: center; justify-content: center; font-size: 10px; 
            font-weight: 800; color: white; border: 3px solid white; transition: 0.3s;
        }
        .step.done .step-circle { background: var(--accent-green); }
        .step-label { font-size: 9px; font-weight: 700; color: var(--text-light); margin-top: 8px; text-align: center; }
        .step.done .step-label { color: var(--accent-green); }

        .view-details { 
            width: 100%; padding: 10px; border-radius: 12px; border: none; 
            background: #075985; color: white; font-weight: 700; font-size: 12px;
            cursor: pointer; margin-top: 10px; transition: 0.2s;
        }
        .view-details:active { transform: scale(0.98); background: #0c4a6e; }

        /* Overlay & Popup Styling (Same as previous but cleaner) */
         .overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px; }
        .popup { background: var(--white); width: 100%; max-width: 350px; border-radius: 24px; overflow: hidden; animation: popScale 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        
        @keyframes popScale { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .popup-header { padding: 18px; text-align: center; border-bottom: 1px solid #f1f5f9; position: relative; }
        .popup-header h3 { font-size: 16px; color: #0369a1; font-weight: 800; }

        .popup-body { padding: 25px 30px; }
        
        .status-badge { padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700; border: 1px solid #bae6fd; color: #0369a1; background: #f0f9ff; }
        
        .timeline-item { display: flex; gap: 20px; position: relative; padding-bottom: 25px; }
        .timeline-item:not(:last-child)::before { content: ''; position: absolute; left: 12px; top: 25px; width: 2px; height: 100%; background: #e2e8f0; }
        
        .dot { width: 26px; height: 26px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold; color: white; z-index: 2; }
        .dot.active { background: var(--success); box-shadow: 0 0 10px rgba(16, 185, 129, 0.4); }

        .time-content h4 { font-size: 13px; color: var(--text-dark); margin-bottom: 4px; }
        .time-content p { font-size: 11px; color: var(--text-light); line-height: 1.4; }

        .go-back { width: 100%; padding: 15px; background: var(--white); border: none; border-top: 1px solid #f1f5f9; color: #0369a1; font-weight: 800; font-size: 14px; cursor: pointer; }
    
    </style>
</head>
<body>

    <div class="header">
        <h1>Ticket Raise</h1>
        <div class="add-btn"><i class="fa-solid fa-plus"></i></div>
    </div>

    <div class="tabs-container">
        <button class="tab-btn active" onclick="filterTickets('pending')">Pending</button>
        <button class="tab-btn" onclick="filterTickets('confirmation')">Confirmation</button>
        <button class="tab-btn" onclick="filterTickets('solved')">Solved</button>
    </div>

    <div class="ticket-list" id="ticketList">
        
        <div class="ticket-card" data-status="pending">
            <div class="card-head">
                <div class="icon-box"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="title-info">
                    <h3>Support [ EP00956 ]</h3>
                    <p class="sub-tag">Technical <span class="tag-outline">Found Error</span></p>
                </div>
            </div>
            
            <p class="desc-text"><b>Description :</b> While edit amount in student ledger the remark gets removed automatically.</p>
            
            <div class="meta-info">
                <span><i class="fa-regular fa-clock"></i> 20 Apr 2025</span>
            </div>

            <div class="inline-timeline">
                <div class="timeline-line"></div>
                <div class="progress-line" style="width: 33%;"></div> <div class="step done"><div class="step-circle">1</div><p class="step-label">Ticket Raise</p></div>
                <div class="step done"><div class="step-circle">2</div><p class="step-label">Transfer Dept</p></div>
                <div class="step"><div class="step-circle">3</div><p class="step-label">Expected Date</p></div>
                <div class="step"><div class="step-circle">4</div><p class="step-label">Testing Mode</p></div>
            </div>

            <button class="view-details" onclick="openTimeline()">View Details</button>
        </div>

    </div>

    <div class="overlay" id="timelineOverlay">
        <div class="popup">
            <div class="popup-header">
                <h3>Tracking Report</h3>
            </div>
            <div class="popup-body">
                <div class="timeline-item">
                    <div class="dot active">1</div>
                    <div class="time-content">
                        <h4>Allotment</h4>
                        <p>Transfer To David Mamnani by EDPLUS AI On 20 Apr 2025 , 05:30 AM</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="dot">2</div>
                    <div class="time-content">
                        <h4>Timeline</h4>
                        <p>Expected resolve date will be updated soon.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="dot">3</div>
                    <div class="time-content">
                        <h4>Development</h4>
                        <p>Not started yet.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="dot">4</div>
                    <div class="time-content">
                        <h4>Tested & Solved</h4>
                        <p>Final quality check.</p>
                    </div>
                </div>
            </div>
            <button class="go-back" onclick="closeTimeline()">Go Back</button>
        </div>
    </div>

    <script>
        function filterTickets(status) {
            // Tab switching logic
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            // Yahan aap tickets filter karne ka logic add kar sakte hain
        }

       function openTimeline() {
            document.getElementById('timelineOverlay').style.display = 'flex';
        }
        function closeTimeline() {
            document.getElementById('timelineOverlay').style.display = 'none';
        }
    </script>
</body>
</html>