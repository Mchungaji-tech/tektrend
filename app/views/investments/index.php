<?php $pageTitle = 'Investor Relations & Capital Allocation'; ?>

<!-- Top Summary Cards -->
<div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="padding: 1.5rem; border-left: 4px solid var(--primary);">
        <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Total Inquiries</div>
        <div style="font-size: 2rem; font-weight: 800; color: var(--text-primary);"><?= number_format($stats['total_inquiries']) ?></div>
        <div style="font-size: 0.8rem; color: var(--primary); margin-top: 0.4rem;"><i class="fas fa-users"></i> Angel & Institutional Pool</div>
    </div>
    <div class="card" style="padding: 1.5rem; border-left: 4px solid #10b981;">
        <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Pipeline Capital Interest</div>
        <div style="font-size: 2rem; font-weight: 800; color: #10b981;"><?= formatCurrency($stats['total_target']) ?></div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.4rem;"><i class="fas fa-chart-line"></i> Total indicated ticket value</div>
    </div>
    <div class="card" style="padding: 1.5rem; border-left: 4px solid #f59e0b;">
        <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Active Discussions</div>
        <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;"><?= number_format($stats['active_pipeline']) ?></div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.4rem;"><i class="fas fa-file-contract"></i> Inquiries, NDAs & Term Sheets</div>
    </div>
    <div class="card" style="padding: 1.5rem; border-left: 4px solid #8b5cf6;">
        <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Closed / Funded Capital</div>
        <div style="font-size: 2rem; font-weight: 800; color: #8b5cf6;"><?= formatCurrency($stats['funded_amount']) ?></div>
        <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.4rem;"><i class="fas fa-check-circle"></i> On-boarded Cap Table</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-chart-pie" style="color: var(--primary); margin-right: 0.4rem;"></i> Investor Pipeline & Inquiries</h3>
            <p class="card-subtitle">Manage Angel Syndicates, Growth Round participants, and Strategic VC allocations for Tektrend Softwares</p>
        </div>
        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <a href="<?= eurl('/investments') ?>" class="btn <?= empty($statusFilter) ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">All</a>
            <a href="<?= eurl('/investments?status=inquiry') ?>" class="btn <?= $statusFilter === 'inquiry' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Inquiries</a>
            <a href="<?= eurl('/investments?status=nda_sent') ?>" class="btn <?= $statusFilter === 'nda_sent' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">NDA Sent</a>
            <a href="<?= eurl('/investments?status=data_room_access') ?>" class="btn <?= $statusFilter === 'data_room_access' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Data Room</a>
            <a href="<?= eurl('/investments?status=term_sheet') ?>" class="btn <?= $statusFilter === 'term_sheet' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Term Sheet</a>
            <a href="<?= eurl('/investments?status=funded') ?>" class="btn <?= $statusFilter === 'funded' ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.8rem; font-size: 0.8rem;">Funded</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Investor / Firm</th>
                    <th>Investor Type</th>
                    <th>Target Amount</th>
                    <th>Contact Channels</th>
                    <th>Status</th>
                    <th>Received On</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($investments)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: var(--text-muted);">
                            <i class="fas fa-hand-holding-usd" style="font-size: 2.5rem; display: block; margin-bottom: 0.8rem; opacity: 0.4;"></i>
                            No investor inquiries recorded yet. Inquiries submitted via the front-end "Invest" modal will appear here.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($investments as $inv): ?>
                        <tr>
                            <td>
                                <strong><?= sanitize($inv['investor_name']) ?></strong>
                                <?php if (!empty($inv['notes'])): ?>
                                    <div style="font-size: 0.78rem; color: var(--text-muted); max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <?= sanitize($inv['notes']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(228, 147, 102, 0.15); color: var(--primary); border: 1px solid rgba(228, 147, 102, 0.3);">
                                    <?= sanitize($inv['investor_type']) ?>
                                </span>
                            </td>
                            <td>
                                <strong style="font-size: 1.05rem; color: #10b981;">
                                    <?= formatCurrency($inv['target_amount']) ?>
                                </strong>
                            </td>
                            <td>
                                <div><a href="mailto:<?= sanitize($inv['email']) ?>"><i class="fas fa-envelope"></i> <?= sanitize($inv['email']) ?></a></div>
                                <?php if (!empty($inv['phone'])): ?>
                                    <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 0.2rem;">
                                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $inv['phone']) ?>" target="_blank" style="color: #25d366;">
                                            <i class="fab fa-whatsapp"></i> <?= sanitize($inv['phone']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $st = $inv['status'];
                                    $stBadge = 'badge-secondary';
                                    if ($st === 'inquiry') $stBadge = 'badge-warning';
                                    elseif ($st === 'nda_sent' || $st === 'data_room_access') $stBadge = 'badge-info';
                                    elseif ($st === 'term_sheet') $stBadge = 'badge-primary';
                                    elseif ($st === 'funded') $stBadge = 'badge-success';
                                ?>
                                <span class="badge <?= $stBadge ?>">
                                    <?= strtoupper(str_replace('_', ' ', sanitize($st))) ?>
                                </span>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;"><?= formatDate($inv['created_at']) ?></div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= date('H:i', strtotime($inv['created_at'])) ?> EAT</div>
                            </td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="<?= eurl('/investments/' . $inv['id']) ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="View & Manage Profile"><i class="fas fa-eye"></i> View</a>
                                <a href="<?= eurl('/investments/' . $inv['id'] . '/delete') ?>" onclick="return confirm('Delete this investor inquiry?')" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
        <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 0.35rem;">
            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                <a href="?page=<?= $i ?>" class="btn <?= $i == $pagination['page'] ? 'btn-primary' : 'btn-outline' ?>" style="padding: 0.35rem 0.75rem;"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
