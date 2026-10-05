<?php
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyEmail = $settings['company_email'] ?? 'tektrend.softwares@gmail.com';
$companyAddress = $settings['company_address'] ?? 'Tek Cyber, Roadblock, Eldoret, Kenya';
$canonicalUrl = url('/live-demos');
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tektrend Softwares · Eldoret, Kenya · Live Production Prototypes</title>
  <meta name="description" content="Explore real production HTML prototypes, interactive web architectures, and turnkey source code templates by Tektrend Softwares Eldoret, Kenya.">
  <meta name="keywords" content="Tektrend Softwares, Eldoret Kenya, We are Tektrend Softwares, turnkey web templates, live demos, software prototypes, HTML prototypes, PHP systems">
  <meta name="author" content="Tektrend Softwares">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
  <link rel="canonical" href="<?= eurl('/live-demos') ?>">
  <meta name="theme-color" content="#0e0e0e">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
  <link rel="alternate icon" href="<?= eurl('/favicon.svg') ?>">
  <link rel="apple-touch-icon" href="<?= eurl('/favicon.svg') ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Tektrend Softwares">
  <meta property="og:url" content="<?= eurl('/live-demos') ?>">
  <meta property="og:title" content="Tektrend Softwares · Eldoret, Kenya · We are Tektrend Softwares">
  <meta property="og:description" content="Explore real production HTML prototypes, interactive web architectures, and turnkey source code templates by Tektrend Softwares.">
  <meta property="og:image" content="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop">
  <meta property="og:locale" content="en_US">

  <!-- Anti-flicker Theme & Palette Script -->
  <script>
    (function() {
      const savedTheme = localStorage.getItem('tektrend_theme') || 'dark';
      const savedPalette = localStorage.getItem('tektrend_palette') || 'terracotta';
      document.documentElement.setAttribute('data-theme', savedTheme);
      document.documentElement.setAttribute('data-palette', savedPalette);
    })();
  </script>

  <!-- Google Fonts: Syne & Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Design System Stylesheet -->
  <link rel="stylesheet" href="<?= eurl('/assets/css/main.css') ?>">

  <!-- Lenis Smooth Inertia Scroll (Akaru Physics) -->
  <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js"></script>

  <!-- GSAP & ScrollTrigger -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

  <style>
    /* Specific overrides for the live demos portfolio grid */
    .portfolio-grid-card {
      position: relative;
      border-radius: 2.4rem;
      background: var(--bg-card);
      border: 1px solid var(--border-subtle);
      padding: 2.4rem;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
    .portfolio-grid-card:hover {
      background: var(--bg-card-hover);
    }
    .portfolio-card-thumb {
      position: relative;
      width: 100%;
      aspect-ratio: 16 / 9;
      border-radius: 1.6rem;
      overflow: hidden;
      background: #111;
      margin-bottom: 2rem;
    }
    .portfolio-card-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .card-actions-trio {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
      flex-wrap: wrap;
    }
    .btn-quick-run {
      flex: 1;
      min-width: 140px;
      padding: 1.1rem 1.6rem;
      border-radius: 10rem;
      background: var(--pill-bg);
      color: var(--pill-text);
      font-size: 1.25rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.6rem;
    }
    .btn-card-sub {
      padding: 1.1rem 1.4rem;
      border-radius: 10rem;
      border: 1px solid var(--border-subtle);
      font-size: 1.2rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: var(--text-primary);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    .btn-card-sub:hover {
      border-color: var(--terra);
    }

    /* Modal styling */
    .akar-modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.85);
      backdrop-filter: blur(12px);
      z-index: 100008;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .akar-modal-box {
      background: var(--bg-card);
      border: 1px solid var(--border-subtle);
      border-radius: 2.4rem;
      padding: 4rem;
      max-width: 65rem;
      width: 100%;
      position: relative;
      color: var(--text-primary);
      box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
    }
    .akar-modal-close {
      position: absolute;
      top: 2rem;
      right: 2rem;
      font-size: 2.6rem;
      color: var(--text-primary);
      cursor: pointer;
    }
    .akar-form-group {
      margin-bottom: 1.6rem;
    }
    .akar-form-group label {
      display: block;
      font-size: 1.2rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      color: var(--text-muted);
      margin-bottom: 0.6rem;
    }
    .akar-form-input {
      width: 100%;
      padding: 1.3rem 1.6rem;
      border-radius: 1.2rem;
      background: var(--bg-page);
      border: 1px solid var(--border-subtle);
      color: var(--text-primary);
      font-size: 1.4rem;
      font-family: inherit;
    }
    .akar-form-input:focus {
      outline: none;
      border-color: var(--terra);
    }
  </style>
</head>
<body id="top">
  <!-- FLUID MAGNETIC CURSOR -->
  <div class="akar-cursor" id="akarCursor">
    <span class="akar-cursor-badge">VIEW</span>
  </div>
  <!-- FLOATING HEADER -->
  <header class="akar-header">
    <div class="akar-header-left">
      <a href="<?= eurl('/') ?>" class="akar-brand-logo">
        Tektrend<span class="akar-dot"></span>
      </a>
    </div>

    <div class="akar-header-right">
      <!-- Invest in Tektrend Button -->
      <button class="akar-invest-pill" onclick="openInvestModal()" title="Invest in Tektrend Softwares" aria-label="Invest in Tektrend Softwares">
        <i class="fas fa-chart-line"></i>
        <span class="akar-invest-label">Invest</span>
      </button>

      <!-- Company Portal / Sign In -->
      <a href="<?= eurl(isset($_SESSION['user_id']) ? '/dashboard' : '/login') ?>" class="akar-login-pill" title="Sign In" aria-label="Sign In">
        <i class="fas fa-arrow-right-to-bracket"></i>
        <span class="akar-login-label">Sign In</span>
      </a>

      <!-- Color Palette Switcher Pill -->
      <button class="akar-palette-pill" onclick="togglePaletteModal()" title="Select Color Theme" aria-label="Select Color Theme">
        <i class="fas fa-palette"></i>
        <span class="akar-palette-label">Theme</span>
      </button>

      <!-- Dark / Light Mode Switcher -->
      <button class="akar-theme-pill" onclick="toggleAkaruTheme()" title="Toggle Dark/Light Mode" aria-label="Toggle Dark/Light Mode">
        <i class="fas fa-sun" id="akarThemeIcon"></i>
      </button>

      <!-- Akaru Pill Burger Button -->
      <button class="akar-burger-btn" id="akarBurgerBtn" aria-label="Open Navigation Menu">
        <span class="akar-burger-label">Menu</span>
        <div class="akar-burger-circle">
          <div class="akar-burger-icon">
            <span></span>
            <span></span>
          </div>
        </div>
      </button>
    </div>
  </header>

  <!-- COLOR THEME SWITCHER MODAL -->
  <div class="akar-palette-modal" id="akarPaletteModal" role="dialog" aria-labelledby="akarPaletteTitle" aria-modal="true">
    <div class="akar-palette-header">
      <div class="akar-palette-title" id="akarPaletteTitle">
        <i class="fas fa-palette"></i>
        <span>Color Theme</span>
      </div>
      <button class="akar-palette-close" onclick="closePaletteModal()" aria-label="Close theme selector">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="akar-palette-grid">
      <!-- 1. Terracotta Akaru -->
      <button class="akar-palette-option" data-palette-target="terracotta" onclick="setAkaruPalette('terracotta')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #e49366;"></span>
          <span class="akar-palette-dot" style="background: #798e7b;"></span>
          <span class="akar-palette-dot" style="background: #b692a1;"></span>
          <span class="akar-palette-dot" style="background: #8bb4c9;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Terracotta</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">French studio warm earth & stone</div>
      </button>

      <!-- 2. Emerald Luxe -->
      <button class="akar-palette-option" data-palette-target="emerald" onclick="setAkaruPalette('emerald')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #10b981;"></span>
          <span class="akar-palette-dot" style="background: #059669;"></span>
          <span class="akar-palette-dot" style="background: #f59e0b;"></span>
          <span class="akar-palette-dot" style="background: #06b6d4;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Emerald Gold</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">Prestige forest green & royal gold</div>
      </button>

      <!-- 3. Cyberpunk Violet -->
      <button class="akar-palette-option" data-palette-target="cyberpunk" onclick="setAkaruPalette('cyberpunk')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #a855f7;"></span>
          <span class="akar-palette-dot" style="background: #06b6d4;"></span>
          <span class="akar-palette-dot" style="background: #f43f5e;"></span>
          <span class="akar-palette-dot" style="background: #3b82f6;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Cyberpunk</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">Neo-Tokyo violet & laser rose</div>
      </button>

      <!-- 4. Cobalt Cyan -->
      <button class="akar-palette-option" data-palette-target="cobalt" onclick="setAkaruPalette('cobalt')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #3b82f6;"></span>
          <span class="akar-palette-dot" style="background: #10b981;"></span>
          <span class="akar-palette-dot" style="background: #6366f1;"></span>
          <span class="akar-palette-dot" style="background: #0ea5e9;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Nordic Cobalt</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">Electric blue & high-tech indigo</div>
      </button>

      <!-- 5. Sunset Amber -->
      <button class="akar-palette-option" data-palette-target="sunset" onclick="setAkaruPalette('sunset')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #f97316;"></span>
          <span class="akar-palette-dot" style="background: #eab308;"></span>
          <span class="akar-palette-dot" style="background: #ef4444;"></span>
          <span class="akar-palette-dot" style="background: #ec4899;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Solar Sunset</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">Vivid amber orange & crimson heat</div>
      </button>

      <!-- 6. Monochrome Platinum -->
      <button class="akar-palette-option" data-palette-target="monochrome" onclick="setAkaruPalette('monochrome')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #ffffff; border: 1px solid #555;"></span>
          <span class="akar-palette-dot" style="background: #94a3b8;"></span>
          <span class="akar-palette-dot" style="background: #64748b;"></span>
          <span class="akar-palette-dot" style="background: #cbd5e1;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Monochrome</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">Brutalist obsidian & pure platinum</div>
      </button>

      <!-- 7. Alabaster Ivory & Champagne Light -->
      <button class="akar-palette-option" data-palette-target="alabaster" onclick="setAkaruPalette('alabaster')">
        <div class="akar-palette-swatches">
          <span class="akar-palette-dot" style="background: #faf9f5; border: 1px solid #bbb;"></span>
          <span class="akar-palette-dot" style="background: #d97706;"></span>
          <span class="akar-palette-dot" style="background: #059669;"></span>
          <span class="akar-palette-dot" style="background: #2563eb;"></span>
        </div>
        <div class="akar-palette-opt-name">
          <span>Alabaster Light</span>
          <i class="fas fa-check"></i>
        </div>
        <div class="akar-palette-opt-desc">Ultra-light linen ivory & champagne</div>
      </button>
    </div>
  </div>

  <!-- INVESTOR RELATIONS & CAPITAL MODAL -->
  <div class="akar-invest-modal-overlay" id="akarInvestModal" role="dialog" aria-labelledby="akarInvestTitle" aria-modal="true">
    <div class="akar-invest-modal">
      <div class="akar-invest-header">
        <div>
          <div class="akar-invest-badge"><i class="fas fa-gem"></i> Investor Relations & Growth Capital</div>
          <h2 class="akar-invest-title" id="akarInvestTitle">Invest in Tektrend Softwares</h2>
          <p class="akar-invest-subtitle">We are Tektrend Softwares based in Eldoret, Kenya. Partner with East Africa's elite software architecture and turnkey digital systems powerhouse.</p>
        </div>
        <button class="akar-palette-close" onclick="closeInvestModal()" aria-label="Close investor dialog">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Quick Metrics Grid -->
      <div class="akar-invest-grid-stats">
        <div class="akar-invest-stat-card">
          <div class="akar-invest-stat-val">11+</div>
          <div class="akar-invest-stat-lbl">Live Turnkey Platforms</div>
        </div>
        <div class="akar-invest-stat-card">
          <div class="akar-invest-stat-val">Eldoret, KE</div>
          <div class="akar-invest-stat-lbl">East Africa Tech Hub</div>
        </div>
        <div class="akar-invest-stat-card">
          <div class="akar-invest-stat-val">78%+</div>
          <div class="akar-invest-stat-lbl">SaaS Software Margin</div>
        </div>
      </div>

      <!-- Investment Tiers -->
      <div style="margin-bottom: 1rem; font-size: 1.25rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary);">
        Select Investment Tier
      </div>
      <div class="akar-invest-tiers">
        <button type="button" class="akar-tier-btn --active" data-tier="angel" onclick="selectInvestTier(1000, 'angel')">
          <div class="akar-tier-name">Angel Syndicate</div>
          <div class="akar-tier-amount">$1,000</div>
          <div class="akar-tier-note">Product Royalty & Advisory Pool</div>
        </button>
        <button type="button" class="akar-tier-btn" data-tier="growth" onclick="selectInvestTier(5000, 'growth')">
          <div class="akar-tier-name">Growth Round</div>
          <div class="akar-tier-amount">$5,000</div>
          <div class="akar-tier-note">Equity SAFE & Board Briefings</div>
        </button>
        <button type="button" class="akar-tier-btn" data-tier="institutional" onclick="selectInvestTier(25000, 'institutional')">
          <div class="akar-tier-name">Strategic Partner</div>
          <div class="akar-tier-amount">$25,000+</div>
          <div class="akar-tier-note">Direct Cap Table & Enterprise Rights</div>
        </button>
      </div>

      <!-- Investor Submission Form -->
      <form action="<?= eurl('/invest') ?>" method="POST">
        <input type="hidden" name="investment_amount" id="investAmountInput" value="1000">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.4rem; margin-bottom: 1.4rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 0.6rem;">Investor / Firm Name *</label>
            <input type="text" name="name" required style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-primary); font-size: 1.4rem;" placeholder="e.g. Kipchoge Capital">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 0.6rem;">Email Address *</label>
            <input type="email" name="email" required style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-primary); font-size: 1.4rem;" placeholder="investor@fund.com">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.4rem; margin-bottom: 1.4rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 0.6rem;">WhatsApp / Phone Number</label>
            <input type="text" name="phone" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-primary); font-size: 1.4rem;" placeholder="+254 700 000000">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 0.6rem;">Investor Type</label>
            <select name="investor_type" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-primary); font-size: 1.4rem;">
              <option value="Angel Investor">Angel Investor</option>
              <option value="Venture Capital / Fund">Venture Capital / Fund</option>
              <option value="Private Equity">Private Equity</option>
              <option value="Strategic Corporate Partner">Strategic Corporate Partner</option>
              <option value="Syndicate Member">Syndicate Member</option>
            </select>
          </div>
        </div>

        <div style="margin-bottom: 1.8rem;">
          <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); display: block; margin-bottom: 0.6rem;">Investor Notes & Target Allocation</label>
          <textarea name="notes" rows="3" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: var(--bg-card); border: 1px solid var(--border-subtle); color: var(--text-primary); font-size: 1.4rem;" placeholder="Let us know your investment thesis, target ticket size, or questions..."></textarea>
        </div>

        <button type="submit" class="akar-invest-pill" style="width: 100%; justify-content: center; height: 5.2rem; font-size: 1.4rem;">
          Submit Investment Inquiry & Request Pitch Deck <i class="fas fa-arrow-right" style="margin-left: 0.8rem;"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- FULLSCREEN CLIP-PATH NAVIGATION OVERLAY -->
  <div class="akar-nav-overlay" id="akarNavOverlay">
    <div class="akar-nav-top">
      <ul class="akar-nav-menu-list">
        <li class="akar-nav-item" data-teaser="home">
          <a href="<?= eurl('/') ?>" class="akar-nav-link">
            01 / Home <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="projects">
          <a href="<?= eurl('/#akarShowcase') ?>" class="akar-nav-link">
            02 / Selected Works <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="services">
          <a href="<?= eurl('/#services') ?>" class="akar-nav-link">
            03 / Expertise <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="demos">
          <a href="<?= eurl('/live-demos') ?>" class="akar-nav-link">
            04 / Live Demos (<?= count($demos) ?>) <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="consultation">
          <a href="<?= eurl('/#consultation') ?>" class="akar-nav-link">
            05 / Book Consultation <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="portal">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= eurl('/dashboard') ?>" class="akar-nav-link">
              06 / Company Dashboard <span class="akar-nav-arrow"><i class="fas fa-chart-pie"></i></span>
            </a>
          <?php else: ?>
            <a href="<?= eurl('/login') ?>" class="akar-nav-link">
              06 / Sign In / Portal <span class="akar-nav-arrow"><i class="fas fa-arrow-right-to-bracket"></i></span>
            </a>
          <?php endif; ?>
        </li>
      </ul>

      <div class="akar-nav-teaser-box">
        <img src="https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=800&q=80" id="akarTeaserImg" alt="Teaser">
        <div class="akar-teaser-badge" id="akarTeaserBadge">All Production Prototypes</div>
      </div>
    </div>

    <div class="akar-nav-bottom">
      <div class="akar-col">
        <div class="akar-col-title">Studio Location</div>
        <div class="akar-col-content">
          <?= sanitize($companyAddress) ?><br>
          East Africa & Remote Global
        </div>
      </div>
      <div class="akar-col">
        <div class="akar-col-title">Direct Inquiries</div>
        <div class="akar-col-content">
          <a href="mailto:<?= sanitize($companyEmail) ?>"><?= sanitize($companyEmail) ?></a>
          <a href="tel:<?= sanitize($companyPhone) ?>"><?= sanitize($companyPhone) ?></a>
        </div>
      </div>
      <div class="akar-col">
        <div class="akar-col-title">Quick Connect</div>
        <div class="akar-col-content">
          <a href="https://wa.me/<?= $companyWhatsApp ?>" target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp Team</a>
          <a href="<?= eurl('/') ?>"><i class="fas fa-home"></i> Main Site</a>
        </div>
      </div>
      <div class="akar-col">
        <div class="akar-col-title">Networks</div>
        <div class="akar-col-content">
          <a href="https://github.com/Mchungaji-tech" target="_blank">GitHub</a>
          <a href="https://linkedin.com" target="_blank">LinkedIn</a>
          <a href="https://twitter.com" target="_blank">X (Twitter)</a>
        </div>
      </div>
    </div>

    <div class="akar-ghost-letters">PROJETS</div>
  </div>

  <!-- HERO SECTION -->
  <section class="akar-portfolio-hero">
    <div style="font-size: 1.3rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--terra); margin-bottom: 1.5rem;">
      [ PRODUCTION ARCHITECTURES & TURNKEY SOURCE CODE ]
    </div>
    <h1 style="font-family: 'Syne', sans-serif; font-size: clamp(3.6rem, 6vw, 7.2rem); font-weight: 900; letter-spacing: -0.04em; line-height: 1.05; margin-bottom: 1.8rem;">
      Selected Works & Live Prototypes
    </h1>
    <p style="font-size: 1.8rem; color: var(--text-secondary); max-width: 80rem; line-height: 1.6;">
      Explore our active production prototypes. Launch interactive live previews across desktop, tablet, and mobile frames, request custom enterprise implementations, or acquire turnkey source code.
    </p>

    <?php if (flash('success')): ?>
      <div style="margin-top: 2rem; padding: 1.6rem 2.4rem; border-radius: 1.6rem; background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.3); color: #4ade80; font-size: 1.45rem; font-weight: 600;">
        <i class="fas fa-check-circle" style="margin-right: 0.8rem;"></i> <?= sanitize(flash('success')) ?>
      </div>
    <?php endif; ?>

    <!-- AKARU FILTER PILLS -->
    <div class="akar-filters-wrap">
      <button class="akar-filter-btn --active" data-filter="all" onclick="filterAkaruDemos('all')">
        <span class="akar-filter-dot"></span> All Showcases (<?= count($demos) ?>)
      </button>
      <button class="akar-filter-btn" data-filter="creative" onclick="filterAkaruDemos('creative')">
        <span class="akar-filter-dot"></span> Creative & Agency
      </button>
      <button class="akar-filter-btn" data-filter="marketing" onclick="filterAkaruDemos('marketing')">
        <span class="akar-filter-dot"></span> Marketing & SEO
      </button>
      <button class="akar-filter-btn" data-filter="ecommerce" onclick="filterAkaruDemos('ecommerce')">
        <span class="akar-filter-dot"></span> E-Commerce & Retail
      </button>
      <button class="akar-filter-btn" data-filter="legal" onclick="filterAkaruDemos('legal')">
        <span class="akar-filter-dot"></span> Legal & Corporate
      </button>
      <button class="akar-filter-btn" data-filter="engineering" onclick="filterAkaruDemos('engineering')">
        <span class="akar-filter-dot"></span> Industrial & Tech
      </button>
      <button class="akar-filter-btn" data-filter="hospitality" onclick="filterAkaruDemos('hospitality')">
        <span class="akar-filter-dot"></span> Hospitality & Dining
      </button>
      <button class="akar-filter-btn" data-filter="saas" onclick="filterAkaruDemos('saas')">
        <span class="akar-filter-dot"></span> SaaS & Cloud
      </button>
    </div>
  </section>

  <!-- PORTFOLIO GRID -->
  <section class="akar-portfolio-grid">
    <?php foreach ($demos as $index => $demo): ?>
      <?php
        $catLower = strtolower($demo['category']);
        $filterClasses = 'all';
        if (strpos($catLower, 'creative') !== false || strpos($catLower, 'design') !== false) $filterClasses .= ' creative';
        if (strpos($catLower, 'marketing') !== false || strpos($catLower, 'seo') !== false) $filterClasses .= ' marketing';
        if (strpos($catLower, 'commerce') !== false || strpos($catLower, 'retail') !== false) $filterClasses .= ' ecommerce';
        if (strpos($catLower, 'legal') !== false || strpos($catLower, 'corporate') !== false) $filterClasses .= ' legal';
        if (strpos($catLower, 'engineering') !== false || strpos($catLower, 'industrial') !== false) $filterClasses .= ' engineering';
        if (strpos($catLower, 'restaurant') !== false || strpos($catLower, 'hospitality') !== false || strpos($catLower, 'dining') !== false) $filterClasses .= ' hospitality';
        if (strpos($catLower, 'saas') !== false || strpos($catLower, 'fintech') !== false || strpos($catLower, 'publishing') !== false) $filterClasses .= ' saas';

        $priceVal = !empty($demo['price']) ? (float)$demo['price'] : 49.00;
        $projData = [
            'id' => $demo['id'] ?? $index,
            'title' => $demo['title'],
            'category' => $demo['category'],
            'desc' => $demo['short_description'],
            'url' => url($demo['demo_url']),
            'tech' => $demo['tech_stack'] ?? 'HTML5, CSS3, JavaScript',
            'price' => $priceVal
        ];
      ?>
      <div class="portfolio-grid-card akar-portfolio-item" data-category="<?= $filterClasses ?>">
        <div>
          <!-- Top Row -->
          <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <span style="font-family: 'Syne', sans-serif; font-size: 1.35rem; font-weight: 800; color: var(--terra);">
              0<?= $index + 1 ?>
            </span>
            <div style="display: flex; gap: 0.8rem;">
              <span class="akar-pill-tag"><?= sanitize($demo['category']) ?></span>
              <?php if (!empty($demo['award_badge'])): ?>
                <span class="akar-pill-tag" style="background: rgba(228, 147, 102, 0.15); color: var(--terra);"><i class="fas fa-medal"></i> <?= sanitize($demo['award_badge']) ?></span>
              <?php endif; ?>
            </div>
          </div>

          <!-- Thumbnail -->
          <?php if (!empty($demo['preview_image'])): ?>
            <div class="portfolio-card-thumb" onclick="openAkaruCaseStudy(<?= htmlspecialchars(json_encode($projData), ENT_QUOTES) ?>)">
              <img src="<?= eurl($demo['preview_image']) ?>" alt="<?= sanitize($demo['title']) ?>" loading="lazy">
            </div>
          <?php endif; ?>

          <h3 style="font-family: 'Syne', sans-serif; font-size: 2.6rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 0.8rem;">
            <?= sanitize($demo['title']) ?>
          </h3>
          <p style="font-size: 1.45rem; color: var(--text-secondary); line-height: 1.55; margin-bottom: 1.5rem;">
            <?= sanitize($demo['short_description']) ?>
          </p>

          <?php if (!empty($demo['tech_stack'])): ?>
            <div style="display: flex; flex-wrap: wrap; gap: 0.6rem; margin-bottom: 2rem;">
              <?php foreach (explode(',', $demo['tech_stack']) as $t): ?>
                <span style="font-size: 1.15rem; font-weight: 600; padding: 0.35rem 1rem; border-radius: 20rem; background: var(--border-subtle); color: var(--text-secondary);">
                  <?= trim(sanitize($t)) ?>
                </span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>

        <div>
          <div style="display: flex; justify-content: space-between; align-items: baseline; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle);">
            <div style="font-size: 1.35rem; color: var(--text-secondary);">
              Turnkey License: <strong style="font-size: 1.8rem; color: var(--text-primary); margin-left: 0.4rem;">$<?= number_format($priceVal, 0) ?></strong>
            </div>
            <div style="font-size: 1.15rem; font-weight: 700; text-transform: uppercase; color: #22c55e;">
              <i class="fas fa-check"></i> Commercial Ready
            </div>
          </div>

          <!-- Trio Action Row -->
          <div class="card-actions-trio">
            <button type="button" class="btn-quick-run" onclick="openAkaruCaseStudy(<?= htmlspecialchars(json_encode($projData), ENT_QUOTES) ?>)">
              <i class="fas fa-play"></i> Interactive View
            </button>
            <button type="button" class="btn-card-sub" onclick="openRequestCustomModal('<?= htmlspecialchars(addslashes($demo['title']), ENT_QUOTES) ?>')">
              <i class="fas fa-cogs"></i> Custom Build
            </button>
            <button type="button" class="btn-card-sub" onclick="openBuyTurnkeyModal('<?= htmlspecialchars(addslashes($demo['title']), ENT_QUOTES) ?>', '<?= $priceVal ?>')">
              <i class="fas fa-shopping-cart"></i> Buy Code ($<?= number_format($priceVal, 0) ?>)
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </section>

  <!-- IMMERSIVE CASE STUDY DRAWER & MULTI-DEVICE PROTOTYPE RUNNER -->
  <div class="akar-case-drawer" id="akarCaseDrawer">
    <div class="akar-drawer-nav">
      <button type="button" class="akar-drawer-back-btn" id="akarBackDrawer">
        <i class="fas fa-arrow-left"></i> Back to Gallery
      </button>

      <div class="akar-device-switcher">
        <button type="button" class="akar-device-btn --active" data-device="desktop">
          <i class="fas fa-desktop"></i> Desktop
        </button>
        <button type="button" class="akar-device-btn" data-device="tablet">
          <i class="fas fa-tablet-screen-button"></i> Tablet
        </button>
        <button type="button" class="akar-device-btn" data-device="mobile">
          <i class="fas fa-mobile-screen"></i> Mobile
        </button>
      </div>

      <button type="button" class="akar-drawer-close-btn" id="akarCloseDrawer" aria-label="Close Case Study">
        &times;
      </button>
    </div>

    <div class="akar-drawer-body">
      <div class="akar-drawer-header">
        <div class="akar-drawer-meta">
          <span id="akarDrawerCategory">Production Architecture</span>
          <span>Verified Interactive Build</span>
          <span>Turnkey Code Available</span>
        </div>
        <h2 id="akarDrawerTitle">Selected Prototype</h2>
        <p id="akarDrawerDesc">High-speed production architecture engineered by Tek Trend.</p>
      </div>

      <div class="akar-live-frame-wrapper --desktop" id="akarFrameWrapper">
        <iframe id="akarDrawerIframe" src="about:blank" title="Interactive Prototype Runner"></iframe>
      </div>

      <div class="akar-case-specs-grid">
        <div class="akar-spec-box">
          <h4>Core Tech Stack</h4>
          <p id="akarDrawerTech">HTML5, Modern CSS, JavaScript</p>
        </div>
        <div class="akar-spec-box">
          <h4>Deliverables Included</h4>
          <p>Full Source Code, Assets, Styling, Documentation</p>
        </div>
        <div class="akar-spec-box">
          <h4>Commercial Licensing</h4>
          <p>Lifetime Commercial Turnkey License</p>
        </div>
      </div>

      <div class="akar-drawer-actions">
        <div class="akar-price-callout">
          Turnkey Source Code: <strong id="akarDrawerPrice">$49</strong>
        </div>

        <div class="akar-cta-btns">
          <a href="#" id="akarDrawerExternalLink" target="_blank" class="akar-btn-main">
            <i class="fas fa-up-right-from-square"></i> Open Live Prototype in New Tab
          </a>
          <button type="button" class="akar-btn-outline" onclick="openRequestFromDrawer()">
            <i class="fas fa-cogs"></i> Request Custom System Build
          </button>
          <button type="button" class="akar-btn-outline" onclick="openBuyFromDrawer()">
            <i class="fas fa-shopping-cart"></i> Buy Source Code License
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- MODAL 1: REQUEST CUSTOM SYSTEM BUILD -->
  <div class="akar-modal-overlay" id="requestCustomModal">
    <div class="akar-modal-box">
      <button class="akar-modal-close" onclick="closeAllModals()">&times;</button>
      <h3 style="font-family: 'Syne', sans-serif; font-size: 2.8rem; margin-bottom: 0.8rem;">Commission Custom Build</h3>
      <p style="color: var(--text-secondary); font-size: 1.4rem; margin-bottom: 2.5rem;">Have Tek Trend customize and deploy this system for your brand.</p>

      <form action="<?= eurl('/live-demos/request') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action_type" value="request_system">

        <div class="akar-form-group">
          <label>Selected Architecture</label>
          <input type="text" name="template_title" id="requestTemplateInput" class="akar-form-input" readonly style="font-weight: 700; color: var(--terra);">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
          <div class="akar-form-group">
            <label>Full Name *</label>
            <input type="text" name="name" required class="akar-form-input" placeholder="Jane Mwangi">
          </div>
          <div class="akar-form-group">
            <label>Email Address *</label>
            <input type="email" name="email" required class="akar-form-input" placeholder="jane@company.com">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
          <div class="akar-form-group">
            <label>WhatsApp / Phone</label>
            <input type="text" name="phone" class="akar-form-input" placeholder="+254 700 000000">
          </div>
          <div class="akar-form-group">
            <label>Organization</label>
            <input type="text" name="company" class="akar-form-input" placeholder="Global Logistics Ltd">
          </div>
        </div>

        <div class="akar-form-group">
          <label>Custom Features & Requirements</label>
          <textarea name="notes" rows="3" class="akar-form-input" placeholder="Describe any custom integrations, API keys, or payment gateways needed..."></textarea>
        </div>

        <button type="submit" style="width: 100%; padding: 1.5rem; border-radius: 10rem; background: var(--pill-bg); color: var(--pill-text); font-size: 1.35rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; cursor: pointer;">
          Submit Architecture Request <i class="fas fa-paper-plane" style="margin-left: 0.6rem;"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- MODAL 2: BUY TURNKEY CODE -->
  <div class="akar-modal-overlay" id="buyTurnkeyModal">
    <div class="akar-modal-box">
      <button class="akar-modal-close" onclick="closeAllModals()">&times;</button>
      <h3 style="font-family: 'Syne', sans-serif; font-size: 2.8rem; margin-bottom: 0.8rem;">Buy Turnkey Source Code</h3>
      <p style="color: var(--text-secondary); font-size: 1.4rem; margin-bottom: 2.5rem;">Receive the clean source code package with commercial rights.</p>

      <form action="<?= eurl('/live-demos/request') ?>" method="POST">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="action_type" value="buy_template">

        <div class="akar-form-group">
          <label>Selected Template</label>
          <input type="text" name="template_title" id="buyTemplateInput" class="akar-form-input" readonly style="font-weight: 700; color: var(--terra);">
        </div>

        <div class="akar-form-group">
          <label>Package Tier</label>
          <select name="license" class="akar-form-input">
            <option value="Standard Turnkey Source Code">Turnkey Codebase Package</option>
            <option value="Source Code + Domain & Hosting Deployment">Turnkey Code + Cloud / Domain Setup (+$99)</option>
            <option value="Turnkey Code + 1-Month Engineering Support">Turnkey Code + Engineering Retainer</option>
          </select>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
          <div class="akar-form-group">
            <label>Your Name *</label>
            <input type="text" name="name" required class="akar-form-input" placeholder="David Otieno">
          </div>
          <div class="akar-form-group">
            <label>Email Address *</label>
            <input type="email" name="email" required class="akar-form-input" placeholder="david@company.com">
          </div>
        </div>

        <div class="akar-form-group">
          <label>Phone / WhatsApp Number</label>
          <input type="text" name="phone" class="akar-form-input" placeholder="+254 700 000000">
        </div>

        <button type="submit" style="width: 100%; padding: 1.5rem; border-radius: 10rem; background: var(--terra); color: #0e0e0e; font-size: 1.35rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; cursor: pointer;">
          Confirm Order & Receive Handover Pack <i class="fas fa-shopping-cart" style="margin-left: 0.6rem;"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- AKARU FOOTER -->
  <footer class="akar-footer">
    <div class="akar-footer-top">
      <div class="akar-footer-brand">
        <h3>Tektrend Softwares</h3>
        <p>We are Tektrend Softwares. Production prototypes, bespoke software architectures, and turnkey source code packages based in Eldoret, Kenya.</p>
      </div>

      <div class="akar-footer-links-col">
        <h4>Explore</h4>
        <ul>
          <li><a href="<?= eurl('/') ?>">Main Agency</a></li>
          <li><a href="<?= eurl('/#akarShowcase') ?>">Featured Works</a></li>
          <li><a href="<?= eurl('/#services') ?>">Capabilities</a></li>
          <li><a href="<?= eurl('/#consultation') ?>">Book Scoping Zoom</a></li>
          <li><a href="<?= eurl(isset($_SESSION['user_id']) ? '/dashboard' : '/login') ?>">Sign In</a></li>
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
          Turnkey Delivery Available
        </p>
      </div>
    </div>

    <div class="akar-footer-brand-mark">
      <div class="akar-big-brand">TEKTREND</div>
    </div>

    <div class="akar-footer-bottom">
      <div>&copy; <?= date('Y') ?> Tektrend Softwares · Eldoret, Kenya · We are Tektrend Softwares</div>
      <a href="#top" class="akar-back-to-top">
        Back to Top <i class="fas fa-arrow-up"></i>
      </a>
    </div>
  </footer>

  <!-- Modal Scripts -->
  <script>
    function openRequestCustomModal(title) {
      document.getElementById('requestTemplateInput').value = title;
      document.getElementById('requestCustomModal').style.display = 'flex';
    }
    function openBuyTurnkeyModal(title, price) {
      document.getElementById('buyTemplateInput').value = title + ' ($' + price + ')';
      document.getElementById('buyTurnkeyModal').style.display = 'flex';
    }
    function openRequestFromDrawer() {
      const title = document.getElementById('akarDrawerTitle').textContent;
      document.getElementById('akarCloseDrawer').click();
      openRequestCustomModal(title);
    }
    function openBuyFromDrawer() {
      const title = document.getElementById('akarDrawerTitle').textContent;
      const price = document.getElementById('akarDrawerPrice').textContent.replace('$', '');
      document.getElementById('akarCloseDrawer').click();
      openBuyTurnkeyModal(title, price);
    }
    function closeAllModals() {
      document.getElementById('requestCustomModal').style.display = 'none';
      document.getElementById('buyTurnkeyModal').style.display = 'none';
    }
  </script>

  <!-- Platform Engine JS -->
  <script src="<?= eurl('/assets/js/main.js') ?>"></script>
</body>
</html>
