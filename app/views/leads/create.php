<?php $pageTitle = 'Create Lead'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Create Sales Lead / Opportunity</h3>
            <p class="card-subtitle">Add a prospective client deal or enterprise consulting lead</p>
        </div>
        <a href="<?= eurl('/leads') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Leads</a>
    </div>

    <form method="POST" action="<?= eurl('/leads') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" required value="<?= sanitize($_SESSION['old']['first_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" required value="<?= sanitize($_SESSION['old']['last_name'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= sanitize($_SESSION['old']['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($_SESSION['old']['phone'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Company / Organization</label>
                <input type="text" name="company" class="form-control" value="<?= sanitize($_SESSION['old']['company'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Position / Title</label>
                <input type="text" name="position" class="form-control" value="<?= sanitize($_SESSION['old']['position'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Lead Source</label>
                <select name="source_id" class="form-control">
                    <option value="">Select Source...</option>
                    <?php foreach ($sources as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (($_SESSION['old']['source_id'] ?? '') == $s['id']) ? 'selected' : '' ?>><?= sanitize($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Assigned Consultant</label>
                <select name="assigned_to" class="form-control">
                    <option value="">Unassigned</option>
                    <?php foreach ($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= (($_SESSION['old']['assigned_to'] ?? '') == $u['id']) ? 'selected' : '' ?>><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Estimated Value (KSh)</label>
                <input type="number" step="100" name="value" class="form-control" value="<?= sanitize($_SESSION['old']['value'] ?? '0') ?>">
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option value="low" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'low') ? 'selected' : '' ?>>Low</option>
                    <option value="medium" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'medium') ? 'selected' : '' ?>>Medium</option>
                    <option value="high" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'high') ? 'selected' : '' ?>>High</option>
                    <option value="urgent" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'urgent') ? 'selected' : '' ?>>Urgent</option>
                </select>
            </div>
            <div class="form-group">
                <label>Pipeline Stage</label>
                <select name="status" class="form-control">
                    <option value="new" <?= (($_SESSION['old']['status'] ?? 'new') == 'new') ? 'selected' : '' ?>>New</option>
                    <option value="contacted" <?= (($_SESSION['old']['status'] ?? 'new') == 'contacted') ? 'selected' : '' ?>>Contacted</option>
                    <option value="qualified" <?= (($_SESSION['old']['status'] ?? 'new') == 'qualified') ? 'selected' : '' ?>>Qualified</option>
                    <option value="proposal" <?= (($_SESSION['old']['status'] ?? 'new') == 'proposal') ? 'selected' : '' ?>>Proposal</option>
                    <option value="negotiation" <?= (($_SESSION['old']['status'] ?? 'new') == 'negotiation') ? 'selected' : '' ?>>Negotiation</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Next Follow-up Date & Time</label>
            <input type="datetime-local" name="next_followup" class="form-control" value="<?= sanitize($_SESSION['old']['next_followup'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Notes & Scope Summary</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Key client requirements and initial discussion notes..."><?= sanitize($_SESSION['old']['notes'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/leads') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Lead</button>
        </div>
    </form>
</div>
