<?php
$csrfToken = csrf_token();
$referralUrl = url('/contact?ref=' . urlencode($referralCode));
$commissionRate = (float)($partner['commission_rate'] ?? 10.00);
?>

<div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Status</span>
            <div class="stat-icon" style="background: rgba(16,185,129,0.15); color: #10b981; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-badge-check"></i></div>
        </div>
        <span class="value" style="font-size: 1.35rem; font-weight:700; color: #10b981;"><?= sanitize(ucfirst($partner['status'] ?? 'approved')) ?></span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Verified Reseller & Affiliate</p>
    </div>

    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Commission Rate</span>
            <div class="stat-icon" style="background: rgba(224,90,58,0.15); color: #e05a3a; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-percent"></i></div>
        </div>
        <span class="value" style="font-size: 1.35rem; font-weight:700; color: var(--text);"><?= rtrim(rtrim(number_format($commissionRate, 2), '0'), '.') ?>%</span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">On every closed referral</p>
    </div>

    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Referred Clients</span>
            <div class="stat-icon" style="background: rgba(59,130,246,0.15); color: #3b82f6; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-users-rays"></i></div>
        </div>
        <span class="value" style="font-size: 1.35rem; font-weight:700; color: var(--text);"><?= (int)($stats['total_referrals'] ?? 0) ?></span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;"><?= (int)($stats['closed_deals'] ?? 0) ?> deals won / contracted</p>
    </div>

    <div class="stat-card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.25rem;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:.5rem;">
            <span class="label" style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Earned Commission</span>
            <div class="stat-icon" style="background: rgba(168,85,247,0.15); color: #a855f7; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center;"><i class="fas fa-sack-dollar"></i></div>
        </div>
        <span class="value" style="font-size: 1.35rem; font-weight:700; color: #10b981;"><?= formatCurrency((float)($stats['earned_commission'] ?? 0)) ?></span>
        <p style="font-size:0.75rem; color:var(--text-muted); margin-top:0.25rem;">Pending Payout: <?= formatCurrency((float)($stats['pending_payout'] ?? 0)) ?></p>
    </div>
</div>

<!-- Referral & Growth Hub -->
<div class="card" style="background: linear-gradient(135deg, rgba(224,90,58,0.08) 0%, rgba(20,20,22,0.95) 100%); border: 1px solid rgba(224,90,58,0.25); border-radius: var(--radius-lg); padding: 1.75rem; margin-bottom: 2rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom: 1.25rem;">
        <div>
            <span style="display:inline-flex; align-items:center; gap:0.4rem; font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--primary); background:rgba(224,90,58,0.15); padding:0.25rem 0.65rem; border-radius:999px; margin-bottom:0.5rem;">
                <i class="fas fa-bullhorn"></i> Partner Reseller Toolkit
            </span>
            <h3 style="font-size:1.35rem; font-weight:700; margin:0;">Share Your Dedicated Referral Link</h3>
            <p style="color:var(--text-muted); font-size:0.9rem; margin-top:0.25rem;">
                Send your unique link to clients, leads, or companies needing custom web, enterprise, or mobile software. All deals initiated through your link are tagged to your partner ledger.
            </p>
        </div>
        <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">
            <a href="<?= url('/partners/referrals') ?>" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-user-plus"></i> Submit Client Lead
            </a>
            <a href="<?= url('/partners/shop') ?>" class="btn btn-outline" style="display:inline-flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-bag-shopping"></i> Reseller Store
            </a>
        </div>
    </div>

    <div style="background: var(--bg-surface); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1rem 1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div style="display:flex; align-items:center; gap:0.75rem; flex-grow:1; min-width:260px;">
            <i class="fas fa-link" style="color:var(--primary); font-size:1.1rem;"></i>
            <div style="overflow:hidden;">
                <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase;">Direct Referral URL</div>
                <code id="partnerReferralUrl" style="font-size:0.95rem; color:var(--text); word-break:break-all; font-family:monospace; background:transparent; padding:0;"><?= sanitize($referralUrl) ?></code>
            </div>
        </div>
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <span style="font-size:0.8rem; background:rgba(255,255,255,0.06); padding:0.4rem 0.75rem; border-radius:4px; font-weight:600; color:var(--text-muted);">
                Code: <strong style="color:var(--text);"><?= sanitize($referralCode) ?></strong>
            </span>
            <button type="button" class="btn btn-sm btn-outline" onclick="copyReferralLink()" id="copyBtn" style="min-width:100px;">
                <i class="fas fa-copy"></i> Copy Link
            </button>
        </div>
    </div>
</div>

<!-- Two Column Layout: Recent Pipeline & Turnkey Reseller Catalog -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Left: Client Pipeline Overview -->
    <div class="card" style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div>
                <h3 style="font-size:1.15rem; font-weight:700; margin:0;">Active Client Pipeline</h3>
                <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem;">Recent deals & referred contacts</p>
            </div>
            <a href="<?= url('/partners/referrals') ?>" style="font-size:0.85rem; color:var(--primary); font-weight:600; text-decoration:none;">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <?php if (!empty($recentReferrals)): ?>
            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                <?php foreach ($recentReferrals as $r): ?>
                    <div style="padding:0.85rem 1rem; background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-md); display:flex; justify-content:space-between; align-items:center; gap:0.75rem;">
                        <div>
                            <div style="font-weight:600; font-size:0.95rem; color:var(--text);"><?= sanitize($r['client_name']) ?></div>
                            <div style="font-size:0.75rem; color:var(--text-muted);"><?= sanitize($r['project_type'] ?? 'Custom Project') ?> · <?= date('M d, Y', strtotime($r['created_at'])) ?></div>
                        </div>
                        <div style="text-align:right;">
                            <?php 
                                $statusBadge = [
                                    'lead'        => ['label' => 'New Lead', 'bg' => 'rgba(59,130,246,0.15)', 'color' => '#3b82f6'],
                                    'negotiation' => ['label' => 'Negotiating', 'bg' => 'rgba(234,179,8,0.15)', 'color' => '#eab308'],
                                    'closed_won'  => ['label' => 'Closed Won', 'bg' => 'rgba(16,185,129,0.15)', 'color' => '#10b981'],
                                    'paid'        => ['label' => 'Commission Paid', 'bg' => 'rgba(168,85,247,0.15)', 'color' => '#a855f7'],
                                    'closed_lost' => ['label' => 'Closed Lost', 'bg' => 'rgba(239,68,68,0.15)', 'color' => '#ef4444'],
                                ][$r['status'] ?? 'lead'] ?? ['label' => ucfirst($r['status'] ?? 'lead'), 'bg' => 'rgba(255,255,255,0.1)', 'color' => '#aaa'];
                            ?>
                            <span style="font-size:0.7rem; font-weight:700; padding:0.25rem 0.55rem; border-radius:999px; background:<?= $statusBadge['bg'] ?>; color:<?= $statusBadge['color'] ?>;">
                                <?= $statusBadge['label'] ?>
                            </span>
                            <div style="font-size:0.8rem; font-weight:700; color:var(--text); margin-top:0.25rem;">
                                <?= formatCurrency((float)($r['commission_amount'] ?? 0)) ?> commission
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:2.5rem 1rem; background:var(--bg-surface); border-radius:var(--radius-md); border:1px dashed var(--border);">
                <i class="fas fa-users-rays" style="font-size:2rem; color:var(--text-muted); opacity:0.5; margin-bottom:0.75rem;"></i>
                <p style="color:var(--text); font-weight:600; margin-bottom:0.25rem;">No Referrals Logged Yet</p>
                <p style="font-size:0.8rem; color:var(--text-muted); max-width:280px; margin:0 auto 1rem;">
                    Submit prospective clients or share your link to start receiving <?= rtrim(rtrim(number_format($commissionRate, 2), '0'), '.') ?>% revenue share.
                </p>
                <a href="<?= url('/partners/referrals') ?>" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus"></i> Submit First Client Lead
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right: Reseller Store Quick Catalog -->
    <div class="card" style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:1.5rem;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div>
                <h3 style="font-size:1.15rem; font-weight:700; margin:0;">Turnkey Software Catalog</h3>
                <p style="font-size:0.8rem; color:var(--text-muted); margin-top:0.2rem;">Pre-built solutions ready to resell or deploy</p>
            </div>
            <a href="<?= url('/partners/shop') ?>" style="font-size:0.85rem; color:var(--primary); font-weight:600; text-decoration:none;">
                Browse Store <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <?php if (!empty($templates)): ?>
            <div style="display:flex; flex-direction:column; gap:0.75rem;">
                <?php foreach (array_slice($templates, 0, 4) as $tpl): 
                    $price = (float)($tpl['price'] ?? 199);
                    $partnerCut = round($price * ($commissionRate / 100), 2);
                    $partnerWholesale = max(0, $price - $partnerCut);
                ?>
                    <div style="padding:0.85rem 1rem; background:var(--bg-surface); border:1px solid var(--border); border-radius:var(--radius-md); display:flex; justify-content:space-between; align-items:center; gap:0.75rem;">
                        <div style="overflow:hidden;">
                            <div style="font-weight:600; font-size:0.95rem; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= sanitize($tpl['title']) ?></div>
                            <div style="font-size:0.75rem; color:var(--text-muted);">
                                Retail: <span style="text-decoration:line-through;"><?= formatCurrency($price) ?></span> 
                                · <strong style="color:var(--primary);">Wholesale: <?= formatCurrency($partnerWholesale) ?></strong>
                            </div>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <span style="display:inline-block; font-size:0.7rem; font-weight:700; color:#10b981; background:rgba(16,185,129,0.15); padding:0.2rem 0.5rem; border-radius:4px;">
                                +<?= formatCurrency($partnerCut) ?> margin
                            </span>
                            <div style="margin-top:0.35rem;">
                                <a href="<?= url('/partners/shop') ?>" class="btn btn-sm btn-outline" style="padding:0.2rem 0.6rem; font-size:0.75rem;">Order</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center; padding:2rem 1rem; color:var(--text-muted);">
                <p>Visit the partner store to explore all turnkey architectures.</p>
                <a href="<?= url('/partners/shop') ?>" class="btn btn-sm btn-outline">Explore Store</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Partner Information & Support Profile -->
<div class="card" style="background:var(--bg-card); border:1px solid var(--border); border-radius:var(--radius-lg); padding:1.5rem;">
    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
        <div>
            <h3 style="font-size:1.15rem; font-weight:700; margin:0 0 0.25rem 0;">Partner Account Details</h3>
            <p style="font-size:0.85rem; color:var(--text-muted); margin:0;">
                Organization: <strong style="color:var(--text);"><?= sanitize($partner['company_name'] ?? 'Authorized Partner') ?></strong> 
                <?php if (!empty($partner['website'])): ?>
                    · <a href="<?= sanitize($partner['website']) ?>" target="_blank" rel="noopener" style="color:var(--primary);"><?= sanitize($partner['website']) ?></a>
                <?php endif; ?>
            </p>
        </div>
        <div>
            <a href="<?= url('/contact') ?>?subject=Partner+Assistance" class="btn btn-sm btn-outline" style="display:inline-flex; align-items:center; gap:0.4rem;">
                <i class="fas fa-headset"></i> Contact Solutions Executive
            </a>
        </div>
    </div>
</div>

<script>
function copyReferralLink() {
    const url = document.getElementById('partnerReferralUrl').innerText;
    navigator.clipboard.writeText(url).then(() => {
        const btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i class="fas fa-check" style="color:#10b981;"></i> Copied!';
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-copy"></i> Copy Link';
        }, 2500);
    }).catch(err => {
        alert('Referral URL: ' + url);
    });
}
</script>
