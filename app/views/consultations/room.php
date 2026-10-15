<?php
// Self-contained public meeting room (no staff layout). Token is the access secret.
$c = $consultation;
$jsRoom = json_encode($roomName, JSON_UNESCAPED_SLASHES);
$jsDomain = json_encode($jitsiDomain ?? JITSI_DOMAIN);
$jsName = json_encode($displayName ?? 'Guest');
$jsEmail = json_encode($userEmail ?? '');
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
        .btn[disabled] { opacity: 0.6; cursor: default; }
        .actions { display: flex; gap: 0.75rem; justify-content: center; flex-wrap: wrap; }
        .note { margin-top: 1rem; font-size: 0.78rem; color: #64748b; }
        .err { display: none; margin-top: 1.1rem; color: #f87171; font-size: 0.85rem; }
        #meetingWrap { display: none; }
        #meetingStatus { font-size: 0.82rem; color: #94a3b8; margin-bottom: 0.6rem; }
        #jitsiContainer { width: 100%; height: 78vh; min-height: 78vh; border-radius: 14px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); background: #000; }
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
                <button type="button" id="joinMeetingBtn" class="btn btn-primary"><i class="fas fa-video"></i> Join Video Meeting</button>
                <a href="<?= eurl('/') ?>" class="btn btn-outline"><i class="fas fa-home"></i> Home</a>
            </div>
            <p class="note">Runs entirely inside Tek Trend using the open-source meeting server — no external links, no app install.</p>
            <p class="err" id="landingError"></p>
        </div>

        <div id="meetingWrap">
            <div id="meetingStatus"><i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…</div>
            <div id="jitsiContainer"></div>
            <div style="text-align:center; margin-top:1rem;" class="actions">
                <button type="button" id="retryBtn" class="btn btn-outline" style="display:none;"><i class="fas fa-redo"></i> Retry Connection</button>
                <a href="<?= eurl('/') ?>" class="btn btn-outline"><i class="fas fa-phone-slash"></i> Leave Meeting</a>
            </div>
        </div>
    </div>

<script>
(function () {
    const ROOM = <?= $jsRoom ?>;
    const DOMAIN = <?= $jsDomain ?>;
    const displayName = <?= $jsName ?>;
    const email = <?= $jsEmail ?>;

    const landing = document.getElementById('meetingLanding');
    const wrap = document.getElementById('meetingWrap');
    const btn = document.getElementById('joinMeetingBtn');
    const retryBtn = document.getElementById('retryBtn');
    const status = document.getElementById('meetingStatus');
    const container = document.getElementById('jitsiContainer');
    const landingError = document.getElementById('landingError');

    let api = null;

    function setStatus(html, color) { status.innerHTML = html; status.style.color = color || '#94a3b8'; }
    function clearRoom() { if (api) { try { api.dispose(); } catch (e) {} api = null; } container.innerHTML = ''; }

    function loadExternalApi(onSuccess, onFailure) {
        if (window.JitsiMeetExternalAPI) { onSuccess(); return; }
        const existing = document.querySelector('script[data-jitsi]');
        if (existing) { existing.addEventListener('load', onSuccess); existing.addEventListener('error', onFailure); return; }
        const s = document.createElement('script');
        s.src = 'https://' + DOMAIN + '/external_api.js';
        s.async = true;
        s.setAttribute('data-jitsi', '1');
        s.onload = onSuccess; s.onerror = onFailure;
        document.head.appendChild(s);
    }

    function fail(msg) { setStatus('<i class="fas fa-triangle-exclamation"></i> ' + msg, '#f87171'); retryBtn.style.display = 'inline-flex'; }

    function reset() {
        wrap.style.display = 'none';
        landing.style.display = 'block';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-video"></i> Join Video Meeting';
    }

    function startMeeting() {
        retryBtn.style.display = 'none';
        landing.style.display = 'none';
        wrap.style.display = 'block';
        setStatus('<i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…');
        loadExternalApi(function () {
            clearRoom();
            api = new JitsiMeetExternalAPI(DOMAIN, {
                roomName: ROOM,
                parentNode: container,
                width: '100%',
                height: '100%',
                userInfo: { displayName: displayName, email: email },
                configOverwrite: {
                    prejoinConfig: { enabled: true },
                    disableZoomNoLock: true
                },
                interfaceConfigOverwrite: {
                    SHOW_JITSI_WATERMARK: false,
                    SHOW_WATERMARK_FOR_GUESTS: false
                }
            });
            api.addListener('videoConferenceJoined', function () {
                setStatus('<i class="fas fa-circle" style="color:#34d399;"></i> Connected · ' + ROOM);
                retryBtn.style.display = 'none';
            });
            api.addListener('readyToClose', reset);
            ['connectionFailed', 'networkIncompatible', 'disconnected'].forEach(function (ev) {
                api.addListener(ev, function () { fail('Lost connection to the meeting server. Check your network and retry.'); });
            });
        }, function () {
            fail('Could not reach the meeting server (' + DOMAIN + ').');
            landingError.style.display = 'block';
            landingError.textContent = 'Could not load the meeting client from ' + DOMAIN + '. Verify the connection and retry.';
            reset();
        });
    }

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting…';
        startMeeting();
    });
    retryBtn.addEventListener('click', function () { setStatus('<i class="fas fa-spinner fa-spin"></i> Reconnecting…'); startMeeting(); });
})();
</script>
</body>
</html>
