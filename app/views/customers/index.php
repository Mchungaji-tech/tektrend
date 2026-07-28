<?php $pageTitle = 'Customers'; ?>
<div class="topbar"><div class="greeting"><h1>Customers</h1><p>Manage customer records</p></div>
    <a href="/customers/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Customer</a>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Customers (<?= count($customers) ?>)</h3>
        <input type="text" name="search" placeholder="Search customers..." value="<?= sanitize($search) ?>" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
    </div>
    <table>
        <thead><tr><th>Name</th><th>Company</th><th>Email</th><th>Phone</th><th>Country</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($customers)): ?>
                <tr><td colspan="7" style="text-align: center; color: rgba(245,240,235,0.3);">No customers found</td></tr>
            <?php else: ?>
                <?php foreach ($customers as $c): ?>
                    <tr>
                        <td><?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?></td>
                        <td><?= sanitize($c['company'] ?? '-') ?></td>
                        <td><?= sanitize($c['email'] ?? '-') ?></td>
                        <td><?= sanitize($c['phone'] ?? '-') ?></td>
                        <td><?= sanitize($c['country'] ?? '-') ?></td>
                        <td><span class="status <?= $c['status'] ?>"><?= ucfirst($c['status']) ?></span></td>
                        <td>
                            <a href="/customers/<?= $c['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a>
                            <a href="/customers/<?= $c['id'] ?>/edit" style="color: #f59e0b; margin-left: 0.5rem;"><i class="fas fa-edit"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.active { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.inactive { color: #ef5350; background: rgba(239,83,80,0.04); }</style>
