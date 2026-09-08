<?php
/**
 * Shared Table Partial
 * Reusable table view for list pages
 * Usage: $this->render('layouts/partials/table', [...])
 */
?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title"><?= sanitize($title ?? 'Records') ?></h3>
            <p class="card-subtitle"><?= isset($data) ? count($data) : 0 ?> items loaded</p>
        </div>
        <?php if (!empty($createUrl)): ?>
            <a href="<?= $createUrl ?>" class="btn btn-primary"><i class="fas fa-plus"></i> Add New</a>
        <?php endif; ?>
    </div>
    
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <?php foreach ($columns as $col): ?>
                        <th><?= sanitize($col['label']) ?></th>
                    <?php endforeach; ?>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="<?= count($columns) + 1 ?>" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                            No records found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data as $row): ?>
                        <tr>
                            <?php foreach ($columns as $col): ?>
                                <td><?= isset($row[$col['key']]) ? sanitize($row[$col['key']]) : '-' ?></td>
                            <?php endforeach; ?>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="<?= $row['id'] ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem; margin-right: 0.25rem;" title="View"><i class="fas fa-eye" style="color: var(--primary);"></i></a>
                                <a href="<?= $row['id'] ?>/edit" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem; margin-right: 0.25rem;" title="Edit"><i class="fas fa-edit" style="color: var(--accent);"></i></a>
                                <a href="<?= $row['id'] ?>/delete" onclick="return confirm('Are you sure you want to delete this record?')" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div style="margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.75rem;">
            <div style="font-size: 0.8rem; color: var(--text-muted);">
                Showing page <?= $pagination['page'] ?> of <?= $pagination['total_pages'] ?> (<?= $pagination['total'] ?> total entries)
            </div>
            <div style="display: flex; gap: 0.35rem;">
                <?php if ($pagination['has_prev']): ?>
                    <a href="?page=<?= $pagination['prev_page'] ?>" class="btn btn-outline" style="padding: 0.35rem 0.75rem;"><i class="fas fa-chevron-left"></i> Prev</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                    <a href="?page=<?= $i ?>" class="btn <?= $i == $pagination['page'] ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.75rem; min-width: 36px;"><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?= $pagination['next_page'] ?>" class="btn btn-outline" style="padding: 0.35rem 0.75rem;">Next <i class="fas fa-chevron-right"></i></a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
