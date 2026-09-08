<?php $pageTitle = 'Edit Department'; ?>
<div class="card" style="max-width: 700px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Department: <?= sanitize($department['name']) ?></h3>
            <p class="card-subtitle">Update department details and head</p>
        </div>
        <a href="<?= eurl('/departments') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Departments</a>
    </div>

    <form method="POST" action="<?= eurl('/departments/' . $department['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label class="required">Department Name</label>
            <input type="text" name="name" class="form-control" value="<?= sanitize($department['name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?= sanitize($department['description'] ?? '') ?></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Department Color</label>
                <input type="color" name="color" class="form-control" value="<?= sanitize($department['color'] ?? '#4f46e5') ?>" style="height: 42px;">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="active" <?= $department['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $department['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/departments') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Department</button>
        </div>
    </form>
</div>
