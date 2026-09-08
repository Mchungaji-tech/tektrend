<?php $pageTitle = 'Create Campaign'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Compose Email Campaign</h3>
            <p class="card-subtitle">Broadcast HTML and plain-text newsletters to verified subscribers</p>
        </div>
        <a href="<?= eurl('/emails') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Campaigns</a>
    </div>

    <form method="POST" action="<?= eurl('/emails') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Campaign Name (Internal)</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. Q1 Architecture Innovations Digest" required>
            </div>
            <div class="form-group">
                <label class="required">Email Subject Line</label>
                <input type="text" name="subject" class="form-control" placeholder="e.g. Next-Gen Enterprise Platforms by Tek Trend" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Sender Name</label>
                <input type="text" name="from_name" class="form-control" value="Tek Trend Innovations">
            </div>
            <div class="form-group">
                <label>Sender Email</label>
                <input type="email" name="from_email" class="form-control" value="info@tektrend.com">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Campaign Status</label>
                <select name="status" class="form-control">
                    <option value="draft">Draft (Save for Later)</option>
                    <option value="scheduled">Scheduled</option>
                </select>
            </div>
            <div class="form-group">
                <label>Scheduled Send Date & Time</label>
                <input type="datetime-local" name="scheduled_at" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label class="required">HTML Content Body</label>
            <textarea name="content_html" class="form-control" rows="8" placeholder="<h2>Welcome to Tek Trend</h2><p>Our latest technical innovations and live demos are available...</p>"></textarea>
        </div>

        <div class="form-group">
            <label>Plain-Text Fallback</label>
            <textarea name="content_text" class="form-control" rows="4" placeholder="Plain text version for email clients that do not render HTML..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/emails') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Campaign</button>
        </div>
    </form>
</div>
