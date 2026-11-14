
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#ffffff" />
<title>Time Table</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0d1b2a;
    --ink-soft:#334155;
    --muted:#7683a0;
    --line:#e8edf5;
    --line-strong:#dbe3f0;
    --bg:#f3f6fb;
    --card:#ffffff;
    --primary:#1f5fe0;
    --primary-ink:#1b4fbf;
    --primary-tint:#eaf1ff;
    --shadow-sm:0 1px 2px rgba(19,42,86,.06);
    --shadow-md:0 6px 22px -8px rgba(19,42,86,.18);
    --shadow-lg:0 18px 46px -18px rgba(19,42,86,.28);
    --radius:18px;
    --font-d:'Plus Jakarta Sans',system-ui,sans-serif;
    --font-b:'Inter',system-ui,sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
  html,body{height:100%}
  body{
    font-family:var(--font-b);
    color:var(--ink);
    background:
      radial-gradient(1200px 500px at 100% -10%, #e9f0ff 0, transparent 60%),
      radial-gradient(900px 500px at -10% 0%, #eef7f3 0, transparent 55%),
      var(--bg);
    min-height:100vh;
    display:flex;
    justify-content:center;
    padding:0;
    -webkit-font-smoothing:antialiased;
  }

  /* App shell — behaves like a mobile app screen, centered on desktop */
  .app{
    width:100%;
    max-width:460px;
    min-height:100vh;
    background:var(--bg);
    display:flex;
    flex-direction:column;
    position:relative;
    overflow:hidden;
  }
  @media(min-width:520px){
    body{padding:26px 16px}
    .app{
      min-height:auto;
      height:calc(100vh - 52px);
      border-radius:30px;
      box-shadow:var(--shadow-lg);
      border:1px solid var(--line);
    }
  }

  /* ---------- App bar ---------- */
  .appbar{
    position:sticky;top:0;z-index:20;
    background:rgba(255,255,255,.82);
    backdrop-filter:saturate(180%) blur(14px);
    -webkit-backdrop-filter:saturate(180%) blur(14px);
    border-bottom:1px solid var(--line);
    padding:14px 16px 12px;
  }
  .appbar-top{display:flex;align-items:center;gap:10px}
  .icon-btn{
    width:38px;height:38px;flex:0 0 auto;
    display:grid;place-items:center;
    border-radius:12px;border:1px solid var(--line);
    background:#fff;color:var(--ink-soft);
    box-shadow:var(--shadow-sm);cursor:pointer;
    transition:transform .15s ease, background .15s ease;
  }
  .icon-btn:active{transform:scale(.94)}
  .icon-btn:hover{background:var(--primary-tint)}
  .appbar-title{flex:1;min-width:0}
  .appbar-title h1{
    font-family:var(--font-d);font-weight:800;font-size:19px;
    letter-spacing:-.02em;line-height:1.1;
  }
  .appbar-title p{font-size:12px;color:var(--muted);margin-top:2px;font-weight:500}

  /* Segmented control */
  .segment{
    margin-top:14px;
    display:flex;gap:4px;padding:4px;
    background:#eef2f9;border-radius:14px;position:relative;
  }
  .segment .thumb{
    position:absolute;top:4px;left:4px;
    width:calc(50% - 4px);height:calc(100% - 8px);
    background:#fff;border-radius:10px;
    box-shadow:var(--shadow-sm);
    transition:transform .28s cubic-bezier(.4,.1,.2,1);
    z-index:0;
  }
  .segment.faculty .thumb{transform:translateX(100%)}
  .seg-btn{
    flex:1;position:relative;z-index:1;
    border:0;background:transparent;cursor:pointer;
    font-family:var(--font-d);font-weight:700;font-size:14px;
    padding:9px 0;border-radius:10px;color:var(--muted);
    display:flex;align-items:center;justify-content:center;gap:7px;
    transition:color .2s ease;
  }
  .seg-btn.active{color:var(--primary-ink)}
  .seg-btn svg{width:16px;height:16px}

  /* ---------- Controls (selector + days) ---------- */
  .controls{padding:14px 16px 4px}
  .picker{
    position:relative;display:flex;align-items:center;
    background:#fff;border:1px solid var(--line-strong);
    border-radius:14px;padding:11px 14px;box-shadow:var(--shadow-sm);
  }
  .picker .lead{
    width:34px;height:34px;flex:0 0 auto;margin-right:11px;
    border-radius:10px;background:var(--primary-tint);
    display:grid;place-items:center;color:var(--primary);
  }
  .picker .lead svg{width:18px;height:18px}
  .picker .meta{flex:1;min-width:0}
  .picker .meta span{display:block;font-size:11px;color:var(--muted);font-weight:600;letter-spacing:.03em;text-transform:uppercase}
  .picker select{
    -webkit-appearance:none;appearance:none;
    border:0;background:transparent;outline:none;
    font-family:var(--font-d);font-weight:700;font-size:15.5px;color:var(--ink);
    width:100%;cursor:pointer;padding:1px 0;
  }
  .picker .chev{color:var(--muted);flex:0 0 auto;margin-left:8px}
  .picker .chev svg{width:18px;height:18px;display:block}

  /* Day rail */
  .days{
    display:flex;gap:8px;padding:14px 16px 6px;
    overflow-x:auto;scrollbar-width:none;-webkit-overflow-scrolling:touch;
  }
  .days::-webkit-scrollbar{display:none}
  .day{
    flex:0 0 auto;min-width:52px;text-align:center;cursor:pointer;
    background:#fff;border:1px solid var(--line-strong);
    border-radius:14px;padding:8px 4px 9px;
    transition:all .18s ease;
  }
  .day .dow{font-size:11px;font-weight:600;color:var(--muted);letter-spacing:.04em;text-transform:uppercase}
  .day .num{font-family:var(--font-d);font-weight:700;font-size:17px;margin-top:2px;color:var(--ink-soft)}
  .day.active{
    background:linear-gradient(180deg,#2a68e6,#1f5fe0);
    border-color:transparent;box-shadow:0 10px 20px -8px rgba(31,95,224,.6);
  }
  .day.active .dow,.day.active .num{color:#fff}
  .day.today:not(.active){border-color:var(--primary);}
  .day.today .num{position:relative}
  .day.today:not(.active) .num::after{
    content:"";position:absolute;left:50%;bottom:-6px;transform:translateX(-50%);
    width:5px;height:5px;border-radius:50%;background:var(--primary);
  }

  /* ---------- List area ---------- */
  .sheet{flex:1;overflow-y:auto;padding:6px 16px 30px;scrollbar-width:thin}
  .sheet::-webkit-scrollbar{width:0}
  .list-head{
    display:flex;align-items:baseline;justify-content:space-between;
    padding:12px 2px 10px;
  }
  .list-head h2{font-family:var(--font-d);font-weight:800;font-size:16px;letter-spacing:-.01em}
  .list-head .count{font-size:12.5px;color:var(--muted);font-weight:600}

  .timeline{position:relative;padding-left:2px}

  .period{
    position:relative;display:flex;gap:12px;
    background:var(--card);border:1px solid var(--line);
    border-radius:var(--radius);padding:14px 15px;margin-bottom:11px;
    box-shadow:var(--shadow-sm);
    animation:rise .42s cubic-bezier(.2,.7,.2,1) both;
    transition:transform .16s ease, box-shadow .16s ease;
  }
  .period:active{transform:scale(.99)}
  .period::before{
    content:"";position:absolute;left:0;top:14px;bottom:14px;width:4px;
    border-radius:0 4px 4px 0;background:var(--accent,#1f5fe0);
  }
  .p-time{
    flex:0 0 auto;width:58px;text-align:right;padding-right:12px;
    border-right:1px dashed var(--line-strong);
  }
  .p-time .start{font-family:var(--font-d);font-weight:800;font-size:14.5px;color:var(--ink)}
  .p-time .end{font-size:11.5px;color:var(--muted);margin-top:3px;font-weight:500}
  .p-body{flex:1;min-width:0}
  .p-toprow{display:flex;align-items:center;gap:8px;margin-bottom:5px}
  .badge{
    font-family:var(--font-d);font-weight:700;font-size:10.5px;
    padding:3px 8px;border-radius:999px;letter-spacing:.02em;
    color:var(--accent,#1f5fe0);background:var(--accent-tint,#eaf1ff);
  }
  .p-title{font-family:var(--font-d);font-weight:700;font-size:15.5px;letter-spacing:-.01em;color:var(--ink)}
  .p-sub{display:flex;flex-wrap:wrap;gap:6px 14px;margin-top:6px}
  .p-sub .m{display:flex;align-items:center;gap:5px;font-size:12.5px;color:var(--ink-soft);font-weight:500}
  .p-sub .m svg{width:13px;height:13px;color:var(--muted);flex:0 0 auto}

  .breakcard{
    display:flex;align-items:center;justify-content:center;gap:8px;
    margin:2px 0 11px;padding:9px;
    border:1px dashed var(--line-strong);border-radius:14px;
    color:var(--muted);font-weight:600;font-size:12.5px;
    background:repeating-linear-gradient(45deg,#fafbfe,#fafbfe 8px,#f4f7fc 8px,#f4f7fc 16px);
  }
  .breakcard svg{width:15px;height:15px}

  /* Empty state */
  .empty{
    padding:56px 24px;text-align:center;
    display:flex;flex-direction:column;align-items:center;gap:6px;
    animation:rise .4s ease both;
  }
  .empty .art{
    width:96px;height:96px;border-radius:26px;margin-bottom:12px;
    background:linear-gradient(160deg,#eef4ff,#e6f6f1);
    display:grid;place-items:center;color:var(--primary);
    box-shadow:inset 0 0 0 1px rgba(31,95,224,.08);
  }
  .empty .art svg{width:44px;height:44px}
  .empty h3{font-family:var(--font-d);font-weight:800;font-size:17px}
  .empty p{font-size:13.5px;color:var(--muted);max-width:230px;line-height:1.5}

  @keyframes rise{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:none}}
  @media(prefers-reduced-motion:reduce){
    *{animation:none !important;transition:none !important}
  }
  :focus-visible{outline:2px solid var(--primary);outline-offset:2px;border-radius:8px}
</style>
</head>
<body>
  <div class="app">
    <!-- APP BAR -->
    <header class="appbar">
      <div class="appbar-top">
        <button class="icon-btn" aria-label="Back">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <div class="appbar-title">
          <h1>Time Table</h1>
          <p id="crumb">Class schedule · Week view</p>
        </div>
        <button class="icon-btn" aria-label="Export">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="M8 11l4 4 4-4"/><path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/></svg>
        </button>
      </div>

      <!-- Segmented -->
      <div class="segment" id="segment">
        <span class="thumb"></span>
        <button class="seg-btn active" data-view="class">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 4v16"/></svg>
          Class
        </button>
        <button class="seg-btn" data-view="faculty">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.3 3.1-5.5 7-5.5s7 2.2 7 5.5"/></svg>
          Faculty
        </button>
      </div>
    </header>

    <!-- SELECTOR -->
    <div class="controls">
      <label class="picker">
        <span class="lead" id="pickerIcon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 4v16"/></svg>
        </span>
        <span class="meta">
          <span id="pickerLabel">Select class</span>
          <select id="entity" aria-label="Select"></select>
        </span>
        <span class="chev"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
      </label>
    </div>

    <!-- DAYS -->
    <nav class="days" id="days" aria-label="Weekday"></nav>

    <!-- LIST -->
    <main class="sheet">
      <div class="list-head">
        <h2 id="dayTitle">Monday</h2>
        <span class="count" id="dayCount"></span>
      </div>
      <div id="listRoot" class="timeline"></div>
    </main>
  </div>

<script>
/* ---------------- Subject theming ---------------- */
const SUBJECTS = {
  Mathematics:{a:"#2563eb",t:"#eaf1fe",code:"MAT"},
  Physics:{a:"#0891b2",t:"#e2f4f9",code:"PHY"},
  Chemistry:{a:"#d97706",t:"#fdf2e3",code:"CHE"},
  Biology:{a:"#0d9488",t:"#e4f5f3",code:"BIO"},
  English:{a:"#7c3aed",t:"#f1eafe",code:"ENG"},
  Hindi:{a:"#c026d3",t:"#fbeafb",code:"HIN"},
  History:{a:"#e11d48",t:"#fce8ed",code:"HIS"},
  Geography:{a:"#059669",t:"#e7f6f0",code:"GEO"},
  "Computer Sci":{a:"#4f46e5",t:"#ecebfd",code:"CS"},
  "Phys. Education":{a:"#ea580c",t:"#fdede3",code:"PE"},
};
const clr = s => SUBJECTS[s] || {a:"#1f5fe0",t:"#eaf1ff",code:"–"};

/* ---------------- Sample data ---------------- */
// period helper: [start,end,subject,teacher/class,room]
const P = (s,e,sub,who,room)=>({s,e,sub,who,room});
const BREAK = t => ({brk:t});

const DATA = {
  class:{
    "Class 9 · A":{
      Mon:[P("08:00","08:45","Mathematics","R. Sharma","204"),P("08:45","09:30","English","P. Nair","204"),BREAK("Short Break"),P("09:45","10:30","Physics","A. Khan","Lab 2"),P("10:30","11:15","Hindi","S. Verma","204"),BREAK("Lunch"),P("12:00","12:45","Computer Sci","N. Rao","Lab 1"),P("12:45","13:30","Phys. Education","T. Singh","Ground")],
      Tue:[P("08:00","08:45","Biology","M. Iyer","Lab 3"),P("08:45","09:30","Mathematics","R. Sharma","204"),BREAK("Short Break"),P("09:45","10:30","History","K. Das","204"),P("10:30","11:15","English","P. Nair","204"),BREAK("Lunch"),P("12:00","12:45","Chemistry","A. Khan","Lab 2"),P("12:45","13:30","Geography","K. Das","204")],
      Wed:[P("08:00","08:45","English","P. Nair","204"),P("08:45","09:30","Physics","A. Khan","Lab 2"),BREAK("Short Break"),P("09:45","10:30","Mathematics","R. Sharma","204"),P("10:30","11:15","Computer Sci","N. Rao","Lab 1"),BREAK("Lunch"),P("12:00","12:45","Hindi","S. Verma","204"),P("12:45","13:30","Biology","M. Iyer","Lab 3")],
      Thu:[P("08:00","08:45","Chemistry","A. Khan","Lab 2"),P("08:45","09:30","Geography","K. Das","204"),BREAK("Short Break"),P("09:45","10:30","English","P. Nair","204"),P("10:30","11:15","Mathematics","R. Sharma","204"),BREAK("Lunch"),P("12:00","12:45","History","K. Das","204"),P("12:45","13:30","Phys. Education","T. Singh","Ground")],
      Fri:[P("08:00","08:45","Mathematics","R. Sharma","204"),P("08:45","09:30","Biology","M. Iyer","Lab 3"),BREAK("Short Break"),P("09:45","10:30","Hindi","S. Verma","204"),P("10:30","11:15","Physics","A. Khan","Lab 2"),BREAK("Lunch"),P("12:00","12:45","English","P. Nair","204"),P("12:45","13:30","Computer Sci","N. Rao","Lab 1")],
      Sat:[P("08:00","08:45","Computer Sci","N. Rao","Lab 1"),P("08:45","09:30","Phys. Education","T. Singh","Ground"),BREAK("Short Break"),P("09:45","10:30","English","P. Nair","204"),P("10:30","11:15","Mathematics","R. Sharma","204")],
    },
    "Class 9 · B":{
      Mon:[P("08:00","08:45","English","P. Nair","206"),P("08:45","09:30","Mathematics","R. Sharma","206"),BREAK("Short Break"),P("09:45","10:30","Chemistry","A. Khan","Lab 2"),P("10:30","11:15","History","K. Das","206"),BREAK("Lunch"),P("12:00","12:45","Biology","M. Iyer","Lab 3"),P("12:45","13:30","Hindi","S. Verma","206")],
      Tue:[P("08:00","08:45","Mathematics","R. Sharma","206"),P("08:45","09:30","Physics","A. Khan","Lab 2"),BREAK("Short Break"),P("09:45","10:30","Geography","K. Das","206"),P("10:30","11:15","Computer Sci","N. Rao","Lab 1"),BREAK("Lunch"),P("12:00","12:45","English","P. Nair","206"),P("12:45","13:30","Phys. Education","T. Singh","Ground")],
      Wed:[],
      Thu:[P("08:00","08:45","Biology","M. Iyer","Lab 3"),P("08:45","09:30","English","P. Nair","206"),BREAK("Short Break"),P("09:45","10:30","Mathematics","R. Sharma","206"),P("10:30","11:15","Hindi","S. Verma","206"),BREAK("Lunch"),P("12:00","12:45","Chemistry","A. Khan","Lab 2")],
      Fri:[P("08:00","08:45","History","K. Das","206"),P("08:45","09:30","Mathematics","R. Sharma","206"),BREAK("Short Break"),P("09:45","10:30","Physics","A. Khan","Lab 2"),P("10:30","11:15","English","P. Nair","206")],
      Sat:[P("08:00","08:45","Phys. Education","T. Singh","Ground"),P("08:45","09:30","Computer Sci","N. Rao","Lab 1")],
    },
    "Class 10 · A":{
      Mon:[P("08:00","08:45","Physics","A. Khan","Lab 2"),P("08:45","09:30","Mathematics","R. Sharma","301"),BREAK("Short Break"),P("09:45","10:30","English","P. Nair","301"),P("10:30","11:15","Chemistry","A. Khan","Lab 2"),BREAK("Lunch"),P("12:00","12:45","Biology","M. Iyer","Lab 3"),P("12:45","13:30","Computer Sci","N. Rao","Lab 1")],
      Tue:[P("08:00","08:45","Mathematics","R. Sharma","301"),P("08:45","09:30","English","P. Nair","301"),BREAK("Short Break"),P("09:45","10:30","History","K. Das","301"),P("10:30","11:15","Physics","A. Khan","Lab 2"),BREAK("Lunch"),P("12:00","12:45","Hindi","S. Verma","301")],
      Wed:[P("08:00","08:45","Chemistry","A. Khan","Lab 2"),P("08:45","09:30","Mathematics","R. Sharma","301"),BREAK("Short Break"),P("09:45","10:30","Geography","K. Das","301"),P("10:30","11:15","English","P. Nair","301"),BREAK("Lunch"),P("12:00","12:45","Biology","M. Iyer","Lab 3")],
      Thu:[],Fri:[],Sat:[],
    },
  },
  faculty:{
    "R. Sharma":{
      Mon:[P("08:00","08:45","Mathematics","Class 9 · A","204"),P("08:45","09:30","Mathematics","Class 9 · B","206"),BREAK("Free / Prep"),P("09:45","10:30","Mathematics","Class 10 · A","301")],
      Tue:[P("08:45","09:30","Mathematics","Class 9 · A","204"),P("08:00","08:45","Mathematics","Class 10 · A","301"),P("08:00","08:45","Mathematics","Class 9 · B","206")],
      Wed:[P("09:45","10:30","Mathematics","Class 9 · A","204"),P("08:45","09:30","Mathematics","Class 10 · A","301")],
      Thu:[P("10:30","11:15","Mathematics","Class 9 · A","204"),P("09:45","10:30","Mathematics","Class 9 · B","206")],
      Fri:[P("08:00","08:45","Mathematics","Class 9 · A","204"),P("08:45","09:30","Mathematics","Class 9 · B","206"),P("10:30","11:15","Mathematics","Class 10 · A","301")],
      Sat:[P("10:30","11:15","Mathematics","Class 9 · A","204")],
    },
    "A. Khan":{
      Mon:[P("08:00","08:45","Physics","Class 10 · A","Lab 2"),P("09:45","10:30","Physics","Class 9 · A","Lab 2"),P("09:45","10:30","Chemistry","Class 9 · B","Lab 2")],
      Tue:[P("12:00","12:45","Chemistry","Class 9 · A","Lab 2"),P("08:45","09:30","Physics","Class 9 · B","Lab 2"),P("10:30","11:15","Physics","Class 10 · A","Lab 2")],
      Wed:[P("08:45","09:30","Physics","Class 9 · A","Lab 2"),P("08:00","08:45","Chemistry","Class 10 · A","Lab 2")],
      Thu:[P("08:00","08:45","Chemistry","Class 9 · A","Lab 2")],
      Fri:[P("10:30","11:15","Physics","Class 9 · A","Lab 2"),P("09:45","10:30","Physics","Class 9 · B","Lab 2")],
      Sat:[],
    },
    "P. Nair":{
      Mon:[P("08:45","09:30","English","Class 9 · A","204"),P("08:00","08:45","English","Class 9 · B","206"),P("09:45","10:30","English","Class 10 · A","301")],
      Tue:[P("10:30","11:15","English","Class 9 · A","204"),P("08:45","09:30","English","Class 10 · A","301"),P("12:00","12:45","English","Class 9 · B","206")],
      Wed:[P("08:00","08:45","English","Class 9 · A","204"),P("10:30","11:15","English","Class 10 · A","301")],
      Thu:[P("09:45","10:30","English","Class 9 · A","204"),P("08:45","09:30","English","Class 9 · B","206")],
      Fri:[P("12:00","12:45","English","Class 9 · A","204"),P("10:30","11:15","English","Class 9 · B","206")],
      Sat:[P("09:45","10:30","English","Class 9 · A","204")],
    },
  }
};

/* ---------------- State ---------------- */
const DOWS=["Mon","Tue","Wed","Thu","Fri","Sat"];
const FULL={Mon:"Monday",Tue:"Tuesday",Wed:"Wednesday",Thu:"Thursday",Fri:"Friday",Sat:"Saturday"};
// build weekday date numbers relative to today (Mon-Sat of current week)
const now=new Date();
const monday=new Date(now); monday.setDate(now.getDate()-((now.getDay()+6)%7));
const DATES=DOWS.map((_,i)=>{const d=new Date(monday);d.setDate(monday.getDate()+i);return d.getDate();});
const todayIdx=(now.getDay()+6)%7; // 0=Mon
let state={view:"class",entity:null,day:DOWS[Math.min(todayIdx,5)]};

/* ---------------- Rendering ---------------- */
const $=s=>document.querySelector(s);
const entitySel=$("#entity");

function fillEntities(){
  const keys=Object.keys(DATA[state.view]);
  entitySel.innerHTML=keys.map(k=>`<option value="${k}">${k}</option>`).join("");
  state.entity=keys[0];
  entitySel.value=state.entity;
  $("#pickerLabel").textContent = state.view==="class"?"Select class":"Select faculty";
  $("#pickerIcon").innerHTML = state.view==="class"
    ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 4v16"/></svg>'
    : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.3 3.1-5.5 7-5.5s7 2.2 7 5.5"/></svg>';
  $("#crumb").textContent = (state.view==="class"?"Class schedule":"Faculty schedule")+" · Week view";
}

function buildDays(){
  $("#days").innerHTML=DOWS.map((d,i)=>`
    <button class="day ${d===state.day?'active':''} ${i===todayIdx?'today':''}" data-day="${d}">
      <span class="dow">${d}</span>
      <span class="num">${DATES[i]}</span>
    </button>`).join("");
}

const svg={
  who:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.2"/><path d="M6 20c0-3 2.7-5 6-5s6 2 6 5"/></svg>',
  room:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21V5a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v16"/><path d="M3 21h18M9 8h.01M9 12h.01M9 16h.01"/></svg>',
  cls:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6l9-3 9 3-9 3-9-3z"/><path d="M21 6v5M7 8.5V13c0 1.4 2.2 2.5 5 2.5s5-1.1 5-2.5V8.5"/></svg>',
  coffee:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 8h13v5a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V8z"/><path d="M17 9h2a2 2 0 0 1 0 4h-2"/><path d="M8 2v2M12 2v2"/></svg>',
};

function render(){
  const rows=(DATA[state.view][state.entity]||{})[state.day]||[];
  const classes=rows.filter(r=>!r.brk);
  $("#dayTitle").textContent=FULL[state.day];
  $("#dayCount").textContent = classes.length ? `${classes.length} periods · ${new Set(classes.map(r=>r.sub)).size} subjects` : "";

  const root=$("#listRoot");
  if(!classes.length){
    root.innerHTML=`
      <div class="empty">
        <div class="art">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2.5"/><path d="M3 9h18M8 2v4M16 2v4"/><path d="M9.2 14.2a1.8 1.8 0 1 1 2.9 1.4c-.6.5-1.1.9-1.1 1.7"/><path d="M11 19.4h.01"/></svg>
        </div>
        <h3>Nothing scheduled</h3>
        <p>${FULL[state.day]} has no periods for <b>${state.entity}</b>. Pick another day or ${state.view==='class'?'class':'faculty member'}.</p>
      </div>`;
    return;
  }

  root.innerHTML=rows.map((r,i)=>{
    if(r.brk){
      return `<div class="breakcard" style="animation-delay:${i*40}ms">${svg.coffee}<span>${r.brk}</span></div>`;
    }
    const c=clr(r.sub);
    const whoIcon = state.view==="class" ? svg.who : svg.cls;
    return `
      <article class="period" style="--accent:${c.a};--accent-tint:${c.t};animation-delay:${i*45}ms">
        <div class="p-time">
          <div class="start">${r.s}</div>
          <div class="end">${r.e}</div>
        </div>
        <div class="p-body">
          <div class="p-toprow"><span class="badge">${c.code}</span><span class="p-title">${r.sub}</span></div>
          <div class="p-sub">
            <span class="m">${whoIcon}${r.who}</span>
            <span class="m">${svg.room}Room ${r.room}</span>
          </div>
        </div>
      </article>`;
  }).join("");
}

/* ---------------- Events ---------------- */
$("#segment").addEventListener("click",e=>{
  const b=e.target.closest(".seg-btn"); if(!b) return;
  const v=b.dataset.view; if(v===state.view) return;
  state.view=v;
  $("#segment").classList.toggle("faculty",v==="faculty");
  document.querySelectorAll(".seg-btn").forEach(x=>x.classList.toggle("active",x===b));
  fillEntities(); render();
});
entitySel.addEventListener("change",()=>{state.entity=entitySel.value;render();});
$("#days").addEventListener("click",e=>{
  const d=e.target.closest(".day"); if(!d) return;
  state.day=d.dataset.day;
  document.querySelectorAll(".day").forEach(x=>x.classList.toggle("active",x===d));
  render();
});

/* ---------------- Init ---------------- */
fillEntities();
buildDays();
render();
</script>
</body>
</html>
