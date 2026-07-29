<?php $pageTitle = 'Edit Demo'; ?>
<div class="table-section reveal">
    <div class="header">
        <h3>Edit Demo</h3>
    </div>
    <?php $demo ??= []; ?>
    <form method="POST" action="/demos/<?= $demo['id'] ?>" style="max-width: 700px; margin-top: 1rem;">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-group">
            <label for="title" class="required">Title</label>
            <input type="text" id="title" name="title" class="form-control" value="<?= sanitize($demo['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="slug" class="required">Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" value="<?= sanitize($demo['slug'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" id="url" name="url" class="form-control" value="<?= sanitize($demo['url'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" class="form-control" value="<?= sanitize($demo['category'] ?? 'general') ?>">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Save Changes
        </button>
    </form>
</div>
