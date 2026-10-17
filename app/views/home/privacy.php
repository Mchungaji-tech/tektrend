<?php
$companyPhone = $settings['company_phone'] ?? '0707246273';
$companyWhatsApp = $settings['company_whatsapp'] ?? '254707246273';
$companyEmail = $settings['company_email'] ?? 'tektrend.softwares@gmail.com';
$companyAddress = $settings['company_address'] ?? 'Tek Cyber, Roadblock, Eldoret, Kenya';
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Privacy Policy · Tektrend Softwares Eldoret</title>
  <meta name="description" content="Privacy Policy, Data Protection, Google OAuth disclosure, and Terms for Tektrend Softwares, Eldoret Kenya.">
  <link rel="canonical" href="<?= eurl('/privacy') ?>">
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
    .legal-hero {
      padding: clamp(9rem, 14vh, 14rem) 5% 4rem;
      max-width: 1100px;
      margin: 0 auto;
    }
    .legal-content {
      max-width: 1100px;
      margin: 0 auto 8rem;
      padding: 0 5%;
      font-size: 1.5rem;
      line-height: 1.8;
      color: rgba(255, 255, 255, 0.82);
    }
    .legal-card {
      background: var(--bg-card);
      border: 1px solid var(--border-subtle);
      border-radius: 2rem;
      padding: 3rem;
      margin-bottom: 2.5rem;
      backdrop-filter: blur(12px);
    }
    .legal-card h2 {
      font-family: 'Syne', sans-serif;
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      margin: 0 0 1.2rem 0;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .legal-card h2 i {
      color: var(--terra);
      font-size: 1.8rem;
    }
    .legal-card p {
      margin-bottom: 1.2rem;
    }
    .legal-card p:last-child {
      margin-bottom: 0;
    }
    .legal-card ul {
      margin: 1rem 0 1.5rem 2rem;
      padding: 0;
    }
    .legal-card li {
      margin-bottom: 0.6rem;
    }
    .legal-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      font-size: 1.1rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      color: var(--terra);
      background: rgba(228, 147, 102, 0.12);
      border: 1px solid rgba(228, 147, 102, 0.25);
      padding: 0.6rem 1.4rem;
      border-radius: 10rem;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body id="top">

  <!-- NAVIGATION -->
  <header class="akar-header">
    <div class="akar-nav-inner">
      <a href="<?= eurl('/') ?>" class="akar-logo">
        <span class="akar-logo-dot"></span>
        <span class="akar-logo-text">Tektrend</span>
      </a>

      <nav class="akar-nav-links">
        <a href="<?= eurl('/') ?>" class="akar-nav-link">Home</a>
        <a href="<?= eurl('/live-demos') ?>" class="akar-nav-link">Works &amp; Demos</a>
        <a href="<?= eurl('/shop') ?>" class="akar-nav-link">Store</a>
        <a href="<?= eurl('/faq') ?>" class="akar-nav-link">FAQ</a>
        <a href="<?= eurl('/privacy') ?>" class="akar-nav-link --active">Privacy</a>
      </nav>

      <div class="akar-nav-actions">
        <a href="<?= eurl('/login') ?>" class="akar-btn-text">Log In</a>
        <a href="<?= eurl('/#consultation') ?>" class="akar-btn-primary">Book Session</a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="legal-hero">
    <span class="legal-badge">
      <i class="fas fa-shield-halved"></i> Data Protection &amp; Governance
    </span>
    <h1 style="font-family: 'Syne', sans-serif; font-size: clamp(3rem, 5vw, 5.2rem); font-weight: 900; line-height: 1.1; margin: 0 0 1.5rem 0; color: #fff;">
      Privacy Policy &amp; Terms
    </h1>
    <p style="font-size: clamp(1.4rem, 2vw, 1.8rem); color: rgba(255, 255, 255, 0.65); max-width: 800px; line-height: 1.6; margin: 0;">
      Last updated: October 2026. Your privacy and intellectual property rights are fundamental to our engineering standards at Tektrend Softwares Eldoret.
    </p>
  </section>

  <!-- CONTENT -->
  <main class="legal-content">
    
    <!-- Section 1 -->
    <div class="legal-card">
      <h2><i class="fas fa-building"></i> 1. Who We Are</h2>
      <p>
        Tektrend Softwares ("Tektrend", "we", "our", or "us") is an enterprise digital consultancy and custom software engineering firm headquartered in Eldoret, Kenya (Tek Cyber, Roadblock, Eldoret). We architect custom web platforms, bespoke client enterprise systems, mobile applications, and verified turnkey software architectures.
      </p>
      <p>
        This Privacy Policy explains how we collect, process, protect, and handle your data across our consultancy platform, client portal, partner reseller program, video consultations, and source code store.
      </p>
    </div>

    <!-- Section 2: Google OAuth -->
    <div class="legal-card">
      <h2><i class="fab fa-google"></i> 2. Google OAuth &amp; Social Sign-In Data</h2>
      <p>
        When you choose to authenticate using <strong>"Sign in with Google"</strong> on our website, we access only the minimal basic profile information authorized by your consent:
      </p>
      <ul>
        <li><strong>Primary Email Address:</strong> Used to uniquely identify your account, send consultation bookings, deliver purchase invoices, and coordinate support.</li>
        <li><strong>Full Name (Given &amp; Family Name):</strong> Used to personalize your portal workspace and address your inquiries.</li>
        <li><strong>Profile Picture URL:</strong> Used optionally to display your avatar inside the client and partner dashboards.</li>
        <li><strong>Google Account Identifier (Sub ID):</strong> Stored securely to streamline one-click authentication without storing a third-party password.</li>
      </ul>
      <p>
        We <strong>never</strong> request access to your private Google Drive documents, emails, calendar contents, or sensitive personal data beyond identity verification. Furthermore, our use of Google user data adheres strictly to the <em>Google API Services User Data Policy</em>, including the Limited Use requirements.
      </p>
    </div>

    <!-- Section 3: Data Collection -->
    <div class="legal-card">
      <h2><i class="fas fa-database"></i> 3. Information We Collect</h2>
      <p>Depending on how you interact with Tektrend Softwares, we collect the following:</p>
      <ul>
        <li><strong>Consultation Bookings:</strong> Name, business email, phone number, company name, preferred consultation date/time, and project specifications for Google Meet sessions.</li>
        <li><strong>Turnkey Software Orders:</strong> Contact information, selected template or architecture package, licensing preference, and deployment instructions.</li>
        <li><strong>Partner Program Records:</strong> Organization name, corporate website, referral codes, commission ledger, and preferred disbursement channels (M-Pesa Express, Bank Wire, or PayPal).</li>
        <li><strong>Technical Logs:</strong> IP address, browser user-agent, session tokens, and security telemetry collected solely to detect fraud, prevent DDoS attacks, and maintain platform stability.</li>
      </ul>
    </div>

    <!-- Section 4: Completed Client Projects vs. Templates -->
    <div class="legal-card">
      <h2><i class="fas fa-code-branch"></i> 4. Intellectual Property: Client Projects vs. Turnkey Store</h2>
      <p>
        Tektrend Softwares strictly distinguishes between <strong>Featured Completed Client Works</strong> and <strong>Turnkey Software Templates</strong>:
      </p>
      <ul>
        <li><strong>Featured Client Projects (Live External Domains):</strong> Projects showcased with external domains (such as real client websites) are exclusive proprietary assets commissioned and owned by their respective organizations. <strong>These projects are strictly NOT FOR SALE</strong> and are featured solely as portfolio case studies of our engineering capability.</li>
        <li><strong>Turnkey Software Templates (Local Demonstrations):</strong> Software packages marked "For Sale" in our store are proprietary turnkey prototypes authored by Tektrend Softwares. Purchasing these grants you a commercial license to deploy, customize, and operate the codebase under our standard commercial software license.</li>
      </ul>
    </div>

    <!-- Section 5: Security & Payment -->
    <div class="legal-card">
      <h2><i class="fas fa-lock"></i> 5. Payments &amp; Financial Security</h2>
      <p>
        Financial transactions conducted via Tektrend Softwares (including M-Pesa Express, Pesapal, or Direct Bank Wire) are processed via secured, encrypted protocols. We do not store credit card CVV codes or private M-Pesa PINs on our servers. All commission payouts to registered partners are calculated transparently and logged within the partner portal.
      </p>
    </div>

    <!-- Section 6: User Rights -->
    <div class="legal-card">
      <h2><i class="fas fa-user-shield"></i> 6. Your Rights &amp; Data Control</h2>
      <p>You have full ownership of your data. You may at any time:</p>
      <ul>
        <li>Request an export copy of all personal information and transaction records linked to your account.</li>
        <li>Request immediate rectification or deletion of your account and personal identifiers.</li>
        <li>Revoke Google OAuth permissions at any time through your Google Security Account settings.</li>
        <li>Unsubscribe from optional marketing updates with a single click.</li>
      </ul>
    </div>

    <!-- Section 7: Contact -->
    <div class="legal-card">
      <h2><i class="fas fa-envelope-open-text"></i> 7. Contact Our Privacy Officer</h2>
      <p>
        If you have questions regarding this Privacy Policy, your data protection rights, or partner confidentiality, contact our executive team directly:
      </p>
      <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--border-subtle); border-radius: 1.2rem; padding: 1.5rem; margin-top: 1.5rem;">
        <p style="margin: 0 0 0.5rem 0;"><strong>Tektrend Softwares Eldoret</strong></p>
        <p style="margin: 0 0 0.5rem 0;"><i class="fas fa-map-marker-alt" style="color:var(--terra); width:20px;"></i> <?= sanitize($companyAddress) ?></p>
        <p style="margin: 0 0 0.5rem 0;"><i class="fas fa-envelope" style="color:var(--terra); width:20px;"></i> <a href="mailto:<?= sanitize($companyEmail) ?>" style="color:var(--terra);"><?= sanitize($companyEmail) ?></a></p>
        <p style="margin: 0;"><i class="fas fa-phone" style="color:var(--terra); width:20px;"></i> <a href="tel:<?= sanitize($companyPhone) ?>" style="color:var(--terra);"><?= sanitize($companyPhone) ?></a></p>
      </div>
    </div>

  </main>

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

</body>
</html>
