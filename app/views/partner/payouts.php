<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--success-light); color: var(--success);"><i class="fas fa-wallet"></i></div>
        <span class="label">Available</span>
        <span class="value" style="font-size: 1.3rem;"><?= formatCurrency(0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--primary-light); color: var(--primary);"><i class="fas fa-clock"></i></div>
        <span class="label">Pending</span>
        <span class="value" style="font-size: 1.3rem;"><?= formatCurrency(0) ?></span>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: var(--accent-light); color: var(--accent);"><i class="fas fa-receipt"></i></div>
        <span class="label">Lifetime Earned</span>
        <span class="value" style="font-size: 1.3rem;"><?= formatCurrency(0) ?></span>
    </div>
</div>

<div class="card">
    <div class="card-header"><h3 class="card-title">Payout History</h3></div>
    <div class="empty-state">
        <i class="fas fa-sack-dollar"></i>
        <p>No payouts yet. The commission ledger will be enabled in a future release.</p>
    </div>
</div>
