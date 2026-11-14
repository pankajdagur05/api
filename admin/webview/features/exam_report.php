
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#ffffff" />
<title>Exam Report</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0e1b2c;--ink-soft:#33435b;--muted:#7a879e;
    --line:#e9edf5;--line-strong:#dbe3ef;
    --bg:#f2f5fb;--card:#fff;
    --brand:#0e6ba8;--brand-deep:#0b4f7d;--brand-tint:#e7f2f9;
    --ok:#0a9d63;--ok-tint:#e6f6ef;--no:#e11d48;--warn:#e0870f;
    --shadow-sm:0 1px 2px rgba(16,42,80,.06);
    --shadow-md:0 8px 26px -10px rgba(16,42,80,.20);
    --shadow-lg:0 22px 50px -18px rgba(16,42,80,.30);
    --r:20px;
    --fd:'Plus Jakarta Sans',system-ui,sans-serif;
    --fb:'Inter',system-ui,sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
  html,body{height:100%}
  body{
    font-family:var(--fb);color:var(--ink);
    background:
      radial-gradient(1100px 460px at 108% -8%, #e2f0f8 0, transparent 60%),
      radial-gradient(900px 460px at -12% 2%, #eaf5f0 0, transparent 55%),
      var(--bg);
    min-height:100vh;display:flex;justify-content:center;-webkit-font-smoothing:antialiased;
  }
  .app{width:100%;max-width:468px;height:100vh;height:100dvh;background:var(--bg);position:relative;overflow:hidden}
  @media(min-width:540px){body{padding:26px 16px}.app{height:calc(100vh - 52px);border-radius:30px;box-shadow:var(--shadow-lg);border:1px solid var(--line)}}

  /* three stacked views */
  .view{position:absolute;inset:0;display:flex;flex-direction:column;background:var(--bg);
    transition:transform .34s cubic-bezier(.3,.9,.3,1);will-change:transform}
  #detailView,#subjectView{transform:translateX(100%)}
  .app.at-detail  #listView{transform:translateX(-16%)}
  .app.at-detail  #detailView{transform:translateX(0)}
  .app.at-subject #listView{transform:translateX(-16%)}
  .app.at-subject #detailView{transform:translateX(-16%)}
  .app.at-subject #subjectView{transform:translateX(0)}

  /* App bar */
  .appbar{position:sticky;top:0;z-index:20;background:rgba(255,255,255,.86);
    backdrop-filter:saturate(180%) blur(14px);-webkit-backdrop-filter:saturate(180%) blur(14px);
    border-bottom:1px solid var(--line);padding:14px 16px 0}
  .bar-row{display:flex;align-items:center;gap:10px}
  .title-wrap{flex:1;min-width:0}
  .title-wrap h1{font-family:var(--fd);font-weight:800;font-size:20px;letter-spacing:-.02em;color:var(--brand);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .title-wrap p{font-size:12px;color:var(--muted);font-weight:500;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .icon-btn{width:40px;height:40px;flex:0 0 auto;display:grid;place-items:center;border-radius:13px;border:1px solid var(--line);
    background:#fff;color:var(--ink-soft);box-shadow:var(--shadow-sm);cursor:pointer;transition:transform .15s,background .15s,color .15s}
  .icon-btn:active{transform:scale(.93)}
  .icon-btn:hover{background:var(--brand-tint);color:var(--brand)}
  .icon-btn.on{background:var(--brand);color:#fff;border-color:transparent}
  .icon-btn svg{width:19px;height:19px}

  .search{max-height:0;overflow:hidden;transition:max-height .28s,margin .28s;margin-top:0}
  .search.open{max-height:60px;margin-top:12px}
  .search-in{display:flex;align-items:center;gap:9px;background:#fff;border:1px solid var(--line-strong);border-radius:13px;padding:10px 13px;box-shadow:var(--shadow-sm)}
  .search-in svg{width:17px;height:17px;color:var(--muted);flex:0 0 auto}
  .search-in input{flex:1;border:0;outline:none;font-family:var(--fb);font-size:14.5px;color:var(--ink);background:transparent}
  .search-in .clr{color:var(--muted);cursor:pointer;display:none}
  .search-in .clr.show{display:block}
  .barpad{height:14px}

  .sheet{flex:1;overflow-y:auto;padding:14px 16px 40px;scrollbar-width:none}
  .sheet::-webkit-scrollbar{display:none}

  /* LIST cards */
  .exam{background:var(--card);border:1px solid var(--line);border-radius:var(--r);padding:16px;margin-bottom:14px;box-shadow:var(--shadow-sm);cursor:pointer;
    animation:rise .4s cubic-bezier(.2,.7,.2,1) both;transition:transform .16s,box-shadow .16s,border-color .16s}
  .exam:hover{box-shadow:var(--shadow-md);border-color:var(--line-strong)}
  .exam:active{transform:scale(.992)}
  .exam-top{display:flex;align-items:center;gap:13px}
  .avatar{width:46px;height:46px;flex:0 0 auto;border-radius:50%;display:grid;place-items:center;color:#fff;font-family:var(--fd);font-weight:800;font-size:19px;box-shadow:inset 0 -9px 18px rgba(0,0,0,.14)}
  .exam-name{flex:1;min-width:0;font-family:var(--fd);font-weight:700;font-size:17px;letter-spacing:-.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .go{width:30px;height:30px;flex:0 0 auto;display:grid;place-items:center;color:var(--muted);border-radius:9px;background:#f3f6fb}
  .go svg{width:18px;height:18px}
  .exam-stats{display:flex;flex-wrap:wrap;gap:8px 10px;margin-top:14px;padding-top:13px;border-top:1px solid var(--line)}
  .stat{display:flex;align-items:center;gap:7px}
  .stat .slabel{font-size:12.5px;color:var(--ink-soft);font-weight:600}
  .stat .sval{font-family:var(--fd);font-weight:800;font-size:13px;color:#fff;background:var(--brand);min-width:30px;text-align:center;padding:4px 9px;border-radius:9px;box-shadow:0 4px 10px -5px rgba(14,107,168,.7)}
  .stat .sval.zero{background:#9aa8bf;box-shadow:none}

  /* DETAIL summary + accordion */
  .summary{display:flex;gap:10px;padding:14px 16px 2px}
  .sbox{flex:1;background:#fff;border:1px solid var(--line);border-radius:16px;padding:13px 12px;text-align:center;box-shadow:var(--shadow-sm)}
  .sbox .big{font-family:var(--fd);font-weight:800;font-size:20px;color:var(--brand);line-height:1}
  .sbox .cap{font-size:11px;color:var(--muted);font-weight:600;margin-top:5px;letter-spacing:.02em}
  .expandbar{display:flex;justify-content:flex-end;padding:12px 16px 0}
  .linkbtn{border:0;background:transparent;color:var(--brand);font-family:var(--fd);font-weight:700;font-size:13px;cursor:pointer;display:flex;align-items:center;gap:6px;padding:6px 8px;border-radius:9px;transition:background .15s}
  .linkbtn:hover{background:var(--brand-tint)}
  .linkbtn svg{width:15px;height:15px;transition:transform .25s}
  .linkbtn.allopen svg{transform:rotate(180deg)}

  .group{background:var(--card);border:1px solid var(--line);border-radius:var(--r);margin-bottom:13px;overflow:hidden;box-shadow:var(--shadow-sm);animation:rise .4s cubic-bezier(.2,.7,.2,1) both}
  .g-head{display:flex;align-items:center;gap:12px;padding:16px;cursor:pointer;user-select:none;transition:background .15s}
  .g-head:hover{background:#f8fafd}
  .flag{width:36px;height:36px;flex:0 0 auto;border-radius:11px;background:var(--brand-tint);color:var(--brand);display:grid;place-items:center}
  .flag svg{width:19px;height:19px}
  .g-name{flex:1;min-width:0;font-family:var(--fd);font-weight:700;font-size:15.5px;letter-spacing:-.01em}
  .g-count{font-family:var(--fd);font-weight:800;font-size:13px;color:#fff;background:var(--brand);min-width:28px;text-align:center;padding:3px 9px;border-radius:9px}
  .g-count.zero{background:#9aa8bf}
  .chev{width:22px;height:22px;color:var(--muted);flex:0 0 auto;transition:transform .28s}
  .group.open .chev{transform:rotate(180deg)}
  .g-body{max-height:0;overflow:hidden;transition:max-height .34s cubic-bezier(.3,.9,.3,1)}
  .g-inner{padding:0 16px 8px}

  .subject{background:#f7f9fc;border:1px solid var(--line);border-radius:14px;padding:13px 14px;margin-bottom:9px;cursor:pointer;transition:background .15s,transform .12s}
  .subject:hover{background:#eef3f9}
  .subject:active{transform:scale(.99)}
  .sub-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:9px}
  .sub-name{font-family:var(--fd);font-weight:700;font-size:14.5px;display:flex;align-items:center;gap:8px}
  .sub-name .arw{color:var(--muted)}.sub-name .arw svg{width:14px;height:14px;display:block}
  .sub-num{font-family:var(--fd);font-weight:800;font-size:13.5px}
  .sub-num .d{color:var(--brand)}.sub-num .d.zero{color:var(--muted)}
  .sub-num .s{color:var(--muted);margin:0 2px}.sub-num .t{color:var(--muted)}
  .track{height:7px;border-radius:999px;background:#e4e9f1;overflow:hidden}
  .fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--brand),var(--brand-deep));width:0;transition:width .6s cubic-bezier(.3,.8,.3,1)}
  .fill.full{background:linear-gradient(90deg,#12b981,#0a9d63)}
  .g-empty{padding:6px 4px 16px;text-align:center;color:var(--muted);font-size:13px;font-weight:500}

  /* SUBJECT -> student marks list */
  .marks-strip{display:flex;gap:10px;padding:14px 16px 2px}
  .mbox{flex:1;background:#fff;border:1px solid var(--line);border-radius:16px;padding:12px;text-align:center;box-shadow:var(--shadow-sm)}
  .mbox .big{font-family:var(--fd);font-weight:800;font-size:19px;line-height:1}
  .mbox .cap{font-size:10.5px;color:var(--muted);font-weight:600;margin-top:5px;letter-spacing:.02em}
  .mbox .big.brand{color:var(--brand)} .mbox .big.ok{color:var(--ok)} .mbox .big.warn{color:var(--warn)}

  .student{display:flex;align-items:center;gap:12px;background:var(--card);border:1px solid var(--line);border-radius:16px;
    padding:13px 15px;margin-bottom:10px;box-shadow:var(--shadow-sm);animation:rise .38s cubic-bezier(.2,.7,.2,1) both}
  .st-av{width:38px;height:38px;flex:0 0 auto;border-radius:50%;display:grid;place-items:center;color:#fff;font-family:var(--fd);font-weight:800;font-size:14px;box-shadow:inset 0 -7px 14px rgba(0,0,0,.14)}
  .st-name{flex:1;min-width:0;font-family:var(--fd);font-weight:600;font-size:14.5px;color:var(--ink);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .st-marks{flex:0 0 auto;font-family:var(--fd);font-weight:800;font-size:15px;display:flex;align-items:baseline;gap:2px}
  .st-marks .ob{font-size:16px}
  .st-marks .ob.pass{color:var(--ok)} .st-marks .ob.fail{color:var(--no)}
  .st-marks .of{color:var(--muted);font-weight:700;font-size:13px}
  .st-marks.pending .ob{color:var(--muted)}
  .st-tag{font-size:10px;font-weight:700;font-family:var(--fd);color:var(--warn);background:#fdf3e2;border-radius:999px;padding:2px 8px;margin-left:2px}

  /* empty state */
  .empty{padding:60px 26px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:6px;animation:rise .4s ease both}
  .empty .art{width:96px;height:96px;border-radius:28px;margin-bottom:10px;display:grid;place-items:center;background:linear-gradient(160deg,#e6f2fb,#e7f6ef);color:var(--brand)}
  .empty .art svg{width:44px;height:44px}
  .empty h3{font-family:var(--fd);font-weight:800;font-size:17px}
  .empty p{font-size:13.5px;color:var(--muted);max-width:240px;line-height:1.5}

  .toast{position:absolute;left:50%;bottom:26px;transform:translate(-50%,20px);z-index:60;background:var(--ink);color:#fff;font-size:13.5px;font-weight:600;padding:11px 18px;border-radius:13px;box-shadow:var(--shadow-lg);opacity:0;pointer-events:none;transition:opacity .25s,transform .25s;white-space:nowrap;max-width:90%}
  .toast.show{opacity:1;transform:translate(-50%,0)}

  @keyframes rise{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
  @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
  :focus-visible{outline:2px solid var(--brand);outline-offset:2px;border-radius:8px}
</style>
</head>
<body>
<div class="app" id="app">

  <!-- ===== LIST ===== -->
  <section class="view" id="listView">
    <header class="appbar">
      <div class="bar-row">
        <div class="title-wrap"><h1>Exam Report</h1><p id="listSub">—</p></div>
        <button class="icon-btn" id="searchToggle" aria-label="Search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></button>
      </div>
      <div class="search" id="searchWrap"><div class="search-in">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
        <input id="searchInput" type="text" placeholder="Search exam…" />
        <button class="clr" id="searchClear" aria-label="Clear"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
      </div></div>
      <div class="barpad"></div>
    </header>
    <main class="sheet"><div id="examList"></div></main>
  </section>

  <!-- ===== DETAIL ===== -->
  <section class="view" id="detailView">
    <header class="appbar">
      <div class="bar-row">
        <button class="icon-btn" id="backDetail" aria-label="Back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg></button>
        <div class="title-wrap"><h1 id="detailTitle">Exam</h1><p id="detailSub">—</p></div>
      </div>
      <div class="barpad"></div>
    </header>
    <div class="summary" id="summary"></div>
    <div class="expandbar"><button class="linkbtn" id="expandAll"><span id="expandLbl">Expand all</span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></button></div>
    <main class="sheet" style="padding-top:8px"><div id="groupList"></div></main>
  </section>

  <!-- ===== SUBJECT -> STUDENTS ===== -->
  <section class="view" id="subjectView">
    <header class="appbar">
      <div class="bar-row">
        <button class="icon-btn" id="backSubject" aria-label="Back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg></button>
        <div class="title-wrap"><h1 id="subjTitle">Subject</h1><p id="subjSub">—</p></div>
        <button class="icon-btn" id="stSearchToggle" aria-label="Search"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></button>
      </div>
      <div class="search" id="stSearchWrap"><div class="search-in">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
        <input id="stSearchInput" type="text" placeholder="Search student…" />
        <button class="clr" id="stSearchClear" aria-label="Clear"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
      </div></div>
      <div class="barpad"></div>
    </header>
    <div class="marks-strip" id="marksStrip"></div>
    <main class="sheet" style="padding-top:12px"><div id="studentList"></div></main>
  </section>

  <div class="toast" id="toast"></div>
</div>

<script>
/* ---------- data ---------- */
const AV=["#0e6ba8","#2a7f62","#7a4fd0","#c2683a","#b03e6a","#1f7a8c","#3a6cc2"];
const hash=s=>{let h=0;for(const c of String(s))h=(h*31+c.charCodeAt(0))>>>0;return h;};
const avColor=t=>AV[hash(t)%AV.length];
const MAX_MARKS=100;      // out-of for every student
const PASS=33;            // pass mark

const NAMES=["Aarav Sharma","Diya Patel","Vivaan Gupta","Ananya Singh","Aditya Verma","Ishita Nair","Kabir Mehta","Saanvi Rao","Arjun Reddy","Myra Iyer","Reyansh Joshi","Aadhya Das","Vihaan Khan","Anika Bose","Krishna Menon","Riya Kapoor","Dhruv Malhotra","Navya Pillai","Aryan Shah","Kiara Chauhan","Ayaan Sheikh","Prisha Jain","Rudra Yadav","Tara Kulkarni"];

const TEMPLATE=[
  {name:"LA (LKG-Prep)",subjects:[]},
  {name:"LKG HM",subjects:["Hindi","Maths","English"]},
  {name:"LKG EM",subjects:["Hindi","Maths","English"]},
  {name:"LA 1st",subjects:[]},
  {name:"UKG EM",subjects:["Hindi","Maths","English","EVS"]},
  {name:"Class 1 - A",subjects:["Hindi","Maths","English","EVS","GK"]},
  {name:"Class 2 - B",subjects:["Hindi","Maths","English"]},
  {name:"Class 3 - A",subjects:["Hindi","Maths","English","Science"]},
];
const TOTAL_PER=8; // students per subject (matches "/8")

function buildGroups(factor){
  return TEMPLATE.map((g,gi)=>({
    name:g.name,
    subjects:g.subjects.map((s,si)=>{
      const seed=((gi+1)*7+(si+1)*13)%10/10;
      const done=Math.min(TOTAL_PER,Math.round(TOTAL_PER*Math.max(0,Math.min(1,factor*(0.6+seed)))));
      return {name:s,done,total:TOTAL_PER};
    })
  }));
}
const RAW=[
  {title:"JUly",standard:33,student:792,factor:0},
  {title:"Term I",standard:33,student:792,factor:0},
  {title:"Hindi (14-05-2026)",standard:33,student:792,factor:0},
  {title:"yearly exam 25-26",standard:33,student:792,factor:.35},
  {title:"YEARLY EXAM",standard:33,student:792,factor:.62},
  {title:"HALF YEARLY",standard:33,student:792,factor:.9},
];
let EXAMS=RAW.map((e,i)=>({id:i+1,title:e.title,standard:e.standard,student:e.student,upload:Math.round(e.student*e.factor),groups:buildGroups(e.factor)}));

/* build a student marks list for a given (group,subject,done) */
function buildStudents(examTitle,groupName,subjectName,done,total){
  const base=hash(examTitle+"|"+groupName+"|"+subjectName);
  const out=[];
  for(let i=0;i<total;i++){
    const nm=NAMES[(base+i*5)%NAMES.length];
    const entered=i<done;                       // first `done` students have marks
    const obtained=entered?35+((base+i*17)%(MAX_MARKS-34)):null; // 35..99
    out.push({roll:i+1,name:nm,obtained,entered});
  }
  return out;
}

/* ---------- helpers ---------- */
const $=s=>document.querySelector(s);
const esc=s=>String(s).replace(/[&<>"]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
function toast(m){const t=$("#toast");t.textContent=m;t.classList.add("show");clearTimeout(t._t);t._t=setTimeout(()=>t.classList.remove("show"),1900);}
let query="";

/* ---------- LIST ---------- */
function renderList(){
  const arr=query?EXAMS.filter(e=>e.title.toLowerCase().includes(query.toLowerCase())):EXAMS;
  $("#listSub").textContent=`${EXAMS.length} exams`;
  const el=$("#examList");
  if(!arr.length){el.innerHTML=`<div class="empty"><div class="art"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5a2 2 0 0 1 2-2h9l5 5v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M14 3v5h5"/></svg></div><h3>No exams found</h3><p>Nothing matches “${esc(query)}”.</p></div>`;return;}
  el.innerHTML=arr.map((e,i)=>{
    const stat=(l,v)=>`<span class="stat"><span class="slabel">${l}</span><span class="sval ${v===0?'zero':''}">${v}</span></span>`;
    return `<article class="exam" data-id="${e.id}" style="animation-delay:${i*45}ms">
      <div class="exam-top">
        <div class="avatar" style="background:linear-gradient(160deg,${avColor(e.title)},${avColor(e.title)}cc)">${esc(e.title.trim()[0]||"?").toUpperCase()}</div>
        <div class="exam-name">${esc(e.title)}</div>
        <span class="go"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></span>
      </div>
      <div class="exam-stats">${stat("Standard",e.standard)}${stat("Student",e.student)}${stat("Upload Student",e.upload)}</div>
    </article>`;
  }).join("");
}

/* ---------- DETAIL ---------- */
let currentExam=null;
function openExam(id){
  const e=EXAMS.find(x=>x.id==id);if(!e)return;currentExam=e;
  $("#detailTitle").textContent=e.title;
  const totalSub=e.groups.reduce((a,g)=>a+g.subjects.length,0);
  const doneSub=e.groups.reduce((a,g)=>a+g.subjects.filter(s=>s.done>=s.total).length,0);
  $("#detailSub").textContent=`${e.groups.length} classes · ${totalSub} subjects`;
  $("#summary").innerHTML=`
    <div class="sbox"><div class="big">${e.groups.length}</div><div class="cap">Classes</div></div>
    <div class="sbox"><div class="big">${totalSub}</div><div class="cap">Subjects</div></div>
    <div class="sbox"><div class="big">${totalSub?Math.round(doneSub/totalSub*100):0}%</div><div class="cap">Uploaded</div></div>`;
  $("#groupList").innerHTML=e.groups.map((g,i)=>{
    const cnt=g.subjects.length;
    const body=cnt?g.subjects.map(s=>{
      const pct=s.total>0?Math.round(s.done/s.total*100):0;
      return `<div class="subject" data-grp="${esc(g.name)}" data-sub="${esc(s.name)}" data-done="${s.done}" data-total="${s.total}">
        <div class="sub-top">
          <span class="sub-name">${esc(s.name)}<span class="arw"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></span></span>
          <span class="sub-num"><span class="d ${s.done===0?'zero':''}">${s.done}</span><span class="s">/</span><span class="t">${s.total}</span></span>
        </div>
        <div class="track"><div class="fill ${pct===100?'full':''}" data-pct="${pct}"></div></div>
      </div>`;}).join(""):`<div class="g-empty">No subjects added to this class yet.</div>`;
    return `<div class="group" data-i="${i}" style="animation-delay:${i*40}ms">
      <div class="g-head" data-toggle>
        <span class="flag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3v18"/><path d="M5 4h11l-2.5 3.5L16 11H5"/></svg></span>
        <span class="g-name">${esc(g.name)}</span>
        <span class="g-count ${cnt===0?'zero':''}">${cnt}</span>
        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg>
      </div>
      <div class="g-body"><div class="g-inner">${body}</div></div>
    </div>`;
  }).join("");
  $("#expandAll").classList.remove("allopen");$("#expandLbl").textContent="Expand all";
  $("#groupList").scrollTop=0;
  $("#app").className="app at-detail";
}
function setOpen(group,open){
  const body=group.querySelector(".g-body");
  group.classList.toggle("open",open);
  body.style.maxHeight=open?body.querySelector(".g-inner").scrollHeight+16+"px":"0px";
  if(open)group.querySelectorAll(".fill").forEach(f=>requestAnimationFrame(()=>f.style.width=f.dataset.pct+"%"));
}

/* ---------- SUBJECT -> STUDENTS ---------- */
let currentStudents=[];let stQuery="";
function openSubject(groupName,subjectName,done,total){
  const students=buildStudents(currentExam.title,groupName,subjectName,done,total);
  currentStudents=students;stQuery="";
  $("#stSearchInput").value="";$("#stSearchClear").classList.remove("show");
  $("#stSearchWrap").classList.remove("open");$("#stSearchToggle").classList.remove("on");

  $("#subjTitle").textContent=subjectName;
  $("#subjSub").textContent=`${groupName} · ${currentExam.title}`;

  const entered=students.filter(s=>s.entered);
  const avg=entered.length?Math.round(entered.reduce((a,s)=>a+s.obtained,0)/entered.length):null;
  const passCount=entered.filter(s=>s.obtained>=PASS).length;
  $("#marksStrip").innerHTML=`
    <div class="mbox"><div class="big brand">${entered.length}/${students.length}</div><div class="cap">Entered</div></div>
    <div class="mbox"><div class="big ${avg===null?'':'ok'}">${avg===null?'—':avg}</div><div class="cap">Avg (/${MAX_MARKS})</div></div>
    <div class="mbox"><div class="big warn">${entered.length?passCount:'—'}</div><div class="cap">Passed</div></div>`;

  renderStudents();
  $("#studentList").scrollTop=0;
  $("#app").className="app at-subject";
}
function renderStudents(){
  const arr=stQuery?currentStudents.filter(s=>s.name.toLowerCase().includes(stQuery.toLowerCase())):currentStudents;
  const el=$("#studentList");
  if(!arr.length){
    el.innerHTML=`<div class="empty"><div class="art"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.3 3.1-5.5 7-5.5s7 2.2 7 5.5"/></svg></div><h3>No students</h3><p>${stQuery?`Nothing matches “${esc(stQuery)}”.`:'No students in this subject yet.'}</p></div>`;
    return;
  }
  el.innerHTML=arr.map((s,i)=>{
    const marks=s.entered
      ? `<span class="st-marks"><span class="ob ${s.obtained>=PASS?'pass':'fail'}">${s.obtained}</span><span class="of"> / ${MAX_MARKS}</span></span>`
      : `<span class="st-marks pending"><span class="ob">—</span><span class="of"> / ${MAX_MARKS}</span><span class="st-tag">Pending</span></span>`;
    return `<div class="student" style="animation-delay:${i*32}ms">
      <div class="st-av" style="background:linear-gradient(160deg,${avColor(s.name)},${avColor(s.name)}cc)">${esc(s.name.trim()[0]).toUpperCase()}</div>
      <div class="st-name">${esc(s.name)}</div>
      ${marks}
    </div>`;
  }).join("");
}

/* ---------- events ---------- */
$("#examList").addEventListener("click",e=>{const c=e.target.closest(".exam");if(c)openExam(c.dataset.id);});
$("#backDetail").addEventListener("click",()=>{$("#app").className="app";});
$("#backSubject").addEventListener("click",()=>{$("#app").className="app at-detail";});

$("#groupList").addEventListener("click",e=>{
  const head=e.target.closest("[data-toggle]");
  if(head){const g=head.closest(".group");setOpen(g,!g.classList.contains("open"));return;}
  const sub=e.target.closest(".subject");
  if(sub)openSubject(sub.dataset.grp,sub.dataset.sub,+sub.dataset.done,+sub.dataset.total);
});
$("#expandAll").addEventListener("click",()=>{
  const groups=[...$("#groupList").querySelectorAll(".group")];
  const anyClosed=groups.some(g=>!g.classList.contains("open"));
  groups.forEach(g=>setOpen(g,anyClosed));
  $("#expandAll").classList.toggle("allopen",anyClosed);
  $("#expandLbl").textContent=anyClosed?"Collapse all":"Expand all";
});

/* list search */
const sInput=$("#searchInput"),sClear=$("#searchClear");
$("#searchToggle").addEventListener("click",()=>{const w=$("#searchWrap"),o=w.classList.toggle("open");$("#searchToggle").classList.toggle("on",o);if(o)setTimeout(()=>sInput.focus(),120);else{sInput.value="";query="";sClear.classList.remove("show");renderList();}});
sInput.addEventListener("input",()=>{query=sInput.value.trim();sClear.classList.toggle("show",!!query);renderList();});
sClear.addEventListener("click",()=>{sInput.value="";query="";sClear.classList.remove("show");sInput.focus();renderList();});

/* student search */
const stIn=$("#stSearchInput"),stClr=$("#stSearchClear");
$("#stSearchToggle").addEventListener("click",()=>{const w=$("#stSearchWrap"),o=w.classList.toggle("open");$("#stSearchToggle").classList.toggle("on",o);if(o)setTimeout(()=>stIn.focus(),120);else{stIn.value="";stQuery="";stClr.classList.remove("show");renderStudents();}});
stIn.addEventListener("input",()=>{stQuery=stIn.value.trim();stClr.classList.toggle("show",!!stQuery);renderStudents();});
stClr.addEventListener("click",()=>{stIn.value="";stQuery="";stClr.classList.remove("show");stIn.focus();renderStudents();});

window.addEventListener("resize",()=>{$("#groupList").querySelectorAll(".group.open").forEach(g=>setOpen(g,true));});

/* ---------- init ---------- */
renderList();
</script>
</body>
</html>
