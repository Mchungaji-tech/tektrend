<?php $pageTitle = 'Design Marketplace & Bidding'; ?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-trophy" style="color: var(--accent); margin-right: 0.4rem;"></i> Awwwards-Style Design Showcase & Auctions</h3>
            <p class="card-subtitle">Manage digital templates, set reserve bids, and review live buy-now acquisitions</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/marketplace/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Design Item</a>
            <a href="<?= eurl('/marketplace/bids') ?>" class="btn btn-outline"><i class="fas fa-gavel"></i> View Client Bids</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Preview</th>
                    <th>Design Title & Category</th>
                    <th>Award Badge</th>
                    <th>Current Highest Bid</th>
                    <th>Buy Now Price</th>
                    <th>Total Bids</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fas fa-paint-brush" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                            No designs currently listed in the marketplace.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $it): ?>
                        <tr>
                            <td style="width: 80px;">
                                <img src="<?= sanitize($it['image']) ?>" alt="" style="width: 70px; height: 48px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                            </td>
                            <td>
                                <strong style="color: var(--text-main);"><?= sanitize($it['title']) ?></strong>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><?= sanitize($it['category']) ?></div>
                            </td>
                            <td><span class="badge pending"><i class="fas fa-medal"></i> <?= sanitize($it['award_badge']) ?></span></td>
                            <td><strong style="color: var(--accent);">$<?= number_format($it['current_bid'], 2) ?></strong></td>
                            <td style="color: var(--text-main);">$<?= number_format($it['buy_now_price'], 2) ?></td>
                            <td><span class="badge in_progress"><?= $it['total_bids'] ?> Bids</span></td>
                            <td><span class="badge <?= sanitize($it['status']) ?>"><?= ucfirst(sanitize($it['status'])) ?></span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <a href="<?= eurl('/marketplace/' . $it['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Edit"><i class="fas fa-edit"></i></a>
                                <a href="<?= eurl('/marketplace/' . $it['id'] . '/delete') ?>" onclick="return confirm('Delete this design item and all associated bids?')" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Delete"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
