
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#ffffff" />
<title>Find Student</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#0e1b2c;--ink-soft:#33435b;--muted:#7a879e;
    --line:#e9edf5;--line-strong:#dbe3ef;
    --bg:#f2f5fb;--card:#fff;
    --brand:#0e6ba8;--brand-deep:#0b4f7d;--brand-tint:#e7f2f9;
    --ok:#0a9d63;--ok-tint:#e6f6ef;--due:#e0362f;--due-tint:#fdeceb;
    --amber:#ef9614;--amber-deep:#d9820c;
    --shadow-sm:0 1px 2px rgba(16,42,80,.06);
    --shadow-md:0 8px 26px -10px rgba(16,42,80,.20);
    --shadow-lg:0 22px 50px -18px rgba(16,42,80,.30);
    --r:20px;
    --fd:'Plus Jakarta Sans',system-ui,sans-serif;
    --fb:'Inter',system-ui,sans-serif;
  }
  *{margin:0;padding:0;box-sizing:border-box;-webkit-tap-highlight-color:transparent}
  html,body{height:100%}
  body{font-family:var(--fb);color:var(--ink);
    background:radial-gradient(1100px 460px at 108% -8%, #e2f0f8 0, transparent 60%),radial-gradient(900px 460px at -12% 2%, #eaf5f0 0, transparent 55%),var(--bg);
    min-height:100vh;display:flex;justify-content:center;-webkit-font-smoothing:antialiased}
  .app{width:100%;max-width:468px;height:100vh;height:100dvh;background:var(--bg);position:relative;overflow:hidden}
  @media(min-width:540px){body{padding:26px 16px}.app{height:calc(100vh - 52px);border-radius:30px;box-shadow:var(--shadow-lg);border:1px solid var(--line)}}

  .view{position:absolute;inset:0;display:flex;flex-direction:column;background:var(--bg);transition:transform .34s cubic-bezier(.3,.9,.3,1);will-change:transform}
  #ledgerView{transform:translateX(100%)}
  .app.at-ledger #findView{transform:translateX(-16%)}
  .app.at-ledger #ledgerView{transform:translateX(0)}

  .appbar{position:sticky;top:0;z-index:20;background:rgba(255,255,255,.9);
    backdrop-filter:saturate(180%) blur(14px);-webkit-backdrop-filter:saturate(180%) blur(14px);
    border-bottom:1px solid var(--line);padding:14px 16px}
  .bar-row{display:flex;align-items:center;gap:10px}
  .title-wrap{flex:1;min-width:0}
  .title-wrap h1{font-family:var(--fd);font-weight:800;font-size:20px;letter-spacing:-.02em;color:var(--brand);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .title-wrap p{font-size:12px;color:var(--muted);font-weight:500;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .icon-btn{width:40px;height:40px;flex:0 0 auto;display:grid;place-items:center;border-radius:50%;border:1px solid var(--line);background:#fff;color:var(--brand);box-shadow:var(--shadow-sm);cursor:pointer;transition:transform .15s,background .15s,color .15s}
  .icon-btn.sq{border-radius:13px;color:var(--ink-soft)}
  .icon-btn:active{transform:scale(.93)}
  .icon-btn:hover{background:var(--brand-tint)}
  .icon-btn.wa{color:#fff;background:#25d366;border-color:transparent}
  .icon-btn.wa:hover{background:#1fbb59}
  .icon-btn svg{width:19px;height:19px}

  /* segmented */
  .seg-card{padding:14px 16px 4px}
  .segment{display:flex;gap:4px;padding:5px;background:#eaeef6;border-radius:15px;position:relative}
  .segment .thumb{position:absolute;top:5px;left:5px;width:calc(50% - 5px);height:calc(100% - 10px);background:var(--brand);border-radius:11px;box-shadow:0 6px 14px -6px rgba(14,107,168,.7);transition:transform .28s cubic-bezier(.4,.1,.2,1);z-index:0}
  .segment.standard .thumb{transform:translateX(100%)}
  .seg-btn{flex:1;position:relative;z-index:1;border:0;background:transparent;cursor:pointer;font-family:var(--fd);font-weight:700;font-size:14px;padding:11px 0;border-radius:11px;color:var(--ink-soft);transition:color .2s;display:flex;align-items:center;justify-content:center;gap:7px}
  .seg-btn.active{color:#fff}
  .seg-btn svg{width:15px;height:15px}

  .control{padding:14px 16px 4px}
  .field-in{display:flex;align-items:center;gap:10px;background:#fff;border:1px solid var(--line-strong);border-radius:14px;padding:13px 14px;box-shadow:var(--shadow-sm)}
  .field-in svg{width:18px;height:18px;color:var(--muted);flex:0 0 auto}
  .field-in input{flex:1;border:0;outline:none;font-family:var(--fb);font-size:15px;color:var(--ink);background:transparent}
  .field-in select{flex:1;border:0;outline:none;background:transparent;font-family:var(--fd);font-weight:600;font-size:15px;color:var(--ink);-webkit-appearance:none;appearance:none;cursor:pointer}
  .field-in select.placeholder{color:var(--muted);font-weight:500}
  .field-in .clr{color:var(--muted);cursor:pointer;display:none}
  .field-in .clr.show{display:block}
  .field-in .chev{color:var(--muted)}

  .sheet{flex:1;overflow-y:auto;padding:12px 16px 96px;scrollbar-width:none}
  .sheet::-webkit-scrollbar{display:none}

  /* student card */
  .stu{background:var(--card);border:1px solid var(--line);border-radius:var(--r);padding:16px;margin-bottom:14px;box-shadow:var(--shadow-sm);animation:rise .4s cubic-bezier(.2,.7,.2,1) both}
  .stu-top{display:flex;align-items:center;gap:13px}
  .avatar{width:52px;height:52px;flex:0 0 auto;border-radius:50%;display:grid;place-items:center;color:#fff;font-family:var(--fd);font-weight:800;font-size:20px;box-shadow:inset 0 -10px 20px rgba(0,0,0,.13)}
  .stu-id{flex:1;min-width:0}
  .stu-id .nm{font-family:var(--fd);font-weight:800;font-size:16.5px;letter-spacing:-.01em;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .stu-id .std{font-size:13px;color:var(--muted);font-weight:600;margin-top:2px}
  .disc-chip{font-family:var(--fd);font-weight:700;font-size:11px;color:var(--ok);background:var(--ok-tint);padding:4px 9px;border-radius:999px;white-space:nowrap}

  .stu-meta{margin:13px 0 12px;padding-top:13px;border-top:1px solid var(--line)}
  .kv{font-size:13.5px;color:var(--ink-soft);margin-bottom:8px;line-height:1.4}
  .kv b{font-family:var(--fd);font-weight:700;color:var(--ink)}
  .streamline{display:flex;align-items:center;flex-wrap:wrap;gap:9px}
  .fampill{font-family:var(--fd);font-weight:700;font-size:12px;color:#fff;background:linear-gradient(135deg,#1685c9,var(--brand-deep));padding:6px 12px;border-radius:999px;box-shadow:0 5px 12px -5px rgba(14,107,168,.7)}

  .stu-actions{display:flex;gap:10px}
  .btn{flex:0 0 auto;border:1px solid var(--brand);background:#fff;color:var(--brand);font-family:var(--fd);font-weight:700;font-size:13.5px;padding:10px 16px;border-radius:12px;cursor:pointer;transition:transform .14s,background .15s;display:inline-flex;align-items:center;gap:7px}
  .btn svg{width:15px;height:15px}
  .btn:active{transform:scale(.96)}
  .btn:hover{background:var(--brand-tint)}
  .btn.ghost{border-color:var(--line-strong);color:var(--ink-soft)}
  .btn.ghost:hover{background:#f4f7fb}

  /* bottom nav */
  .bottomnav{position:absolute;left:0;right:0;bottom:0;z-index:25;background:rgba(255,255,255,.94);
    backdrop-filter:saturate(180%) blur(14px);-webkit-backdrop-filter:saturate(180%) blur(14px);
    border-top:1px solid var(--line);display:flex;justify-content:space-around;align-items:flex-end;padding:8px 8px calc(8px + env(safe-area-inset-bottom));height:70px}
  .nav-item{flex:1;border:0;background:transparent;cursor:pointer;display:flex;flex-direction:column;align-items:center;gap:3px;color:var(--muted);font-family:var(--fd);font-weight:600;font-size:10.5px;padding:6px 0;transition:color .15s}
  .nav-item svg{width:22px;height:22px}
  .nav-item.active{color:var(--brand)}
  .nav-item.center{position:relative}
  .nav-center-btn{width:52px;height:52px;margin-top:-24px;border-radius:16px;background:linear-gradient(135deg,#1685c9,var(--brand-deep));color:#fff;display:grid;place-items:center;box-shadow:0 12px 24px -8px rgba(11,79,125,.7);font-family:var(--fd);font-weight:800;font-size:15px;border:3px solid #fff}

  /* ===== LEDGER ===== */
  .fee{position:relative;background:var(--card);border:1px solid var(--line);border-radius:16px;padding:15px 16px 15px;margin-bottom:12px;box-shadow:var(--shadow-sm);overflow:hidden;animation:rise .38s cubic-bezier(.2,.7,.2,1) both}
  .fee::before{content:"";position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--due)}
  .fee.paid::before{background:var(--ok)}
  .fee-top{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}
  .fee-type{display:flex;align-items:center;gap:9px;min-width:0}
  .fee-type .fl{color:var(--brand);flex:0 0 auto}.fee-type .fl svg{width:18px;height:18px;display:block}
  .fee-type .tt{font-family:var(--fd);font-weight:700;font-size:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
  .fee-date{display:flex;align-items:center;gap:6px;color:var(--muted);font-size:12.5px;font-weight:600;flex:0 0 auto}
  .fee-date svg{width:14px;height:14px;color:var(--brand)}
  .fee-bottom{display:flex;align-items:center;justify-content:space-between;gap:10px}
  .amt{font-family:var(--fd);font-weight:800;font-size:19px;color:var(--due)}
  .amt.paid{color:var(--ok)}
  .amt .rs{font-size:14px;margin-right:1px}
  .fee-tags{display:flex;gap:7px;flex-wrap:wrap;justify-content:flex-end}
  .tg{font-family:var(--fd);font-weight:700;font-size:11px;padding:5px 11px;border-radius:999px;color:#fff;white-space:nowrap}
  .tg.cash{background:var(--ok)}
  .tg.period{background:linear-gradient(135deg,var(--amber),var(--amber-deep))}

  .ledger-foot{border-top:1px solid var(--line);background:#fff}
  .foot-row{display:flex;align-items:center;justify-content:space-between;padding:13px 18px}
  .foot-row .fl,.foot-row .fr{font-family:var(--fd);font-weight:800;font-size:18px;display:flex;align-items:center;gap:3px}
  .foot-row .fl{color:var(--due)} .foot-row .fr{color:var(--ok)}
  .foot-row .rs{font-size:14px}
  .disc-row{display:flex;align-items:center;justify-content:space-between;padding:0 18px 10px;font-size:12.5px;color:var(--muted);font-weight:600}
  .disc-row b{font-family:var(--fd);color:var(--ok)}
  .balance{background:linear-gradient(135deg,#1685c9,var(--brand-deep));color:#fff;padding:15px 18px;display:flex;align-items:center;justify-content:space-between;
    padding-bottom:calc(15px + env(safe-area-inset-bottom))}
  .balance .lbl{font-family:var(--fd);font-weight:700;font-size:15px;letter-spacing:.01em}
  .balance .val{font-family:var(--fd);font-weight:800;font-size:20px}

  /* empty */
  .empty{padding:56px 26px;text-align:center;display:flex;flex-direction:column;align-items:center;gap:6px;animation:rise .4s ease both}
  .empty .art{width:94px;height:94px;border-radius:28px;margin-bottom:10px;display:grid;place-items:center;background:linear-gradient(160deg,#e6f2fb,#e7f6ef);color:var(--brand)}
  .empty .art svg{width:42px;height:42px}
  .empty h3{font-family:var(--fd);font-weight:800;font-size:17px}
  .empty p{font-size:13.5px;color:var(--muted);max-width:240px;line-height:1.5}

  /* modal (discount) */
  .overlay{position:absolute;inset:0;z-index:60;background:rgba(11,30,55,.45);backdrop-filter:blur(2px);
    display:flex;align-items:center;justify-content:center;padding:22px;opacity:0;pointer-events:none;transition:opacity .26s}
  .overlay.show{opacity:1;pointer-events:auto}
  .modal{width:100%;max-width:340px;background:#fff;border-radius:22px;box-shadow:var(--shadow-lg);
    transform:translateY(14px) scale(.97);transition:transform .3s cubic-bezier(.3,.9,.3,1);overflow:hidden}
  .overlay.show .modal{transform:none}
  .modal-head{padding:20px 20px 4px;text-align:center}
  .modal-head .mi{width:56px;height:56px;border-radius:17px;margin:0 auto 12px;display:grid;place-items:center;background:var(--ok-tint);color:var(--ok)}
  .modal-head .mi svg{width:26px;height:26px}
  .modal-head h3{font-family:var(--fd);font-weight:800;font-size:18px}
  .modal-head p{font-size:12.5px;color:var(--muted);margin-top:4px}
  .modal-body{padding:16px 20px 20px}
  .money-in{display:flex;align-items:center;gap:8px;border:1px solid var(--line-strong);border-radius:14px;padding:13px 15px;transition:border .15s,box-shadow .15s}
  .money-in:focus-within{border-color:var(--brand);box-shadow:0 0 0 3px rgba(14,107,168,.14)}
  .money-in .rs{font-family:var(--fd);font-weight:800;font-size:18px;color:var(--muted)}
  .money-in input{flex:1;border:0;outline:none;font-family:var(--fd);font-weight:700;font-size:18px;color:var(--ink);background:transparent}
  .modal-actions{display:flex;gap:10px;margin-top:16px}
  .modal-actions button{flex:1;padding:14px;border-radius:13px;font-family:var(--fd);font-weight:700;font-size:14.5px;cursor:pointer;transition:transform .14s}
  .modal-actions .close{border:1px solid var(--line-strong);background:#fff;color:var(--ink-soft)}
  .modal-actions .apply{border:0;background:linear-gradient(135deg,#12b981,var(--ok));color:#fff;box-shadow:0 10px 20px -10px rgba(10,157,99,.8)}
  .modal-actions button:active{transform:scale(.97)}

  .toast{position:absolute;left:50%;bottom:92px;transform:translate(-50%,20px);z-index:70;background:var(--ink);color:#fff;font-size:13.5px;font-weight:600;padding:11px 18px;border-radius:13px;box-shadow:var(--shadow-lg);opacity:0;pointer-events:none;transition:opacity .25s,transform .25s;white-space:nowrap;max-width:90%}
  .toast.show{opacity:1;transform:translate(-50%,0)}

  @keyframes rise{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:none}}
  @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}}
  :focus-visible{outline:2px solid var(--brand);outline-offset:2px;border-radius:8px}
</style>
</head>
<body>
<div class="app" id="app">

  <!-- ===== FIND STUDENT ===== -->
  <section class="view" id="findView">
    <header class="appbar">
      <div class="bar-row"><div class="title-wrap"><h1>Find Student</h1><p id="findSub">—</p></div></div>
    </header>
    <div class="seg-card">
      <div class="segment" id="segment">
        <span class="thumb"></span>
        <button class="seg-btn active" data-mode="search">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>By Search</button>
        <button class="seg-btn" data-mode="standard">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>By Standard</button>
      </div>
    </div>

    <div class="control">
      <div class="field-in" id="searchField">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg>
        <input id="searchInput" type="text" placeholder="Search by name or father…" />
        <button class="clr" id="searchClear" aria-label="Clear"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg></button>
      </div>
      <div class="field-in" id="standardField" style="display:none">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
        <select id="standardSelect" class="placeholder"></select>
        <span class="chev"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9l6 6 6-6"/></svg></span>
      </div>
    </div>

    <main class="sheet"><div id="studentList"></div></main>

    <!--<nav class="bottomnav" id="bottomnav">-->
    <!--  <button class="nav-item" data-tab="home"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>Home</button>-->
    <!--  <button class="nav-item active" data-tab="find"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l9-4 9 4-9 4z"/><path d="M7 11v4c0 1.5 2.2 2.5 5 2.5s5-1 5-2.5v-4"/></svg>Find Student</button>-->
    <!--  <button class="nav-item center" data-tab="ed"><span class="nav-center-btn">Ed⁺</span></button>-->
    <!--  <button class="nav-item" data-tab="support"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H8l-4 4V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2z"/></svg>Ed⁺ Support</button>-->
    <!--  <button class="nav-item" data-tab="profile"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="3.4"/><path d="M5 20c0-3.3 3.1-5.5 7-5.5s7 2.2 7 5.5"/></svg>Profile</button>-->
    <!--</nav>-->
    
  </section>

  <!-- ===== STUDENT LEDGER ===== -->
  <section class="view" id="ledgerView">
    <header class="appbar">
      <div class="bar-row">
        <button class="icon-btn sq" id="backLedger" aria-label="Back"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg></button>
        <div class="title-wrap"><h1>Student Ledger</h1><p id="ledgerSub">—</p></div>
        <button class="icon-btn" id="exportBtn" aria-label="Export"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v11"/><path d="M8 10l4 4 4-4"/><path d="M5 21h14"/></svg></button>
        <button class="icon-btn wa" id="waBtn" aria-label="WhatsApp"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15l-1.3 4.7 4.8-1.3A10 10 0 1 0 12 2m0 2a8 8 0 1 1-4.1 14.9l-.3-.2-2.6.7.7-2.5-.2-.3A8 8 0 0 1 12 4m-3 3.6c-.2 0-.5 0-.7.4s-.9.9-.9 2.2.9 2.6 1 2.8 1.8 3 4.5 4.1c2.2.9 2.7.7 3.2.7s1.5-.6 1.8-1.2.2-1.1.1-1.2l-.9-.4-1.4-.7c-.2-.1-.4-.1-.5.1l-.7.9c-.1.2-.3.2-.5.1a6.5 6.5 0 0 1-1.9-1.2 7.3 7.3 0 0 1-1.4-1.7c-.1-.2 0-.4.1-.5l.4-.5.3-.5v-.4l-.8-1.9c-.2-.5-.4-.4-.5-.4z"/></svg></button>
      </div>
    </header>
    <main class="sheet" id="feeSheet" style="padding-bottom:16px"><div id="feeList"></div></main>
    <div class="ledger-foot" id="ledgerFoot"></div>
  </section>

  <!-- ===== DISCOUNT MODAL ===== -->
  <div class="overlay" id="overlay">
    <div class="modal" id="modal">
      <div class="modal-head">
        <div class="mi"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 9h.01M15 15h.01M16 8L8 16"/><circle cx="12" cy="12" r="9.5"/></svg></div>
        <h3>Add Discount</h3>
        <p id="discFor">—</p>
      </div>
      <div class="modal-body">
        <div class="money-in"><span class="rs">₹</span><input id="discInput" type="number" inputmode="numeric" min="0" placeholder="Enter discount amount" /></div>
        <div class="modal-actions">
          <button class="close" id="discClose">Close</button>
          <button class="apply" id="discApply">Apply</button>
        </div>
      </div>
    </div>
  </div>

  <div class="toast" id="toast"></div>
</div>

<script>
/* ---------- helpers ---------- */
const $=s=>document.querySelector(s);
const esc=s=>String(s).replace(/[&<>"]/g,c=>({"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;"}[c]));
const inr=n=>Number(n).toLocaleString("en-IN");
const AV=["#0e6ba8","#2a7f62","#7a4fd0","#c2683a","#b03e6a","#1f7a8c","#3a6cc2"];
const hash=s=>{let h=0;for(const c of String(s))h=(h*31+c.charCodeAt(0))>>>0;return h;};
const avColor=t=>AV[hash(t)%AV.length];
function toast(m){const t=$("#toast");t.textContent=m;t.classList.add("show");clearTimeout(t._t);t._t=setTimeout(()=>t.classList.remove("show"),2000);}

/* ---------- data ---------- */
const STANDARDS=["Nursery","LKG","UKG","First","Second","Third","Fourth","Fifth","Sixth"];
const FEE_TPL=[
  {type:"Tuition Fees",date:"15 Apr 2026",amount:4900,tag:"1st Installment"},
  {type:"Transportation Fees",date:"15 Apr 2026",amount:1000,tag:"April"},
  {type:"Tuition Fees",date:"13 May 2026",amount:4000,tag:"1st Installment"},
  {type:"Transportation Fees",date:"13 May 2026",amount:1000,tag:"May"},
  {type:"Transportation Fees",date:"01 Jul 2026",amount:3900,tag:"1st Quarter"},
  {type:"Tuition Fees",date:"01 Aug 2026",amount:4000,tag:"2nd Installment"},
  {type:"Transportation Fees",date:"01 Oct 2026",amount:3000,tag:"2nd Quarter"},
  {type:"Tuition Fees",date:"01 Nov 2026",amount:4000,tag:"3rd Installment"},
];
function buildFees(id){
  return FEE_TPL.map((f,i)=>{
    const paid=((id*3+i*2)%3===0); // deterministic paid rows
    return {...f,paid,mode:paid?(i%2?"Online":"Cash"):null};
  });
}
const RAW=[
  {name:"DEVENDRA SEN",father:"MAHENDRA SEN",standard:"Third",board:"RBSE",medium:"English"},
  {name:"RANVEER",father:"CHENA RAM",standard:"Third",board:"RBSE",medium:"English"},
  {name:"DHANU SHREE",father:"ASHOK VISHNOI",standard:"Third",board:"RBSE",medium:"English"},
  {name:"DARSHAN",father:"SURESH KUMAR",standard:"Third",board:"RBSE",medium:"English"},
  {name:"KHUSHI SHARMA",father:"RAJESH SHARMA",standard:"Fourth",board:"CBSE",medium:"English"},
  {name:"AARAV MEHTA",father:"NIKHIL MEHTA",standard:"Fourth",board:"CBSE",medium:"English"},
  {name:"PRIYA CHOUDHARY",father:"MOHAN CHOUDHARY",standard:"Fifth",board:"RBSE",medium:"Hindi"},
  {name:"VIVAAN GUPTA",father:"ANIL GUPTA",standard:"Fifth",board:"CBSE",medium:"English"},
  {name:"ANANYA SINGH",father:"VIKRAM SINGH",standard:"Second",board:"RBSE",medium:"English"},
  {name:"KABIR RATHORE",father:"DILIP RATHORE",standard:"Second",board:"RBSE",medium:"Hindi"},
  {name:"SAANVI RAO",father:"GANESH RAO",standard:"UKG",board:"RBSE",medium:"English"},
  {name:"MYRA IYER",father:"RAMESH IYER",standard:"First",board:"CBSE",medium:"English"},
];
let STUDENTS=RAW.map((s,i)=>{
  const fees=buildFees(i+1);
  const family=fees.reduce((a,f)=>a+f.amount,0);
  return {id:i+1,...s,fees,family,discount:0};
});

/* ---------- state ---------- */
let mode="search",query="",selStd="",currentStudent=null,discountFor=null;

/* ---------- FIND render ---------- */
function filtered(){
  if(mode==="search"){
    if(!query)return STUDENTS;
    const q=query.toLowerCase();
    return STUDENTS.filter(s=>(s.name+" "+s.father).toLowerCase().includes(q));
  }else{
    if(!selStd)return STUDENTS;
    return STUDENTS.filter(s=>s.standard===selStd);
  }
}
function renderStudents(){
  const arr=filtered();
  $("#findSub").textContent=`${arr.length} student${arr.length!==1?"s":""}`;
  const el=$("#studentList");
  if(!arr.length){
    el.innerHTML=`<div class="empty"><div class="art"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4-4"/></svg></div><h3>No students found</h3><p>${mode==="search"?(query?`Nothing matches “${esc(query)}”.`:"Type a name to search."):"No students in this standard."}</p></div>`;
    return;
  }
  el.innerHTML=arr.map((s,i)=>`
    <article class="stu" style="animation-delay:${i*40}ms">
      <div class="stu-top">
        <div class="avatar" style="background:linear-gradient(160deg,${avColor(s.name)},${avColor(s.name)}cc)">${esc(s.name.trim()[0])}</div>
        <div class="stu-id"><div class="nm">${esc(s.name)}</div><div class="std">${esc(s.standard)}</div></div>
        ${s.discount>0?`<span class="disc-chip">− ₹${inr(s.discount)}</span>`:""}
      </div>
      <div class="stu-meta">
        <p class="kv"><b>Father's Name :</b> ${esc(s.father)}</p>
        <div class="streamline"><span class="kv" style="margin:0"><b>Stream :</b> ${esc(s.board)} - ${esc(s.medium)}</span>
          <span class="fampill">Family ₹${inr(s.family - s.discount)}</span></div>
      </div>
      <div class="stu-actions">
        <button class="btn" data-view="${s.id}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>View Fees</button>
        <button class="btn ghost" data-disc="${s.id}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 9h.01M15 15h.01M16 8L8 16"/><circle cx="12" cy="12" r="9"/></svg>Discount</button>
      </div>
    </article>`).join("");
}

/* ---------- LEDGER ---------- */
function openLedger(id){
  const s=STUDENTS.find(x=>x.id==id);if(!s)return;currentStudent=s;
  $("#ledgerSub").textContent=`${s.name} · ${s.standard}`;
  $("#feeList").innerHTML=s.fees.map((f,i)=>`
    <div class="fee ${f.paid?"paid":""}" style="animation-delay:${i*32}ms">
      <div class="fee-top">
        <div class="fee-type"><span class="fl"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3v18"/><path d="M5 4h11l-2.5 3.5L16 11H5"/></svg></span><span class="tt">${esc(f.type)}</span></div>
        <div class="fee-date"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2.5"/><path d="M3 9h18M8 2v4M16 2v4"/></svg>${esc(f.date)}</div>
      </div>
      <div class="fee-bottom">
        <span class="amt ${f.paid?"paid":""}"><span class="rs">₹</span>${inr(f.amount)}</span>
        <span class="fee-tags">${f.paid?`<span class="tg cash">${esc(f.mode)}</span>`:""}<span class="tg period">${esc(f.tag)}</span></span>
      </div>
    </div>`).join("");

  const total=s.fees.reduce((a,f)=>a+f.amount,0);
  const paid=s.fees.filter(f=>f.paid).reduce((a,f)=>a+f.amount,0);
  const balance=Math.max(0,total-paid-s.discount);
  $("#ledgerFoot").innerHTML=`
    <div class="foot-row"><span class="fl"><span class="rs">₹</span>${inr(total)}</span><span class="fr"><span class="rs">₹</span>${inr(paid)}</span></div>
    ${s.discount>0?`<div class="disc-row"><span>Discount applied</span><b>− ₹${inr(s.discount)}</b></div>`:""}
    <div class="balance"><span class="lbl">Balance Amount</span><span class="val">₹ ${inr(balance)}</span></div>`;
  $("#feeSheet").scrollTop=0;
  $("#app").classList.add("at-ledger");
}

/* ---------- DISCOUNT modal ---------- */
function openDiscount(id){
  const s=STUDENTS.find(x=>x.id==id);if(!s)return;discountFor=s;
  $("#discFor").textContent=`${s.name} · ${s.standard}`;
  $("#discInput").value=s.discount>0?s.discount:"";
  $("#overlay").classList.add("show");
  setTimeout(()=>$("#discInput").focus(),150);
}
function closeDiscount(){$("#overlay").classList.remove("show");}
function applyDiscount(){
  if(!discountFor)return;
  let v=parseInt($("#discInput").value,10);if(isNaN(v)||v<0)v=0;
  if(v>discountFor.family)v=discountFor.family;
  discountFor.discount=v;
  renderStudents();
  if(currentStudent&&currentStudent.id===discountFor.id)openLedger(currentStudent.id); // refresh if open
  closeDiscount();
  toast(v>0?`₹${inr(v)} discount applied to ${discountFor.name}`:`Discount removed for ${discountFor.name}`);
}

/* ---------- events ---------- */
/* segmented */
$("#segment").addEventListener("click",e=>{
  const b=e.target.closest(".seg-btn");if(!b)return;
  mode=b.dataset.mode;
  $("#segment").classList.toggle("standard",mode==="standard");
  document.querySelectorAll(".seg-btn").forEach(x=>x.classList.toggle("active",x===b));
  $("#searchField").style.display=mode==="search"?"":"none";
  $("#standardField").style.display=mode==="standard"?"":"none";
  renderStudents();
});
/* search */
const sIn=$("#searchInput"),sClr=$("#searchClear");
sIn.addEventListener("input",()=>{query=sIn.value.trim();sClr.classList.toggle("show",!!query);renderStudents();});
sClr.addEventListener("click",()=>{sIn.value="";query="";sClr.classList.remove("show");sIn.focus();renderStudents();});
/* standard select */
const stdSel=$("#standardSelect");
stdSel.innerHTML=`<option value="">Select Standard</option>`+STANDARDS.map(s=>`<option value="${s}">${s}</option>`).join("");
stdSel.addEventListener("change",()=>{selStd=stdSel.value;stdSel.classList.toggle("placeholder",!selStd);renderStudents();});

/* list buttons */
$("#studentList").addEventListener("click",e=>{
  const v=e.target.closest("[data-view]");if(v){openLedger(v.dataset.view);return;}
  const d=e.target.closest("[data-disc]");if(d){openDiscount(d.dataset.disc);return;}
});

/* ledger */
$("#backLedger").addEventListener("click",()=>$("#app").classList.remove("at-ledger"));
$("#exportBtn").addEventListener("click",()=>toast("Exporting ledger as PDF…"));
$("#waBtn").addEventListener("click",()=>toast(`Sharing ledger to parent on WhatsApp…`));

/* discount modal */
$("#discClose").addEventListener("click",closeDiscount);
$("#discApply").addEventListener("click",applyDiscount);
$("#discInput").addEventListener("keydown",e=>{if(e.key==="Enter")applyDiscount();});
$("#overlay").addEventListener("click",e=>{if(e.target.id==="overlay")closeDiscount();});

/* bottom nav */
$("#bottomnav").addEventListener("click",e=>{
  const b=e.target.closest(".nav-item");if(!b)return;
  document.querySelectorAll(".nav-item").forEach(x=>x.classList.toggle("active",x===b));
  const tab=b.dataset.tab;
  if(tab!=="find")toast(`${b.textContent.trim()} — coming soon`);
});

/* ---------- init ---------- */
renderStudents();
</script>
</body>
</html>
