<?php $pageTitle = 'Edit Invoice #' . sanitize($invoice['invoice_number']); ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-edit" style="color: var(--primary); margin-right: 0.5rem;"></i> Edit Invoice #<?= sanitize($invoice['invoice_number']) ?>
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Update deliverables, amounts in Kenyan Shillings (KSh), and payment status</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/invoices/' . $invoice['id']) ?>" class="btn btn-outline">
                <i class="fas fa-eye"></i> View Invoice
            </a>
            <a href="<?= eurl('/invoices') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Invoices
            </a>
        </div>
    </div>
</div>

<form method="POST" action="<?= eurl('/invoices/' . $invoice['id']) ?>" id="invoiceForm">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
        <!-- Left Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Customer info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Client Information</h3>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1; min-width: 240px;">
                        <label class="required">Customer</label>
                        <select name="customer_id" class="form-control" required>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $invoice['customer_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?><?= !empty($c['company']) ? ' (' . sanitize($c['company']) . ')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label class="required">Invoice Number</label>
                        <input type="text" name="invoice_number" class="form-control" value="<?= sanitize($invoice['invoice_number']) ?>" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Issue Date</label>
                        <input type="date" name="issue_date" class="form-control" value="<?= $invoice['issue_date'] ?>" required>
                    </div>
                    <div class="form-group" style="flex: 1; min-width: 200px;">
                        <label>Due Date</label>
                        <input type="date" name="due_date" class="form-control" value="<?= $invoice['due_date'] ?>" required>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Invoice Deliverables & Line Items</h3>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline" onclick="addLineItem()">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width: 45%;">Description</th>
                                <th style="width: 15%;">Qty</th>
                                <th style="width: 20%;">Unit Price (KSh)</th>
                                <th style="width: 15%;">Line Total (KSh)</th>
                                <th style="width: 5%;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody">
                            <?php if (!empty($items)): ?>
                                <?php foreach ($items as $idx => $item): ?>
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" name="items[<?= $idx ?>][description]" class="form-control item-desc" value="<?= sanitize($item['description']) ?>" required>
                                        </td>
                                        <td>
                                            <input type="number" step="1" min="1" name="items[<?= $idx ?>][quantity]" class="form-control item-qty" value="<?= $item['quantity'] ?>" oninput="calcTotals()" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="items[<?= $idx ?>][unit_price]" class="form-control item-price" value="<?= $item['unit_price'] ?>" oninput="calcTotals()" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="items[<?= $idx ?>][line_total]" class="form-control item-total" value="<?= $item['line_total'] ?>" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeLineItem(this)"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr class="item-row">
                                    <td>
                                        <input type="text" name="items[0][description]" class="form-control item-desc" value="Turnkey System Development & Setup" required>
                                    </td>
                                    <td>
                                        <input type="number" step="1" min="1" name="items[0][quantity]" class="form-control item-qty" value="1" oninput="calcTotals()" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="items[0][unit_price]" class="form-control item-price" value="<?= $invoice['subtotal'] ?>" oninput="calcTotals()" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" name="items[0][line_total]" class="form-control item-total" value="<?= $invoice['subtotal'] ?>" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeLineItem(this)"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Notes -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notes & Payment Terms</h3>
                </div>
                <div class="form-group">
                    <textarea name="notes" class="form-control" rows="3"><?= sanitize($invoice['notes'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Invoice Totals (KSh)</h3>
                </div>

                <div class="form-group">
                    <label>Subtotal (KSh)</label>
                    <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" value="<?= $invoice['subtotal'] ?>" readonly style="font-weight: 700;">
                </div>

                <div class="form-group">
                    <label>Tax Amount (KSh)</label>
                    <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" value="<?= $invoice['tax_amount'] ?>" oninput="calcTotals()">
                </div>

                <div class="form-group">
                    <label>Discount Amount (KSh)</label>
                    <input type="number" step="0.01" name="discount_amount" id="discount_amount" class="form-control" value="<?= $invoice['discount_amount'] ?>" oninput="calcTotals()">
                </div>

                <div class="form-group" style="background: var(--bg-card-subtle); padding: 1rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
                    <label style="font-size: 0.95rem; font-weight: 800; color: var(--text-main);">Grand Total (KSh)</label>
                    <input type="number" step="0.01" name="total" id="grandTotal" class="form-control" value="<?= $invoice['total'] ?>" readonly style="font-size: 1.3rem; font-weight: 800; color: var(--primary);">
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $invoice['status'] === 'draft' ? 'selected' : '' ?>>Draft (Unsent)</option>
                        <option value="sent" <?= $invoice['status'] === 'sent' ? 'selected' : '' ?>>Sent (Awaiting Payment)</option>
                        <option value="paid" <?= $invoice['status'] === 'paid' ? 'selected' : '' ?>>Paid (Fully Settled)</option>
                        <option value="overdue" <?= $invoice['status'] === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                        <option value="cancelled" <?= $invoice['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 0.95rem; margin-top: 0.5rem;">
                    <i class="fas fa-save"></i> Save Changes (KSh)
                </button>
            </div>
        </div>
    </div>
</form>

<script>
let itemIndex = <?= count($items ?? [1]) ?>;

function addLineItem() {
    const tbody = document.getElementById('itemsBody');
    const tr = document.createElement('tr');
    tr.className = 'item-row';
    tr.innerHTML = `
        <td>
            <input type="text" name="items[${itemIndex}][description]" class="form-control item-desc" placeholder="Scope description" required>
        </td>
        <td>
            <input type="number" step="1" min="1" name="items[${itemIndex}][quantity]" class="form-control item-qty" value="1" oninput="calcTotals()" required>
        </td>
        <td>
            <input type="number" step="0.01" min="0" name="items[${itemIndex}][unit_price]" class="form-control item-price" value="0.00" oninput="calcTotals()" required>
        </td>
        <td>
            <input type="number" step="0.01" name="items[${itemIndex}][line_total]" class="form-control item-total" value="0.00" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-danger" onclick="removeLineItem(this)"><i class="fas fa-trash"></i></button>
        </td>
    `;
    tbody.appendChild(tr);
    itemIndex++;
    calcTotals();
}

function removeLineItem(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('tr').remove();
        calcTotals();
    } else {
        alert('An invoice must contain at least one line item.');
    }
}

function calcTotals() {
    let subtotal = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const price = parseFloat(row.querySelector('.item-price').value) || 0;
        const lineTotal = qty * price;
        row.querySelector('.item-total').value = lineTotal.toFixed(2);
        subtotal += lineTotal;
    });

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
    const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const total = Math.max(0, subtotal + tax - discount);
    document.getElementById('grandTotal').value = total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', calcTotals);
</script>
