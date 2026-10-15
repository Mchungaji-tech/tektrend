<?php
$posts = $posts ?? [];
$page = $page ?? 1;
$perPage = $perPage ?? 9;
$settings = $settings ?? [];
$companyEmail = $settings['company_email'] ?? 'tektrend.softwares@gmail.com';
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyAddress = $settings['company_address'] ?? 'Tektrend Softwares, Eldoret, Kenya';

$metaTitle = 'Software Engineering Insights & Guides · Tektrend Softwares Eldoret, Kenya';
$metaDescription = 'Practical guides on custom software architecture, PHP 8 MVC systems, Google Workspace automation, cloud dashboards and enterprise development costs in Kenya.';

$perPage = $perPage ?: 9;
$total = count($posts);
$offset = ($page - 1) * $perPage;
$visible = array_slice($posts, $offset, $perPage);
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="author" content="Tektrend Softwares">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
  <link rel="canonical" href="<?= eurl('/blog') ?>">
  <meta name="theme-color" content="#0e0e0e">

  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
  <link rel="apple-touch-icon" href="<?= eurl('/favicon.svg') ?>">

  <meta property="og:type" content="website">
  <meta property="og:site_name" content="Tektrend Softwares">
  <meta property="og:url" content="<?= eurl('/blog') ?>">
  <meta property="og:title" content="<?= htmlspecialchars($metaTitle, ENT_QUOTES, 'UTF-8') ?>">
  <meta property="og:description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">

  <script>
    (function() {
      document.documentElement.setAttribute('data-theme', localStorage.getItem('tektrend_theme') || 'dark');
      document.documentElement.setAttribute('data-palette', localStorage.getItem('tektrend_palette') || 'terracotta');
    })();
  </script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= eurl('/assets/css/main.css') ?>">

  <?php
  // JSON-LD: CollectionPage + BreadcrumbList + ItemList
  $ldBase = rtrim(url('/'), '/');
  $ldItems = [];
  foreach ($posts as $p) {
      $ldItems[] = [
          '@type' => 'ListItem',
          'position' => count($ldItems) + 1,
          'url' => $ldBase . '/blog/' . $p['slug'],
          'name' => $p['title'],
      ];
  }
  $ldDoc = [
      '@context' => 'https://schema.org',
      '@graph' => [
          ['@type' => 'WebSite', '@id' => $ldBase . '/#website', 'url' => $ldBase . '/', 'name' => 'Tektrend Softwares'],
          ['@type' => 'CollectionPage', '@id' => $ldBase . '/blog', 'url' => $ldBase . '/blog',
              'name' => 'Software Engineering Insights', 'isPartOf' => ['@id' => $ldBase . '/#website'],
              'publisher' => ['@type' => 'Organization', 'name' => 'Tektrend Softwares']],
          ['@type' => 'BreadcrumbList', 'itemListElement' => [
              ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $ldBase . '/'],
              ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $ldBase . '/blog'],
          ]],
          ['@type' => 'ItemList', 'itemListElement' => $ldItems],
      ],
  ];
  echo '<script type="application/ld+json">' . json_encode($ldDoc, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
  ?>

  <style>
    .blog-hero { padding: clamp(8rem, 16vh, 14rem) 0 3rem; }
    .blog-hero h1 { font-family: 'Syne', sans-serif; font-size: clamp(3.4rem, 6vw, 6.6rem); font-weight: 900; letter-spacing: -0.03em; line-height: 1.05; margin-bottom: 1.6rem; }
    .blog-hero p { font-size: 1.7rem; color: var(--text-secondary); max-width: 78rem; line-height: 1.6; }
    .blog-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 2.6rem; padding-bottom: 6rem; }
    .blog-card { display: flex; flex-direction: column; border-radius: 2.2rem; background: var(--bg-card); border: 1px solid var(--border-subtle); overflow: hidden; text-decoration: none; color: inherit; transition: transform .2s ease, background .2s ease; }
    .blog-card:hover { transform: translateY(-4px); background: var(--bg-card-hover); }
    .blog-card .thumb { width: 100%; aspect-ratio: 16 / 9; object-fit: cover; background: #111; }
    .blog-card .body { padding: 2.2rem; display: flex; flex-direction: column; gap: 1.1rem; flex: 1; }
    .blog-card .cat { font-size: 1.1rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--terra); }
    .blog-card h2 { font-family: 'Syne', sans-serif; font-size: 2.2rem; font-weight: 800; line-height: 1.2; margin: 0; }
    .blog-card .exc { font-size: 1.4rem; color: var(--text-secondary); line-height: 1.55; }
    .blog-card .meta { margin-top: auto; font-size: 1.2rem; color: var(--text-muted); }
    .blog-empty { padding: 4rem 0 8rem; font-size: 1.6rem; color: var(--text-secondary); }
    .blog-pager { display: flex; gap: 1rem; justify-content: center; padding-bottom: 8rem; flex-wrap: wrap; }
    .blog-pager a, .blog-pager span { padding: 1rem 1.8rem; border-radius: 10rem; border: 1px solid var(--border-subtle); text-decoration: none; color: var(--text-primary); font-weight: 700; font-size: 1.3rem; }
    .blog-pager .active { background: var(--terra); color: #0e0e0e; border-color: transparent; }
  </style>
</head>
<body id="top">
  <header class="akar-header">
    <div class="akar-header-left">
      <a href="<?= eurl('/') ?>" class="akar-brand-logo">Tektrend<span class="akar-dot"></span></a>
    </div>
    <div class="akar-header-right">
      <a href="<?= eurl('/live-demos') ?>" class="akar-login-pill" title="Live Demos"><i class="fas fa-cubes"></i> <span class="akar-login-label">Live Demos</span></a>
      <a href="<?= eurl('/#consultation') ?>" class="akar-invest-pill" title="Book Consultation"><i class="fas fa-calendar-check"></i> <span class="akar-invest-label">Book Consultation</span></a>
      <a href="<?= eurl(isset($_SESSION['user_id']) ? '/dashboard' : '/login') ?>" class="akar-theme-pill" title="Sign In"><i class="fas fa-arrow-right-to-bracket"></i></a>
    </div>
  </header>

  <main class="container">
    <section class="blog-hero">
      <div class="akar-section-badge">[ ENGINEERING JOURNAL &amp; GUIDES ]</div>
      <h1>Insights from the Tektrend Studio</h1>
      <p>Practical, no-fluff guides on custom software architecture, PHP 8 systems, Google Workspace automation and how to scope and budget enterprise builds in Kenya.</p>
    </section>

    <section class="blog-grid">
      <?php if (empty($visible)): ?>
        <p class="blog-empty">No articles published yet — check back soon.</p>
      <?php else: ?>
        <?php foreach ($visible as $p): ?>
          <a class="blog-card" href="<?= eurl('/blog/' . $p['slug']) ?>">
            <?php if (!empty($p['cover_image'])): ?>
              <img class="thumb" src="<?= htmlspecialchars($p['cover_image'], ENT_QUOTES) ?>" alt="<?= sanitize($p['title']) ?>" loading="lazy">
            <?php endif; ?>
            <div class="body">
              <span class="cat"><?= sanitize($p['category']) ?></span>
              <h2><?= sanitize($p['title']) ?></h2>
              <p class="exc"><?= sanitize($p['excerpt']) ?></p>
              <span class="meta"><i class="far fa-calendar"></i> <?= formatDate($p['published_at']) ?> · <?= sanitize($p['author_name']) ?></span>
            </div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </section>

    <?php $totalPages = (int)ceil($total / $perPage); if ($totalPages > 1): ?>
      <nav class="blog-pager" aria-label="Pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?><span class="active"><?= $i ?></span>
          <?php else: ?><a href="<?= eurl('/blog?page=' . $i) ?>"><?= $i ?></a><?php endif; ?>
        <?php endfor; ?>
      </nav>
    <?php endif; ?>
  </main>

  <footer class="akar-footer">
    <div class="akar-footer-top">
      <div class="akar-footer-brand">
        <h3>Tektrend Softwares</h3>
        <p>We are Tektrend Softwares. Bespoke software architecture, digital systems, and cloud engineering based in Eldoret, Kenya.</p>
      </div>
      <div class="akar-footer-links-col">
        <h4>Explore</h4>
        <ul>
          <li><a href="<?= eurl('/') ?>">Main Agency</a></li>
          <li><a href="<?= eurl('/live-demos') ?>">Live Demos</a></li>
          <li><a href="<?= eurl('/blog') ?>">Blog</a></li>
          <li><a href="<?= eurl('/#consultation') ?>">Book Consultation</a></li>
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
    </div>
    <div class="akar-footer-bottom">
      <div>&copy; <?= date('Y') ?> Tektrend Softwares · Eldoret, Kenya</div>
      <a href="#top" class="akar-back-to-top">Back to Top <i class="fas fa-arrow-up"></i></a>
    </div>
  </footer>

  <script src="<?= eurl('/assets/js/main.js') ?>"></script>
</body>
</html>
