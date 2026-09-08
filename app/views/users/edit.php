<?php $pageTitle = 'Edit User'; ?>

<div class="card" style="max-width: 750px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit User: <?= sanitize($user['first_name'] . ' ' . $user['last_name']) ?></h3>
            <p class="card-subtitle">Update role, department and system status</p>
        </div>
        <a href="<?= eurl('/users') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> System Users</a>
    </div>

    <form method="POST" action="<?= eurl('/users/' . $user['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>First Name *</label>
                <input type="text" name="first_name" class="form-control" value="<?= sanitize($user['first_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Last Name *</label>
                <input type="text" name="last_name" class="form-control" value="<?= sanitize($user['last_name']) ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" class="form-control" value="<?= sanitize($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>New Password (Leave blank to keep current)</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="manager" <?= $user['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="sales" <?= $user['role'] === 'sales' ? 'selected' : '' ?>>Sales</option>
                    <option value="accountant" <?= $user['role'] === 'accountant' ? 'selected' : '' ?>>Accountant</option>
                    <option value="employee" <?= $user['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
                </select>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= $user['department_id'] == $d['id'] ? 'selected' : '' ?>><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/users') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
