<?php $pageTitle = 'Create Employee'; ?>
<div class="topbar"><div class="greeting"><h1>Create Employee</h1><p>Add a new employee</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Employee Information</h3></div>
    <form method="POST" action="/employees">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">First Name</label><input type="text" name="first_name" class="form-control" required></div>
            <div class="form-group"><label class="required">Last Name</label><input type="text" name="last_name" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="required">Employee ID</label><input type="text" name="employee_id" class="form-control" required></div>
            <div class="form-group"><label class="required">Email</label><input type="email" name="email" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="required">Password</label><input type="password" name="password" class="form-control" required minlength="8"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Department</label><select name="department_id" class="form-control">
                <option value="">Select Department</option>
                <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option><?php endforeach; ?>
            </select></div>
            <div class="form-group"><label>Role</label><select name="role" class="form-control">
                <option value="employee">Employee</option><option value="manager">Manager</option><option value="admin">Admin</option>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Position</label><input type="text" name="position" class="form-control"></div>
            <div class="form-group"><label>Status</label><select name="status" class="form-control">
                <option value="active">Active</option><option value="inactive">Inactive</option>
            </select></div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Employee</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
