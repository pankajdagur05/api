<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary: #4f46e5;
            --danger: #ef4444;
            --white: #ffffff;
            --bg-soft: #f1f5f9;
        }

        /* Profile Drawer (Hidden by default) */
        .profile-drawer {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4); backdrop-filter: blur(10px);
            z-index: 2000; display: none; align-items: flex-end;
        }
        .profile-drawer.active { display: flex; }

        .drawer-content {
    background: white;
    width: 100%;
    border-radius: 40px 40px 0 0;
    padding: 24px 20px;
    transform: translateY(100%);
    transition: 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    max-height: 100vh;
    overflow-y: auto;
    height: 75vh;
}
        .profile-drawer.active .drawer-content { transform: translateY(0); }

        /* Profile Top Info */
        .drawer-header { text-align: center; margin-bottom: 20px; }
        .big-avatar {
            width: 100px; height: 100px; border-radius: 35px;
            border: 4px solid var(--primary); padding: 4px; margin: 0 auto 15px;
        }
        .big-avatar img { width: 100%; height: 100%; border-radius: 30px; object-fit: cover; }

        /* Data Grid */
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
        .info-tile { background: var(--bg-soft); padding: 15px; border-radius: 20px; }
        .info-tile span { font-size: 10px; color: #94a3b8; font-weight: 700; text-transform: uppercase; }
        .info-tile b { display: block; font-size: 14px; color: #1e293b; margin-top: 5px; }

        /* Action Buttons */
        .action-list { display: flex; flex-direction: column; gap: 12px; }
        .action-item {
            display: flex; align-items: center; gap: 15px; padding: 18px;
            border-radius: 20px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s;
        }
        .btn-pass { background: #eef2ff; color: var(--primary); }
        .btn-logout { background: #fef2f2; color: var(--danger); }
        .action-item:active { transform: scale(0.97); }

        /* Re-using previous Header Styles */
        .header-main { position: sticky; top:0; z-index:1000; background:rgba(255,255,255,0.9); backdrop-filter:blur(20px); padding:3px 9px 10px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(0,0,0,0.05); }
        .profile-container { display:flex; align-items:center; gap:12px; cursor:pointer; padding:5px 5px 5px 15px; background:#f1f5f9; border-radius:50px; }
        .avatar-circle { width:40px; height:40px; border-radius:50%; border:2px solid var(--primary); padding:2px; background:white; }
        .avatar-circle img { width:100%; height:100%; border-radius:50%; }
        
        /* Dropdown Style from previous step */
        .student-dropdown { position: absolute; top: 75px; right: 20px; width: 250px; background: white; border-radius: 24px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); padding: 15px; display: none; z-index: 1001; }
        .student-dropdown.active { display: block; }
        .student-item { display: flex; align-items: center; gap: 12px; padding: 12px; border-radius: 15px; transition: 0.3s; }
        .student-item.selected { background: #eef2ff; }
    </style>
</head>
<body>

    <header class="header-main">
        <div class="school-info">
            <h1 style="margin:0; font-size:18px; font-weight:800;">Marudhar School</h1>
        </div>
        <div class="profile-container" onclick="toggleDropdown()">
            <span style="font-size:13px; font-weight:700;" id="currName">Piyush Vyas</span>
            <div class="avatar-circle">
                <img id="currImg" src="https://ui-avatars.com/api/?name=Piyush+Vyas&background=4f46e5&color=fff">
            </div>
        </div>

        <div class="student-dropdown" id="studentDropdown">
            <div class="student-item selected" onclick="selectStudent('Piyush Vyas', '10th-A', this)">
                <img src="https://ui-avatars.com/api/?name=Piyush+Vyas&background=4f46e5&color=fff" style="width:30px; border-radius:50%">
                <b style="font-size:13px">Piyush Vyas</b>
            </div>
            <div class="student-item" onclick="selectStudent('Aryan Vyas', '8th-C', this)">
                <img src="https://ui-avatars.com/api/?name=Aryan+Vyas&background=10b981&color=fff" style="width:30px; border-radius:50%">
                <b style="font-size:13px">Aryan Vyas</b>
            </div>
            <hr style="border:0; border-top:1px solid #eee; margin:10px 0;">
            <div class="student-item" style="color:var(--primary); justify-content:center;" onclick="openProfile()">
                <i class="fas fa-user-edit"></i> <b>View Full Profile</b>
            </div>
        </div>
    </header>

    <div class="profile-drawer" id="profileDrawer" onclick="closeProfile()">
        <div class="drawer-content" onclick="event.stopPropagation()">
            <div style="width: 40px; height: 4px; background: #e2e8f0; border-radius: 10px; margin: 0 auto 25px;"></div>
            
            <div class="drawer-header">
                <div class="big-avatar">
                    <img id="p_avatar" src="https://ui-avatars.com/api/?name=Piyush+Vyas&background=4f46e5&color=fff">
                </div>
                <h2 style="margin:0; font-size:20px;" id="p_name">Piyush Vyas</h2>
                <p style="margin:5px 0 0; color:#64748b; font-size:13px;">Student ID: #MS-2025-084</p>
            </div>

            <div class="info-grid">
                <div class="info-tile"><span>Father's Name</span><b>Mr. Ramesh Vyas</b></div>
                <div class="info-tile"><span>Standard</span><b id="p_class">Class 10th-A</b></div>
                <div class="info-tile"><span>Roll No</span><b>24</b></div>
                <div class="info-tile"><span>Blood Group</span><b>B+ Positive</b></div>
            </div>

            <div class="action-list">
                <div class="action-item btn-pass">
                    <i class="fas fa-key"></i> Change Password
                </div>
                <div class="action-item btn-logout" onclick="confirmLogout()">
                    <i class="fas fa-sign-out-alt"></i> Logout from App
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDropdown() { document.getElementById('studentDropdown').classList.toggle('active'); }
        
        function openProfile() {
            document.getElementById('studentDropdown').classList.remove('active');
            document.getElementById('profileDrawer').classList.add('active');
            document.body.style.overflow = 'hidden'; // Stop scroll
        }

        function closeProfile() {
            document.getElementById('profileDrawer').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        function selectStudent(name, cls, el) {
            document.getElementById('currName').innerText = name;
            document.getElementById('p_name').innerText = name;
            document.getElementById('p_class').innerText = "Class " + cls;
            
            let imgUrl = `https://ui-avatars.com/api/?name=${name.replace(' ','+')}&background=${name.includes('Aryan')?'10b981':'4f46e5'}&color=fff`;
            document.getElementById('currImg').src = imgUrl;
            document.getElementById('p_avatar').src = imgUrl;

            document.querySelectorAll('.student-item').forEach(i => i.classList.remove('selected'));
            el.classList.add('selected');
            setTimeout(() => toggleDropdown(), 300);
        }

        function confirmLogout() {
            if(confirm("Are you sure you want to logout?")) {
                alert("Logging out...");
            }
        }
    </script>
</body>
</html>