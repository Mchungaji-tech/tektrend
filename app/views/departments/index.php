<?php $pageTitle = 'Company Departments'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-sitemap" style="color: var(--primary); margin-right: 0.5rem;"></i> Company Departments & Structure
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Organize engineering, design, sales, and executive business units</p>
        </div>
        <a href="<?= eurl('/departments/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Department
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Departments (<?= count($departments) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Department Name</th>
                    <th>Identifier / Slug</th>
                    <th>Department Head</th>
                    <th>Scope Description</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($departments)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No departments found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($departments as $d): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.6rem;">
                                    <span style="width: 12px; height: 12px; border-radius: 50%; background: <?= sanitize($d['color'] ?? 'var(--primary)') ?>; display: inline-block;"></span>
                                    <strong style="color: var(--text-main);"><?= sanitize($d['name']) ?></strong>
                                </div>
                            </td>
                            <td><span style="font-family: monospace; font-size: 0.82rem; color: var(--text-muted);"><?= sanitize($d['slug']) ?></span></td>
                            <td><strong style="color: var(--text-main);"><?= sanitize(($d['head_first'] ?? '') . ' ' . ($d['head_last'] ?? '')) ?: 'Not assigned' ?></strong></td>
                            <td style="color: var(--text-muted); font-size: 0.85rem;"><?= sanitize(substr($d['description'] ?? '-', 0, 60)) . (strlen($d['description'] ?? '') > 60 ? '...' : '') ?></td>
                            <td><span class="badge <?= sanitize($d['status']) ?>"><?= ucfirst(sanitize($d['status'])) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/departments/' . $d['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit Department">
                                    <i class="fas fa-edit" style="color: var(--accent);"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
