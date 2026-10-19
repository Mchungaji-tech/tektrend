<?php
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyEmail = $settings['company_email'] ?? 'tektrend.softwares@gmail.com';
$companyAddress = $settings['company_address'] ?? 'Tek Cyber, Roadblock, Eldoret, Kenya';
$isPartner = ($role === 'partner');
$commissionRate = $partner['commission_rate'] ?? 10.0;
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Turnkey Software Store &amp; Architecture Packages · Tektrend Softwares</title>
  <meta name="description" content="Shop verified production source code, turnkey PHP &amp; modern web architectures, and e-commerce templates by Tektrend Softwares Eldoret, Kenya.">
  <meta name="keywords" content="Tektrend Softwares shop, buy web templates, turnkey software, source code marketplace, Eldoret Kenya">
  <link rel="canonical" href="<?= eurl('/shop') ?>">
  <meta name="theme-color" content="#0e0e0e">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">

  <!-- Fonts & Icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= eurl('/assets/css/main.css') ?>">

  <style>
    .shop-hero {
      padding: clamp(10rem, 15vh, 16rem) 5% 5rem;
      max-width: 1400px;
      margin: 0 auto;
    }
    .shop-partner-banner {
      background: linear-gradient(135deg, rgba(228, 147, 102, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%);
      border: 1px solid rgba(228, 147, 102, 0.35);
      border-radius: 1.6rem;
      padding: 1.8rem 2.4rem;
      margin-bottom: 3.5rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1.5rem;
    }
    .shop-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 3rem;
      max-width: 1400px;
      margin: 0 auto 8rem;
      padding: 0 5%;
    }
    .shop-card {
      background: var(--bg-card);
      border: 1px solid var(--border-subtle);
      border-radius: 2.2rem;
      padding: 2.4rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.3s ease, box-shadow 0.3s ease;
      position: relative;
    }
    .shop-card:hover {
      transform: translateY(-6px);
      border-color: rgba(228, 147, 102, 0.4);
      box-shadow: 0 20px 40px rgba(0,0,0,0.35);
    }
    .shop-thumb {
      width: 100%;
      aspect-ratio: 16 / 10;
      border-radius: 1.4rem;
      overflow: hidden;
      background: #151515;
      margin-bottom: 2rem;
      position: relative;
    }
    .shop-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.6s ease;
    }
    .shop-card:hover .shop-thumb img {
      transform: scale(1.05);
    }
    .shop-price-badge {
      position: absolute;
      top: 1.4rem;
      right: 1.4rem;
      background: rgba(14, 14, 14, 0.88);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.15);
      padding: 0.6rem 1.2rem;
      border-radius: 10rem;
      font-family: 'Syne', sans-serif;
      font-size: 1.5rem;
      font-weight: 800;
      color: #fff;
    }
    .btn-buy-now {
      flex: 1;
      padding: 1.2rem 1.8rem;
      border-radius: 10rem;
      background: var(--terra);
      color: #0e0e0e;
      font-size: 1.3rem;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.7rem;
      transition: all 0.25s ease;
    }
    .btn-buy-now:hover {
      filter: brightness(1.15);
      transform: translateY(-2px);
    }
    .btn-view-demo {
      padding: 1.2rem 1.6rem;
      border-radius: 10rem;
      border: 1px solid var(--border-subtle);
      background: transparent;
      color: var(--text-primary);
      font-size: 1.25rem;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
      transition: all 0.25s ease;
    }
    .btn-view-demo:hover {
      background: rgba(255,255,255,0.06);
      border-color: rgba(255,255,255,0.3);
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header class="akar-header" id="akarHeader">
    <a href="<?= eurl('/') ?>" class="akar-brand">
      <span class="akar-brand-logo"><i class="fas fa-layer-group"></i></span>
      <span class="akar-brand-text">Tektrend<span>.</span>Softwares</span>
    </a>

    <div style="display: flex; align-items: center; gap: 1.2rem;">
      <a href="<?= eurl('/live-demos') ?>" class="akar-palette-pill" style="text-decoration:none; color:var(--text-primary);">
        <i class="fas fa-desktop"></i>
        <span class="akar-palette-label">Live Demos</span>
      </a>
      <a href="<?= eurl('/shop') ?>" class="akar-palette-pill" style="text-decoration:none; background: var(--terra); color:#0e0e0e;">
        <i class="fas fa-shopping-bag"></i>
        <span class="akar-palette-label">Shop (<?= count($templates) ?>)</span>
      </a>
      <a href="<?= eurl('/faq') ?>" class="akar-palette-pill" style="text-decoration:none; color:var(--text-primary);">
        <i class="fas fa-circle-question"></i>
        <span class="akar-palette-label">FAQ</span>
      </a>
      <?php if (!empty($user)): ?>
        <a href="<?= eurl(homeUrlForRole($role)) ?>" class="akar-palette-pill" style="text-decoration:none; color:var(--text-primary);">
          <i class="fas fa-user"></i>
          <span class="akar-palette-label">My Portal</span>
        </a>
      <?php else: ?>
        <a href="<?= eurl('/login') ?>" class="akar-palette-pill" style="text-decoration:none; color:var(--text-primary);">
          <i class="fas fa-sign-in-alt"></i>
          <span class="akar-palette-label">Sign In</span>
        </a>
      <?php endif; ?>
    </div>
  </header>

  <!-- HERO SECTION -->
  <section class="shop-hero">
    <div style="font-size: 1.3rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--terra); margin-bottom: 1.2rem;">
      [ TURNKEY SOFTWARE PACKAGES · COMMERCIAL CODEBASES ]
    </div>
    <h1 style="font-family: 'Syne', sans-serif; font-size: clamp(3.6rem, 5.5vw, 6.4rem); font-weight: 900; letter-spacing: -0.04em; line-height: 1.05; margin-bottom: 1.8rem;">
      Turnkey Systems &amp; Software Store
    </h1>
    <p style="font-size: 1.8rem; color: var(--text-secondary); max-width: 82rem; line-height: 1.6; margin-bottom: 3rem;">
      Acquire turnkey, production-engineered software packages with clean MVC architecture, responsive styling, and complete commercial rights. Instant source code download &amp; deployment support included.
    </p>

    <?php if ($isPartner): ?>
      <div class="shop-partner-banner">
        <div>
          <div style="font-size: 1.15rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; color: var(--terra); margin-bottom: 0.3rem;">
            <i class="fas fa-handshake"></i> Verified Partner Status Active
          </div>
          <div style="font-size: 1.6rem; font-weight: 700; color: #fff;">
            You earn a <strong><?= $commissionRate ?>% Partner Commission</strong> on all purchases and client deployments!
          </div>
        </div>
        <a href="<?= eurl('/partners') ?>" class="btn-view-demo" style="border-color: var(--terra); color: var(--terra);">
          <i class="fas fa-chart-pie"></i> Partner Dashboard
        </a>
      </div>
    <?php endif; ?>

    <!-- NOTICE: Real Domain Projects Distinction -->
    <div style="background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.25); border-radius: 1.4rem; padding: 1.4rem 2rem; margin-bottom: 4rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
      <div style="display: flex; align-items: center; gap: 1rem;">
        <i class="fas fa-info-circle" style="color: #60a5fa; font-size: 1.6rem;"></i>
        <div style="font-size: 1.35rem; color: #cbd5e1;">
          Looking for our completed client systems on real domains? Those are bespoke client projects and are <strong>not for sale</strong>.
        </div>
      </div>
      <a href="<?= eurl('/live-demos') ?>" style="color: #60a5fa; font-weight: 700; font-size: 1.3rem; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
        View Live Client Deployments <i class="fas fa-arrow-right"></i>
      </a>
    </div>

    <!-- FILTER BUTTONS -->
    <div class="akar-filters-wrap" style="margin-bottom: 4rem;">
      <button class="akar-filter-btn --active" data-filter="all" onclick="filterShop('all')">
        <span class="akar-filter-dot"></span> All Templates (<?= count($templates) ?>)
      </button>
      <button class="akar-filter-btn" data-filter="ecommerce" onclick="filterShop('ecommerce')">
        <span class="akar-filter-dot"></span> E-Commerce &amp; Retail
      </button>
      <button class="akar-filter-btn" data-filter="saas" onclick="filterShop('saas')">
        <span class="akar-filter-dot"></span> SaaS &amp; Cloud
      </button>
      <button class="akar-filter-btn" data-filter="corporate" onclick="filterShop('corporate')">
        <span class="akar-filter-dot"></span> Corporate &amp; Legal
      </button>
      <button class="akar-filter-btn" data-filter="creative" onclick="filterShop('creative')">
        <span class="akar-filter-dot"></span> Creative &amp; Marketing
      </button>
    </div>
  </section>

  <!-- SHOPPING GRID -->
  <section class="shop-grid">
    <?php foreach ($templates as $idx => $item): ?>
      <?php
        $price = (float)($item['price'] ?? 49.00);
        $catLower = strtolower($item['category'] ?? '');
        $filterClass = 'all';
        if (strpos($catLower, 'commerce') !== false || strpos($catLower, 'retail') !== false) $filterClass .= ' ecommerce';
        if (strpos($catLower, 'saas') !== false || strpos($catLower, 'tech') !== false || strpos($catLower, 'engineering') !== false) $filterClass .= ' saas';
        if (strpos($catLower, 'legal') !== false || strpos($catLower, 'corporate') !== false) $filterClass .= ' corporate';
        if (strpos($catLower, 'creative') !== false || strpos($catLower, 'marketing') !== false || strpos($catLower, 'design') !== false) $filterClass .= ' creative';
        $targetDemoUrl = (strpos($item['demo_url'], 'http') === 0) ? $item['demo_url'] : url($item['demo_url']);
      ?>
      <div class="shop-card" data-category="<?= $filterClass ?>">
        <div>
          <!-- Thumbnail & Price -->
          <div class="shop-thumb">
            <img src="<?= eurl($item['preview_image'] ?: '/assets/img/demo-placeholder.svg') ?>" alt="<?= sanitize($item['title']) ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= eurl('/assets/img/demo-placeholder.svg') ?>';">
            <div class="shop-price-badge">
              $<?= number_format($price, 0) ?>
            </div>
          </div>

          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <span class="akar-pill-tag"><?= sanitize($item['category']) ?></span>
            <span style="font-size: 1.15rem; font-weight: 700; color: #22c55e;"><i class="fas fa-check-circle"></i> Ready to Deploy</span>
          </div>

          <h3 style="font-family: 'Syne', sans-serif; font-size: 2.3rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 1rem;">
            <?= sanitize($item['title']) ?>
          </h3>

          <p style="font-size: 1.45rem; color: var(--text-secondary); line-height: 1.55; margin-bottom: 2rem;">
            <?= sanitize($item['short_description']) ?>
          </p>

          <!-- Specifications -->
          <div style="background: var(--bg-card-subtle, rgba(255,255,255,0.02)); border: 1px solid var(--border-subtle); border-radius: 1.2rem; padding: 1.4rem; margin-bottom: 2rem;">
            <div style="font-size: 1.2rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; color: var(--text-secondary); margin-bottom: 0.8rem;">
              Included Deliverables:
            </div>
            <ul style="list-style: none; padding: 0; margin: 0; font-size: 1.3rem; color: var(--text-primary); line-height: 1.7;">
              <li><i class="fas fa-code" style="color: var(--terra); margin-right: 0.6rem;"></i> Full Turnkey Source Code</li>
              <li><i class="fas fa-mobile-screen" style="color: var(--terra); margin-right: 0.6rem;"></i> Fully Responsive UI &amp; Assets</li>
              <li><i class="fas fa-shield-alt" style="color: var(--terra); margin-right: 0.6rem;"></i> Commercial Lifetime License</li>
              <li><i class="fas fa-book" style="color: var(--terra); margin-right: 0.6rem;"></i> Setup &amp; Deployment Guide</li>
            </ul>
          </div>
        </div>

        <div>
          <!-- Pricing summary -->
          <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.8rem; padding-top: 1.2rem; border-top: 1px solid var(--border-subtle);">
            <div>
              <span style="font-size: 1.25rem; color: var(--text-secondary);">Turnkey Price:</span>
              <strong style="font-size: 2.2rem; font-family: 'Syne', sans-serif; margin-left: 0.6rem; color: var(--terra);">$<?= number_format($price, 0) ?></strong>
            </div>
            <?php if ($isPartner): ?>
              <div style="font-size: 1.15rem; font-weight: 700; color: #60a5fa;">
                Partner Commission: $<?= number_format(($price * $commissionRate) / 100, 2) ?>
              </div>
            <?php endif; ?>
          </div>

          <!-- Actions -->
          <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <button type="button" class="btn-buy-now" onclick="openShopBuyModal('<?= htmlspecialchars(addslashes($item['title']), ENT_QUOTES) ?>', '<?= $price ?>')">
              <i class="fas fa-shopping-cart"></i> Buy License
            </button>
            <a href="<?= htmlspecialchars($targetDemoUrl) ?>" target="_blank" class="btn-view-demo">
              <i class="fas fa-eye"></i> Live Demo
            </a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- BUY LICENSE MODAL -->
  <div class="akar-modal-overlay" id="shopBuyModal" style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); z-index: 100005; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: #161616; border: 1px solid rgba(255,255,255,0.12); border-radius: 2.4rem; padding: 3.5rem; max-width: 60rem; width: 100%; position: relative; color: #ffffff;">
      <button onclick="document.getElementById('shopBuyModal').style.display='none'" style="position: absolute; top: 2rem; right: 2rem; font-size: 2.4rem; color: #ffffff; background: none; border: none; cursor: pointer;">&times;</button>
      <h3 style="font-family: 'Syne', sans-serif; font-size: 2.8rem; margin-bottom: 0.8rem;">Acquire Turnkey Software License</h3>
      <p style="color: rgba(255,255,255,0.65); font-size: 1.4rem; margin-bottom: 2.5rem;">Receive the turnkey source code package, clean MVC assets, and commercial deployment bundle.</p>

      <form action="<?= eurl('/live-demos/request') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action_type" value="buy_template">

        <div style="margin-bottom: 1.5rem;">
          <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Selected Software Package</label>
          <input type="text" name="template_title" id="shopModalTemplateInput" readonly style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: var(--terra); font-weight: 700; font-size: 1.4rem;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Full Name *</label>
            <input type="text" name="name" required style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" value="<?= sanitize($user['first_name'] ?? '') ?> <?= sanitize($user['last_name'] ?? '') ?>" placeholder="e.g. Kelvin Mutua">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Email Address *</label>
            <input type="email" name="email" required style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" value="<?= sanitize($user['email'] ?? '') ?>" placeholder="kelvin@company.com">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Phone / M-Pesa</label>
            <input type="text" name="phone" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" value="<?= sanitize($user['phone'] ?? '') ?>" placeholder="+254 700 000000">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Company (Optional)</label>
            <input type="text" name="company" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="Acme Ltd">
          </div>
        </div>

        <div style="margin-bottom: 2rem;">
          <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Deployment Instructions / Custom Domain</label>
          <textarea name="notes" rows="2" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="Mention your target server (e.g. cPanel, AWS, Ubuntu) or custom features needed..."></textarea>
        </div>

        <button type="submit" style="width: 100%; padding: 1.5rem; border-radius: 10rem; background: var(--terra); color: #0e0e0e; font-size: 1.4rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; cursor: pointer; border: none;">
          Confirm Order &amp; Access Source Code Pack <i class="fas fa-download" style="margin-left: 0.6rem;"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="akar-footer">
    <div class="akar-footer-top">
      <div class="akar-footer-brand">
        <h3>Tektrend Softwares</h3>
        <p>We are Tektrend Softwares. Production software architectures, turnkey codebases, and digital enterprise systems based in Eldoret, Kenya.</p>
      </div>

      <div class="akar-footer-links-col">
        <h4>Explore</h4>
        <ul>
          <li><a href="<?= eurl('/') ?>">Main Agency</a></li>
          <li><a href="<?= eurl('/shop') ?>">Turnkey Software Store</a></li>
          <li><a href="<?= eurl('/live-demos') ?>">Completed Works &amp; Demos</a></li>
          <li><a href="<?= eurl('/faq') ?>">FAQ</a></li>
          <li><a href="<?= eurl('/privacy') ?>">Privacy Policy</a></li>
        </ul>
      </div>

      <div class="akar-footer-links-col">
        <h4>Direct Channels</h4>
        <ul>
          <li><a href="mailto:<?= sanitize($companyEmail) ?>"><?= sanitize($companyEmail) ?></a></li>
          <li><a href="tel:<?= sanitize($companyPhone) ?>"><?= sanitize($companyPhone) ?></a></li>
          <li><a href="https://wa.me/<?= $companyWhatsApp ?>" target="_blank">WhatsApp Direct</a></li>
        </ul>
      </div>

      <div class="akar-footer-links-col">
        <h4>Headquarters</h4>
        <p style="font-size: 1.4rem; color: var(--text-secondary); line-height: 1.6;">
          Tektrend Softwares, Eldoret, Kenya<br>
          Global Engineering Retainers
        </p>
      </div>
    </div>

    <div class="akar-footer-bottom">
      <div>&copy; <?= date('Y') ?> Tektrend Softwares · Eldoret, Kenya · We are Tektrend Softwares</div>
      <a href="#top" class="akar-back-to-top">
        Back to Top <i class="fas fa-arrow-up"></i>
      </a>
    </div>
  </footer>

  <script>
    function openShopBuyModal(title, price) {
      document.getElementById('shopModalTemplateInput').value = title + ' ($' + price + ')';
      document.getElementById('shopBuyModal').style.display = 'flex';
    }

    function filterShop(category) {
      document.querySelectorAll('.akar-filter-btn').forEach(btn => {
        btn.classList.toggle('--active', btn.getAttribute('data-filter') === category);
      });
      document.querySelectorAll('.shop-card').forEach(card => {
        const cats = card.getAttribute('data-category') || '';
        if (category === 'all' || cats.includes(category)) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    }
  </script>
</body>
</html>
