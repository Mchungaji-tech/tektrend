<?php $pageTitle = 'Department Budgets'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-chart-pie" style="color: var(--primary); margin-right: 0.5rem;"></i> Department Budgets & Allocation
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage operational limits, capital expenditure, and sprint allocations</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/budgets/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Budget
            </a>
            <a href="<?= eurl('/finances') ?>" class="btn btn-outline">
                <i class="fas fa-wallet"></i> Transactions
            </a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="label">Total Planned Budget</div>
        <div class="value" style="color: var(--text-main);"><?= formatCurrency($totalPlanned) ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Approved fiscal cap</div>
    </div>
    <div class="stat-card">
        <div class="label">Total Amount Spent</div>
        <div class="value" style="color: var(--danger);"><?= formatCurrency($totalSpent) ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Utilized operational funds</div>
    </div>
    <div class="stat-card">
        <div class="label">Remaining Balance</div>
        <div class="value" style="color: var(--success);"><?= formatCurrency($totalPlanned - $totalSpent) ?></div>
        <div class="footer-text" style="color: var(--success); font-weight: 700;">Available to allocate</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Department Budgets (<?= count($budgets) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Budget Name</th>
                    <th>Department</th>
                    <th>Category</th>
                    <th>Planned</th>
                    <th>Spent</th>
                    <th>Remaining</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($budgets)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No budgets configured yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($budgets as $b): ?>
                        <tr>
                            <td><strong style="color: var(--text-main);"><?= sanitize($b['name']) ?></strong></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($b['department_name'] ?? 'General') ?></span></td>
                            <td><span style="color: var(--text-muted);"><?= sanitize($b['category'] ?? '-') ?></span></td>
                            <td><strong style="color: var(--text-main);"><?= formatCurrency($b['planned_amount']) ?></strong></td>
                            <td><strong style="color: var(--danger);"><?= formatCurrency($b['spent_amount']) ?></strong></td>
                            <td><strong style="color: var(--success);"><?= formatCurrency($b['planned_amount'] - $b['spent_amount']) ?></strong></td>
                            <td><span style="color: var(--text-muted); font-size: 0.85rem;"><?= ucfirst(sanitize($b['period'])) ?></span></td>
                            <td><span class="badge <?= sanitize($b['status']) ?>"><?= ucfirst(sanitize($b['status'])) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/budgets/' . $b['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit">
                                    <i class="fas fa-edit" style="color: var(--accent);"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
