<?php $pageTitle = 'Email Subscribers'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-users" style="color: var(--primary); margin-right: 0.5rem;"></i> Audience Subscribers List
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage newsletter contacts, opt-ins, and acquisition sources</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button type="button" onclick="document.getElementById('addModal').style.display='flex'" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Subscriber
            </button>
            <a href="<?= eurl('/emails') ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> All Campaigns
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All Subscribers (<?= count($subscribers) ?>)</h3>
        </div>
        <form method="GET" action="<?= eurl('/emails/subscribers') ?>" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search subscribers..." value="<?= sanitize($search ?? '') ?>" class="form-control" style="width: 240px;">
            <button type="submit" class="btn btn-outline"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Email Address</th>
                    <th>Subscriber Name</th>
                    <th>Opt-in Source</th>
                    <th>Subscribed Date</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subscribers)): ?>
                    <tr><td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">No subscribers found.</td></tr>
                <?php else: ?>
                    <?php foreach ($subscribers as $s): ?>
                        <tr>
                            <td><strong style="color: var(--text-main);"><?= sanitize($s['email']) ?></strong></td>
                            <td><span style="color: var(--text-main);"><?= sanitize(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')) ?: '-' ?></span></td>
                            <td><span style="color: var(--text-muted); font-size: 0.85rem;"><?= sanitize($s['source'] ?? 'Landing Page') ?></span></td>
                            <td style="color: var(--text-muted);"><?= formatDate($s['subscribed_at'] ?? $s['created_at']) ?></td>
                            <td><span class="badge <?= sanitize($s['status'] ?? 'active') ?>"><?= ucfirst(sanitize($s['status'] ?? 'active')) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/emails/subscribers/' . $s['id'] . '/delete') ?>" onclick="return confirm('Remove this subscriber?')" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Remove"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center;">
    <div style="background: var(--bg-card); border-radius: var(--radius-lg); padding: 2rem; width: 90%; max-width: 480px; border: 1px solid var(--border); box-shadow: var(--shadow-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
            <h3 style="color: var(--text-main); font-size: 1.2rem; font-weight: 800;">Add Email Subscriber</h3>
            <button type="button" onclick="document.getElementById('addModal').style.display='none'" style="background: none; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem;">&times;</button>
        </div>

        <form method="POST" action="<?= eurl('/emails/subscribers/add') ?>">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group">
                <label class="required">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="name@company.com">
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control">
                </div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn btn-outline">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Subscriber</button>
            </div>
        </form>
    </div>
</div>
