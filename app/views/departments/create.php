<?php $pageTitle = 'Create Department'; ?>

<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Create Department Unit</h3>
            <p class="card-subtitle">Establish a new organizational division and assign its lead</p>
        </div>
        <a href="<?= eurl('/departments') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Departments</a>
    </div>

    <form method="POST" action="<?= eurl('/departments') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">Department Name</label>
                <input type="text" name="name" class="form-control" placeholder="e.g. AI & Cloud Architecture" required>
            </div>
            <div class="form-group">
                <label class="required">Slug / Key</label>
                <input type="text" name="slug" class="form-control" placeholder="e.g. ai-cloud" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department Head</label>
                <select name="head_id" class="form-control">
                    <option value="">Select Head...</option>
                    <?php foreach ($heads as $h): ?>
                        <option value="<?= $h['id'] ?>"><?= sanitize($h['first_name'] . ' ' . $h['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Theme Color Tag</label>
                <input type="color" name="color" class="form-control" value="#4f46e5" style="height: 42px;">
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label>Department Description</label>
            <textarea name="description" class="form-control" rows="3" placeholder="Scope of projects, deliverables and capabilities..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/departments') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Save Department</button>
        </div>
    </form>
</div>
