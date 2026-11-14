
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#ffffff" />
<title>Review</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0e1b2c;--ink-soft:#33435b;--muted:#7a879e;
    --line:#e9edf5;--line-strong:#dbe3ef;
    --bg:#f2f5fb;--card:#fff;
    --brand:#0e6ba8;--brand-deep:#0b4f7d;--brand-tint:#e7f2f9;
    --ok:#0a9d63;--ok-tint:#e6f6ef;
    --no:#e11d48;--no-tint:#fdeaee;
    --warn:#ef9614;
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
  .app{
    width:100%;max-width:468px;min-height:100vh;background:var(--bg);
    display:flex;flex-direction:column;position:relative;overflow:hidden;
  }
  @media(min-width:540px){
    body{padding:26px 16px}
    .app{min-height:auto;height:calc(100vh - 52px);border-radius:30px;box-shadow:var(--shadow-lg);border:1px solid var(--line)}
  }

  /* App bar */
  .appbar{
    position:sticky;top:0;z-index:20;
    background:rgba(255,255,255,.85);backdrop-filter:saturate(180%) blur(14px);
    -webkit-backdrop-filter:saturate(180%) blur(14px);
    border-bottom:1px solid var(--line);padding:14px 16px 0;
  }
  .bar-row{display:flex;align-items:center;gap:10px}
  .title-wrap{flex:1;min-width:0}
  .title-wrap h1{font-family:var(--fd);font-weight:800;font-size:22px;letter-spacing:-.02em;color:var(--brand)}
  .title-wrap p{font-size:12px;color:var(--muted);font-weight:500;margin-top:1px}
  .icon-btn{
    width:40px;height:40px;flex:0 0 auto;display:grid;place-items:center;
    border-radius:13px;border:1px solid var(--line);background:#fff;color:var(--ink-soft);
    box-shadow:var(--shadow-sm);cursor:pointer;transition:transform .15s,background .15s,color .15s;
  }
  .icon-btn:active{transform:scale(.93)}
  .icon-btn:hover{background:var(--brand-tint);color:var(--brand)}
  .icon-btn.on{background:var(--brand);color:#fff;border-color:transparent}
  .icon-btn svg{width:19px;height:19px}

  /* Search */
  .search{max-height:0;overflow:hidden;transition:max-height .28s ease,margin .28s ease;margin-top:0}
  .search.open{max-height:60px;margin-top:12px}
  .search-in{display:flex;align-items:center;gap:9px;background:#fff;border:1px solid var(--line-strong);border-radius:13px;padding:10px 13px;box-shadow:var(--shadow-sm)}
  .search-in svg{width:17px;height:17px;color:var(--muted);flex:0 0 auto}
  .search-in input{flex:1;border:0;outline:none;font-family:var(--fb);font-size:14.5px;color:var(--ink);background:transparent}
  .search-in .clr{color:var(--muted);cursor:pointer;display:none}
  .search-in .clr.show{display:block}

  /* Filter chips */
  .chips{display:flex;gap:8px;padding:12px 0 12px;overflow-x:auto;scrollbar-width:none}
  .chips::-webkit-scrollbar{display:none}
  .chip{
    flex:0 0 auto;border:1px solid var(--line-strong);background:#fff;color:var(--ink-soft);
    font-family:var(--fd);font-weight:700;font-size:13px;padding:8px 14px;border-radius:999px;cursor:pointer;
    display:flex;align-items:center;gap:7px;transition:all .16s;
  }
  .chip .n{font-size:11px;background:#eef2f8;color:var(--muted);border-radius:999px;padding:1px 7px;font-weight:700;transition:all .16s}
  .chip.active{background:var(--brand);border-color:transparent;color:#fff;box-shadow:0 8px 18px -8px rgba(14,107,168,.6)}
  .chip.active .n{background:rgba(255,255,255,.25);color:#fff}

  /* List */
  .sheet{flex:1;overflow-y:auto;padding:6px 16px 100px;scrollbar-width:none}
  .sheet::-webkit-scrollbar{display:none}

  .card{
    background:var(--card);border:1px solid var(--line);border-radius:var(--r);
    padding:16px;margin-bottom:14px;box-shadow:var(--shadow-sm);cursor:pointer;
    animation:rise .4s cubic-bezier(.2,.7,.2,1) both;
    transition:transform .16s,box-shadow .16s,border-color .16s;
  }
  .card:hover{box-shadow:var(--shadow-md);border-color:var(--line-strong)}
  .card:active{transform:scale(.992)}

  .c-top{display:flex;align-items:flex-start;gap:12px}
  .avatar{
    width:46px;height:46px;flex:0 0 auto;border-radius:14px;display:grid;place-items:center;
    color:#fff;font-family:var(--fd);font-weight:800;font-size:19px;
    box-shadow:inset 0 -10px 20px rgba(0,0,0,.12);
  }
  .c-head{flex:1;min-width:0}
  .c-head .name{font-family:var(--fd);font-weight:700;font-size:16px;letter-spacing:-.01em;line-height:1.25;
    white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .c-head .grp{font-size:11.5px;color:var(--muted);font-weight:600;letter-spacing:.06em;margin-top:3px}
  .kebab{
    width:32px;height:32px;flex:0 0 auto;border:0;background:transparent;border-radius:9px;
    color:var(--muted);cursor:pointer;display:grid;place-items:center;transition:background .15s,color .15s;
  }
  .kebab:hover{background:#eef2f8;color:var(--ink-soft)}
  .kebab svg{width:20px;height:20px}

  .c-meta{display:flex;align-items:center;justify-content:space-between;gap:10px;
    margin:13px 0 12px;padding-top:12px;border-top:1px solid var(--line)}
  .date{display:flex;align-items:center;gap:7px;color:var(--ink-soft);font-size:13px;font-weight:600}
  .date svg{width:15px;height:15px;color:var(--brand)}

  .status{
    border:1px solid;background:#fff;font-family:var(--fd);font-weight:700;font-size:12px;
    padding:5px 12px;border-radius:999px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;
    transition:transform .14s,background .15s;
  }
  .status:active{transform:scale(.94)}
  .status .dot{width:6px;height:6px;border-radius:50%}
  .status.ok{color:var(--ok);border-color:#bfe6d3}
  .status.ok .dot{background:var(--ok)}
  .status.ok:hover{background:var(--ok-tint)}
  .status.no{color:var(--no);border-color:#f6c9d3}
  .status.no .dot{background:var(--no)}
  .status.no:hover{background:var(--no-tint)}

  .kv{font-size:13.5px;color:var(--ink-soft);margin-bottom:7px;line-height:1.4}
  .kv b{font-family:var(--fd);font-weight:700;color:var(--ink)}
  .kv .val{color:var(--ink-soft);font-weight:500}
  .modeline{display:flex;align-items:center;flex-wrap:wrap;gap:9px;margin-bottom:14px}
  .tag{
    font-family:var(--fd);font-weight:700;font-size:11px;color:#fff;
    background:linear-gradient(135deg,#f5a623,#ef9614);
    padding:4px 11px;border-radius:999px;letter-spacing:.02em;box-shadow:0 4px 10px -4px rgba(239,150,20,.6)
  }

  .prog-head{display:flex;align-items:baseline;justify-content:space-between;margin-bottom:7px}
  .prog-head .lbl{font-size:11.5px;color:var(--muted);font-weight:600;letter-spacing:.04em;text-transform:uppercase}
  .prog-head .num{font-family:var(--fd);font-weight:800;font-size:14px}
  .prog-head .num .done{color:var(--ok)}
  .prog-head .num .done.zero{color:var(--muted)}
  .prog-head .num .sep{color:var(--muted);font-weight:600;margin:0 2px}
  .prog-head .num .tot{color:var(--muted)}
  .track{height:8px;border-radius:999px;background:#eef2f8;overflow:hidden}
  .fill{height:100%;border-radius:999px;background:linear-gradient(90deg,var(--brand),var(--brand-deep));
    width:0;transition:width .6s cubic-bezier(.3,.8,.3,1)}
  .fill.full{background:linear-gradient(90deg,#12b981,#0a9d63)}

  /* Empty */
  .empty{padding:64px 26px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:6px;animation:rise .4s ease both}
  .empty .art{width:98px;height:98px;border-radius:28px;margin-bottom:12px;display:grid;place-items:center;
    background:linear-gradient(160deg,#e6f2fb,#e7f6ef);color:var(--brand)}
  .empty .art svg{width:46px;height:46px}
  .empty h3{font-family:var(--fd);font-weight:800;font-size:17px}
  .empty p{font-size:13.5px;color:var(--muted);max-width:250px;line-height:1.5}

  /* FAB */
  .fab{
    position:absolute;right:18px;bottom:20px;z-index:30;
    height:56px;padding:0 20px 0 18px;border:0;border-radius:18px;cursor:pointer;
    background:linear-gradient(135deg,#1685c9,var(--brand-deep));color:#fff;
    font-family:var(--fd);font-weight:700;font-size:15px;
    display:flex;align-items:center;gap:9px;box-shadow:0 16px 30px -10px rgba(11,79,125,.65);
    transition:transform .16s,box-shadow .16s;
  }
  .fab:active{transform:scale(.95)}
  .fab svg{width:20px;height:20px}

  /* Overlay + sheet */
  .overlay{position:absolute;inset:0;z-index:50;background:rgba(11,30,55,.42);
    backdrop-filter:blur(2px);opacity:0;pointer-events:none;transition:opacity .28s}
  .overlay.show{opacity:1;pointer-events:auto}
  .osheet{
    position:absolute;left:0;right:0;bottom:0;background:#fff;
    border-radius:26px 26px 0 0;box-shadow:0 -18px 50px -20px rgba(16,42,80,.4);
    transform:translateY(102%);transition:transform .34s cubic-bezier(.3,.9,.3,1);
    max-height:88%;display:flex;flex-direction:column;
  }
  .overlay.show .osheet{transform:translateY(0)}
  .grip{width:42px;height:5px;border-radius:999px;background:#d7dfea;margin:11px auto 4px}
  .osheet-head{display:flex;align-items:center;justify-content:space-between;padding:6px 20px 10px;border-bottom:1px solid var(--line)}
  .osheet-head h3{font-family:var(--fd);font-weight:800;font-size:17px}
  .osheet-body{padding:16px 20px 24px;overflow-y:auto}
  .x{width:34px;height:34px;border:0;background:#f1f4f9;border-radius:10px;color:var(--ink-soft);cursor:pointer;display:grid;place-items:center}
  .x:active{transform:scale(.92)}
  .x svg{width:18px;height:18px}

  /* menu items */
  .menu-item{display:flex;align-items:center;gap:13px;width:100%;border:0;background:transparent;
    padding:14px 6px;font-family:var(--fb);font-size:15px;font-weight:600;color:var(--ink);cursor:pointer;
    border-radius:12px;text-align:left;transition:background .14s}
  .menu-item:hover{background:#f4f7fb}
  .menu-item .mi{width:38px;height:38px;flex:0 0 auto;border-radius:11px;display:grid;place-items:center;background:var(--brand-tint);color:var(--brand)}
  .menu-item .mi svg{width:18px;height:18px}
  .menu-item.danger{color:var(--no)}
  .menu-item.danger .mi{background:var(--no-tint);color:var(--no)}

  /* detail rows */
  .drow{display:flex;justify-content:space-between;gap:14px;padding:12px 2px;border-bottom:1px solid var(--line)}
  .drow:last-child{border-bottom:0}
  .drow .k{font-size:13px;color:var(--muted);font-weight:600}
  .drow .v{font-family:var(--fd);font-size:14px;font-weight:700;color:var(--ink);text-align:right}

  /* form */
  .field{margin-bottom:14px}
  .field label{display:block;font-family:var(--fd);font-weight:700;font-size:12.5px;color:var(--ink-soft);margin-bottom:7px}
  .field input,.field select{
    width:100%;border:1px solid var(--line-strong);border-radius:12px;padding:12px 13px;
    font-family:var(--fb);font-size:14.5px;color:var(--ink);background:#fff;outline:none;transition:border .15s,box-shadow .15s;
    -webkit-appearance:none;appearance:none;
  }
  .field select{background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none' stroke='%237a879e' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><path d='M6 9l6 6 6-6'/></svg>");background-repeat:no-repeat;background-position:right 12px center;padding-right:38px}
  .field input:focus,.field select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(14,107,168,.14)}
  .two{display:flex;gap:12px}.two .field{flex:1}
  .seg2{display:flex;gap:8px}
  .seg2 button{flex:1;border:1px solid var(--line-strong);background:#fff;border-radius:12px;padding:11px;
    font-family:var(--fd);font-weight:700;font-size:13px;color:var(--ink-soft);cursor:pointer;transition:all .15s}
  .seg2 button.sel{border-color:transparent;color:#fff}
  .seg2 button.sel.ok{background:var(--ok)}
  .seg2 button.sel.no{background:var(--no)}
  .save-btn{width:100%;margin-top:8px;border:0;border-radius:14px;padding:15px;cursor:pointer;
    background:linear-gradient(135deg,#1685c9,var(--brand-deep));color:#fff;font-family:var(--fd);font-weight:700;font-size:15.5px;
    box-shadow:0 14px 28px -12px rgba(11,79,125,.7);transition:transform .15s}
  .save-btn:active{transform:scale(.98)}

  /* confirm */
  .confirm .osheet-body{text-align:center;padding-top:22px}
  .confirm .warnart{width:70px;height:70px;border-radius:20px;margin:0 auto 14px;display:grid;place-items:center;background:var(--no-tint);color:var(--no)}
  .confirm .warnart svg{width:32px;height:32px}
  .confirm h4{font-family:var(--fd);font-weight:800;font-size:18px;margin-bottom:6px}
  .confirm p{font-size:13.5px;color:var(--muted);line-height:1.5;margin-bottom:20px}
  .confirm .row{display:flex;gap:10px}
  .confirm .row button{flex:1;padding:14px;border-radius:13px;font-family:var(--fd);font-weight:700;font-size:14.5px;cursor:pointer;transition:transform .14s}
  .confirm .row .cancel{border:1px solid var(--line-strong);background:#fff;color:var(--ink-soft)}
  .confirm .row .del{border:0;background:var(--no);color:#fff}
  .confirm .row button:active{transform:scale(.97)}

  /* toast */
  .toast{position:absolute;left:50%;bottom:92px;transform:translate(-50%,20px);z-index:60;
    background:var(--ink);color:#fff;font-size:13.5px;font-weight:600;padding:11px 18px;border-radius:13px;
    box-shadow:var(--shadow-lg);opacity:0;pointer-events:none;transition:opacity .25s,transform .25s;white-space:nowrap;max-width:90%}
  .toast.show{opacity:1;transform:translate(-50%,0)}

  @keyframes rise{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
  @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
  :focus-visible{outline:2px solid var(--brand);outline-offset:2px;border-radius:8px}
</style>
</head>
<body>
<div class="app">
  <!-- APP BAR -->
  <header class="appbar">
    <div class="bar-row">
      <div class="title-wrap">
        <h1>Review</h1>
        <p id="subcount">—</p>
      </div>
      <button class="icon-btn" id="searchToggle" aria-label="Search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
      </button>
      <button class="icon-btn" id="sortBtn" aria-label="Sort">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 4v16M7 20l-3-3M7 4l3 3"/><path d="M17 20V4M17 4l-3 3M17 20l3-3"/></svg>
      </button>
    </div>
    <div class="search" id="searchWrap">
      <div class="search-in">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
        <input id="searchInput" type="text" placeholder="Search review or name…" />
        <button class="clr" id="searchClear" aria-label="Clear"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
      </div>
    </div>
    <div class="chips" id="chips"></div>
  </header>

  <!-- LIST -->
  <main class="sheet"><div id="list"></div></main>

  <!-- FAB -->
  <button class="fab" id="addBtn">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
    New review
  </button>

  <!-- Overlay / sheet -->
  <div class="overlay" id="overlay"><div class="osheet" id="osheet"></div></div>
  <div class="toast" id="toast"></div>
</div>

<script>
/* ---------- seed data ---------- */
let SEQ=100;
let reviews=[
  {id:1,title:"mds test review",group:"STUDENTS",date:"2023-12-27",name:"Hitesh Joshi",mode:"Daily",tag:"Daily",status:"Active",done:52,total:52},
  {id:2,title:"Half yearly Exam PTM",group:"STUDENTS",date:"2023-12-25",name:"Shyam Lal Vaishnav",mode:"Custom Date",tag:"26 Dec, 28 Dec",status:"Inactive",done:1391,total:1424},
  {id:3,title:"Revision Test",group:"STUDENTS",date:"2023-12-09",name:"Prahlad Singh Rathore",mode:"Daily",tag:"Daily",status:"Active",done:0,total:0},
  {id:4,title:"Regular Weekly Review",group:"STUDENTS",date:"2023-12-09",name:"Prahlad Singh Rathore",mode:"Weekly",tag:"Wednesday",status:"Active",done:0,total:0},
  {id:5,title:"Report Card Review",group:"STUDENTS",date:"2023-12-09",name:"Prahlad Singh Rathore",mode:"Custom Date",tag:"05 Dec, 09 Dec",status:"Active",done:12,total:40},
  {id:6,title:"Annual Day Feedback",group:"PARENTS",date:"2023-12-02",name:"Meena Kumari",mode:"Weekly",tag:"Friday",status:"Inactive",done:8,total:30},
];

/* ---------- state ---------- */
let filter="all", query="", sortDesc=true;

/* ---------- helpers ---------- */
const $=s=>document.querySelector(s);
const AV=["#0e6ba8","#2a7f62","#7a4fd0","#c2683a","#b03e6a","#1f7a8c","#3a6cc2"];
const hash=s=>{let h=0;for(const c of s)h=(h*31+c.charCodeAt(0))>>>0;return h;};
const avatarColor=t=>AV[hash(t)%AV.length];
const M=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
const fmtDate=d=>{const[y,m,dd]=d.split("-");return `${dd} ${M[+m-1]} ${y}`;};
const esc=s=>String(s).replace(/[&<>"]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
function toast(msg){const t=$("#toast");t.textContent=msg;t.classList.add("show");clearTimeout(t._t);t._t=setTimeout(()=>t.classList.remove("show"),2000);}

/* ---------- filtered view ---------- */
function view(){
  let arr=reviews.slice();
  if(filter!=="all") arr=arr.filter(r=>r.status.toLowerCase()===filter);
  if(query){const q=query.toLowerCase();arr=arr.filter(r=>(r.title+" "+r.name).toLowerCase().includes(q));}
  arr.sort((a,b)=>sortDesc?b.date.localeCompare(a.date):a.date.localeCompare(b.date));
  return arr;
}

/* ---------- render chips ---------- */
function renderChips(){
  const counts={all:reviews.length,active:reviews.filter(r=>r.status==="Active").length,inactive:reviews.filter(r=>r.status==="Inactive").length};
  const defs=[["all","All"],["active","Active"],["inactive","Inactive"]];
  $("#chips").innerHTML=defs.map(([k,l])=>`<button class="chip ${filter===k?'active':''}" data-f="${k}">${l}<span class="n">${counts[k]}</span></button>`).join("");
}

/* ---------- render list ---------- */
function render(){
  renderChips();
  const arr=view();
  $("#subcount").textContent=`${reviews.length} total · ${reviews.filter(r=>r.status==="Active").length} active`;
  const list=$("#list");
  if(!arr.length){
    list.innerHTML=`<div class="empty">
      <div class="art"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5a2 2 0 0 1 2-2h9l5 5v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h4"/></svg></div>
      <h3>${query||filter!=='all'?'No matching reviews':'No reviews yet'}</h3>
      <p>${query?`Nothing matches “${esc(query)}”. Try another search.`:filter!=='all'?`No ${filter} reviews right now.`:'Tap “New review” to create your first one.'}</p>
    </div>`;
    return;
  }
  list.innerHTML=arr.map((r,i)=>{
    const pct=r.total>0?Math.round(r.done/r.total*100):0;
    const okCls=r.status==="Active"?"ok":"no";
    return `
    <article class="card" data-id="${r.id}" style="animation-delay:${i*45}ms">
      <div class="c-top">
        <div class="avatar" style="background:linear-gradient(160deg,${avatarColor(r.title)},${avatarColor(r.title)}cc)">${esc(r.title[0]||"?").toUpperCase()}</div>
        <div class="c-head">
          <div class="name">${esc(r.title)}</div>
          <div class="grp">${esc(r.group)}</div>
        </div>
        <button class="kebab" data-act="menu" aria-label="Options"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg></button>
      </div>
      <div class="c-meta">
        <span class="date"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2.5"/><path d="M3 9h18M8 2v4M16 2v4"/></svg>${fmtDate(r.date)}</span>
        <button class="status ${okCls}" data-act="status"><span class="dot"></span>${r.status}</button>
      </div>
      <p class="kv"><b>Review Name :</b> <span class="val">${esc(r.name)}</span></p>
      <div class="modeline"><span class="kv" style="margin:0"><b>Mode :</b> <span class="val">${esc(r.mode)}</span></span><span class="tag">${esc(r.tag)}</span></div>
      <div class="prog-head">
        <span class="lbl">Progress</span>
        <span class="num"><span class="done ${r.done===0?'zero':''}">${r.done}</span><span class="sep">/</span><span class="tot">${r.total}</span></span>
      </div>
      <div class="track"><div class="fill ${pct===100?'full':''}" data-pct="${pct}"></div></div>
    </article>`;
  }).join("");
  // animate bars
  requestAnimationFrame(()=>list.querySelectorAll(".fill").forEach(f=>f.style.width=f.dataset.pct+"%"));
}

/* ---------- overlay ---------- */
function openSheet(html,cls=""){
  const o=$("#overlay"),s=$("#osheet");
  s.className="osheet "+cls;s.innerHTML='<span class="grip"></span>'+html;
  o.classList.add("show");
}
function closeSheet(){$("#overlay").classList.remove("show");}
$("#overlay").addEventListener("click",e=>{if(e.target.id==="overlay")closeSheet();});

/* ---------- kebab menu ---------- */
function openMenu(r){
  openSheet(`
    <div class="osheet-head"><h3>${esc(r.title)}</h3><button class="x" data-x><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button></div>
    <div class="osheet-body" style="padding-top:6px">
      <button class="menu-item" data-m="view"><span class="mi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg></span>View details</button>
      <button class="menu-item" data-m="edit"><span class="mi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg></span>Edit review</button>
      <button class="menu-item" data-m="dup"><span class="mi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15V5a2 2 0 0 1 2-2h10"/></svg></span>Duplicate</button>
      <button class="menu-item" data-m="toggle"><span class="mi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a5 5 0 0 1 0 10H7A5 5 0 0 1 7 3z"/><circle cx="17" cy="8" r="2.4" fill="currentColor" stroke="none"/></svg></span>Mark ${r.status==="Active"?"Inactive":"Active"}</button>
      <button class="menu-item danger" data-m="delete"><span class="mi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg></span>Delete review</button>
    </div>`);
  $("#osheet").querySelectorAll("[data-m]").forEach(b=>b.addEventListener("click",()=>{
    const m=b.dataset.m;
    if(m==="view"){openDetail(r);}
    else if(m==="edit"){openForm(r);}
    else if(m==="dup"){const c={...r,id:++SEQ,title:r.title+" (copy)"};reviews.unshift(c);closeSheet();render();toast("Review duplicated");}
    else if(m==="toggle"){toggleStatus(r.id);closeSheet();}
    else if(m==="delete"){confirmDelete(r);}
  }));
}

/* ---------- detail ---------- */
function openDetail(r){
  const pct=r.total>0?Math.round(r.done/r.total*100):0;
  openSheet(`
    <div class="osheet-head"><h3>Review details</h3><button class="x" data-x><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button></div>
    <div class="osheet-body">
      <div style="display:flex;align-items:center;gap:13px;margin-bottom:6px">
        <div class="avatar" style="width:52px;height:52px;font-size:22px;background:linear-gradient(160deg,${avatarColor(r.title)},${avatarColor(r.title)}cc)">${esc(r.title[0]||"?").toUpperCase()}</div>
        <div><div style="font-family:var(--fd);font-weight:800;font-size:18px">${esc(r.title)}</div><div style="font-size:12px;color:var(--muted);font-weight:600;letter-spacing:.05em;margin-top:2px">${esc(r.group)}</div></div>
      </div>
      <div style="margin-top:10px">
        <div class="drow"><span class="k">Status</span><span class="v" style="color:${r.status==='Active'?'var(--ok)':'var(--no)'}">${r.status}</span></div>
        <div class="drow"><span class="k">Review Name</span><span class="v">${esc(r.name)}</span></div>
        <div class="drow"><span class="k">Date</span><span class="v">${fmtDate(r.date)}</span></div>
        <div class="drow"><span class="k">Mode</span><span class="v">${esc(r.mode)}</span></div>
        <div class="drow"><span class="k">Schedule</span><span class="v">${esc(r.tag)}</span></div>
        <div class="drow"><span class="k">Completed</span><span class="v">${r.done} / ${r.total} (${pct}%)</span></div>
      </div>
      <button class="save-btn" data-edit>Edit this review</button>
    </div>`);
  $("#osheet").querySelector("[data-edit]").addEventListener("click",()=>openForm(r));
}

/* ---------- form (add/edit) ---------- */
function openForm(r){
  const isEdit=!!r;
  const d=r||{title:"",group:"STUDENTS",date:new Date().toISOString().slice(0,10),name:"",mode:"Daily",tag:"Daily",status:"Active",done:0,total:0};
  openSheet(`
    <div class="osheet-head"><h3>${isEdit?"Edit review":"New review"}</h3><button class="x" data-x><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button></div>
    <div class="osheet-body">
      <div class="field"><label>Review title</label><input id="f_title" value="${esc(d.title)}" placeholder="e.g. Half yearly Exam PTM"></div>
      <div class="two">
        <div class="field"><label>Group</label><select id="f_group">
          ${["STUDENTS","TEACHERS","PARENTS"].map(g=>`<option ${d.group===g?"selected":""}>${g}</option>`).join("")}
        </select></div>
        <div class="field"><label>Date</label><input id="f_date" type="date" value="${d.date}"></div>
      </div>
      <div class="field"><label>Review name (person)</label><input id="f_name" value="${esc(d.name)}" placeholder="e.g. Hitesh Joshi"></div>
      <div class="two">
        <div class="field"><label>Mode</label><select id="f_mode">
          ${["Daily","Weekly","Custom Date"].map(m=>`<option ${d.mode===m?"selected":""}>${m}</option>`).join("")}
        </select></div>
        <div class="field"><label>Schedule tag</label><input id="f_tag" value="${esc(d.tag)}" placeholder="e.g. Wednesday"></div>
      </div>
      <div class="two">
        <div class="field"><label>Completed</label><input id="f_done" type="number" min="0" value="${d.done}"></div>
        <div class="field"><label>Total</label><input id="f_total" type="number" min="0" value="${d.total}"></div>
      </div>
      <div class="field"><label>Status</label>
        <div class="seg2" id="f_status">
          <button type="button" data-s="Active" class="${d.status==='Active'?'sel ok':''}">Active</button>
          <button type="button" data-s="Inactive" class="${d.status==='Inactive'?'sel no':''}">Inactive</button>
        </div>
      </div>
      <button class="save-btn" id="saveBtn">${isEdit?"Save changes":"Create review"}</button>
    </div>`);
  const sheet=$("#osheet");
  let status=d.status;
  sheet.querySelectorAll("#f_status button").forEach(b=>b.addEventListener("click",()=>{
    status=b.dataset.s;
    sheet.querySelectorAll("#f_status button").forEach(x=>x.className=x.dataset.s===status?("sel "+(status==="Active"?"ok":"no")):"");
  }));
  // mode auto-fills a sensible tag
  sheet.querySelector("#f_mode").addEventListener("change",e=>{
    const t=sheet.querySelector("#f_tag");
    if(e.target.value==="Daily")t.value="Daily";
    else if(e.target.value==="Weekly"&&(!t.value||t.value==="Daily"))t.value="Monday";
  });
  sheet.querySelector("#saveBtn").addEventListener("click",()=>{
    const g=id=>sheet.querySelector(id).value.trim();
    const title=g("#f_title"), name=g("#f_name");
    if(!title){toast("Please enter a review title");return;}
    const obj={
      id:isEdit?r.id:++SEQ,
      title, group:g("#f_group"), date:g("#f_date")||d.date, name:name||"—",
      mode:g("#f_mode"), tag:g("#f_tag")||g("#f_mode"),
      status, done:Math.max(0,+g("#f_done")||0), total:Math.max(0,+g("#f_total")||0)
    };
    if(isEdit){const i=reviews.findIndex(x=>x.id===r.id);reviews[i]=obj;toast("Review updated");}
    else{reviews.unshift(obj);toast("Review created");}
    closeSheet();render();
  });
}

/* ---------- confirm delete ---------- */
function confirmDelete(r){
  openSheet(`
    <div class="osheet-body">
      <div class="warnart"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg></div>
      <h4>Delete this review?</h4>
      <p>“${esc(r.title)}” will be permanently removed. This can't be undone.</p>
      <div class="row"><button class="cancel" data-x>Cancel</button><button class="del" data-del>Delete</button></div>
    </div>`,"confirm");
  $("#osheet").querySelector("[data-del]").addEventListener("click",()=>{
    reviews=reviews.filter(x=>x.id!==r.id);closeSheet();render();toast("Review deleted");
  });
}

/* ---------- status toggle ---------- */
function toggleStatus(id){
  const r=reviews.find(x=>x.id===id);if(!r)return;
  r.status=r.status==="Active"?"Inactive":"Active";
  render();toast(`Marked ${r.status}`);
}

/* ---------- list interactions (delegated) ---------- */
$("#list").addEventListener("click",e=>{
  const card=e.target.closest(".card");if(!card)return;
  const r=reviews.find(x=>x.id==card.dataset.id);if(!r)return;
  const actBtn=e.target.closest("[data-act]");
  if(actBtn){
    const a=actBtn.dataset.act;
    if(a==="menu")openMenu(r);
    else if(a==="status")toggleStatus(r.id);
    return;
  }
  openDetail(r);
});

/* close buttons inside sheets */
$("#overlay").addEventListener("click",e=>{if(e.target.closest("[data-x]"))closeSheet();});

/* ---------- top controls ---------- */
$("#addBtn").addEventListener("click",()=>openForm(null));
$("#sortBtn").addEventListener("click",()=>{sortDesc=!sortDesc;$("#sortBtn").classList.toggle("on",!sortDesc);render();toast(sortDesc?"Newest first":"Oldest first");});
$("#chips").addEventListener("click",e=>{const c=e.target.closest(".chip");if(!c)return;filter=c.dataset.f;render();});

const sInput=$("#searchInput"),sClear=$("#searchClear");
$("#searchToggle").addEventListener("click",()=>{
  const w=$("#searchWrap"),open=w.classList.toggle("open");
  $("#searchToggle").classList.toggle("on",open);
  if(open)setTimeout(()=>sInput.focus(),120);else{sInput.value="";query="";sClear.classList.remove("show");render();}
});
sInput.addEventListener("input",()=>{query=sInput.value.trim();sClear.classList.toggle("show",!!query);render();});
sClear.addEventListener("click",()=>{sInput.value="";query="";sClear.classList.remove("show");sInput.focus();render();});

/* ---------- init ---------- */
render();
</script>
</body>
</html>
