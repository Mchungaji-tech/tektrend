<?php $pageTitle = 'Event: ' . sanitize($event['title']); ?>
<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title"><?= sanitize($event['title']) ?></h3>
            <p class="card-subtitle"><i class="fas fa-calendar-alt"></i> <?= formatDate($event['start_datetime'] ?? ($event['start_date'] ?? '')) ?> · <span class="badge <?= sanitize($event['status'] ?? 'scheduled') ?>"><?= ucfirst(sanitize($event['status'] ?? 'Scheduled')) ?></span></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/events/' . $event['id'] . '/edit') ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Event</a>
            <a href="<?= eurl('/events') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Calendar</a>
        </div>
    </div>

    <div style="font-size: 0.92rem; line-height: 1.6; margin-bottom: 2rem;">
        <h4 style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 0.5rem;">Event Description</h4>
        <div style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border); color: var(--text-main);">
            <?= nl2br(sanitize($event['description'] ?? 'No description provided.')) ?>
        </div>
    </div>

    <table style="width: 100%; font-size: 0.88rem;">
        <tr><td style="padding: 0.4rem 0; color: var(--text-muted); width: 140px;">Location:</td><td style="color: var(--text-main); font-weight: 600;"><?= sanitize($event['location'] ?? 'Virtual Office / Online') ?></td></tr>
        <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">Start Time:</td><td style="color: var(--text-main);"><?= formatDateTime($event['start_datetime'] ?? ($event['start_date'] ?? '')) ?></td></tr>
        <tr><td style="padding: 0.4rem 0; color: var(--text-muted);">End Time:</td><td style="color: var(--text-main);"><?= formatDateTime($event['end_datetime'] ?? ($event['end_date'] ?? '')) ?></td></tr>
    </table>
</div>
