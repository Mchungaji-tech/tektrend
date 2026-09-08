<?php $pageTitle = 'Register User'; ?>
<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-code"></i> Tek Trend</div>
        <p>Register New User</p>
    </div>

    <?php if (flash('error')): ?>
        <div class="flash-message"><?= sanitize(flash('error')) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= eurl('/register') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label for="department_id">Department</label>
            <select id="department_id" name="department_id" class="form-control">
                <option value="">Select Department</option>
                <?php foreach (($departments ?? []) as $dept): ?>
                    <option value="<?= $dept['id'] ?>"><?= sanitize($dept['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="first_name" class="required">First Name</label>
                <input type="text" id="first_name" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="last_name" class="required">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="form-control" required>
            </div>
        </div>

        <div class="form-group">
            <label for="employee_id" class="required">Employee ID</label>
            <input type="text" id="employee_id" name="employee_id" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email" class="required">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>

        <div class="form-group">
            <label for="role" class="required">Role</label>
            <select id="role" name="role" class="form-control">
                <option value="employee">Employee</option>
                <option value="manager">Manager</option>
                <option value="admin">Admin</option>
                <option value="accountant">Accountant</option>
                <option value="sales">Sales</option>
                <option value="support">Support</option>
            </select>
        </div>

        <div class="form-group">
            <label for="password" class="required">Password</label>
            <input type="password" id="password" name="password" class="form-control" required minlength="8">
        </div>

        <div class="form-group">
            <label for="position">Position</label>
            <input type="text" id="position" name="position" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Register User
        </button>
    </form>

    <div class="auth-footer">
        <a href="<?= eurl('/login') ?>">Back to login</a>
    </div>
</div>
