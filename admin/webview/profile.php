<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Coming Soon</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Manrope:wght@700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<style>
  :root{
    --primary:#4F46E5;
    --secondary:#7C3AED;
    --accent:#06B6D4;
    --success:#22C55E;
    --ink:#111827;
    --ink-soft:#6B7280;
    --hairline:rgba(17,24,39,0.07);
  }

  *{ margin:0; padding:0; box-sizing:border-box; }
  html,body{ height:100%; font-family:'Inter', sans-serif; }

  body{
    background:#E9EBF3;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
  }

  /* ============ DEVICE FRAME ============ */
  .device{
    position:relative;
    width:390px;
    height:844px;
    background:
      radial-gradient(130% 55% at 10% -5%, #EEF2FF 0%, transparent 55%),
      radial-gradient(110% 50% at 105% 105%, #ECFEFF 0%, transparent 55%),
      linear-gradient(180deg, #FAFBFF 0%, #FFFFFF 62%);
    overflow:hidden; 
    isolation:isolate;
  }

  .grain{
    position:absolute; inset:0; z-index:6; pointer-events:none; opacity:0.02;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='120' height='120'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  } 

  .status-icons{ display:flex; align-items:center; gap:6px; }

  .signal-bars{ display:flex; align-items:flex-end; gap:2px; height:10px; }
  .signal-bars span{ width:3px; background:var(--ink); border-radius:1px; }
  .signal-bars span:nth-child(1){ height:4px; }
  .signal-bars span:nth-child(2){ height:6px; }
  .signal-bars span:nth-child(3){ height:8px; }
  .signal-bars span:nth-child(4){ height:10px; }

  .wifi-icon{
    width:14px; height:10px;
    position:relative;
  }
  .wifi-icon::before{
    content:"";
    position:absolute;
    inset:0;
    border:2px solid var(--ink);
    border-bottom:none;
    border-left:none;
    border-right:none;
    border-radius:50% 50% 0 0 / 100% 100% 0 0;
    transform:scale(1);
  }

  .battery{
    width:22px; height:11px;
    border:1.4px solid var(--ink);
    border-radius:3px;
    position:relative;
    opacity:0.9;
  }
  .battery::after{
    content:"";
    position:absolute;
    top:1.4px; left:1.4px; bottom:1.4px;
    width:70%;
    background:var(--ink);
    border-radius:1.5px;
  }
  .battery::before{
    content:"";
    position:absolute;
    right:-3px; top:3px;
    width:2px; height:5px;
    background:var(--ink);
    border-radius:0 2px 2px 0;
  }

  /* ============ BACKGROUND MESH ============ */
  .bg-blob{ position:absolute; border-radius:50%; filter:blur(72px); z-index:0; }
  .blob-1{ width:300px; height:300px; top:-100px; left:-100px; background:radial-gradient(circle at 30% 30%, rgba(79,70,229,0.48), transparent 70%); animation:drift1 10s ease-in-out infinite; }
  .blob-2{ width:280px; height:280px; bottom:-120px; right:-100px; background:radial-gradient(circle at 60% 60%, rgba(6,182,212,0.42), transparent 70%); animation:drift2 12s ease-in-out infinite; }
  .blob-3{ width:220px; height:220px; top:42%; right:-120px; background:radial-gradient(circle, rgba(124,58,237,0.32), transparent 70%); animation:drift3 9s ease-in-out infinite; }
  .blob-4{ width:170px; height:170px; top:6%; left:22%; background:radial-gradient(circle, rgba(34,197,94,0.16), transparent 70%); animation:drift3 13s ease-in-out infinite reverse; }

  @keyframes drift1{ 0%,100%{ transform:translate(0,0) scale(1);} 50%{ transform:translate(22px,26px) scale(1.08);} }
  @keyframes drift2{ 0%,100%{ transform:translate(0,0) scale(1);} 50%{ transform:translate(-20px,-22px) scale(1.1);} }
  @keyframes drift3{ 0%,100%{ transform:translate(0,0) scale(1);} 50%{ transform:translate(-16px,16px) scale(0.94);} }

  .grid-overlay{
    position:absolute; inset:0; z-index:0;
    background-image:radial-gradient(rgba(79,70,229,0.06) 1px, transparent 1px);
    background-size:22px 22px;
    mask-image:radial-gradient(ellipse 80% 55% at 50% 32%, black 40%, transparent 90%);
  }

  .sparkle{ position:absolute; border-radius:50%; z-index:1; }
  .sparkle.dot{ background:var(--primary); box-shadow:0 0 10px 3px rgba(79,70,229,0.5); }
  .s1{ top:150px; left:30px; width:5px; height:5px; animation:floaty 5s ease-in-out infinite; }
  .s2{ top:210px; right:38px; width:4px; height:4px; background:var(--accent); box-shadow:0 0 8px 2px rgba(6,182,212,0.6); animation:floaty 6.2s ease-in-out infinite 0.4s; }
  .s3{ bottom:210px; left:46px; width:5px; height:5px; background:var(--secondary); box-shadow:0 0 8px 2px rgba(124,58,237,0.5); animation:floaty 4.8s ease-in-out infinite 0.9s; }
  .s4{ bottom:160px; right:54px; width:6px; height:6px; background:#fff; box-shadow:0 0 10px 3px rgba(79,70,229,0.35); animation:floaty 5.4s ease-in-out infinite 0.2s; }

  @keyframes floaty{ 0%,100%{ transform:translateY(0) scale(1); opacity:0.75;} 50%{ transform:translateY(-14px) scale(1.15); opacity:1;} }

  /* ============ MAIN CARD ============ */
  .card{
    position:relative;
    z-index:2;
    width:326px;
    margin:0 auto;
    margin-top:56px; 
    border:1px solid rgba(255,255,255,0.75);
    border-radius:30px;
    padding:75px 26px 28px; 
    text-align:center;
    animation:fadeSlideUp 1s cubic-bezier(0.22,1,0.36,1) both;
  }

  @keyframes fadeSlideUp{ 0%{ opacity:0; transform:translateY(30px);} 100%{ opacity:1; transform:translateY(0);} }

  /* ============ SIGNATURE ILLUSTRATION: assembling core ============ */
  .core-scene{
    position:relative;
    width:118px; height:118px;
    margin:0 auto 10px;
    display:flex; align-items:center; justify-content:center;
    animation:float 4.5s ease-in-out infinite;
  }

  .halo{
    position:absolute; width:118px; height:118px; border-radius:50%;
    background:radial-gradient(circle, rgba(79,70,229,0.20), rgba(6,182,212,0.04) 65%, transparent 100%);
    filter:blur(6px);
    animation:pulseGlow 3.2s ease-in-out infinite;
  }

  /* conic progress ring */
  .progress-ring{
    position:absolute;
    width:100px; height:100px;
    border-radius:50%;
    background:conic-gradient(var(--primary) 0deg, var(--accent) 210deg, transparent 210deg, transparent 360deg);
    -webkit-mask:radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 3px));
            mask:radial-gradient(farthest-side, transparent calc(100% - 3px), #000 calc(100% - 3px));
    animation:ringRotate 6s linear infinite;
  }

  @keyframes ringRotate{ from{ transform:rotate(0deg);} to{ transform:rotate(360deg);} }

  .orbit{
    position:absolute;
    border-radius:50%;
    border:1px dashed rgba(79,70,229,0.18);
  }
  .orbit-a{ width:82px; height:82px; animation:spin 9s linear infinite; }
  .orbit-b{ width:66px; height:66px; animation:spin 6s linear infinite reverse; }

  .particle{
    position:absolute;
    width:8px; height:8px;
    border-radius:50%;
    top:-4px; left:50%;
    transform:translateX(-50%);
  }
  .particle-1{ background:linear-gradient(135deg, var(--primary), var(--secondary)); box-shadow:0 0 10px rgba(79,70,229,0.7); }
  .particle-2{ background:linear-gradient(135deg, var(--accent), var(--primary)); box-shadow:0 0 8px rgba(6,182,212,0.7); width:6px; height:6px; }

  @keyframes spin{ from{ transform:rotate(0deg);} to{ transform:rotate(360deg);} }

  .core{
    position:relative;
    width:46px; height:46px;
    border-radius:50%;
    background:
      radial-gradient(circle at 32% 28%, #C7D2FE 0%, transparent 42%),
      linear-gradient(155deg, var(--primary) 0%, var(--secondary) 55%, var(--accent) 100%);
    box-shadow:
      inset -7px -7px 14px rgba(17,24,39,0.28),
      inset 3px 3px 8px rgba(255,255,255,0.35),
      0 10px 24px -6px rgba(79,70,229,0.55);
    z-index:3;
    animation:coreBeat 2.4s ease-in-out infinite;
  }

  @keyframes coreBeat{ 0%,100%{ transform:scale(1);} 50%{ transform:scale(1.06);} }

  @keyframes float{ 0%,100%{ transform:translateY(0);} 50%{ transform:translateY(-12px);} }
  @keyframes pulseGlow{ 0%,100%{ opacity:0.6; transform:scale(1);} 50%{ opacity:1; transform:scale(1.1);} }

  /* ============ BADGE ============ */
  .badge{
    display:inline-flex; align-items:center; gap:6px;
    padding:7px 15px;
    border-radius:999px;
    background:linear-gradient(135deg, var(--primary), var(--secondary));
    color:#fff;
    font-size:11.5px;
    font-weight:700;
    letter-spacing:0.3px;
    box-shadow:0 8px 20px -6px rgba(79,70,229,0.55);
    margin-bottom:18px;
    animation:badgePulse 3s ease-in-out infinite;
  }

  @keyframes badgePulse{ 0%,100%{ box-shadow:0 8px 20px -6px rgba(79,70,229,0.55);} 50%{ box-shadow:0 10px 28px -4px rgba(124,58,237,0.7);} }

  /* ============ HEADING ============ */
  .heading{
    font-family:'Manrope', 'Inter', sans-serif;
    font-size:31px;
    font-weight:800;
    color:var(--ink);
    letter-spacing:-0.7px;
    line-height:1.14;
    margin-bottom:12px;
  }

  .heading span{
    background:linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip:text; background-clip:text; color:transparent;
  }

  /* ============ DESCRIPTION ============ */
  .description{
    font-size:14px;
    line-height:1.62;
    color:var(--ink-soft);
    font-weight:500;
    max-width:250px;
    margin:0 auto 22px;
  }

  /* ============ SOCIAL PROOF ============ */
  .social-proof{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    margin-bottom:24px;
    padding-bottom:22px;
    border-bottom:1px solid var(--hairline);
  }

  .avatar-stack{ display:flex; }
  .avatar-stack span{
    width:26px; height:26px;
    border-radius:50%;
    border:2px solid #fff;
    margin-left:-8px;
    display:flex; align-items:center; justify-content:center;
    font-size:9.5px;
    font-weight:700;
    color:#fff;
    font-family:'Space Grotesk', sans-serif;
  }
  .avatar-stack span:first-child{ margin-left:0; }
  .a1{ background:linear-gradient(135deg, #818CF8, var(--primary)); }
  .a2{ background:linear-gradient(135deg, #22D3EE, var(--accent)); }
  .a3{ background:linear-gradient(135deg, #A78BFA, var(--secondary)); }
  .a4{ background:linear-gradient(135deg, #6EE7B7, var(--success)); }

  .social-text{
    font-size:12px;
    font-weight:600;
    color:var(--ink-soft);
    text-align:left;
  }
  .social-text b{ color:var(--ink); font-weight:800; }

  /* ============ PROGRESS BAR ============ */
  .progress-label{ display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; }
  .progress-label span{ font-size:11px; font-weight:700; color:var(--ink-soft); letter-spacing:0.3px; }
  .progress-label .pct{
    font-family:'Space Grotesk', sans-serif;
    background:linear-gradient(135deg, var(--primary), var(--accent));
    -webkit-background-clip:text; background-clip:text; color:transparent;
    font-weight:700;
  }

  .progress-track{
    position:relative; width:100%; height:7px; border-radius:999px;
    background:#EAEDF7; overflow:hidden; margin-bottom:20px;
    box-shadow:inset 0 1px 3px rgba(17,24,39,0.06);
  }

  .progress-fill{
    position:absolute; top:0; left:0; height:100%; width:32%;
    border-radius:999px;
    background:linear-gradient(90deg, var(--primary), var(--accent));
    box-shadow:0 0 14px rgba(79,70,229,0.6);
    animation:loadBar 2.8s ease-in-out infinite;
  }

  @keyframes loadBar{ 0%{ left:-42%; width:32%;} 50%{ width:58%;} 100%{ left:100%; width:32%;} }

  /* ============ TYPING / PREPARING INDICATOR ============ */
  .preparing{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    margin-bottom:4px;
  }

  .preparing-text{
    font-size:12.5px;
    font-weight:600;
    color:var(--ink-soft);
  }

  .typing-dots{ display:flex; gap:4px; }
  .typing-dots span{
    width:5px; height:5px;
    border-radius:50%;
    background:linear-gradient(135deg, var(--primary), var(--secondary));
    animation:typeDot 1.2s ease-in-out infinite;
  }
  .typing-dots span:nth-child(1){ animation-delay:0s; }
  .typing-dots span:nth-child(2){ animation-delay:0.2s; }
  .typing-dots span:nth-child(3){ animation-delay:0.4s; }

  @keyframes typeDot{ 0%,60%,100%{ opacity:0.3; transform:translateY(0);} 30%{ opacity:1; transform:translateY(-4px);} }

  /* ============ BOTTOM TEXT ============ */
  .bottom-text{
    font-size:11.5px; font-weight:700; color:var(--ink-soft);
    letter-spacing:0.6px; text-transform:uppercase; opacity:0.85;
    margin-top:20px;
  }

  .shimmer-line{
    width:60px; height:3px; margin:10px auto 0; border-radius:999px;
    background:linear-gradient(90deg, transparent, var(--primary), var(--accent), transparent);
    background-size:200% 100%;
    animation:shimmer 3s linear infinite;
  }

  @keyframes shimmer{ 0%{ background-position:200% 0;} 100%{ background-position:-200% 0;} }

  /* ============ HOME INDICATOR ============ */
  .home-indicator{
    position:absolute;
    bottom:10px;
    left:50%;
    transform:translateX(-50%);
    width:134px; height:5px;
    border-radius:999px;
    background:var(--ink);
    opacity:0.85;
    z-index:4;
  }

  @media (prefers-reduced-motion: reduce){
    *{ animation-duration:0.001s !important; animation-iteration-count:1 !important; }
  }
</style>
</head>
<body>

  <div class="device"> 

    <div class="grid-overlay"></div>
    <div class="bg-blob blob-1"></div>
    <div class="bg-blob blob-2"></div>
    <div class="bg-blob blob-3"></div>
    <div class="bg-blob blob-4"></div>

    <div class="sparkle dot s1"></div>
    <div class="sparkle dot s2"></div>
    <div class="sparkle dot s3"></div>
    <div class="sparkle s4"></div>

    <!-- Main Card -->
    <div class="card">

      <!-- Signature illustration: assembling core -->
      <div class="core-scene">
        <div class="halo"></div>
        <div class="progress-ring"></div>
        <div class="orbit orbit-a">
          <div class="particle particle-1"></div>
        </div>
        <div class="orbit orbit-b">
          <div class="particle particle-2"></div>
        </div>
        <div class="core"></div>
      </div>

      <div class="badge">🚀 Coming Soon</div>

      <h1 class="heading">Something <span>Amazing</span><br>is Coming</h1>

      <p class="description">
        Something exciting is on the way. We're working hard to bring you an amazing experience.
      </p>

      <!-- Social proof -->
      <div class="social-proof">
        <div class="avatar-stack">
          <span class="a1">A</span>
          <span class="a2">R</span>
          <span class="a3">S</span>
          <span class="a4">+</span>
        </div>
        <div class="social-text"><b>2,400+</b> already waiting</div>
      </div>

      <!-- Progress bar -->
      <div class="progress-label">
        <span>Building your experience</span>
        <span class="pct">68%</span>
      </div>
      <div class="progress-track">
        <div class="progress-fill"></div>
      </div>

      <!-- Preparing indicator -->
      <div class="preparing">
        <span class="preparing-text">Preparing</span>
        <div class="typing-dots"><span></span><span></span><span></span></div>
      </div>

      <div class="bottom-text">Launching Soon</div>
      <div class="shimmer-line"></div>

    </div>

    <div class="home-indicator"></div>
    <div class="grain"></div>

  </div>

</body>
</html>