<?php $pageTitle = 'Live Team Work Status'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-user-clock" style="color: var(--primary); margin-right: 0.5rem;"></i> Live Team Work Status
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Real-time availability and activity of consultants, managers, and developers</p>
        </div>
        <a href="<?= eurl('/dashboard') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Active Personnel Presence</h3>
            <p class="card-subtitle">Showing all system users and their active working modes</p>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>User / Consultant</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Work Status</th>
                    <th>Last Activity</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 2rem;">No users found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem;">
                                        <?= strtoupper(substr($u['first_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong style="color: var(--text-main);"><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);"><?= sanitize($u['email']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><span style="color: var(--text-main); font-weight: 500;"><?= sanitize($u['department_name'] ?? 'General') ?></span></td>
                            <td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= ucfirst(sanitize($u['role'])) ?></span></td>
                            <td><span class="badge <?= sanitize($u['work_status'] ?? 'offline') ?>"><?= ucfirst(str_replace('_', ' ', sanitize($u['work_status'] ?? 'offline'))) ?></span></td>
                            <td style="color: var(--text-muted);"><?= timeAgo($u['last_activity'] ?? $u['last_login']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
