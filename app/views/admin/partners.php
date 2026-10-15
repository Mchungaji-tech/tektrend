<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-light); color: var(--accent);"><i class="fas fa-hourglass-half"></i></div>
        <span class="label">Pending</span>
        <span class="value"><?= (int)($counts['pending'] ?? 0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fas fa-circle-check"></i></div>
        <span class="label">Approved</span>
        <span class="value"><?= (int)($counts['approved'] ?? 0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fas fa-ban"></i></div>
        <span class="label">Rejected</span>
        <span class="value"><?= (int)($counts['rejected'] ?? 0) ?></span>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div><h3 class="card-title">Partner Applications</h3><p class="card-subtitle">Review and approve referral partners</p></div>
        <div style="display:flex; gap:.5rem;">
            <a href="<?= eurl('/admin/partners') ?>" class="btn btn-outline <?= $statusFilter === '' ? 'btn-primary' : '' ?>">All</a>
            <a href="<?= eurl('/admin/partners?status=pending') ?>" class="btn btn-outline <?= $statusFilter === 'pending' ? 'btn-primary' : '' ?>">Pending</a>
            <a href="<?= eurl('/admin/partners?status=approved') ?>" class="btn btn-outline <?= $statusFilter === 'approved' ? 'btn-primary' : '' ?>">Approved</a>
        </div>
    </div>

    <?php if (empty($applications)): ?>
        <div class="empty-state"><i class="fas fa-handshake"></i><p>No partner applications yet.</p></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Company</th><th>Contact</th><th>Website</th><th>Commission</th><th>Status</th><th>Applied</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($applications as $a): ?>
                    <tr>
                        <td>
                            <strong><?= sanitize($a['company_name'] ?? '—') ?></strong><br>
                            <span style="color: var(--text-muted); font-size:.8rem;"><?= sanitize($a['first_name'] ?? '') . ' ' . sanitize($a['last_name'] ?? '') ?></span>
                        </td>
                        <td><?= sanitize($a['email'] ?? '') ?><?php if (!empty($a['phone'])): ?><br><span style="color: var(--text-muted); font-size:.8rem;"><?= sanitize($a['phone']) ?></span><?php endif; ?></td>
                        <td><?php if (!empty($a['website'])): ?><a href="<?= sanitize($a['website']) ?>" target="_blank" rel="noopener"><?= sanitize($a['website']) ?></a><?php else: ?>—<?php endif; ?></td>
                        <td><?= rtrim(rtrim(number_format((float)$a['commission_rate'], 2), '0'), '.') ?>%</td>
                        <td><span class="badge <?= sanitize($a['status']) ?>"><?= sanitize(ucfirst($a['status'])) ?></span></td>
                        <td><?= formatDate($a['created_at']) ?></td>
                        <td>
                            <?php if ($a['status'] !== 'approved'): ?>
                                <form method="post" action="<?= eurl('/admin/partners/' . $a['id'] . '/approve') ?>" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <button type="submit" class="btn btn-primary" style="padding:.35rem .7rem;"><i class="fas fa-check"></i> Approve</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($a['status'] !== 'rejected'): ?>
                                <form method="post" action="<?= eurl('/admin/partners/' . $a['id'] . '/reject') ?>" style="display:inline;">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <button type="submit" class="btn btn-danger" style="padding:.35rem .7rem;"><i class="fas fa-times"></i> Reject</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
