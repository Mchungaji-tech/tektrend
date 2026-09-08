<?php
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyEmail = $settings['company_email'] ?? 'info@tektrend.com';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tek Trend · Live HTML Prototypes & Turnkey Template Showcase</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Google Fonts: Inter & Outfit -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #d6c29d;
      --primary-hover: #e5d4b4;
      --accent: #38bdf8;
      --accent-hover: #0ea5e9;
      --bg-dark: #080a0c;
      --bg-card: rgba(18, 22, 28, 0.7);
      --border-card: rgba(214, 194, 157, 0.15);
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: var(--bg-dark);
      color: var(--text-main);
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      padding: 1.5rem;
      min-height: 100vh;
    }

    /* Ambient background glow */
    body::before {
      content: '';
      position: fixed;
      top: -20%;
      left: -20%;
      width: 140%;
      height: 140%;
      background: radial-gradient(circle at 20% 30%, rgba(56, 189, 248, 0.08) 0%, transparent 50%),
                  radial-gradient(circle at 80% 70%, rgba(214, 194, 157, 0.06) 0%, transparent 50%);
      z-index: -1;
      pointer-events: none;
    }

    .container {
      max-width: 1440px;
      margin: 0 auto;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      padding: 1rem 0 2rem 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      margin-bottom: 3rem;
    }

    .logo {
      font-family: 'Outfit', sans-serif;
      font-size: 1.85rem;
      font-weight: 800;
      background: linear-gradient(135deg, #f0e9d0, #b7a88b);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.5px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
    }
    .logo i {
      margin-right: 12px;
      color: #d6c29d;
      -webkit-text-fill-color: #d6c29d;
    }

    .header-actions {
      display: flex;
      gap: 1.25rem;
      align-items: center;
      flex-wrap: wrap;
    }
    .header-actions a {
      color: rgba(255,255,255,0.75);
      text-decoration: none;
      font-size: 0.92rem;
      font-weight: 500;
      transition: 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
    }
    .header-actions a:hover {
      color: #fff;
    }
    .header-actions .btn-outline {
      border: 1px solid rgba(214, 194, 157, 0.3);
      padding: 0.5rem 1.3rem;
      border-radius: 40px;
      background: rgba(214, 194, 157, 0.08);
      color: #e5d4b4;
    }
    .header-actions .btn-outline:hover {
      background: rgba(214, 194, 157, 0.2);
      border-color: #d6c29d;
      color: #fff;
    }
    .header-actions .btn-whatsapp {
      border: 1px solid rgba(37, 211, 102, 0.3);
      background: rgba(37, 211, 102, 0.1);
      color: #25d366;
    }
    .header-actions .btn-whatsapp:hover {
      background: rgba(37, 211, 102, 0.2);
      color: #4ade80;
    }

    /* Hero / Intro Banner */
    .intro-banner {
      text-align: center;
      max-width: 900px;
      margin: 0 auto 3rem auto;
      padding: 0 1rem;
    }
    .intro-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.4rem 1.1rem;
      border-radius: 30px;
      background: rgba(56, 189, 248, 0.1);
      border: 1px solid rgba(56, 189, 248, 0.25);
      color: #38bdf8;
      font-size: 0.82rem;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 1.25rem;
    }
    .intro-banner h1 {
      font-family: 'Outfit', sans-serif;
      font-size: clamp(2.2rem, 5vw, 3.4rem);
      font-weight: 800;
      letter-spacing: -1px;
      line-height: 1.15;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, #ffffff 40%, #cbd5e1 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .intro-banner p {
      color: var(--text-muted);
      font-size: 1.08rem;
      line-height: 1.7;
      margin-bottom: 2rem;
    }

    /* Category Filter */
    .filter-bar {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 0.6rem;
      margin-bottom: 3.5rem;
    }
    .filter-btn {
      background: rgba(255, 255, 255, 0.04);
      color: rgba(255, 255, 255, 0.7);
      border: 1px solid rgba(255, 255, 255, 0.08);
      padding: 0.55rem 1.2rem;
      border-radius: 30px;
      font-size: 0.86rem;
      font-weight: 600;
      cursor: pointer;
      transition: 0.25s ease;
    }
    .filter-btn:hover, .filter-btn.active {
      background: rgba(214, 194, 157, 0.15);
      border-color: #d6c29d;
      color: #f8fafc;
      transform: translateY(-1px);
    }

    /* Demo Grid */
    .demo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
      gap: 2rem;
      margin-bottom: 4rem;
    }

    /* Card */
    .demo-card {
      background: var(--bg-card);
      border: 1px solid var(--border-card);
      border-radius: 20px;
      padding: 2rem;
      backdrop-filter: blur(16px);
      display: flex;
      flex-direction: column;
      position: relative;
      transition: all 0.35s cubic-bezier(0.2, 0, 0, 1);
      box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
    }
    .demo-card:hover {
      transform: translateY(-6px);
      border-color: rgba(214, 194, 157, 0.4);
      box-shadow: 0 20px 40px -15px rgba(214, 194, 157, 0.12);
    }

    .card-top-row {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 1.25rem;
    }
    .icon-wrap {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: rgba(214, 194, 157, 0.1);
      border: 1px solid rgba(214, 194, 157, 0.2);
      color: #d6c29d;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
    }
    .award-pill {
      font-size: 0.7rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      padding: 0.3rem 0.85rem;
      border-radius: 20px;
      background: rgba(56, 189, 248, 0.12);
      color: #38bdf8;
      border: 1px solid rgba(56, 189, 248, 0.3);
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }

    .demo-card h3 {
      font-family: 'Outfit', sans-serif;
      font-size: 1.45rem;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin-bottom: 0.4rem;
      color: #fff;
    }
    .demo-card .category {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #d6c29d;
      margin-bottom: 0.9rem;
      font-weight: 700;
    }
    .demo-card p {
      color: var(--text-muted);
      font-size: 0.92rem;
      line-height: 1.65;
      margin-bottom: 1.5rem;
      flex: 1;
    }

    /* Tech tags */
    .tech-stack {
      display: flex;
      flex-wrap: wrap;
      gap: 0.45rem;
      margin-bottom: 1.5rem;
    }
    .tech-stack span {
      background: rgba(255, 255, 255, 0.05);
      padding: 0.25rem 0.8rem;
      border-radius: 30px;
      font-size: 0.72rem;
      color: #cbd5e1;
      border: 1px solid rgba(255, 255, 255, 0.08);
      font-weight: 500;
    }

    /* Pricing & Action Bar */
    .pricing-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.85rem 1rem;
      border-radius: 12px;
      background: rgba(255, 255, 255, 0.02);
      border: 1px solid rgba(255, 255, 255, 0.05);
      margin-bottom: 1.25rem;
    }
    .price-tag {
      font-size: 0.82rem;
      color: var(--text-muted);
    }
    .price-tag strong {
      font-size: 1.15rem;
      color: #fff;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
    }

    /* Card Action Buttons (3 actions) */
    .action-group {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0.65rem;
    }
    .btn-preview {
      grid-column: span 2;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.55rem;
      background: linear-gradient(135deg, rgba(214,194,157,0.18), rgba(214,194,157,0.08));
      color: #f5efe2;
      border: 1px solid rgba(214, 194, 157, 0.35);
      font-weight: 700;
      font-size: 0.92rem;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      text-decoration: none;
      transition: 0.25s ease;
    }
    .btn-preview:hover {
      background: linear-gradient(135deg, rgba(214,194,157,0.3), rgba(214,194,157,0.18));
      border-color: #d6c29d;
      color: #fff;
      transform: translateY(-1px);
    }

    .btn-action-sub {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      font-size: 0.82rem;
      font-weight: 600;
      padding: 0.65rem 0.75rem;
      border-radius: 10px;
      cursor: pointer;
      border: 1px solid transparent;
      transition: 0.25s ease;
      text-decoration: none;
    }
    .btn-request {
      background: rgba(56, 189, 248, 0.1);
      border-color: rgba(56, 189, 248, 0.25);
      color: #38bdf8;
    }
    .btn-request:hover {
      background: rgba(56, 189, 248, 0.22);
      border-color: #38bdf8;
      color: #fff;
    }
    .btn-buy {
      background: rgba(34, 197, 94, 0.1);
      border-color: rgba(34, 197, 94, 0.25);
      color: #4ade80;
    }
    .btn-buy:hover {
      background: rgba(34, 197, 94, 0.22);
      border-color: #22c55e;
      color: #fff;
    }

    /* Modal Overlay */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(8px);
      z-index: 1000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .modal-overlay.active {
      display: flex;
    }
    .modal-box {
      background: #0f1318;
      border: 1px solid rgba(214, 194, 157, 0.3);
      border-radius: 20px;
      max-width: 580px;
      width: 100%;
      padding: 2.25rem;
      box-shadow: 0 25px 60px rgba(0,0,0,0.8);
      position: relative;
      animation: modalFadeIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes modalFadeIn {
      from { opacity: 0; transform: translateY(20px) scale(0.97); }
      to { opacity: 1; transform: translateY(0) scale(1); }
    }
    .modal-close {
      position: absolute;
      top: 1.25rem;
      right: 1.25rem;
      background: none;
      border: none;
      color: var(--text-muted);
      font-size: 1.25rem;
      cursor: pointer;
      padding: 0.5rem;
      transition: 0.2s;
    }
    .modal-close:hover { color: #fff; }

    .modal-header h2 {
      font-family: 'Outfit', sans-serif;
      font-size: 1.6rem;
      color: #fff;
      margin-bottom: 0.4rem;
    }
    .modal-header p {
      color: var(--text-muted);
      font-size: 0.88rem;
      margin-bottom: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.1rem;
    }
    .form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      color: #cbd5e1;
      margin-bottom: 0.4rem;
    }
    .form-control {
      width: 100%;
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: #fff;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      font-family: inherit;
      font-size: 0.92rem;
      transition: 0.2s;
    }
    .form-control:focus {
      outline: none;
      border-color: #d6c29d;
      background: rgba(255, 255, 255, 0.08);
    }

    .modal-actions {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }
    .btn-submit-modal {
      background: linear-gradient(135deg, #d6c29d, #b7a88b);
      color: #0b0d0f;
      border: none;
      padding: 0.85rem;
      border-radius: 12px;
      font-weight: 700;
      font-size: 0.95rem;
      cursor: pointer;
      transition: 0.25s;
    }
    .btn-submit-modal:hover {
      background: linear-gradient(135deg, #e5d4b4, #c7b18b);
      transform: translateY(-1px);
    }
    .btn-wa-modal {
      background: rgba(37, 211, 102, 0.12);
      border: 1px solid rgba(37, 211, 102, 0.3);
      color: #25d366;
      padding: 0.8rem;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.9rem;
      text-align: center;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      transition: 0.25s;
    }
    .btn-wa-modal:hover {
      background: rgba(37, 211, 102, 0.25);
      color: #fff;
    }

    /* Flash Notice */
    .flash-alert {
      max-width: 800px;
      margin: 0 auto 2rem auto;
      padding: 1rem 1.5rem;
      border-radius: 12px;
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.3);
      color: #4ade80;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.95rem;
    }

    /* Footer */
    .footer {
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      padding: 2.5rem 0 1rem 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      color: var(--text-muted);
      font-size: 0.88rem;
    }
    .footer a {
      color: #d6c29d;
      text-decoration: none;
      transition: 0.2s;
    }
    .footer a:hover { color: #fff; }

    @media (max-width: 800px) {
      .demo-grid { grid-template-columns: 1fr; }
      .header { flex-direction: column; align-items: flex-start; }
      .header-actions { width: 100%; flex-wrap: wrap; }
      body { padding: 1.2rem; }
    }
  </style>
</head>
<body>
  <div class="container">

    <!-- HEADER (100% PUBLIC, ZERO DASHBOARD OVERLAY) -->
    <header class="header">
      <a href="<?= eurl('/') ?>" class="logo"><i class="fas fa-code"></i> Tek Trend</a>
      <div class="header-actions">
        <a href="<?= eurl('/') ?>"><i class="fas fa-home"></i> Home</a>
        <a href="<?= eurl('/#portfolio') ?>"><i class="fas fa-layer-group"></i> Services</a>
        <a href="<?= eurl('/#consultation') ?>"><i class="fas fa-calendar-check"></i> Book Consultation</a>
        <a href="<?= eurl('/#contact') ?>"><i class="fas fa-envelope"></i> Contact</a>
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="<?= eurl('/dashboard') ?>" class="btn-outline"><i class="fas fa-chart-pie"></i> Portal Dashboard</a>
        <?php else: ?>
          <a href="<?= eurl('/login') ?>" class="btn-outline"><i class="fas fa-lock"></i> Portal Login</a>
        <?php endif; ?>
        <a href="https://wa.me/<?= $companyWhatsApp ?>?text=Hello%20Tek%20Trend,%20I%20am%20exploring%20your%20Live%20Demo%20Prototypes%20and%20turnkey%20systems." target="_blank" class="btn-outline btn-whatsapp">
          <i class="fab fa-whatsapp"></i> WhatsApp Us
        </a>
      </div>
    </header>

    <?php if (flash('success')): ?>
      <div class="flash-alert">
        <i class="fas fa-check-circle" style="font-size: 1.25rem;"></i>
        <?= sanitize(flash('success')) ?>
      </div>
    <?php endif; ?>

    <!-- HERO / INTRO -->
    <div class="intro-banner">
      <div class="intro-pill"><i class="fas fa-rocket"></i> Production Prototypes & Turnkey Templates</div>
      <h1>Live Demo Prototypes & Turnkey Systems</h1>
      <p>Explore our working HTML prototypes and enterprise-grade software architectures from the <code>/live_demo</code> collection. Review live interactive demos, request custom software builds for your business, or purchase turnkey source code immediately.</p>
    </div>

    <!-- CATEGORY FILTERS -->
    <div class="filter-bar">
      <button class="filter-btn active" onclick="filterDemos('all')">All Showcases (<?= count($demos) ?>)</button>
      <button class="filter-btn" onclick="filterDemos('creative')">Creative & Agency</button>
      <button class="filter-btn" onclick="filterDemos('marketing')">Marketing & SEO</button>
      <button class="filter-btn" onclick="filterDemos('ecommerce')">E-Commerce & Retail</button>
      <button class="filter-btn" onclick="filterDemos('legal')">Legal & Corporate</button>
      <button class="filter-btn" onclick="filterDemos('community')">Community & Faith</button>
      <button class="filter-btn" onclick="filterDemos('engineering')">Engineering & Tech</button>
      <button class="filter-btn" onclick="filterDemos('hospitality')">Hospitality & Dining</button>
      <button class="filter-btn" onclick="filterDemos('saas')">Fintech & SaaS</button>
    </div>

    <!-- DEMO GRID -->
    <div class="demo-grid" id="demoGrid">
      <?php foreach ($demos as $demo): ?>
        <?php 
          $catLower = strtolower($demo['category']);
          $dataFilter = 'all';
          if (strpos($catLower, 'creative') !== false || strpos($catLower, 'design') !== false) $dataFilter .= ' creative';
          if (strpos($catLower, 'marketing') !== false || strpos($catLower, 'seo') !== false) $dataFilter .= ' marketing';
          if (strpos($catLower, 'commerce') !== false || strpos($catLower, 'retail') !== false) $dataFilter .= ' ecommerce';
          if (strpos($catLower, 'legal') !== false || strpos($catLower, 'corporate') !== false) $dataFilter .= ' legal';
          if (strpos($catLower, 'church') !== false || strpos($catLower, 'community') !== false || strpos($catLower, 'non-profit') !== false) $dataFilter .= ' community';
          if (strpos($catLower, 'engineering') !== false || strpos($catLower, 'industrial') !== false) $dataFilter .= ' engineering';
          if (strpos($catLower, 'restaurant') !== false || strpos($catLower, 'hospitality') !== false || strpos($catLower, 'dining') !== false) $dataFilter .= ' hospitality';
          if (strpos($catLower, 'saas') !== false || strpos($catLower, 'fintech') !== false || strpos($catLower, 'publishing') !== false) $dataFilter .= ' saas';
          
          $priceVal = !empty($demo['price']) ? (float)$demo['price'] : 49.00;
        ?>
        <div class="demo-card" data-category="<?= $dataFilter ?>">
          <div class="card-top-row">
            <div class="icon-wrap">
              <i class="<?= sanitize($demo['icon'] ?? 'fas fa-laptop-code') ?>"></i>
            </div>
            <?php if (!empty($demo['award_badge'])): ?>
              <div class="award-pill"><i class="fas fa-medal"></i> <?= sanitize($demo['award_badge']) ?></div>
            <?php else: ?>
              <div class="award-pill"><i class="fas fa-code"></i> Turnkey Template</div>
            <?php endif; ?>
          </div>

          <h3><?= sanitize($demo['title']) ?></h3>
          <div class="category"><?= sanitize($demo['category']) ?></div>
          <p><?= sanitize($demo['short_description']) ?></p>

          <?php if (!empty($demo['tech_stack'])): ?>
            <div class="tech-stack">
              <?php foreach (explode(',', $demo['tech_stack']) as $tag): ?>
                <span><?= trim(sanitize($tag)) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <div class="pricing-bar">
            <div class="price-tag">Turnkey Code: <strong>$<?= number_format($priceVal, 0) ?></strong></div>
            <div style="font-size: 0.76rem; color: #38bdf8;"><i class="fas fa-shield-alt"></i> Clean Source Code</div>
          </div>

          <!-- 3 Customer Actions: 1. Review/Preview | 2. Request System | 3. Buy Template -->
          <div class="action-group">
            <a href="<?= eurl($demo['demo_url']) ?>" class="btn-preview" target="_blank">
              <i class="fas fa-eye"></i> Review Live Prototype <i class="fas fa-arrow-right" style="margin-left: 4px;"></i>
            </a>

            <button type="button" class="btn-action-sub btn-request" 
                    onclick="openRequestModal('<?= htmlspecialchars(addslashes($demo['title']), ENT_QUOTES) ?>')">
              <i class="fas fa-cogs"></i> Request System
            </button>

            <button type="button" class="btn-action-sub btn-buy" 
                    onclick="openBuyModal('<?= htmlspecialchars(addslashes($demo['title']), ENT_QUOTES) ?>', '<?= $priceVal ?>')">
              <i class="fas fa-shopping-cart"></i> Buy Template ($<?= number_format($priceVal, 0) ?>)
            </button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
      <span>&copy; <?= date('Y') ?> <a href="<?= eurl('/') ?>">Tek Trend Innovations</a> · Premium Web Architecture Consultancy</span>
      <span style="display: flex; gap: 1.5rem; flex-wrap: wrap;">
        <a href="mailto:<?= sanitize($companyEmail) ?>"><i class="fas fa-envelope"></i> <?= sanitize($companyEmail) ?></a>
        <a href="tel:<?= sanitize($companyPhone) ?>"><i class="fas fa-phone-alt"></i> <?= sanitize($companyPhone) ?></a>
        <a href="https://wa.me/<?= $companyWhatsApp ?>" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a>
        <a href="<?= eurl('/') ?>"><i class="fas fa-globe"></i> Main Website</a>
      </span>
    </footer>

  </div>

  <!-- MODAL 1: REQUEST CUSTOM SYSTEM -->
  <div class="modal-overlay" id="requestModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModals()">&times;</button>
      <div class="modal-header">
        <h2>Request Custom System</h2>
        <p>Commission Tek Trend to customize, engineer, and deploy this exact platform architecture for your organization.</p>
      </div>

      <form action="<?= eurl('/live-demos/request') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action_type" value="request_system">
        
        <div class="form-group">
          <label>Selected Prototype / Template</label>
          <input type="text" name="template_title" id="requestTemplateTitle" class="form-control" readonly style="background: rgba(214,194,157,0.08); border-color: rgba(214,194,157,0.25); color: #d6c29d; font-weight: 700;">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
          <div class="form-group">
            <label class="required">Your Full Name</label>
            <input type="text" name="name" class="form-control" placeholder="e.g. David Mwangi" required>
          </div>
          <div class="form-group">
            <label class="required">Business Email</label>
            <input type="email" name="email" class="form-control" placeholder="david@company.com" required>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
          <div class="form-group">
            <label>Phone / WhatsApp Number</label>
            <input type="text" name="phone" id="requestPhone" class="form-control" placeholder="+254 700 000000">
          </div>
          <div class="form-group">
            <label>Company / Brand Name</label>
            <input type="text" name="company" class="form-control" placeholder="Acme Global Ltd">
          </div>
        </div>

        <div class="form-group">
          <label>Project Requirements & Custom Features</label>
          <textarea name="notes" class="form-control" rows="3" placeholder="Explain what custom integrations you need (e.g. M-Pesa payments, customer portal, custom domain, inventory)..."></textarea>
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-submit-modal"><i class="fas fa-paper-plane"></i> Submit System Architecture Request</button>
          <a href="#" id="requestWhatsAppLink" target="_blank" class="btn-wa-modal">
            <i class="fab fa-whatsapp"></i> Request Instantly via WhatsApp
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL 2: BUY TEMPLATE / TURNKEY SOURCE CODE -->
  <div class="modal-overlay" id="buyModal">
    <div class="modal-box">
      <button class="modal-close" onclick="closeModals()">&times;</button>
      <div class="modal-header">
        <h2>Buy Turnkey Template Code</h2>
        <p>Get the complete production-grade HTML5/CSS3/JS source code package with lifetime commercial license.</p>
      </div>

      <form action="<?= eurl('/live-demos/request') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action_type" value="buy_template">
        
        <div class="form-group">
          <label>Template Package</label>
          <input type="text" name="template_title" id="buyTemplateTitle" class="form-control" readonly style="background: rgba(34,197,94,0.08); border-color: rgba(34,197,94,0.25); color: #4ade80; font-weight: 700;">
        </div>

        <div class="form-group">
          <label>Select Purchase Option</label>
          <select name="license" id="buyLicenseSelect" class="form-control" onchange="updateBuyPrice()">
            <option value="Standard Turnkey Source Code">Turnkey HTML/CSS/JS Source Code Package</option>
            <option value="Source Code + Domain & Hosting Deployment">Source Code + cPanel / Domain Deployment (+$99)</option>
            <option value="Enterprise Customization + Retainer">Turnkey Code + Dedicated 1-Month Engineering Support</option>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;">
          <div class="form-group">
            <label class="required">Your Name</label>
            <input type="text" name="name" class="form-control" placeholder="Jane Doe" required>
          </div>
          <div class="form-group">
            <label class="required">Delivery Email</label>
            <input type="email" name="email" class="form-control" placeholder="jane@example.com" required>
          </div>
        </div>

        <div class="form-group">
          <label>WhatsApp / Phone (for instant download link)</label>
          <input type="text" name="phone" class="form-control" placeholder="+254 700 000000">
        </div>

        <div class="modal-actions">
          <button type="submit" class="btn-submit-modal" style="background: linear-gradient(135deg, #22c55e, #16a34a); color: #fff;">
            <i class="fas fa-check-circle"></i> Complete Template Order Request
          </button>
          <a href="#" id="buyWhatsAppLink" target="_blank" class="btn-wa-modal">
            <i class="fab fa-whatsapp"></i> Buy Instantly via WhatsApp Chat
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Filter Demos
    function filterDemos(cat) {
      document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');

      const cards = document.querySelectorAll('.demo-card');
      cards.forEach(card => {
        if (cat === 'all' || card.getAttribute('data-category').includes(cat)) {
          card.style.display = 'flex';
        } else {
          card.style.display = 'none';
        }
      });
    }

    // Modal Handlers
    const waNumber = "<?= $companyWhatsApp ?>";

    function openRequestModal(title) {
      document.getElementById('requestTemplateTitle').value = title;
      const waText = encodeURIComponent("Hello Tek Trend Innovations, I reviewed the live prototype '" + title + "' and I would like to request a customized system architecture for my business.");
      document.getElementById('requestWhatsAppLink').href = "https://wa.me/" + waNumber + "?text=" + waText;
      document.getElementById('requestModal').classList.add('active');
    }

    function openBuyModal(title, price) {
      document.getElementById('buyTemplateTitle').value = title + " (Standard: $" + price + ")";
      const waText = encodeURIComponent("Hello Tek Trend Innovations, I want to purchase the '" + title + "' turnkey template source code ($" + price + "). Please share the instant download and payment instructions.");
      document.getElementById('buyWhatsAppLink').href = "https://wa.me/" + waNumber + "?text=" + waText;
      document.getElementById('buyModal').classList.add('active');
    }

    function closeModals() {
      document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
    }

    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeModals();
    });

    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', (e) => {
        if (e.target === overlay) closeModals();
      });
    });
  </script>
</body>
</html>
