<?php
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyEmail = $settings['company_email'] ?? 'tektrend.softwares@gmail.com';
$companyAddress = $settings['company_address'] ?? 'Tektrend Softwares, Eldoret, Kenya';
$siteCanonicalUrl = url('/');

$metaTitle = cms('meta_title', 'Tektrend Softwares · Eldoret, Kenya · Software Architecture & Creative Studio');
$metaDescription = cms('meta_description', "Tektrend Softwares · Enterprise software architecture, digital innovation, cloud dashboards, and turnkey production platforms based in Eldoret, Kenya. We are Tektrend Softwares.");
$metaKeywords = cms('meta_keywords', 'Tektrend Softwares, Tektrend Softwares Eldoret Kenya, We are Tektrend Softwares, software architecture, web development, custom software, PHP 8 MVC, Google Apps Script, dashboards, Eldoret, Kenya, creative agency');
$ogImage = cms_img('og_image', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=85');

// Dynamic showcase from database portfolio demos if available, or fallback to default curated
if (!empty($portfolioDemos)) {
    $colors = ['terra', 'green', 'pink', 'blue', 'terra', 'green'];
    $featuredShowcase = [];
    foreach ($portfolioDemos as $idx => $pDemo) {
        $demoLink = $pDemo['demo_url'] ?? '';
        if ($demoLink && strpos($demoLink, 'http') !== 0 && strpos($demoLink, '/') !== 0) {
            $demoLink = '/' . $demoLink;
        }
        $featuredShowcase[] = [
            'id' => $pDemo['id'],
            'title' => $pDemo['title'],
            'category' => $pDemo['category'] ?? 'Enterprise Architecture',
            'color' => $colors[$idx % count($colors)],
            'year' => date('Y', strtotime($pDemo['created_at'] ?? 'now')),
            'short_desc' => $pDemo['short_description'] ?? 'High-performance cloud management system with real-time financial tracking.',
            'image' => $pDemo['preview_image'] ?: 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => strpos($demoLink, 'http') === 0 ? $demoLink : url($demoLink),
            'tech' => $pDemo['tech_stack'] ?? 'PHP 8.2 MVC, MySQL, Redis, GSAP',
            'price' => (float)($pDemo['price'] ?? 79.00)
        ];
    }
} else {
    // Default curated featured projects inspired by Akaru
    $featuredShowcase = [
        [
            'id' => 1,
            'title' => 'Nexus Commercial ERP',
            'category' => 'Enterprise Architecture',
            'color' => 'terra',
            'year' => '2026',
            'short_desc' => 'High-performance cloud management system with real-time financial tracking, multi-tenant RBAC, and executive reporting suite.',
            'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => url('/live_demo/nexus.html'),
            'tech' => 'PHP 8.2, MySQL 8, Redis, Chart.js, Tailwind MVC',
            'price' => 89.00
        ],
        [
            'id' => 2,
            'title' => 'Titan Industrial Automation',
            'category' => 'Industrial & Engineering',
            'color' => 'green',
            'year' => '2026',
            'short_desc' => 'Industrial equipment telemetry and blueprint management platform featuring interactive 3D technical viewer and procurement RFQ pipelines.',
            'image' => 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => url('/live_demo/engineering.html'),
            'tech' => 'HTML5 Canvas, WebGL, Modern CSS, REST APIs',
            'price' => 79.00
        ],
        [
            'id' => 3,
            'title' => 'LuxeCart Modern Storefront',
            'category' => 'E-Commerce & Retail',
            'color' => 'pink',
            'year' => '2025',
            'short_desc' => 'Ultra-fast headless commerce platform with instantaneous client filtering, dynamic cart drawer, multi-currency pricing, and M-Pesa readiness.',
            'image' => 'https://images.unsplash.com/photo-1556742049-0a67c5574f73?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => url('/live_demo/e-commerce.html'),
            'tech' => 'JavaScript ES6+, Modern CSS Grid, Checkout Engine',
            'price' => 79.00
        ],
        [
            'id' => 4,
            'title' => 'Vanguard & Sterling Legal',
            'category' => 'Corporate & Advisory',
            'color' => 'blue',
            'year' => '2025',
            'short_desc' => 'High-trust corporate legal portal featuring confidential case study archives, attorney directory, and encrypted consultation scheduling vaults.',
            'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => url('/live_demo/law_firm.html'),
            'tech' => 'Semantic HTML5, CSS3 Variables, Booking Vault',
            'price' => 69.00
        ],
        [
            'id' => 5,
            'title' => 'GrowthPulse Media & SEO',
            'category' => 'Marketing & Analytics',
            'color' => 'terra',
            'year' => '2026',
            'short_desc' => 'Growth-driven marketing agency portal with live campaign tracking, interactive ROI calculation models, and automated lead capture funnel.',
            'image' => 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => url('/live_demo/digital_markting.html'),
            'tech' => 'Chart.js, GSAP Animations, Responsive Grid',
            'price' => 59.00
        ],
        [
            'id' => 6,
            'title' => 'Le Jardin Gourmet Bistro',
            'category' => 'Hospitality & Dining',
            'color' => 'green',
            'year' => '2025',
            'short_desc' => 'Atmospheric culinary experience portal with seasonal degustation menu showcases, wine pairing guides, and real-time reservation desk.',
            'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1200&q=85',
            'demo_url' => url('/live_demo/restaurant.html'),
            'tech' => 'Playfair Typography, CSS Motion, Booking Form',
            'price' => 49.00
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="keywords" content="<?= htmlspecialchars($metaKeywords, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="author" content="Tektrend Softwares">
  <link rel="canonical" href="<?= eurl('/') ?>">
  <meta name="theme-color" content="#0e0e0e">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
  <link rel="alternate icon" href="<?= eurl('/favicon.svg') ?>">
  <link rel="apple-touch-icon" href="<?= eurl('/favicon.svg') ?>">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Tektrend Softwares">
  <meta property="og:url" content="<?= eurl('/') ?>">
  <meta property="og:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:locale" content="en_US">

  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="<?= eurl('/') ?>">
  <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($ogImage, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="twitter:image" content="https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=85">

  <!-- Anti-flicker Theme & Palette Script -->
  <script>
    (function() {
      const savedTheme = localStorage.getItem('tektrend_theme') || 'dark';
      const savedPalette = localStorage.getItem('tektrend_palette') || 'terracotta';
      document.documentElement.setAttribute('data-theme', savedTheme);
      document.documentElement.setAttribute('data-palette', savedPalette);
    })();
  </script>

  <!-- Google Fonts: Syne & Plus Jakarta Sans (Akaru Typography Hierarchy) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Design System Stylesheet -->
  <link rel="stylesheet" href="<?= eurl('/assets/css/main.css') ?>">

  <!-- Lenis Smooth Inertia Scroll (Akaru Physics) -->
  <script src="https://cdn.jsdelivr.net/npm/lenis@1.1.18/dist/lenis.min.js"></script>

  <!-- GSAP & ScrollTrigger (Akaru Pinned Horizontal Showcase & Timelines) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
</head>
<body id="top">
  <!-- FLUID MAGNETIC CURSOR (Akaru Elastic Blob) -->
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

      <!-- Front-End CMS Studio Direct Link -->
      <a href="<?= eurl('/content') ?>" class="akar-cms-pill" title="Front-End Visual CMS & Media Studio" aria-label="Front-End CMS & Media Studio">
        <i class="fas fa-magic"></i>
        <span class="akar-cms-label">Edit Content</span>
      </a>

      <!-- Company Portal / Sign In or Dashboard -->
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= eurl('/dashboard') ?>" class="akar-login-pill" title="Virtual Company Operations Console" aria-label="Company Dashboard">
          <i class="fas fa-chart-pie"></i>
          <span class="akar-login-label">Admin Console</span>
        </a>
      <?php else: ?>
        <a href="<?= eurl('/login') ?>" class="akar-login-pill" title="Company Portal / Sign In" aria-label="Sign In to Dashboard">
          <i class="fas fa-arrow-right-to-bracket"></i>
          <span class="akar-login-label">Sign In</span>
        </a>
      <?php endif; ?>

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
          <div class="akar-invest-badge"><i class="fas fa-gem"></i> <?= sanitize(cms('invest_badge', 'Investor Relations & Growth Capital')) ?></div>
          <h2 class="akar-invest-title" id="akarInvestTitle"><?= sanitize(cms('invest_title', 'Invest in Tektrend Softwares')) ?></h2>
          <p class="akar-invest-subtitle"><?= sanitize(cms('invest_subtitle', "We are Tektrend Softwares based in Eldoret, Kenya. Partner with East Africa's elite software architecture and turnkey digital systems powerhouse.")) ?></p>
        </div>
        <button class="akar-palette-close" onclick="closeInvestModal()" aria-label="Close investor dialog">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <!-- Quick Metrics Grid -->
      <div class="akar-invest-grid-stats">
        <div class="akar-invest-stat-card">
          <div class="akar-invest-stat-val"><?= sanitize(cms('invest_stat_1_val', '11+')) ?></div>
          <div class="akar-invest-stat-lbl"><?= sanitize(cms('invest_stat_1_lbl', 'Live Turnkey Platforms')) ?></div>
        </div>
        <div class="akar-invest-stat-card">
          <div class="akar-invest-stat-val"><?= sanitize(cms('invest_stat_2_val', 'Eldoret, KE')) ?></div>
          <div class="akar-invest-stat-lbl"><?= sanitize(cms('invest_stat_2_lbl', 'East Africa Tech Hub')) ?></div>
        </div>
        <div class="akar-invest-stat-card">
          <div class="akar-invest-stat-val"><?= sanitize(cms('invest_stat_3_val', '78%+')) ?></div>
          <div class="akar-invest-stat-lbl"><?= sanitize(cms('invest_stat_3_lbl', 'SaaS Software Margin')) ?></div>
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
      <!-- Nav Menu List -->
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
            04 / Live Demos (11) <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="consultation">
          <a href="<?= eurl('/#consultation') ?>" class="akar-nav-link">
            05 / Book Consultation <span class="akar-nav-arrow"><i class="fas fa-arrow-right"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="portal">
          <a href="<?= eurl('/content') ?>" class="akar-nav-link" style="color: var(--terra);">
            06 / Front-End CMS Studio <span class="akar-nav-arrow"><i class="fas fa-magic"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="portal">
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="<?= eurl('/dashboard') ?>" class="akar-nav-link">
              07 / Virtual Company Console <span class="akar-nav-arrow"><i class="fas fa-building"></i></span>
            </a>
          <?php else: ?>
            <a href="<?= eurl('/login') ?>" class="akar-nav-link">
              07 / Virtual Company / Sign In <span class="akar-nav-arrow"><i class="fas fa-arrow-right-to-bracket"></i></span>
            </a>
          <?php endif; ?>
        </li>
        <li class="akar-nav-item" data-teaser="portal">
          <a href="<?= eurl('/chat') ?>" class="akar-nav-link">
            08 / Live Company Chat <span class="akar-nav-arrow"><i class="fas fa-comments"></i></span>
          </a>
        </li>
        <li class="akar-nav-item" data-teaser="portal">
          <a href="<?= eurl('/crm/pipeline') ?>" class="akar-nav-link">
            09 / CRM Sales Pipeline <span class="akar-nav-arrow"><i class="fas fa-funnel-dollar"></i></span>
          </a>
        </li>
      </ul>

      <!-- Interactive Project Teaser Window -->
      <div class="akar-nav-teaser-box">
        <img src="https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=800&q=80" id="akarTeaserImg" alt="Project Teaser">
        <div class="akar-teaser-badge" id="akarTeaserBadge">Nexus Commercial ERP</div>
      </div>
    </div>

    <!-- Agency Details & Footer in Menu -->
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
          <a href="<?= eurl('/live-demos') ?>"><i class="fas fa-desktop"></i> View Prototypes</a>
        </div>
      </div>
      <div class="akar-col">
        <div class="akar-col-title">Social / Networks</div>
        <div class="akar-col-content">
          <a href="https://github.com/Mchungaji-tech" target="_blank">GitHub</a>
          <a href="https://linkedin.com" target="_blank">LinkedIn</a>
          <a href="https://twitter.com" target="_blank">X (Twitter)</a>
        </div>
      </div>
    </div>

    <!-- Giant Ghost Letters -->
    <div class="akar-ghost-letters"><?= sanitize(cms('hero_brand_letters', 'TEKTREND')) ?></div>
  </div>

  <!-- MAIN HERO SECTION (AKARU MONUMENTAL TYPOGRAPHY) -->
  <section class="akar-hero">
    <div class="akar-hero-meta-top">
      <span><?= sanitize(cms('hero_eyebrow', '[ SOFTWARE ARCHITECTURE & DIGITAL INNOVATION ]')) ?></span>
      <span class="akar-tag-live"><?= sanitize(cms('hero_tag_live', 'ACCEPTING Q4 / 2026 COMMISSIONS')) ?></span>
    </div>

    <!-- Monumental Interactive Letters -->
    <?php 
    $brandLetters = cms('hero_brand_letters', 'TEKTREND');
    $letterChars = preg_split('//u', $brandLetters, -1, PREG_SPLIT_NO_EMPTY);
    ?>
    <div class="akar-letters-stage" id="akarLetterStage">
      <div class="akar-letters-grid">
        <?php foreach ($letterChars as $lChar): ?>
          <span class="akar-letter"><?= sanitize($lChar) ?></span>
        <?php endforeach; ?>
      </div>

      <div class="akar-letters-tagline">
        <p><?= sanitize(cms('hero_headline', 'We are Tektrend Softwares. We architect world-class web systems, cloud dashboards, and turnkey production platforms based in Eldoret, Kenya.')) ?></p>
        <button type="button" class="akar-featured-pill" onclick="openAkaruCaseStudy(<?= htmlspecialchars(json_encode($featuredShowcase[0] ?? []), ENT_QUOTES) ?>)">
          <?= sanitize(cms('hero_featured_btn_text', '01 / Featured Project')) ?> <i class="fas fa-arrow-up-right-from-square"></i>
        </button>
      </div>
    </div>

    <div class="akar-hero-footer">
      <ul class="akar-hero-discipline">
        <li><?= sanitize(cms('hero_discipline_1', 'PHP 8 MVC Architecture')) ?></li>
        <li>•</li>
        <li><?= sanitize(cms('hero_discipline_2', 'Google Workspace ERP')) ?></li>
        <li>•</li>
        <li><?= sanitize(cms('hero_discipline_3', 'Real-time Dashboards')) ?></li>
        <li>•</li>
        <li><?= sanitize(cms('hero_discipline_4', 'Turnkey Codebases')) ?></li>
      </ul>

      <a href="#akarShowcase" class="akar-scroll-prompt">
        <span><?= sanitize(cms('hero_explore_btn_text', 'Explore Works')) ?> <i class="fas fa-arrow-down" style="margin-left: 0.6rem;"></i></span>
      </a>
    </div>
  </section>

  <!-- SELECTED ARCHITECTURES & PROTOTYPES (PINNED HORIZONTAL SHOWCASE LIKE AKARU) -->
  <section class="akar-showcase-pin-wrap" id="akarShowcase">
    <div class="akar-showcase-sticky">
      <div class="akar-showcase-header">
        <div>
          <div class="akar-section-badge"><?= sanitize(cms('showcase_badge', '[ SELECTED ARCHITECTURES & PROTOTYPES ]')) ?></div>
          <h2 class="akar-showcase-title"><?= sanitize(cms('showcase_title', 'Featured Works')) ?></h2>
        </div>
        <div class="akar-showcase-counter" id="akarShowcaseCounter">01 / <?= sprintf('%02d', count($featuredShowcase)) ?></div>
      </div>

      <!-- Horizontal Translating Reel -->
      <div class="akar-showcase-reel" id="akarShowcaseReel">
        <?php foreach ($featuredShowcase as $index => $proj): ?>
          <div class="akar-project-card" data-color="<?= $proj['color'] ?>" data-index="0<?= $index + 1 ?> / 06" onclick="openAkaruCaseStudy(<?= htmlspecialchars(json_encode($proj), ENT_QUOTES) ?>)">
            <div class="akar-card-top">
              <span class="akar-card-index">0<?= $index + 1 ?> / 06</span>
              <div class="akar-card-tags">
                <span class="akar-pill-tag"><?= sanitize($proj['category']) ?></span>
                <span class="akar-pill-tag"><?= sanitize($proj['year']) ?></span>
              </div>
            </div>

            <div class="akar-card-media">
              <img src="<?= $proj['image'] ?>" alt="<?= sanitize($proj['title']) ?>" loading="lazy">
              <div class="akar-media-overlay-badge"><i class="fas fa-code"></i> Live System</div>
            </div>

            <div class="akar-card-bottom">
              <div class="akar-card-info">
                <h3 class="akar-card-title"><?= sanitize($proj['title']) ?></h3>
                <p class="akar-card-desc"><?= sanitize($proj['short_desc']) ?></p>
              </div>
              <div class="akar-action-circle" title="Explore Case Study">
                <i class="fas fa-arrow-right"></i>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.5rem; border-top: 1px solid var(--border-subtle); flex-wrap: wrap; gap: 1.5rem;">
        <span style="font-size: 1.25rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em;">
          <i class="fas fa-arrows-left-right"></i> Scroll down to glide horizontally · Click any showcase to open interactive prototype
        </span>
        <a href="<?= eurl('/live-demos') ?>" style="font-size: 1.3rem; font-weight: 700; text-transform: uppercase; color: var(--text-primary); letter-spacing: 0.06em; display: inline-flex; align-items: center; gap: 0.8rem;">
          View All 11 Live Prototypes <i class="fas fa-arrow-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- IMMERSIVE CASE STUDY DRAWER & MULTI-DEVICE PROTOTYPE RUNNER -->
  <div class="akar-case-drawer" id="akarCaseDrawer">
    <div class="akar-drawer-nav">
      <button type="button" class="akar-drawer-back-btn" id="akarBackDrawer">
        <i class="fas fa-arrow-left"></i> Back to Overview
      </button>

      <!-- Device Viewport Switcher -->
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
          <span id="akarDrawerCategory">Enterprise Architecture</span>
          <span>Verified Production Build</span>
          <span>Turnkey Code Available</span>
        </div>
        <h2 id="akarDrawerTitle">Nexus Commercial ERP</h2>
        <p id="akarDrawerDesc">High-performance cloud management system with real-time financial tracking, multi-tenant RBAC, and executive reporting suite.</p>
      </div>

      <!-- Live Interactive Prototype Runner -->
      <div class="akar-live-frame-wrapper --desktop" id="akarFrameWrapper">
        <iframe id="akarDrawerIframe" src="about:blank" title="Interactive Prototype Runner"></iframe>
      </div>

      <!-- Technical Specifications Breakdown -->
      <div class="akar-case-specs-grid">
        <div class="akar-spec-box">
          <h4>Core Tech Stack</h4>
          <p id="akarDrawerTech">PHP 8.2 MVC, MySQL 8, Redis, Chart.js, GSAP</p>
        </div>
        <div class="akar-spec-box">
          <h4>Deliverables Included</h4>
          <p>Full Source Code, Schema SQL, CSS/JS Assets, Admin Panel</p>
        </div>
        <div class="akar-spec-box">
          <h4>Architecture Model</h4>
          <p>Clean MVC · Zero-bloat · Modular REST API</p>
        </div>
      </div>

      <div class="akar-drawer-actions">
        <div class="akar-price-callout">
          Turnkey Commercial Codebase: <strong id="akarDrawerPrice">$89</strong>
        </div>

        <div class="akar-cta-btns">
          <a href="#" id="akarDrawerExternalLink" target="_blank" class="akar-btn-main">
            <i class="fas fa-up-right-from-square"></i> Open Live Prototype in New Tab
          </a>
          <a href="<?= eurl('/live-demos') ?>" class="akar-btn-outline">
            <i class="fas fa-cubes"></i> Browse All Prototypes
          </a>
          <a href="#consultation" onclick="document.getElementById('akarCloseDrawer').click()" class="akar-btn-outline">
            <i class="fas fa-calendar-check"></i> Commission Custom Build
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- SERVICES & CAPABILITIES (AKARU ACCORDION) -->
  <section class="akar-services-section" id="services">
    <div class="akar-services-header">
      <div>
        <div class="akar-section-badge"><?= sanitize(cms('services_badge', '[ SYSTEM CAPABILITIES & EXPERTISE ]')) ?></div>
        <h2><?= sanitize(cms('services_title', 'What We Architect')) ?></h2>
      </div>
      <p><?= sanitize(cms('services_subtitle', 'We blend uncompromising engineering standards with French creative agency aesthetics to build systems that scale effortlessly.')) ?></p>
    </div>

    <div class="akar-services-list">
      <div class="akar-service-row" data-img="<?= htmlspecialchars(cms_img('service_1_image', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'), ENT_QUOTES, 'UTF-8') ?>">
        <div class="akar-service-num"><?= sanitize(cms('service_1_num', '00')) ?></div>
        <div class="akar-service-main">
          <h3><?= sanitize(cms('service_1_title', 'Enterprise Software Architecture')) ?></h3>
          <p><?= sanitize(cms('service_1_desc', 'Custom PHP 8.x MVC backends, relational schema design, role hierarchies, audit logging, and resilient micro-framework foundations.')) ?></p>
        </div>
        <div class="akar-service-action">
          <div class="akar-service-arrow"><i class="fas fa-arrow-right"></i></div>
        </div>
      </div>

      <div class="akar-service-row" data-img="<?= htmlspecialchars(cms_img('service_2_image', 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=600&q=80'), ENT_QUOTES, 'UTF-8') ?>">
        <div class="akar-service-num"><?= sanitize(cms('service_2_num', '01')) ?></div>
        <div class="akar-service-main">
          <h3><?= sanitize(cms('service_2_title', 'Google Workspace & Cloud Automation')) ?></h3>
          <p><?= sanitize(cms('service_2_desc', 'Google Apps Script enterprise ERPs, automated Sheets-to-Docs reporting, Gmail outreach pipelines, and zero-maintenance cloud workflows.')) ?></p>
        </div>
        <div class="akar-service-action">
          <div class="akar-service-arrow"><i class="fas fa-arrow-right"></i></div>
        </div>
      </div>

      <div class="akar-service-row" data-img="<?= htmlspecialchars(cms_img('service_3_image', 'https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=600&q=80'), ENT_QUOTES, 'UTF-8') ?>">
        <div class="akar-service-num"><?= sanitize(cms('service_3_num', '02')) ?></div>
        <div class="akar-service-main">
          <h3><?= sanitize(cms('service_3_title', 'Real-time Dashboards & School Systems')) ?></h3>
          <p><?= sanitize(cms('service_3_desc', 'Interactive metric suites with Chart.js, attendance tracking, automated grading pipelines, financial ledgers, and teleconferencing.')) ?></p>
        </div>
        <div class="akar-service-action">
          <div class="akar-service-arrow"><i class="fas fa-arrow-right"></i></div>
        </div>
      </div>

      <div class="akar-service-row" data-img="<?= htmlspecialchars(cms_img('service_4_image', 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80'), ENT_QUOTES, 'UTF-8') ?>">
        <div class="akar-service-num"><?= sanitize(cms('service_4_num', '03')) ?></div>
        <div class="akar-service-main">
          <h3><?= sanitize(cms('service_4_title', 'Turnkey Web Templates & Source Code')) ?></h3>
          <p><?= sanitize(cms('service_4_desc', 'Award-caliber HTML5/CSS3 prototypes, accessible typography, fluid scroll physics, and instant deployment packs for founders and businesses.')) ?></p>
        </div>
        <div class="akar-service-action">
          <div class="akar-service-arrow"><i class="fas fa-arrow-right"></i></div>
        </div>
      </div>
    </div>

    <!-- Floating Service Preview Image -->
    <div class="akar-floating-service-img" id="akarFloatingServiceImg">
      <img src="" alt="Service Preview">
    </div>
  </section>

  <!-- DARK EDITORIAL BREAK: TRUST, METRICS & ZOOM CONSULTATION -->
  <section class="akar-dark-break" id="consultation">
    <div class="akar-metrics-grid">
      <div class="akar-metric-box">
        <div class="akar-metric-val"><?= sanitize(cms('metric_1_val', '99.9%')) ?></div>
        <div class="akar-metric-label"><?= sanitize(cms('metric_1_lbl', 'System Uptime Architecture')) ?></div>
      </div>
      <div class="akar-metric-box">
        <div class="akar-metric-val"><?= sanitize(cms('metric_2_val', '< 85ms')) ?></div>
        <div class="akar-metric-label"><?= sanitize(cms('metric_2_lbl', 'Server Response Latency')) ?></div>
      </div>
      <div class="akar-metric-box">
        <div class="akar-metric-val"><?= sanitize(cms('metric_3_val', '15+')) ?></div>
        <div class="akar-metric-label"><?= sanitize(cms('metric_3_lbl', 'Enterprise Deployments')) ?></div>
      </div>
      <div class="akar-metric-box">
        <div class="akar-metric-val"><?= sanitize(cms('metric_4_val', '100%')) ?></div>
        <div class="akar-metric-label"><?= sanitize(cms('metric_4_lbl', 'Turnkey Delivery Guarantee')) ?></div>
      </div>
    </div>

    <!-- Consultation & Zoom Booking Vault -->
    <div class="akar-consult-banner">
      <div class="akar-consult-text">
        <div style="font-size: 1.2rem; font-weight: 700; color: var(--terra); letter-spacing: 0.12em; text-transform: uppercase; margin-bottom: 0.8rem;">
          <?= sanitize(cms('consult_badge', '[ FREE 45-MINUTE ARCHITECTURAL SCOPING ]')) ?>
        </div>
        <h3><?= sanitize(cms('consult_title', 'Book 1-on-1 Zoom Session')) ?></h3>
        <p><?= sanitize(cms('consult_desc', 'Discuss your enterprise software scope, cloud automation needs, or turnkey prototype deployment directly with our Principal Solutions Architect.')) ?></p>
      </div>

      <button type="button" class="akar-consult-btn" onclick="document.getElementById('consultFormModal').style.display='flex'">
        <?= sanitize(cms('consult_btn_text', 'Schedule Zoom Session')) ?> <i class="fas fa-calendar-check" style="margin-left: 0.6rem;"></i>
      </button>
    </div>
  </section>

  <!-- ZOOM CONSULTATION MODAL -->
  <div id="consultFormModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.85); backdrop-filter: blur(12px); z-index: 100005; align-items: center; justify-content: center; padding: 2rem;">
    <div style="background: #161616; border: 1px solid rgba(255,255,255,0.12); border-radius: 2.4rem; padding: 4rem; max-width: 65rem; width: 100%; position: relative; color: #ffffff;">
      <button onclick="document.getElementById('consultFormModal').style.display='none'" style="position: absolute; top: 2rem; right: 2rem; font-size: 2.4rem; color: #ffffff; cursor: pointer;">&times;</button>
      <h3 style="font-family: 'Syne', sans-serif; font-size: 2.8rem; margin-bottom: 0.8rem;"><?= sanitize(cms('consult_modal_title', 'Book Scoping Consultation')) ?></h3>
      <p style="color: rgba(255,255,255,0.65); font-size: 1.4rem; margin-bottom: 2.5rem;"><?= sanitize(cms('consult_modal_subtitle', 'Select your preferred slot to receive an instant Zoom meeting link & calendar invite.')) ?></p>

      <form action="<?= eurl('/book-consultation') ?>" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Full Name *</label>
            <input type="text" name="name" required style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="e.g. Alex Kimani">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Email Address *</label>
            <input type="email" name="email" required style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="alex@company.com">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">WhatsApp / Phone</label>
            <input type="text" name="phone" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="+254 700 000000">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Company / Organization</label>
            <input type="text" name="company" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="Acme Inc">
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Preferred Date</label>
            <input type="date" name="preferred_date" value="<?= date('Y-m-d', strtotime('+1 day')) ?>" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;">
          </div>
          <div>
            <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Preferred Time</label>
            <select name="preferred_time" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;">
              <option value="10:00 AM">10:00 AM EAT</option>
              <option value="02:00 PM">02:00 PM EAT</option>
              <option value="04:30 PM">04:30 PM EAT</option>
            </select>
          </div>
        </div>

        <div style="margin-bottom: 2rem;">
          <label style="font-size: 1.2rem; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.6); display: block; margin-bottom: 0.6rem;">Project Overview / Key Requirements</label>
          <textarea name="notes" rows="3" style="width: 100%; padding: 1.2rem; border-radius: 1rem; background: #222; border: 1px solid #333; color: #fff; font-size: 1.4rem;" placeholder="Tell us briefly about what you want to engineer..."></textarea>
        </div>

        <button type="submit" style="width: 100%; padding: 1.5rem; border-radius: 10rem; background: var(--terra); color: #0e0e0e; font-size: 1.4rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; cursor: pointer;">
          Confirm Booking & Generate Zoom Room <i class="fas fa-video" style="margin-left: 0.6rem;"></i>
        </button>
      </form>
    </div>
  </div>

  <!-- AKARU FOOTER & MONUMENTAL LETTERMARK -->
  <footer class="akar-footer">
    <div class="akar-footer-top">
      <div class="akar-footer-brand">
        <h3><?= sanitize(cms('footer_brand_title', 'Tektrend Softwares')) ?></h3>
        <p><?= sanitize(cms('footer_tagline', 'We are Tektrend Softwares. Bespoke software architecture, digital systems, and cloud engineering based in Eldoret, Kenya.')) ?></p>
      </div>

      <div class="akar-footer-links-col">
        <h4>Navigation</h4>
        <ul>
          <li><a href="<?= eurl('/') ?>">Home</a></li>
          <li><a href="<?= eurl('/#akarShowcase') ?>">Selected Works</a></li>
          <li><a href="<?= eurl('/#services') ?>">Capabilities</a></li>
          <li><a href="<?= eurl('/live-demos') ?>">11 Live Demos</a></li>
          <li><a href="<?= eurl('/#consultation') ?>">Consultation</a></li>
        </ul>
      </div>

      <div class="akar-footer-links-col">
        <h4>Management & Studio</h4>
        <ul>
          <li><a href="<?= eurl('/content') ?>" style="color: var(--terra);"><i class="fas fa-magic" style="margin-right: 0.5rem;"></i> Edit Front-End (CMS)</a></li>
          <li><a href="<?= eurl('/dashboard') ?>"><i class="fas fa-building" style="margin-right: 0.5rem;"></i> Virtual Company Console</a></li>
          <li><a href="<?= eurl('/chat') ?>"><i class="fas fa-comments" style="margin-right: 0.5rem;"></i> Company Chat Room</a></li>
          <li><a href="<?= eurl('/invoices') ?>"><i class="fas fa-file-invoice-dollar" style="margin-right: 0.5rem;"></i> Invoices & Billings</a></li>
          <li><a href="<?= eurl('/crm/pipeline') ?>"><i class="fas fa-funnel-dollar" style="margin-right: 0.5rem;"></i> CRM Sales Pipeline</a></li>
        </ul>
      </div>

      <div class="akar-footer-links-col">
        <h4>Connect</h4>
        <ul>
          <li><a href="mailto:<?= sanitize($companyEmail) ?>"><?= sanitize($companyEmail) ?></a></li>
          <li><a href="tel:<?= sanitize($companyPhone) ?>"><?= sanitize($companyPhone) ?></a></li>
          <li><a href="https://wa.me/<?= $companyWhatsApp ?>" target="_blank">WhatsApp Direct</a></li>
        </ul>
      </div>

      <div class="akar-footer-links-col">
        <h4>Headquarters</h4>
        <p style="font-size: 1.4rem; color: var(--text-secondary); line-height: 1.6;">
          <?= nl2br(sanitize(cms('footer_headquarters', "Tektrend Softwares, Eldoret, Kenya\nGlobal Engineering Retainers"))) ?>
        </p>
      </div>
    </div>

    <!-- Giant Footer Stamped Brand Mark -->
    <div class="akar-footer-brand-mark">
      <div class="akar-big-brand"><?= sanitize(cms('footer_big_mark', 'TEKTREND')) ?></div>
    </div>

    <div class="akar-footer-bottom">
      <div>&copy; <?= date('Y') ?> <?= sanitize(cms('footer_copyright', 'Tektrend Softwares · Eldoret, Kenya · We are Tektrend Softwares')) ?></div>
      <a href="#top" class="akar-back-to-top">
        Back to Top <i class="fas fa-arrow-up"></i>
      </a>
    </div>
  </footer>

  <!-- FLOATING QUICK-ACCESS DOCK -->
  <aside class="akar-floating-dock" aria-label="Quick management actions">
    <a href="<?= eurl('/content') ?>" class="akar-dock-item --primary" title="Edit Front-End Text & Images in Real-Time">
      <i class="fas fa-magic"></i>
      <span>Edit Front-End</span>
    </a>
    <div class="akar-dock-divider"></div>
    <a href="<?= eurl('/dashboard') ?>" class="akar-dock-item" title="Virtual Company Operations Console">
      <i class="fas fa-building"></i>
      <span>Company Console</span>
    </a>
    <a href="<?= eurl('/chat') ?>" class="akar-dock-item" title="Team & Client Live Chat Room">
      <i class="fas fa-comments"></i>
      <span>Live Chat</span>
    </a>
    <a href="<?= eurl('/crm/pipeline') ?>" class="akar-dock-item" title="CRM Sales Pipeline">
      <i class="fas fa-funnel-dollar"></i>
      <span>Pipeline</span>
    </a>
  </aside>

  <!-- Include Google Gemini AI Chatbot -->
  <?php require_once BASE_PATH . '/app/views/partials/ai_chat.php'; ?>

  <!-- Platform Engine JS -->
  <script src="<?= eurl('/assets/js/main.js') ?>"></script>
</body>
</html>
