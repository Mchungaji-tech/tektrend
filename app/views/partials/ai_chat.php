<?php
/**
 * Tek Trend AI Chatbot Component
 * Powered by Google Gemini API
 * Compatible with Dark Mode and Light Mode
 */
$chatEndpoint = eurl('/api/ai/chat');
?>
<!-- AI Chatbot Floating Trigger -->
<div id="aiChatWrapper" class="ai-chat-root">
  <button id="aiChatTrigger" class="ai-launcher" onclick="toggleAiChat()" aria-label="Open Tek Trend AI Assistant">
    <div class="ai-launcher-pulse"></div>
    <span class="ai-launcher-icon"><i class="fas fa-wand-magic-sparkles"></i></span>
    <span class="ai-launcher-text">Ask AI</span>
    <span class="ai-unread-dot"></span>
  </button>

  <!-- AI Chat Window -->
  <div id="aiChatWindow" class="ai-window" style="display: none;">
    <!-- Chat Header -->
    <div class="ai-header">
      <div class="ai-brand">
        <div class="ai-avatar">
          <i class="fas fa-brain"></i>
          <span class="ai-online-indicator"></span>
        </div>
        <div>
          <div class="ai-title">Nova · AI Architect</div>
          <div class="ai-subtitle"><i class="fas fa-bolt" style="color: #eab308; font-size: 0.68rem;"></i> Powered by Google Gemini</div>
        </div>
      </div>
      <div class="ai-controls">
        <button onclick="resetAiChat()" class="ai-ctrl-btn" title="Clear Conversation"><i class="fas fa-rotate-right"></i></button>
        <button onclick="toggleAiChat()" class="ai-ctrl-btn" title="Close"><i class="fas fa-xmark"></i></button>
      </div>
    </div>

    <!-- Message Thread Container -->
    <div id="aiMessages" class="ai-messages">
      <!-- Welcome Message -->
      <div class="ai-msg ai-msg-bot">
        <div class="ai-bubble">
          <p>Hello! I am <strong>Nova</strong>, your AI Solutions Architect at Tek Trend.</p>
          <p>I can help you review our <strong>9 live prototype templates</strong>, configure custom software builds, or book a 1-on-1 Zoom architecture consultation. How can I help you today?</p>
        </div>
        <span class="ai-timestamp"><?= date('H:i') ?></span>
      </div>

      <!-- Quick Chips -->
      <div class="ai-chips" id="aiQuickChips">
        <button onclick="sendQuickPrompt('What turnkey prototype templates do you offer and what are their prices?')" class="ai-chip">
          <i class="fas fa-tags"></i> Templates & Pricing
        </button>
        <button onclick="sendQuickPrompt('How do I book a 45-minute Zoom consultation?')" class="ai-chip">
          <i class="fas fa-video"></i> Book Zoom Call
        </button>
        <button onclick="sendQuickPrompt('How do I request a custom software architecture for my company?')" class="ai-chip">
          <i class="fas fa-hammer"></i> Custom System Build
        </button>
        <button onclick="sendQuickPrompt('What is your WhatsApp contact for instant questions?')" class="ai-chip">
          <i class="fab fa-whatsapp"></i> WhatsApp Us
        </button>
      </div>
    </div>

    <!-- Typing Indicator -->
    <div id="aiTypingIndicator" class="ai-typing" style="display: none;">
      <div class="ai-dots">
        <span></span><span></span><span></span>
      </div>
      <span style="font-size: 0.76rem; color: var(--ai-muted, #94a3b8);">Nova is thinking...</span>
    </div>

    <!-- Chat Input Form -->
    <form id="aiChatForm" class="ai-input-form" onsubmit="handleAiChatSubmit(event)">
      <div class="ai-input-wrapper">
        <input type="text" id="aiChatInput" class="ai-input-field" placeholder="Ask anything about our systems or templates..." autocomplete="off" required>
        <button type="submit" id="aiSendBtn" class="ai-send-btn" aria-label="Send message">
          <i class="fas fa-arrow-up"></i>
        </button>
      </div>
      <div class="ai-disclaimer">
        <span>AI responses are generated via Google Gemini REST API</span>
      </div>
    </form>
  </div>
</div>

<style>
  /* AI Chatbot Scoped Variables */
  :root, [data-theme="dark"] {
    --ai-bg-window: #0c1017;
    --ai-header-bg: #121824;
    --ai-border: rgba(255, 255, 255, 0.1);
    --ai-border-glow: rgba(214, 194, 157, 0.3);
    --ai-bubble-bot: #161f2e;
    --ai-bubble-user: linear-gradient(135deg, #d6c29d, #bba377);
    --ai-text-bot: #f8fafc;
    --ai-text-user: #0c0e12;
    --ai-muted: #94a3b8;
    --ai-input-bg: #161f2e;
    --ai-input-border: rgba(255, 255, 255, 0.12);
    --ai-accent: #d6c29d;
    --ai-shadow: 0 25px 60px -10px rgba(0, 0, 0, 0.85);
  }

  [data-theme="light"] {
    --ai-bg-window: #ffffff;
    --ai-header-bg: #f8fafc;
    --ai-border: #e2e8f0;
    --ai-border-glow: rgba(184, 134, 11, 0.35);
    --ai-bubble-bot: #f1f5f9;
    --ai-bubble-user: linear-gradient(135deg, #2563eb, #1d4ed8);
    --ai-text-bot: #0f172a;
    --ai-text-user: #ffffff;
    --ai-muted: #64748b;
    --ai-input-bg: #f8fafc;
    --ai-input-border: #cbd5e1;
    --ai-accent: #b8860b;
    --ai-shadow: 0 20px 50px -10px rgba(15, 23, 42, 0.18);
  }

  /* Chatbot Root */
  .ai-chat-root {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 99999;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  }

  /* Launcher Button */
  .ai-launcher {
    background: linear-gradient(135deg, #101726 0%, #1a2233 100%);
    border: 1.5px solid var(--ai-border-glow);
    border-radius: 60px;
    padding: 0.75rem 1.4rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    cursor: pointer;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4), 0 0 20px rgba(214, 194, 157, 0.18);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    color: #fff;
  }
  .ai-launcher:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 0 30px rgba(214, 194, 157, 0.3);
    border-color: #d6c29d;
  }
  .ai-launcher-icon {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d6c29d, #e2d2b4);
    color: #0c0e12;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
  }
  .ai-launcher-text {
    font-size: 0.95rem;
    font-weight: 700;
    color: #f8fafc;
    letter-spacing: -0.2px;
  }
  .ai-unread-dot {
    width: 9px;
    height: 9px;
    background: #22c55e;
    border-radius: 50%;
    border: 2px solid #101726;
  }

  /* Chat Window */
  .ai-window {
    position: absolute;
    bottom: calc(100% + 14px);
    right: 0;
    width: 390px;
    max-width: calc(100vw - 32px);
    height: 560px;
    max-height: calc(100vh - 120px);
    background: var(--ai-bg-window);
    border: 1px solid var(--ai-border);
    border-radius: 24px;
    box-shadow: var(--ai-shadow);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    animation: aiWindowOpen 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    backdrop-filter: blur(16px);
  }
  @keyframes aiWindowOpen {
    from { opacity: 0; transform: translateY(15px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
  }

  /* Window Header */
  .ai-header {
    background: var(--ai-header-bg);
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--ai-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .ai-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }
  .ai-avatar {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(214, 194, 157, 0.2), rgba(59, 130, 246, 0.2));
    border: 1px solid var(--ai-border-glow);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    color: var(--ai-accent);
    position: relative;
  }
  .ai-online-indicator {
    width: 9px;
    height: 9px;
    background: #22c55e;
    border-radius: 50%;
    position: absolute;
    bottom: -2px;
    right: -2px;
    border: 2px solid var(--ai-header-bg);
  }
  .ai-title {
    font-size: 0.96rem;
    font-weight: 700;
    color: var(--ai-text-bot);
    line-height: 1.2;
  }
  .ai-subtitle {
    font-size: 0.72rem;
    color: var(--ai-muted);
    margin-top: 2px;
  }
  .ai-controls {
    display: flex;
    gap: 0.4rem;
  }
  .ai-ctrl-btn {
    background: none;
    border: none;
    color: var(--ai-muted);
    font-size: 0.95rem;
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
  }
  .ai-ctrl-btn:hover {
    background: rgba(255, 255, 255, 0.08);
    color: var(--ai-text-bot);
  }

  /* Messages Container */
  .ai-messages {
    flex: 1;
    overflow-y: auto;
    padding: 1.1rem 1.1rem 0.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.85rem;
    scroll-behavior: smooth;
  }
  .ai-messages::-webkit-scrollbar { width: 5px; }
  .ai-messages::-webkit-scrollbar-thumb { background: rgba(150, 150, 150, 0.2); border-radius: 4px; }

  .ai-msg {
    display: flex;
    flex-direction: column;
    max-width: 86%;
    animation: aiMsgIn 0.25s ease-out;
  }
  @keyframes aiMsgIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
  }
  .ai-msg-bot { align-self: flex-start; }
  .ai-msg-user { align-self: flex-end; }

  .ai-bubble {
    padding: 0.85rem 1.05rem;
    border-radius: 18px;
    font-size: 0.88rem;
    line-height: 1.55;
    word-break: break-word;
  }
  .ai-msg-bot .ai-bubble {
    background: var(--ai-bubble-bot);
    color: var(--ai-text-bot);
    border: 1px solid var(--ai-border);
    border-bottom-left-radius: 4px;
  }
  .ai-msg-bot .ai-bubble p { margin-bottom: 0.5rem; }
  .ai-msg-bot .ai-bubble p:last-child { margin-bottom: 0; }
  .ai-msg-bot .ai-bubble ul { margin: 0.4rem 0 0.4rem 1.2rem; }
  .ai-msg-bot .ai-bubble a { color: var(--ai-accent); text-decoration: underline; font-weight: 600; }

  .ai-msg-user .ai-bubble {
    background: var(--ai-bubble-user);
    color: var(--ai-text-user);
    font-weight: 500;
    border-bottom-right-radius: 4px;
  }
  .ai-timestamp {
    font-size: 0.65rem;
    color: var(--ai-muted);
    margin-top: 3px;
    padding: 0 4px;
  }
  .ai-msg-user .ai-timestamp { align-self: flex-end; }

  /* Quick Suggestion Chips */
  .ai-chips {
    display: flex;
    flex-wrap: wrap;
    gap: 0.45rem;
    margin-top: 0.4rem;
  }
  .ai-chip {
    background: rgba(214, 194, 157, 0.08);
    border: 1px solid var(--ai-border-glow);
    color: var(--ai-accent);
    padding: 0.4rem 0.85rem;
    border-radius: 30px;
    font-size: 0.76rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
  }
  .ai-chip:hover {
    background: rgba(214, 194, 157, 0.2);
    transform: translateY(-1px);
    color: var(--ai-text-bot);
  }

  /* Typing Animation */
  .ai-typing {
    padding: 0.4rem 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.6rem;
  }
  .ai-dots {
    display: inline-flex;
    gap: 4px;
  }
  .ai-dots span {
    width: 6px;
    height: 6px;
    background: var(--ai-accent);
    border-radius: 50%;
    animation: aiBounce 1.2s infinite ease-in-out;
  }
  .ai-dots span:nth-child(2) { animation-delay: 0.2s; }
  .ai-dots span:nth-child(3) { animation-delay: 0.4s; }
  @keyframes aiBounce {
    0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
    40% { transform: scale(1.1); opacity: 1; }
  }

  /* Input Form */
  .ai-input-form {
    padding: 0.75rem 1rem 0.9rem;
    border-top: 1px solid var(--ai-border);
    background: var(--ai-header-bg);
  }
  .ai-input-wrapper {
    display: flex;
    align-items: center;
    background: var(--ai-input-bg);
    border: 1px solid var(--ai-input-border);
    border-radius: 30px;
    padding: 0.3rem 0.4rem 0.3rem 1rem;
    transition: border-color 0.25s;
  }
  .ai-input-wrapper:focus-within {
    border-color: var(--ai-accent);
    box-shadow: 0 0 0 2px var(--ai-border-glow);
  }
  .ai-input-field {
    flex: 1;
    background: none;
    border: none;
    outline: none;
    color: var(--ai-text-bot);
    font-size: 0.88rem;
    font-family: inherit;
  }
  .ai-send-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d6c29d, #bba377);
    color: #0c0e12;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: transform 0.2s;
  }
  .ai-send-btn:hover {
    transform: scale(1.08);
  }
  .ai-disclaimer {
    text-align: center;
    font-size: 0.65rem;
    color: var(--ai-muted);
    margin-top: 0.4rem;
    opacity: 0.8;
  }

  @media (max-width: 480px) {
    .ai-window {
      width: calc(100vw - 20px);
      height: 490px;
      right: -10px;
    }
  }
</style>

<script>
  let aiChatOpen = false;

  function toggleAiChat() {
    const win = document.getElementById('aiChatWindow');
    aiChatOpen = !aiChatOpen;
    win.style.display = aiChatOpen ? 'flex' : 'none';
    if (aiChatOpen) {
      setTimeout(() => {
        document.getElementById('aiChatInput').focus();
        scrollAiMessages();
      }, 100);
    }
  }

  function scrollAiMessages() {
    const container = document.getElementById('aiMessages');
    if (container) container.scrollTop = container.scrollHeight;
  }

  function resetAiChat() {
    const container = document.getElementById('aiMessages');
    container.innerHTML = `
      <div class="ai-msg ai-msg-bot">
        <div class="ai-bubble">
          <p>Conversation reset. How can I assist you with Tek Trend's engineering architectures or turnkey prototype templates?</p>
        </div>
        <span class="ai-timestamp">${getCurrentTime()}</span>
      </div>
      <div class="ai-chips">
        <button onclick="sendQuickPrompt('What turnkey prototype templates do you offer and what are their prices?')" class="ai-chip">
          <i class="fas fa-tags"></i> Templates & Pricing
        </button>
        <button onclick="sendQuickPrompt('How do I book a 45-minute Zoom consultation?')" class="ai-chip">
          <i class="fas fa-video"></i> Book Zoom Call
        </button>
      </div>
    `;
  }

  function getCurrentTime() {
    const now = new Date();
    return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
  }

  function sendQuickPrompt(promptText) {
    document.getElementById('aiChatInput').value = promptText;
    document.getElementById('aiChatForm').dispatchEvent(new Event('submit'));
  }

  function handleAiChatSubmit(e) {
    e.preventDefault();
    const input = document.getElementById('aiChatInput');
    const msg = input.value.trim();
    if (!msg) return;

    // Append User Bubble
    appendAiMessage('user', msg);
    input.value = '';

    // Show Typing Indicator
    document.getElementById('aiTypingIndicator').style.display = 'flex';
    scrollAiMessages();

    // Call Backend AI Endpoint
    fetch('<?= $chatEndpoint ?>', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: msg })
    })
    .then(res => res.json())
    .then(data => {
      document.getElementById('aiTypingIndicator').style.display = 'none';
      const replyText = data.reply || "Thank you for reaching out! Our engineering team will assist you.";
      appendAiMessage('bot', replyText);
    })
    .catch(err => {
      document.getElementById('aiTypingIndicator').style.display = 'none';
      appendAiMessage('bot', "Tek Trend's software engineering team is available! You can book a free Zoom consultation or contact our principal solutions architect on WhatsApp at +254 707 246 273.");
    });
  }

  function appendAiMessage(sender, text) {
    const container = document.getElementById('aiMessages');
    const msgDiv = document.createElement('div');
    msgDiv.className = `ai-msg ai-msg-${sender}`;

    const formattedText = formatAiMarkdown(text);
    msgDiv.innerHTML = `
      <div class="ai-bubble">${formattedText}</div>
      <span class="ai-timestamp">${getCurrentTime()}</span>
    `;

    container.appendChild(msgDiv);
    scrollAiMessages();
  }

  function formatAiMarkdown(txt) {
    if (!txt) return '';
    // Escape HTML first
    let escaped = txt
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');

    // Basic markdown: bolding, links, bullet points
    escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
    escaped = escaped.replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>');
    escaped = escaped.replace(/\n• /g, '<br>&bull; ');
    escaped = escaped.replace(/\n/g, '<br>');
    return escaped;
  }
</script>
