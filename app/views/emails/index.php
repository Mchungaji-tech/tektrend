<?php $pageTitle = 'Email Marketing'; ?>
<div class="topbar"><div class="greeting"><h1>Email Marketing</h1><p>Manage email campaigns</p></div>
    <a href="/emails/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Create Campaign</a>
    <a href="/emails/subscribers" style="color: #3b82f6; background: rgba(59,130,246,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-left: 0.5rem;"><i class="fas fa-users"></i> Subscribers</a>
</div>
<div class="stats-grid">
    <div class="stat-card reveal"><div class="label">Total Subscribers</div><div class="value"><?= $totalSubscribers ?></div></div>
    <div class="stat-card reveal"><div class="label">Total Sent</div><div class="value"><?= $totalSent ?></div></div>
    <div class="stat-card reveal"><div class="label">Active Campaigns</div><div class="value"><?= count(array_filter($campaigns, function($c) { return $c['status'] !== 'sent'; })) ?></div></div>
</div>
<div class="table-section reveal">
    <div class="header"><h3>Campaigns (<?= count($campaigns) ?>)</h3></div>
    <table>
        <thead><tr><th>Name</th><th>Subject</th><th>Status</th><th>Recipients</th><th>Sent</th><th>Created</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($campaigns)): ?>
                <tr><td colspan="7" style="text-align: center; color: rgba(245,240,235,0.3);">No campaigns found</td></tr>
            <?php else: ?>
                <?php foreach ($campaigns as $c): ?>
                    <tr>
                        <td><?= sanitize($c['name']) ?></td>
                        <td><?= sanitize($c['subject']) ?></td>
                        <td><span class="status <?= $c['status'] ?>"><?= ucfirst($c['status']) ?></span></td>
                        <td><?= $c['total_recipients'] ?? 0 ?></td>
                        <td><?= $c['total_sent'] ?? 0 ?></td>
                        <td><?= formatDate($c['created_at']) ?></td>
                        <td>
                            <a href="/emails/<?= $c['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a>
                            <?php if ($c['status'] === 'draft'): ?><a href="/emails/<?= $c['id'] ?>/send" style="color: #10b981; margin-left: 0.5rem;"><i class="fas fa-paper-plane"></i></a><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; } .stat-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.5rem 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .stat-card .label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; color: rgba(245,240,235,0.15); font-weight: 600; } .stat-card .value { font-size: 2.2rem; font-weight: 800; color: #f5f0eb; margin: 0.3rem 0 0.5rem; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.draft { color: #6b7280; background: rgba(107,114,128,0.04); } table .status.scheduled { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.sent { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.failed { color: #ef4444; background: rgba(239,68,68,0.04); }</style>
