<?php $pageTitle = 'Contract #' . $contract['contract_number']; ?>
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h3 class="card-title"><?= sanitize($contract['title']) ?></h3>
            <p class="card-subtitle">Contract Ref: <strong><?= sanitize($contract['contract_number']) ?></strong> · Status: <span class="badge <?= sanitize($contract['status']) ?>"><?= ucfirst(sanitize($contract['status'])) ?></span></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/contracts/' . $contract['id'] . '/print') ?>" target="_blank" class="btn btn-primary"><i class="fas fa-print"></i> Print / Download Agreement</a>
            <a href="<?= eurl('/contracts/' . $contract['id'] . '/edit') ?>" class="btn btn-outline"><i class="fas fa-edit"></i> Edit</a>
            <a href="<?= eurl('/contracts') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Contracts</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 2rem;">
        <div>
            <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; color: var(--text-main);">Engagement Particulars</h4>
            <table style="width: 100%; font-size: 0.88rem;">
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted); width: 140px;">Client Organization:</td><td><strong style="color: var(--text-main);"><?= sanitize($contract['customer_company'] ?? ($contract['customer_first'] . ' ' . $contract['customer_last'])) ?></strong></td></tr>
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted);">Client Signatory:</td><td style="color: var(--text-main);"><?= sanitize($contract['signed_by_name'] ?? 'Pending') ?></td></tr>
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted);">Email:</td><td style="color: var(--text-main);"><?= sanitize($contract['customer_email'] ?? '-') ?></td></tr>
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted);">Contract Value:</td><td><strong style="color: var(--primary); font-size: 1.1rem;">$<?= number_format($contract['value'], 2) ?></strong></td></tr>
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted);">Start Date:</td><td><strong style="color: var(--text-main);"><?= formatDate($contract['start_date']) ?></strong></td></tr>
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted);">End Date:</td><td style="color: var(--text-main);"><?= formatDate($contract['end_date']) ?></td></tr>
                <tr><td style="padding: 0.45rem 0; color: var(--text-muted);">Created On:</td><td style="color: var(--text-muted);"><?= formatDate($contract['created_at']) ?></td></tr>
            </table>
        </div>

        <div>
            <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; color: var(--text-main);">Terms & Deliverables Scope</h4>
            <div style="background: var(--bg-card-subtle); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid var(--border); font-size: 0.9rem; line-height: 1.6; color: var(--text-main); white-space: pre-line;">
                <?= sanitize($contract['terms'] ?? 'No specific terms recorded.') ?>
            </div>
        </div>
    </div>
</div>
