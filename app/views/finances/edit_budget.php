<?php $pageTitle = 'Edit Budget'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Budget: <?= sanitize($budget['name']) ?></h3>
            <p class="card-subtitle">Adjust allocation limits and periods</p>
        </div>
        <a href="<?= eurl('/budgets') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Budgets</a>
    </div>

    <form method="POST" action="<?= eurl('/budgets/' . $budget['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Budget Title</label>
            <input type="text" name="name" class="form-control" value="<?= sanitize($budget['name']) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= $budget['department_id'] == $d['id'] ? 'selected' : '' ?>><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control" value="<?= sanitize($budget['category']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Planned Allocation (KSh)</label>
                <input type="number" step="1000" name="planned_amount" class="form-control" value="<?= $budget['planned_amount'] ?>" required>
            </div>
            <div class="form-group">
                <label>Period</label>
                <select name="period" class="form-control">
                    <option value="monthly" <?= $budget['period'] === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                    <option value="quarterly" <?= $budget['period'] === 'quarterly' ? 'selected' : '' ?>>Quarterly</option>
                    <option value="annual" <?= $budget['period'] === 'annual' ? 'selected' : '' ?>>Annual</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/budgets') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Budget</button>
        </div>
    </form>
</div>
