<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fas fa-circle-check"></i></div>
        <span class="label">Status</span>
        <span class="value" style="font-size: 1.2rem;"><?= sanitize(ucfirst($partner['status'] ?? 'approved')) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fas fa-percent"></i></div>
        <span class="label">Commission</span>
        <span class="value"><?= rtrim(rtrim(number_format((float)($partner['commission_rate'] ?? 0), 2), '0'), '.') ?>%</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-light); color: var(--accent);"><i class="fas fa-users"></i></div>
        <span class="label">Referrals</span>
        <span class="value">0</span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--danger-light); color: var(--danger);"><i class="fas fa-sack-dollar"></i></div>
        <span class="label">Available Payout</span>
        <span class="value" style="font-size: 1.25rem;"><?= formatCurrency(0) ?></span>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Your Referral Code</h3></div>
    <p class="card-subtitle" style="margin-bottom: 1rem;">Share your code to earn commission on referred clients. Referral tracking will be enabled in a future release.</p>
    <div style="display:flex; align-items:center; gap:.75rem; flex-wrap: wrap;">
        <code style="font-size: 1.05rem; font-weight: 700; background: var(--bg-card-subtle); padding: .6rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);"><?= sanitize($referralCode) ?></code>
        <button class="btn btn-outline" onclick="navigator.clipboard.writeText('<?= sanitize($referralCode) ?>').then(()=>alert('Copied'))"><i class="fas fa-copy"></i> Copy</button>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Company</h3></div>
    <?php if (!empty($partner)): ?>
        <p><strong><?= sanitize($partner['company_name'] ?? '—') ?></strong></p>
        <?php if (!empty($partner['website'])): ?>
            <p style="margin-top:.4rem;"><i class="fas fa-link"></i> <a href="<?= sanitize($partner['website']) ?>" target="_blank" rel="noopener"><?= sanitize($partner['website']) ?></a></p>
        <?php endif; ?>
        <?php if (!empty($partner['about'])): ?>
            <p style="margin-top:.6rem; color: var(--text-muted);"><?= nl2br(sanitize($partner['about'])) ?></p>
        <?php endif; ?>
    <?php else: ?>
        <div class="empty-state"><i class="fas fa-building"></i><p>No company details yet.</p></div>
    <?php endif; ?>
</div>
