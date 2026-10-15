<?php
$pageTitle = 'Meeting Room: ' . sanitize($meeting['title'] ?? 'Conference Room');
// Media is carried by LiveKit; the browser fetches a short-lived, server-signed token on join.
$jsRoom = json_encode($roomName ?? '', JSON_UNESCAPED_SLASHES);
$jsName = json_encode($displayName ?? 'Guest');
$jsTokenUrl = json_encode(eurl('/meetings/' . ($meeting['id'] ?? 0) . '/token'), JSON_UNESCAPED_SLASHES);
$lkConfigured = !empty($lkConfigured);
$hostName = trim(($meeting['host_first'] ?? 'Admin') . ' ' . ($meeting['host_last'] ?? '')) ?: 'Admin';
$isLive = ($meeting['status'] ?? 'scheduled') === 'in_progress';
?>
<div class="card" style="max-width: 700px; margin: 0 auto; text-align: center; padding: 3rem 2rem;" id="meetingLanding">
    <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-video"></i>
    </div>

    <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);"><?= sanitize($meeting['title'] ?? 'Tek Trend Video Meeting') ?></h2>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
        Hosted by: <strong style="color: var(--text-main);"><?= sanitize($hostName) ?></strong> · <?= $isLive ? '<span style="color: var(--success); font-weight:700;">● Live now</span>' : 'Starts: ' . formatDateTime($meeting['scheduled_at'] ?? date('Y-m-d H:i:s')) ?>
    </p>

    <div style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1rem; text-align: left;">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Meeting Agenda:</div>
        <p style="font-size: 0.9rem; color: var(--text-main);"><?= nl2br(sanitize($meeting['description'] ?? 'General consulting and architecture briefing.')) ?></p>
    </div>

    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">
        Room key: <span style="font-family: monospace;"><?= sanitize($roomName ?? '') ?></span> · Secured by the embedded LiveKit server
    </div>

    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <button type="button" id="joinMeetingBtn" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem;" <?= $lkConfigured ? '' : 'disabled' ?>>
            <i class="fas fa-video"></i> Join Video Meeting
        </button>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline" style="padding: 0.85rem 1.5rem;">Back to Meetings</a>
    </div>
    <?php if (!$lkConfigured): ?>
        <p style="margin-top: 1rem; font-size: 0.8rem; color: var(--danger, #ef4444);">The live meeting server isn't configured yet. Set <code>LIVEKIT_URL</code> and API keys in <code>.env</code>.</p>
    <?php endif; ?>
    <p id="landingError" style="display:none; margin-top: 1.25rem; color: var(--danger, #ef4444); font-size: 0.85rem;"></p>
</div>

<!-- The meeting runs here, embedded inside the app (no external redirect) -->
<div id="meetingWrap" style="display: none; margin: 0 auto; max-width: 1100px;">
    <div id="meetingStatus" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">
        <i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…
    </div>
    <div id="lkGrid" style="width: 100%; min-height: 60vh; max-height: 76vh; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.6rem; background: #0b1220; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 0.6rem; overflow: hidden;"></div>
    <div style="display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem;">
        <button type="button" class="btn btn-outline lk-ctl" id="micBtn" style="padding: 0.55rem 1rem;"><i class="fas fa-microphone"></i> Mute</button>
        <button type="button" class="btn btn-outline lk-ctl" id="camBtn" style="padding: 0.55rem 1rem;"><i class="fas fa-video"></i> Stop camera</button>
        <button type="button" class="btn btn-outline lk-ctl" id="screenBtn" style="padding: 0.55rem 1rem;"><i class="fas fa-display"></i> Share screen</button>
        <button type="button" class="btn btn-danger lk-ctl" id="leaveBtn" style="padding: 0.55rem 1rem;"><i class="fas fa-phone-slash"></i> Leave</button>
        <button type="button" id="retryBtn" class="btn btn-secondary" style="display:none; padding: 0.55rem 1rem;"><i class="fas fa-redo"></i> Retry Connection</button>
    </div>
    <style>
        .lk-tile { position: relative; background: #111a2b; border-radius: 10px; overflow: hidden; min-height: 200px; display: flex; align-items: center; justify-content: center; }
        .lk-tile video { width: 100%; height: 100%; object-fit: cover; background: #000; }
        .lk-tile .lk-name { position: absolute; left: 8px; bottom: 8px; font-size: 0.72rem; font-weight: 600; background: rgba(0,0,0,0.55); padding: 0.2rem 0.5rem; border-radius: 6px; color: #e2e8f0; }
        .lk-tile .lk-mic { position: absolute; right: 8px; bottom: 8px; font-size: 0.7rem; color: #94a3b8; }
        .lk-placeholder { color: #334155; font-size: 2.4rem; }
        .lk-ctl.off { color: #ef4444; border-color: rgba(239,68,68,0.5); }
    </style>
</div>

<script src="https://cdn.jsdelivr.net/npm/livekit-client@2/dist/livekit-client.umd.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
(function () {
    const ROOM = <?= $jsRoom ?>;
    const displayName = <?= $jsName ?>;
    const TOKEN_URL = <?= $jsTokenUrl ?>;

    const landing = document.getElementById('meetingLanding');
    const wrap = document.getElementById('meetingWrap');
    const btn = document.getElementById('joinMeetingBtn');
    const retryBtn = document.getElementById('retryBtn');
    const status = document.getElementById('meetingStatus');
    const grid = document.getElementById('lkGrid');
    const landingError = document.getElementById('landingError');
    const micBtn = document.getElementById('micBtn');
    const camBtn = document.getElementById('camBtn');
    const screenBtn = document.getElementById('screenBtn');
    const leaveBtn = document.getElementById('leaveBtn');

    const tiles = new Map();
    let room = null;

    function setStatus(html, color) { status.innerHTML = html; status.style.color = color || 'var(--text-muted)'; }

    function tileFor(participant) {
        if (tiles.has(participant.sid)) return tiles.get(participant.sid);
        const el = document.createElement('div');
        el.className = 'lk-tile';
        el.innerHTML = '<div class="lk-placeholder"><i class="fas fa-user"></i></div><span class="lk-name"></span><span class="lk-mic"></span>';
        grid.appendChild(el);
        const rec = { el: el, nameEl: el.querySelector('.lk-name'), micEl: el.querySelector('.lk-mic'), videos: [] };
        tiles.set(participant.sid, rec);
        rec.nameEl.textContent = (participant === room.localParticipant) ? (displayName || 'You') : (participant.name || participant.identity || 'Guest');
        return rec;
    }

    function addTrack(track, participant) {
        const rec = tileFor(participant);
        if (track.kind === 'video') {
            const ph = rec.el.querySelector('.lk-placeholder'); if (ph) ph.remove();
            const video = document.createElement('video');
            video.autoplay = true; video.playsInline = true; video.muted = participant === room.localParticipant;
            track.attach(video); rec.el.appendChild(video);
            rec.videos.push({ track: track, el: video });
        } else if (track.kind === 'audio') {
            const audio = document.createElement('audio'); audio.autoplay = true;
            track.attach(audio); rec.el.appendChild(audio);
            rec.videos.push({ track: track, el: audio });
            rec.micEl.innerHTML = '<i class="fas fa-microphone"></i>';
        }
    }

    function removeTrack(track) {
        tiles.forEach((rec) => {
            const idx = rec.videos.findIndex(v => v.track === track);
            if (idx > -1) { try { track.detach(rec.videos[idx].el); } catch (e) {} rec.videos[idx].el.remove(); rec.videos.splice(idx, 1); }
        });
    }

    function removeTile(sid) { const rec = tiles.get(sid); if (rec) { rec.el.remove(); tiles.delete(sid); } }

    function reset() {
        tiles.clear(); grid.innerHTML = '';
        wrap.style.display = 'none'; landing.style.display = 'block';
        retryBtn.style.display = 'none'; btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-video"></i> Join Video Meeting';
    }

    function fail(msg) {
        setStatus('<i class="fas fa-triangle-exclamation"></i> ' + msg, 'var(--danger, #ef4444)');
        retryBtn.style.display = 'inline-flex';
        landingError.style.display = 'block'; landingError.textContent = msg;
    }

    async function fetchToken() {
        const res = await fetch(TOKEN_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, credentials: 'same-origin' });
        const data = await res.json().catch(() => ({}));
        if (!res.ok || !data.token) throw new Error(data.error || ('Could not get a meeting token (HTTP ' + res.status + ').'));
        return data;
    }

    async function startMeeting() {
        if (!window.LivekitClient) { fail('Live meeting client failed to load. Check your connection and retry.'); return; }
        retryBtn.style.display = 'none'; landing.style.display = 'none'; wrap.style.display = 'block';
        setStatus('<i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…');

        const { Room, RoomEvent } = window.LivekitClient;
        room = new Room({ adaptiveStream: true, dynacast: true });
        room.on(RoomEvent.TrackSubscribed, (track, pub, participant) => addTrack(track, participant));
        room.on(RoomEvent.TrackUnsubscribed, (track) => removeTrack(track));
        room.on(RoomEvent.LocalTrackPublished, (pub) => { if (pub.track) addTrack(pub.track, room.localParticipant); });
        room.on(RoomEvent.ParticipantDisconnected, (p) => removeTile(p.sid));
        room.on(RoomEvent.Reconnected, () => setStatus('<i class="fas fa-circle" style="color: var(--success);"></i> Reconnected'));
        room.on(RoomEvent.Disconnected, () => fail('Disconnected from the meeting server.'));

        try {
            const cfg = await fetchToken();
            await room.connect(cfg.url, cfg.token);
            try { await room.localParticipant.setMicrophoneEnabled(true); await room.localParticipant.setCameraEnabled(true); }
            catch (mediaErr) { setStatus('<i class="fas fa-triangle-exclamation" style="color:#fbbf24;"></i> Joined without camera/mic — allow device access and retry.', '#fbbf24'); }
            setStatus('<i class="fas fa-circle" style="color: var(--success);"></i> Connected · ' + cfg.room);
            retryBtn.style.display = 'none'; updateControls();
        } catch (err) { fail(err.message || 'Could not connect to the meeting server.'); reset(); }
    }

    function updateControls() {
        if (!room) return;
        const p = room.localParticipant;
        const micOn = p.isMicrophoneEnabled(); const camOn = p.isCameraEnabled();
        micBtn.classList.toggle('off', !micOn); micBtn.innerHTML = micOn ? '<i class="fas fa-microphone"></i> Mute' : '<i class="fas fa-microphone-slash"></i> Unmute';
        camBtn.classList.toggle('off', !camOn); camBtn.innerHTML = camOn ? '<i class="fas fa-video"></i> Stop camera' : '<i class="fas fa-video-slash"></i> Start camera';
    }

    micBtn.addEventListener('click', async () => { if (room) { await room.localParticipant.setMicrophoneEnabled(!room.localParticipant.isMicrophoneEnabled()); updateControls(); } });
    camBtn.addEventListener('click', async () => { if (room) { await room.localParticipant.setCameraEnabled(!room.localParticipant.isCameraEnabled()); updateControls(); } });
    screenBtn.addEventListener('click', async () => {
        if (!room) return;
        const pub = room.localParticipant.getTrackPublication('screen');
        if (pub && pub.track) { await room.localParticipant.setScreenShareEnabled(false); screenBtn.classList.remove('off'); }
        else { try { await room.localParticipant.setScreenShareEnabled(true); screenBtn.classList.add('off'); } catch (e) {} }
    });
    leaveBtn.addEventListener('click', async () => { if (room) { try { await room.disconnect(); } catch (e) {} } reset(); });

    btn.addEventListener('click', function () { btn.disabled = true; btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting…'; landingError.style.display = 'none'; startMeeting(); });
    retryBtn.addEventListener('click', function () { setStatus('<i class="fas fa-spinner fa-spin"></i> Reconnecting…'); startMeeting(); });
})();
</script>
