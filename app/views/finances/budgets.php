<?php $pageTitle = 'Budgets'; ?>
<div class="topbar"><div class="greeting"><h1>Budgets</h1><p>Manage department budgets</p></div>
    <a href="/budgets/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Create Budget</a>
</div>
<div class="stats-grid">
    <div class="stat-card reveal"><div class="label">Total Planned</div><div class="value"><?= formatCurrency($totalPlanned) ?></div></div>
    <div class="stat-card reveal"><div class="label">Total Spent</div><div class="value"><?= formatCurrency($totalSpent) ?></div></div>
    <div class="stat-card reveal"><div class="label">Remaining</div><div class="value"><?= formatCurrency($totalPlanned - $totalSpent) ?></div></div>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Budgets (<?= count($budgets) ?>)</h3></div>
    <table>
        <thead><tr><th>Name</th><th>Department</th><th>Category</th><th>Planned</th><th>Spent</th><th>Remaining</th><th>Period</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($budgets)): ?>
                <tr><td colspan="9" style="text-align: center; color: rgba(245,240,235,0.3);">No budgets found</td></tr>
            <?php else: ?>
                <?php foreach ($budgets as $b): ?>
                    <tr>
                        <td><?= sanitize($b['name']) ?></td>
                        <td><?= sanitize($b['department_name'] ?? '-') ?></td>
                        <td><?= sanitize($b['category'] ?? '-') ?></td>
                        <td><?= formatCurrency($b['planned_amount']) ?></td>
                        <td><?= formatCurrency($b['spent_amount']) ?></td>
                        <td><?= formatCurrency($b['planned_amount'] - $b['spent_amount']) ?></td>
                        <td><?= ucfirst($b['period']) ?></td>
                        <td><span class="status <?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                        <td><a href="/budgets/<?= $b['id'] ?>/edit" style="color: #f59e0b;"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; } .stat-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.5rem 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .stat-card .label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; color: rgba(245,240,235,0.15); font-weight: 600; } .stat-card .value { font-size: 2.2rem; font-weight: 800; color: #f5f0eb; margin: 0.3rem 0 0.5rem; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.active { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.inactive { color: #ef5350; background: rgba(239,83,80,0.04); }</style>
