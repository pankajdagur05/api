<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Feedback</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #6c5ce7;
            --accent: #ff7675;
            --bg: #f0f3f7;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg);
            margin: 0; padding: 20px;
            color: #2d3436;
        }

        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { font-weight: 700; color: #130f40; margin: 0; }
        .header p { font-size: 13px; color: #636e72; }

        /* Teacher Profile Card */
        .teacher-select-card {
            background: white;
            border-radius: 25px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            animation: fadeIn 0.8s ease;
        }

        .teacher-avatar {
            width: 60px; height: 60px;
            border-radius: 20px;
            background: var(--primary);
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px; font-weight: 700;
        }

        /* Emoji Rating Section */
        .rating-box {
            background: white;
            border-radius: 30px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(108, 92, 231, 0.1);
        }

        .emoji-display {
            font-size: 60px;
            margin-bottom: 10px;
            display: block;
            transition: 0.3s transform cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .rating-stars {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .rating-stars i {
            font-size: 28px;
            color: #dfe6e9;
            cursor: pointer;
            transition: 0.2s;
        }

        .rating-stars i.active { color: #fdcb6e; transform: scale(1.2); }

        /* Quick Feedback Tags */
        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 30px;
        }

        .feedback-tag {
            padding: 8px 15px;
            border-radius: 12px;
            background: #f1f2f6;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: 2px solid transparent;
            transition: 0.3s;
        }

        .feedback-tag.selected {
            background: #eef2ff;
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Anonymous Toggle */
        .anonymous-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f8fafc;
            padding: 15px 20px;
            border-radius: 15px;
            margin-top: 25px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 40px; height: 22px;
        }

        .switch input { opacity: 0; width: 0; height: 0; }

        .slider {
            position: absolute; cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc; transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute; content: "";
            height: 16px; width: 16px; left: 3px; bottom: 3px;
            background-color: white; transition: .4s; border-radius: 50%;
        }

        input:checked + .slider { background-color: var(--primary); }
        input:checked + .slider:before { transform: translateX(18px); }

        .btn-submit-review {
            width: 100%;
            background: var(--primary);
            color: white;
            padding: 18px;
            border: none;
            border-radius: 20px;
            font-weight: 700;
            font-size: 15px;
            margin-top: 30px;
            box-shadow: 0 8px 20px rgba(108, 92, 231, 0.3);
            cursor: pointer;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

    <div class="header">
        <h2>Teacher Review</h2>
        <p>Your honest feedback helps us improve</p>
    </div>

    <div class="teacher-select-card">
        <div class="teacher-avatar">MK</div>
        <div>
            <h4 style="margin:0;">Mr. Manoj Kumar</h4>
            <p style="margin:0; font-size:12px; color:#636e72;">Mathematics Dept.</p>
        </div>
    </div>

    <div class="rating-box">
        <span class="emoji-display" id="emoji">😊</span>
        <h4 id="rating-text" style="margin:0;">Very Good</h4>
        
        <div class="rating-stars" id="starContainer">
            <i class="fas fa-star active" onclick="setRating(1)"></i>
            <i class="fas fa-star active" onclick="setRating(2)"></i>
            <i class="fas fa-star active" onclick="setRating(3)"></i>
            <i class="fas fa-star active" onclick="setRating(4)"></i>
            <i class="fas fa-star" onclick="setRating(5)"></i>
        </div>

        <div class="tags-container">
            <div class="feedback-tag" onclick="toggleTag(this)">Great Explanation</div>
            <div class="feedback-tag" onclick="toggleTag(this)">Friendly</div>
            <div class="feedback-tag" onclick="toggleTag(this)">Solving Doubts</div>
            <div class="feedback-tag" onclick="toggleTag(this)">Inspiring</div>
            <div class="feedback-tag" onclick="toggleTag(this)">Clear Voice</div>
        </div>

        <div class="anonymous-box">
            <div style="text-align: left;">
                <h5 style="margin:0; font-size:13px;">Stay Anonymous</h5>
                <p style="margin:0; font-size:10px; color:#94a3b8;">Teacher won't see your name</p>
            </div>
            <label class="switch">
                <input type="checkbox">
                <span class="slider"></span>
            </label>
        </div>

        <button class="btn-submit-review" onclick="submitReview()">
            Submit Feedback
        </button>
    </div>

    <script>
        const emojis = ['😢', '😕', '😊', '🤩', '🔥'];
        const texts = ['Poor', 'Average', 'Very Good', 'Excellent', 'Amazing!'];

        function setRating(n) {
            const stars = document.getElementById('starContainer').getElementsByTagName('i');
            const emojiEl = document.getElementById('emoji');
            const textEl = document.getElementById('rating-text');

            for (let i = 0; i < stars.length; i++) {
                if (i < n) stars[i].classList.add('active');
                else stars[i].classList.remove('active');
            }

            emojiEl.innerHTML = emojis[n-1];
            textEl.innerHTML = texts[n-1];
            
            // Animation effect
            emojiEl.style.transform = "scale(1.3)";
            setTimeout(() => emojiEl.style.transform = "scale(1)", 200);
        }

        function toggleTag(el) {
            el.classList.toggle('selected');
        }

        function submitReview() {
            alert("Thank you! Your anonymous review has been submitted.");
        }
    </script>
</body>
</html>