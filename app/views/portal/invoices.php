<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">My Invoices</h3>
            <p class="card-subtitle">All billing documents associated with your account</p>
        </div>
    </div>
    <?php if (empty($invoices)): ?>
        <div class="empty-state"><i class="fas fa-file-invoice"></i><p>You have no invoices yet.</p></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Invoice</th><th>Issued</th><th>Due</th><th>Subtotal</th><th>Tax</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><strong><?= sanitize($inv['invoice_number']) ?></strong></td>
                        <td><?= formatDate($inv['issue_date']) ?></td>
                        <td><?= formatDate($inv['due_date']) ?></td>
                        <td><?= formatCurrency($inv['subtotal']) ?></td>
                        <td><?= formatCurrency($inv['tax_amount']) ?></td>
                        <td><strong><?= formatCurrency($inv['total']) ?></strong></td>
                        <td><span class="badge <?= sanitize($inv['status']) ?>"><?= sanitize(ucfirst($inv['status'])) ?></span></td>
                        <td style="text-align: right;"><a href="<?= eurl('/portal/invoices/' . $inv['id']) ?>" class="btn btn-secondary">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
