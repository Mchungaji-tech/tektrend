<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Invoice <?= sanitize($invoice['invoice_number']) ?></h3>
            <p class="card-subtitle">Issued <?= formatDate($invoice['issue_date']) ?> · Due <?= formatDate($invoice['due_date']) ?></p>
        </div>
        <div style="display:flex; align-items:center; gap:.75rem;">
            <span class="badge <?= sanitize($invoice['status']) ?>"><?= sanitize(ucfirst($invoice['status'])) ?></span>
            <a href="<?= eurl('/portal/invoices') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>

    <?php if (!empty($invoice['notes'])): ?>
        <p style="margin-bottom:1rem; color: var(--text-muted);"><?= nl2br(sanitize($invoice['notes'])) ?></p>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Description</th><th style="text-align:right;">Qty</th><th style="text-align:right;">Unit Price</th><th style="text-align:right;">Line Total</th></tr></thead>
            <tbody>
            <?php if (empty($items)): ?>
                <tr><td colspan="4"><div class="empty-state" style="padding:1.5rem;"><p>No line items.</p></div></td></tr>
            <?php else: ?>
                <?php foreach ($items as $it): ?>
                    <tr>
                        <td><?= sanitize($it['description']) ?></td>
                        <td style="text-align:right;"><?= rtrim(rtrim(number_format($it['quantity'], 2), '0'), '.') ?></td>
                        <td style="text-align:right;"><?= formatCurrency($it['unit_price']) ?></td>
                        <td style="text-align:right;"><?= formatCurrency($it['line_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
            <tfoot>
                <tr><td colspan="3" style="text-align:right;">Subtotal</td><td style="text-align:right;"><?= formatCurrency($invoice['subtotal']) ?></td></tr>
                <tr><td colspan="3" style="text-align:right;">Tax</td><td style="text-align:right;"><?= formatCurrency($invoice['tax_amount']) ?></td></tr>
                <?php if ((float)$invoice['discount_amount'] > 0): ?>
                    <tr><td colspan="3" style="text-align:right;">Discount</td><td style="text-align:right;">- <?= formatCurrency($invoice['discount_amount']) ?></td></tr>
                <?php endif; ?>
                <tr><td colspan="3" style="text-align:right;"><strong>Total</strong></td><td style="text-align:right;"><strong><?= formatCurrency($invoice['total']) ?></strong></td></tr>
            </tfoot>
        </table>
    </div>
</div>
