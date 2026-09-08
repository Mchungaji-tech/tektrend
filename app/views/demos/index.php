<?php $pageTitle = 'Live Demos & Hosting Projects'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-globe" style="color: var(--primary); margin-right: 0.5rem;"></i> Live Demos & Hosting Domains
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage showcased web platforms, client hosting domains, and live project linkages</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/live-demos') ?>" target="_blank" class="btn btn-secondary">
                <i class="fas fa-external-link-alt"></i> Public Showcase
            </a>
            <a href="<?= eurl('/demos/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Link Project / Domain
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Configured Projects & Domains (<?= count($demos ?? []) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Category</th>
                    <th>Hosting Domain / URL</th>
                    <th>Type</th>
                    <th>Tech Stack</th>
                    <th>Featured</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($demos)): ?>
                    <?php foreach ($demos as $d): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                                        <i class="<?= sanitize($d['icon'] ?? 'fas fa-laptop-code') ?>"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight: 700; color: var(--text-main);"><?= sanitize($d['title']) ?></div>
                                        <?php if (!empty($d['award_badge'])): ?>
                                            <span class="badge" style="background: var(--accent-light); color: var(--accent); font-size: 0.65rem; font-weight: 700;"><?= sanitize($d['award_badge']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= sanitize($d['category']) ?></span></td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <i class="fas fa-globe" style="color: var(--text-muted); font-size: 0.8rem;"></i>
                                    <a href="<?= sanitize($d['demo_url']) ?>" target="_blank" style="color: var(--primary); font-weight: 600; text-decoration: none; font-size: 0.85rem;">
                                        <?= sanitize($d['hosting_domain'] ?: $d['demo_url']) ?>
                                    </a>
                                </div>
                            </td>
                            <td>
                                <span class="badge <?= $d['demo_type'] === 'external' ? 'active' : 'working' ?>">
                                    <?= ucfirst(sanitize($d['demo_type'])) ?>
                                </span>
                            </td>
                            <td style="font-size: 0.8rem; color: var(--text-muted); max-width: 180px;">
                                <?= sanitize($d['tech_stack']) ?>
                            </td>
                            <td>
                                <span class="badge <?= $d['is_featured'] ? 'active' : 'inactive' ?>">
                                    <?= $d['is_featured'] ? 'Published' : 'Hidden' ?>
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <a href="<?= sanitize($d['demo_url']) ?>" target="_blank" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Open Demo">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="<?= eurl('/demos/' . $d['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Edit Link">
                                        <i class="fas fa-edit" style="color: var(--accent);"></i>
                                    </a>
                                    <form action="<?= eurl('/demos/' . $d['id'] . '/delete') ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this demo?');" style="display:inline;">
                                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-4" style="color: var(--text-muted); text-align: center; padding: 2rem;">No demo projects configured yet. Click "Link Project / Domain" to add one.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
