<?php
/** @var array $stats */
/** @var array $recentInvoices */
/** @var array $upcomingConsultations */
/** @var array $projects */
?>
<?php if (empty($hasCustomer)): ?>
    <div class="card">
        <div class="empty-state">
            <i class="fas fa-folder-open"></i>
            <h3>Welcome to your portal</h3>
            <p>We're still linking your account to your project records. Invoices and projects will appear here once available.</p>
        </div>
    </div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fas fa-file-invoice-dollar"></i></div>
        <span class="label">Total Billed</span>
        <span class="value"><?= formatCurrency($stats['total_billed'] ?? 0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fas fa-circle-check"></i></div>
        <span class="label">Paid</span>
        <span class="value"><?= formatCurrency($stats['amount_paid'] ?? 0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fas fa-clock"></i></div>
        <span class="label">Outstanding</span>
        <span class="value"><?= formatCurrency($stats['amount_due'] ?? 0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-light); color: var(--accent);"><i class="fas fa-file-lines"></i></div>
        <span class="label">Open Invoices</span>
        <span class="value"><?= (int)($stats['open_invoices'] ?? 0) ?></span>
    </div>
</div>

<?php if (!empty($purchases)): ?>
    <div class="card" style="border: 1px solid rgba(228, 147, 102, 0.35); background: linear-gradient(180deg, rgba(228, 147, 102, 0.04) 0%, rgba(22, 22, 22, 0.8) 100%);">
        <div class="card-header">
            <div>
                <h3 class="card-title" style="color: #fff;"><i class="fas fa-file-zipper" style="color: var(--primary); margin-right: 0.5rem;"></i> My Purchased Software &amp; Source Code Packages</h3>
                <p class="card-subtitle">Download your verified source code packages, documentation, and lifetime commercial licenses</p>
            </div>
            <a href="<?= eurl('/shop') ?>" class="btn btn-outline"><i class="fas fa-store"></i> Browse Software Store</a>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Package Title</th>
                        <th>Paystack Ref</th>
                        <th>Amount Paid</th>
                        <th>Purchased On</th>
                        <th>Downloads</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($purchases as $p): ?>
                    <tr>
                        <td>
                            <strong style="font-size: 1rem; color: #fff;"><?= sanitize($p['item_title']) ?></strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><i class="fas fa-certificate" style="color:#10b981;"></i> Commercial License Active</div>
                        </td>
                        <td><code style="font-size: 0.85rem;"><?= sanitize($p['paystack_reference']) ?></code></td>
                        <td><?= sanitize($p['currency']) ?> <?= number_format($p['amount'], 2) ?></td>
                        <td><?= formatDate($p['paid_at'] ?? $p['created_at']) ?></td>
                        <td><span class="badge active"><?= (int)$p['download_count'] ?> downloads</span></td>
                        <td style="text-align: right;">
                            <a href="<?= eurl('/checkout/download/' . $p['download_token']) ?>" class="btn btn-primary" style="padding: 0.45rem 1rem; font-size: 0.85rem;">
                                <i class="fas fa-download"></i> Download ZIP
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">Recent Invoices</h3>
            <p class="card-subtitle">Your latest billing documents</p>
        </div>
        <a href="<?= eurl('/portal/invoices') ?>" class="btn btn-outline">View all</a>
    </div>
    <?php if (empty($recentInvoices)): ?>
        <div class="empty-state"><i class="fas fa-file-invoice"></i><p>No invoices yet.</p></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Invoice</th><th>Issued</th><th>Due</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php foreach ($recentInvoices as $inv): ?>
                    <tr>
                        <td><strong><?= sanitize($inv['invoice_number']) ?></strong></td>
                        <td><?= formatDate($inv['issue_date']) ?></td>
                        <td><?= formatDate($inv['due_date']) ?></td>
                        <td><?= formatCurrency($inv['total']) ?></td>
                        <td><span class="badge <?= sanitize($inv['status']) ?>"><?= sanitize(ucfirst($inv['status'])) ?></span></td>
                        <td style="text-align: right;"><a href="<?= eurl('/portal/invoices/' . $inv['id']) ?>" class="btn btn-secondary">View</a></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="form-row">
    <div class="card" style="flex: 1; min-width: 300px;">
        <div class="card-header"><h3 class="card-title">Upcoming Consultations</h3></div>
        <?php if (empty($upcomingConsultations)): ?>
            <div class="empty-state"><i class="fas fa-video"></i><p>No scheduled sessions.</p></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Service</th><th>Date</th><th>Time</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($upcomingConsultations as $c): ?>
                        <tr>
                            <td><?= sanitize($c['service_type']) ?></td>
                            <td><?= formatDate($c['preferred_date']) ?></td>
                            <td><?= sanitize($c['preferred_time']) ?></td>
                            <td><span class="badge <?= sanitize($c['status']) ?>"><?= sanitize(ucfirst($c['status'])) ?></span></td>
                            <td style="text-align:right;">
                                <?php if (!empty($c['meeting_link'])): ?>
                                    <a href="<?= sanitize($c['meeting_link']) ?>" target="_blank" rel="noopener" class="btn btn-primary" style="padding:0.2rem 0.6rem; font-size:0.75rem;"><i class="fas fa-video"></i> Join</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card" style="flex: 1; min-width: 300px;">
        <div class="card-header">
            <h3 class="card-title">My Projects</h3>
            <a href="<?= eurl('/portal/projects') ?>" class="btn btn-outline">View all</a>
        </div>
        <?php if (empty($projects)): ?>
            <div class="empty-state"><i class="fas fa-diagram-project"></i><p>No active projects.</p></div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead><tr><th>Project</th><th>Status</th><th>Value</th></tr></thead>
                    <tbody>
                    <?php foreach ($projects as $p): ?>
                        <tr>
                            <td><?= sanitize($p['title']) ?></td>
                            <td><span class="badge <?= sanitize($p['status']) ?>"><?= sanitize(ucfirst(str_replace('_',' ',$p['status']))) ?></span></td>
                            <td><?= formatCurrency($p['value']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
