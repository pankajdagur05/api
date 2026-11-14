
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="theme-color" content="#f5f7fc" />
<title>Edplus · Paper Slot</title>
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
    --pink:#d6336c;  --pink-bg:#fce8f0;
    --shadow-sm:0 1px 2px rgba(20,26,46,.05);
    --shadow:0 6px 22px -8px rgba(38,44,74,.18), 0 2px 6px -2px rgba(38,44,74,.06);
    --shadow-lg:0 18px 40px -14px rgba(38,44,74,.30);
    --r:20px; --r-sm:14px;
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
    min-height:100vh; display:flex; justify-content:center; -webkit-font-smoothing:antialiased;
  }
  .app{
    width:100%; max-width:440px; height:100vh; height:100dvh; background:var(--bg-2);
    position:relative; overflow:hidden; display:flex; flex-direction:column;
    box-shadow:0 0 0 1px rgba(20,26,46,.03), 0 40px 80px -30px rgba(38,44,74,.35);
  }

  /* header */
  .appbar{padding:calc(env(safe-area-inset-top,0px) + 14px) 16px 14px; background:var(--bg-2); flex:none}
  .top-row{display:flex; align-items:center; justify-content:space-between; gap:12px}
  .htxt .t{font-family:var(--num); font-weight:700; font-size:21px; letter-spacing:-.4px; line-height:1.05}
  .htxt .s{font-size:11.5px; font-weight:600; color:var(--muted); margin-top:2px}
  .htxt .s b{color:var(--brand)}
  .add{
    width:44px; height:44px; border-radius:14px; flex:none;
    background:linear-gradient(145deg,var(--brand),var(--brand-2)); color:#fff; border:none;
    display:grid; place-items:center; cursor:pointer;
    box-shadow:0 12px 22px -8px rgba(75,91,214,.6); transition:transform .12s;
  }
  .add:active{transform:scale(.9)}
  .search{
    display:flex; align-items:center; gap:11px; margin-top:14px;
    background:var(--surface); border:1px solid var(--line); border-radius:14px;
    padding:12px 14px; box-shadow:var(--shadow-sm); transition:border-color .18s, box-shadow .18s;
  }
  .search:focus-within{border-color:var(--brand-2); box-shadow:0 0 0 3px var(--brand-soft)}
  .search svg{color:var(--muted); flex:none}
  .search input{border:none; outline:none; background:none; font-family:inherit; font-size:14px; flex:1; color:var(--ink); font-weight:500}
  .search input::placeholder{color:var(--muted)}
  .search .clr{border:none;background:none;color:var(--muted);cursor:pointer;padding:2px;display:none}
  .search .clr.show{display:block}

  .body{flex:1; overflow-y:auto; overflow-x:hidden; padding:2px 16px calc(28px + env(safe-area-inset-bottom,0px)); -webkit-overflow-scrolling:touch}
  .body::-webkit-scrollbar{width:0}

  /* card */
  .card{
    background:var(--surface); border:1px solid var(--line-2); border-radius:var(--r);
    box-shadow:var(--shadow-sm); padding:15px 16px; margin-top:13px; position:relative; overflow:hidden;
    animation:rise .45s cubic-bezier(.2,.8,.2,1) both;
  }
  @keyframes rise{from{opacity:0; transform:translateY(10px)}to{opacity:1; transform:none}}
  .card.removing{animation:fall .3s ease forwards}
  @keyframes fall{to{opacity:0; transform:scale(.96) translateY(-6px); margin-top:-4px; max-height:0; padding-top:0; padding-bottom:0}}
  .card.hidden{display:none}
  .card::before{content:""; position:absolute; left:0; top:14px; bottom:14px; width:3px; border-radius:3px; background:var(--c)}

  .c-top{display:flex; align-items:center; gap:13px}
  .avatar{
    width:46px; height:46px; border-radius:50%; flex:none; display:grid; place-items:center; color:#fff;
    font-family:var(--num); font-weight:700; font-size:18px;
    background:linear-gradient(145deg,var(--c),color-mix(in srgb,var(--c) 78%, #000 6%));
    box-shadow:0 8px 16px -6px color-mix(in srgb,var(--c) 55%, transparent);
  }
  .c-name{font-weight:700; font-size:16px; letter-spacing:-.2px; line-height:1.2; color:var(--ink); word-break:break-word}
  .c-by{font-size:12.5px; color:var(--muted); margin-top:3px; font-weight:500}
  .c-by b{color:var(--ink-2); font-weight:600}
  .kebab{
    margin-left:auto; align-self:flex-start; width:32px; height:32px; border-radius:9px; flex:none;
    background:none; border:none; color:var(--muted); cursor:pointer; display:grid; place-items:center;
    transition:background .15s, color .15s;
  }
  .kebab:hover{background:var(--line-2); color:var(--ink-2)}
  .kebab:active{transform:scale(.9)}

  .divider{height:1px; background:var(--line); margin:13px 0}
  .fields{display:grid; grid-template-columns:1fr 1fr; gap:14px 12px}
  @media(max-width:330px){ .fields{grid-template-columns:1fr} }
  .f{display:flex; gap:10px; align-items:flex-start; min-width:0}
  .f .ic{width:30px; height:30px; border-radius:9px; flex:none; display:grid; place-items:center; background:var(--fb); color:var(--fc)}
  .f .ic svg{width:15px; height:15px}
  .f .kv{min-width:0}
  .f .k{font-size:10px; font-weight:700; letter-spacing:.5px; text-transform:uppercase; color:var(--muted)}
  .f .v{font-size:13.5px; font-weight:600; color:var(--ink); margin-top:2px; line-height:1.3; word-break:break-word}

  /* floating kebab menu */
  .menu{
    position:absolute; z-index:70; background:var(--surface); border:1px solid var(--line);
    border-radius:14px; box-shadow:var(--shadow-lg); padding:6px; min-width:168px; display:none;
    animation:pop .16s ease;
  }
  .menu.open{display:block}
  @keyframes pop{from{opacity:0; transform:translateY(-6px) scale(.97)}to{opacity:1; transform:none}}
  .menu button{
    width:100%; text-align:left; border:none; background:none; padding:10px 11px; border-radius:9px;
    font-family:var(--sans); font-size:13.5px; font-weight:600; color:var(--ink-2); cursor:pointer;
    display:flex; align-items:center; gap:10px;
  }
  .menu button svg{width:17px; height:17px; color:var(--muted)}
  .menu button:hover{background:var(--brand-soft); color:var(--brand)}
  .menu button:hover svg{color:var(--brand)}
  .menu button.danger{color:var(--red)}
  .menu button.danger svg{color:var(--red)}
  .menu button.danger:hover{background:var(--red-bg)}

  .empty{display:none; text-align:center; padding:64px 20px; color:var(--muted)}
  .empty.show{display:block}
  .empty .ic{width:56px;height:56px;border-radius:18px;background:var(--surface);border:1px solid var(--line);display:grid;place-items:center;margin:0 auto 14px;box-shadow:var(--shadow-sm);color:var(--muted)}
  .empty p{font-weight:600; color:var(--ink-2); margin:0 0 4px; font-size:15px}
  .empty span{font-size:13px}

  /* bottom sheet */
  .overlay{position:absolute; inset:0; background:rgba(20,26,46,.42); backdrop-filter:blur(2px); opacity:0; pointer-events:none; transition:opacity .25s; z-index:80}
  .overlay.open{opacity:1; pointer-events:auto}
  .sheet{
    position:absolute; left:0; right:0; bottom:0; z-index:81; background:var(--surface);
    border-radius:24px 24px 0 0; padding:8px 18px calc(18px + env(safe-area-inset-bottom,0px));
    transform:translateY(100%); transition:transform .32s cubic-bezier(.32,.72,.28,1);
    max-height:90%; overflow-y:auto; box-shadow:0 -20px 50px -20px rgba(20,26,46,.4);
  }
  .sheet.open{transform:none}
  .grab{width:40px; height:4px; border-radius:4px; background:var(--line); margin:6px auto 14px}
  .sheet h3{font-family:var(--num); font-weight:700; font-size:19px; letter-spacing:-.3px; margin:0 0 2px}
  .sheet .sub{font-size:12.5px; color:var(--muted); margin-bottom:16px}
  .field{margin-bottom:13px}
  .field label{display:block; font-size:11px; font-weight:700; letter-spacing:.4px; text-transform:uppercase; color:var(--muted); margin-bottom:6px}
  .field input, .field select{
    width:100%; border:1px solid var(--line); border-radius:12px; padding:12px 13px;
    font-family:inherit; font-size:14px; font-weight:500; color:var(--ink); background:var(--bg-2);
    outline:none; transition:border-color .18s, box-shadow .18s;
  }
  .field input:focus, .field select:focus{border-color:var(--brand-2); box-shadow:0 0 0 3px var(--brand-soft); background:var(--surface)}
  .row2{display:grid; grid-template-columns:1fr 1fr; gap:11px}
  .actions{display:flex; gap:10px; margin-top:6px}
  .btn{flex:1; border:none; border-radius:13px; padding:14px; font-family:inherit; font-size:14.5px; font-weight:700; cursor:pointer; transition:transform .1s, background .18s}
  .btn:active{transform:scale(.98)}
  .btn.ghost{background:var(--line-2); color:var(--ink-2)}
  .btn.primary{background:var(--brand); color:#fff; box-shadow:0 12px 22px -8px rgba(75,91,214,.55)}

  /* toast */
  .toast{
    position:absolute; left:50%; bottom:calc(26px + env(safe-area-inset-bottom,0px)); transform:translate(-50%,20px);
    background:var(--ink); color:#fff; font-size:13px; font-weight:600; padding:11px 16px; border-radius:12px;
    box-shadow:var(--shadow-lg); opacity:0; pointer-events:none; transition:opacity .25s, transform .25s; z-index:90;
    display:flex; align-items:center; gap:8px; max-width:80%;
  }
  .toast.show{opacity:1; transform:translate(-50%,0)}
  .toast svg{width:16px; height:16px; color:var(--green)}

  @media(prefers-reduced-motion:reduce){ *{animation:none!important; transition:opacity .2s!important} }
</style>
</head>
<body>
<div class="app" id="app">
  <div class="appbar">
    <div class="top-row">
      <div class="htxt">
        <div class="t">Paper Slot</div>
        <div class="s"><b id="cnt">6</b> paper slots</div>
      </div>
      <button class="add" id="addBtn" aria-label="Add paper slot">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
      </button>
    </div>
    <label class="search">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg>
      <input id="search" type="text" placeholder="Search by name, subject or teacher" autocomplete="off" />
      <button class="clr" id="clr" aria-label="Clear"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </label>
  </div>

  <div class="body">
    <div id="list"></div>
    <div class="empty" id="empty">
      <div class="ic"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-3.5-3.5"/></svg></div>
      <p>No paper slots found</p>
      <span>Try a different search, or add a new one with +.</span>
    </div>
  </div>

  <!-- kebab menu -->
  <div class="menu" id="cardMenu">
    <button data-act="edit"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>Edit</button>
    <button data-act="dup"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>Duplicate</button>
    <button data-act="del" class="danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>Delete</button>
  </div>

  <!-- bottom sheet -->
  <div class="overlay" id="overlay"></div>
  <div class="sheet" id="sheet">
    <div class="grab"></div>
    <h3 id="sheetTitle">New Paper Slot</h3>
    <div class="sub" id="sheetSub">Fill in the details to create a slot</div>
    <div class="field"><label>Paper name</label><input id="fTitle" type="text" placeholder="e.g. Footprints" /></div>
    <div class="field"><label>Created by</label><input id="fBy" type="text" placeholder="e.g. Hitesh Joshi" /></div>
    <div class="row2">
      <div class="field"><label>Board</label>
        <select id="fBoard"><option>RBSE</option><option>CBSE</option><option>ICSE</option><option>State Board</option></select>
      </div>
      <div class="field"><label>Standard</label><input id="fStd" type="text" placeholder="e.g. 10th EM" /></div>
    </div>
    <div class="row2">
      <div class="field"><label>Medium</label><input id="fMedium" type="text" placeholder="e.g. Hindi Medium" /></div>
      <div class="field"><label>Subject</label><input id="fSubject" type="text" placeholder="e.g. English" /></div>
    </div>
    <div class="actions">
      <button class="btn ghost" id="cancelBtn">Cancel</button>
      <button class="btn primary" id="saveBtn">Create slot</button>
    </div>
  </div>

  <!-- toast -->
  <div class="toast" id="toast"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg><span id="toastMsg">Done</span></div>
</div>

<script>
var PAL=['var(--blue)','var(--violet)','var(--teal)','var(--amber)','var(--brand)','var(--pink)','var(--green)'];
var uid=0;
function mk(title,by,board,std,med,sub){ return {id:++uid, title:title, by:by, board:board, std:std, med:med, sub:sub, color:PAL[(uid-1)%PAL.length]}; }

var slots=[
  mk('test','Reception','RBSE','10th EM GURUN','Hindi Medium','English'),
  mk('Footprints','Hitesh Joshi','RBSE','10th EM','English','English'),
  mk('Computer Science','Hitesh Joshi','RBSE','12th Comm HM','Hindi Medium','COMPUTER SCI.'),
  mk('maths','Balveer Singh','RBSE','5th HM','Hindi Medium','Math'),
  mk('hamara priyavarana','Balveerr Singh','RBSE','5th HM','english medium','EVS'),
  mk('NCERT','Jitendra Chouhan','RBSE','8th HM','Hindi Medium','Science')
];

var FIELD_ICONS={
  board:{c:'var(--blue)',b:'var(--blue-bg)',svg:'<path d="M3 21h18M4 10h16M5 10 12 4l7 6M6 10v11M10 10v11M14 10v11M18 10v11"/>'},
  std:{c:'var(--violet)',b:'var(--violet-bg)',svg:'<path d="M22 10 12 5 2 10l10 5 10-5z"/><path d="M6 12v5c0 1.1 2.7 2.5 6 2.5s6-1.4 6-2.5v-5"/>'},
  med:{c:'var(--teal)',b:'var(--teal-bg)',svg:'<circle cx="12" cy="12" r="9"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20 15 15 0 0 1 0-20z"/>'},
  sub:{c:'var(--amber)',b:'var(--amber-bg)',svg:'<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>'}
};
function fieldHTML(type,label,val){
  var fi=FIELD_ICONS[type];
  return '<div class="f" style="--fc:'+fi.c+';--fb:'+fi.b+'">'+
    '<span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">'+fi.svg+'</svg></span>'+
    '<span class="kv"><span class="k">'+label+'</span><span class="v">'+(val||'—')+'</span></span></div>';
}
function esc(s){return (s||'').replace(/[&<>"]/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c];});}

var list=document.getElementById('list'), empty=document.getElementById('empty'), cnt=document.getElementById('cnt');
var searchEl=document.getElementById('search'), clr=document.getElementById('clr');

function render(){
  list.innerHTML = slots.map(function(s){
    var letter=(s.title.charAt(0)||'?').toLowerCase();
    return '<div class="card" data-id="'+s.id+'" data-key="'+esc((s.title+' '+s.by+' '+s.sub+' '+s.std).toLowerCase())+'" style="--c:'+s.color+'">'+
      '<div class="c-top">'+
        '<div class="avatar">'+esc(letter)+'</div>'+
        '<div style="min-width:0"><div class="c-name">'+esc(s.title)+'</div><div class="c-by">By <b>'+esc(s.by)+'</b></div></div>'+
        '<button class="kebab" data-kebab="'+s.id+'" aria-label="Options"><svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="19" r="1.7"/></svg></button>'+
      '</div>'+
      '<div class="divider"></div>'+
      '<div class="fields">'+
        fieldHTML('board','Board',esc(s.board))+
        fieldHTML('std','Standard',esc(s.std))+
        fieldHTML('med','Medium',esc(s.med))+
        fieldHTML('sub','Subject',esc(s.sub))+
      '</div>'+
    '</div>';
  }).join('');
  cnt.textContent=slots.length;
  applyFilter();
}
function applyFilter(){
  var q=searchEl.value.trim().toLowerCase();
  clr.classList.toggle('show', q.length>0);
  var shown=0;
  list.querySelectorAll('.card').forEach(function(c){
    var ok=!q||c.dataset.key.indexOf(q)>-1; c.classList.toggle('hidden',!ok); if(ok)shown++;
  });
  empty.classList.toggle('show', shown===0);
}
searchEl.addEventListener('input', applyFilter);
clr.addEventListener('click', function(){ searchEl.value=''; applyFilter(); searchEl.focus(); });

/* ---------- kebab menu ---------- */
var menu=document.getElementById('cardMenu'), app=document.getElementById('app'), activeId=null;
list.addEventListener('click', function(e){
  var k=e.target.closest('[data-kebab]');
  if(k){
    e.stopPropagation();
    activeId=+k.dataset.kebab;
    var open=menu.classList.contains('open');
    closeMenu();
    if(open) return;
    var r=k.getBoundingClientRect(), pr=app.getBoundingClientRect();
    menu.classList.add('open');
    var mw=menu.offsetWidth, mh=menu.offsetHeight;
    var left=r.right-pr.left-mw; if(left<8)left=8;
    var top=r.bottom-pr.top+6;
    if(top+mh > pr.height-10) top=r.top-pr.top-mh-6;
    menu.style.left=left+'px'; menu.style.top=top+'px';
    return;
  }
  var card=e.target.closest('.card');
  if(card) card.animate([{transform:'scale(.99)'},{transform:'scale(1)'}],{duration:150,easing:'ease-out'});
});
function closeMenu(){ menu.classList.remove('open'); }
document.addEventListener('click', closeMenu);
menu.addEventListener('click', function(e){ e.stopPropagation(); });

menu.querySelectorAll('button').forEach(function(b){
  b.addEventListener('click', function(){
    var act=b.dataset.act, s=slots.find(function(x){return x.id===activeId;});
    closeMenu();
    if(!s) return;
    if(act==='del'){
      var el=list.querySelector('.card[data-id="'+s.id+'"]');
      if(el){ el.classList.add('removing'); setTimeout(function(){ slots=slots.filter(function(x){return x.id!==s.id;}); render(); }, 280); }
      toast('Paper slot deleted');
    } else if(act==='dup'){
      var copy=mk(s.title+' (copy)', s.by, s.board, s.std, s.med, s.sub);
      var i=slots.findIndex(function(x){return x.id===s.id;});
      slots.splice(i+1,0,copy); render(); toast('Duplicated');
    } else if(act==='edit'){
      openSheet(s);
    }
  });
});

/* ---------- bottom sheet ---------- */
var overlay=document.getElementById('overlay'), sheet=document.getElementById('sheet');
var fTitle=document.getElementById('fTitle'), fBy=document.getElementById('fBy'), fBoard=document.getElementById('fBoard'),
    fStd=document.getElementById('fStd'), fMedium=document.getElementById('fMedium'), fSubject=document.getElementById('fSubject');
var sheetTitle=document.getElementById('sheetTitle'), sheetSub=document.getElementById('sheetSub'), saveBtn=document.getElementById('saveBtn');
var editingId=null;

function openSheet(s){
  editingId = s ? s.id : null;
  sheetTitle.textContent = s ? 'Edit Paper Slot' : 'New Paper Slot';
  sheetSub.textContent = s ? 'Update the details of this slot' : 'Fill in the details to create a slot';
  saveBtn.textContent = s ? 'Save changes' : 'Create slot';
  fTitle.value=s?s.title:''; fBy.value=s?s.by:''; fBoard.value=s?s.board:'RBSE';
  fStd.value=s?s.std:''; fMedium.value=s?s.med:''; fSubject.value=s?s.sub:'';
  overlay.classList.add('open'); sheet.classList.add('open');
  setTimeout(function(){ fTitle.focus(); }, 260);
}
function closeSheet(){ overlay.classList.remove('open'); sheet.classList.remove('open'); }
document.getElementById('addBtn').addEventListener('click', function(){ openSheet(null); });
document.getElementById('cancelBtn').addEventListener('click', closeSheet);
overlay.addEventListener('click', closeSheet);

saveBtn.addEventListener('click', function(){
  var title=fTitle.value.trim();
  if(!title){ fTitle.focus(); fTitle.style.borderColor='var(--red)'; setTimeout(function(){fTitle.style.borderColor='';},1200); return; }
  var vals={title:title, by:fBy.value.trim()||'Unknown', board:fBoard.value, std:fStd.value.trim(), med:fMedium.value.trim(), sub:fSubject.value.trim()};
  if(editingId){
    var s=slots.find(function(x){return x.id===editingId;});
    if(s){ s.title=vals.title; s.by=vals.by; s.board=vals.board; s.std=vals.std; s.med=vals.med; s.sub=vals.sub; }
    render(); toast('Changes saved');
  } else {
    var n=mk(vals.title, vals.by, vals.board, vals.std, vals.med, vals.sub);
    slots.unshift(n); render();
    document.querySelector('.body').scrollTop=0;
    toast('Paper slot added');
  }
  closeSheet();
});

/* ---------- toast ---------- */
var toastEl=document.getElementById('toast'), toastMsg=document.getElementById('toastMsg'), toastT;
function toast(msg){ toastMsg.textContent=msg; toastEl.classList.add('show'); clearTimeout(toastT); toastT=setTimeout(function(){toastEl.classList.remove('show');},2000); }

render();
</script>
</body>
</html>
