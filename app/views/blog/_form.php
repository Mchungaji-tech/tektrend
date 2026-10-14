<?php
/**
 * Shared blog article form. Expects: $post (array|null), $formAction (string)
 */
$post = $post ?? [];
$formAction = $formAction ?? url('/blog-manage');
$val = function ($key, $default = '') use ($post) {
    return isset($post[$key]) ? $post[$key] : $default;
};
?>
<form action="<?= eurl($formAction) ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

    <div class="form-row">
        <div class="form-group" style="flex:2;">
            <label for="title">Title * <span style="color:var(--text-muted);font-weight:400;">(use your target keyword)</span></label>
            <input type="text" id="title" name="title" class="form-control" required value="<?= sanitize($val('title')) ?>" placeholder="e.g. How Much Does Custom Software Cost in Kenya">
        </div>
        <div class="form-group" style="flex:1;">
            <label for="slug">URL Slug</label>
            <input type="text" id="slug" name="slug" class="form-control" value="<?= sanitize($val('slug')) ?>" placeholder="auto-generated-from-title">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="flex:1;">
            <label for="category">Category</label>
            <input type="text" id="category" name="category" class="form-control" value="<?= sanitize($val('category', 'Engineering')) ?>">
        </div>
        <div class="form-group" style="flex:1;">
            <label for="author_name">Author</label>
            <input type="text" id="author_name" name="author_name" class="form-control" value="<?= sanitize($val('author_name', 'Tektrend Softwares')) ?>">
        </div>
        <div class="form-group" style="flex:1;">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <option value="draft" <?= $val('status', 'draft') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="published" <?= $val('status') === 'published' ? 'selected' : '' ?>>Published</option>
            </select>
        </div>
        <div class="form-group" style="flex:0 0 auto; align-self:flex-end;">
            <label style="display:flex;align-items:center;gap:.5rem;font-weight:600;">
                <input type="checkbox" name="is_featured" value="1" <?php if (!empty($val('is_featured'))) { echo 'checked'; } ?>> Featured
            </label>
        </div>
    </div>

    <div class="form-group">
        <label for="excerpt">Meta Description / Excerpt <span style="color:var(--text-muted);font-weight:400;">(~155 characters for best SERP display)</span></label>
        <textarea id="excerpt" name="excerpt" class="form-control" rows="2" maxlength="320"><?= sanitize($val('excerpt')) ?></textarea>
    </div>

    <div class="form-group">
        <label for="body">Article Body (HTML allowed)</label>
        <textarea id="body" name="body" class="form-control" rows="16" placeholder="<p>Start with your keyword in the first 100 words...</p>"><?= $val('body') /* raw HTML */ ?></textarea>
    </div>

    <div class="form-row">
        <div class="form-group" style="flex:2;">
            <label for="cover_image">Cover Image URL</label>
            <input type="text" id="cover_image" name="cover_image" class="form-control" value="<?= sanitize($val('cover_image')) ?>" placeholder="https://...">
        </div>
        <div class="form-group" style="flex:1;">
            <label for="cover_file">Or Upload Cover Image</label>
            <input type="file" id="cover_file" name="cover_file" class="form-control" accept="image/*">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group" style="flex:2;">
            <label for="meta_title">SEO Meta Title (optional)</label>
            <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?= sanitize($val('meta_title')) ?>" placeholder="Defaults to Title">
        </div>
        <div class="form-group" style="flex:2;">
            <label for="meta_description">SEO Meta Description (optional)</label>
            <input type="text" id="meta_description" name="meta_description" class="form-control" value="<?= sanitize($val('meta_description')) ?>" placeholder="Defaults to excerpt">
        </div>
        <div class="form-group" style="flex:1;">
            <label for="tags">Tags (comma separated)</label>
            <input type="text" id="tags" name="tags" class="form-control" value="<?= sanitize($val('tags')) ?>">
        </div>
    </div>

    <div style="display:flex; gap:.85rem; margin-top:1rem;">
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Article</button>
        <a href="<?= eurl('/blog-manage') ?>" class="btn btn-outline">Cancel</a>
    </div>
</form>
