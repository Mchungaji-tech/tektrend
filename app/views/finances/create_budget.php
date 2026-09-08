<?php $pageTitle = 'Create Budget'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Establish Departmental Budget</h3>
            <p class="card-subtitle">Set planned expenditure limits and monitoring periods</p>
        </div>
        <a href="<?= eurl('/budgets') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Budgets</a>
    </div>

    <form method="POST" action="<?= eurl('/budgets') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Budget Title</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. Cloud AI & Dedicated Server Cluster Q1" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Category</label>
                <input type="text" name="category" class="form-control" placeholder="Technology, Marketing, Infrastructure...">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Planned Allocation (KSh)</label>
                <input type="number" step="1000" name="planned_amount" class="form-control" placeholder="150000" required>
            </div>
            <div class="form-group">
                <label>Period</label>
                <select name="period" class="form-control">
                    <option value="monthly">Monthly</option>
                    <option value="quarterly" selected>Quarterly</option>
                    <option value="annual">Annual</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label class="required">End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d', strtotime('+90 days')) ?>" required>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/budgets') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Save Budget</button>
        </div>
    </form>
</div>
