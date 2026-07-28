<?php $pageTitle = 'Tasks'; ?>
<div class="topbar"><div class="greeting"><h1>Tasks</h1><p>Manage team tasks</p></div>
    <a href="/tasks/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Create Task</a>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Tasks (<?= count($tasks) ?>)</h3>
        <form method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search tasks..." value="<?= sanitize($search) ?>" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
            <select name="status" onchange="this.form.submit()" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
                <option value="">All Statuses</option>
                <option value="todo" <?= $statusFilter === 'todo' ? 'selected' : '' ?>>To Do</option>
                <option value="in_progress" <?= $statusFilter === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </form>
    </div>
    <table>
        <thead><tr><th>Task</th><th>Department</th><th>Priority</th><th>Status</th><th>Due Date</th><th>Progress</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($tasks)): ?>
                <tr><td colspan="7" style="text-align: center; color: rgba(245,240,235,0.3);">No tasks found</td></tr>
            <?php else: ?>
                <?php foreach ($tasks as $t): ?>
                    <tr>
                        <td><?= sanitize($t['title']) ?></td>
                        <td><?= sanitize($t['department_name'] ?? '-') ?></td>
                        <td><span class="status <?= $t['priority'] ?>"><?= ucfirst($t['priority']) ?></span></td>
                        <td><span class="status <?= $t['status'] ?>"><?= str_replace('_', ' ', $t['status']) ?></span></td>
                        <td><?= formatDate($t['due_date']) ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="flex: 1; background: rgba(245,240,235,0.02); border-radius: 10px; height: 6px; overflow: hidden;"><div style="height: 100%; background: #b8943c; width: <?= $t['progress'] ?>%;"></div></div>
                                <span style="font-size: 0.7rem;"><?= $t['progress'] ?>%</span>
                            </div>
                        </td>
                        <td><a href="/tasks/<?= $t['id'] ?>" style="color: #3b82f6;"><i class="fas fa-eye"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.high { color: #ef5350; background: rgba(239,83,80,0.04); } table .status.medium { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.low { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.urgent { color: #ef4444; background: rgba(239,68,68,0.04); } table .status.todo { color: #6b7280; background: rgba(107,114,128,0.04); } table .status.in_progress { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.completed { color: #4caf50; background: rgba(76,175,80,0.04); }</style>
