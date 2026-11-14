<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#f5f7fc" />
<title>Edplus · ERP Reports</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#eef1f8; --bg-2:#f6f8fc; --surface:#ffffff;
    --ink:#141a2e; --ink-2:#3b4256; --muted:#8b91a6;
    --line:#eaedf5; --line-2:#f1f3fa;
    --brand:#4b5bd6; --brand-2:#6d7cf5; --brand-soft:#eef0fe;
    --green:#12a150; --green-bg:#e6f6ed;
    --red:#e5484d;   --red-bg:#fdecec;
    --blue:#2e6be6;  --blue-bg:#e8f0fe;
    --amber:#d98407; --amber-bg:#fdf1de;
    --violet:#7c4dd6;--violet-bg:#f1eafc;
    --teal:#0d9488;  --teal-bg:#e2f5f2;
    --shadow-sm:0 1px 2px rgba(20,26,46,.05);
    --shadow:0 6px 22px -8px rgba(38,44,74,.18), 0 2px 6px -2px rgba(38,44,74,.06);
    --r:20px; --r-sm:15px;
    --num:"Space Grotesk",system-ui,sans-serif;
    --sans:"Inter",system-ui,-apple-system,sans-serif;
  }
  *{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
  html,body{margin:0;padding:0}
  body{
    font-family:var(--sans); color:var(--ink);
    background:
      radial-gradient(120% 55% at 100% 0%, #e9edfb 0%, transparent 55%),
      radial-gradient(120% 55% at 0% 6%, #eef7f3 0%, transparent 50%),
      var(--bg);
    min-height:100vh; display:flex; justify-content:center;
    -webkit-font-smoothing:antialiased;
  }
  .app{
    width:100%; max-width:440px; min-height:100vh; background:var(--bg-2);
    position:relative; display:flex; flex-direction:column;
    box-shadow:0 0 0 1px rgba(20,26,46,.03), 0 40px 80px -30px rgba(38,44,74,.35);
  }
  .scroll{
    flex:1; overflow-y:auto; overflow-x:hidden;
    padding:0 16px calc(96px + env(safe-area-inset-bottom,0px));
    -webkit-overflow-scrolling:touch;
  }
  .scroll::-webkit-scrollbar{width:0}

  /* header */
  .header{
    position:sticky; top:0; z-index:30;
    padding:calc(env(safe-area-inset-top,0px) + 5px) 16px 10px;
    background:linear-gradient(180deg,var(--bg-2) 70%, rgba(246,248,252,0));
    backdrop-filter:saturate(1.1) blur(2px);
  }
  .top-row{display:flex; align-items:center; justify-content:space-between; gap:12px}
  .h-title{display:flex; align-items:center; gap:11px}
  .h-title .logo{
    width:38px;height:38px;border-radius:12px;flex:none;
    background:linear-gradient(145deg,var(--brand),var(--brand-2));
    color:#fff;display:grid;place-items:center;font-family:var(--num);font-weight:700;font-size:15px;
    box-shadow:0 8px 16px -6px rgba(75,91,214,.55); letter-spacing:-.5px;
  }
  .h-title .t{font-family:var(--num); font-weight:700; font-size:21px; letter-spacing:-.5px; line-height:1.05}
  .h-title .t small{display:block; font-family:var(--sans); font-weight:600; font-size:11.5px; color:var(--muted); letter-spacing:0}
  .qa{
    display:inline-flex; align-items:center; gap:7px; flex:none;
    background:var(--surface); border:1px solid var(--line); border-radius:12px;
    padding:9px 12px; font-weight:600; font-size:12.5px; color:var(--ink-2);
    box-shadow:var(--shadow-sm); cursor:pointer; transition:transform .1s, background .18s, color .18s;
  }
  .qa svg{color:var(--red); transition:transform .2s}
  .qa:active{transform:scale(.96)}
  .qa.active{background:var(--red-bg); color:var(--red); border-color:transparent}
  .qa.active svg{transform:scale(1.12)}

  .search{
    display:flex; align-items:center; gap:11px; margin-top:14px;
    background:var(--surface); border:1px solid var(--line); border-radius:14px;
    padding:12px 14px; box-shadow:var(--shadow-sm); transition:border-color .18s, box-shadow .18s;
  }
  .search:focus-within{border-color:var(--brand-2); box-shadow:0 0 0 3px var(--brand-soft)}
  .search svg{color:var(--muted); flex:none}
  .search input{border:none; outline:none; background:none; font-family:inherit; font-size:14px; flex:1; color:var(--ink); font-weight:500}
  .search input::placeholder{color:var(--muted); font-weight:500}
  .search .clr{border:none;background:none;color:var(--muted);cursor:pointer;padding:2px;display:none}
  .search .clr.show{display:block}

  /* section card */
  .section{
    margin-top:16px; background:var(--surface); border:1px solid var(--line-2);
    border-radius:var(--r); box-shadow:var(--shadow-sm); padding:16px 12px 10px;
    opacity:0; transform:translateY(10px); animation:rise .5s cubic-bezier(.2,.8,.2,1) forwards;
  }
  @keyframes rise{to{opacity:1; transform:none}}
  .sec-head{display:flex; align-items:center; gap:9px; padding:0 6px; margin-bottom:6px}
  .sec-head .dot{width:9px; height:9px; border-radius:3px}
  .sec-head h2{font-family:var(--sans); font-weight:700; font-size:16.5px; letter-spacing:-.2px; margin:0; color:var(--ink)}
  .sec-head .count{margin-left:auto; font-size:11px; font-weight:700; color:var(--muted); background:var(--line-2); padding:3px 8px; border-radius:20px}

  /* launcher grid */
  .grid{display:grid; grid-template-columns:repeat(4,1fr); gap:2px}
  @media(max-width:346px){ .grid{grid-template-columns:repeat(3,1fr)} }
  .tile{
    background:none; border:none; cursor:pointer;
    display:flex; flex-direction:column; align-items:center; gap:9px;
    padding:12px 4px 10px; border-radius:15px;
    transition:background .16s, transform .1s;
  }
  .tile:hover{background:var(--line-2)}
  .tile:active{transform:scale(.93)}
  .tile .chip{
    width:48px; height:48px; border-radius:15px; display:grid; place-items:center;
    background:var(--cb); color:var(--c);
    box-shadow:inset 0 0 0 1px rgba(20,26,46,.02);
    transition:transform .16s;
  }
  .tile:hover .chip{transform:translateY(-2px)}
  .tile .chip svg{width:22px; height:22px}
  .tile .lbl{
    font-size:11px; font-weight:600; color:var(--ink-2); line-height:1.22;
    text-align:center; min-height:27px; display:flex; align-items:center;
  }
  .tile.hidden{display:none}
  .section.hidden{display:none}

  .empty{
    display:none; text-align:center; padding:60px 20px; color:var(--muted);
  }
  .empty.show{display:block}
  .empty .ic{width:56px;height:56px;border-radius:18px;background:var(--surface);border:1px solid var(--line);
    display:grid;place-items:center;margin:0 auto 14px;box-shadow:var(--shadow-sm);color:var(--muted)}
  .empty p{font-weight:600; color:var(--ink-2); margin:0 0 4px; font-size:15px}
  .empty span{font-size:13px}

  /* bottom nav */
  .nav{
    position:fixed; left:50%; transform:translateX(-50%); bottom:0; z-index:40;
    width:100%; max-width:440px;
    padding:8px 14px calc(8px + env(safe-area-inset-bottom,0px));
    background:rgba(255,255,255,.86); backdrop-filter:blur(16px) saturate(1.4);
    border-top:1px solid var(--line);
    display:grid; grid-template-columns:repeat(5,1fr); align-items:end;
  }
  .nav-item{
    display:flex; flex-direction:column; align-items:center; gap:4px;
    padding:6px 0; color:var(--muted); cursor:pointer; border:none; background:none;
    font-family:var(--sans); font-size:10.5px; font-weight:600; transition:color .18s;
  }
  .nav-item svg{width:22px; height:22px; transition:transform .18s}
  .nav-item.active{color:var(--brand)}
  .nav-item.active svg{transform:translateY(-1px)}
  .nav-item:active svg{transform:scale(.88)}
  .fab{
    align-self:end; justify-self:center;
    width:56px; height:56px; border-radius:18px;
    background:linear-gradient(145deg,var(--brand),var(--brand-2)); color:#fff;
    display:grid; place-items:center; border:none; cursor:pointer;
    font-family:var(--num); font-weight:700; font-size:16px;
    box-shadow:0 14px 26px -8px rgba(75,91,214,.7); transform:translateY(-14px);
    transition:transform .16s;
  }
  .fab:active{transform:translateY(-14px) scale(.93)}
  .fab small{font-size:9px; margin-top:1px}

  @media(prefers-reduced-motion:reduce){
    *{animation:none!important; transition:none!important}
    .section{opacity:1; transform:none}
  }
</style>
</head>
<body>
<div class="app">
  <header class="header">
    <div class="top-row">
      <div class="h-title">
        <div class="logo">Ed</div>
        <div class="t">ERP Report<small>All reports & tools</small></div>
      </div>
      <button class="qa" id="qaBtn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 21s-7.5-4.6-10-9.3C.3 8.2 1.9 4.5 5.4 4.5c2 0 3.4 1.1 4.2 2.3l.4.6.4-.6c.8-1.2 2.2-2.3 4.2-2.3 3.5 0 5.1 3.7 3.4 7.2C19.5 16.4 12 21 12 21z"/></svg>
        Quick Access
      </button>
    </div>
    <label class="search">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
      <input id="search" type="text" placeholder="Search reports & tools" autocomplete="off" />
      <button class="clr" id="clr" aria-label="Clear"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </label>
  </header>

  <div class="scroll">
    <div id="grid"></div>
    <div class="empty" id="empty">
      <div class="ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg></div>
      <p>No reports found</p>
      <span>Try a different word or clear the search.</span>
    </div>
  </div>
 
</div>

<script>
/* ---------- icon library (inner SVG markup, 24x24 stroke) ---------- */
var I={
  examReport:'<rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3"/><path d="m9 14 2 2 4-4"/>',
  paperSlot:'<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h6"/>',
  timeTable:'<path d="M21 8V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6"/><path d="M3 10h18M8 2v4M16 2v4"/><circle cx="17.5" cy="17.5" r="4.5"/><path d="M17.5 15.5v2l1.4 .9"/>',
  review:'<path d="M11.5 3l2.5 5.1 5.6.8-4 4 1 5.6-5-2.6-5 2.6 1-5.6-4-4 5.6-.8z"/>',
  lessonPlan:'<path d="M12 7v14M3 5a2 2 0 0 1 2-2h4a3 3 0 0 1 3 3 3 3 0 0 1 3-3h4a2 2 0 0 1 2 2v13a1 1 0 0 1-1 1h-6a2 2 0 0 0-2 2 2 2 0 0 0-2-2H4a1 1 0 0 1-1-1z"/>',
  lessonReport:'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M9 7h7M9 11h5"/>',
  attendance:'<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="m15 11 2 2 4-4"/>',
  calendar:'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18M8 2v4M16 2v4"/><path d="m9 15 2 2 3-3"/>',
  studentLedger:'<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.1 2.7 2.5 6 2.5s6-1.4 6-2.5v-5"/><path d="M22 10v5"/>',
  message:'<path d="M21 11.5a8.4 8.4 0 0 1-8.5 8.5 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.7A8.5 8.5 0 0 1 12.5 3 8.4 8.4 0 0 1 21 11.5z"/>',
  ledgerRupee:'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><path d="M9 6h6M9 9h6M14 6c0 3-2.5 3.5-5 3.5l5 4"/>',
  bank:'<path d="M3 21h18M4 10h16M5 10 12 4l7 6M6 10v11M10 10v11M14 10v11M18 10v11"/>',
  cashBook:'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/><circle cx="12" cy="10" r="2.4"/><path d="M9 10h.01M15 10h.01"/>',
  dayBook:'<path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>',
  card:'<rect x="2" y="5" width="20" height="14" rx="2.5"/><path d="M2 10h20M6 15h4"/>',
  overdue:'<path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0z"/><path d="M12 9v4M12 17h.01"/>',
  feesExtend:'<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M3 10h18M8 2v4M16 2v4"/><path d="M15 16h4M17 14v4"/>',
  feesDeposit:'<circle cx="12" cy="12" r="9"/><path d="M12 7v10M9.5 9.5h4a2 2 0 0 1 0 4h-4"/>',
  bell:'<path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/>',
  leave:'<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5M21 12H9"/>',
  send:'<path d="m22 2-7 20-4-9-9-4z"/><path d="M22 2 11 13"/>',
  mail:'<rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3 7 9 6 9-6"/>',
  library:'<rect x="3" y="4" width="4" height="16" rx="1"/><rect x="9" y="4" width="4" height="16" rx="1"/><path d="m15.5 5.2 3.9 15 1.9-.5-3.9-15z"/>',
  task:'<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
  users:'<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13A4 4 0 0 1 16 11"/>',
  gatepass:'<path d="M2 9a3 3 0 0 1 0 6v2a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-2a3 3 0 0 1 0-6V7a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2z"/><path d="M13 5v2M13 11v2M13 17v2"/>',
  route:'<circle cx="6" cy="19" r="3"/><path d="M9 19h8.5a3.5 3.5 0 0 0 0-7h-11a3.5 3.5 0 0 1 0-7H15"/><circle cx="18" cy="5" r="3"/>',
  driver:'<circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="2.5"/><path d="M12 3v6.5M4.2 8l5.6 3.2M19.8 8l-5.6 3.2"/>',
  truck:'<path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-4l-3-4h-4v8h2"/><circle cx="7.5" cy="17.5" r="2"/><circle cx="17.5" cy="17.5" r="2"/>',
  bus:'<rect x="4" y="3" width="16" height="14" rx="2"/><path d="M4 10h16M8 3v7M16 3v7"/><path d="M6 21v-2M18 21v-2"/><circle cx="8" cy="17" r=".6"/><circle cx="16" cy="17" r=".6"/>'
};

/* ---------- sections ----------
   NOTE: har item me "u" key add ki gayi h -> yahi uska target PHP page url h.
   Apne hisaab se sirf "u" ki value change kro, baaki UI/labels/icons wahi rahenge. */
var ACC={
  blue:['var(--blue)','var(--blue-bg)'], violet:['var(--violet)','var(--violet-bg)'],
  green:['var(--green)','var(--green-bg)'], brand:['var(--brand)','var(--brand-soft)'],
  teal:['var(--teal)','var(--teal-bg)']
};
var SECTIONS=[
  {title:'Academic', accent:'blue', items:[
    {l:'Exam Report', i:'examReport', q:1, u:'features/exam_report.php'},
    {l:'Paper Slot', i:'paperSlot', u:'features/paper_slot.php'},
    {l:'Time Table', i:'timeTable', u:'features/time_table.php'},
    {l:'Review', i:'review', u:'features/review.php'},
    {l:'Lesson Plan', i:'lessonPlan', u:'lesson_plan.php'},
    {l:'Lesson Plan Report', i:'lessonReport', u:'lesson_plan_report.php'},
    {l:'Attendance Report', i:'attendance', q:1, u:'attendance_report.php'},
    {l:'Calender Management', i:'calendar', u:'calendar_management.php'}
  ]},
  {title:'Student Manage', accent:'violet', items:[
    {l:'Student Ledger', i:'studentLedger', u:'student_ledger.php'},
    {l:'Student Message', i:'message', q:1, u:'student_message.php'},
    {l:'Expensive Ledger', i:'ledgerRupee', u:'expensive_ledger.php'}
  ]},
  {title:'Finance', accent:'green', items:[
    {l:'Bank Book', i:'bank', u:'bank_book.php'},
    {l:'Cash Book', i:'cashBook', u:'cash_book.php'},
    {l:'Day Book', i:'dayBook', u:'day_book.php'},
    {l:'Expenses Payment', i:'card', u:'expenses_payment.php'},
    {l:'Overdue Fees', i:'overdue', u:'overdue_fees.php'},
    {l:'Fees Extend', i:'feesExtend', u:'fees_extend.php'},
    {l:'Fees Deposit', i:'feesDeposit', q:1, u:'fees_deposit.php'},
    {l:'Fees Reminder', i:'bell', q:1, u:'fees_reminder.php'}
  ]},
  {title:'Edplus Special', accent:'brand', items:[
    {l:'Leave Application', i:'leave', u:'leave_application.php'},
    {l:'Reminder', i:'bell', u:'reminder.php'},
    {l:'Send Notification', i:'send', q:1, u:'send_notification.php'},
    {l:'Notification', i:'mail', u:'notification.php'},
    {l:'Library Manage', i:'library', u:'library_manage.php'},
    {l:'Task Manage', i:'task', u:'task_manage.php'},
    {l:'Lead Report', i:'users', q:1, u:'lead_report.php'},
    {l:'Gatepass Report', i:'gatepass', u:'gatepass_report.php'}
  ]},
  {title:'Transport', accent:'teal', items:[
    {l:'Transport Routing', i:'route', u:'transport_routing.php'},
    {l:'Transport Driver', i:'driver', u:'transport_driver.php'},
    {l:'Transport Vehicle', i:'truck', u:'transport_vehicle.php'},
    {l:'Transport Fees', i:'bus', u:'transport_fees.php'}
  ]}
];

/* ---------- render ---------- */
function svg(name){ return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'+(I[name]||'')+'</svg>'; }
var grid=document.getElementById('grid');
SECTIONS.forEach(function(sec,si){
  var acc=ACC[sec.accent], el=document.createElement('section');
  el.className='section'; el.style.animationDelay=(si*0.05)+'s';
  el.dataset.title=sec.title.toLowerCase();
  var tiles=sec.items.map(function(it){
    return '<button class="tile" data-lbl="'+it.l.toLowerCase()+'" data-quick="'+(it.q?1:0)+'" data-url="'+(it.u||'')+'" '+
           'style="--c:'+acc[0]+';--cb:'+acc[1]+'">'+
           '<span class="chip">'+svg(it.i)+'</span>'+
           '<span class="lbl">'+it.l+'</span></button>';
  }).join('');
  el.innerHTML='<div class="sec-head"><span class="dot" style="background:'+acc[0]+'"></span>'+
               '<h2>'+sec.title+'</h2><span class="count">'+sec.items.length+'</span></div>'+
               '<div class="grid">'+tiles+'</div>';
  grid.appendChild(el);
});

/* ---------- search + quick access filter ---------- */
var search=document.getElementById('search'), clr=document.getElementById('clr'),
    empty=document.getElementById('empty'), qaBtn=document.getElementById('qaBtn');
var quickOnly=false;

function applyFilter(){
  var q=search.value.trim().toLowerCase();
  clr.classList.toggle('show', q.length>0);
  var anyVisible=false;
  document.querySelectorAll('.section').forEach(function(sec){
    var shown=0;
    sec.querySelectorAll('.tile').forEach(function(t){
      var okText = !q || t.dataset.lbl.indexOf(q)>-1;
      var okQuick = !quickOnly || t.dataset.quick==='1';
      var show = okText && okQuick;
      t.classList.toggle('hidden', !show);
      if(show) shown++;
    });
    // update count + hide empty sections
    var cnt=sec.querySelector('.count'); if(cnt) cnt.textContent=shown;
    sec.classList.toggle('hidden', shown===0);
    if(shown>0) anyVisible=true;
  });
  empty.classList.toggle('show', !anyVisible);
}
search.addEventListener('input', applyFilter);
clr.addEventListener('click', function(){ search.value=''; applyFilter(); search.focus(); });
qaBtn.addEventListener('click', function(){
  quickOnly=!quickOnly;
  qaBtn.classList.toggle('active', quickOnly);
  applyFilter();
});

/* ---------- press feedback + navigate on click ---------- */
grid.addEventListener('click', function(e){
  var tile=e.target.closest('.tile'); if(!tile) return;
  var anim=tile.animate([{transform:'scale(.9)'},{transform:'scale(1)'}],{duration:180,easing:'ease-out'});
  var url=tile.dataset.url;
  if(url){
    anim.onfinish=function(){ window.location.href=url; };
  }
});

/* ---------- bottom nav ---------- */
document.querySelectorAll('[data-nav]').forEach(function(n){
  n.addEventListener('click',function(){
    document.querySelectorAll('[data-nav]').forEach(function(x){x.classList.remove('active')});
    n.classList.add('active');
  });
});
</script>
</body>
</html>