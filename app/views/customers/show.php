<?php $pageTitle = 'Customer Profile'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">
                    <?= sanitize($customer['first_name'] . ' ' . $customer['last_name']) ?>
                </h2>
                <span class="badge <?= sanitize($customer['status'] ?? 'active') ?>"><?= ucfirst(sanitize($customer['status'] ?? 'active')) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Company: <strong><?= sanitize($customer['company'] ?? 'Independent Client') ?></strong> · <?= sanitize($customer['country'] ?? 'Kenya') ?></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/customers/' . $customer['id'] . '/edit') ?>" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
            <a href="<?= eurl('/invoices/create') ?>" class="btn btn-primary">
                <i class="fas fa-file-invoice-dollar"></i> Issue Invoice
            </a>
            <a href="<?= eurl('/customers') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> All Customers
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Customer Information</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Contact Name</span>
                <strong style="color: var(--text-main);"><?= sanitize($customer['first_name'] . ' ' . $customer['last_name']) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Company</span>
                <strong style="color: var(--text-main);"><?= sanitize($customer['company'] ?? '-') ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Email</span>
                <span style="color: var(--text-main);"><?= sanitize($customer['email'] ?? '-') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Phone</span>
                <span style="color: var(--text-main);"><?= sanitize($customer['phone'] ?? '-') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Tax PIN / VAT</span>
                <span style="color: var(--text-main); font-family: monospace;"><?= sanitize($customer['tax_id'] ?? '-') ?></span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Address</span>
                <span style="color: var(--text-main);"><?= sanitize($customer['address'] ?? '-') ?>, <?= sanitize($customer['city'] ?? '') ?> <?= sanitize($customer['country'] ?? '') ?></span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Financial Summary</h3>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Total Invoices Generated</span>
                <strong style="color: var(--text-main);"><?= count($invoices) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                <span style="color: var(--text-muted);">Total Lifetime Billed</span>
                <strong style="color: var(--primary); font-size: 1.1rem;"><?= formatCurrency(array_sum(array_column($invoices, 'total'))) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: var(--text-muted);">Account Standing</span>
                <span class="badge <?= sanitize($customer['status'] ?? 'active') ?>"><?= ucfirst(sanitize($customer['status'] ?? 'active')) ?></span>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Customer Invoices (<?= count($invoices) ?>)</h3>
        <a href="<?= eurl('/invoices/create') ?>" class="btn btn-sm btn-outline"><i class="fas fa-plus"></i> New Invoice</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices)): ?>
                    <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No invoices generated for this client yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td><strong style="color: var(--primary); font-family: monospace;"><?= sanitize($inv['invoice_number']) ?></strong></td>
                            <td style="color: var(--text-muted);"><?= formatDate($inv['issue_date']) ?></td>
                            <td style="color: var(--text-muted);"><?= formatDate($inv['due_date']) ?></td>
                            <td><strong style="color: var(--text-main);"><?= formatCurrency($inv['total']) ?></strong></td>
                            <td><span class="badge <?= sanitize($inv['status']) ?>"><?= ucfirst(sanitize($inv['status'])) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/invoices/' . $inv['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;"><i class="fas fa-eye" style="color: var(--primary);"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
