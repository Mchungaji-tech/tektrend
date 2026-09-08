<?php $pageTitle = 'Edit Employee'; ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Employee: <?= sanitize($employee['first_name'] . ' ' . $employee['last_name']) ?></h3>
            <p class="card-subtitle">Update department, position, contact and status</p>
        </div>
        <a href="<?= eurl('/employees/' . $employee['id']) ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> View Profile</a>
    </div>

    <form method="POST" action="<?= eurl('/employees/' . $employee['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?= sanitize($employee['first_name']) ?>" required>
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?= sanitize($employee['last_name']) ?>" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Work Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= sanitize($employee['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Phone / WhatsApp</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($employee['phone'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Position / Title</label>
                <input type="text" name="position" class="form-control" value="<?= sanitize($employee['position'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="admin" <?= $employee['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="manager" <?= $employee['role'] === 'manager' ? 'selected' : '' ?>>Manager</option>
                    <option value="sales" <?= $employee['role'] === 'sales' ? 'selected' : '' ?>>Sales</option>
                    <option value="accountant" <?= $employee['role'] === 'accountant' ? 'selected' : '' ?>>Accountant</option>
                    <option value="employee" <?= $employee['role'] === 'employee' ? 'selected' : '' ?>>Employee</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?= $employee['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $employee['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/employees/' . $employee['id']) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Employee Profile</button>
        </div>
    </form>
</div>
