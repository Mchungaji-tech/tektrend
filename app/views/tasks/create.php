<?php $pageTitle = 'Create Task'; ?>
<div class="topbar"><div class="greeting"><h1>Create Task</h1><p>Create a new task</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Task Information</h3></div>
    <form method="POST" action="/tasks">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">Title</label><input type="text" name="title" class="form-control" required></div>
            <div class="form-group"><label>Priority</label><select name="priority" class="form-control">
                <option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="urgent">Urgent</option>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Status</label><select name="status" class="form-control">
                <option value="todo">To Do</option><option value="in_progress">In Progress</option><option value="completed">Completed</option>
            </select></div>
            <div class="form-group"><label>Department</label><select name="department_id" class="form-control">
                <option value="">Select Department</option>
                <?php foreach ($departments as $d): ?><option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option><?php endforeach; ?>
            </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Start Date</label><input type="date" name="start_date" class="form-control"></div>
            <div class="form-group"><label>Due Date</label><input type="date" name="due_date" class="form-control"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Estimated Hours</label><input type="number" step="0.5" name="estimated_hours" class="form-control" value="0"></div>
            <div class="form-group"><label>Progress (%)</label><input type="number" name="progress" class="form-control" value="0" min="0" max="100"></div>
        </div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
        <div class="form-group"><label>Assignees</label>
            <select name="assignees[]" class="form-control" multiple style="height: 100px;">
                <?php foreach ($users as $u): ?><option value="<?= $u['id'] ?>"><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></option><?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Task</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
