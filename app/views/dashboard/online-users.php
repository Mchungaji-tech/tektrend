<?php $pageTitle = 'Online Users'; ?>
<div class="table-section reveal">
    <div class="header">
        <h3>Online Users (<?= count($onlineUsers) ?>)</h3>
        <span class="period">Active in last 5 minutes</span>
    </div>
    <div style="margin-top: 1rem;">
        <?php if (empty($onlineUsers)): ?>
            <p style="color: rgba(245,240,235,0.3); font-size: 0.85rem;">No users online</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Department</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Active</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($onlineUsers as $ou): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.8rem;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 500;"><?= sanitize($ou['first_name'] . ' ' . $ou['last_name']) ?></div>
                                        <div style="font-size: 0.7rem; color: rgba(245,240,235,0.3);"><?= sanitize($ou['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= sanitize($ou['department_name'] ?? '-') ?></td>
                            <td><span class="status processing"><?= ucfirst($ou['role']) ?></span></td>
                            <td><span class="status <?= $ou['work_status'] ?>"><?= ucfirst(str_replace('_', ' ', $ou['work_status'])) ?></span></td>
                            <td><?= timeAgo($ou['last_activity']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
