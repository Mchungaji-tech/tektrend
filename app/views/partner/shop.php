<?php
/** @var array $templates */
/** @var array|null $partner */
/** @var string $referralCode */
$commissionRate = (float)($partner['commission_rate'] ?? 10.00);
?>
<div class="card mb-4" style="background: linear-gradient(135deg, rgba(228, 147, 102, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%); border-color: rgba(228, 147, 102, 0.35);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.08em; color: var(--primary); margin-bottom: 0.25rem;">
                <i class="fas fa-handshake"></i> Verified Reseller &amp; Wholesale Access
            </div>
            <h2 style="font-size: 1.45rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem;">
                Partner Software Store &amp; Reseller Catalog
            </h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.5;">
                You earn <strong><?= $commissionRate ?>% partner commission</strong> on every turnkey code license and custom system delivered. Acquire licenses directly for your clients or share turnkey demos with your referral code.
            </p>
        </div>
        <div style="display: flex; gap: 0.6rem; align-items: center;">
            <div style="background: var(--bg-card-subtle); padding: 0.6rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.85rem;">
                Code: <strong><?= sanitize($referralCode) ?></strong>
            </div>
            <a href="<?= eurl('/partners/referrals') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Submit Client Lead
            </a>
        </div>
    </div>
</div>

<!-- Category Tabs -->
<div style="display: flex; gap: 0.6rem; flex-wrap: wrap; margin-bottom: 1.75rem;">
    <button class="btn btn-sm btn-primary filter-tab active" onclick="filterPartnerShop('all', this)">All Templates (<?= count($templates) ?>)</button>
    <button class="btn btn-sm btn-outline filter-tab" onclick="filterPartnerShop('ecommerce', this)">E-Commerce</button>
    <button class="btn btn-sm btn-outline filter-tab" onclick="filterPartnerShop('saas', this)">SaaS &amp; Tech</button>
    <button class="btn btn-sm btn-outline filter-tab" onclick="filterPartnerShop('corporate', this)">Corporate &amp; Legal</button>
    <button class="btn btn-sm btn-outline filter-tab" onclick="filterPartnerShop('creative', this)">Creative Studio</button>
</div>

<!-- Template Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
    <?php foreach ($templates as $item): ?>
        <?php
            $price = (float)($item['price'] ?? 49.00);
            $commission = round(($price * $commissionRate) / 100, 2);
            $wholesalePrice = round($price - $commission, 2);
            $catLower = strtolower($item['category'] ?? '');
            $filterTag = 'all';
            if (strpos($catLower, 'commerce') !== false || strpos($catLower, 'retail') !== false) $filterTag .= ' ecommerce';
            if (strpos($catLower, 'saas') !== false || strpos($catLower, 'tech') !== false || strpos($catLower, 'engineering') !== false) $filterTag .= ' saas';
            if (strpos($catLower, 'legal') !== false || strpos($catLower, 'corporate') !== false) $filterTag .= ' corporate';
            if (strpos($catLower, 'creative') !== false || strpos($catLower, 'marketing') !== false || strpos($catLower, 'design') !== false) $filterTag .= ' creative';
            $targetUrl = (strpos($item['demo_url'], 'http') === 0) ? $item['demo_url'] : url($item['demo_url']);
        ?>
        <div class="card partner-shop-item" data-category="<?= $filterTag ?>" style="display: flex; flex-direction: column; justify-content: space-between; padding: 1.5rem; border-radius: var(--radius-md);">
            <div>
                <div style="position: relative; width: 100%; aspect-ratio: 16 / 9; border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 1.25rem; background: #111;">
                    <img src="<?= eurl($item['preview_image'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=800&q=80') ?>" alt="<?= sanitize($item['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <span style="position: absolute; top: 0.75rem; right: 0.75rem; background: rgba(0,0,0,0.85); color: #fff; font-weight: 800; font-size: 0.95rem; padding: 0.35rem 0.75rem; border-radius: 20rem; border: 1px solid rgba(255,255,255,0.2);">
                        $<?= number_format($price, 0) ?>
                    </span>
                    <span style="position: absolute; bottom: 0.75rem; left: 0.75rem; background: rgba(37,99,235,0.9); color: #fff; font-weight: 700; font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 20rem;">
                        Earn +$<?= number_format($commission, 2) ?>
                    </span>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);">
                        <?= sanitize($item['category']) ?>
                    </span>
                    <span style="font-size: 0.75rem; font-weight: 700; color: #60a5fa;">
                        <?= $commissionRate ?>% Commission
                    </span>
                </div>

                <h4 style="font-size: 1.15rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.5rem;">
                    <?= sanitize($item['title']) ?>
                </h4>

                <p style="font-size: 0.85rem; color: var(--text-muted); line-height: 1.5; margin-bottom: 1.25rem;">
                    <?= sanitize($item['short_description']) ?>
                </p>
            </div>

            <div>
                <div style="background: var(--bg-card-subtle, rgba(255,255,255,0.02)); border: 1px solid var(--border); border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700;">Retail Price</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--text-main);">$<?= number_format($price, 0) ?></div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--success); font-weight: 700;">Your Net Price</div>
                        <div style="font-size: 1.1rem; font-weight: 800; color: var(--success);">$<?= number_format($wholesalePrice, 2) ?></div>
                    </div>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="button" class="btn btn-primary" style="flex: 1; font-size: 0.82rem;" onclick="openPartnerOrderModal('<?= htmlspecialchars(addslashes($item['title']), ENT_QUOTES) ?>', '<?= $price ?>', '<?= $commission ?>')">
                        <i class="fas fa-cart-shopping"></i> Order for Client
                    </button>
                    <a href="<?= htmlspecialchars($targetUrl) ?>" target="_blank" class="btn btn-outline" style="font-size: 0.82rem;">
                        <i class="fas fa-eye"></i> Preview
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Order Modal for Partners -->
<div id="partnerOrderModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(8px); z-index: 99999; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="card" style="max-width: 550px; width: 100%; position: relative;">
        <div class="card-header">
            <div>
                <h3 class="card-title">Order Template for Client</h3>
                <p class="card-subtitle">Your partner discount &amp; commission will be applied</p>
            </div>
            <button onclick="document.getElementById('partnerOrderModal').style.display='none'" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer;">&times;</button>
        </div>

        <form action="<?= eurl('/live-demos/request') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <input type="hidden" name="action_type" value="buy_template">

            <div class="form-group">
                <label>Template</label>
                <input type="text" name="template_title" id="partnerModalTitle" class="form-control" readonly style="font-weight: 700; color: var(--primary);">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label class="required">Partner / Contact Name</label>
                    <input type="text" name="name" required class="form-control" value="<?= sanitize($_SESSION['user_name'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="required">Contact Email</label>
                    <input type="email" name="email" required class="form-control" value="<?= sanitize($_SESSION['user_email'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Client Organization or Domain</label>
                <input type="text" name="company" class="form-control" placeholder="Client business name or planned domain">
            </div>

            <div class="form-group">
                <label>Setup / Handover Notes</label>
                <textarea name="notes" rows="2" class="form-control" placeholder="Specify turnkey source code handover or installation requirements..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('partnerOrderModal').style.display='none'">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Place Order</button>
            </div>
        </form>
    </div>
</div>

<script>
function openPartnerOrderModal(title, price, commission) {
    document.getElementById('partnerModalTitle').value = title + ' ($' + price + ' - Partner Net: $' + (price - commission).toFixed(2) + ')';
    document.getElementById('partnerOrderModal').style.display = 'flex';
}

function filterPartnerShop(category, btn) {
    document.querySelectorAll('.filter-tab').forEach(b => {
        b.classList.remove('btn-primary', 'active');
        b.classList.add('btn-outline');
    });
    btn.classList.add('btn-primary', 'active');
    btn.classList.remove('btn-outline');

    document.querySelectorAll('.partner-shop-item').forEach(card => {
        const cat = card.getAttribute('data-category') || '';
        if (category === 'all' || cat.includes(category)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
