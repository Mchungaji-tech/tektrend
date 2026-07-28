<?php $pageTitle = 'Employees'; ?>
<div class="topbar"><div class="greeting"><h1>Employees</h1><p>Manage company employees</p></div>
    <a href="/employees/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Employee</a>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Employees (<?= count($employees) ?>)</h3>
        <input type="text" name="search" placeholder="Search employees..." value="<?= sanitize($search) ?>" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
    </div>
    <table>
        <thead><tr><th>Name</th><th>Employee ID</th><th>Email</th><th>Phone</th><th>Department</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($employees)): ?>
                <tr><td colspan="8" style="text-align: center; color: rgba(245,240,235,0.3);">No employees found</td></tr>
            <?php else: ?>
                <?php foreach ($employees as $e): ?>
                    <tr>
                        <td><?= sanitize($e['first_name'] . ' ' . $e['last_name']) ?></td>
                        <td><?= sanitize($e['employee_id'] ?? '-') ?></td>
                        <td><?= sanitize($e['email'] ?? '-') ?></td>
                        <td><?= sanitize($e['phone'] ?? '-') ?></td>
                        <td><?= sanitize($e['department_name'] ?? '-') ?></td>
                        <td><span class="status <?= $e['role'] ?>"><?= ucfirst($e['role']) ?></span></td>
                        <td><span class="status <?= $e['status'] ?>"><?= ucfirst($e['status']) ?></span></td>
                        <td><a href="/employees/<?= $e['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.admin { color: #ef4444; background: rgba(239,68,68,0.04); } table .status.manager { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.employee { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.active { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.inactive { color: #ef5350; background: rgba(239,83,80,0.04); }</style>
