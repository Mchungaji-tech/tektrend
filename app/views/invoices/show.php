<?php $pageTitle = 'Invoice Details'; ?>
<div class="topbar"><div class="greeting"><h1>Invoice #<?= sanitize($invoice['invoice_number']) ?></h1><p>Invoice details and payments</p></div>
    <div class="actions">
        <a href="/invoices/<?= $invoice['id'] ?>/pdf" target="_blank" style="color: #10b981; background: rgba(16,185,129,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-print"></i> PDF</a>
        <a href="/invoices/<?= $invoice['id'] ?>/edit" style="color: #f59e0b; background: rgba(245,159,11,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-left: 0.5rem;"><i class="fas fa-edit"></i> Edit</a>
        <a href="/invoices" style="color: #6b7280; background: rgba(107,114,128,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-left: 0.5rem;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="chart-card reveal"><div class="header"><h3>Invoice Information</h3></div>
        <div style="margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Invoice #</span><span><?= sanitize($invoice['invoice_number']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Customer</span><span><?= sanitize($invoice['first_name'] . ' ' . $invoice['last_name']) ?><br><small style="color: rgba(245,240,235,0.3);"><?= sanitize($invoice['company'] ?? '') ?></small></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Issue Date</span><span><?= formatDate($invoice['issue_date']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Due Date</span><span><?= formatDate($invoice['due_date']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Subtotal</span><span><?= formatCurrency($invoice['subtotal']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Tax</span><span><?= formatCurrency($invoice['tax_amount']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Discount</span><span>- <?= formatCurrency($invoice['discount_amount']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-top: 1px solid rgba(245,240,235,0.03);"><span style="color: rgba(245,240,235,0.3); font-weight: 600;">Total</span><span style="font-weight: 600; font-size: 1.2rem;"><?= formatCurrency($invoice['total']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Status</span><span class="status <?= $invoice['status'] ?>"><?= ucfirst($invoice['status']) ?></span></div>
        </div>
    </div>
    <div class="chart-card reveal"><div class="header"><h3>Payments</h3></div>
        <div style="margin-top: 1rem;">
            <?php if (empty($payments)): ?>
                <p style="color: rgba(245,240,235,0.3);">No payments recorded</p>
            <?php else: ?>
                <?php foreach ($payments as $p): ?>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div style="display: flex; justify-content: space-between;"><span style="font-weight: 500;"><?= formatCurrency($p['amount']) ?></span><span style="font-size: 0.7rem; color: rgba(245,240,235,0.3);"><?= formatDate($p['paid_at']) ?></span></div>
                        <div style="font-size: 0.7rem; color: rgba(245,240,235,0.3);"><?= sanitize($p['payment_method'] ?? '-') ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="table-section reveal">
    <div class="header"><h3>Invoice Items</h3></div>
    <table>
        <thead><tr><th>Description</th><th>Quantity</th><th>Unit Price</th><th>Tax Rate</th><th>Line Total</th></tr></thead>
        <tbody>
            <?php if (empty($items)): ?>
                <tr><td colspan="5" style="text-align: center; color: rgba(245,240,235,0.3);">No items</td></tr>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= sanitize($item['description']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= formatCurrency($item['unit_price']) ?></td>
                        <td><?= $item['tax_rate'] ?>%</td>
                        <td><?= formatCurrency($item['line_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.draft { color: #6b7280; background: rgba(107,114,128,0.04); } table .status.sent { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.paid { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.partial { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.overdue { color: #ef4444; background: rgba(239,68,68,0.04); }</style>
