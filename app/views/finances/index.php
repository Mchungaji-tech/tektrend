<?php $pageTitle = 'Finances'; ?>
<div class="topbar"><div class="greeting"><h1>Finances</h1><p>Manage financial transactions</p></div>
    <a href="/finances/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Transaction</a>
</div>
<div class="stats-grid">
    <div class="stat-card reveal"><div class="label">Total Income</div><div class="value"><?= formatCurrency($income) ?></div></div>
    <div class="stat-card reveal"><div class="label">Total Expenses</div><div class="value"><?= formatCurrency($expenses) ?></div></div>
    <div class="stat-card reveal"><div class="label">Net Profit</div><div class="value"><?= formatCurrency($net) ?></div></div>
</div>
<div class="table-section reveal">
    <div class="header"><h3>Transactions</h3>
        <form method="GET" style="display: flex; gap: 0.5rem;">
            <select name="type" onchange="this.form.submit()" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;"><option value="">All Types</option><option value="income" <?= $typeFilter === 'income' ? 'selected' : '' ?>>Income</option><option value="expense" <?= $typeFilter === 'expense' ? 'selected' : '' ?>>Expense</option></select>
            <input type="date" name="date_from" value="<?= sanitize($dateFrom) ?>" onchange="this.form.submit()" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
            <input type="date" name="date_to" value="<?= sanitize($dateTo) ?>" onchange="this.form.submit()" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
        </form>
    </div>
    <table>
        <thead><tr><th>Date</th><th>Type</th><th>Category</th><th>Title</th><th>Department</th><th>Amount</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr><td colspan="7" style="text-align: center; color: rgba(245,240,235,0.3);">No transactions found</td></tr>
            <?php else: ?>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= formatDate($t['transaction_date']) ?></td>
                        <td><span class="status <?= $t['type'] ?>"><?= ucfirst($t['type']) ?></span></td>
                        <td><?= sanitize($t['category'] ?? '-') ?></td>
                        <td><?= sanitize($t['title']) ?></td>
                        <td><?= sanitize($t['department_name'] ?? '-') ?></td>
                        <td><?= formatCurrency($t['amount']) ?></td>
                        <td><a href="/finances/<?= $t['id'] ?>/edit" style="color: #f59e0b;"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; } .stat-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.5rem 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .stat-card .label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; color: rgba(245,240,235,0.15); font-weight: 600; } .stat-card .value { font-size: 2.2rem; font-weight: 800; color: #f5f0eb; margin: 0.3rem 0 0.5rem; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.income { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.expense { color: #ef5350; background: rgba(239,83,80,0.04); }</style>
