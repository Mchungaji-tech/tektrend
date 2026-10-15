<?php
$pageTitle = 'Meeting Room: ' . sanitize($meeting['title'] ?? 'Conference Room');
// Backend-controlled Jitsi room (never a hard-coded external link).
$jsRoom = json_encode($roomName ?? ($meeting['room_id'] ?? ''), JSON_UNESCAPED_SLASHES);
$jsDomain = json_encode($jitsiDomain ?? JITSI_DOMAIN);
$jsName = json_encode($displayName ?? 'Guest');
$jsEmail = json_encode($userEmail ?? '');
$hostName = trim(($meeting['host_first'] ?? 'Admin') . ' ' . ($meeting['host_last'] ?? '')) ?: 'Admin';
?>
<div class="card" style="max-width: 700px; margin: 0 auto; text-align: center; padding: 3rem 2rem;" id="meetingLanding">
    <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-video"></i>
    </div>

    <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);"><?= sanitize($meeting['title'] ?? 'Tek Trend Video Session') ?></h2>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
        Hosted by: <strong style="color: var(--text-main);"><?= sanitize($hostName) ?></strong> · Starts: <?= formatDateTime($meeting['scheduled_at'] ?? date('Y-m-d H:i:s')) ?>
    </p>

    <div style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1rem; text-align: left;">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Meeting Agenda:</div>
        <p style="font-size: 0.9rem; color: var(--text-main);"><?= nl2br(sanitize($meeting['description'] ?? 'General consulting and architecture briefing.')) ?></p>
    </div>

    <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem;">
        Room key: <span style="font-family: monospace;"><?= sanitize($roomName ?? ($meeting['room_id'] ?? '')) ?></span>
    </div>

    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <button type="button" id="joinMeetingBtn" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem;">
            <i class="fas fa-video"></i> Join Meeting Room
        </button>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline" style="padding: 0.85rem 1.5rem;">
            Back to Meetings List
        </a>
    </div>
</div>

<!-- The meeting runs here, embedded inside the app (no external redirect) -->
<div id="meetingWrap" style="display: none; margin: 0 auto; max-width: 1100px;">
    <div id="jitsiContainer" style="width: 100%; height: 78vh; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border);"></div>
    <div style="text-align: center; margin-top: 1rem;">
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Leave Meeting</a>
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

    function loadExternalApi(cb) {
        if (window.JitsiMeetExternalAPI) { cb(); return; }
        const s = document.createElement('script');
        s.src = 'https://' + DOMAIN + '/external_api.js';
        s.onload = cb;
        s.onerror = function () {
            alert('Could not reach the meeting server (' + DOMAIN + '). Check your connection or JITSI_DOMAIN config.');
        };
        document.head.appendChild(s);
    }

    btn.addEventListener('click', function () {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';
        loadExternalApi(function () {
            landing.style.display = 'none';
            wrap.style.display = 'block';
            // eslint-disable-next-line no-new
            new JitsiMeetExternalAPI(DOMAIN, {
                roomName: ROOM,
                width: '100%',
                height: '78vh',
                parentNode: document.getElementById('jitsiContainer'),
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
        });
    });
})();
</script>
