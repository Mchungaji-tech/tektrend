<?php $pageTitle = 'Add Transaction'; ?>
<div class="topbar"><div class="greeting"><h1>Add Transaction</h1><p>Create a new financial transaction</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Transaction Information</h3></div>
    <form method="POST" action="/finances">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">Type</label><select name="type" class="form-control" required>
                <option value="income">Income</option><option value="expense">Expense</option>
            </select></div>
            <div class="form-group"><label class="required">Title</label><input type="text" name="title" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="required">Amount</label><input type="number" step="0.01" name="amount" class="form-control" required></div>
            <div class="form-group"><label class="required">Date</label><input type="date" name="transaction_date" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Category</label><input type="text" name="category" class="form-control"></div>
            <div class="form-group"><label>Department</label><select name="department_id" class="form-control">
                <option value="">Select Department</option>
                <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option><?php endforeach; ?>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Customer</label><select name="customer_id" class="form-control">
                <option value="">Select Customer</option>
                <?php foreach ($customers as $c): ?><option value="<?= $c['id'] ?>"><?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?></option><?php endforeach; ?>
            </select></div>
            <div class="form-group"><label>Payment Method</label><select name="payment_method" class="form-control">
                <option value="bank">Bank</option><option value="cash">Cash</option><option value="card">Card</option><option value="transfer">Transfer</option>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Tax Amount</label><input type="number" step="0.01" name="tax_amount" class="form-control" value="0"></div>
            <div class="form-group"><label>Tax Rate</label><select name="tax_rate_id" class="form-control">
                <option value="">Select Tax Rate</option>
                <?php foreach ($taxRates as $t): ?><option value="<?= $t['id'] ?>"><?= sanitize($t['name']) ?> (<?= $t['rate'] ?>%)</option><?php endforeach; ?>
            </select></div>
        </div>
        <div class="form-group"><label>Reference</label><input type="text" name="reference" class="form-control"></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Transaction</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
