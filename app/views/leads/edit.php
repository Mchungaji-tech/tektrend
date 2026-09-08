<?php $pageTitle = 'Edit Lead'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Lead: <?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></h3>
            <p class="card-subtitle">Update deal stage, value, and follow-up timeline</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/leads/' . $lead['id']) ?>" class="btn btn-outline"><i class="fas fa-eye"></i> View Lead</a>
            <a href="<?= eurl('/leads') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> All Leads</a>
        </div>
    </div>

    <form method="POST" action="<?= eurl('/leads/' . $lead['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" required value="<?= sanitize($lead['first_name']) ?>">
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" required value="<?= sanitize($lead['last_name']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= sanitize($lead['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($lead['phone'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="<?= sanitize($lead['company'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Position</label>
                <input type="text" name="position" class="form-control" value="<?= sanitize($lead['position'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Lead Source</label>
                <select name="source_id" class="form-control">
                    <option value="">Select Source...</option>
                    <?php foreach ($sources as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($lead['source_id'] == $s['id']) ? 'selected' : '' ?>><?= sanitize($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Assigned Consultant</label>
                <select name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= ($lead['assigned_to'] == $u['id']) ? 'selected' : '' ?>><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Deal Value (KSh)</label>
                <input type="number" step="100" name="value" class="form-control" value="<?= $lead['value'] ?>">
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option value="low" <?= ($lead['priority'] == 'low') ? 'selected' : '' ?>>Low</option>
                    <option value="medium" <?= ($lead['priority'] == 'medium') ? 'selected' : '' ?>>Medium</option>
                    <option value="high" <?= ($lead['priority'] == 'high') ? 'selected' : '' ?>>High</option>
                    <option value="urgent" <?= ($lead['priority'] == 'urgent') ? 'selected' : '' ?>>Urgent</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pipeline Stage</label>
                <select name="status" class="form-control">
                    <option value="new" <?= ($lead['status'] == 'new') ? 'selected' : '' ?>>New</option>
                    <option value="contacted" <?= ($lead['status'] == 'contacted') ? 'selected' : '' ?>>Contacted</option>
                    <option value="qualified" <?= ($lead['status'] == 'qualified') ? 'selected' : '' ?>>Qualified</option>
                    <option value="proposal" <?= ($lead['status'] == 'proposal') ? 'selected' : '' ?>>Proposal</option>
                    <option value="negotiation" <?= ($lead['status'] == 'negotiation') ? 'selected' : '' ?>>Negotiation</option>
                    <option value="closed_won" <?= ($lead['status'] == 'closed_won') ? 'selected' : '' ?>>Closed Won</option>
                    <option value="closed_lost" <?= ($lead['status'] == 'closed_lost') ? 'selected' : '' ?>>Closed Lost</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Next Follow-up Date</label>
            <input type="datetime-local" name="next_followup" class="form-control" value="<?= $lead['next_followup'] ? date('Y-m-d\TH:i', strtotime($lead['next_followup'])) : '' ?>">
        </div>

        <div class="form-group">
            <label>Notes & Scope Details</label>
            <textarea name="notes" class="form-control" rows="3"><?= sanitize($lead['notes'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/leads/' . $lead['id']) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
