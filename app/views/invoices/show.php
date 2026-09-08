<?php $pageTitle = 'Invoice ' . sanitize($invoice['invoice_number']); ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">
                    Invoice #<?= sanitize($invoice['invoice_number']) ?>
                </h2>
                <span class="badge <?= sanitize($invoice['status']) ?>"><?= ucfirst(sanitize($invoice['status'])) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Created on <?= formatDate($invoice['created_at'] ?? $invoice['issue_date']) ?> · Due by <?= formatDate($invoice['due_date']) ?></p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?= eurl('/invoices/' . $invoice['id'] . '/pdf') ?>" target="_blank" class="btn btn-primary">
                <i class="fas fa-print"></i> Print / PDF View
            </a>
            <a href="<?= eurl('/invoices/' . $invoice['id'] . '/edit') ?>" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit Invoice
            </a>
            <a href="<?= eurl('/invoices') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Invoices
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Left Column: Details & Items -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Client Information</h3>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Billed To</label>
                    <div style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); margin-top: 0.25rem;">
                        <?= sanitize($invoice['first_name'] . ' ' . $invoice['last_name']) ?>
                    </div>
                    <?php if (!empty($invoice['company'])): ?>
                        <div style="font-weight: 600; color: var(--primary); font-size: 0.9rem;"><?= sanitize($invoice['company']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($invoice['email'])): ?>
                        <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.25rem;"><i class="fas fa-envelope"></i> <?= sanitize($invoice['email']) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($invoice['phone'])): ?>
                        <div style="color: var(--text-muted); font-size: 0.85rem;"><i class="fas fa-phone"></i> <?= sanitize($invoice['phone']) ?></div>
                    <?php endif; ?>
                </div>
                <div>
                    <label style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Billing Address</label>
                    <div style="color: var(--text-main); font-size: 0.9rem; margin-top: 0.25rem; line-height: 1.4;">
                        <?= !empty($invoice['address']) ? sanitize($invoice['address']) : 'Standard Client Account' ?><br>
                        <?= !empty($invoice['city']) ? sanitize($invoice['city'] . ', ' . ($invoice['country'] ?? '')) : '' ?>
                    </div>
                    <?php if (!empty($invoice['tax_id'])): ?>
                        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.35rem;">Tax / VAT PIN: <strong><?= sanitize($invoice['tax_id']) ?></strong></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Deliverables -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Scope Deliverables</h3>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th style="text-align: center;">Qty</th>
                            <th style="text-align: right;">Unit Price</th>
                            <th style="text-align: right;">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr>
                                <td>Standard System Architecture & Consulting Service</td>
                                <td style="text-align: center;">1</td>
                                <td style="text-align: right;"><?= formatCurrency($invoice['subtotal']) ?></td>
                                <td style="text-align: right; font-weight: 700; color: var(--text-main);"><?= formatCurrency($invoice['subtotal']) ?></td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <strong style="color: var(--text-main);"><?= sanitize($item['description']) ?></strong>
                                    </td>
                                    <td style="text-align: center; color: var(--text-muted);"><?= $item['quantity'] ?></td>
                                    <td style="text-align: right; color: var(--text-muted);"><?= formatCurrency($item['unit_price']) ?></td>
                                    <td style="text-align: right; font-weight: 700; color: var(--text-main);"><?= formatCurrency($item['line_total']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($invoice['notes'])): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Terms & Notes</h3>
                </div>
                <p style="color: var(--text-main); font-size: 0.9rem; line-height: 1.5; white-space: pre-line;"><?= sanitize($invoice['notes']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Column: Summary & Payment -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Invoice Summary</h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.9rem;">
                <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
                    <span>Subtotal</span>
                    <strong style="color: var(--text-main);"><?= formatCurrency($invoice['subtotal']) ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; color: var(--text-muted);">
                    <span>Tax Amount</span>
                    <strong style="color: var(--text-main);"><?= formatCurrency($invoice['tax_amount']) ?></strong>
                </div>
                <?php if ($invoice['discount_amount'] > 0): ?>
                    <div style="display: flex; justify-content: space-between; color: var(--success);">
                        <span>Discount</span>
                        <strong>- <?= formatCurrency($invoice['discount_amount']) ?></strong>
                    </div>
                <?php endif; ?>
                <div style="height: 1px; background: var(--border); margin: 0.25rem 0;"></div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: 800; font-size: 1.05rem; color: var(--text-main);">Total Amount</span>
                    <span style="font-weight: 800; font-size: 1.4rem; color: var(--primary);"><?= formatCurrency($invoice['total']) ?></span>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.25rem;">Payment Terms</div>
                <div style="font-weight: 600; color: var(--text-main); font-size: 0.88rem;">Net 14 Days from Issue Date</div>
            </div>
        </div>

        <!-- Accepted Payment Methods -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment Method (KSh)</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 0.85rem; font-size: 0.88rem;">
                <div style="padding: 0.9rem; background: var(--bg-card-subtle); border-radius: var(--radius-sm); border: 1px solid var(--border);">
                    <div style="font-weight: 800; color: var(--success); margin-bottom: 0.35rem; font-size: 0.95rem;">
                        <i class="fas fa-mobile-alt"></i> M-Pesa (Send Money)
                    </div>
                    <div style="color: var(--text-main); margin-bottom: 0.2rem;">Send Money to: <strong style="font-size: 1.05rem; color: var(--primary);">0707246273</strong></div>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">Recipient: <strong style="color: var(--text-main);">Timothy omondi /Tektrend innovations</strong></div>
                </div>
            </div>
        </div>

        <!-- Recorded Payments -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment History</h3>
            </div>
            <?php if (empty($payments)): ?>
                <p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 1rem 0;">
                    <i class="fas fa-clock" style="margin-right: 0.35rem;"></i> No payments recorded yet
                </p>
            <?php else: ?>
                <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                    <?php foreach ($payments as $p): ?>
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.6rem 0.75rem; border-radius: var(--radius-sm); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                            <div>
                                <strong style="color: var(--text-main); font-size: 0.9rem;"><?= formatCurrency($p['amount']) ?></strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= formatDate($p['paid_at']) ?> · <?= sanitize($p['payment_method'] ?? 'Bank') ?></div>
                            </div>
                            <span class="badge active"><i class="fas fa-check"></i> Recorded</span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
