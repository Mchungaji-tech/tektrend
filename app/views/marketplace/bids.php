<?php $pageTitle = 'Client Design Bids'; ?>
<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-gavel" style="color: var(--primary); margin-right: 0.4rem;"></i> Incoming Design Bids</h3>
            <p class="card-subtitle">Review incoming client bids and instant buy offers on Awwwards showcase items</p>
        </div>
        <a href="<?= eurl('/marketplace') ?>" class="btn btn-outline"><i class="fas fa-trophy"></i> Marketplace Items</a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Target Design</th>
                    <th>Bidder Name & Contact</th>
                    <th>Bid Amount</th>
                    <th>Date Placed</th>
                    <th>Message / Request</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bids)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                            No bids received yet.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bids as $b): ?>
                        <tr>
                            <td>
                                <strong><?= sanitize($b['design_title'] ?? 'Design Template') ?></strong>
                                <?php if (!empty($b['award_badge'])): ?>
                                    <div><span class="badge pending" style="font-size: 0.65rem;"><?= sanitize($b['award_badge']) ?></span></div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong style="color: var(--text-main);"><?= sanitize($b['bidder_name']) ?></strong>
                                <div style="font-size: 0.78rem; color: var(--text-muted);"><a href="mailto:<?= sanitize($b['bidder_email']) ?>"><?= sanitize($b['bidder_email']) ?></a></div>
                                <?php if (!empty($b['bidder_phone'])): ?>
                                    <div style="font-size: 0.75rem;"><a href="https://wa.me/<?= preg_replace('/\D/', '', $b['bidder_phone']) ?>" target="_blank" style="color: #25d366;"><i class="fab fa-whatsapp"></i> <?= sanitize($b['bidder_phone']) ?></a></div>
                                <?php endif; ?>
                            </td>
                            <td><strong style="font-size: 1.1rem; color: var(--accent);"><?= formatCurrency($b['bid_amount']) ?></strong></td>
                            <td style="color: var(--text-muted);"><?= formatDateTime($b['created_at']) ?></td>
                            <td><span style="font-size: 0.82rem; color: var(--text-main);"><?= sanitize($b['message'] ?? '-') ?></span></td>
                            <td><span class="badge <?= sanitize($b['status']) ?>"><?= ucfirst(sanitize($b['status'])) ?></span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <form method="POST" action="<?= eurl('/marketplace/bids/' . $b['id'] . '/status') ?>" style="display: inline-block;">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <?php if ($b['status'] === 'pending'): ?>
                                        <button type="submit" name="status" value="accepted" class="btn btn-primary" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;"><i class="fas fa-check"></i> Accept</button>
                                        <button type="submit" name="status" value="rejected" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.75rem;"><i class="fas fa-times"></i> Reject</button>
                                    <?php else: ?>
                                        <span style="font-size: 0.75rem; color: var(--text-light); font-weight: 600;">Processed</span>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
