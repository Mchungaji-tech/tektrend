<?php $pageTitle = 'Edit Lead'; ?>
<div class="topbar"><div class="greeting"><h1>Edit Lead</h1><p>Update lead information</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Lead Information</h3></div>
    <form method="POST" action="/leads/<?= $lead['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">First Name</label><input type="text" name="first_name" class="form-control" required value="<?= sanitize($lead['first_name']) ?>"></div>
            <div class="form-group"><label class="required">Last Name</label><input type="text" name="last_name" class="form-control" required value="<?= sanitize($lead['last_name']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= sanitize($lead['email']) ?>"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?= sanitize($lead['phone']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Company</label><input type="text" name="company" class="form-control" value="<?= sanitize($lead['company']) ?>"></div>
            <div class="form-group"><label>Position</label><input type="text" name="position" class="form-control" value="<?= sanitize($lead['position']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Lead Source</label><select name="source_id" class="form-control">
                <option value="">Select Source</option>
                <?php foreach ($sources as $s): ?><option value="<?= $s['id'] ?>" <?= ($lead['source_id'] == $s['id']) ? 'selected' : '' ?>><?= sanitize($s['name']) ?></option><?php endforeach; ?>
            </select></div>
            <div class="form-group"><label>Assigned To</label><select name="assigned_to" class="form-control">
                <option value="">Unassigned</option>
                <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>" <?= ($lead['assigned_to'] == $u['id']) ? 'selected' : '' ?>><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></option><?php endforeach; ?>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Value ($)</label><input type="number" step="0.01" name="value" class="form-control" value="<?= $lead['value'] ?>"></div>
            <div class="form-group"><label>Priority</label><select name="priority" class="form-control">
                <option value="low" <?= ($lead['priority'] == 'low') ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= ($lead['priority'] == 'medium') ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= ($lead['priority'] == 'high') ? 'selected' : '' ?>>High</option>
                <option value="urgent" <?= ($lead['priority'] == 'urgent') ? 'selected' : '' ?>>Urgent</option>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Status</label><select name="status" class="form-control">
                <option value="new" <?= ($lead['status'] == 'new') ? 'selected' : '' ?>>New</option>
                <option value="contacted" <?= ($lead['status'] == 'contacted') ? 'selected' : '' ?>>Contacted</option>
                <option value="qualified" <?= ($lead['status'] == 'qualified') ? 'selected' : '' ?>>Qualified</option>
                <option value="proposal" <?= ($lead['status'] == 'proposal') ? 'selected' : '' ?>>Proposal</option>
                <option value="negotiation" <?= ($lead['status'] == 'negotiation') ? 'selected' : '' ?>>Negotiation</option>
                <option value="closed_won" <?= ($lead['status'] == 'closed_won') ? 'selected' : '' ?>>Won</option>
                <option value="closed_lost" <?= ($lead['status'] == 'closed_lost') ? 'selected' : '' ?>>Lost</option>
            </select></div>
            <div class="form-group"><label>Next Follow-up</label><input type="datetime-local" name="next_followup" class="form-control" value="<?= $lead['next_followup'] ? date('Y-m-d\TH:i', strtotime($lead['next_followup'])) : '' ?>"></div>
        </div>
        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="3"><?= sanitize($lead['notes']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
