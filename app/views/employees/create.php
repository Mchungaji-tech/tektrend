<?php $pageTitle = 'Add Employee'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Add Employee Account</h3>
            <p class="card-subtitle">Create login credentials and assign departmental roles</p>
        </div>
        <a href="<?= eurl('/employees') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Employees</a>
    </div>

    <form method="POST" action="<?= eurl('/employees') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Employee ID</label>
                <input type="text" name="employee_id" class="form-control" placeholder="e.g. EMP-010" required>
            </div>
            <div class="form-group">
                <label class="required">Email Address</label>
                <input type="email" name="email" class="form-control" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Initial Password</label>
                <input type="password" name="password" class="form-control" required minlength="8" placeholder="••••••••">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <option value="">Select Department...</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="employee">Employee</option>
                    <option value="manager">Manager</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="form-group">
                <label>Position / Title</label>
                <input type="text" name="position" class="form-control" placeholder="Lead UI Architect">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/employees') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Save Employee</button>
        </div>
    </form>
</div>
