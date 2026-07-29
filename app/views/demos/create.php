<?php $pageTitle = 'Create Demo'; ?>
<div class="table-section reveal">
    <div class="header">
        <h3>Create New Demo</h3>
    </div>
    <form method="POST" action="/demos" style="max-width: 700px; margin-top: 1rem;">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-group">
            <label for="title" class="required">Title</label>
            <input type="text" id="title" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="slug" class="required">Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" id="url" name="url" class="form-control">
        </div>
        <div class="form-group">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" class="form-control" value="general">
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create Demo
        </button>
    </form>
</div>
