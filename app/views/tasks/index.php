<?php $pageTitle = 'Task Management'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-tasks" style="color: var(--primary); margin-right: 0.5rem;"></i> Task Management
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Track team assignments, sprint deliverables, and project progress</p>
        </div>
        <a href="<?= eurl('/tasks/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Task
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All Tasks (<?= count($tasks) ?>)</h3>
            <p class="card-subtitle">Active and completed sprint items</p>
        </div>
        <form method="GET" action="<?= eurl('/tasks') ?>" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Search tasks..." value="<?= sanitize($search ?? '') ?>" class="form-control" style="width: 200px;">
            <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto;">
                <option value="">All Statuses</option>
                <option value="todo" <?= ($statusFilter ?? '') === 'todo' ? 'selected' : '' ?>>To Do</option>
                <option value="in_progress" <?= ($statusFilter ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= ($statusFilter ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th>Department</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Due Date</th>
                    <th style="width: 160px;">Progress</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tasks)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No tasks found matching your filters.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tasks as $t): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($t['title']) ?></strong>
                            </td>
                            <td><span style="color: var(--text-main);"><?= sanitize($t['department_name'] ?? 'General') ?></span></td>
                            <td><span class="badge status <?= sanitize($t['priority']) ?>"><?= ucfirst(sanitize($t['priority'])) ?></span></td>
                            <td><span class="badge <?= sanitize($t['status']) ?>"><?= ucfirst(str_replace('_', ' ', sanitize($t['status']))) ?></span></td>
                            <td style="color: var(--text-muted);"><?= formatDate($t['due_date']) ?></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <div style="flex: 1; background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: 10px; height: 8px; overflow: hidden;">
                                        <div style="height: 100%; background: var(--primary); width: <?= (int)$t['progress'] ?>%;"></div>
                                    </div>
                                    <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-main);"><?= (int)$t['progress'] ?>%</span>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem;">
                                    <a href="<?= eurl('/tasks/' . $t['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--primary);"></i>
                                    </a>
                                    <a href="<?= eurl('/tasks/' . $t['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit Task">
                                        <i class="fas fa-edit" style="color: var(--accent);"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
