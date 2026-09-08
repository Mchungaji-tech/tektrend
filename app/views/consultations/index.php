<?php $pageTitle = 'Zoom Consultations'; ?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-video" style="color: var(--primary); margin-right: 0.4rem;"></i> Zoom Consultations & Meetings</h3>
            <p class="card-subtitle">Manage client technical scoping sessions and video briefings</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?= eurl('/consultations') ?>" class="btn <?= empty($statusFilter) ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">All</a>
            <a href="<?= eurl('/consultations?status=confirmed') ?>" class="btn <?= $statusFilter === 'confirmed' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Confirmed</a>
            <a href="<?= eurl('/consultations?status=pending') ?>" class="btn <?= $statusFilter === 'pending' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Pending</a>
            <a href="<?= eurl('/consultations?status=completed') ?>" class="btn <?= $statusFilter === 'completed' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Completed</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Company / Email</th>
                    <th>Consulting Topic</th>
                    <th>Date & Slot</th>
                    <th>Zoom ID / Link</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($consultations)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fas fa-video-slash" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                            No consultation sessions found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($consultations as $c): ?>
                        <tr>
                            <td>
                                <strong><?= sanitize($c['name']) ?></strong>
                                <?php if (!empty($c['phone'])): ?>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fab fa-whatsapp" style="color: #25d366;"></i> <?= sanitize($c['phone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= sanitize($c['company'] ?? 'Individual') ?></div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);"><?= sanitize($c['email']) ?></div>
                            </td>
                            <td><span style="font-weight: 600; color: var(--primary);"><?= sanitize($c['service_type']) ?></span></td>
                            <td>
                                <div><strong><?= formatDate($c['preferred_date']) ?></strong></div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);"><i class="fas fa-clock"></i> <?= sanitize($c['preferred_time']) ?> (<?= $c['duration_minutes'] ?>m)</div>
                            </td>
                            <td>
                                <a href="<?= sanitize($c['zoom_link']) ?>" target="_blank" class="btn btn-zoom" style="padding: 0.25rem 0.65rem; font-size: 0.75rem;">
                                    <i class="fas fa-video"></i> Launch Zoom
                                </a>
                            </td>
                            <td><span class="badge <?= sanitize($c['status']) ?>"><?= ucfirst(sanitize($c['status'])) ?></span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="<?= eurl('/consultations/' . $c['id']) ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="View Details"><i class="fas fa-eye"></i></a>
                                <a href="<?= eurl('/consultations/' . $c['id'] . '/delete') ?>" onclick="return confirm('Delete this consultation booking?')" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.35rem;">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn <?= $i == $pagination['page'] ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.75rem;"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
