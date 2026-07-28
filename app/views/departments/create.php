<?php $pageTitle = 'Create Department'; ?>
<div class="topbar"><div class="greeting"><h1>Create Department</h1><p>Add a new department</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Department Information</h3></div>
    <form method="POST" action="/departments">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label class="required">Slug</label><input type="text" name="slug" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Head</label><select name="head_id" class="form-control">
                <option value="">Select Head</option>
                <?php foreach ($heads as $h): ?><option value="<?= $h['id'] ?>"><?= sanitize($h['first_name'] . ' ' . $h['last_name']) ?></option><?php endforeach; ?>
            </select></div>
            <div class="form-group"><label>Color</label><input type="color" name="color" class="form-control" value="#3b82f6"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Status</label><select name="status" class="form-control">
                <option value="active">Active</option><option value="inactive">Inactive</option>
            </select></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Department</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
