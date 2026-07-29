<?php $pageTitle = 'Work Status'; ?>
<div class="table-section reveal">
    <div class="header">
        <h3>Team Work Status</h3>
        <span class="period">Real-time availability</span>
    </div>
    <div style="margin-top: 1rem;">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Work Status</th>
                    <th>Last Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (($users ?? []) as $u): ?>
                    <tr>
                        <td><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></td>
                        <td><?= sanitize($u['department_name'] ?? '-') ?></td>
                        <td><span class="status processing"><?= ucfirst($u['role']) ?></span></td>
                        <td><span class="status <?= $u['work_status'] ?>"><?= ucfirst(str_replace('_', ' ', $u['work_status'])) ?></span></td>
                        <td><?= timeAgo($u['last_activity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
