<?php
$pageTitle = 'Chat: ' . sanitize($room['name']);
$roomId = (int)$room['id'];
$lastId = 0;
if (!empty($messages)) { $lastId = (int)end($messages)['id']; }
$selfId = (int)($currentUserId ?? 0);
?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-hashtag" style="color: var(--primary); margin-right: 0.35rem;"></i> <?= sanitize($room['name']) ?>
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;"><span id="connStatus" style="color: var(--success);">● Live</span> · <?= count($members ?? []) ?> members</p>
        </div>
        <a href="<?= eurl('/chat') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> All Channels
        </a>
    </div>
</div>

<div class="card" style="height: 540px; display: flex; flex-direction: column;">
    <div id="messagesContainer" data-last-id="<?= $lastId ?>" style="flex: 1; overflow-y: auto; padding: 1.25rem; background: var(--bg-card-subtle); border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; flex-direction: column; gap: 1rem;">
        <?php if (empty($messages)): ?>
            <p id="emptyHint" style="color: var(--text-muted); text-align: center; margin: auto;">No messages in this channel yet. Start the conversation!</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <div class="chat-msg" data-id="<?= (int)$msg['id'] ?>">
                    <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.25rem;">
                        <div class="chat-avatar"><?= strtoupper(substr($msg['first_name'], 0, 1)) ?></div>
                        <span style="font-weight: 700; color: var(--text-main); font-size: 0.88rem;"><?= sanitize($msg['first_name'] . ' ' . $msg['last_name']) ?></span>
                        <span style="font-size: 0.72rem; color: var(--text-muted); margin-left: auto;"><?= formatDateTime($msg['created_at']) ?></span>
                    </div>
                    <div class="chat-bubble"><?= nl2br(sanitize($msg['message'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Typing indicator (appears when someone else is composing) -->
    <div id="typingIndicator" style="min-height: 22px; padding: 0.35rem 1.25rem 0; font-size: 0.8rem; color: var(--text-muted); display: none; align-items: center; gap: 0.5rem;">
        <span id="typingNames"></span>
        <span class="typing-dots"><span></span><span></span><span></span></span>
    </div>

    <form id="chatForm" method="POST" action="<?= eurl('/chat/room/' . $roomId . '/send') ?>" style="margin-top: 0.5rem;">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div style="display: flex; gap: 0.5rem;">
            <input type="text" id="chatInput" name="message" class="form-control" placeholder="Type your message into #<?= sanitize($room['name']) ?>..." autocomplete="off" required style="flex: 1;">
            <button type="submit" class="btn btn-primary" id="sendBtn"><i class="fas fa-paper-plane"></i> Send</button>
        </div>
    </form>
</div>

<style>
    .chat-avatar { width: 28px; height: 28px; border-radius: 50%; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 0.75rem; font-weight: 700; flex-shrink: 0; }
    .chat-bubble { margin-left: 2.25rem; padding: 0.65rem 0.95rem; background: var(--bg-card); border-radius: var(--radius-sm); color: var(--text-main); font-size: 0.9rem; border: 1px solid var(--border); width: fit-content; max-width: 80%; overflow-wrap: anywhere; }
    .chat-msg.is-self .chat-bubble { background: var(--primary-light); border-color: var(--primary); margin-left: auto; }
    .chat-msg { animation: chatIn 0.22s ease-out; }
    @keyframes chatIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }
    .typing-dots { display: inline-flex; gap: 3px; }
    .typing-dots span { width: 5px; height: 5px; border-radius: 50%; background: var(--text-muted); display: inline-block; animation: typingBounce 1.2s infinite ease-in-out; }
    .typing-dots span:nth-child(2) { animation-delay: 0.15s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.3s; }
    @keyframes typingBounce { 0%, 60%, 100% { transform: translateY(0); opacity: 0.4; } 30% { transform: translateY(-4px); opacity: 1; } }
</style>

<script>
(function () {
    const ROOM_ID = <?= $roomId ?>;
    const SELF_ID = <?= $selfId ?>;
    const CSRF = <?= json_encode(csrfToken()) ?>;
    const SEND_URL = <?= json_encode(eurl('/chat/room/' . $roomId . '/send')) ?>;
    const TYPING_URL = <?= json_encode(eurl('/chat/room/' . $roomId . '/typing')) ?>;
    const POLL_URL = <?= json_encode(eurl('/chat/messages/' . $roomId)) ?>;

    const container = document.getElementById('messagesContainer');
    const form = document.getElementById('chatForm');
    const input = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendBtn');
    const typingEl = document.getElementById('typingIndicator');
    const typingNames = document.getElementById('typingNames');
    const connStatus = document.getElementById('connStatus');

    let lastId = parseInt(container.dataset.lastId || '0', 10) || 0;
    let polling = false;

    function nearBottom() {
        return container.scrollHeight - container.scrollTop - container.clientHeight < 120;
    }
    function scrollToBottom(force) {
        if (force || nearBottom()) container.scrollTop = container.scrollHeight;
    }

    function buildMessage(msg) {
        const wrap = document.createElement('div');
        wrap.className = 'chat-msg' + (msg.is_self ? ' is-self' : '');
        wrap.dataset.id = msg.id;

        const head = document.createElement('div');
        head.style.cssText = 'display:flex;align-items:center;gap:0.6rem;margin-bottom:0.25rem;';

        const av = document.createElement('div');
        av.className = 'chat-avatar';
        av.textContent = msg.initial;

        const name = document.createElement('span');
        name.style.cssText = 'font-weight:700;color:var(--text-main);font-size:0.88rem;';
        name.textContent = msg.name;

        const time = document.createElement('span');
        time.style.cssText = 'font-size:0.72rem;color:var(--text-muted);margin-left:auto;';
        time.textContent = msg.time;

        head.append(av, name, time);

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble';
        // Safe text insertion (supports newlines without HTML injection)
        String(msg.message).split('\n').forEach((line, i) => {
            if (i > 0) bubble.appendChild(document.createElement('br'));
            bubble.appendChild(document.createTextNode(line));
        });

        wrap.append(head, bubble);
        return wrap;
    }

    function appendMessages(list) {
        const emptyHint = document.getElementById('emptyHint');
        if (emptyHint) emptyHint.remove();
        const shouldStick = nearBottom();
        let added = false;
        list.forEach((msg) => {
            if (!msg || typeof msg.id !== 'number') return;
            if (msg.id <= lastId && container.querySelector('.chat-msg[data-id="' + msg.id + '"]')) return;
            container.appendChild(buildMessage(msg));
            if (msg.id > lastId) lastId = msg.id;
            added = true;
        });
        if (added) scrollToBottom(shouldStick);
    }

    function setTypingIndicator(list) {
        if (!list || !list.length) {
            typingEl.style.display = 'none';
            typingNames.textContent = '';
            return;
        }
        const names = list.map((t) => t.name).slice(0, 3).join(', ');
        typingNames.textContent = names + (list.length > 3 ? ' +' + (list.length - 3) + ' more' : '') + ' typing';
        typingEl.style.display = 'flex';
    }

    function poll() {
        if (polling) return;
        polling = true;
        fetch(POLL_URL + '?after=' + lastId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then((r) => r.ok ? r.json() : Promise.reject(r.status))
        .then((data) => {
            connStatus.style.color = 'var(--success)';
            connStatus.textContent = '● Live';
            if (data.messages && data.messages.length) appendMessages(data.messages);
            setTypingIndicator(data.typing);
            if (typeof data.max_id === 'number' && data.max_id > lastId) lastId = data.max_id;
        })
        .catch(() => {
            connStatus.style.color = 'var(--text-muted)';
            connStatus.textContent = '● Reconnecting…';
        })
        .finally(() => { polling = false; });
    }

    function pingTyping(isTyping) {
        const body = new URLSearchParams();
        body.set('csrf_token', CSRF);
        body.set('typing', isTyping ? '1' : '0');
        fetch(TYPING_URL, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            credentials: 'same-origin',
            body: body.toString()
        }).catch(() => {});
    }

    // ---- Send a message via AJAX (no page reload) ----
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) { input.focus(); return; }

        sendBtn.disabled = true;
        const body = new URLSearchParams();
        body.set('csrf_token', CSRF);
        body.set('message', text);
        body.set('type', 'text');

        fetch(SEND_URL, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/x-www-form-urlencoded' },
            credentials: 'same-origin',
            body: body.toString()
        })
        .then((r) => r.json().then((d) => ({ ok: r.ok, d })))
        .then(({ ok, d }) => {
            if (!ok || !d.message) { throw new Error(d && d.error ? d.error : 'Send failed'); }
            input.value = '';
            appendMessages([d.message]);
            if (d.message.id > lastId) lastId = d.message.id;
            scrollToBottom(true);
            pingTyping(false);
            poll(); // pull anyone else's messages immediately
        })
        .catch((err) => {
            alert('Could not send message: ' + err.message);
        })
        .finally(() => { sendBtn.disabled = false; input.focus(); });
    });

    // ---- Typing presence (throttled start/stop) ----
    let typingActive = false;
    let lastPing = 0;
    let stopTimer = null;
    input.addEventListener('input', () => {
        const now = Date.now();
        if (input.value.trim() === '') {
            if (typingActive) { typingActive = false; pingTyping(false); }
            return;
        }
        if (!typingActive || now - lastPing > 3000) {
            typingActive = true;
            lastPing = now;
            pingTyping(true);
        }
        clearTimeout(stopTimer);
        stopTimer = setTimeout(() => { typingActive = false; pingTyping(false); }, 2000);
    });

    scrollToBottom(true);
    setInterval(poll, 2000);
    // First incremental sync shortly after load to catch anything posted meanwhile.
    setTimeout(poll, 600);
})();
</script>
