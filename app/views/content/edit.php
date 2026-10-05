<?php $pageTitle = 'Edit Content: ' . sanitize($content['title'] ?? $content['key']); ?>
<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-edit" style="color: var(--primary); margin-right: 0.4rem;"></i> Edit CMS Section: <?= sanitize($content['key']) ?></h3>
            <p class="card-subtitle">Manage public landing page section, photo, or marketing copy</p>
        </div>
        <a href="<?= eurl('/content') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Studio</a>
    </div>

    <form method="POST" action="<?= eurl('/content/' . $content['key']) ?>" enctype="multipart/form-data">
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

        <?php if ($content['type'] === 'image' || strpos($content['key'], 'image') !== false || strpos($content['key'], 'photo') !== false): ?>
            <!-- Image editor with live preview & file upload -->
            <div class="form-group" style="background: var(--bg-card-subtle); padding: 1.25rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
                <label style="font-weight: 700; margin-bottom: 0.5rem; display: block;"><i class="fas fa-image" style="color: var(--primary);"></i> Image Photo Preview</label>
                <div style="display: grid; grid-template-columns: 140px 1fr; gap: 1.25rem; align-items: center;">
                    <div style="width: 140px; height: 100px; border-radius: var(--radius-sm); overflow: hidden; background: #000; border: 1px solid var(--border);">
                        <img id="singleEditPreview" src="<?= sanitize($content['content']) ?>" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80'">
                    </div>
                    <div>
                        <div style="margin-bottom: 0.5rem;">
                            <label style="font-size: 0.8rem; color: var(--text-muted);">Image URL</label>
                            <input type="text" name="content" id="singleEditUrl" class="form-control" value="<?= sanitize($content['content']) ?>" oninput="document.getElementById('singleEditPreview').src=this.value">
                        </div>
                        <div>
                            <label style="font-size: 0.8rem; color: var(--text-muted);">Or Upload New File</label>
                            <input type="file" name="image_file" class="form-control" accept="image/*" onchange="previewUploadedFile(this, 'singleEditPreview')">
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="form-group">
                <label>Body / Text Content</label>
                <textarea name="content" class="form-control" style="min-height: 180px; font-size: 0.95rem; line-height: 1.6;"><?= sanitize($content['content']) ?></textarea>
            </div>
        <?php endif; ?>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/content') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Content Section</button>
        </div>
    </form>
</div>

<script>
function previewUploadedFile(fileInput, imgId) {
    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(imgId);
            if (img) img.src = e.target.result;
        };
        reader.readAsDataURL(fileInput.files[0]);
    }
}
</script>
