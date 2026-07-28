<?php $pageTitle = 'Task Details'; ?>
<div class="topbar"><div class="greeting"><h1>Task: <?= sanitize($task['title']) ?></h1><p>Task details and comments</p></div>
    <div class="actions">
        <a href="/tasks/<?= $task['id'] ?>/edit" style="color: #f59e0b; background: rgba(245,159,11,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-edit"></i> Edit</a>
        <a href="/tasks" style="color: #6b7280; background: rgba(107,114,128,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-left: 0.5rem;"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="chart-card reveal"><div class="header"><h3>Task Information</h3></div>
        <div style="margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Title</span><span><?= sanitize($task['title']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Priority</span><span class="status <?= $task['priority'] ?>"><?= ucfirst($task['priority']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Status</span><span class="status <?= $task['status'] ?>"><?= str_replace('_', ' ', $task['status']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Department</span><span><?= sanitize($task['department_name'] ?? '-') ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Due Date</span><span><?= formatDate($task['due_date']) ?></span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Progress</span><span><?= $task['progress'] ?>%</span></div>
            <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);"><span style="color: rgba(245,240,235,0.3);">Created By</span><span><?= sanitize($task['first_name'] . ' ' . $task['last_name']) ?></span></div>
        </div>
    </div>
    <div class="chart-card reveal"><div class="header"><h3>Assignees</h3></div>
        <div style="margin-top: 1rem;">
            <?php if (empty($assignees)): ?>
                <p style="color: rgba(245,240,235,0.3);">No assignees</p>
            <?php else: ?>
                <?php foreach ($assignees as $a): ?>
                    <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c;"><i class="fas fa-user"></i></div>
                        <span><?= sanitize($a['first_name'] . ' ' . $a['last_name']) ?></span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="chart-card reveal">
    <div class="header"><h3>Add Comment</h3></div>
    <form method="POST" action="/tasks/<?= $task['id'] ?>/comment" style="margin-top: 1rem;">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-group"><textarea name="comment" class="form-control" rows="3" placeholder="Add a comment..." required></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Add Comment</button>
    </form>
</div>
<div class="table-section reveal" style="margin-top: 1.5rem;">
    <div class="header"><h3>Comments (<?= count($comments) ?>)</h3></div>
    <table>
        <thead><tr><th>User</th><th>Comment</th><th>Date</th></tr></thead>
        <tbody>
            <?php if (empty($comments)): ?>
                <tr><td colspan="3" style="text-align: center; color: rgba(245,240,235,0.3);">No comments yet</td></tr>
            <?php else: ?>
                <?php foreach ($comments as $c): ?>
                    <tr>
                        <td><?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?></td>
                        <td><?= sanitize($c['comment']) ?></td>
                        <td><?= formatDateTime($c['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.high { color: #ef5350; background: rgba(239,83,80,0.04); } table .status.medium { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.low { color: #3b82f6; background: rgba(59,130,246,0.04); } table .status.urgent { color: #ef4444; background: rgba(239,68,68,0.04); } table .status.todo { color: #6b7280; background: rgba(107,114,128,0.04); } table .status.in_progress { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.completed { color: #4caf50; background: rgba(76,175,80,0.04); } .form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }</style>
