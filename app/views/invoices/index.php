<?php $pageTitle = 'Invoices & Billing'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-file-invoice-dollar" style="color: var(--primary); margin-right: 0.5rem;"></i> Invoices & Billing
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage client billing, payments, and financial accounts</p>
        </div>
        <div style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="<?= eurl('/invoices/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create Invoice
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All Invoices (<?= count($invoices) ?>)</h3>
            <p class="card-subtitle">Real-time status of issued invoices and receipts</p>
        </div>
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <select name="status" onchange="window.location='<?= eurl('/invoices') ?>?status='+this.value" class="form-control" style="width: auto; min-width: 160px;">
                <option value="">All Statuses</option>
                <option value="draft" <?= ($statusFilter ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="sent" <?= ($statusFilter ?? '') === 'sent' ? 'selected' : '' ?>>Sent</option>
                <option value="paid" <?= ($statusFilter ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="partial" <?= ($statusFilter ?? '') === 'partial' ? 'selected' : '' ?>>Partial</option>
                <option value="overdue" <?= ($statusFilter ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Issue Date</th>
                    <th>Due Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem 1rem; color: var(--text-muted);">
                            <i class="fas fa-receipt" style="font-size: 2rem; color: var(--text-light); margin-bottom: 0.75rem; display: block;"></i>
                            No invoices found. <a href="<?= eurl('/invoices/create') ?>" style="color: var(--primary); font-weight: 600;">Create your first invoice</a>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($invoices as $inv): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($inv['invoice_number']) ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-main);"><?= sanitize($inv['first_name'] . ' ' . $inv['last_name']) ?></div>
                                <?php if (!empty($inv['company'])): ?>
                                    <small style="color: var(--text-muted); font-size: 0.75rem;"><?= sanitize($inv['company']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td style="color: var(--text-muted);"><?= formatDate($inv['issue_date']) ?></td>
                            <td style="color: var(--text-muted);"><?= formatDate($inv['due_date']) ?></td>
                            <td>
                                <strong style="color: var(--text-main); font-size: 0.95rem;"><?= formatCurrency($inv['total']) ?></strong>
                            </td>
                            <td>
                                <span class="badge <?= sanitize($inv['status']) ?>"><?= ucfirst(sanitize($inv['status'])) ?></span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem;">
                                    <a href="<?= eurl('/invoices/' . $inv['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--primary);"></i>
                                    </a>
                                    <a href="<?= eurl('/invoices/' . $inv['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit">
                                        <i class="fas fa-edit" style="color: var(--accent);"></i>
                                    </a>
                                    <a href="<?= eurl('/invoices/' . $inv['id'] . '/pdf') ?>" target="_blank" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Print PDF">
                                        <i class="fas fa-print" style="color: var(--success);"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
