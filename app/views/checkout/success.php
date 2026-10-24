<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Successful · Order Verified · Tektrend Softwares</title>
  <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Syne:wght@700;800;900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="<?= eurl('/assets/css/main.css') ?>">

  <style>
    :root {
      --bg-page: #0a0a0a;
      --bg-card: rgba(22, 22, 22, 0.85);
      --border-subtle: rgba(255, 255, 255, 0.08);
      --terra: #e49366;
      --emerald: #10b981;
      --blue: #3b82f6;
    }
    body {
      background: var(--bg-page);
      color: #ffffff;
      font-family: 'Plus Jakarta Sans', sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      position: relative;
      overflow-x: hidden;
    }
    .ambient-glow {
      position: absolute;
      width: 50vw;
      height: 50vw;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(16, 185, 129, 0.12) 0%, rgba(228, 147, 102, 0.08) 50%, transparent 70%);
      top: -15vw;
      right: -10vw;
      pointer-events: none;
      z-index: 0;
    }
    .ambient-glow-2 {
      position: absolute;
      width: 45vw;
      height: 45vw;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
      bottom: -10vw;
      left: -10vw;
      pointer-events: none;
      z-index: 0;
    }
    .order-container {
      position: relative;
      z-index: 1;
      max-width: 800px;
      width: 90%;
      margin: 4rem auto;
      background: var(--bg-card);
      border: 1px solid var(--border-subtle);
      border-radius: 2.4rem;
      padding: clamp(2.5rem, 5vw, 4.5rem);
      backdrop-filter: blur(20px);
      box-shadow: 0 30px 80px rgba(0,0,0,0.6);
      text-align: center;
    }
    .success-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 8rem;
      height: 8rem;
      border-radius: 50%;
      background: rgba(16, 185, 129, 0.15);
      border: 2px solid rgba(16, 185, 129, 0.35);
      color: var(--emerald);
      font-size: 3.4rem;
      margin-bottom: 2.5rem;
      animation: pulseGlow 2.5s infinite;
    }
    @keyframes pulseGlow {
      0%, 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4); }
      50% { box-shadow: 0 0 30px 10px rgba(16, 185, 129, 0.2); }
    }
    .order-title {
      font-family: 'Syne', sans-serif;
      font-size: clamp(2.8rem, 4vw, 3.8rem);
      font-weight: 800;
      letter-spacing: -0.02em;
      margin-bottom: 1rem;
      background: linear-gradient(135deg, #ffffff 40%, rgba(255,255,255,0.7) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .receipt-box {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 1.6rem;
      padding: 2.2rem;
      margin: 3rem 0;
      text-align: left;
    }
    .receipt-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0.9rem 0;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      font-size: 1.4rem;
    }
    .receipt-row:last-child {
      border-bottom: none;
      padding-bottom: 0;
    }
    .receipt-label {
      color: rgba(255, 255, 255, 0.6);
    }
    .receipt-value {
      font-weight: 700;
      color: #ffffff;
      font-family: 'Plus Jakarta Sans', monospace;
    }
    .btn-download-zip {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 1.2rem;
      width: 100%;
      background: linear-gradient(135deg, var(--terra) 0%, #d47a4a 100%);
      color: #000000;
      font-family: 'Syne', sans-serif;
      font-size: 1.8rem;
      font-weight: 800;
      padding: 1.8rem 3rem;
      border-radius: 1.4rem;
      text-decoration: none;
      box-shadow: 0 10px 30px rgba(228, 147, 102, 0.35);
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      margin-bottom: 1.5rem;
    }
    .btn-download-zip:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(228, 147, 102, 0.5);
      color: #000000;
    }
    .btn-portal-access {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.8rem;
      width: 100%;
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.15);
      color: #ffffff;
      font-size: 1.4rem;
      font-weight: 600;
      padding: 1.4rem 2.4rem;
      border-radius: 1.2rem;
      text-decoration: none;
      transition: all 0.2s ease;
    }
    .btn-portal-access:hover {
      background: rgba(255,255,255,0.1);
      border-color: rgba(255,255,255,0.3);
      color: #ffffff;
    }
  </style>
</head>
<body>
  <div class="ambient-glow"></div>
  <div class="ambient-glow-2"></div>

  <div class="order-container">
    <div class="success-badge">
      <i class="fas fa-check"></i>
    </div>

    <h1 class="order-title">Payment Verified &amp; Order Complete</h1>
    <p style="font-size: 1.6rem; color: rgba(255,255,255,0.7); line-height: 1.6; max-width: 580px; margin: 0 auto;">
      Thank you, <strong><?= sanitize($order['customer_name']) ?></strong>! Your payment has been confirmed via Paystack. Your turnkey source code package is ready for immediate deployment.
    </p>

    <!-- RECEIPT -->
    <div class="receipt-box">
      <div class="receipt-row">
        <span class="receipt-label">Software Package:</span>
        <span class="receipt-value" style="color: var(--terra);"><?= sanitize($order['item_title']) ?></span>
      </div>
      <div class="receipt-row">
        <span class="receipt-label">Paystack Reference:</span>
        <span class="receipt-value" style="font-size: 1.25rem;"><?= sanitize($order['paystack_reference']) ?></span>
      </div>
      <div class="receipt-row">
        <span class="receipt-label">Amount Paid:</span>
        <span class="receipt-value"><?= sanitize($order['currency']) ?> <?= number_format($order['amount'], 2) ?></span>
      </div>
      <div class="receipt-row">
        <span class="receipt-label">Licensed Email:</span>
        <span class="receipt-value"><?= sanitize($order['customer_email']) ?></span>
      </div>
      <div class="receipt-row">
        <span class="receipt-label">License Status:</span>
        <span class="receipt-value" style="color: var(--emerald);"><i class="fas fa-circle-check"></i> Commercial Lifetime License Active</span>
      </div>
    </div>

    <!-- ACTION BUTTONS -->
    <a href="<?= eurl('/checkout/download/' . $order['download_token']) ?>" class="btn-download-zip" id="btnDownloadCode">
      <i class="fas fa-file-zipper"></i> Download Full Source Code Package (.ZIP)
    </a>

    <a href="<?= eurl('/portal') ?>" class="btn-portal-access">
      <i class="fas fa-user-circle"></i> Access Buyer Client Portal &amp; All Downloads
    </a>

    <!-- SETUP INSTRUCTIONS -->
    <div style="margin-top: 3.5rem; text-align: left; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06); border-radius: 1.6rem; padding: 2rem; font-size: 1.35rem; color: rgba(255,255,255,0.65); line-height: 1.7;">
      <h4 style="color: #fff; font-size: 1.5rem; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.6rem;">
        <i class="fas fa-circle-info" style="color: var(--blue);"></i> What happens next?
      </h4>
      <p style="margin: 0 0 0.8rem;">
        1. Click <strong>Download Full Source Code Package</strong> to receive your complete code repository, UI assets, and commercial license certificate.
      </p>
      <p style="margin: 0 0 0.8rem;">
        2. A client account has been prepared for <strong><?= sanitize($order['customer_email']) ?></strong>. You can sign in anytime via Google or your email to access your invoices, scope agreements, and redownload your code packages.
      </p>
      <p style="margin: 0;">
        Need custom engineering or live server deployment? Our architects are ready at <a href="mailto:tektrend.softwares@gmail.com" style="color: var(--terra); text-decoration: underline;">tektrend.softwares@gmail.com</a> or WhatsApp <strong>+254 707 246 273</strong>.
      </p>
    </div>
  </div>
</body>
</html>
