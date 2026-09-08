<?php $pageTitle = 'Edit Transaction'; ?>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Financial Transaction</h3>
            <p class="card-subtitle">Ref: <?= sanitize($transaction['reference_number'] ?? 'TXN-' . $transaction['id']) ?></p>
        </div>
        <a href="<?= eurl('/finances') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Finances</a>
    </div>

    <form method="POST" action="<?= eurl('/finances/' . $transaction['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Transaction Title</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($transaction['title']) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="income" <?= $transaction['type'] === 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Expense</option>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control" value="<?= sanitize($transaction['category']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Amount (KSh)</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="<?= $transaction['amount'] ?>" required>
            </div>
            <div class="form-group">
                <label class="required">Transaction Date</label>
                <input type="date" name="transaction_date" class="form-control" value="<?= $transaction['transaction_date'] ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Description / Reference Notes</label>
            <textarea name="description" class="form-control" rows="3"><?= sanitize($transaction['description']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/finances') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
