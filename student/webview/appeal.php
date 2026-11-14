<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resolution Center</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6c5ce7;
            --success: #00b894;
            --pending: #f1c40f;
            --bg: #f8fafc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 15px;
            color: #2d3436;
        }

        .header { margin-bottom: 25px; }
        .header h2 { margin: 0; font-weight: 700; color: #130f40; }
        .header p { font-size: 12px; color: #636e72; }

        /* Form Card */
        .appeal-form {
            background: white;
            border-radius: 25px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            margin-bottom: 30px;
            animation: slideUp 0.6s ease;
        }

        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #636e72; }
        
        .type-chips { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: none; }
        .chip {
            padding: 8px 18px; border-radius: 12px; background: #f1f2f6;
            font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap; transition: 0.3s;
        }
        .chip.active { background: var(--primary); color: white; }

        textarea {
            width: 100%; border-radius: 15px; border: 1px solid #eee;
            padding: 12px; font-family: inherit; font-size: 13px; outline: none;
            background: #fafafa; transition: 0.3s; box-sizing: border-box;
        }
        textarea:focus { border-color: var(--primary); background: white; }

        .btn-submit {
            width: 100%; padding: 15px; border-radius: 15px; border: none;
            background: var(--primary); color: white; font-weight: 700;
            font-size: 14px; cursor: pointer; display: flex; align-items: center;
            justify-content: center; gap: 10px; transition: 0.3s;
        }
        .btn-submit:active { transform: scale(0.98); }

        /* Appeal History */
        .history-title { font-size: 16px; font-weight: 700; margin-bottom: 15px; }
        
        .appeal-card {
            background: white; border-radius: 20px; padding: 15px; margin-bottom: 15px;
            border-left: 5px solid var(--pending); position: relative;
            animation: fadeIn 0.8s ease;
        }

        .status-badge {
            position: absolute; top: 15px; right: 15px;
            padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 700;
        }

        .appeal-card h4 { margin: 0 0 5px 0; font-size: 14px; }
        .appeal-card p { margin: 0; font-size: 11px; color: #95a5a6; line-height: 1.4; }
        
        .timeline-date { font-size: 10px; color: #bdc3c7; margin-top: 10px; display: block; }

        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body>

    <div class="header">
        <h2>Raise an Appeal</h2>
        <p>Submit your requests or concerns to administration</p>
    </div>

    <div class="appeal-form">
        <div class="input-group">
            <label>Select Category</label>
            <div class="type-chips">
                <div class="chip active" onclick="selectChip(this)">Exam Marks</div>
                <div class="chip" onclick="selectChip(this)">Attendance</div>
                <div class="chip" onclick="selectChip(this)">Fee Query</div>
                <div class="chip" onclick="selectChip(this)">Discipline</div>
            </div>
        </div>

        <div class="input-group">
            <label>Your Message</label>
            <textarea rows="4" placeholder="Explain your concern in detail..."></textarea>
        </div>

        <button class="btn-submit" onclick="submitAppeal()">
            <i class="fas fa-paper-plane"></i> Send Request
        </button>
    </div>

    <div class="history-title">Recent Appeals</div>

    <div class="appeal-card" style="border-left-color: var(--pending);">
        <span class="status-badge" style="background:#fff9db; color:#f39c12;">Under Review</span>
        <h4>Marks Re-evaluation</h4>
        <p>Requesting re-check for Mathematics Half Yearly exam papers...</p>
        <span class="timeline-date">Submitted on 24 Dec, 2025</span>
    </div>

    <div class="appeal-card" style="border-left-color: var(--success);">
        <span class="status-badge" style="background:#e8faf0; color:var(--success);">Resolved</span>
        <h4>Attendance Correction</h4>
        <p>Marked absent on 12th Dec by mistake. Corrected to Present.</p>
        <span class="timeline-date">Resolved on 15 Dec, 2025</span>
    </div>

    <script>
        function selectChip(el) {
            document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
            el.classList.add('active');
        }

        function submitAppeal() {
            const btn = document.querySelector('.btn-submit');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-check-circle"></i> Sent Successfully';
                btn.style.background = 'var(--success)';
                alert("Your appeal has been submitted to the admin office!");
            }, 1500);
        }
    </script>
</body>
</html>