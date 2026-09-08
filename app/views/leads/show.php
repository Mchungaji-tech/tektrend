<?php $pageTitle = 'Lead: ' . sanitize($lead['first_name'] . ' ' . $lead['last_name']); ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">
                    <?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?>
                </h2>
                <span class="badge <?= sanitize($lead['status']) ?>"><?= ucfirst(str_replace('_', ' ', sanitize($lead['status']))) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Company: <strong><?= sanitize($lead['company'] ?? 'Independent Client') ?></strong> · Value: <strong style="color: var(--primary);"><?= formatCurrency($lead['value']) ?></strong></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/leads/' . $lead['id'] . '/edit') ?>" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit Lead
            </a>
            <a href="<?= eurl('/leads') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> All Leads
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Left: Details -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Prospect Profile</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Contact Name</span>
                <strong style="color: var(--text-main);"><?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Company</span>
                <strong style="color: var(--text-main);"><?= sanitize($lead['company'] ?? '-') ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Email</span>
                <span style="color: var(--text-main);"><?= sanitize($lead['email'] ?? '-') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Phone</span>
                <span style="color: var(--text-main);"><?= sanitize($lead['phone'] ?? '-') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Position</span>
                <span style="color: var(--text-main);"><?= sanitize($lead['position'] ?? '-') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Lead Source</span>
                <span style="color: var(--text-main);"><?= sanitize($lead['source_name'] ?? 'Direct Inbound') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Deal Value</span>
                <strong style="color: var(--primary); font-size: 1.05rem;"><?= formatCurrency($lead['value']) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Priority</span>
                <span class="badge status <?= sanitize($lead['priority']) ?>"><?= ucfirst(sanitize($lead['priority'])) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Next Scheduled Follow-up</span>
                <strong style="color: var(--text-main);"><?= $lead['next_followup'] ? formatDateTime($lead['next_followup']) : 'None scheduled' ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Assigned Consultant</span>
                <strong style="color: var(--text-main);"><?= sanitize(($lead['assigned_first'] ?? '') . ' ' . ($lead['assigned_last'] ?? '')) ?: 'Unassigned' ?></strong>
            </div>
        </div>
    </div>

    <!-- Right: Add Activity -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Log Consultation Activity</h3>
        </div>
        <form method="POST" action="<?= eurl('/leads/' . $lead['id'] . '/activity') ?>">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group">
                <label>Activity Type</label>
                <select name="type" class="form-control">
                    <option value="note">Internal Note</option>
                    <option value="call">Phone Call</option>
                    <option value="email">Email Outreach</option>
                    <option value="meeting">Zoom Consultation</option>
                </select>
            </div>
            <div class="form-group">
                <label>Subject</label>
                <input type="text" name="subject" class="form-control" placeholder="e.g. Scoping Call regarding Portal Customization" required>
            </div>
            <div class="form-group">
                <label>Activity Notes</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Key outcomes and next steps agreed..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Save Activity Log</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Activity History & Touchpoints</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Subject</th>
                    <th>Summary</th>
                    <th>Logged By</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($activities)): ?>
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No activities logged yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($activities as $a): ?>
                        <tr>
                            <td style="color: var(--text-muted);"><?= formatDateTime($a['activity_at']) ?></td>
                            <td><span class="badge badge-info"><?= ucfirst(sanitize($a['type'])) ?></span></td>
                            <td><strong style="color: var(--text-main);"><?= sanitize($a['subject'] ?? '-') ?></strong></td>
                            <td style="color: var(--text-main);"><?= sanitize($a['description'] ?? '-') ?></td>
                            <td style="color: var(--text-muted);"><?= sanitize(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? '')) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
