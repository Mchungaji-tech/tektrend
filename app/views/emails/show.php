<?php $pageTitle = 'Campaign: ' . sanitize($campaign['name'] ?? 'Details'); ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">
                    <?= sanitize($campaign['name'] ?? 'Email Campaign') ?>
                </h2>
                <span class="badge <?= sanitize($campaign['status'] ?? 'draft') ?>"><?= ucfirst(sanitize($campaign['status'] ?? 'draft')) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Subject: <strong><?= sanitize($campaign['subject'] ?? '-') ?></strong></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <?php if (($campaign['status'] ?? 'draft') === 'draft'): ?>
                <a href="<?= eurl('/emails/' . $campaign['id'] . '/send') ?>" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Send Now</a>
            <?php endif; ?>
            <a href="<?= eurl('/emails') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Campaigns</a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Rendered HTML Preview</h3>
        </div>
        <div style="background: var(--bg-card-subtle); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border); color: var(--text-main); min-height: 200px;">
            <?= $campaign['content_html'] ?? '<p>No HTML body content specified.</p>' ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Campaign Delivery Metrics</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">From:</span>
                <strong style="color: var(--text-main);"><?= sanitize($campaign['from_name'] ?? 'Tek Trend') ?> &lt;<?= sanitize($campaign['from_email'] ?? 'info@tektrend.com') ?>&gt;</strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Sent Timestamp:</span>
                <strong style="color: var(--text-main);"><?= !empty($campaign['sent_at']) ? formatDateTime($campaign['sent_at']) : 'Not yet dispatched' ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Target Recipients:</span>
                <strong style="color: var(--text-main);"><?= (int)($campaign['total_recipients'] ?? 0) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Delivered Dispatches:</span>
                <strong style="color: var(--success); font-size: 1.05rem;"><?= (int)($campaign['total_sent'] ?? 0) ?></strong>
            </div>
        </div>
    </div>
</div>
