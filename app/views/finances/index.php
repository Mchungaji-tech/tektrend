<?php $pageTitle = 'Financial Transactions'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-wallet" style="color: var(--primary); margin-right: 0.5rem;"></i> Financial Accounting & Ledger
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Audit revenue transactions, operations expenditure, and net fiscal profit</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?= eurl('/finances/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Transaction
            </a>
            <a href="<?= eurl('/budgets') ?>" class="btn btn-outline">
                <i class="fas fa-chart-line"></i> Budgets
            </a>
            <a href="<?= eurl('/taxes') ?>" class="btn btn-secondary">
                <i class="fas fa-calculator"></i> Tax Rates
            </a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--success-light); color: var(--success);">
            <i class="fas fa-arrow-down"></i>
        </div>
        <div class="label">Total Inflow / Income</div>
        <div class="value" style="color: var(--success);"><?= formatCurrency($income) ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Verified client deposits & fees</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div class="label">Total Operational Expenses</div>
        <div class="value" style="color: var(--danger);"><?= formatCurrency($expenses) ?></div>
        <div class="footer-text" style="color: var(--text-muted);">Hosting, software licenses, ops</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);">
            <i class="fas fa-scale-balanced"></i>
        </div>
        <div class="label">Net Fiscal Balance</div>
        <div class="value" style="color: var(--text-main);"><?= formatCurrency($net) ?></div>
        <div class="footer-text" style="color: var(--success); font-weight: 700;">Healthy cash reserves</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Transaction History (<?= count($transactions) ?>)</h3>
            <p class="card-subtitle">Detailed ledger entries and payment methods</p>
        </div>
        <form method="GET" action="<?= eurl('/finances') ?>" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <select name="type" onchange="this.form.submit()" class="form-control" style="width: auto;">
                <option value="">All Types</option>
                <option value="income" <?= ($typeFilter ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                <option value="expense" <?= ($typeFilter ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
            </select>
            <input type="date" name="date_from" value="<?= sanitize($dateFrom ?? '') ?>" onchange="this.form.submit()" class="form-control" style="width: auto;">
            <input type="date" name="date_to" value="<?= sanitize($dateTo ?? '') ?>" onchange="this.form.submit()" class="form-control" style="width: auto;">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Category</th>
                    <th>Title & Reference</th>
                    <th>Department</th>
                    <th>Amount</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No transactions recorded matching the selected timeframe.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td style="color: var(--text-muted);"><?= formatDate($t['transaction_date']) ?></td>
                            <td>
                                <span class="badge <?= $t['type'] === 'income' ? 'active' : 'inactive' ?>">
                                    <?= ucfirst(sanitize($t['type'])) ?>
                                </span>
                            </td>
                            <td><strong style="color: var(--text-main); font-size: 0.85rem;"><?= sanitize($t['category'] ?? 'General') ?></strong></td>
                            <td>
                                <div style="color: var(--text-main); font-weight: 600;"><?= sanitize($t['title']) ?></div>
                                <?php if (!empty($t['reference_number'])): ?>
                                    <small style="color: var(--text-muted); font-size: 0.75rem;">Ref: <?= sanitize($t['reference_number']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><span style="color: var(--text-main);"><?= sanitize($t['department_name'] ?? 'Corporate') ?></span></td>
                            <td>
                                <strong style="color: <?= $t['type'] === 'income' ? 'var(--success)' : 'var(--danger)' ?>; font-size: 0.95rem;">
                                    <?= $t['type'] === 'income' ? '+' : '-' ?><?= formatCurrency($t['amount']) ?>
                                </strong>
                            </td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/finances/' . $t['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit">
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
