<?php $pageTitle = 'Lead Details'; ?>
<div class="topbar"><div class="greeting"><h1>Lead: <?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></h1><p>Lead details and activity history</p></div>
    <div class="actions">
        <a href="/leads/<?= $lead['id'] ?>/edit" style="color: #f59e0b; background: rgba(245,159,11,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-edit"></i> Edit</a>
        <a href="/leads" style="color: #6b7280; background: rgba(107,114,128,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-left: 0.5rem;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="chart-card reveal">
        <div class="header"><h3>Lead Information</h3></div>
        <div style="margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Name</span><span><?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Company</span><span><?= sanitize($lead['company'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Email</span><span><?= sanitize($lead['email'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Phone</span><span><?= sanitize($lead['phone'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Position</span><span><?= sanitize($lead['position'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Source</span><span><?= sanitize($lead['source_name'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Value</span><span><?= formatCurrency($lead['value']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Status</span><span class="status <?= $lead['status'] ?>"><?= str_replace('_', ' ', $lead['status']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Priority</span><span class="status <?= $lead['priority'] ?>"><?= ucfirst($lead['priority']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Next Follow-up</span><span><?= $lead['next_followup'] ? formatDateTime($lead['next_followup']) : 'Not set' ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Assigned To</span><span><?= sanitize(($lead['assigned_first'] ?? '') . ' ' . ($lead['assigned_last'] ?? '')) ?: 'Unassigned' ?></span></div>
        </div>
    </div>
    <div class="chart-card reveal">
        <div class="header"><h3>Add Activity</h3></div>
        <form method="POST" action="/leads/<?= $lead['id'] ?>/activity" style="margin-top: 1rem;">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group"><label>Type</label><select name="type" class="form-control">
                <option value="note">Note</option><option value="call">Call</option><option value="email">Email</option><option value="meeting">Meeting</option>
            </select></div>
            <div class="form-group"><label>Subject</label><input type="text" name="subject" class="form-control"></div>
            <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Activity</button>
        </form>
    </div>
</div>
<div class="table-section reveal">
    <div class="header"><h3>Activity History</h3></div>
    <table>
        <thead><tr><th>Date</th><th>Type</th><th>Subject</th><th>Description</th><th>By</th></tr></thead>
        <tbody>
            <?php if (empty($activities)): ?>
                <tr><td colspan="5" style="text-align: center; color: rgba(245,240,235,0.3);">No activities yet</td></tr>
            <?php else: ?>
                <?php foreach ($activities as $a): ?>
                    <tr>
                        <td><?= formatDateTime($a['activity_at']) ?></td>
                        <td><span class="status <?= $a['type'] ?>"><?= ucfirst($a['type']) ?></span></td>
                        <td><?= sanitize($a['subject'] ?? '-') ?></td>
                        <td><?= sanitize(substr($a['description'] ?? '-', 0, 80)) . (strlen($a['description'] ?? '') > 80 ? '...' : '') ?></td>
                        <td><?= sanitize(($a['first_name'] ?? '') . ' ' . ($a['last_name'] ?? '')) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.note { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.call { color: #10b981; background: rgba(16,185,129,0.04); } table .status.email { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.meeting { color: #6366f1; background: rgba(99,102,241,0.04); } .form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }</style>
