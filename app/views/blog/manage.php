<?php $posts = $posts ?? []; ?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Blog &amp; Insights</h3>
            <p class="card-subtitle">Publish keyword-rich articles to grow organic search rankings.</p>
        </div>
        <a href="<?= eurl('/blog-manage/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Article</a>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Views</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($posts)): ?>
                    <tr><td colspan="6" class="text-center">No articles yet. Click "New Article" to publish your first post.</td></tr>
                <?php else: ?>
                    <?php foreach ($posts as $p): ?>
                        <tr>
                            <td>
                                <strong><?= sanitize($p['title']) ?></strong>
                                <?php if (!empty($p['is_featured'])): ?><span class="badge badge-primary">Featured</span><?php endif; ?>
                                <div style="font-size:.75rem;color:var(--text-muted);">/blog/<?= sanitize($p['slug']) ?></div>
                            </td>
                            <td><?= sanitize($p['category']) ?></td>
                            <td><span class="status <?= $p['status'] === 'published' ? 'active' : 'draft' ?>"><?= sanitize($p['status']) ?></span></td>
                            <td><?= $p['published_at'] ? formatDate($p['published_at']) : '—' ?></td>
                            <td><?= (int)$p['view_count'] ?></td>
                            <td style="text-align:right; white-space:nowrap;">
                                <a href="<?= eurl('/blog/' . $p['slug']) ?>" target="_blank" class="btn btn-outline" title="View"><i class="fas fa-eye"></i></a>
                                <a href="<?= eurl('/blog-manage/' . $p['id'] . '/edit') ?>" class="btn btn-secondary" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= eurl('/blog-manage/' . $p['id'] . '/delete') ?>" class="btn btn-danger" title="Delete" onclick="return confirm('Delete this article?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
