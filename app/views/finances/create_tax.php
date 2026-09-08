<?php $pageTitle = 'Add Tax Rate'; ?>
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Configure Tax / Duty Rate</h3>
            <p class="card-subtitle">Set statutory tax rates for invoices and transactions</p>
        </div>
        <a href="<?= eurl('/taxes') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Tax Rates</a>
    </div>

    <form method="POST" action="<?= eurl('/taxes') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Tax Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. VAT Standard (Kenya)" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Tax Rate (%)</label>
                <input type="number" step="0.01" name="rate" class="form-control" placeholder="16.00" required>
            </div>
            <div class="form-group">
                <label>Type</label>
                <select name="type" class="form-control">
                    <option value="percentage">Percentage (%)</option>
                    <option value="fixed">Fixed Amount</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Jurisdiction / Country</label>
            <input type="text" name="country" class="form-control" placeholder="Kenya, USA, Singapore...">
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/taxes') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Save Tax Rate</button>
        </div>
    </form>
</div>
