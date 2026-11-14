<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Reports</title>
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{
      font-family: 'Segoe UI', sans-serif;
      background:#f4f7f9;
          padding: 10px 10px;
    }

    .wrap{max-width:420px;margin:auto}

    .header{
      font-size:22px;
      font-weight:600;
      color:#2d3a45;
      margin-bottom:20px;
      text-align:center;
    }

    .card{
      background:#fff;
      padding:18px 20px;
      margin-bottom:16px;
      border-radius:14px;
      box-shadow:0 6px 18px rgba(30,40,50,0.06);
    }

    .title{
      font-size:18px;
      color:#2d3a45;
      margin-bottom:14px;
      font-weight:600;
    }

    .list{
      display:flex;
      flex-direction:column;
      gap:14px;
    }

    .report-item{
      display:flex;
      justify-content:space-between;
      align-items:center;
      background:#f8fafc;
      padding:14px 16px;
      border-radius:10px;
      border:1px solid rgba(0,0,0,0.05);
      cursor:pointer;
      transition:0.2s;
    }

    .report-item:hover{
      background:#eef3f7;
    }

    .left{
      display:flex;
      align-items:center;
      gap:12px;
    }

    .left svg{width:32px;height:32px;stroke:#2b8fb3}

    .name{font-size:15px;font-weight:500;color:#2d3a45}
    .desc{font-size:12px;color:#6b7b88;margin-top:2px}

    .arrow svg{width:20px;height:20px;stroke:#2d3a45}
  </style>
</head>
<body>
  <div class="wrap">

    <!--<div class="header">Reports</div>-->

    <!-- Academic Progress -->
    <div class="card">
      <div class="title">Academic Progress</div>
      <div class="list">

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 10h18"/></svg>
            <div>
              <div class="name">Term Report</div>
              <div class="desc">View detailed term-wise performance</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
            <div>
              <div class="name">Subject-wise Summary</div>
              <div class="desc">Marks & grade for each subject</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v4H4z"/><path d="M4 12h16v8H4z"/></svg>
            <div>
              <div class="name">Overall Performance</div>
              <div class="desc">Combined score & performance graph</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

      </div>
    </div>

    <!-- Attendance Reports -->
    <div class="card">
      <div class="title">Attendance Reports</div>
      <div class="list">

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M8 2v4"/></svg>
            <div>
              <div class="name">Daily Attendance</div>
              <div class="desc">Present / Absent / Late details</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6"><circle cx="12" cy="12" r="9"/></svg>
            <div>
              <div class="name">Monthly Attendance</div>
              <div class="desc">Month-wise attendance percentage</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

      </div>
    </div>

    <!-- Fee Related Reports -->
    <div class="card">
      <div class="title">Fees Reports</div>
      <div class="list">

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6"><circle cx="12" cy="12" r="7"/><path d="M12 8v4l2 2"/></svg>
            <div>
              <div class="name">Fee Transactions</div>
              <div class="desc">All payments & dues history</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

        <div class="report-item">
          <div class="left">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.6"><path d="M4 4h16v16H4z"/></svg>
            <div>
              <div class="name">Due Report</div>
              <div class="desc">Pending fee summary</div>
            </div>
          </div>
          <div class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke-width="1.5"><path d="M9 6l6 6-6 6" /></svg></div>
        </div>

      </div>
    </div>

  </div>
</body>
</html>
