<?php $pageTitle = 'Draft Contract'; ?>
<div class="card" style="max-width: 860px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Draft New Client Contract</h3>
            <p class="card-subtitle">Establish consulting scope, retainers, and legal obligations</p>
        </div>
        <a href="<?= eurl('/contracts') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Contracts</a>
    </div>

    <form method="POST" action="<?= eurl('/contracts') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Contract Title / Engagement Name</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Enterprise Technology & AI Architecture Advisory Retainer" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Associated Customer / Client</label>
                <select name="customer_id" class="form-control">
                    <option value="">-- Select Client --</option>
                    <?php foreach ($customers as $cu): ?>
                        <option value="<?= $cu['id'] ?>"><?= sanitize($cu['first_name'] . ' ' . $cu['last_name'] . ($cu['company'] ? ' (' . $cu['company'] . ')' : '')) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="required">Contract Total Value (KSh)</label>
                <input type="number" step="1000" name="value" class="form-control" placeholder="450000" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Effective Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label>End / Renewal Date</label>
                <input type="date" name="end_date" class="form-control" value="<?= date('Y-m-d', strtotime('+1 year')) ?>">
            </div>
            <div class="form-group">
                <label>Contract Status</label>
                <select name="status" class="form-control">
                    <option value="draft">Draft</option>
                    <option value="sent">Sent to Client</option>
                    <option value="signed">Signed & Executed</option>
                    <option value="active">Active</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Signatory Representative (Client Contact)</label>
            <input type="text" name="signed_by_name" class="form-control" placeholder="e.g. Marcus Chen (COO)">
        </div>

        <div class="form-group">
            <label>Contract Terms, Deliverables & Clauses</label>
            <textarea name="terms" class="form-control" style="min-height: 180px;" placeholder="1. Scope of Services...&#10;2. Payment Schedule & Retainer Milestones...&#10;3. Intellectual Property Rights...&#10;4. Confidentiality & Non-Disclosure..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/contracts') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-file-signature"></i> Create & Save Contract</button>
        </div>
    </form>
</div>
