<?php
$pageTitle = 'Meeting Room: ' . sanitize($meeting['title'] ?? 'Conference Room');
// Backend-controlled open-source (Jitsi) room. Never a hard-coded external link, never a redirect.
$jsRoom = json_encode($roomName ?? ($meeting['room_id'] ?? ''), JSON_UNESCAPED_SLASHES);
$jsDomain = json_encode($jitsiDomain ?? JITSI_DOMAIN);
$jsName = json_encode($displayName ?? 'Guest');
$jsEmail = json_encode($userEmail ?? '');
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
        Room key: <span style="font-family: monospace;"><?= sanitize($roomName ?? ($meeting['room_id'] ?? '')) ?></span> · Powered by the embedded open-source meeting server
    </div>

    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <button type="button" id="joinMeetingBtn" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem;">
            <i class="fas fa-video"></i> Join Video Meeting
        </button>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline" style="padding: 0.85rem 1.5rem;">
            Back to Meetings
        </a>
    </div>
    <p id="landingError" style="display:none; margin-top: 1.25rem; color: var(--danger, #ef4444); font-size: 0.85rem;"></p>
</div>

<!-- The meeting runs here, embedded inside the app (no external redirect) -->
<div id="meetingWrap" style="display: none; margin: 0 auto; max-width: 1100px;">
    <div id="meetingStatus" style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.5rem;">
        <i class="fas fa-spinner fa-spin"></i> Connecting to the meeting server…
    </div>
    <div id="jitsiContainer" style="width: 100%; min-height: 78vh; height: 78vh; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border); position: relative;"></div>
    <div style="text-align: center; margin-top: 1rem;">
        <button type="button" id="retryBtn" class="btn btn-secondary" style="display:none; margin-right: 0.5rem;"><i class="fas fa-redo"></i> Retry Connection</button>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline"><i class="fas fa-phone-slash"></i> Leave Meeting</a>
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

    function setStatus(html, color) {
        status.innerHTML = html;
        status.style.color = color || 'var(--text-muted)';
    }

    function clearRoom() {
        if (api) { try { api.dispose(); } catch (e) {} api = null; }
        container.innerHTML = '';
    }

    function loadExternalApi(onSuccess, onFailure) {
        if (window.JitsiMeetExternalAPI) { onSuccess(); return; }
        const existing = document.querySelector('script[data-jitsi]');
        if (existing) { existing.addEventListener('load', onSuccess); existing.addEventListener('error', onFailure); return; }
        const s = document.createElement('script');
        s.src = 'https://' + DOMAIN + '/external_api.js';
        s.async = true;
        s.setAttribute('data-jitsi', '1');
        s.onload = onSuccess;
        s.onerror = onFailure;
        document.head.appendChild(s);
    }

    function fail(msg) {
        setStatus('<i class="fas fa-triangle-exclamation"></i> ' + msg, 'var(--danger, #ef4444)');
        retryBtn.style.display = 'inline-flex';
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
                    startWithAudioMuted: false,
                    startWithVideoMuted: false,
                    disableZoomNoLock: true
                },
                interfaceConfigOverwrite: {
                    SHOW_JITSI_WATERMARK: false,
                    SHOW_WATERMARK_FOR_GUESTS: false
                }
            });

            api.addListener('videoConferenceJoined', function () {
                setStatus('<i class="fas fa-circle" style="color: var(--success);"></i> Connected · ' + ROOM);
                retryBtn.style.display = 'none';
            });
            api.addListener('readyToClose', function () {
                clearRoom();
                wrap.style.display = 'none';
                landing.style.display = 'block';
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-video"></i> Join Video Meeting';
            });
            ['connectionFailed', 'networkIncompatible', 'disconnected'].forEach(function (ev) {
                api.addListener(ev, function () {
                    fail('Lost connection to the meeting server. Check your network and retry.');
                });
            });
        }, function () {
            fail('Could not reach the meeting server (' + DOMAIN + '). Verify JITSI_DOMAIN and your connection, then retry.');
            landing.style.display = 'block';
            wrap.style.display = 'none';
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-video"></i> Join Video Meeting';
            landingError.style.display = 'block';
            landingError.textContent = 'Could not load the meeting client from ' + DOMAIN + '.';
        });
    }

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting…';
        startMeeting();
    });

    retryBtn.addEventListener('click', function () {
        setStatus('<i class="fas fa-spinner fa-spin"></i> Reconnecting…');
        startMeeting();
    });
})();
</script>
