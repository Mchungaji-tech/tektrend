<?php $pageTitle = 'Task Details'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.25rem;">
                <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">
                    <?= sanitize($task['title']) ?>
                </h2>
                <span class="badge <?= sanitize($task['status']) ?>"><?= ucfirst(str_replace('_', ' ', sanitize($task['status']))) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Created by <?= sanitize($task['first_name'] . ' ' . $task['last_name']) ?> · Due on <?= formatDate($task['due_date']) ?></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/tasks/' . $task['id'] . '/edit') ?>" class="btn btn-outline">
                <i class="fas fa-edit"></i> Edit Task
            </a>
            <a href="<?= eurl('/tasks') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> All Tasks
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Left Column: Task Overview & Comments -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Task Description & Deliverables</h3>
            </div>
            <div style="color: var(--text-main); font-size: 0.95rem; line-height: 1.6; white-space: pre-line;">
                <?= !empty($task['description']) ? sanitize($task['description']) : '<em>No detailed description provided.</em>' ?>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-main);">Sprint Completion Progress</span>
                    <span style="font-size: 0.85rem; font-weight: 800; color: var(--primary);"><?= (int)$task['progress'] ?>%</span>
                </div>
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: 9999px; height: 10px; overflow: hidden;">
                    <div style="height: 100%; background: var(--primary); width: <?= (int)$task['progress'] ?>%;"></div>
                </div>
            </div>
        </div>

        <!-- Task Discussion & Comments -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Comments & Activity Log</h3>
            </div>

            <form method="POST" action="<?= eurl('/tasks/' . $task['id'] . '/comment') ?>" style="margin-bottom: 1.5rem;">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <div class="form-group">
                    <textarea name="comment" class="form-control" rows="3" placeholder="Add an engineering note or milestone update..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-comment"></i> Post Update</button>
            </form>

            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                <?php if (empty($comments)): ?>
                    <p style="color: var(--text-muted); font-size: 0.85rem; text-align: center; padding: 1rem 0;">No comments posted yet.</p>
                <?php else: ?>
                    <?php foreach ($comments as $cm): ?>
                        <div style="padding: 0.85rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.35rem;">
                                <strong style="color: var(--text-main); font-size: 0.88rem;"><?= sanitize($cm['first_name'] . ' ' . $cm['last_name']) ?></strong>
                                <small style="color: var(--text-muted);"><?= timeAgo($cm['created_at']) ?></small>
                            </div>
                            <div style="color: var(--text-main); font-size: 0.88rem; line-height: 1.4;"><?= nl2br(sanitize($cm['comment'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Meta -->
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Task Attributes</h3>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.88rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">Priority</span>
                    <span class="badge status <?= sanitize($task['priority']) ?>"><?= ucfirst(sanitize($task['priority'])) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">Department</span>
                    <strong style="color: var(--text-main);"><?= sanitize($task['department_name'] ?? 'General') ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
                    <span style="color: var(--text-muted);">Due Date</span>
                    <strong style="color: var(--text-main);"><?= formatDate($task['due_date']) ?></strong>
                </div>
                <div style="display: flex; justify-content: space-between; padding-bottom: 0.25rem;">
                    <span style="color: var(--text-muted);">Created By</span>
                    <strong style="color: var(--text-main);"><?= sanitize($task['first_name'] . ' ' . $task['last_name']) ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>
