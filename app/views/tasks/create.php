<?php $pageTitle = 'Create Task'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Create Task Deliverable</h3>
            <p class="card-subtitle">Assign a milestone or task to team members</p>
        </div>
        <a href="<?= eurl('/tasks') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Tasks</a>
    </div>

    <form method="POST" action="<?= eurl('/tasks') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Task Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Implement Virtual Office Webhook Integration" required>
        </div>

        <div class="form-group">
            <label>Task Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Detail the expected scope, deliverables, and acceptance criteria..."></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <option value="">None / General</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+7 days')) ?>">
            </div>
            <div class="form-group">
                <label>Initial Progress (%)</label>
                <input type="number" name="progress" min="0" max="100" class="form-control" value="0">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/tasks') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Task</button>
        </div>
    </form>
</div>
