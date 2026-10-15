<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">My Referrals</h3>
            <p class="card-subtitle">Clients you've referred to Tek Trend</p>
        </div>
    </div>
    <div style="margin-bottom: 1rem;">
        <p class="card-subtitle">Your referral code</p>
        <code style="font-size: 1.05rem; font-weight: 700; background: var(--bg-card-subtle); padding: .6rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border);"><?= sanitize($referralCode) ?></code>
    </div>
    <div class="empty-state">
        <i class="fas fa-users-rays"></i>
        <p>No referrals recorded yet. Referral tracking will be enabled in a future release.</p>
    </div>
</div>
