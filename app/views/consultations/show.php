<?php $pageTitle = 'Consultation #' . $consultation['id']; ?>
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Consultation Scoping Brief</h3>
            <p class="card-subtitle">Client: <?= sanitize($consultation['name']) ?> (<?= sanitize($consultation['company'] ?? 'Individual') ?>)</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= sanitize($consultation['zoom_link']) ?>" target="_blank" class="btn btn-zoom"><i class="fas fa-video"></i> Launch Zoom Teleconference</a>
            <a href="<?= eurl('/consultations') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
        <div>
            <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; color: var(--text-main);">Client Details</h4>
            <table style="width: 100%; font-size: 0.88rem;">
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted); width: 140px;">Client Name:</td><td><strong style="color: var(--text-main);"><?= sanitize($consultation['name']) ?></strong></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Email:</td><td><a href="mailto:<?= sanitize($consultation['email']) ?>" style="color: var(--primary);"><?= sanitize($consultation['email']) ?></a></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Phone / WhatsApp:</td><td><a href="https://wa.me/<?= preg_replace('/\D/', '', $consultation['phone']) ?>" target="_blank" style="color: #25d366; font-weight: 600;"><i class="fab fa-whatsapp"></i> <?= sanitize($consultation['phone'] ?? '-') ?></a></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Company:</td><td style="color: var(--text-main);"><?= sanitize($consultation['company'] ?? '-') ?></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Service Area:</td><td><span class="badge" style="background: var(--primary-light); color: var(--primary); font-weight: 700;"><?= sanitize($consultation['service_type']) ?></span></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Session Slot:</td><td><strong style="color: var(--text-main);"><?= formatDate($consultation['preferred_date']) ?></strong> at <?= sanitize($consultation['preferred_time']) ?></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Duration:</td><td style="color: var(--text-main);"><?= $consultation['duration_minutes'] ?> Minutes</td></tr>
            </table>

            <div style="margin-top: 1.5rem; background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
                <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-muted); margin-bottom: 0.35rem;">Client Initial Project Brief:</div>
                <p style="font-size: 0.9rem; color: var(--text-main); line-height: 1.5;"><?= nl2br(sanitize($consultation['notes'] ?? 'No additional notes provided.')) ?></p>
            </div>
        </div>

        <div>
            <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; color: var(--text-main);">Meeting Control & Status</h4>
            <form method="POST" action="<?= eurl('/consultations/' . $consultation['id'] . '/status') ?>">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                
                <div class="form-group">
                    <label>Consultation Status</label>
                    <select name="status" class="form-control">
                        <option value="pending" <?= $consultation['status'] === 'pending' ? 'selected' : '' ?>>Pending Review</option>
                        <option value="confirmed" <?= $consultation['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed (Active Zoom)</option>
                        <option value="completed" <?= $consultation['status'] === 'completed' ? 'selected' : '' ?>>Completed (Scope Delivered)</option>
                        <option value="cancelled" <?= $consultation['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Zoom Meeting Link</label>
                    <input type="url" name="zoom_link" class="form-control" value="<?= sanitize($consultation['zoom_link']) ?>">
                </div>

                <div class="form-group">
                    <label>Internal Technical Follow-up Notes</label>
                    <textarea name="notes" class="form-control" style="min-height: 120px;" placeholder="Add private consulting notes, estimated scope, proposed architecture..."><?= sanitize($consultation['notes']) ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-save"></i> Save Consultation Updates
                </button>
            </form>
        </div>
    </div>
</div>
