<?php
$pageTitle = 'Meeting Room: ' . sanitize($meeting['title'] ?? 'Conference Room');
$meetUrl = trim((string)($meeting['meeting_url'] ?? ''));
$hostName = trim(($meeting['host_first'] ?? 'Admin') . ' ' . ($meeting['host_last'] ?? '')) ?: 'Admin';
$isLive = ($meeting['status'] ?? 'scheduled') === 'in_progress';
?>
<div class="card" style="max-width: 700px; margin: 0 auto; text-align: center; padding: 3rem 2rem;">
    <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-video"></i>
    </div>

    <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);"><?= sanitize($meeting['title'] ?? 'Tek Trend Video Meeting') ?></h2>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
        Hosted by: <strong style="color: var(--text-main);"><?= sanitize($hostName) ?></strong> · <?= $isLive ? '<span style="color: var(--success); font-weight:700;">● Live now</span>' : 'Starts: ' . formatDateTime($meeting['scheduled_at'] ?? date('Y-m-d H:i:s')) ?>
    </p>

    <div style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1.5rem; text-align: left;">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Meeting Agenda:</div>
        <p style="font-size: 0.9rem; color: var(--text-main);"><?= nl2br(sanitize($meeting['description'] ?? 'General consulting and architecture briefing.')) ?></p>
    </div>

    <?php if ($meetUrl !== ''): ?>
        <div style="background: var(--bg-card-subtle); padding: 0.9rem 1rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 1.5rem;">
            <div style="font-size: 0.78rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Google Meet link:</div>
            <a href="<?= sanitize($meetUrl) ?>" target="_blank" rel="noopener" style="color: var(--primary); font-weight: 600; word-break: break-all;"><?= sanitize($meetUrl) ?></a>
        </div>

        <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
            <a href="<?= sanitize($meetUrl) ?>" target="_blank" rel="noopener" class="btn btn-primary" style="padding: 0.85rem 2rem; font-size: 1rem;">
                <i class="fas fa-video"></i> Join in Google Meet
            </a>
            <a href="<?= eurl('/meetings') ?>" class="btn btn-outline" style="padding: 0.85rem 1.5rem;">Back to Meetings</a>
        </div>
        <p style="margin-top: 1.25rem; font-size: 0.8rem; color: var(--text-muted);">The meeting opens Google Meet in a new tab.</p>
    <?php else: ?>
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1.25rem;">
            No Google Meet link has been generated for this meeting yet. Google Meet auto-creation is not configured on this server.
        </p>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline" style="padding: 0.85rem 1.5rem;">Back to Meetings</a>
    <?php endif; ?>
</div>
