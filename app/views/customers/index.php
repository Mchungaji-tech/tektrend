<?php $pageTitle = 'Client Accounts'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-users" style="color: var(--primary); margin-right: 0.5rem;"></i> Client Accounts & Organizations
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage enterprise customer directory, billing details, and active contracts</p>
        </div>
        <a href="<?= eurl('/customers/create') ?>" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Client Account
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All Clients (<?= count($customers) ?>)</h3>
            <p class="card-subtitle">Active enterprise and retained accounts</p>
        </div>
        <form method="GET" action="<?= eurl('/customers') ?>" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" placeholder="Search by name, company, email..." value="<?= sanitize($search ?? '') ?>" class="form-control" style="width: 240px;">
            <button type="submit" class="btn btn-outline"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Contact Person</th>
                    <th>Company Name</th>
                    <th>Email Address</th>
                    <th>Phone</th>
                    <th>Country / City</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No client records found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($c['first_name'] . ' ' . $c['last_name']) ?></strong>
                            </td>
                            <td><span style="color: var(--text-main); font-weight: 600;"><?= sanitize($c['company'] ?? '-') ?></span></td>
                            <td style="color: var(--text-muted);"><?= sanitize($c['email'] ?? '-') ?></td>
                            <td style="color: var(--text-muted);"><?= sanitize($c['phone'] ?? '-') ?></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($c['country'] ?? ($c['city'] ?? '-')) ?></span></td>
                            <td><span class="badge <?= sanitize($c['status'] ?? 'active') ?>"><?= ucfirst(sanitize($c['status'] ?? 'active')) ?></span></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <a href="<?= eurl('/customers/' . $c['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Details">
                                        <i class="fas fa-eye" style="color: var(--primary);"></i>
                                    </a>
                                    <a href="<?= eurl('/customers/' . $c['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit Client">
                                        <i class="fas fa-edit" style="color: var(--accent);"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
