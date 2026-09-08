<?php $pageTitle = 'Employees Directory'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-id-badge" style="color: var(--primary); margin-right: 0.5rem;"></i> Staff & Consultant Directory
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage company employees, department assignments, and roles</p>
        </div>
        <a href="<?= eurl('/employees/create') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Employee
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All Employees (<?= count($employees) ?>)</h3>
            <p class="card-subtitle">Active staff across all engineering & design departments</p>
        </div>
        <form method="GET" action="<?= eurl('/employees') ?>" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search staff..." value="<?= sanitize($search ?? '') ?>" class="form-control" style="width: 220px;">
            <button type="submit" class="btn btn-outline"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Staff Name</th>
                    <th>Employee ID</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($employees)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No employee records found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($employees as $e): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($e['first_name'] . ' ' . $e['last_name']) ?></strong>
                            </td>
                            <td><span style="font-family: monospace; font-size: 0.85rem; color: var(--text-muted);"><?= sanitize($e['employee_id'] ?? '-') ?></span></td>
                            <td style="color: var(--text-muted);"><?= sanitize($e['email'] ?? '-') ?></td>
                            <td style="color: var(--text-muted);"><?= sanitize($e['phone'] ?? '-') ?></td>
                            <td><span style="color: var(--text-main); font-weight: 600;"><?= sanitize($e['department_name'] ?? 'General') ?></span></td>
                            <td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= ucfirst(sanitize($e['role'])) ?></span></td>
                            <td><span class="badge <?= sanitize($e['status']) ?>"><?= ucfirst(sanitize($e['status'])) ?></span></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <a href="<?= eurl('/employees/' . $e['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--primary);"></i>
                                    </a>
                                    <a href="<?= eurl('/employees/' . $e['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit Staff">
                                        <i class="fas fa-edit" style="color: var(--accent);"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
