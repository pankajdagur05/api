<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Help — AI Chatbot UI</title>
  <style>
    :root{
      --bg:#f4f7fb;--card:#ffffff;--primary:#0066ff;--muted:#6b7b88;
    }
    *{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%}
    body{
      font-family: Inter, 'Segoe UI', Roboto, Arial, sans-serif;
      background:var(--bg);
      -webkit-font-smoothing:antialiased;
      -moz-osx-font-smoothing:grayscale;
      padding:0px;
    }

    /* App frame */
    .app{
      max-width:420px;
      height:calc(100vh - 28px);
      margin:0 auto;
      display:flex;
      flex-direction:column;
      background:linear-gradient(180deg, rgba(255,255,255,0.95), rgba(250,252,255,0.95));
      border-radius:16px;
      overflow:hidden;
      box-shadow:0 10px 30px rgba(20,30,60,0.08);
      border:1px solid rgba(0,0,0,0.04);
    }

    /* Header */
    .header{
      display:flex;align-items:center;gap:12px;padding:12px 16px;background-image: url('/mnt/data/WhatsApp Image 2025-11-21 at 10.56.33_cba12438.jpg');background-size:cover;background-position:center;min-height:78px;
    }
    .logo{
      width:52px;height:52px;border-radius:12px;background:rgba(255,255,255,0.9);display:grid;place-items:center;font-weight:700;color:var(--primary);
      box-shadow:0 4px 12px rgba(0,0,0,0.08);
    }
    .h-title{text-shadow:0 1px 2px rgba(0,0,0,0.25)}
    .h-title .main{font-size:16px;font-weight:700}
    .h-title .sub{font-size:12px;opacity:0.95}

    /* Chat area */
    .chat-wrap{display:flex;flex-direction:column;padding:14px;gap:10px;overflow:hidden}
    .messages{flex:1;overflow:auto;padding-right:6px;display:flex;flex-direction:column;gap:10px}

    .bubble{
      max-width:78%;padding:10px 12px;border-radius:14px;line-height:1.35;font-size:14px;box-shadow:0 6px 16px rgba(20,30,60,0.03);
    }
    .bot{align-self:flex-start;background:linear-gradient(180deg,#ffffff,#f6fbff);border:1px solid rgba(10,80,160,0.06)}
    .user{align-self:flex-end;background:linear-gradient(180deg,#e9f0ff,#dbeeff);border:1px solid rgba(0,100,220,0.08)}

    .meta{font-size:11px;color:var(--muted);margin-top:6px}

    /* Quick suggestions */
    .suggestions{display:flex;gap:8px;flex-wrap:wrap}
    .chip{background:#fff;padding:8px 10px;border-radius:999px;border:1px solid rgba(0,0,0,0.06);font-size:13px;cursor:pointer}

    /* Composer */
    .composer{padding:10px;border-top:1px solid rgba(0,0,0,0.04);background:linear-gradient(180deg,rgba(255,255,255,0.6),rgba(255,255,255,0.9));position: fixed;
    bottom: 0;
    width: 97%;
    left: 0px;}
    .composer-row{display:flex;gap:8px;align-items:center}
    .input{flex:1;display:flex;align-items:center;background:#fff;padding:8px 4px;border-radius:12px;border:1px solid rgba(0,0,0,0.04)}
    .input textarea{width:100%;border:0;resize:none;outline:none;font-size:12px;padding:5px;background:transparent;height:36px}
    .actions{display:flex;gap:8px;align-items:center}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:10px;background:var(--primary);color:#fff;border:0;font-weight:600;cursor:pointer}
    .icon-btn{width:40px;height:40px;border-radius:10px;background:#fff;border:1px solid rgba(0,0,0,0.04);display:grid;place-items:center;cursor:pointer}

    /* Typing indicator */
    .typing{display:inline-block;height:8px;width:36px}
    .dot{display:inline-block;height:8px;width:8px;margin:0 2px;border-radius:50%;background:#c3d6ff;opacity:0.9;animation:blink 1.2s infinite}
    .dot:nth-child(2){animation-delay:.2s}
    .dot:nth-child(3){animation-delay:.4s}
    @keyframes blink{0%{opacity:0.25}50%{opacity:1}100%{opacity:0.25}}

    /* Empty state */
    .empty{display:flex;flex-direction:column;align-items:center;justify-content:center;color:var(--muted);gap:8px;padding:20px}

    /* Responsive tweaks */
    @media (max-width:420px){
      .app{height:100vh;border-radius:0}
      .header{min-height:74px;padding:10px}
      .logo{width:46px;height:46px}
      .h-title .main{font-size:15px}
    }
  </style>
</head>
<body>
  <div class="app">
    <div class="header">
      <div class="logo">AI</div>
      <div class="h-title">
        <div class="main">Student Help — AI</div>
        <div class="sub">Ask anything about school, fees, homework...</div>
      </div>
    </div>

    <div class="chat-wrap">
      <div class="messages" id="messages">
        <div class="empty" id="emptyState">
          <svg width="88" height="64" viewBox="0 0 88 64" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="2" y="6" width="84" height="48" rx="8" stroke="#e6eefc" stroke-width="2" fill="#f7fbff"/><path d="M22 34h44" stroke="#d6e7ff" stroke-width="2" stroke-linecap="round"/><path d="M22 26h20" stroke="#d6e7ff" stroke-width="2" stroke-linecap="round"/></svg>
          <div><strong>Hi! मैं आपकी कैसे मदद कर सकता हूँ?</strong></div>
          <div style="font-size:13px;color:var(--muted)">नीचे दिए शॉर्टकट से शुरू करें या अपना प्रश्न लिखें</div>
        </div>
      </div>

      <div style="padding:8px 0;position: fixed;
    bottom: 83px;
    width: 97%;
    left: 13px;">
        <div class="suggestions" id="suggestions">
          <div class="chip">Homework help</div>
          <div class="chip">Fee status</div>
          <div class="chip">Exam schedule</div>
          <div class="chip">Attendance</div>
        </div>
      </div>

      <div class="composer">
        <div class="composer-row">
          <div class="input">
            <textarea id="input" placeholder="Type your question..." rows="1"></textarea>
          </div>
          <div class="actions">
            <button class="icon-btn" id="attach" title="Attach">📎</button>
            <button class="icon-btn" id="mic" title="Voice">🎤</button>
            <button class="btn" id="send">Send</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    const messages = document.getElementById('messages');
    const input = document.getElementById('input');
    const send = document.getElementById('send');
    const suggestions = document.getElementById('suggestions');
    const emptyState = document.getElementById('emptyState');

    function addMessage(text, who='bot'){
      if(emptyState) emptyState.style.display='none';
      const el = document.createElement('div');
      el.className = 'bubble '+(who==='user'?'user':'bot');
      el.innerHTML = '<div>'+text+'</div>' + '<div class="meta">'+new Date().toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})+'</div>';
      messages.appendChild(el);
      messages.scrollTop = messages.scrollHeight;
    }

    function showTyping(){
      const typing = document.createElement('div');
      typing.className='bubble bot';
      typing.id='typing';
      typing.innerHTML = '<div class="typing"><span class="dot"></span><span class="dot"></span><span class="dot"></span></div>';
      messages.appendChild(typing);
      messages.scrollTop = messages.scrollHeight;
    }

    function removeTyping(){
      const t = document.getElementById('typing'); if(t) t.remove();
    }

    send.addEventListener('click', async ()=>{
      const text = input.value.trim(); if(!text) return;
      addMessage(text,'user');
      input.value='';
      showTyping();

      // Simulate bot response (replace this with real API call)
      await new Promise(r=>setTimeout(r,900));
      removeTyping();
      addMessage('यह एक नमूना उत्तर है। अगर आप इसे लाइव करना चाहते हैं तो बताइए — मैं OpenAI API integration का sample भी दे दूंगा।');
    });

    // Allow Enter to send (Shift+Enter for newline)
    input.addEventListener('keydown', (e)=>{
      if(e.key === 'Enter' && !e.shiftKey){ e.preventDefault(); send.click(); }
    });

    // Suggestion chips
    suggestions.addEventListener('click', (e)=>{
      const chip = e.target.closest('.chip'); if(!chip) return;
      input.value = chip.textContent; input.focus();
    });

    // Auto-resize textarea
    input.addEventListener('input', ()=>{
      input.style.height = 'auto';
      input.style.height = (input.scrollHeight) + 'px';
    });
  </script>
</body>
</html>
