<?php $pageTitle = 'Invoices'; ?>
<div class="topbar"><div class="greeting"><h1>Invoices</h1><p>Manage customer invoices</p></div>
    <a href="/invoices/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Create Invoice</a>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Invoices (<?= count($invoices) ?>)</h3>
        <select name="status" onchange="window.location='/invoices?status='+this.value" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
            <option value="">All Statuses</option>
            <option value="draft" <?= $statusFilter === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="sent" <?= $statusFilter === 'sent' ? 'selected' : '' ?>>Sent</option>
            <option value="paid" <?= $statusFilter === 'paid' ? 'selected' : '' ?>>Paid</option>
            <option value="partial" <?= $statusFilter === 'partial' ? 'selected' : '' ?>>Partial</option>
            <option value="overdue" <?= $statusFilter === 'overdue' ? 'selected' : '' ?>>Overdue</option>
        </select>
    </div>
    <table>
        <thead><tr><th>Invoice #</th><th>Customer</th><th>Issue Date</th><th>Due Date</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($invoices)): ?>
                <tr><td colspan="7" style="text-align: center; color: rgba(245,240,235,0.3);">No invoices found</td></tr>
            <?php else: ?>
                <?php foreach ($invoices as $inv): ?>
                    <tr>
                        <td><?= sanitize($inv['invoice_number']) ?></td>
                        <td><?= sanitize($inv['first_name'] . ' ' . $inv['last_name']) ?><br><small style="color: rgba(245,240,235,0.3);"><?= sanitize($inv['company'] ?? '') ?></small></td>
                        <td><?= formatDate($inv['issue_date']) ?></td>
                        <td><?= formatDate($inv['due_date']) ?></td>
                        <td><?= formatCurrency($inv['total']) ?></td>
                        <td><span class="status <?= $inv['status'] ?>"><?= ucfirst($inv['status']) ?></span></td>
                        <td>
                            <a href="/invoices/<?= $inv['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a>
                            <a href="/invoices/<?= $inv['id'] ?>/edit" style="color: #f59e0b; margin-left: 0.5rem;"><i class="fas fa-edit"></i></a>
                            <a href="/invoices/<?= $inv['id'] ?>/pdf" target="_blank" style="color: #10b981; margin-left: 0.5rem;"><i class="fas fa-print"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.draft { color: #6b7280; background: rgba(107,114,128,0.04); } table .status.sent { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.paid { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.partial { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.overdue { color: #ef4444; background: rgba(239,68,68,0.04); }</style>
