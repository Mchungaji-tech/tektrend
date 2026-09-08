<?php $pageTitle = 'Content Management'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-edit" style="color: var(--primary); margin-right: 0.5rem;"></i> Landing Page & CMS Content
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Edit marketing copy, service descriptions, and public headings</p>
        </div>
        <form method="GET" action="<?= eurl('/content') ?>" style="display: flex; gap: 0.5rem;">
            <select name="page" onchange="this.form.submit()" class="form-control" style="width: auto;">
                <option value="home" <?= ($currentPage ?? 'home') === 'home' ? 'selected' : '' ?>>Home Page</option>
                <option value="about" <?= ($currentPage ?? '') === 'about' ? 'selected' : '' ?>>About Us</option>
                <option value="services" <?= ($currentPage ?? '') === 'services' ? 'selected' : '' ?>>Services</option>
                <option value="contact" <?= ($currentPage ?? '') === 'contact' ? 'selected' : '' ?>>Contact</option>
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Page Content: <?= ucfirst($currentPage ?? 'Home') ?> (<?= count($contents) ?> sections)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Content Key</th>
                    <th>Section Title</th>
                    <th>Format Type</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contents)): ?>
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No content sections found.</td></tr>
                <?php else: ?>
                    <?php foreach ($contents as $c): ?>
                        <tr>
                            <td><strong style="font-family: monospace; font-size: 0.85rem; color: var(--primary);"><?= sanitize($c['key']) ?></strong></td>
                            <td><strong style="color: var(--text-main);"><?= sanitize($c['title'] ?? '-') ?></strong></td>
                            <td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= strtoupper(sanitize($c['type'] ?? 'html')) ?></span></td>
                            <td><span class="badge <?= $c['is_active'] ? 'active' : 'inactive' ?>"><?= $c['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                            <td style="color: var(--text-muted);"><?= formatDate($c['updated_at']) ?></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/content/' . $c['key'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit Content">
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
