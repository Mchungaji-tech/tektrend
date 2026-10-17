<?php
$csrfToken = csrf_token();
$referralUrl = url('/contact?ref=' . urlencode($referralCode));
$commissionRate = (float)($partner['commission_rate'] ?? 10.00);
?>

<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; margin-bottom: 2rem;">
    <div>
        <h2 style="font-size:1.6rem; font-weight:700; margin:0 0 0.25rem 0;">Client Pipeline & Referrals</h2>
        <p style="color:var(--text-muted); margin:0;">Track prospective clients, deals in progress, and earned commission payouts.</p>
    </div>
    <div style="display:flex; gap:0.75rem;">
        <button type="button" class="btn btn-primary" onclick="document.getElementById('newReferralFormCard').scrollIntoView({behavior: 'smooth'})" style="display:inline-flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-plus"></i> Submit New Lead
        </button>
        <a href="<?= url('/partners/shop') ?>" class="btn btn-outline" style="display:inline-flex; align-items:center; gap:0.5rem;">
            <i class="fas fa-bag-shopping"></i> Reseller Store
        </a>
    </div>
</div>

<!-- Referral Share Bar -->
<div class="card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
        <div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--primary); margin-bottom:0.25rem;">Your Reseller Link</div>
            <code id="refUrlText" style="font-size:0.95rem; font-weight:600; background:var(--bg-surface); padding:0.4rem 0.8rem; border-radius:var(--radius-sm); border:1px solid var(--border);"><?= sanitize($referralUrl) ?></code>
        </div>
        <div style="display:flex; gap:0.5rem; align-items:center;">
            <span style="font-size:0.85rem; color:var(--text-muted);">Partner ID: <strong style="color:var(--text);"><?= sanitize($referralCode) ?></strong></span>
            <button class="btn btn-outline btn-sm" onclick="copyRef()"><i class="fas fa-copy"></i> Copy Link</button>
        </div>
    </div>
</div>

<!-- Referrals Table -->
<div class="card" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2rem;">
    <div class="card-header" style="margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 class="card-title" style="margin:0;">Registered Client Pipeline</h3>
            <p class="card-subtitle" style="margin-top:0.25rem; font-size:0.85rem;">All opportunities linked to your partner code</p>
        </div>
        <span style="font-size:0.85rem; color:var(--text-muted); background:var(--bg-surface); padding:0.35rem 0.75rem; border-radius:999px; border:1px solid var(--border);">
            Total: <strong><?= count($referrals) ?></strong> leads
        </span>
    </div>

    <?php if (!empty($referrals)): ?>
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:0.9rem;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border); text-align:left; color:var(--text-muted); font-size:0.75rem; text-transform:uppercase;">
                        <th style="padding:0.75rem 0.5rem;">Client / Organization</th>
                        <th style="padding:0.75rem 0.5rem;">Contact</th>
                        <th style="padding:0.75rem 0.5rem;">Project Scope</th>
                        <th style="padding:0.75rem 0.5rem;">Est. Value</th>
                        <th style="padding:0.75rem 0.5rem;">Commission (<?= rtrim(rtrim(number_format($commissionRate, 2), '0'), '.') ?>%)</th>
                        <th style="padding:0.75rem 0.5rem;">Status</th>
                        <th style="padding:0.75rem 0.5rem;">Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($referrals as $ref): 
                        $statusBadge = [
                            'lead'        => ['label' => 'New Lead', 'bg' => 'rgba(59,130,246,0.15)', 'color' => '#3b82f6'],
                            'negotiation' => ['label' => 'Negotiation', 'bg' => 'rgba(234,179,8,0.15)', 'color' => '#eab308'],
                            'closed_won'  => ['label' => 'Contracted / Won', 'bg' => 'rgba(16,185,129,0.15)', 'color' => '#10b981'],
                            'paid'        => ['label' => 'Commission Paid', 'bg' => 'rgba(168,85,247,0.15)', 'color' => '#a855f7'],
                            'closed_lost' => ['label' => 'Lost', 'bg' => 'rgba(239,68,68,0.15)', 'color' => '#ef4444'],
                        ][$ref['status'] ?? 'lead'] ?? ['label' => ucfirst($ref['status'] ?? 'lead'), 'bg' => 'rgba(255,255,255,0.1)', 'color' => '#aaa'];
                    ?>
                        <tr style="border-bottom: 1px solid var(--border);">
                            <td style="padding:1rem 0.5rem;">
                                <strong style="color:var(--text);"><?= sanitize($ref['client_name']) ?></strong>
                            </td>
                            <td style="padding:1rem 0.5rem; color:var(--text-muted);">
                                <div><a href="mailto:<?= sanitize($ref['client_email']) ?>" style="color:var(--primary); text-decoration:none;"><?= sanitize($ref['client_email']) ?></a></div>
                                <?php if (!empty($ref['client_phone'])): ?>
                                    <div style="font-size:0.8rem;"><?= sanitize($ref['client_phone']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="padding:1rem 0.5rem;">
                                <?= sanitize($ref['project_type'] ?? 'Custom Web Solution') ?>
                            </td>
                            <td style="padding:1rem 0.5rem; font-weight:600;">
                                <?= formatCurrency((float)($ref['estimated_value'] ?? 0)) ?>
                            </td>
                            <td style="padding:1rem 0.5rem; font-weight:700; color:#10b981;">
                                <?= formatCurrency((float)($ref['commission_amount'] ?? 0)) ?>
                            </td>
                            <td style="padding:1rem 0.5rem;">
                                <span style="font-size:0.75rem; font-weight:700; padding:0.25rem 0.6rem; border-radius:999px; background:<?= $statusBadge['bg'] ?>; color:<?= $statusBadge['color'] ?>;">
                                    <?= $statusBadge['label'] ?>
                                </span>
                            </td>
                            <td style="padding:1rem 0.5rem; font-size:0.8rem; color:var(--text-muted);">
                                <?= date('M d, Y', strtotime($ref['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="text-align:center; padding:3rem 1rem;">
            <i class="fas fa-users-rays" style="font-size:2.5rem; color:var(--text-muted); opacity:0.5; margin-bottom:1rem;"></i>
            <h4 style="margin:0 0 0.5rem 0; font-weight:600;">No Referrals Recorded Yet</h4>
            <p style="color:var(--text-muted); font-size:0.9rem; max-width:400px; margin:0 auto 1.5rem;">
                When you introduce clients to Tek Trend or submit project leads, they will appear here with live pipeline progress and commission calculations.
            </p>
        </div>
    <?php endif; ?>
</div>

<!-- Submit New Client Lead Form -->
<div class="card" id="newReferralFormCard" style="background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.75rem;">
    <div style="margin-bottom:1.5rem;">
        <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--primary); background:rgba(224,90,58,0.15); padding:0.2rem 0.6rem; border-radius:999px;">
            Direct Pipeline Insertion
        </span>
        <h3 style="font-size:1.3rem; font-weight:700; margin:0.5rem 0 0.25rem 0;">Submit a Prospective Client Lead</h3>
        <p style="color:var(--text-muted); font-size:0.85rem; margin:0;">
            Tek Trend engineering & solution architects will contact the lead, scope the architecture, and close the contract. Upon signing, your <?= rtrim(rtrim(number_format($commissionRate, 2), '0'), '.') ?>% commission is credited directly to your partner balance.
        </p>
    </div>

    <form method="POST" action="<?= url('/partners/referrals/submit') ?>">
        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Client / Company Name *</label>
                <input type="text" name="client_name" class="form-control" placeholder="e.g. Acme Logistics or John Doe" required style="width:100%; padding:0.65rem 0.85rem; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--bg-surface); color:var(--text);">
            </div>
            <div class="form-group">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Client Email *</label>
                <input type="email" name="client_email" class="form-control" placeholder="lead@company.com" required style="width:100%; padding:0.65rem 0.85rem; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--bg-surface); color:var(--text);">
            </div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:1.25rem; margin-bottom:1.25rem;">
            <div class="form-group">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Client Phone / WhatsApp</label>
                <input type="text" name="client_phone" class="form-control" placeholder="+254 700 000 000" style="width:100%; padding:0.65rem 0.85rem; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--bg-surface); color:var(--text);">
            </div>
            <div class="form-group">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Project Category</label>
                <select name="project_type" class="form-control" style="width:100%; padding:0.65rem 0.85rem; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--bg-surface); color:var(--text);">
                    <option value="Enterprise Web Platform">Enterprise Web Platform</option>
                    <option value="Mobile App Development">Mobile App (iOS / Android)</option>
                    <option value="E-Commerce & Payment Engine">E-Commerce & Payment Engine</option>
                    <option value="Custom CRM & ERP Architecture">Custom CRM & ERP Architecture</option>
                    <option value="Turnkey Software Customization">Turnkey Software Deployment</option>
                    <option value="Cloud Migration & DevOps">Cloud Migration & DevOps</option>
                </select>
            </div>
            <div class="form-group">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Estimated Deal Value (USD)</label>
                <input type="number" name="estimated_value" class="form-control" value="800" min="50" step="50" style="width:100%; padding:0.65rem 0.85rem; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--bg-surface); color:var(--text);">
            </div>
        </div>

        <div class="form-group" style="margin-bottom:1.5rem;">
            <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Project Notes & Requirements</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Briefly describe what the client needs, target timeline, or special requirements..." style="width:100%; padding:0.65rem 0.85rem; border-radius:var(--radius-sm); border:1px solid var(--border); background:var(--bg-surface); color:var(--text);"></textarea>
        </div>

        <button type="submit" class="btn btn-primary" style="padding:0.75rem 1.5rem; font-size:0.95rem; font-weight:600;">
            <i class="fas fa-paper-plane"></i> Submit Client Referral
        </button>
    </form>
</div>

<script>
function copyRef() {
    const text = document.getElementById('refUrlText').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Referral URL copied to clipboard!');
    }).catch(err => {
        alert('Referral URL: ' + text);
    });
}
</script>
