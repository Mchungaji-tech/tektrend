<?php $pageTitle = 'Create Invoice'; ?>
<div class="topbar"><div class="greeting"><h1>Create Invoice</h1><p>Create a new customer invoice</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Invoice Information</h3></div>
    <form method="POST" action="/invoices" id="invoiceForm">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">Customer</label><select name="customer_id" class="form-control" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $c): ?><option value="<?= $c['id'] ?>"><?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?><?= $c['company'] ? ' (' . sanitize($c['company']) . ')' : '' ?></option><?php endforeach; ?>
            </select></div>
            <div class="form-group"><label class="required">Invoice Number</label><input type="text" name="invoice_number" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Issue Date</label><input type="date" name="issue_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
            <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Subtotal</label><input type="number" step="0.01" name="subtotal" class="form-control" value="0" id="subtotal"></div>
            <div class="form-group"><label>Tax Amount</label><input type="number" step="0.01" name="tax_amount" class="form-control" value="0" id="tax_amount"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Discount Amount</label><input type="number" step="0.01" name="discount_amount" class="form-control" value="0" id="discount_amount"></div>
            <div class="form-group"><label>Total</label><input type="number" step="0.01" name="total" class="form-control" value="0" id="total" readonly></div>
        </div>
        <div class="form-group"><label>Status</label><select name="status" class="form-control">
            <option value="draft">Draft</option><option value="sent">Sent</option><option value="paid">Paid</option>
        </select></div>
        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Invoice</button>
    </form>
</div>
<script>
document.getElementById('subtotal').addEventListener('input', calcTotal);
document.getElementById('tax_amount').addEventListener('input', calcTotal);
document.getElementById('discount_amount').addEventListener('input', calcTotal);
function calcTotal() {
    var s = parseFloat(document.getElementById('subtotal').value) || 0;
    var t = parseFloat(document.getElementById('tax_amount').value) || 0;
    var d = parseFloat(document.getElementById('discount_amount').value) || 0;
    document.getElementById('total').value = (s + t - d).toFixed(2);
}
</script>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
