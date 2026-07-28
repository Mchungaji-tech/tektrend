<?php $pageTitle = 'Leads'; ?>
<div class="topbar">
    <div class="greeting"><h1>Leads & CRM</h1><p>Manage sales leads and track follow-ups</p></div>
    <div class="actions">
        <a href="/leads/create" class="notif" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Lead</a>
        <a href="/leads/followup" class="notif"><i class="fas fa-bell"></i></a>
    </div>
</div>
<div class="table-section reveal">
    <div class="header">
        <h3>All Leads (<?= count($leads) ?>)</h3>
        <form method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search leads..." value="<?= sanitize($search) ?>" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
            <select name="status" onchange="this.form.submit()" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
                <option value="">All Statuses</option>
                <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New</option>
                <option value="contacted" <?= $statusFilter === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                <option value="qualified" <?= $statusFilter === 'qualified' ? 'selected' : '' ?>>Qualified</option>
                <option value="proposal" <?= $statusFilter === 'proposal' ? 'selected' : '' ?>>Proposal</option>
                <option value="negotiation" <?= $statusFilter === 'negotiation' ? 'selected' : '' ?>>Negotiation</option>
                <option value="closed_won" <?= $statusFilter === 'closed_won' ? 'selected' : '' ?>>Won</option>
                <option value="closed_lost" <?= $statusFilter === 'closed_lost' ? 'selected' : '' ?>>Lost</option>
            </select>
        </form>
    </div>
    <table>
        <thead><tr><th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Value</th><th>Status</th><th>Priority</th><th>Assigned To</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($leads)): ?>
                <tr><td colspan="9" style="text-align: center; color: rgba(245,240,235,0.3);">No leads found</td></tr>
            <?php else: ?>
                <?php foreach ($leads as $lead): ?>
                    <tr>
                        <td><?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></td>
                        <td><?= sanitize($lead['company'] ?? '-') ?></td>
                        <td><?= sanitize($lead['email'] ?? '-') ?></td>
                        <td><?= sanitize($lead['phone'] ?? '-') ?></td>
                        <td><?= formatCurrency($lead['value']) ?></td>
                        <td><span class="status <?= $lead['status'] ?>"><?= str_replace('_', ' ', $lead['status']) ?></span></td>
                        <td><span class="status <?= $lead['priority'] ?>"><?= ucfirst($lead['priority']) ?></span></td>
                        <td><?= sanitize(($lead['assigned_first'] ?? '') . ' ' . ($lead['assigned_last'] ?? '')) ?: 'Unassigned' ?></td>
                        <td>
                            <a href="/leads/<?= $lead['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a>
                            <a href="/leads/<?= $lead['id'] ?>/edit" style="color: #f59e0b; margin-left: 0.5rem;"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>
    .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; }
    .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }
    table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); }
    table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); }
    table tr:hover td { background: rgba(245,240,235,0.01); }
    table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    table .status.new { color: #3b82f6; background: rgba(59,130,246,0.04); }
    table .status.contacted { color: #6366f1; background: rgba(99,102,241,0.04); }
    table .status.qualified { color: #10b981; background: rgba(16,185,129,0.04); }
    table .status.proposal { color: #f59e0b; background: rgba(245,159,11,0.04); }
    table .status.negotiation { color: #f97316; background: rgba(249,115,22,0.04); }
    table .status.closed_won { color: #4caf50; background: rgba(76,175,80,0.04); }
    table .status.closed_lost { color: #ef4444; background: rgba(239,68,68,0.04); }
    table .status.high { color: #ef5350; background: rgba(239,83,80,0.04); }
    table .status.medium { color: #f59e0b; background: rgba(245,159,11,0.04); }
    table .status.low { color: #3b82f6; background: rgba(59,130,246,0.04); }
    table .status.urgent { color: #ef4444; background: rgba(239,68,68,0.04); }
</style>
