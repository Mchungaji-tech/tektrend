<?php $pageTitle = 'Customer Details'; ?>
<div class="topbar"><div class="greeting"><h1><?= sanitize($customer['first_name'] . ' ' . $customer['last_name']) ?></h1><p>Customer details and invoices</p></div>
    <div class="actions">
        <a href="/customers/<?= $customer['id'] ?>/edit" style="color: #f59e0b; background: rgba(245,159,11,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-edit"></i> Edit</a>
        <a href="/customers" style="color: #6b7280; background: rgba(107,114,128,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-left: 0.5rem;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="chart-card reveal"><div class="header"><h3>Customer Information</h3></div>
        <div style="margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Name</span><span><?= sanitize($customer['first_name'] . ' ' . $customer['last_name']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Company</span><span><?= sanitize($customer['company'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Email</span><span><?= sanitize($customer['email'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Phone</span><span><?= sanitize($customer['phone'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Tax ID</span><span><?= sanitize($customer['tax_id'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Country</span><span><?= sanitize($customer['country'] ?? '-') ?></span></div>
        </div>
    </div>
    <div class="chart-card reveal"><div class="header"><h3>Quick Stats</h3></div>
        <div style="margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Total Invoices</span><span><?= count($invoices) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Total Billed</span><span><?= formatCurrency(array_sum(array_column($invoices, 'total'))) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Status</span><span class="status <?= $customer['status'] ?>"><?= ucfirst($customer['status']) ?></span></div>
        </div>
    </div>
</div>
<div class="table-section reveal">
    <div class="header"><h3>Invoices (<?= count($invoices) ?>)</h3><a href="/invoices/create" class="action">New Invoice</a></div>
    <table>
        <thead><tr><th>Invoice #</th><th>Date</th><th>Due Date</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($invoices)): ?>
                <tr><td colspan="6" style="text-align: center; color: rgba(245,240,235,0.3);">No invoices yet</td></tr>
            <?php else: ?>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?= sanitize($inv['invoice_number']) ?></td>
                        <td><?= formatDate($inv['issue_date']) ?></td>
                        <td><?= formatDate($inv['due_date']) ?></td>
                        <td><?= formatCurrency($inv['total']) ?></td>
                        <td><span class="status <?= $inv['status'] ?>"><?= ucfirst($inv['status']) ?></span></td>
                        <td><a href="/invoices/<?= $inv['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .table-section .header .action { font-size: 0.7rem; color: #b8943c; cursor: pointer; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.draft { color: #6b7280; background: rgba(107,114,128,0.04); } table .status.sent { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.paid { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.partial { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.overdue { color: #ef4444; background: rgba(239,68,68,0.04); } table .status.active { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.inactive { color: #ef5350; background: rgba(239,83,80,0.04); }</style>
