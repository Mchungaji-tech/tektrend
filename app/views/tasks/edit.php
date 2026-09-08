<?php $pageTitle = 'Edit Task'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Task: <?= sanitize($task['title']) ?></h3>
            <p class="card-subtitle">Update task milestone, assigned department and completion status</p>
        </div>
        <a href="<?= eurl('/tasks/' . $task['id']) ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> View Task</a>
    </div>

    <form method="POST" action="<?= eurl('/tasks/' . $task['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Task Title</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($task['title']) ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="4"><?= sanitize($task['description'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department</label>
                <select name="department_id" class="form-control">
                    <option value="">None / General</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>" <?= $task['department_id'] == $d['id'] ? 'selected' : '' ?>><?= sanitize($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Priority</label>
                <select name="priority" class="form-control">
                    <option value="low" <?= $task['priority'] === 'low' ? 'selected' : '' ?>>Low</option>
                    <option value="medium" <?= $task['priority'] === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="high" <?= $task['priority'] === 'high' ? 'selected' : '' ?>>High</option>
                    <option value="urgent" <?= $task['priority'] === 'urgent' ? 'selected' : '' ?>>Urgent</option>
                </select>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="todo" <?= $task['status'] === 'todo' ? 'selected' : '' ?>>To Do</option>
                    <option value="in_progress" <?= $task['status'] === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="review" <?= $task['status'] === 'review' ? 'selected' : '' ?>>Review</option>
                    <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="form-group">
                <label>Due Date</label>
                <input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>">
            </div>
            <div class="form-group">
                <label>Progress (%)</label>
                <input type="number" name="progress" min="0" max="100" class="form-control" value="<?= (int)$task['progress'] ?>">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/tasks/' . $task['id']) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
