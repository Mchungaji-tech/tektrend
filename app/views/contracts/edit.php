<?php $pageTitle = 'Edit Contract ' . $contract['contract_number']; ?>
<div class="card" style="max-width: 860px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Contract: <?= sanitize($contract['contract_number']) ?></h3>
            <p class="card-subtitle"><?= sanitize($contract['title']) ?></p>
        </div>
        <a href="<?= eurl('/contracts/' . $contract['id']) ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> View Contract</a>
    </div>

    <form method="POST" action="<?= eurl('/contracts/' . $contract['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Contract Title</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($contract['title']) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Customer / Client</label>
                <select name="customer_id" class="form-control">
                    <option value="">-- Select Client --</option>
                    <?php foreach ($customers as $cu): ?>
                        <option value="<?= $cu['id'] ?>" <?= $contract['customer_id'] == $cu['id'] ? 'selected' : '' ?>><?= sanitize($cu['first_name'] . ' ' . $cu['last_name'] . ($cu['company'] ? ' (' . $cu['company'] . ')' : '')) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Contract Value (KSh)</label>
                <input type="number" step="1000" name="value" class="form-control" value="<?= $contract['value'] ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= $contract['start_date'] ?>" required>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= $contract['end_date'] ?>">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="draft" <?= $contract['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    <option value="sent" <?= $contract['status'] === 'sent' ? 'selected' : '' ?>>Sent</option>
                    <option value="signed" <?= $contract['status'] === 'signed' ? 'selected' : '' ?>>Signed</option>
                    <option value="active" <?= $contract['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="completed" <?= $contract['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="cancelled" <?= $contract['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Signatory Name</label>
            <input type="text" name="signed_by_name" class="form-control" value="<?= sanitize($contract['signed_by_name'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Contract Terms & Clauses</label>
            <textarea name="terms" class="form-control" style="min-height: 180px;"><?= sanitize($contract['terms'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/contracts/' . $contract['id']) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
