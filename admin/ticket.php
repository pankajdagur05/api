<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ticket Raise UI</title>
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background: #fff;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      border-bottom: 1px solid #ddd;
    }

    header h2 {
      margin: 0;
      font-size: 18px;
      color: #007193;
    }

    .add-btn {
      border: 1px solid #007193;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 22px;
      color: #007193;
      cursor: pointer;
    }

    .tabs {
      display: flex;
      justify-content: space-around;
      margin: 10px;
    }

    .tab {
      padding: 8px 13px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-right: 10px;
      font-size: 14px;
      display: flex;
      align-items: center;
      background: #f5f5f5;
      cursor: pointer;
    }

    .tab.active {
      background: #007193;
      color: #fff;
    }

    .tab span {
      margin-left: 5px;
      background: #e3e3e3;
      padding: 2px 8px;
      border-radius: 20px;
      font-size: 12px;
      color: #333;
    }

    .tab.active span {
      background: #005d75;
      color: #fff;
    }

    .popup {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0,0,0,0.4);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 999;
    }

    .popup.active {
      display: flex;
    }

    .popup-content {
          background: #fff;
    width: 95%;
    max-width: 500px;
    padding: 9px 13px;
    border-radius: 5px;
    position: relative;
    height: 98%;
    overflow-y: scroll;
    }

    .popup-header {
      display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    border-bottom: 1px solid #ccc;
    padding-bottom: 8px;
    }

    .close {
      font-size: 24px;
      cursor: pointer;
    }

    .section-title {
      font-weight: bold;
      margin: 14px 0 9px;
    }

    .option-boxes {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .radio-box {
      position: relative;
    }

    .radio-box input[type="radio"] {
      display: none;
    }

    .radio-box label {
      border: 1px solid #ccc;
    padding: 8px 11px 9px 31px;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    background: #fff;
    color: #000;
    font-size: 12px;
    display: inline-block;
    }

    .radio-box input[type="radio"]:checked + label {
      background: #007193;
      color: #fff;
      border-color: #007193;
    }

    .radio-box label::before {
      content: '';
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 2px solid #007193;
    position: absolute;
    left: 6px;
    top: 50%;
    transform: translateY(-50%);
    background: #fff;
    }

    .radio-box input[type="radio"]:checked + label::before {
      background: #fff;
      border: 2px solid #fff;
      background-image: url('data:image/svg+xml;utf8,<svg fill="%23007193" viewBox="0 0 24 24" width="18" height="18" xmlns="http://www.w3.org/2000/svg"><path d="M9 16.17l-4.41-4.42L3.17 13l5.83 5.83L20.83 6.17 19.41 4.75z"/></svg>');
      background-repeat: no-repeat;
      background-position: center;
    }

    input, textarea {
      width: 100%;
      margin-top: 22px;
      padding: 12px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }

    .button-row {
      display: flex;
      gap: 10px;
      margin: 15px 0 51px;
    }

    button {
      padding: 5px 14px;
      border: none;
      font-size: 14px;
      border-radius: 6px;
      cursor: pointer;
    }

    .btn-upload {
      background: #007193;
      color: white;
    }

    .btn-clear {
      background: white;
      border: 1px solid #ccc;
      color: #333;
    }

    .submit-btn {
          width: 90%;
    background: #28a745;
    color: white;
    padding: 7px 7px;
    font-size: 16px;
    border-radius: 10px;
    margin-top: 10px;
    position: fixed;
    bottom: 15px;
    }

  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <h2>Ticket Raise</h2>
    <div class="add-btn" onclick="togglePopup()">+</div>
  </header>

  <!-- Tabs -->
  <div class="tabs">
    <div class="tab active">Pending <span>0</span></div>
    <div class="tab">Confirmation <span>0</span></div>
    <div class="tab">Solved <span>0</span></div>
  </div>

  <!-- Popup -->
  <div class="popup" id="popup">
    <div class="popup-content">
      <div class="popup-header">
        <strong>Raise Ticket</strong>
        <div class="close" onclick="togglePopup()">×</div>
      </div>

     <div class="section-title">Type:</div>
<div class="option-boxes">
  <div class="radio-box">
    <input type="radio" name="type" id="type-query" checked>
    <label for="type-query">Query</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="type" id="type-suggestion">
    <label for="type-suggestion">Suggestion</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="type" id="type-support">
    <label for="type-support">Support</label>
  </div>
</div>

<div id="complaint_regarding" style="display: none;">
  <div class="section-title">Complaint Regarding:</div>
  <div class="option-boxes">
    <div class="radio-box">
      <input type="radio" name="complaint" id="technical">
      <label for="technical">Technical</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="complaint" id="training">
      <label for="training">Training</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="complaint" id="other">
      <label for="other">Other</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="complaint" id="sales">
      <label for="sales">Sales</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="complaint" id="support">
      <label for="support">Support</label>
    </div>
  </div>
</div>

<div id="software_for" style="display: none;">
  <div class="section-title">Software For:</div>
  <div class="option-boxes">
    <div class="radio-box">
      <input type="radio" name="software_for" id="improvement">
      <label for="improvement">Found Error/Improvement</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="software_for" id="software_other">
      <label for="software_other">Other</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="software_for" id="development">
      <label for="development">New Design/Development</label>
    </div>
  </div>
</div>

<div id="sales_for" style="display: none;">
  <div class="section-title">Sales:</div>
  <div class="option-boxes">
    <div class="radio-box">
      <input type="radio" name="sales" id="assurance">
      <label for="assurance">Assurance</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="sales" id="payment">
      <label for="payment">Payment</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="sales" id="sales_other">
      <label for="sales_other">Other</label>
    </div>
  </div>
</div>

<div id="support_for" style="display: none;">
  <div class="section-title">Support:</div>
  <div class="option-boxes">
    <div class="radio-box">
      <input type="radio" name="support" id="not_responding">
      <label for="not_responding">Not Responding</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="support" id="ignorance">
      <label for="ignorance">Ignorance</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="support" id="support_other">
      <label for="support_other">Other</label>
    </div>
    <div class="radio-box">
      <input type="radio" name="support" id="mis_behaviour">
      <label for="mis_behaviour">Mis Behaviour</label>
    </div>
  </div>
</div>

<div class="section-title">Platform With:</div>
<div class="option-boxes">
  <div class="radio-box">
    <input type="radio" name="platform" id="platform-web">
    <label for="platform-web">Web Panel</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="platform" id="platform-admin">
    <label for="platform-admin">Admin App</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="platform" id="platform-teacher">
    <label for="platform-teacher">Teacher App</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="platform" id="platform-attendance">
    <label for="platform-attendance">Attendance App</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="platform" id="platform-transport">
    <label for="platform-transport">Transport</label>
  </div>
  <div class="radio-box">
    <input type="radio" name="platform" id="platform-student" checked>
    <label for="platform-student">Student App</label>
  </div>
</div>


      <input type="text" placeholder="Subject *">
      <textarea rows="3" placeholder="Description"></textarea>

      <div class="button-row">
        <button class="btn-upload">UPLOAD</button>
        <button class="btn-clear">CLEAR</button>
      </div>

      <button class="submit-btn">Submit</button>
    </div>
  </div>

  <script>
    function togglePopup() {
      document.getElementById('popup').classList.toggle('active');
    }
    function togglePopup() {
    document.getElementById('popup').classList.toggle('active');
  }

  // Handle tab switching
  document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      tab.classList.add('active');
    });
  });

  document.querySelectorAll('input[name="type"]').forEach(radio => {
  radio.addEventListener('change', function () {
    document.getElementById('complaint_regarding').style.display = this.id === 'type-support' ? 'block' : 'none';

    // Hide all sub-sections on type change
    document.getElementById('software_for').style.display = 'none';
    document.getElementById('sales_for').style.display = 'none';
    document.getElementById('support_for').style.display = 'none';
  });
});

document.querySelectorAll('input[name="complaint"]').forEach(radio => {
  radio.addEventListener('change', function () {
    // Hide all by default
    document.getElementById('software_for').style.display = 'none';
    document.getElementById('sales_for').style.display = 'none';
    document.getElementById('support_for').style.display = 'none';

    // Show only based on selection
    if (this.id === 'technical') {
      document.getElementById('software_for').style.display = 'block';
    } else if (this.id === 'sales') {
      document.getElementById('sales_for').style.display = 'block';
    } else if (this.id === 'support') {
      document.getElementById('support_for').style.display = 'block';
    }
  });
});


  </script>

</body>
</html>
