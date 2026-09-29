<?php $pageTitle = 'Investor Profile: ' . sanitize($investment['investor_name']); ?>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Details Column -->
    <div class="card">
        <div class="card-header">
            <div>
                <a href="<?= eurl('/investments') ?>" style="font-size: 0.85rem; color: var(--text-muted); display: inline-flex; align-items: center; gap: 0.4rem; margin-bottom: 0.5rem;">
                    <i class="fas fa-arrow-left"></i> Back to Investor Pipeline
                </a>
                <h3 class="card-title"><?= sanitize($investment['investor_name']) ?></h3>
                <p class="card-subtitle">Inquiry Record #INV-<?= str_pad($investment['id'], 4, '0', STR_PAD_LEFT) ?> · Registered <?= formatDate($investment['created_at']) ?></p>
            </div>
            <div>
                <?php
                    $st = $investment['status'];
                    $stBadge = 'badge-secondary';
                    if ($st === 'inquiry') $stBadge = 'badge-warning';
                    elseif ($st === 'nda_sent' || $st === 'data_room_access') $stBadge = 'badge-info';
                    elseif ($st === 'term_sheet') $stBadge = 'badge-primary';
                    elseif ($st === 'funded') $stBadge = 'badge-success';
                ?>
                <span class="badge <?= $stBadge ?>" style="font-size: 0.9rem; padding: 0.5rem 1rem;">
                    <?= strtoupper(str_replace('_', ' ', sanitize($st))) ?>
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; padding: 1.5rem; background: var(--bg-hover, rgba(255,255,255,0.02)); border-radius: 1rem; border: 1px solid var(--border-color);">
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.3rem;">Investor Classification</div>
                <div style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary);"><?= sanitize($investment['investor_type']) ?></div>
            </div>
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.3rem;">Target Ticket Size</div>
                <div style="font-size: 1.25rem; font-weight: 800; color: #10b981;"><?= formatCurrency($investment['target_amount']) ?></div>
            </div>
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.3rem;">Official Email</div>
                <div style="font-size: 1rem; color: var(--text-primary);"><a href="mailto:<?= sanitize($investment['email']) ?>"><i class="fas fa-envelope"></i> <?= sanitize($investment['email']) ?></a></div>
            </div>
            <div>
                <div style="font-size: 0.8rem; text-transform: uppercase; color: var(--text-muted); margin-bottom: 0.3rem;">Direct Phone / WhatsApp</div>
                <div style="font-size: 1rem; color: var(--text-primary);">
                    <?php if (!empty($investment['phone'])): ?>
                        <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $investment['phone']) ?>" target="_blank" style="color: #25d366;">
                            <i class="fab fa-whatsapp"></i> <?= sanitize($investment['phone']) ?>
                        </a>
                    <?php else: ?>
                        <span style="color: var(--text-muted);">Not provided</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div style="margin-bottom: 2rem;">
            <h4 style="font-size: 1rem; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);">Investor Thesis & Inquiry Notes</h4>
            <div style="padding: 1.5rem; background: var(--bg-page); border-radius: 1rem; border: 1px solid var(--border-color); line-height: 1.7; color: var(--text-secondary);">
                <?= !empty($investment['notes']) ? nl2br(sanitize($investment['notes'])) : '<em>No initial notes submitted with this inquiry.</em>' ?>
            </div>
        </div>

        <!-- Quick Action Buttons -->
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="mailto:<?= sanitize($investment['email']) ?>?subject=Tektrend%20Softwares%20Investor%20Relations%20Deck%20%26%20Data%20Room" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i> Email Pitch Deck & Data Room
            </a>
            <?php if (!empty($investment['phone'])): ?>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $investment['phone']) ?>?text=Hello%20<?= urlencode($investment['investor_name']) ?>%2C%20this%20is%20Tektrend%20Softwares%20Executive%20Office%20following%20up%20on%20your%20investment%20inquiry." target="_blank" class="btn" style="background: #25d366; color: #fff;">
                    <i class="fab fa-whatsapp"></i> WhatsApp Investor
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Update Status & Allocation Sidebar -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-tasks"></i> Pipeline Status</h3>
        </div>

        <form action="<?= eurl('/investments/' . $investment['id'] . '/status') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Stage</label>
                <select name="status" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 0.75rem; background: var(--bg-page); border: 1px solid var(--border-color); color: var(--text-primary);">
                    <option value="inquiry" <?= $investment['status'] === 'inquiry' ? 'selected' : '' ?>>1. Initial Inquiry</option>
                    <option value="nda_sent" <?= $investment['status'] === 'nda_sent' ? 'selected' : '' ?>>2. NDA Sent</option>
                    <option value="data_room_access" <?= $investment['status'] === 'data_room_access' ? 'selected' : '' ?>>3. Data Room Access Granted</option>
                    <option value="term_sheet" <?= $investment['status'] === 'term_sheet' ? 'selected' : '' ?>>4. Term Sheet Negotiating</option>
                    <option value="funded" <?= $investment['status'] === 'funded' ? 'selected' : '' ?>>5. Funded / Closed</option>
                    <option value="archived" <?= $investment['status'] === 'archived' ? 'selected' : '' ?>>6. Archived / Passed</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Confirmed Ticket ($)</label>
                <input type="number" step="100" name="target_amount" value="<?= (float)$investment['target_amount'] ?>" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 0.75rem; background: var(--bg-page); border: 1px solid var(--border-color); color: var(--text-primary);">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem;">Internal Deal Notes</label>
                <textarea name="notes" rows="4" class="form-control" style="width: 100%; padding: 0.8rem; border-radius: 0.75rem; background: var(--bg-page); border: 1px solid var(--border-color); color: var(--text-primary);"><?= sanitize($investment['notes']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.9rem;">
                <i class="fas fa-save"></i> Save Deal Changes
            </button>
        </form>
    </div>
</div>
