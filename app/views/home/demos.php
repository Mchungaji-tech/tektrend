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
  <title>Tek Trend · Live Demo & Project Showcase</title>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Google Fonts: Inter -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      background: #0b0d0f;
      color: #fff;
      font-family: 'Inter', sans-serif;
      overflow-x: hidden;
      padding: 2rem 1.5rem;
    }

    /* Subtle background glow */
    body::before {
      content: '';
      position: fixed;
      top: -30%;
      left: -30%;
      width: 160%;
      height: 160%;
      background: radial-gradient(circle at 30% 40%, rgba(60, 70, 100, 0.18) 0%, transparent 70%);
      z-index: -1;
      pointer-events: none;
    }

    .container {
      max-width: 1400px;
      margin: 0 auto;
    }

    /* Header */
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1.5rem;
      padding-bottom: 2rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.05);
      margin-bottom: 3.5rem;
    }

    .logo {
      font-size: 2rem;
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
      margin-right: 10px;
      color: #c7b18b;
      -webkit-text-fill-color: #c7b18b;
    }

    .header-actions {
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }
    .header-actions a {
      color: rgba(255,255,255,0.7);
      text-decoration: none;
      font-size: 0.92rem;
      transition: 0.3s;
      border-bottom: 2px solid transparent;
      padding-bottom: 4px;
    }
    .header-actions a:hover {
      color: #fff;
      border-bottom-color: #d6c29d;
    }
    .header-actions .btn-outline {
      border: 1px solid rgba(214, 194, 157, 0.25);
      padding: 0.55rem 1.6rem;
      border-radius: 60px;
      background: rgba(214, 194, 157, 0.08);
      color: #f5efe2;
      transition: 0.3s;
    }
    .header-actions .btn-outline:hover {
      background: rgba(214, 194, 157, 0.2);
      border-color: #d6c29d;
      color: #fff;
      transform: scale(1.02);
    }

    /* Intro */
    .intro {
      text-align: center;
      max-width: 800px;
      margin: 0 auto 3.5rem;
    }
    .intro h1 {
      font-size: clamp(2.4rem, 5vw, 3.8rem);
      font-weight: 800;
      letter-spacing: -0.02em;
      background: linear-gradient(to right, #f5efe2, #cfbc9a);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 0.8rem;
    }
    .intro p {
      color: rgba(255,255,255,0.65);
      font-size: 1.1rem;
      font-weight: 300;
      line-height: 1.6;
    }
    .intro .tag {
      display: inline-block;
      margin-top: 1.2rem;
      background: rgba(214, 194, 157, 0.08);
      padding: 0.35rem 2rem;
      border-radius: 60px;
      font-size: 0.75rem;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: #d6c29d;
      border: 1px solid rgba(214, 194, 157, 0.15);
    }

    /* Demo Grid */
    .demo-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 2.2rem;
      margin-top: 1rem;
    }

    .demo-card {
      background: rgba(14, 18, 24, 0.6);
      backdrop-filter: blur(8px);
      border-radius: 32px;
      padding: 2.2rem 2rem 2rem;
      border: 1px solid rgba(255, 215, 150, 0.06);
      transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
      display: flex;
      flex-direction: column;
      box-shadow: 0 15px 30px -12px rgba(0,0,0,0.6);
      position: relative;
      overflow: hidden;
    }
    .demo-card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: linear-gradient(145deg, rgba(214, 194, 157, 0.03) 0%, transparent 60%);
      pointer-events: none;
      border-radius: 32px;
    }
    .demo-card:hover {
      transform: translateY(-8px) scale(1.01);
      border-color: rgba(214, 194, 157, 0.25);
      background: rgba(20, 26, 36, 0.8);
      box-shadow: 0 30px 60px -15px rgba(0,0,0,0.8);
    }

    .demo-card .icon-wrap {
      font-size: 2.2rem;
      color: #d6c29d;
      margin-bottom: 1.2rem;
      display: inline-block;
      background: rgba(214, 194, 157, 0.08);
      width: 62px;
      height: 62px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(214, 194, 157, 0.1);
      transition: 0.3s;
    }
    .demo-card:hover .icon-wrap {
      background: rgba(214, 194, 157, 0.15);
      border-color: rgba(214, 194, 157, 0.3);
    }

    .demo-card h3 {
      font-size: 1.55rem;
      font-weight: 700;
      letter-spacing: -0.3px;
      margin-bottom: 0.4rem;
      color: #f5efe2;
    }
    .demo-card .category {
      font-size: 0.72rem;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: rgba(214, 194, 157, 0.7);
      margin-bottom: 0.8rem;
      font-weight: 600;
    }
    .demo-card p {
      color: rgba(255,255,255,0.65);
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 1.5rem;
      flex: 1;
    }

    .demo-card .tech-stack {
      display: flex;
      flex-wrap: wrap;
      gap: 0.45rem;
      margin-bottom: 1.5rem;
    }
    .demo-card .tech-stack span {
      background: rgba(214, 194, 157, 0.08);
      padding: 0.25rem 0.95rem;
      border-radius: 40px;
      font-size: 0.65rem;
      letter-spacing: 0.5px;
      color: #d6d0c4;
      border: 1px solid rgba(255,255,255,0.04);
    }

    .demo-card .demo-link {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      color: #d6c29d;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      padding: 0.6rem 1.4rem;
      border-radius: 60px;
      background: rgba(214, 194, 157, 0.08);
      border: 1px solid rgba(214, 194, 157, 0.15);
      transition: 0.3s;
      align-self: flex-start;
      margin-top: 0.4rem;
    }
    .demo-card .demo-link:hover {
      background: rgba(214, 194, 157, 0.2);
      border-color: #d6c29d;
      color: #fff;
      transform: scale(1.02);
    }
    .demo-card .demo-link i {
      font-size: 0.8rem;
      transition: 0.3s;
    }
    .demo-card .demo-link:hover i {
      transform: translateX(4px);
    }

    .award-pill {
      position: absolute;
      top: 20px;
      right: 20px;
      background: rgba(214, 194, 157, 0.12);
      border: 1px solid rgba(214, 194, 157, 0.25);
      color: #d6c29d;
      padding: 0.25rem 0.8rem;
      border-radius: 30px;
      font-size: 0.7rem;
      font-weight: 700;
    }

    .domain-tag {
      font-size: 0.72rem;
      color: rgba(255,255,255,0.4);
      margin-top: 0.4rem;
      display: flex;
      align-items: center;
      gap: 0.4rem;
    }

    /* Footer */
    .footer {
      margin-top: 5rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(255,255,255,0.05);
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 1rem;
      color: rgba(255,255,255,0.4);
      font-size: 0.88rem;
    }
    .footer a {
      color: #d6c29d;
      text-decoration: none;
      transition: 0.3s;
    }
    .footer a:hover { color: #fff; }

    @media (max-width: 700px) {
      .demo-grid { grid-template-columns: 1fr; }
      .header { flex-direction: column; align-items: flex-start; }
      .header-actions { width: 100%; flex-wrap: wrap; }
      body { padding: 1.2rem; }
    }
  </style>
</head>
<body>
  <div class="container">

    <!-- HEADER -->
    <header class="header">
      <a href="<?= eurl('/') ?>" class="logo"><i class="fas fa-code"></i> Tek Trend</a>
      <div class="header-actions">
        <a href="<?= eurl('/') ?>"><i class="fas fa-home"></i> Home</a>
        <a href="<?= eurl('/#portfolio') ?>">Services</a>
        <a href="<?= eurl('/#contact') ?>">Contact</a>
        <a href="<?= eurl('/login') ?>" class="btn-outline"><i class="fas fa-lock" style="margin-right: 6px;"></i> Portal Login</a>
        <a href="https://wa.me/<?= $companyWhatsApp ?>" target="_blank" class="btn-outline"><i class="fab fa-whatsapp" style="color: #25d366; margin-right: 6px;"></i> WhatsApp Us</a>
      </div>
    </header>

    <!-- INTRO -->
    <div class="intro">
      <h1>Live Demo Showcase</h1>
      <p>Explore our curated collection of production-grade platforms, turnkey systems, and custom web experiences hosted across client domains and internal infrastructure.</p>
      <span class="tag"><i class="fas fa-rocket"></i>  <?= count($demos) ?> Active Projects</span>
    </div>

    <!-- DEMO GRID -->
    <div class="demo-grid">
      <?php foreach ($demos as $demo): ?>
        <div class="demo-card">
          <?php if (!empty($demo['award_badge'])): ?>
            <div class="award-pill"><i class="fas fa-medal"></i> <?= sanitize($demo['award_badge']) ?></div>
          <?php endif; ?>

          <div class="icon-wrap"><i class="<?= sanitize($demo['icon'] ?? 'fas fa-laptop-code') ?>"></i></div>
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

          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <a href="<?= sanitize($demo['demo_url']) ?>" class="demo-link" target="_blank">
              Visit Live Demo <i class="fas fa-arrow-right"></i>
            </a>
            <?php if (!empty($demo['hosting_domain'])): ?>
              <span class="domain-tag"><i class="fas fa-globe"></i> <?= sanitize($demo['hosting_domain']) ?></span>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
      <span>Designed by <a href="<?= eurl('/') ?>">TekTrend Innovations</a></span>
      <span style="display: flex; gap: 1.2rem;">
        <a href="mailto:<?= sanitize($companyEmail) ?>"><i class="fas fa-envelope"></i> <?= sanitize($companyEmail) ?></a>
        <a href="tel:<?= sanitize($companyPhone) ?>"><i class="fas fa-phone-alt"></i> <?= sanitize($companyPhone) ?></a>
        <a href="<?= eurl('/login') ?>"><i class="fas fa-lock"></i> Portal Login</a>
      </span>
    </footer>

  </div>
</body>
</html>
