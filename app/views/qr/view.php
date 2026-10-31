<?php
/** @var string $qr_text @var string $ec @var string $title */
$shareUrl = url('/qr/png?data=' . urlencode($qr_text) . '&ec=' . urlencode($ec) . '&scale=10&margin=4&download=1');
$pageUrl  = url('/qr/view?data=' . urlencode($qr_text) . '&ec=' . urlencode($ec) . '&title=' . urlencode($title));
$pngInline = url('/qr/png?data=' . urlencode($qr_text) . '&ec=' . urlencode($ec) . '&scale=10&margin=4');
$pdfUrl   = url('/qr/pdf?data=' . urlencode($qr_text) . '&ec=' . urlencode($ec) . '&label=' . urlencode($title));
$svgUrl   = url('/qr/svg?data=' . urlencode($qr_text) . '&ec=' . urlencode($ec) . '&scale=10&margin=4');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= sanitize($title) ?> &middot; QR Code</title>
  <meta name="robots" content="noindex, follow">
  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
  <link rel="preconnect" href="https://cdnjs.cloudflare.com">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <style>
    :root { --primary:#2563eb; --ink:#0f172a; --muted:#64748b; --border:#e2e8f0; --bg:#f8fafc; }
    * { box-sizing: border-box; }
    body { margin:0; font-family:'Segoe UI',system-ui,-apple-system,sans-serif; background:var(--bg); color:var(--ink); min-height:100vh; display:flex; align-items:center; justify-content:center; padding:2rem 1rem; }
    .wrap { width:100%; max-width:460px; background:#fff; border:1px solid var(--border); border-radius:20px; box-shadow:0 20px 50px -20px rgba(15,23,42,.25); padding:2rem; text-align:center; }
    .brand { font-size:.75rem; letter-spacing:.12em; text-transform:uppercase; color:var(--muted); margin-bottom:.4rem; }
    h1 { font-size:1.25rem; margin:0 0 1.25rem; word-break:break-word; }
    .qr { display:inline-block; padding:12px; border:1px solid var(--border); border-radius:16px; background:#fff; }
    .qr img { display:block; width:260px; height:260px; image-rendering:pixelated; }
    .target { margin:1rem 0; font-size:.82rem; color:var(--muted); word-break:break-all; }
    .target a { color:var(--primary); text-decoration:none; font-weight:600; }
    .btns { display:flex; flex-wrap:wrap; gap:.5rem; justify-content:center; margin-top:1rem; }
    .btn { display:inline-flex; align-items:center; gap:.4rem; padding:.6rem 1rem; border-radius:10px; font-size:.85rem; font-weight:600; text-decoration:none; border:1px solid var(--border); color:var(--ink); background:#fff; cursor:pointer; }
    .btn.primary { background:var(--primary); color:#fff; border-color:var(--primary); }
    .link { margin-top:1rem; }
    .link input { width:100%; padding:.6rem .75rem; border:1px solid var(--border); border-radius:10px; font-size:.8rem; color:var(--muted); }
    footer { margin-top:1.5rem; font-size:.72rem; color:var(--muted); }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="brand">TekTrend &middot; QR Code</div>
    <h1><?= sanitize($title !== '' ? $title : 'Scan to open') ?></h1>
    <div class="qr"><img src="<?= sanitize($pngInline) ?>" alt="QR code"></div>
    <p class="target">
      Encodes: <a href="<?= sanitize($qr_text) ?>" target="_blank" rel="noopener noreferrer"><?= sanitize($qr_text) ?></a>
    </p>
    <div class="btns">
      <a class="btn primary" href="<?= sanitize($shareUrl) ?>" download><i class="fas fa-download"></i> PNG</a>
      <a class="btn" href="<?= sanitize($pdfUrl) ?>" download><i class="fas fa-file-pdf"></i> PDF</a>
      <a class="btn" href="<?= sanitize($svgUrl) ?>" download><i class="fas fa-file-code"></i> SVG</a>
      <button type="button" class="btn" onclick="copyLink()"><i class="fas fa-link"></i> Copy Link</button>
    </div>
    <div class="link"><input type="text" id="shareLink" readonly value="<?= sanitize($pageUrl) ?>" onclick="this.select();"></div>
    <footer>Point your phone camera at the code to open the link.</footer>
  </div>
  <script>
    function copyLink(){
      var i=document.getElementById('shareLink'); i.select();
      try{document.execCommand('copy'); }catch(e){}
      alert('Link copied to clipboard');
    }
  </script>
</body>
</html>
