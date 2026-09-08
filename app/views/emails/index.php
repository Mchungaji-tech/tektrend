<?php $pageTitle = 'Email Marketing'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-envelope-open-text" style="color: var(--primary); margin-right: 0.5rem;"></i> Email Marketing & Newsletters
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Broadcast engineering announcements, product launches, and client updates</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/emails/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Campaign
            </a>
            <a href="<?= eurl('/emails/subscribers') ?>" class="btn btn-outline">
                <i class="fas fa-users"></i> Subscribers (<?= $totalSubscribers ?>)
            </a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Total Subscribers</div>
        <div class="value" style="color: var(--primary);"><?= $totalSubscribers ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Opted-in clients & partners</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Emails Dispatched</div>
        <div class="value" style="color: var(--success);"><?= $totalSent ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Broadcast deliveries</div>
    </div>
    <div class="stat-card">
        <div class="label">Draft & Scheduled</div>
        <div class="value" style="color: var(--accent);"><?= count(array_filter($campaigns, function($c) { return $c['status'] !== 'sent'; })) ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Queued campaigns</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Campaigns List (<?= count($campaigns) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Email Subject</th>
                    <th>Status</th>
                    <th>Recipients</th>
                    <th>Dispatched</th>
                    <th>Date Created</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($campaigns)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No email campaigns found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($campaigns as $c): ?>
                        <tr>
                            <td><strong style="color: var(--text-main);"><?= sanitize($c['name']) ?></strong></td>
                            <td><span style="color: var(--text-main); font-weight: 500;"><?= sanitize($c['subject']) ?></span></td>
                            <td><span class="badge <?= sanitize($c['status']) ?>"><?= ucfirst(sanitize($c['status'])) ?></span></td>
                            <td><span style="color: var(--text-muted);"><?= (int)($c['total_recipients'] ?? 0) ?></span></td>
                            <td><strong style="color: var(--success);"><?= (int)($c['total_sent'] ?? 0) ?></strong></td>
                            <td style="color: var(--text-muted);"><?= formatDate($c['created_at']) ?></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <a href="<?= eurl('/emails/' . $c['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--primary);"></i>
                                    </a>
                                    <?php if ($c['status'] === 'draft'): ?>
                                        <a href="<?= eurl('/emails/' . $c['id'] . '/send') ?>" class="btn btn-primary" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Send Campaign">
                                            <i class="fas fa-paper-plane"></i> Send
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
