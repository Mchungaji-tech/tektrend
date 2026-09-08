<?php $pageTitle = 'Edit Content: ' . sanitize($content['title'] ?? $content['key']); ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit CMS Content: <?= sanitize($content['key']) ?></h3>
            <p class="card-subtitle">Manage public landing page sections and copy</p>
        </div>
        <a href="<?= eurl('/content') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Content</a>
    </div>

    <form method="POST" action="<?= eurl('/content/' . $content['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Content Title</label>
            <input type="text" name="title" class="form-control" value="<?= sanitize($content['title']) ?>" required>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Page</label>
                <input type="text" name="page" class="form-control" value="<?= sanitize($content['page'] ?? 'home') ?>" required>
            </div>
            <div class="form-group">
                <label>Section Key</label>
                <input type="text" name="section" class="form-control" value="<?= sanitize($content['section'] ?? 'hero') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Body / Text Content</label>
            <textarea name="content" class="form-control" style="min-height: 180px;"><?= sanitize($content['content']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/content') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Content</button>
        </div>
    </form>
</div>
