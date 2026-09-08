<?php $pageTitle = 'System Users'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-user-shield" style="color: var(--primary); margin-right: 0.5rem;"></i> System Users & Access
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage executive, managerial, and developer credentials and permissions</p>
        </div>
        <a href="<?= eurl('/users/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All System Accounts (<?= count($users) ?>)</h3>
            <p class="card-subtitle">Authenticated users with portal access</p>
        </div>
        <form method="GET" action="<?= eurl('/users') ?>" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search by name or email..." value="<?= sanitize($search ?? '') ?>" class="form-control" style="width: 240px;">
            <button type="submit" class="btn btn-outline"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Employee ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No users found matching your search.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                            </td>
                            <td><span style="color: var(--text-muted); font-family: monospace; font-size: 0.82rem;"><?= sanitize($u['employee_id'] ?? '-') ?></span></td>
                            <td style="color: var(--text-main);"><?= sanitize($u['email'] ?? '-') ?></td>
                            <td style="color: var(--text-muted);"><?= sanitize($u['phone'] ?? '-') ?></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($u['department_name'] ?? 'General') ?></span></td>
                            <td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= ucfirst(sanitize($u['role'])) ?></span></td>
                            <td><span class="badge <?= sanitize($u['status']) ?>"><?= ucfirst(sanitize($u['status'])) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/users/' . $u['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit User">
                                    <i class="fas fa-edit" style="color: var(--accent);"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
