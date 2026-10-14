<?php
$post = $post ?? [];
$formAction = '/blog-manage/' . ($post['id'] ?? 0);
?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Article</h3>
            <p class="card-subtitle">Update content and SEO fields, then republish.</p>
        </div>
        <div style="display:flex;gap:.6rem;">
            <a href="<?= eurl('/blog/' . $post['slug']) ?>" target="_blank" class="btn btn-outline"><i class="fas fa-eye"></i> Preview</a>
            <a href="<?= eurl('/blog-manage') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
        </div>
    </div>
    <?php include VIEW_PATH . '/blog/_form.php'; ?>
</div>
