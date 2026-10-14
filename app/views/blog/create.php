<?php
$post = $post ?? [];
$formAction = '/blog-manage';
?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">New Article</h3>
            <p class="card-subtitle">Write helpful, keyword-focused content. Publish consistently to climb Google rankings.</p>
        </div>
        <a href="<?= eurl('/blog-manage') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <?php include VIEW_PATH . '/blog/_form.php'; ?>
</div>
