<?php $pageTitle = 'Live Active Staff'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-user-clock" style="color: var(--success); margin-right: 0.5rem;"></i> Live Active Staff & Consultants
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Real-time presence monitoring and direct communication links</p>
        </div>
        <a href="<?= eurl('/chat') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Virtual Office</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Staff Activity Roster</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Role & Position</th>
                    <th>Work Status</th>
                    <th>Last Active</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No active members found.</td></tr>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main);"><?= sanitize($u['first_name'] . ' ' . $u['last_name']) ?></strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= sanitize($u['email']) ?></div>
                            </td>
                            <td><span style="color: var(--text-main); font-weight: 500;"><?= sanitize($u['department_name'] ?? 'Executive') ?></span></td>
                            <td><?= sanitize($u['position'] ?? ucfirst($u['role'])) ?></td>
                            <td><span class="badge <?= $u['is_online'] ? 'working' : 'offline' ?>"><?= $u['is_online'] ? 'Online (' . sanitize($u['work_status'] ?? 'working') . ')' : 'Offline' ?></span></td>
                            <td style="color: var(--text-muted);"><?= formatDateTime($u['last_login'] ?? $u['created_at']) ?></td>
                            <td style="text-align: right;">
                                <a href="https://wa.me/<?= preg_replace('/\D/', '', $u['phone'] ?? '254707246273') ?>" target="_blank" class="btn btn-whatsapp" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
