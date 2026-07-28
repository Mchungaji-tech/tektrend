<?php
/**
 * Shared Table Partial
 * Reusable table view for list pages
 * Usage: $this->render('layouts/partials/table', [...])
 */
?>
<div class="table-section reveal">
    <div class="header">
        <h3><?= $title ?? 'Records' ?></h3>
        <a href="<?= $createUrl ?? '#' ?>" class="action"><i class="fas fa-plus"></i> Add New</a>
    </div>
    <table>
        <thead>
            <tr>
                <?php foreach ($columns as $col): ?>
                    <th><?= $col['label'] ?></th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($data)): ?>
                <tr><td colspan="<?= count($columns) + 1 ?>" style="text-align: center; color: rgba(245,240,235,0.3);">No records found</td></tr>
            <?php else: ?>
                <?php foreach ($data as $row): ?>
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <td><?= isset($row[$col['key']]) ? sanitize($row[$col['key']]) : '-' ?></td>
                        <?php endforeach; ?>
                        <td>
                            <a href="<?= $row['id'] ?>" style="color: #3b82f6; margin-right: 0.5rem;"><i class="fas fa-eye"></i></a>
                            <a href="<?= $row['id'] ?>/edit" style="color: #f59e0b; margin-right: 0.5rem;"><i class="fas fa-edit"></i></a>
                            <a href="<?= $row['id'] ?>/delete" onclick="return confirm('Are you sure?')" style="color: #ef4444;"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
    <div style="margin-top: 1rem; display: flex; justify-content: center; gap: 0.5rem;">
        <?php if ($pagination['has_prev']): ?>
            <a href="?page=<?= $pagination['prev_page'] ?>" style="padding: 0.4rem 1rem; background: rgba(245,240,235,0.02); border-radius: 8px; color: #f5f0eb; text-decoration: none;">Prev</a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
            <a href="?page=<?= $i ?>" style="padding: 0.4rem 0.8rem; background: <?= $i == $pagination['page'] ? 'rgba(184,148,60,0.1)' : 'rgba(245,240,235,0.02)' ?>; border-radius: 8px; color: <?= $i == $pagination['page'] ? '#b8943c' : '#f5f0eb' ?>; text-decoration: none;"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($pagination['has_next']): ?>
            <a href="?page=<?= $pagination['next_page'] ?>" style="padding: 0.4rem 1rem; background: rgba(245,240,235,0.02); border-radius: 8px; color: #f5f0eb; text-decoration: none;">Next</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
