<?php
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyEmail = $settings['company_email'] ?? 'info@tektrend.com';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tek Trend · Live Demo Prototypes & Turnkey Templates</title>
  <meta name="description" content="Explore real production HTML prototypes, interactive web architectures, and turnkey source code templates by Tek Trend. Review live demos, commission custom builds, or purchase templates instantly.">

  <!-- Anti-flicker Theme Script -->
  <script>
    (function() {
      const savedTheme = localStorage.getItem('tektrend_theme') || 'dark';
      document.documentElement.setAttribute('data-theme', savedTheme);
    })();
  </script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Google Fonts: Inter & Outfit -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* CSS Variables: Dark & Light Mode */
    :root, [data-theme="dark"] {
      --bg-canvas: #07090d;
      --bg-navbar: rgba(11, 15, 22, 0.82);
      --bg-card: rgba(15, 20, 28, 0.72);
      --bg-card-hover: rgba(22, 30, 42, 0.9);
      --border-color: rgba(255, 255, 255, 0.08);
      --border-hover: rgba(214, 194, 157, 0.4);
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --text-soft: #cbd5e1;
      --accent: #d6c29d;
      --accent-hover: #e8dbbf;
      --accent-glow: rgba(214, 194, 157, 0.15);
      --modal-bg: #0f141c;
      --input-bg: rgba(255, 255, 255, 0.05);
      --input-border: rgba(255, 255, 255, 0.12);
      --strip-bg: rgba(255, 255, 255, 0.025);
      --shadow-card: 0 15px 35px -10px rgba(0, 0, 0, 0.7);
      --shadow-nav: 0 10px 30px rgba(0, 0, 0, 0.5);
    }

    [data-theme="light"] {
      --bg-canvas: #f8fafc;
      --bg-navbar: rgba(255, 255, 255, 0.9);
      --bg-card: #ffffff;
      --bg-card-hover: #ffffff;
      --border-color: #e2e8f0;
      --border-hover: #b89547;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --text-soft: #334155;
      --accent: #b8860b;
      --accent-hover: #996f08;
      --accent-glow: rgba(184, 134, 11, 0.12);
      --modal-bg: #ffffff;
      --input-bg: #f8fafc;
      --input-border: #cbd5e1;
      --strip-bg: #f1f5f9;
      --shadow-card: 0 10px 30px -5px rgba(15, 23, 42, 0.07);
      --shadow-nav: 0 4px 20px rgba(15, 23, 42, 0.06);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      background: var(--bg-canvas);
      color: var(--text-main);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      overflow-x: hidden;
      min-height: 100vh;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Ambient atmospheric glow */
    .ambient-glow {
      position: fixed;
      top: -20%;
      left: -15%;
      width: 130%;
      height: 130%;
      background: radial-gradient(circle at 20% 25%, rgba(56, 189, 248, 0.06) 0%, transparent 50%),
                  radial-gradient(circle at 80% 75%, var(--accent-glow) 0%, transparent 55%);
      pointer-events: none;
      z-index: 0;
    }

    .container {
      max-width: 1440px;
      margin: 0 auto;
      padding: 0 1.5rem;
      position: relative;
      z-index: 1;
    }

    /* ============================================================
       TOP STICKY NAVBAR (WELL-ORGANIZED, NEVER WRAPS AWKWARDLY)
       ============================================================ */
    .navbar-sticky {
      position: sticky;
      top: 0;
      z-index: 1000;
      background: var(--bg-navbar);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-bottom: 1px solid var(--border-color);
      box-shadow: var(--shadow-nav);
      transition: all 0.3s ease;
    }

    .navbar-inner {
      display: flex;
      justify-content: space-between;
      align-items: center;
      height: 74px;
      gap: 1.5rem;
    }

    /* Logo */
    .nav-brand {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      flex-shrink: 0;
    }
    .nav-brand-logo {
      font-family: 'Outfit', sans-serif;
      font-size: 1.7rem;
      font-weight: 800;
      background: linear-gradient(135deg, var(--text-main) 20%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.5px;
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
    }
    .nav-brand-logo i {
      color: var(--accent);
      -webkit-text-fill-color: var(--accent);
      font-size: 1.5rem;
    }

    /* Center Navigation Menu */
    .nav-menu {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      background: rgba(255, 255, 255, 0.03);
      padding: 0.35rem 0.6rem;
      border-radius: 40px;
      border: 1px solid var(--border-color);
    }
    [data-theme="light"] .nav-menu {
      background: rgba(15, 23, 42, 0.03);
    }
    .nav-link {
      color: var(--text-muted);
      text-decoration: none;
      font-size: 0.88rem;
      font-weight: 500;
      padding: 0.45rem 1rem;
      border-radius: 30px;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      white-space: nowrap;
    }
    .nav-link:hover {
      color: var(--text-main);
      background: rgba(255, 255, 255, 0.06);
    }
    [data-theme="light"] .nav-link:hover {
      background: rgba(15, 23, 42, 0.06);
    }
    .nav-link.active {
      color: var(--accent);
      background: var(--accent-glow);
      font-weight: 600;
    }

    /* Right Action Group */
    .nav-actions {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      flex-shrink: 0;
    }

    /* Theme Toggle Button */
    .theme-toggle-btn {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      border: 1px solid var(--border-color);
      background: rgba(255, 255, 255, 0.04);
      color: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1.1rem;
      transition: all 0.3s ease;
    }
    [data-theme="light"] .theme-toggle-btn {
      background: #f1f5f9;
      border-color: #cbd5e1;
      color: #b8860b;
    }
    .theme-toggle-btn:hover {
      transform: rotate(20deg) scale(1.08);
      border-color: var(--accent);
      box-shadow: 0 0 15px var(--accent-glow);
    }

    /* Buttons in Nav */
    .btn-nav-outline {
      border: 1px solid var(--border-color);
      padding: 0.55rem 1.25rem;
      border-radius: 40px;
      background: rgba(255, 255, 255, 0.04);
      color: var(--text-main);
      text-decoration: none;
      font-size: 0.88rem;
      font-weight: 600;
      transition: all 0.25s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
    }
    .btn-nav-outline:hover {
      border-color: var(--accent);
      color: var(--accent);
      transform: translateY(-1px);
    }
    .btn-nav-whatsapp {
      border: 1px solid rgba(34, 197, 94, 0.3);
      background: rgba(34, 197, 94, 0.12);
      color: #4ade80;
    }
    .btn-nav-whatsapp:hover {
      background: rgba(34, 197, 94, 0.22);
      border-color: #22c55e;
      color: #fff;
    }
    [data-theme="light"] .btn-nav-whatsapp {
      background: rgba(22, 163, 74, 0.1);
      color: #16a34a;
      border-color: rgba(22, 163, 74, 0.3);
    }
    [data-theme="light"] .btn-nav-whatsapp:hover {
      background: #16a34a;
      color: #fff;
    }

    /* Mobile Hamburger */
    .mobile-nav-toggle {
      display: none;
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: none;
      border: 1px solid var(--border-color);
      color: var(--text-main);
      font-size: 1.2rem;
      cursor: pointer;
      align-items: center;
      justify-content: center;
    }

    /* Mobile Drawer */
    .mobile-drawer {
      display: none;
      flex-direction: column;
      gap: 0.85rem;
      padding: 1.25rem 0 1.5rem;
      border-top: 1px solid var(--border-color);
      background: var(--bg-navbar);
    }
    .mobile-drawer.open {
      display: flex;
    }
    .mobile-drawer a {
      color: var(--text-main);
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 500;
      padding: 0.6rem 1rem;
      border-radius: 8px;
    }
    .mobile-drawer a:hover {
      background: rgba(255, 255, 255, 0.05);
      color: var(--accent);
    }

    /* ============================================================
       INTRO / HERO BANNER
       ============================================================ */
    .intro-banner {
      text-align: center;
      max-width: 920px;
      margin: 3.5rem auto 3rem;
    }
    .intro-pill {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.4rem 1.4rem;
      border-radius: 40px;
      font-size: 0.8rem;
      font-weight: 600;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      background: var(--accent-glow);
      border: 1px solid var(--border-hover);
      color: var(--accent);
      margin-bottom: 1.2rem;
    }
    .intro-banner h1 {
      font-family: 'Outfit', sans-serif;
      font-size: clamp(2.4rem, 4.5vw, 3.8rem);
      font-weight: 800;
      line-height: 1.15;
      background: linear-gradient(135deg, var(--text-main) 40%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 1.1rem;
    }
    .intro-banner p {
      color: var(--text-muted);
      font-size: 1.12rem;
      line-height: 1.65;
    }

    /* Category Filter Buttons */
    .filter-bar {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 0.55rem;
      margin-bottom: 3.5rem;
    }
    .filter-btn {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      color: var(--text-muted);
      padding: 0.55rem 1.25rem;
      border-radius: 30px;
      font-size: 0.88rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.25s ease;
    }
    .filter-btn:hover {
      color: var(--text-main);
      border-color: var(--border-hover);
    }
    .filter-btn.active {
      background: linear-gradient(135deg, var(--accent), #bba377);
      color: #0c0e12;
      font-weight: 700;
      border-color: var(--accent);
      box-shadow: 0 4px 15px var(--accent-glow);
    }

    /* ============================================================
       PROTOTYPE CARDS GRID
       ============================================================ */
    .demo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
      gap: 2rem;
      margin-bottom: 5rem;
    }

    .demo-card {
      background: var(--bg-card);
      backdrop-filter: blur(12px);
      border-radius: 24px;
      padding: 1.85rem;
      border: 1px solid var(--border-color);
      transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex;
      flex-direction: column;
      box-shadow: var(--shadow-card);
      position: relative;
    }
    .demo-card:hover {
      transform: translateY(-6px);
      background: var(--bg-card-hover);
      border-color: var(--border-hover);
      box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.4);
    }

    .card-top-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.25rem;
    }
    .icon-wrap {
      width: 52px;
      height: 52px;
      border-radius: 16px;
      background: var(--accent-glow);
      border: 1px solid var(--border-hover);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.45rem;
      color: var(--accent);
    }
    .award-pill {
      font-size: 0.72rem;
      font-weight: 600;
      padding: 0.25rem 0.8rem;
      border-radius: 20px;
      background: var(--accent-glow);
      color: var(--accent);
      border: 1px solid var(--border-hover);
    }

    .demo-card h3 {
      font-family: 'Outfit', sans-serif;
      font-size: 1.35rem;
      font-weight: 700;
      margin-bottom: 0.35rem;
      color: var(--text-main);
    }
    .demo-card .category {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--accent);
      font-weight: 600;
      margin-bottom: 0.85rem;
    }
    .demo-card p {
      color: var(--text-muted);
      font-size: 0.92rem;
      line-height: 1.6;
      margin-bottom: 1.25rem;
      flex-grow: 1;
    }

    .tech-stack {
      display: flex;
      flex-wrap: wrap;
      gap: 0.4rem;
      margin-bottom: 1.35rem;
    }
    .tech-stack span {
      background: var(--strip-bg);
      border: 1px solid var(--border-color);
      padding: 0.2rem 0.65rem;
      border-radius: 20px;
      font-size: 0.72rem;
      color: var(--text-soft);
    }

    /* Pricing strip */
    .pricing-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      background: var(--strip-bg);
      border: 1px solid var(--border-color);
      margin-bottom: 1.2rem;
    }
    .price-tag {
      font-size: 0.84rem;
      color: var(--text-muted);
    }
    .price-tag strong {
      font-size: 1.15rem;
      color: var(--text-main);
      font-family: 'Outfit', sans-serif;
    }

    /* 3 Action Buttons */
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
      background: linear-gradient(135deg, var(--accent-glow), rgba(214,194,157,0.06));
      color: var(--text-main);
      border: 1px solid var(--border-hover);
      font-weight: 700;
      font-size: 0.95rem;
      padding: 0.75rem 1rem;
      border-radius: 12px;
      text-decoration: none;
      transition: all 0.25s ease;
    }
    .btn-preview:hover {
      background: linear-gradient(135deg, var(--accent), #bba377);
      color: #0c0e12;
      transform: translateY(-1px);
    }
    .btn-action-sub {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      padding: 0.7rem 0.8rem;
      border-radius: 12px;
      font-size: 0.85rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.25s ease;
      text-decoration: none;
      border: none;
    }
    .btn-request {
      background: rgba(59, 130, 246, 0.12);
      border: 1px solid rgba(59, 130, 246, 0.28);
      color: #3b82f6;
    }
    .btn-request:hover {
      background: rgba(59, 130, 246, 0.22);
      color: #2563eb;
    }
    [data-theme="dark"] .btn-request { color: #93c5fd; }
    [data-theme="dark"] .btn-request:hover { color: #fff; }

    .btn-buy {
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.28);
      color: #16a34a;
    }
    .btn-buy:hover {
      background: rgba(34, 197, 94, 0.22);
      color: #15803d;
    }
    [data-theme="dark"] .btn-buy { color: #86efac; }
    [data-theme="dark"] .btn-buy:hover { color: #fff; }

    /* Modals */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.8);
      backdrop-filter: blur(8px);
      z-index: 10000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
      background: var(--modal-bg);
      border: 1px solid var(--border-hover);
      border-radius: 24px;
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
      font-size: 1.35rem;
      cursor: pointer;
      transition: color 0.2s;
    }
    .modal-close:hover { color: var(--text-main); }
    .modal-header h2 {
      font-family: 'Outfit', sans-serif;
      font-size: 1.6rem;
      color: var(--text-main);
      margin-bottom: 0.35rem;
    }
    .modal-header p {
      color: var(--text-muted);
      font-size: 0.88rem;
      margin-bottom: 1.5rem;
    }
    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
      display: block;
      font-size: 0.82rem;
      font-weight: 600;
      color: var(--text-soft);
      margin-bottom: 0.4rem;
    }
    .form-group label.required::after { content: ' *'; color: #ef4444; }
    .form-control {
      width: 100%;
      background: var(--input-bg);
      border: 1px solid var(--input-border);
      color: var(--text-main);
      padding: 0.75rem 1rem;
      border-radius: 10px;
      font-family: inherit;
      font-size: 0.92rem;
    }
    .form-control:focus {
      outline: none;
      border-color: var(--accent);
    }
    .modal-actions {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-top: 1.25rem;
    }
    .btn-submit-modal {
      width: 100%;
      padding: 0.95rem;
      border-radius: 12px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      border: none;
      transition: all 0.25s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
    }
    .btn-wa-modal {
      text-align: center;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      color: #22c55e;
      text-decoration: none;
      font-size: 0.88rem;
      font-weight: 600;
      padding: 0.5rem;
    }

    /* Footer */
    .footer {
      border-top: 1px solid var(--border-color);
      padding: 3rem 0 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      color: var(--text-muted);
      font-size: 0.88rem;
    }
    .footer a { color: var(--accent); text-decoration: none; }
    .footer a:hover { color: var(--text-main); }

    /* Flash alert */
    .flash-alert {
      background: rgba(34, 197, 94, 0.12);
      border: 1px solid rgba(34, 197, 94, 0.3);
      color: #4ade80;
      padding: 1rem 1.4rem;
      border-radius: 12px;
      margin: 1.5rem 0;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    @media (max-width: 992px) {
      .nav-menu { display: none; }
      .mobile-nav-toggle { display: flex; }
      .demo-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

  <div class="ambient-glow"></div>

  <!-- STICKY NAVBAR -->
  <header class="navbar-sticky">
    <div class="container">
      <div class="navbar-inner">
        <!-- Logo -->
        <a href="<?= eurl('/') ?>" class="nav-brand">
          <span class="nav-brand-logo"><i class="fas fa-code"></i> Tek Trend</span>
        </a>

        <!-- Center Menu -->
        <nav class="nav-menu">
          <a href="<?= eurl('/') ?>" class="nav-link"><i class="fas fa-home"></i> Home</a>
          <a href="<?= eurl('/#portfolio') ?>" class="nav-link"><i class="fas fa-layer-group"></i> Services</a>
          <a href="<?= eurl('/#consultation') ?>" class="nav-link"><i class="fas fa-calendar-check"></i> Consultation</a>
          <a href="<?= eurl('/#contact') ?>" class="nav-link"><i class="fas fa-envelope"></i> Contact</a>
          <a href="#demoGrid" class="nav-link active"><i class="fas fa-rocket"></i> Prototypes (<?= count($demos) ?>)</a>
        </nav>

        <!-- Right Actions -->
        <div class="nav-actions">
          <!-- Light / Dark Mode Toggle -->
          <button class="theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()" title="Toggle Light / Dark Mode" aria-label="Toggle Theme">
            <i class="fas fa-sun" id="themeIcon"></i>
          </button>

          <!-- WhatsApp Us -->
          <a href="https://wa.me/<?= $companyWhatsApp ?>?text=Hello%20Tek%20Trend,%20I%20am%20exploring%20your%20Live%20Demo%20Prototypes%20and%20turnkey%20systems." target="_blank" class="btn-nav-outline btn-nav-whatsapp">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>

          <!-- Portal Login / Dashboard -->
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= eurl('/dashboard') ?>" class="btn-nav-outline"><i class="fas fa-chart-pie"></i> Portal</a>
          <?php else: ?>
            <a href="<?= eurl('/login') ?>" class="btn-nav-outline"><i class="fas fa-lock"></i> Login</a>
          <?php endif; ?>

          <!-- Mobile Toggle -->
          <button class="mobile-nav-toggle" onclick="toggleMobileDrawer()" aria-label="Toggle Navigation">
            <i class="fas fa-bars"></i>
          </button>
        </div>
      </div>

      <!-- Mobile Dropdown Drawer -->
      <div class="mobile-drawer" id="mobileDrawer">
        <a href="<?= eurl('/') ?>"><i class="fas fa-home"></i> Home</a>
        <a href="<?= eurl('/#portfolio') ?>"><i class="fas fa-layer-group"></i> Services</a>
        <a href="<?= eurl('/#consultation') ?>"><i class="fas fa-calendar-check"></i> Book Consultation</a>
        <a href="<?= eurl('/#contact') ?>"><i class="fas fa-envelope"></i> Contact</a>
        <a href="#demoGrid"><i class="fas fa-rocket"></i> All Prototypes (<?= count($demos) ?>)</a>
      </div>
    </div>
  </header>

  <div class="container">

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

          <!-- 3 Actions -->
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
      <span>&copy; <?= date('Y') ?> <a href="<?= eurl('/') ?>">Tek Trend Innovations</a> · Premium Software Architecture & Design Consultancy</span>
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
          <input type="text" name="template_title" id="requestTemplateTitle" class="form-control" readonly style="background: var(--accent-glow); border-color: var(--border-hover); color: var(--accent); font-weight: 700;">
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
          <button type="submit" class="btn-submit-modal" style="background: linear-gradient(135deg, #3b82f6, #2563eb); color: #fff;">
            <i class="fas fa-paper-plane"></i> Submit System Architecture Request
          </button>
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
          <select name="license" id="buyLicenseSelect" class="form-control">
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

  <!-- Include Google Gemini AI Chatbot -->
  <?php require_once BASE_PATH . '/app/views/partials/ai_chat.php'; ?>

  <script>
    // Theme Toggle Functionality
    function toggleTheme() {
      const current = document.documentElement.getAttribute('data-theme') || 'dark';
      const next = current === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      localStorage.setItem('tektrend_theme', next);
      updateThemeIcon(next);
    }

    function updateThemeIcon(theme) {
      const icon = document.getElementById('themeIcon');
      if (!icon) return;
      if (theme === 'light') {
        icon.className = 'fas fa-moon';
      } else {
        icon.className = 'fas fa-sun';
      }
    }

    // Initialize Theme Icon on Load
    document.addEventListener('DOMContentLoaded', () => {
      const current = document.documentElement.getAttribute('data-theme') || 'dark';
      updateThemeIcon(current);
    });

    // Mobile Navigation Drawer Toggle
    function toggleMobileDrawer() {
      const drawer = document.getElementById('mobileDrawer');
      drawer.classList.toggle('open');
    }

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
