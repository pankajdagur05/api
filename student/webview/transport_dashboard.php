<?php
/**
 * parent_live.php
 * Parent view (App WebView / SMS link) — sirf apne bacche ki bus, aur shuru se uske stop tak ke stops.
 * URL: parent_live.php?sid=STUDENT_ID&t=TOKEN     (track_url($studentId) se banta hai)
 */
declare(strict_types=1);

require_once __DIR__ . '/transport_config.php';
require_once __DIR__ . '/transport_lib.php';

// Student id ab login (auth.php) se aati hai — parent_api.php khud nikaalta hai.
// Testing ke liye ?sid= aur ?shift= aage pass ho jaate hain.
$params = [];
if (TRANSPORT_ALLOW_SID_ONLY && isset($_GET['sid'])) {
    $params['sid'] = (string) $_GET['sid'];
}
if (isset($_GET['shift'])) {
    $params['shift'] = (string) $_GET['shift'];
}
$params['v'] = '1';

$cfg = transport_settings();
$mapsKey = (string) $cfg['google_maps_js_key'];
$apiUrl = 'parent_api.php?' . http_build_query($params);
?>
<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#0f62fe">
<title>बस लाइव ट्रैकिंग</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Hind:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
<?php if ($mapsKey !== ''): ?>
<script src="https://maps.googleapis.com/maps/api/js?key=<?= htmlspecialchars($mapsKey, ENT_QUOTES) ?>&libraries=geometry"></script>
<?php endif; ?>
<style>
:root{
  --text:#0f172a;--muted:#64748b;--line:#e2e8f0;--card:#fff;
  --accent:#0f62fe;--accent-soft:rgba(15,98,254,.1);
  --ok:#0f9f6e;--ok-soft:rgba(15,159,110,.12);
  --warn:#ef8a17;--warn-soft:rgba(239,138,23,.13);
  --bad:#df4759;--bad-soft:rgba(223,71,89,.12);
  --shadow:0 10px 30px rgba(21,44,73,.1);
  --safe-top:env(safe-area-inset-top,0px);--safe-bottom:env(safe-area-inset-bottom,0px);
}
*{box-sizing:border-box;-webkit-tap-highlight-color:transparent}
html,body{margin:0}
body{font-family:'Hind','Manrope',system-ui,sans-serif;color:var(--text);background:#eef4f8;min-height:100vh}
.hidden{display:none!important}
.muted{color:var(--muted);font-size:14px}
.num{font-family:'Manrope',sans-serif}

.top{position:sticky;top:0;z-index:20;display:flex;align-items:center;gap:12px;padding:calc(12px + var(--safe-top)) 16px 12px;background:rgba(255,255,255,.94);backdrop-filter:blur(14px);border-bottom:1px solid var(--line)}
.top .t{flex:1;min-width:0}
.top h1{margin:0;font-size:18px;line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.top p{margin:0;font-size:13px;color:var(--muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.pill{display:flex;align-items:center;gap:6px;padding:6px 11px;border-radius:999px;font-size:13px;font-weight:600;background:var(--ok-soft);color:var(--ok);white-space:nowrap}
.pill.off{background:#eef2f6;color:var(--muted)}
.pill .d{width:8px;height:8px;border-radius:50%;background:currentColor;animation:pulse 1.8s infinite}

.map-wrap{position:relative;height:40vh;min-height:260px;background:#dfe9f0}
#map{position:absolute;inset:0}
.fab{position:absolute;right:12px;bottom:28px;width:44px;height:44px;border:0;border-radius:14px;background:#fff;box-shadow:var(--shadow);font-size:20px}
.map-chip{position:absolute;left:12px;top:12px;right:12px;background:rgba(255,255,255,.95);backdrop-filter:blur(10px);border-radius:14px;padding:9px 12px;box-shadow:var(--shadow);font-size:13px;line-height:1.35}
.map-chip strong{display:block;font-size:14px}
.next-line{margin-top:12px;padding:10px 12px;border-radius:12px;background:var(--accent-soft);font-size:14px;line-height:1.4}
.next-line b{font-family:'Manrope',sans-serif}
.badges{display:flex;gap:6px;flex-wrap:wrap;margin-top:6px}
.badge{padding:4px 9px;border-radius:999px;background:#fff;font-size:12px;font-weight:600;color:#37506a;box-shadow:0 0 0 1px #e2e8f0}
.badge.ok{background:var(--ok);color:#fff;box-shadow:none}
.badge.blue{background:var(--accent);color:#fff;box-shadow:none}
.badge.grey{background:#eef2f6;color:#64748b;box-shadow:none}
.stop .row{display:flex;justify-content:space-between;align-items:flex-start;gap:8px}

.sheet{position:relative;margin-top:-18px;border-radius:22px 22px 0 0;background:#eef4f8;padding:14px 14px calc(28px + var(--safe-bottom))}
.card{background:var(--card);border-radius:20px;padding:16px;box-shadow:var(--shadow);margin-bottom:12px}

.status{display:flex;gap:14px;align-items:center}
.ring{flex:0 0 86px;height:86px;border-radius:50%;display:grid;place-items:center;text-align:center;background:conic-gradient(var(--accent) var(--p,0%),#e6eef5 0);position:relative}
.ring::before{content:"";position:absolute;inset:8px;border-radius:50%;background:#fff}
.ring span{position:relative;line-height:1}
.ring b{display:block;font-family:'Manrope';font-size:26px;letter-spacing:-.04em}
.ring small{font-size:12px;color:var(--muted)}
.ring.ok{background:conic-gradient(var(--ok) 100%,#e6eef5 0)}
.ring.grey{background:#e6eef5}
.status h2{margin:0 0 4px;font-size:20px;line-height:1.3}
.status .sub{font-size:15px;color:#334155;line-height:1.45}
.tags{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
.tag{padding:7px 11px;border-radius:999px;background:#f1f5f9;font-size:13px;font-weight:600}
.tag.blue{background:var(--accent-soft);color:var(--accent)}
.tag.ok{background:var(--ok-soft);color:var(--ok)}
.tag.warn{background:var(--warn-soft);color:var(--warn)}
.tag.bad{background:var(--bad-soft);color:var(--bad)}

.bus{display:flex;align-items:center;gap:12px}
.bus .ic{width:46px;height:46px;border-radius:14px;background:var(--accent-soft);display:grid;place-items:center;font-size:24px}
.bus .info{flex:1;min-width:0}
.bus strong{display:block;font-size:16px}
.call{display:inline-flex;align-items:center;gap:6px;padding:10px 14px;border-radius:14px;background:var(--ok);color:#fff;text-decoration:none;font-weight:600}

.sec{display:flex;justify-content:space-between;align-items:center;margin-bottom:14px}
.sec strong{font-size:16px}
.tl-item{display:flex;gap:12px;padding-bottom:12px}
.tl-item:last-child{padding-bottom:0}
.rail{position:relative;width:20px;flex:0 0 20px}
.rail::after{content:"";position:absolute;top:18px;left:9px;bottom:-4px;width:2px;background:#e3ebf1}
.tl-item:last-child .rail::after{display:none}
.tl-item.departed .rail::after{background:#9fd8c2}
.dot{position:absolute;top:5px;left:1px;width:18px;height:18px;border-radius:50%;border:3px solid #fff;background:#c3d0db;box-shadow:0 0 0 1px #d6e0e8}
.tl-item.departed .dot{background:#9aa9b9}
.tl-item.current .dot{background:var(--ok);box-shadow:0 0 0 6px var(--ok-soft)}
.tl-item.next .dot{background:var(--accent);box-shadow:0 0 0 6px var(--accent-soft)}
.tl-item.child .dot{width:22px;height:22px;left:-1px;top:3px;background:#ef8a17;box-shadow:0 0 0 6px var(--warn-soft)}
.stop{flex:1;padding:10px 12px;border-radius:14px;background:#f8fafc}
.tl-item.departed .stop{opacity:.6}
.tl-item.current .stop{background:var(--ok-soft)}
.tl-item.next .stop{background:var(--accent-soft)}
.tl-item.child .stop{background:#fff7ed;border:1.5px solid #fdba74}
.stop .n{font-weight:600;font-size:15px}
.stop .m{font-size:13px;color:var(--muted);margin-top:2px}

.foot{text-align:center;font-size:12px;color:var(--muted);margin-top:4px}
.err{padding:40px 20px;text-align:center}
@keyframes pulse{0%{opacity:1}50%{opacity:.35}100%{opacity:1}}
@media (min-width:760px){
  .layout{display:grid;grid-template-columns:420px 1fr;height:calc(100vh - 64px)}
  .map-wrap{height:100%;order:2}
  .sheet{margin:0;border-radius:0;overflow-y:auto;order:1}
}
</style>
</head>
<body>
<header class="top">
  <div class="t">
    <h1 id="title">बस लाइव ट्रैकिंग</h1>
    <p id="subtitle"><?= htmlspecialchars((string) $cfg['school_name'], ENT_QUOTES) ?></p>
  </div>
  <div id="livePill" class="pill off"><span class="d"></span><span id="liveText">लोड हो रहा है</span></div>
</header>

<div class="layout">
  <div class="map-wrap">
    <div id="map"></div>
    <div id="mapChip" class="map-chip hidden"></div>
    <button id="recenter" class="fab" type="button" aria-label="Recenter">◎</button>
  </div>

  <div class="sheet">
    <div id="statusCard" class="card">
      <div class="status">
        <div id="ring" class="ring grey"><span><b class="num">--</b><small>मिनट</small></span></div>
        <div><h2>जानकारी लोड हो रही है...</h2></div>
      </div>
    </div>
    <div id="busCard" class="card hidden"></div>
    <div class="card">
      <div class="sec"><strong>आपके स्टॉप तक का रास्ता</strong><span id="stopsMeta" class="muted"></span></div>
      <div id="timeline"></div>
    </div>
    <div id="updated" class="foot"></div>
  </div>
</div>

<script>
const API_URL = <?= json_encode($apiUrl) ?>;
const POLL_MS = 10000;
const HAS_MAPS = !!(window.google && window.google.maps);

let data = null, lastUpdate = 0, pollTimer = null;
let map, busMarker, busHalo, childMarker, routeLine, travelLine, stopMarkers = [];
let routeSig = null, lastPos = null, animFrame = null, fitted = false;

const $ = id => document.getElementById(id);
const esc = v => String(v ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
const km = v => (v === null || v === undefined) ? '--' : Number(v).toFixed(1);

async function load() {
  const r = await fetch(`${API_URL}&_=${Date.now()}`, { cache: 'no-store', credentials: 'same-origin' });
  const p = await r.json().catch(() => ({}));
  if (!r.ok || !p.success) {
    const extra = p.debug ? ' | ' + JSON.stringify(p.debug) : '';
    throw new Error((p.message || `डेटा नहीं मिला (HTTP ${r.status})`) + extra);
  }
  return p;
}

/* ---------------- Status card ---------------- */
function renderStatus(d) {
  const c = d.child, b = d.bus, drop = d.shift === 'DROP';
  let ringCls = 'grey', ringNum = '--', ringSmall = 'मिनट', pct = 0, h = '', sub = '', tags = [];

  if (!b.onMap) {
    h = d.serviceActive ? 'बस का GPS अभी ऑफ़लाइन है' : 'अभी बस सेवा का समय नहीं है';
    sub = d.serviceActive ? 'कुछ देर में दोबारा कोशिश करें।' : 'पिकअप / ड्रॉप शुरू होते ही यहाँ बस की लाइव लोकेशन दिखेगी।';
    ringNum = d.serviceActive ? '📡' : '🕑'; ringSmall = '';
  } else if (c.status === 'unknown') {
    h = 'बस की लोकेशन मैप पर दिख रही है';
    sub = d.serviceActive ? 'रूट की जानकारी अपडेट हो रही है।' : 'अभी पिकअप / ड्रॉप का समय नहीं है।';
    ringNum = '🚌'; ringSmall = '';
  } else if (c.status === 'arrived') {
    ringCls = 'ok'; ringNum = '✓'; ringSmall = 'पहुँची';
    h = 'बस आपके स्टॉप पर पहुँच गई है';
    sub = drop ? `कृपया ${esc(d.student.name)} को ${esc(c.stopName)} पर रिसीव करें।` : `कृपया ${esc(d.student.name)} को बस में बैठाएँ।`;
  } else if (c.status === 'passed') {
    ringCls = 'ok'; ringNum = '✓'; ringSmall = '';
    h = drop ? 'बस आपके स्टॉप से आगे निकल चुकी है' : 'बस आपके स्टॉप से निकल चुकी है';
    sub = drop ? `${esc(d.student.name)} को ${esc(c.stopName)} पर छोड़ा जा चुका है।` : 'बस अब स्कूल की ओर जा रही है।';
  } else {
    const eta = Number(c.etaMin ?? 0);
    ringCls = ''; ringNum = String(eta); pct = Math.max(6, Math.min(100, 100 - (eta / 45) * 100));
    h = `बस ${km(c.distanceKm)} km दूर है`;
    sub = `लगभग <b class="num">${eta}</b> मिनट में ${c.arrivalTime ? `(<b class="num">${esc(c.arrivalTime)}</b> तक) ` : ''}${esc(c.stopName)} पहुँचेगी।`;
    if (c.stopsAway === 0) tags.push(['ok', 'अगला स्टॉप आपका है!']);
    else if (c.stopsAway !== null) tags.push(['blue', `${c.stopsAway} स्टॉप के बाद आपका स्टॉप`]);
    if (eta <= 10) tags.push(['warn', drop ? 'स्टॉप पर पहुँचें' : 'बच्चे को तैयार रखें']);
  }

  if (b.onMap) {
    if (b.isStale) tags.push(['bad', `GPS ${b.gpsAgeMin ?? ''} मिनट पुराना`]);
    else tags.push(b.moving ? ['ok', `चल रही है • ${Math.round(b.speed)} km/h`] : ['warn', 'अभी रुकी हुई है']);
    if (!d.serviceActive) tags.push(['', 'सेवा समय के बाहर']);
  }

  $('statusCard').innerHTML = `
    <div class="status">
      <div id="ring" class="ring ${ringCls}" style="--p:${pct}%"><span><b class="num">${ringNum}</b><small>${ringSmall}</small></span></div>
      <div><h2>${h}</h2><div class="sub">${sub}</div></div>
    </div>
    ${tags.length ? `<div class="tags">${tags.map(([c, t]) => `<span class="tag ${c}">${t}</span>`).join('')}</div>` : ''}`;

  const live = b.onMap && b.online;
  $('livePill').className = `pill ${live ? '' : 'off'}`;
  $('liveText').textContent = live ? (d.serviceActive ? (drop ? 'ड्रॉप • लाइव' : 'पिकअप • लाइव') : 'लाइव') : 'ऑफ़लाइन';
}

function renderBus(d) {
  const b = d.bus;
  $('busCard').classList.remove('hidden');
  $('busCard').innerHTML = `
    <div class="bus">
      <div class="ic">🚌</div>
      <div class="info">
        <strong class="num">${esc(b.vehicleNumber)}</strong>
        <div class="muted">${esc(b.routeName)}${b.busColor ? ` • ${esc(b.busColor)} रंग` : ''}${b.driverName && b.driverName !== 'Unknown' ? ` • ड्राइवर ${esc(b.driverName)}` : ''}</div>
      </div>
      ${b.driverMobile ? `<a class="call" href="tel:${esc(b.driverMobile)}">📞 कॉल</a>` : ''}
    </div>
    ${busWhereLine(d)}`;
}

/* बस अभी कहाँ है + अगला स्टॉप कितनी दूर / कितने समय में */
function busWhereLine(d) {
  const b = d.bus;
  if (!b.onMap) return '';
  const parts = [];
  if (b.currentStop) parts.push(`🟢 बस अभी <b>${esc(b.currentStop)}</b> स्टॉप पर है`);
  if (b.nextStop) {
    const n = b.nextStop;
    parts.push(`➡️ अगला स्टॉप <b>${esc(n.name)}</b> — <b>${km(n.distance)} km</b> दूर, लगभग <b>${n.etaMin ?? '--'} मिनट</b>${n.arrivalTime ? ` (${esc(n.arrivalTime)})` : ''}`);
  } else if (!b.currentStop && b.location) {
    parts.push(`📍 ${esc(b.location)}`);
  }
  return parts.length ? `<div class="next-line">${parts.join('<br>')}</div>` : '';
}

function renderTimeline(d) {
  $('stopsMeta').textContent = `${d.totalStops} स्टॉप`;
  const STATUS = {
    current:  ['ok',   '🚌 बस यहाँ है'],
    departed: ['grey', '✓ निकल चुकी'],
    next:     ['blue', '➡️ अगला स्टॉप'],
    upcoming: ['',     'आने वाला'],
  };
  const hasLive = d.bus.onMap && d.child.status !== 'unknown';

  $('timeline').innerHTML = d.stops.map((s, i) => {
    const st = s.status || 'upcoming';
    const cls = `${st}${s.isChild ? ' child' : ''}`;
    const [bc, bt] = STATUS[st] || STATUS.upcoming;

    let meta = '';
    if (!hasLive) {
      meta = '';
    } else if (st === 'departed') {
      meta = '<div class="m">बस यहाँ से निकल चुकी है</div>';
    } else if (st === 'current') {
      meta = '<div class="m">बस अभी इस स्टॉप पर खड़ी है</div>';
    } else {
      const badges = [];
      if (s.distance !== null && s.distance !== undefined) badges.push(`📏 ${km(s.distance)} km दूर`);
      if (s.etaMin !== null && s.etaMin !== undefined) badges.push(`⏱ ${s.etaMin} मिनट`);
      if (s.arrivalTime) badges.push(`🕐 ${esc(s.arrivalTime)} तक`);
      meta = badges.length ? `<div class="badges">${badges.map(t => `<span class="badge">${t}</span>`).join('')}</div>` : '';
    }

    return `
      <div class="tl-item ${cls}">
        <div class="rail"><div class="dot"></div></div>
        <div class="stop">
          <div class="row">
            <div class="n">${i + 1}. ${esc(s.name)}${s.isChild ? ' 🏠' : ''}</div>
            ${hasLive ? `<span class="badge ${bc}">${bt}</span>` : ''}
          </div>
          ${s.isChild ? '<div class="badges"><span class="tag warn" style="padding:3px 8px;font-size:12px">आपका स्टॉप</span></div>' : ''}
          ${meta}
        </div>
      </div>`;
  }).join('');
}

/* ---------------- Map ---------------- */
function busIcon(rot = 0) {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 80 80"><g transform="rotate(${rot} 40 40)"><circle cx="40" cy="40" r="28" fill="rgba(15,98,254,0.16)"/><path d="M24 24c0-6.6 5.4-12 12-12h8c6.6 0 12 5.4 12 12v22.5c0 2.5-1.5 4.8-3.8 5.8V58a3 3 0 0 1-6 0v-4H33.8v4a3 3 0 0 1-6 0v-5.7A6.2 6.2 0 0 1 24 46.5V24z" fill="#0f62fe"/><rect x="30" y="20" width="20" height="12" rx="5" fill="#9fccff"/><rect x="29" y="36" width="22" height="10" rx="3" fill="#dff0ff"/></g></svg>`;
  return { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg), scaledSize: new google.maps.Size(54, 54), anchor: new google.maps.Point(27, 27) };
}
function homeIcon() {
  const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="48" height="58" viewBox="0 0 48 58"><path d="M24 57s20-19.6 20-33A20 20 0 0 0 4 24c0 13.4 20 33 20 33z" fill="#ef8a17" stroke="#fff" stroke-width="3"/><path d="M14 25l10-8 10 8v9H28v-6h-8v6h-6z" fill="#fff"/></svg>`;
  return { url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg), scaledSize: new google.maps.Size(40, 48), anchor: new google.maps.Point(20, 48) };
}

function initMap(center) {
  map = new google.maps.Map($('map'), {
    center, zoom: 14, mapTypeControl: false, streetViewControl: false, fullscreenControl: false,
    gestureHandling: 'greedy', clickableIcons: false,
    styles: [
      { featureType: 'poi', stylers: [{ visibility: 'off' }] },
      { featureType: 'transit', stylers: [{ visibility: 'off' }] },
      { featureType: 'road', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
      { featureType: 'water', stylers: [{ color: '#cae7ff' }] },
      { featureType: 'landscape', stylers: [{ color: '#eff5f8' }] }
    ]
  });
}

function animate(from, to) {
  cancelAnimationFrame(animFrame);
  if (!from) { busMarker.setPosition(to); busHalo.setCenter(to); return; }
  const start = performance.now(), dur = 8000;
  const step = now => {
    const p = Math.min((now - start) / dur, 1);
    const e = p < .5 ? 4 * p * p * p : 1 - Math.pow(-2 * p + 2, 3) / 2;
    const cur = { lat: from.lat + (to.lat - from.lat) * e, lng: from.lng + (to.lng - from.lng) * e };
    busMarker.setPosition(cur);
    busHalo.setCenter(cur);
    if (p < 1) animFrame = requestAnimationFrame(step);
  };
  animFrame = requestAnimationFrame(step);
}

/* Bus jo rasta tay kar chuki (green) — nikle hue stops + bus ki position */
function drawTravelled(stops, pos) {
  const path = [];
  for (const s of stops) {
    if (s.status !== 'departed' && s.status !== 'current') break;
    path.push({ lat: Number(s.lat), lng: Number(s.lng) });
  }
  if (travelLine) travelLine.setMap(null);
  if (!path.length) { travelLine = null; return; }
  path.push(pos);
  travelLine = new google.maps.Polyline({ map, path, strokeColor: '#0f9f6e', strokeOpacity: .95, strokeWeight: 6, zIndex: 5 });
}

function renderMapChip(d) {
  const b = d.bus, chip = $('mapChip');
  if (!b.onMap) { chip.classList.add('hidden'); return; }
  let line;
  if (b.currentStop) line = `बस अभी <b>${esc(b.currentStop)}</b> पर`;
  else if (b.nextStop) line = `अगला: <b>${esc(b.nextStop.name)}</b> • ${km(b.nextStop.distance)} km • ${b.nextStop.etaMin ?? '--'} मिनट`;
  else line = esc(b.location || 'बस की लाइव लोकेशन');
  chip.innerHTML = `<strong>🚌 ${esc(b.vehicleNumber)} ${b.isStale ? '• GPS पुराना' : (b.moving ? `• ${Math.round(b.speed)} km/h` : '• रुकी हुई')}</strong><span class="muted">${line}</span>`;
  chip.classList.remove('hidden');
}

async function drawRoute(stops) {
  const sig = stops.map(s => `${s.lat},${s.lng}`).join('|');
  if (!stops.length || sig === routeSig) return;
  routeSig = sig;
  let path = stops.map(s => ({ lat: Number(s.lat), lng: Number(s.lng) }));
  if (path.length >= 2 && path.length <= 25) {
    try {
      const res = await new google.maps.DirectionsService().route({
        origin: path[0], destination: path[path.length - 1],
        waypoints: path.slice(1, -1).map(l => ({ location: l, stopover: true })),
        travelMode: google.maps.TravelMode.DRIVING
      });
      const op = res.routes?.[0]?.overview_path;
      if (op?.length) path = op;
    } catch (e) { /* straight line */ }
  }
  if (routeLine) routeLine.setMap(null);
  routeLine = new google.maps.Polyline({ map, path, strokeColor: '#0f62fe', strokeOpacity: .7, strokeWeight: 5 });
}

function fit(d) {
  if (!map) return;
  const bounds = new google.maps.LatLngBounds();
  bounds.extend({ lat: Number(d.child.lat), lng: Number(d.child.lng) });
  if (d.bus.onMap && d.bus.lat) bounds.extend({ lat: Number(d.bus.lat), lng: Number(d.bus.lng) });
  d.stops.forEach(s => { if (s.status !== 'departed') bounds.extend({ lat: Number(s.lat), lng: Number(s.lng) }); });
  map.fitBounds(bounds, 60);
  google.maps.event.addListenerOnce(map, 'idle', () => { if (map.getZoom() > 16) map.setZoom(16); });
}

function updateMap(d) {
  if (!HAS_MAPS) { $('map').innerHTML = '<div class="err muted">मैप उपलब्ध नहीं है</div>'; return; }
  const childPos = { lat: Number(d.child.lat), lng: Number(d.child.lng) };
  if (!map) initMap(childPos);

  if (!childMarker) childMarker = new google.maps.Marker({ map, position: childPos, icon: homeIcon(), zIndex: 900, title: d.child.stopName });

  // Admin jaisa: numbered stop markers, status ke rang me
  stopMarkers.forEach(m => m.setMap(null));
  stopMarkers = d.stops.map((s, i) => s.isChild ? null : new google.maps.Marker({
    map, position: { lat: Number(s.lat), lng: Number(s.lng) }, title: s.name, zIndex: 100,
    label: { text: String(i + 1), color: '#fff', fontWeight: '700', fontSize: '11px' },
    icon: { path: google.maps.SymbolPath.CIRCLE, scale: 10, fillOpacity: 1, strokeColor: '#fff', strokeWeight: 2,
      fillColor: s.status === 'departed' ? '#97a8b6' : s.status === 'current' ? '#0f9f6e' : s.status === 'next' ? '#0f62fe' : '#ef8a17' }
  })).filter(Boolean);

  drawRoute(d.stops);
  renderMapChip(d);

  // Bus icon — position ho to hamesha dikhe (admin view jaisa)
  if (d.bus.onMap && d.bus.lat) {
    const pos = { lat: Number(d.bus.lat), lng: Number(d.bus.lng) };
    if (!busMarker) {
      busHalo = new google.maps.Circle({ map, center: pos, radius: 45, fillColor: '#0f62fe', fillOpacity: .15, strokeOpacity: 0, zIndex: 998 });
      busMarker = new google.maps.Marker({ map, position: pos, icon: busIcon(0), zIndex: 999, title: d.bus.vehicleNumber });
    }
    busMarker.setMap(map);
    busHalo.setMap(map);
    busMarker.setOpacity(d.bus.isStale ? 0.55 : 1);
    if (lastPos && (lastPos.lat !== pos.lat || lastPos.lng !== pos.lng)) {
      busMarker.setIcon(busIcon(google.maps.geometry.spherical.computeHeading(new google.maps.LatLng(lastPos.lat, lastPos.lng), new google.maps.LatLng(pos.lat, pos.lng))));
    }
    animate(lastPos, pos);
    lastPos = pos;
    drawTravelled(d.stops, pos);
  } else {
    if (busMarker) busMarker.setMap(null);
    if (busHalo) busHalo.setMap(null);
    if (travelLine) { travelLine.setMap(null); travelLine = null; }
    lastPos = null;
  }

  // Pehli baar fit, aur jab bus pehli baar map par aaye tab dobara
  if (!fitted || (fitted === 'nobus' && d.bus.onMap)) { fit(d); fitted = d.bus.onMap ? true : 'nobus'; }
}

$('recenter').addEventListener('click', () => data && fit(data));

/* ---------------- Loop ---------------- */
async function refresh() {
  clearTimeout(pollTimer);
  try {
    data = await load();
    lastUpdate = Date.now();
    $('title').textContent = `${data.student.name} की बस`;
    $('subtitle').textContent = `${data.school.name || ''} • स्टॉप: ${data.child.stopName}`;
    renderStatus(data);
    renderBus(data);
    renderTimeline(data);
    updateMap(data);
  } catch (e) {
    if (!data) $('statusCard').innerHTML = `<div class="err"><h3>जानकारी नहीं मिल पाई</h3><p class="muted">${esc(e.message)}</p></div>`;
  }
  if (!document.hidden) pollTimer = setTimeout(refresh, POLL_MS);
}

setInterval(() => {
  if (!lastUpdate) return;
  const s = Math.round((Date.now() - lastUpdate) / 1000);
  $('updated').textContent = `अपडेट: ${s < 5 ? 'अभी' : s + ' सेकंड पहले'} • हर 10 सेकंड में ऑटो-अपडेट`;
}, 1000);

document.addEventListener('visibilitychange', () => { if (!document.hidden) refresh(); else clearTimeout(pollTimer); });
refresh();
</script>
</body>
</html>