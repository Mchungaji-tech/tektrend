<?php $pageTitle = 'Video Meetings & Teleconferences'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-video" style="color: var(--primary); margin-right: 0.5rem;"></i> Video Meetings & Teleconferences
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Host architectural reviews, client teleconferences, and engineering standups</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/meetings/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Schedule Meeting
            </a>
            <a href="<?= eurl('/chat') ?>" class="btn btn-outline">
                <i class="fas fa-comments"></i> Virtual Office
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Scheduled Meetings (<?= count($meetings) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Meeting Title</th>
                    <th>Meeting Host</th>
                    <th>Scheduled Slot</th>
                    <th>Status</th>
                    <th>Room Key</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($meetings)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No video meetings scheduled.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($meetings as $m): ?>
                        <tr>
                            <td><strong style="color: var(--text-main);"><?= sanitize($m['title']) ?></strong></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($m['first_name'] . ' ' . $m['last_name']) ?></span></td>
                            <td style="color: var(--text-muted);"><?= formatDateTime($m['scheduled_at'] ?? ($m['start_time'] ?? '')) ?></td>
                            <td><span class="badge <?= sanitize($m['status'] ?? 'scheduled') ?>"><?= ucfirst(sanitize($m['status'] ?? 'scheduled')) ?></span></td>
                            <td><span style="font-family: monospace; font-size: 0.82rem; color: var(--text-muted);"><?= sanitize($m['room_id'] ?? '-') ?></span></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <?php if (($m['status'] ?? 'scheduled') === 'scheduled'): ?>
                                        <a href="<?= eurl('/meetings/' . $m['id'] . '/start') ?>" class="btn btn-zoom" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;"><i class="fas fa-video"></i> Start</a>
                                    <?php endif; ?>
                                    <a href="<?= eurl('/meetings/' . $m['id'] . '/join') ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;"><i class="fas fa-sign-in-alt"></i> Join Room</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
