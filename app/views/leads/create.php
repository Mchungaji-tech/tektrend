<?php $pageTitle = 'Create Lead'; ?>
<div class="topbar"><div class="greeting"><h1>Create Lead</h1><p>Add a new sales lead</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Lead Information</h3></div>
    <form method="POST" action="/leads">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">First Name</label><input type="text" name="first_name" class="form-control" required value="<?= sanitize($_SESSION['old']['first_name'] ?? '') ?>"></div>
            <div class="form-group"><label class="required">Last Name</label><input type="text" name="last_name" class="form-control" required value="<?= sanitize($_SESSION['old']['last_name'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= sanitize($_SESSION['old']['email'] ?? '') ?>"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?= sanitize($_SESSION['old']['phone'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Company</label><input type="text" name="company" class="form-control" value="<?= sanitize($_SESSION['old']['company'] ?? '') ?>"></div>
            <div class="form-group"><label>Position</label><input type="text" name="position" class="form-control" value="<?= sanitize($_SESSION['old']['position'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Lead Source</label><select name="source_id" class="form-control">
                <option value="">Select Source</option>
                <?php foreach ($sources as $s): ?><option value="<?= $s['id'] ?>" <?= (($_SESSION['old']['source_id'] ?? '') == $s['id']) ? 'selected' : '' ?>><?= sanitize($s['name']) ?></option><?php endforeach; ?>
            </select></div>
            <div class="form-group"><label>Assigned To</label><select name="assigned_to" class="form-control">
                <option value="">Unassigned</option>
                <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>" <?= (($_SESSION['old']['assigned_to'] ?? '') == $u['id']) ? 'selected' : '' ?>><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></option><?php endforeach; ?>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Value ($)</label><input type="number" step="0.01" name="value" class="form-control" value="<?= sanitize($_SESSION['old']['value'] ?? '0') ?>"></div>
            <div class="form-group"><label>Priority</label><select name="priority" class="form-control">
                <option value="low" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'low') ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'medium') ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'high') ? 'selected' : '' ?>>High</option>
                <option value="urgent" <?= (($_SESSION['old']['priority'] ?? 'medium') == 'urgent') ? 'selected' : '' ?>>Urgent</option>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Status</label><select name="status" class="form-control">
                <option value="new" <?= (($_SESSION['old']['status'] ?? 'new') == 'new') ? 'selected' : '' ?>>New</option>
                <option value="contacted" <?= (($_SESSION['old']['status'] ?? 'new') == 'contacted') ? 'selected' : '' ?>>Contacted</option>
                <option value="qualified" <?= (($_SESSION['old']['status'] ?? 'new') == 'qualified') ? 'selected' : '' ?>>Qualified</option>
                <option value="proposal" <?= (($_SESSION['old']['status'] ?? 'new') == 'proposal') ? 'selected' : '' ?>>Proposal</option>
                <option value="negotiation" <?= (($_SESSION['old']['status'] ?? 'new') == 'negotiation') ? 'selected' : '' ?>>Negotiation</option>
            </select></div>
            <div class="form-group"><label>Next Follow-up</label><input type="datetime-local" name="next_followup" class="form-control" value="<?= sanitize($_SESSION['old']['next_followup'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="3" placeholder="Add notes about this lead..."><?= sanitize($_SESSION['old']['notes'] ?? '') ?></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Lead</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }</style>
