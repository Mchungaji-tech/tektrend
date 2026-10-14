<?php
$post     = $post ?? [];
$related  = $related ?? [];
$settings = $settings ?? [];
$companyEmail    = $settings['company_email'] ?? 'tektrend.softwares@gmail.com';
$companyPhone    = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '2547246273';

$pageTitleForSeo   = ($post['meta_title'] ?? $post['title']) . ' · Tektrend Softwares';
$pageDescForSeo    = $post['meta_description'] ?? mb_substr(strip_tags($post['excerpt'] ?? ''), 0, 160);
$pageUrl           = url('/blog/' . $post['slug']);
$pageImage         = $post['cover_image'] ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitleForSeo, ENT_QUOTES, 'UTF-8') ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescForSeo, ENT_QUOTES, 'UTF-8') ?>">
  <meta name="author" content="<?= sanitize($post['author_name']) ?>">
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1">
  <link rel="canonical" href="<?= eurl('/blog/' . $post['slug']) ?>">
  <meta name="theme-color" content="#0e0e0e">

  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
  <link rel="apple-touch-icon" href="<?= eurl('/favicon.svg') ?>">

  <meta property="og:type" content="article">
  <meta property="og:site_name" content="Tektrend Softwares">
  <meta property="og:url" content="<?= htmlspecialchars($pageUrl, ENT_QUOTES) ?>">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitleForSeo, ENT_QUOTES) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($pageDescForSeo, ENT_QUOTES) ?>">
  <?php if ($pageImage): ?><meta property="og:image" content="<?= htmlspecialchars($pageImage, ENT_QUOTES) ?>"><?php endif; ?>
  <meta property="article:published_time" content="<?= date('c', strtotime($post['published_at'])) ?>">
  <meta property="article:section" content="<?= sanitize($post['category']) ?>">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= htmlspecialchars($pageTitleForSeo, ENT_QUOTES) ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($pageDescForSeo, ENT_QUOTES) ?>">
  <?php if ($pageImage): ?><meta name="twitter:image" content="<?= htmlspecialchars($pageImage, ENT_QUOTES) ?>"><?php endif; ?>

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
  // JSON-LD: BlogPosting/Article + BreadcrumbList
  $ldBase = rtrim(url('/'), '/');
  $ldDoc = [
      '@context' => 'https://schema.org',
      '@type' => ['Article', 'BlogPosting'],
      'headline' => $post['meta_title'] ?: $post['title'],
      'description' => $pageDescForSeo,
      'image' => $pageImage ?: null,
      'datePublished' => date('c', strtotime($post['published_at'])),
      'dateModified' => date('c', strtotime($post['updated_at'] ?: $post['published_at'])),
      'author' => ['@type' => 'Person', 'name' => $post['author_name']],
      'publisher' => ['@type' => 'Organization', 'name' => 'Tektrend Softwares', 'url' => $ldBase . '/'],
      'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $pageUrl],
      'articleSection' => $post['category'],
      'keywords' => $post['tags'],
      'url' => $pageUrl,
      'isPartOf' => ['@id' => $ldBase . '/blog'],
  ];
  $ldDoc = array_filter($ldDoc, function ($v) { return $v !== null && $v !== ''; });
  echo '<script type="application/ld+json">' . json_encode($ldDoc, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
  ?>

  <style>
    .article-wrap { max-width: 82rem; margin: 0 auto; padding: clamp(7rem, 14vh, 11rem) 0 6rem; }
    .article-cat { font-size: 1.2rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--terra); margin-bottom: 1.6rem; }
    .article-title { font-family: 'Syne', sans-serif; font-size: clamp(3rem, 5.5vw, 5.4rem); font-weight: 900; line-height: 1.08; letter-spacing: -0.02em; margin-bottom: 1.8rem; }
    .article-meta { font-size: 1.4rem; color: var(--text-muted); margin-bottom: 3rem; }
    .article-cover { width: 100%; border-radius: 2rem; margin-bottom: 3.6rem; }
    .article-body { font-size: 1.7rem; line-height: 1.75; color: var(--text-secondary); }
    .article-body h2 { font-family: 'Syne', sans-serif; font-size: 2.4rem; color: var(--text-primary); margin: 3rem 0 1.2rem; }
    .article-body h3 { font-size: 2rem; color: var(--text-primary); margin: 2.4rem 0 1rem; }
    .article-body p { margin-bottom: 1.8rem; }
    .article-body ul, .article-body ol { margin: 0 0 1.8rem 2.4rem; }
    .article-body li { margin-bottom: .8rem; }
    .article-body a { color: var(--terra); }
    .related-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 2rem; margin-top: 5rem; }
    .related-card { display: block; padding: 2rem; border-radius: 1.8rem; background: var(--bg-card); border: 1px solid var(--border-subtle); text-decoration: none; color: inherit; }
    .related-card h3 { font-family: 'Syne', sans-serif; font-size: 1.8rem; margin-bottom: .8rem; }
    .related-card span { font-size: 1.2rem; color: var(--terra); text-transform: uppercase; letter-spacing: .06em; }
  </style>
</head>
<body id="top">
  <header class="akar-header">
    <div class="akar-header-left">
      <a href="<?= eurl('/') ?>" class="akar-brand-logo">Tektrend<span class="akar-dot"></span></a>
    </div>
    <div class="akar-header-right">
      <a href="<?= eurl('/blog') ?>" class="akar-login-pill"><i class="fas fa-arrow-left"></i> <span class="akar-login-label">All Articles</span></a>
      <a href="<?= eurl('/#consultation') ?>" class="akar-invest-pill"><i class="fas fa-calendar-check"></i> <span class="akar-invest-label">Book Consultation</span></a>
    </div>
  </header>

  <main class="container">
    <article class="article-wrap">
      <div class="article-cat"><?= sanitize($post['category']) ?></div>
      <h1 class="article-title"><?= sanitize($post['title']) ?></h1>
      <div class="article-meta">
        <i class="far fa-calendar"></i> <?= formatDate($post['published_at']) ?>
        · <?= sanitize($post['author_name']) ?>
        <?php if (!empty($post['tags'])): ?> · <i class="fas fa-tags"></i> <?= sanitize($post['tags']) ?><?php endif; ?>
      </div>

      <?php if (!empty($post['cover_image'])): ?>
        <img class="article-cover" src="<?= htmlspecialchars($post['cover_image'], ENT_QUOTES) ?>" alt="<?= sanitize($post['title']) ?>">
      <?php endif; ?>

      <div class="article-body">
        <?= $post['body'] /* admin-authored HTML */ ?>
      </div>

      <div style="margin-top: 4rem; padding: 2.4rem; border-radius: 1.8rem; background: var(--bg-card); border: 1px solid var(--border-subtle);">
        <strong style="font-size: 1.6rem;">Ready to build this with us?</strong>
        <p style="font-size: 1.5rem; color: var(--text-secondary); margin: .8rem 0 1.4rem;">Book a free 45-minute Zoom architectural scoping session with the Tektrend team.</p>
        <a href="<?= eurl('/#consultation') ?>" class="akar-consult-btn" style="display:inline-flex;">Schedule Zoom Session <i class="fas fa-arrow-right" style="margin-left:.6rem;"></i></a>
      </div>

      <?php if (!empty($related)): ?>
        <h2 style="font-family:'Syne',sans-serif; font-size:2.2rem; margin-top:5rem;">Related Insights</h2>
        <div class="related-grid">
          <?php foreach ($related as $r): ?>
            <a class="related-card" href="<?= eurl('/blog/' . $r['slug']) ?>">
              <span><?= sanitize($r['category']) ?></span>
              <h3><?= sanitize($r['title']) ?></h3>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </article>
  </main>

  <footer class="akar-footer">
    <div class="akar-footer-bottom">
      <div>&copy; <?= date('Y') ?> Tektrend Softwares · Eldoret, Kenya</div>
      <a href="#top" class="akar-back-to-top">Back to Top <i class="fas fa-arrow-up"></i></a>
    </div>
  </footer>

  <script src="<?= eurl('/assets/js/main.js') ?>"></script>
</body>
</html>
