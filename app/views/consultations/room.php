<?php
// Self-contained public meeting room (no staff layout). The room token is the access secret.
// Media is carried by LiveKit; the browser fetches a short-lived, server-signed token on join.
$c = $consultation;
$jsRoom = json_encode($roomName, JSON_UNESCAPED_SLASHES);
$jsName = json_encode($displayName ?? 'Guest');
$jsTokenUrl = json_encode(eurl('/consult/' . $token . '/token'), JSON_UNESCAPED_SLASHES);
$lkConfigured = !empty($lkConfigured);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($pageTitle ?? 'Meeting Room') ?> · Tek Trend</title>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0f172a; color: #e2e8f0; min-height: 100vh; }
        a { color: inherit; }
        .topbar { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; background: rgba(255,255,255,0.03); border-bottom: 1px solid rgba(255,255,255,0.08); position: sticky; top: 0; z-index: 20; }
        .brand { font-weight: 800; font-size: 1.05rem; letter-spacing: -0.02em; display: flex; align-items: center; gap: 0.5rem; }
        .brand i { color: #6366f1; }
        .live-pill { font-size: 0.75rem; font-weight: 700; color: #34d399; }
        .wrap { max-width: 1100px; margin: 0 auto; padding: 2rem 1.25rem; }
        .card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 2.5rem 1.5rem; text-align: center; max-width: 720px; margin: 0 auto; }
        .badge-icon { width: 70px; height: 70px; border-radius: 50%; background: rgba(99,102,241,0.15); color: #818cf8; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem; }
        h1 { font-size: 1.5rem; font-weight: 800; margin-bottom: 0.5rem; color: #f8fafc; }
        .meta { color: #94a3b8; font-size: 0.9rem; margin-bottom: 1.25rem; }
        .agenda { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); text-align: left; border-radius: 12px; padding: 1.1rem 1.25rem; margin-bottom: 1rem; }
        .agenda .lbl { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.35rem; }
        .agenda p { font-size: 0.9rem; color: #e2e8f0; line-height: 1.5; }
        .room-key { font-size: 0.8rem; color: #94a3b8; margin-bottom: 1.5rem; }
        .room-key code { font-family: ui-monospace, Menlo, monospace; color: #c7d2fe; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; border: none; cursor: pointer; font-family: inherit; font-weight: 700; border-radius: 12px; text-decoration: none; transition: 0.2s; }
        .btn-primary { background: linear-gradient(135deg,#6366f1,#4338ca); color: #fff; padding: 0.85rem 2rem; font-size: 1rem; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 8px 24px rgba(99,102,241,0.35); }
        .btn-outline { background: transparent; border: 1px solid rgba(255,255,255,0.15); color: #e2e8f0; padding: 0.85rem 1.5rem; }
        .btn-outline:hover { background: rgba(255,255,255,0.06); }
        .btn-danger { background: #ef4444; color: #fff; padding: 0.85rem 1.5rem; }
        .btn[disabled] { opacity: 0.6; cursor: default; }
        .actions { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        .note { margin-top: 1rem; font-size: 0.78rem; color: #64748b; }
        .err { display: none; margin-top: 1.1rem; color: #f87171; font-size: 0.85rem; }
        #meetingWrap { display: none; }
        #meetingStatus { font-size: 0.82rem; color: #94a3b8; margin-bottom: 0.6rem; }
        #lkGrid { width: 100%; min-height: 68vh; max-height: 78vh; display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 0.6rem; background: #060b16; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 0.6rem; overflow: hidden; }
        .lk-tile { position: relative; background: #111a2b; border-radius: 10px; overflow: hidden; min-height: 200px; display: flex; align-items: center; justify-content: center; }
        .lk-tile video { width: 100%; height: 100%; object-fit: cover; background: #000; }
        .lk-tile .lk-name { position: absolute; left: 8px; bottom: 8px; font-size: 0.72rem; font-weight: 600; background: rgba(0,0,0,0.55); padding: 0.2rem 0.5rem; border-radius: 6px; color: #e2e8f0; }
        .lk-tile .lk-mic { position: absolute; right: 8px; bottom: 8px; font-size: 0.7rem; color: #94a3b8; }
        .lk-placeholder { color: #334155; font-size: 2.4rem; }
        #lkControls { display: flex; gap: 0.6rem; justify-content: center; flex-wrap: wrap; margin-top: 1rem; }
        .lk-ctl { display: inline-flex; align-items: center; gap: 0.4rem; border: 1px solid rgba(255,255,255,0.15); background: rgba(255,255,255,0.04); color: #e2e8f0; border-radius: 10px; padding: 0.6rem 1rem; font-weight: 600; cursor: pointer; }
        .lk-ctl:hover { background: rgba(255,255,255,0.08); }
        .lk-ctl.off { background: rgba(239,68,68,0.18); border-color: rgba(239,68,68,0.4); color: #fca5a5; }
        .lk-ctl.leave { background: #ef4444; border-color: #ef4444; color: #fff; }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand"><i class="fas fa-video"></i> Tek Trend</div>
        <div class="live-pill"><i class="fas fa-circle" style="font-size:0.6rem;"></i> <?= ($c['status'] ?? '') === 'in_progress' ? 'Live now' : 'Scheduled' ?></div>
    </div>

    <div class="wrap">
        <div class="card" id="meetingLanding">
            <div class="badge-icon"><i class="fas fa-video"></i></div>
            <h1><?= sanitize($c['service_type'] ?? 'Video Consultation') ?></h1>
            <p class="meta">
                Booking ref: <strong style="color:#e2e8f0;"><?= sanitize($c['meeting_id'] ?? '') ?></strong> ·
                <?= !empty($c['preferred_date']) ? formatDate($c['preferred_date']) : '' ?> <?= sanitize($c['preferred_time'] ?? '') ?>
            </p>

            <div class="agenda">
                <div class="lbl">Agenda / Notes</div>
                <p><?= nl2br(sanitize($c['notes'] ?? 'Live video consultation with the Tek Trend team.')) ?></p>
            </div>

            <div class="room-key">Joining as <strong style="color:#e2e8f0;"><?= sanitize($displayName ?? 'Guest') ?></strong> · Room <code><?= sanitize($roomName) ?></code></div>

            <div class="actions">
                <button type="button" id="joinMeetingBtn" class="btn btn-primary" <?= $lkConfigured ? '' : 'disabled' ?>><i class="fas fa-video"></i> Join Video Meeting</button>
                <a href="<?= eurl('/') ?>" class="btn btn-outline"><i class="fas fa-home"></i> Home</a>
            </div>
            <?php if (!$lkConfigured): ?>
                <p class="note" style="color:#f87171;">The live meeting server isn't configured yet. An administrator must set <code>LIVEKIT_URL</code> and API keys.</p>
            <?php else: ?>
                <p class="note">Runs entirely inside Tek Trend on the secure LiveKit server — no external links, no app install.</p>
            <?php endif; ?>
            <p class="err" id="landingError"></p>
        </div>

        <div id="meetingWrap">
            <div id="meetingStatus"><i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…</div>
            <div id="lkGrid"></div>
            <div id="lkControls">
                <button type="button" class="lk-ctl" id="micBtn"><i class="fas fa-microphone"></i> Mute</button>
                <button type="button" class="lk-ctl" id="camBtn"><i class="fas fa-video"></i> Stop camera</button>
                <button type="button" class="lk-ctl" id="screenBtn"><i class="fas fa-display"></i> Share screen</button>
                <button type="button" class="lk-ctl leave" id="leaveBtn"><i class="fas fa-phone-slash"></i> Leave</button>
            </div>
            <div style="text-align:center; margin-top:0.8rem;">
                <button type="button" id="retryBtn" class="btn btn-outline" style="display:none;"><i class="fas fa-redo"></i> Retry Connection</button>
            </div>
        </div>
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

    const tiles = new Map(); // participantSid -> {el, nameEl}
    let room = null;

    function setStatus(html, color) { status.innerHTML = html; status.style.color = color || '#94a3b8'; }

    function tileFor(participant, isLocal) {
        if (tiles.has(participant.sid)) return tiles.get(participant.sid);
        const el = document.createElement('div');
        el.className = 'lk-tile';
        el.innerHTML = '<div class="lk-placeholder"><i class="fas fa-user"></i></div>'
            + '<span class="lk-name"></span><span class="lk-mic"></span>';
        grid.appendChild(el);
        const rec = { el: el, nameEl: el.querySelector('.lk-name'), micEl: el.querySelector('.lk-mic'), videos: [] };
        tiles.set(participant.sid, rec);
        updateName(rec, participant);
        return rec;
    }

    function updateName(rec, participant) {
        const nm = (participant.identity === room?.localParticipant?.identity) ? (displayName || 'You')
            : (participant.name || participant.identity || 'Guest');
        rec.nameEl.textContent = nm;
    }

    function addTrack(track, participant) {
        const rec = tileFor(participant, participant.sid === room.localParticipant.sid);
        if (track.kind === 'video') {
            const ph = rec.el.querySelector('.lk-placeholder');
            if (ph) ph.remove();
            const video = document.createElement('video');
            video.autoplay = true; video.playsInline = true; video.muted = participant.sid === room.localParticipant.sid;
            track.attach(video);
            rec.el.appendChild(video);
            rec.videos.push({ track: track, el: video });
        } else if (track.kind === 'audio') {
            const audio = document.createElement('audio');
            audio.autoplay = true;
            track.attach(audio);
            rec.el.appendChild(audio);
            rec.videos.push({ track: track, el: audio });
            rec.micEl.innerHTML = '<i class="fas fa-microphone"></i>';
        }
    }

    function removeTrack(track) {
        tiles.forEach((rec) => {
            const idx = rec.videos.findIndex(v => v.track === track);
            if (idx > -1) {
                try { track.detach(rec.videos[idx].el); } catch (e) {}
                rec.videos[idx].el.remove();
                rec.videos.splice(idx, 1);
            }
        });
    }

    function removeTile(sid) {
        const rec = tiles.get(sid);
        if (rec) { rec.el.remove(); tiles.delete(sid); }
    }

    function reset() {
        tiles.clear();
        grid.innerHTML = '';
        wrap.style.display = 'none';
        landing.style.display = 'block';
        retryBtn.style.display = 'none';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-video"></i> Join Video Meeting';
    }

    function fail(msg) {
        setStatus('<i class="fas fa-triangle-exclamation"></i> ' + msg, '#f87171');
        retryBtn.style.display = 'inline-flex';
        landingError.style.display = 'block';
        landingError.textContent = msg;
    }

    async function fetchToken() {
        const res = await fetch(TOKEN_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, credentials: 'same-origin' });
        const data = await res.json().catch(() => ({}));
        if (!res.ok || !data.token) {
            throw new Error(data.error || ('Could not get a meeting token (HTTP ' + res.status + ').'));
        }
        return data;
    }

    async function startMeeting() {
        if (!window.LivekitClient) { fail('Live meeting client failed to load. Check your connection and retry.'); return; }
        retryBtn.style.display = 'none';
        landing.style.display = 'none';
        wrap.style.display = 'block';
        setStatus('<i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…');

        const { Room, RoomEvent } = window.LivekitClient;
        room = new Room({ adaptiveStream: true, dynacast: true });

        room.on(RoomEvent.TrackSubscribed, (track, pub, participant) => addTrack(track, participant));
        room.on(RoomEvent.TrackUnsubscribed, (track) => removeTrack(track));
        room.on(RoomEvent.LocalTrackPublished, (pub) => { if (pub.track) addTrack(pub.track, room.localParticipant); });
        room.on(RoomEvent.ParticipantConnected, () => {});
        room.on(RoomEvent.ParticipantDisconnected, (p) => removeTile(p.sid));
        room.on(RoomEvent.Reconnected, () => setStatus('<i class="fas fa-circle" style="color:#34d399;"></i> Reconnected'));
        room.on(RoomEvent.Disconnected, () => fail('Disconnected from the meeting server.'));

        try {
            const cfg = await fetchToken();
            await room.connect(cfg.url, cfg.token);
            try {
                await room.localParticipant.setMicrophoneEnabled(true);
                await room.localParticipant.setCameraEnabled(true);
            } catch (mediaErr) {
                setStatus('<i class="fas fa-triangle-exclamation" style="color:#fbbf24;"></i> Joined without camera/mic — allow device access and retry.', '#fbbf24');
            }
            setStatus('<i class="fas fa-circle" style="color:#34d399;"></i> Connected · ' + cfg.room);
            retryBtn.style.display = 'none';
            updateControls();
        } catch (err) {
            fail(err.message || 'Could not connect to the meeting server.');
            reset();
        }
    }

    function updateControls() {
        if (!room) return;
        const p = room.localParticipant;
        const micOn = p.isMicrophoneEnabled();
        const camOn = p.isCameraEnabled();
        micBtn.classList.toggle('off', !micOn);
        micBtn.innerHTML = micOn ? '<i class="fas fa-microphone"></i> Mute' : '<i class="fas fa-microphone-slash"></i> Unmute';
        camBtn.classList.toggle('off', !camOn);
        camBtn.innerHTML = camOn ? '<i class="fas fa-video"></i> Stop camera' : '<i class="fas fa-video-slash"></i> Start camera';
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

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting…';
        landingError.style.display = 'none';
        startMeeting();
    });
    retryBtn.addEventListener('click', function () { setStatus('<i class="fas fa-spinner fa-spin"></i> Reconnecting…'); startMeeting(); });
})();
</script>
</body>
</html>
