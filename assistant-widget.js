/*
 * WealthWise Assistant — self-contained chat widget.
 * Works on the static marketing pages and inside the logged-in app.
 * Include with:  <script src="/assistant-widget.js" defer></script>
 * Opens from: a floating bubble, any element with [data-wealthwise-assistant],
 * or any nav link whose text contains "Assistant".
 */
(function () {
  "use strict";
  if (window.__wwAssistantLoaded) return;
  window.__wwAssistantLoaded = true;

  var API = { msg: "/assistant/message", esc: "/assistant/escalate", poll: "/assistant/poll" };
  var SITE = "WealthWise";

  // ---- identity / state ----
  var guestId = localStorage.getItem("ww_guest_id");
  if (!guestId) { guestId = "g_" + Math.random().toString(36).slice(2) + Date.now().toString(36); localStorage.setItem("ww_guest_id", guestId); }
  var convId = localStorage.getItem("ww_conv_id");
  var lastId = parseInt(localStorage.getItem("ww_last_id") || "0", 10) || 0;
  var handedOff = false, pollTimer = null, greeted = false, busy = false;

  // ---- styles ----
  var css = "" +
  ".wwa-bubble{position:fixed;right:20px;bottom:20px;z-index:2147483000;width:60px;height:60px;border-radius:50%;background:#0F3A6E;color:#fff;border:none;cursor:pointer;box-shadow:0 8px 24px rgba(15,58,110,.35);display:flex;align-items:center;justify-content:center;transition:transform .15s,background .15s}" +
  ".wwa-bubble:hover{background:#2E5C8A;transform:translateY(-2px)}" +
  ".wwa-bubble svg{width:28px;height:28px}" +
  ".wwa-panel{position:fixed;right:20px;bottom:20px;z-index:2147483001;width:390px;max-width:calc(100vw - 32px);height:620px;max-height:calc(100vh - 40px);background:#eef1f5;border-radius:16px;box-shadow:0 24px 60px rgba(0,0,0,.3);display:none;flex-direction:column;overflow:hidden;font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Helvetica,Arial,sans-serif}" +
  ".wwa-panel.open{display:flex}" +
  ".wwa-head{background:#0F3A6E;color:#fff;padding:14px 16px;display:flex;align-items:center;gap:10px}" +
  ".wwa-head img{height:26px;width:auto}" +
  ".wwa-head .wwa-title{font-weight:700;font-size:15px;letter-spacing:.3px}" +
  ".wwa-head .wwa-sub{font-size:11px;color:#bcd2ec}" +
  ".wwa-x{margin-left:auto;background:none;border:none;color:#fff;cursor:pointer;padding:4px;line-height:0;border-radius:6px}" +
  ".wwa-x:hover{background:rgba(255,255,255,.15)}" +
  ".wwa-x svg{width:20px;height:20px}" +
  ".wwa-body{flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:12px}" +
  ".wwa-card{background:#fff;border-radius:12px;padding:14px 16px;font-size:14px;line-height:1.5;color:#1f2937;box-shadow:0 1px 2px rgba(0,0,0,.04)}" +
  ".wwa-card h4{font-size:16px;font-weight:700;color:#0F3A6E;margin:0 0 6px}" +
  ".wwa-pills{display:flex;flex-direction:column;gap:8px;margin-top:4px}" +
  ".wwa-pill{background:#eef3f9;border:1px solid #dbe6f3;border-radius:18px;padding:9px 14px;font-size:13px;color:#1a3a7f;cursor:pointer;text-align:left;font-family:inherit;transition:background .12s}" +
  ".wwa-pill:hover{background:#e0eaf6}" +
  ".wwa-msg{max-width:85%;padding:10px 13px;border-radius:14px;font-size:14px;line-height:1.5;white-space:pre-wrap;word-wrap:break-word}" +
  ".wwa-msg.user{align-self:flex-end;background:#2E5C8A;color:#fff;border-bottom-right-radius:4px}" +
  ".wwa-msg.bot{align-self:flex-start;background:#fff;color:#1f2937;border-bottom-left-radius:4px;box-shadow:0 1px 2px rgba(0,0,0,.05)}" +
  ".wwa-msg.system{align-self:center;background:#dce7f4;color:#33506f;font-size:12.5px;border-radius:10px;text-align:center}" +
  ".wwa-msg.admin{align-self:flex-start;background:#0F3A6E;color:#fff;border-bottom-left-radius:4px}" +
  ".wwa-who{font-size:10.5px;color:#7b8aa0;margin:0 4px 2px}" +
  ".wwa-typing{align-self:flex-start;color:#7b8aa0;font-size:13px;font-style:italic}" +
  ".wwa-foot{border-top:1px solid #dfe5ec;background:#fff;padding:10px}" +
  ".wwa-inrow{display:flex;align-items:center;gap:8px;border:2px solid #0F3A6E;border-radius:26px;padding:4px 6px 4px 14px}" +
  ".wwa-inrow input{flex:1;border:none;outline:none;font-size:14px;font-family:inherit;background:none;color:#1a1a1a}" +
  ".wwa-send{background:#0F3A6E;border:none;color:#fff;width:36px;height:36px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0}" +
  ".wwa-send:hover{background:#2E5C8A}" +
  ".wwa-send svg{width:18px;height:18px}" +
  ".wwa-handoff{display:flex;flex-direction:column;gap:8px;margin-top:8px}" +
  ".wwa-handoff input{border:1px solid #cbd5e1;border-radius:8px;padding:9px 11px;font-size:13px;font-family:inherit;outline:none}" +
  ".wwa-handoff input:focus{border-color:#2E5C8A}" +
  ".wwa-btn{background:#2E5C8A;color:#fff;border:none;border-radius:8px;padding:10px;font-size:13px;font-weight:600;cursor:pointer;font-family:inherit}" +
  ".wwa-btn:hover{background:#1a3a7f}" +
  ".wwa-talk{background:none;border:1px solid #2E5C8A;color:#2E5C8A;border-radius:18px;padding:7px 14px;font-size:12.5px;font-weight:600;cursor:pointer;align-self:flex-start;font-family:inherit;margin-top:2px}" +
  ".wwa-talk:hover{background:#eef3f9}" +
  "@media(max-width:480px){.wwa-panel{right:0;bottom:0;width:100vw;height:100vh;max-height:100vh;border-radius:0}.wwa-bubble{right:16px;bottom:16px}}";

  var style = document.createElement("style");
  style.textContent = css;
  document.head.appendChild(style);

  // ---- DOM ----
  var bubble = el("button", "wwa-bubble", '<svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>');
  bubble.setAttribute("aria-label", "Open " + SITE + " Assistant");

  var panel = el("div", "wwa-panel", "");
  panel.setAttribute("role", "dialog");
  panel.setAttribute("aria-label", SITE + " Assistant");
  panel.innerHTML =
    '<div class="wwa-head">' +
      '<img src="/wwec-white-logo.png" alt="" onerror="this.style.display=\'none\'">' +
      '<div><div class="wwa-title">' + SITE + ' Assistant</div><div class="wwa-sub">Around the clock, 7 days a week</div></div>' +
      '<button class="wwa-x" aria-label="Close"><svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></button>' +
    '</div>' +
    '<div class="wwa-body" id="wwaBody"></div>' +
    '<div class="wwa-foot">' +
      '<div class="wwa-inrow">' +
        '<input id="wwaInput" type="text" placeholder="Type your question here..." autocomplete="off">' +
        '<button class="wwa-send" id="wwaSend" aria-label="Send"><svg fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></svg></button>' +
      '</div>' +
    '</div>';

  document.body.appendChild(bubble);
  document.body.appendChild(panel);

  var body = panel.querySelector("#wwaBody");
  var input = panel.querySelector("#wwaInput");

  // ---- helpers ----
  function el(tag, cls, html) { var e = document.createElement(tag); if (cls) e.className = cls; if (html != null) e.innerHTML = html; return e; }
  function scroll() { body.scrollTop = body.scrollHeight; }
  function esc(s) { var d = document.createElement("div"); d.textContent = s; return d.innerHTML; }

  function addMsg(sender, text, who) {
    if (who) { var w = el("div", "wwa-who", esc(who)); body.appendChild(w); }
    var m = el("div", "wwa-msg " + sender, esc(text));
    body.appendChild(m); scroll();
    return m;
  }

  function setLastId(id) { if (id > lastId) { lastId = id; localStorage.setItem("ww_last_id", String(lastId)); } }
  function setConv(id) { if (id) { convId = id; localStorage.setItem("ww_conv_id", String(id)); } }

  function greet() {
    if (greeted) return; greeted = true;
    var c = el("div", "wwa-card", "");
    c.innerHTML = "<h4>Welcome to the " + SITE + " Assistant!</h4>" +
      "<p style='margin:0 0 8px'>Ask a question about your account, deposits, withdrawals, verification, or trading and I'll do my best to help.</p>" +
      "<p style='margin:0'>Ready? Let's get started.</p>";
    body.appendChild(c);

    var topics = el("div", "wwa-card", "<div style='font-size:13px;color:#475569;margin-bottom:8px'>Or pick a popular topic:</div>");
    var pills = el("div", "wwa-pills", "");
    ["How do I make a deposit?", "How do I withdraw funds?", "How do I verify my account?", "I'd like to speak to a human"].forEach(function (q) {
      var p = el("button", "wwa-pill", esc(q));
      p.onclick = function () { if (q.indexOf("human") > -1) { showHandoff(); } else { send(q); } };
      pills.appendChild(p);
    });
    topics.appendChild(pills);
    body.appendChild(topics);
    scroll();
  }

  function addTalkButton() {
    var b = el("button", "wwa-talk", "Talk to a human →");
    b.onclick = function () { b.remove(); showHandoff(); };
    body.appendChild(b); scroll();
  }

  // ---- core actions ----
  function send(text) {
    text = (text || input.value || "").trim();
    if (!text || busy) return;
    input.value = "";
    addMsg("user", text);
    busy = true;
    var typing = el("div", "wwa-typing", "Assistant is typing…"); body.appendChild(typing); scroll();

    fetch(API.msg, {
      method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json" },
      credentials: "same-origin",
      body: JSON.stringify({ message: text, guest_id: guestId, conversation_id: convId })
    }).then(function (r) { return r.json(); }).then(function (d) {
      typing.remove(); busy = false;
      setConv(d.conversation_id);
      if (d.user_message_id) setLastId(d.user_message_id);
      if (d.handed_off) {
        if (!handedOff) { handedOff = true; startPolling(); }
        return;
      }
      if (d.reply) { addMsg("bot", d.reply); if (d.reply_id) setLastId(d.reply_id); }
      if (d.suggest_handoff) addTalkButton();
    }).catch(function () {
      typing.remove(); busy = false;
      addMsg("system", "Network error. Please try again, or tap below to reach a human.");
      addTalkButton();
    });
  }

  function showHandoff() {
    var card = el("div", "wwa-card", "<div style='font-weight:600;color:#0F3A6E;margin-bottom:4px'>Connect with a human agent</div>" +
      "<div style='font-size:13px;color:#475569'>Leave your details and our team will reply right here.</div>");
    var wrap = el("div", "wwa-handoff", "");
    var name = el("input"); name.placeholder = "Your name";
    var email = el("input"); email.placeholder = "Your email"; email.type = "email";
    var btn = el("button", "wwa-btn", "Request a human agent");
    wrap.appendChild(name); wrap.appendChild(email); wrap.appendChild(btn);
    card.appendChild(wrap); body.appendChild(card); scroll();

    btn.onclick = function () {
      var payload = { guest_id: guestId, conversation_id: convId };
      // logged-in users: name/email optional (server ignores). Guests: required.
      if (name.value.trim()) payload.name = name.value.trim();
      if (email.value.trim()) payload.email = email.value.trim();
      btn.disabled = true; btn.textContent = "Connecting…";
      // ensure a conversation exists first
      var go = convId ? Promise.resolve() : fetch(API.msg, {
        method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json" }, credentials: "same-origin",
        body: JSON.stringify({ message: "I'd like to speak to a human.", guest_id: guestId })
      }).then(function (r) { return r.json(); }).then(function (d) { setConv(d.conversation_id); if (d.reply) addMsg("bot", d.reply); });

      go.then(function () {
        payload.conversation_id = convId;
        return fetch(API.esc, {
          method: "POST", headers: { "Content-Type": "application/json", "Accept": "application/json" }, credentials: "same-origin",
          body: JSON.stringify(payload)
        });
      }).then(function (r) {
        if (!r.ok) return r.json().then(function (e) { throw e; });
        return r.json();
      }).then(function () {
        card.remove();
        addMsg("system", "You're connected. A member of our team will reply here shortly.");
        handedOff = true; startPolling();
      }).catch(function (e) {
        btn.disabled = false; btn.textContent = "Request a human agent";
        var msg = (e && e.errors) ? "Please enter a valid name and email." : "Couldn't connect right now. Please try again.";
        addMsg("system", msg);
      });
    };
  }

  function startPolling() {
    if (pollTimer) return;
    poll();
    pollTimer = setInterval(function () { if (panel.classList.contains("open")) poll(); }, 5000);
  }

  function poll() {
    if (!convId) return;
    fetch(API.poll + "?conversation_id=" + encodeURIComponent(convId) + "&guest_id=" + encodeURIComponent(guestId) + "&after_id=" + lastId, {
      headers: { "Accept": "application/json" }, credentials: "same-origin"
    }).then(function (r) { return r.json(); }).then(function (d) {
      (d.messages || []).forEach(function (m) {
        if (m.id <= lastId) return;
        if (m.sender === "user") { setLastId(m.id); return; } // already shown locally
        if (m.sender === "admin") addMsg("admin", m.text, "Support agent");
        else if (m.sender === "system") addMsg("system", m.text);
        else if (m.sender === "assistant") addMsg("bot", m.text);
        setLastId(m.id);
      });
      handedOff = d.handed_off;
    }).catch(function () {});
  }

  // ---- open/close ----
  function open() {
    panel.classList.add("open"); bubble.style.display = "none";
    greet();
    if (convId) startPolling();
    setTimeout(function () { input.focus(); }, 100);
  }
  function close() { panel.classList.remove("open"); bubble.style.display = "flex"; }

  bubble.onclick = open;
  panel.querySelector(".wwa-x").onclick = close;
  panel.querySelector("#wwaSend").onclick = function () { send(); };
  input.addEventListener("keydown", function (e) { if (e.key === "Enter") { e.preventDefault(); send(); } });

  // expose
  window.openAssistant = open;

  // Trigger detection via document-level CAPTURE delegation. This survives
  // SPA/Angular re-renders of the nav and beats the framework's own click
  // handlers (which is why direct binding on load wasn't reliable).
  function isTrigger(node) {
    var el = node;
    while (el && el !== document) {
      if (el.nodeType === 1) {
        if (el.hasAttribute("data-wealthwise-assistant")) return true;
        if (el.getAttribute("data-utility") === "WealthWiseAssistant") return true;
        if (el.tagName === "A" || el.tagName === "BUTTON") {
          var t = (el.textContent || "").trim().toLowerCase();
          if (t === "wealthwise assistant" || t === "fidelity assistant" || t === "assistant") return true;
        }
      }
      el = el.parentNode;
    }
    return false;
  }
  document.addEventListener("click", function (e) {
    if (isTrigger(e.target)) { e.preventDefault(); e.stopPropagation(); open(); }
  }, true);
})();
