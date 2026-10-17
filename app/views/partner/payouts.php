<?php
$earned = (float)($payoutStats['total_earned'] ?? 0);
$pending = (float)($payoutStats['pending_payout'] ?? 0);
$paid = (float)($payoutStats['paid_out'] ?? 0);
$commissionRate = (float)($partner['commission_rate'] ?? 10.00);
?>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Available for Payout</span>
            <div class="stat-icon" style="background: rgba(16,185,129,0.15); color: #10b981; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-wallet"></i></div>
        </div>
        <span class="value" style="font-size: 1.4rem; font-weight:700; color: #10b981;"><?= formatCurrency($pending) ?></span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Ready to disburse via Bank or M-Pesa</p>
    </div>

    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Disbursed Payouts</span>
            <div class="stat-icon" style="background: rgba(59,130,246,0.15); color: #3b82f6; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-money-check-dollar"></i></div>
        </div>
        <span class="value" style="font-size: 1.4rem; font-weight:700; color: var(--text);"><?= formatCurrency($paid) ?></span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Total cleared to your accounts</p>
    </div>

    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">Lifetime Commission</span>
            <div class="stat-icon" style="background: rgba(224,90,58,0.15); color: #e05a3a; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-receipt"></i></div>
        </div>
        <span class="value" style="font-size: 1.4rem; font-weight:700; color: var(--text);"><?= formatCurrency($earned) ?></span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Cumulative partner earnings</p>
    </div>
</div>

<div class="card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom:1.5rem;">
        <div>
            <h3 class="card-title" style="margin:0;">Payout Disbursement Methods</h3>
            <p class="card-subtitle" style="margin-top:0.25rem; font-size:0.85rem;">Payouts are processed on the 1st and 15th of every month or upon request once exceeding $50.</p>
        </div>
        <a href="<?= url('/contact') ?>?subject=Partner+Payout+Request" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-paper-plane"></i> Request Settlement
        </a>
    </div>

    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap:1rem;">
        <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-md); padding:1rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.5rem;">
                <i class="fas fa-mobile-screen-button" style="color:#10b981; font-size:1.2rem;"></i>
                <h4 style="margin:0; font-size:1rem; font-weight:700;">M-Pesa Express</h4>
            </div>
            <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">
                Direct B2C instant disbursement to your registered phone number. Converted automatically to KES at mid-market rates.
            </p>
        </div>

        <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-md); padding:1rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.5rem;">
                <i class="fas fa-building-columns" style="color:#3b82f6; font-size:1.2rem;"></i>
                <h4 style="margin:0; font-size:1rem; font-weight:700;">Direct Wire / EFT / RTGS</h4>
            </div>
            <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">
                Domestic and international corporate bank transfer. Ideal for registered development agencies and IT consultants.
            </p>
        </div>

        <div style="background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-md); padding:1rem;">
            <div style="display:flex; align-items:center; gap:0.6rem; margin-bottom:0.5rem;">
                <i class="fab fa-paypal" style="color:#a855f7; font-size:1.2rem;"></i>
                <h4 style="margin:0; font-size:1rem; font-weight:700;">PayPal / Wise</h4>
            </div>
            <p style="font-size:0.8rem; color:var(--text-muted); margin:0;">
                Global USD settlements for international affiliates, digital agencies, and independent advisors.
            </p>
        </div>
    </div>
</div>
