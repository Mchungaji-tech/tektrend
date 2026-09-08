<?php $pageTitle = 'Join Meeting: ' . sanitize($meeting['title'] ?? 'Conference Room'); ?>
<div class="card" style="max-width: 700px; margin: 0 auto; text-align: center; padding: 3rem 2rem;">
    <div style="width: 70px; height: 70px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem;">
        <i class="fas fa-video"></i>
    </div>
    
    <h2 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-main);"><?= sanitize($meeting['title'] ?? 'Tek Trend Video Session') ?></h2>
    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
        Hosted by: <strong style="color: var(--text-main);"><?= sanitize(($meeting['first_name'] ?? 'Admin') . ' ' . ($meeting['last_name'] ?? '')) ?></strong> · Starts: <?= formatDateTime($meeting['start_time'] ?? date('Y-m-d H:i:s')) ?>
    </p>

    <div style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border); margin-bottom: 2rem; text-align: left;">
        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.25rem;">Meeting Agenda:</div>
        <p style="font-size: 0.9rem; color: var(--text-main);"><?= nl2br(sanitize($meeting['description'] ?? 'General consulting and architecture briefing.')) ?></p>
    </div>

    <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
        <a href="<?= sanitize($meeting['meeting_url'] ?? 'https://zoom.us/j/9924883102?pwd=tektrend_consult') ?>" target="_blank" class="btn btn-zoom" style="padding: 0.85rem 2rem; font-size: 1rem;">
            <i class="fas fa-video"></i> Enter Zoom Meeting Room
        </a>
        <a href="<?= eurl('/meetings') ?>" class="btn btn-outline" style="padding: 0.85rem 1.5rem;">
            Back to Meetings List
        </a>
    </div>
</div>
