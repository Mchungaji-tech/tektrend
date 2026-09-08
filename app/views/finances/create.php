<?php $pageTitle = 'Add Transaction'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Record Financial Transaction</h3>
            <p class="card-subtitle">Log income deposit, client payment, or vendor expense</p>
        </div>
        <a href="<?= eurl('/finances') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Transactions</a>
    </div>

    <form method="POST" action="<?= eurl('/finances') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Transaction Type</label>
                <select name="type" class="form-control" required>
                    <option value="income">Income (Deposit / Client Payment)</option>
                    <option value="expense">Expense (Operational / Vendor Cost)</option>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Transaction Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Milestone 1 Deposit - Apex Real Estate" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Amount (KSh)</label>
                <input type="number" step="0.01" name="amount" class="form-control" required placeholder="0.00">
            </div>
            <div class="form-group">
                <label class="required">Transaction Date</label>
                <input type="date" name="transaction_date" class="form-control" required value="<?= date('Y-m-d') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control" placeholder="e.g. Design Template Sales, Hosting, Consulting">
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <option value="">Select Department...</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Related Customer (Optional)</label>
                <select name="customer_id" class="form-control">
                    <option value="">None / External</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?><?= !empty($c['company']) ? ' (' . sanitize($c['company']) . ')' : '' ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control">
                    <option value="bank">Bank Wire Transfer</option>
                    <option value="mpesa">M-Pesa</option>
                    <option value="card">Credit / Debit Card</option>
                    <option value="cash">Cash / Cheque</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Reference / Transaction Code</label>
            <input type="text" name="reference_number" class="form-control" placeholder="e.g. WIRE-US-11204 or MPESA-QE7812">
        </div>

        <div class="form-group">
            <label>Notes & Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Additional audit details..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/finances') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Record Transaction</button>
        </div>
    </form>
</div>
