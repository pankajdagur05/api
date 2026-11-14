<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Notes Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4834d4;
            --bg: #f6f9fc;
            --white: #ffffff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 20px;
            color: #2d3436;
        }

        .top-header { margin-bottom: 25px; }
        .top-header h2 { font-weight: 700; margin: 0; color: #130f40; }

        /* Subject Filter Chips */
        .filter-wrapper {
            display: flex;
            overflow-x: auto;
            gap: 10px;
            padding-bottom: 15px;
            margin-bottom: 10px;
            scrollbar-width: none;
        }
        .filter-wrapper::-webkit-scrollbar { display: none; }

        .chip {
            background: #dff9fb;
            color: #130f40;
            padding: 8px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            transition: 0.3s;
            cursor: pointer;
        }
        .chip.active {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 10px rgba(72, 52, 212, 0.3);
        }

        /* Note Card Design */
        .note-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
            min-height: 200px;
        }

        .note-item {
            background: var(--white);
            border-radius: 20px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid #edf2f7;
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideIn 0.4s ease-out forwards;
        }

        /* Hidden state for filtering */
        .note-item.hidden {
            display: none;
            opacity: 0;
            transform: translateY(10px) scale(0.95);
        }

        .note-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .teacher-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .teacher-img {
            width: 35px; height: 35px;
            border-radius: 10px;
            background: #f0f2f5;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-weight: bold;
        }

        .subject-tag {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .note-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #f1f1f1;
            padding-top: 12px; 
        }
        
        .note-content h3 {
    font-size: 15px;
    margin: 0;
    color: #2d3436;
}


        .action-btn {
            padding: 8px 15px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            background: var(--primary);
            color: white;
            display: flex; align-items: center; gap: 5px;
        }

        /* Empty State */
        #noData {
            display: none;
            text-align: center;
            padding: 40px 20px;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="top-header">
        <h2>Class Notes</h2>
    </div>

    <div class="filter-wrapper">
        <div class="chip active" onclick="filterNotes('all', this)">All</div>
        <div class="chip" onclick="filterNotes('maths', this)">Mathematics</div>
        <div class="chip" onclick="filterNotes('science', this)">Science</div>
        <div class="chip" onclick="filterNotes('english', this)">English</div>
    </div>

    <div class="note-container" id="noteContainer">
        
        <div class="note-item filter-note maths">
            <div class="note-header">
                <div class="teacher-info">
                    <div class="teacher-img">RK</div>
                    <div>
                        <div style="font-size: 12px; font-weight: 600;">Mr. Rajesh Kumar</div>
                        <div style="font-size: 10px; color: #999;">Today</div>
                    </div>
                </div>
                <span class="subject-tag" style="background:#fff7ed; color:#ea580c;">Maths</span>
            </div>
            <div class="note-content">
            <h3>Algebraic Formulas - Set A</h3>
            <p style="font-size:12px; color:#666;">Detailed derivations of quadratic equations and identities.</p>
            </div>
            <div class="note-actions">
                <span style="font-size:11px;"><i class="far fa-file-pdf"></i> 1.2 MB</span>
                <button class="action-btn"><i class="fas fa-download"></i> Download</button>
            </div>
        </div>

        <div class="note-item filter-note science">
            <div class="note-header">
                <div class="teacher-info">
                    <div class="teacher-img" style="background:#ebfbee; color:#27ae60;">AS</div>
                    <div>
                        <div style="font-size: 12px; font-weight: 600;">Ms. Anita Sharma</div>
                        <div style="font-size: 10px; color: #999;">Yesterday</div>
                    </div>
                </div>
                <span class="subject-tag" style="background:#ebfbee; color:#27ae60;">Science</span>
            </div>
            <div class="note-content">
            <h3>Plant Cell Structure</h3>
            <p style="font-size:12px; color:#666;">Handwritten diagram with labeling for chloroplast and wall.</p>
            </div>
            <div class="note-actions">
                <span style="font-size:11px;"><i class="far fa-file-image"></i> 0.8 MB</span> 
                    <button class="action-btn"><i class="fas fa-download"></i> Download</button>
            </div>
        </div>

        <div id="noData">
            <i class="fas fa-folder-open fa-3x" style="margin-bottom:10px;"></i>
            <p>No notes available for this subject yet.</p>
        </div>

    </div>

    <script>
        function filterNotes(category, element) {
            // Update chip colors
            let chips = document.getElementsByClassName('chip');
            for (let chip of chips) { chip.classList.remove('active'); }
            element.classList.add('active');

            // Filter logic
            let notes = document.getElementsByClassName('filter-note');
            let found = false;

            for (let note of notes) {
                note.classList.add('hidden'); // Sabko hide karo animation ke liye

                if (category === 'all' || note.classList.contains(category)) {
                    setTimeout(() => {
                        note.classList.remove('hidden');
                    }, 50);
                    found = true;
                }
            }

            // Show 'No Data' if nothing found
            document.getElementById('noData').style.display = found ? 'none' : 'block';
        }
    </script>
</body>
</html>