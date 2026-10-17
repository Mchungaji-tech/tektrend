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
  <title>Frequently Asked Questions (FAQ) · Tektrend Softwares Eldoret</title>
  <meta name="description" content="Answers to common questions about Tektrend Softwares: turnkey software templates, completed client projects, partner program, Google sign-in, and payments.">
  <link rel="canonical" href="<?= eurl('/faq') ?>">
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
    .faq-hero {
      padding: clamp(9rem, 14vh, 14rem) 5% 4rem;
      max-width: 1100px;
      margin: 0 auto;
      text-align: center;
    }
    .faq-badge {
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
    .faq-container {
      max-width: 1000px;
      margin: 0 auto 8rem;
      padding: 0 5%;
    }
    .faq-category {
      margin-bottom: 4rem;
    }
    .faq-category-title {
      font-family: 'Syne', sans-serif;
      font-size: 2.2rem;
      font-weight: 800;
      color: #fff;
      margin: 0 0 1.8rem 0;
      display: flex;
      align-items: center;
      gap: 1rem;
    }
    .faq-category-title i {
      color: var(--terra);
      font-size: 1.8rem;
    }
    .faq-item {
      background: var(--bg-card);
      border: 1px solid var(--border-subtle);
      border-radius: 1.6rem;
      margin-bottom: 1.2rem;
      overflow: hidden;
      transition: border-color 0.3s ease, background 0.3s ease;
    }
    .faq-item:hover {
      border-color: rgba(228, 147, 102, 0.35);
    }
    .faq-item.active {
      border-color: var(--terra);
      background: rgba(20, 20, 24, 0.95);
    }
    .faq-question {
      padding: 2rem 2.4rem;
      font-size: 1.6rem;
      font-weight: 600;
      color: #fff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1.5rem;
      user-select: none;
    }
    .faq-question i.faq-chevron {
      color: var(--terra);
      transition: transform 0.3s ease;
      font-size: 1.4rem;
    }
    .faq-item.active .faq-question i.faq-chevron {
      transform: rotate(180deg);
    }
    .faq-answer {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.35s ease, padding 0.35s ease;
      padding: 0 2.4rem;
      font-size: 1.45rem;
      line-height: 1.8;
      color: rgba(255, 255, 255, 0.78);
    }
    .faq-item.active .faq-answer {
      max-height: 600px;
      padding: 0 2.4rem 2.2rem;
    }
    .faq-cta-box {
      background: linear-gradient(135deg, rgba(228, 147, 102, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%);
      border: 1px solid rgba(228, 147, 102, 0.35);
      border-radius: 2rem;
      padding: 3.5rem 3rem;
      text-align: center;
      margin-top: 5rem;
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
        <a href="<?= eurl('/faq') ?>" class="akar-nav-link --active">FAQ</a>
        <a href="<?= eurl('/privacy') ?>" class="akar-nav-link">Privacy</a>
      </nav>

      <div class="akar-nav-actions">
        <a href="<?= eurl('/login') ?>" class="akar-btn-text">Log In</a>
        <a href="<?= eurl('/#consultation') ?>" class="akar-btn-primary">Book Session</a>
      </div>
    </div>
  </header>

  <!-- HERO -->
  <section class="faq-hero">
    <span class="faq-badge">
      <i class="fas fa-circle-question"></i> Knowledge &amp; Guidance
    </span>
    <h1 style="font-family: 'Syne', sans-serif; font-size: clamp(3rem, 5vw, 5.2rem); font-weight: 900; line-height: 1.1; margin: 0 0 1.5rem 0; color: #fff;">
      Frequently Asked Questions
    </h1>
    <p style="font-size: clamp(1.4rem, 2vw, 1.8rem); color: rgba(255, 255, 255, 0.65); max-width: 720px; line-height: 1.6; margin: 0 auto;">
      Everything you need to know about our turnkey software templates, past client deployments, reseller partner program, and Google authentication.
    </p>
  </section>

  <!-- FAQ SECTIONS -->
  <main class="faq-container">

    <!-- Category 1: Client Works vs Turnkey Templates -->
    <div class="faq-category">
      <h2 class="faq-category-title"><i class="fas fa-code-branch"></i> Completed Client Projects vs. Turnkey Store</h2>

      <div class="faq-item active">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>Why are some websites marked "Completed Client Project · Not for Sale"?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            Any project showcased with an external live domain (such as client production systems, e.g. logistics or regional tech hubs) represents a proprietary system engineered exclusively for a specific client organization. These client systems are <strong>strictly NOT FOR SALE</strong>. They are featured on our portfolio to demonstrate our enterprise engineering architecture, UI craftsmanship, and technical delivery.
          </p>
          <p>
            If you wish to purchase ready-to-deploy software packages, please visit our <a href="<?= eurl('/shop') ?>" style="color:var(--terra); font-weight:600;">Turnkey Software Store</a>, which contains fully licensable prototypes and production codebases.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>What do I receive when I purchase a Turnkey Template from the Store?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            When you purchase a turnkey codebase, you receive:
          </p>
          <ul>
            <li>Complete, unencrypted source code (clean PHP MVC, modular CSS, modern JS, and relational SQL schemas).</li>
            <li>One-click database installation seeds and configuration guides.</li>
            <li>A commercial software license authorizing you to deploy the platform on your own or your clients' domain.</li>
            <li>Deployment documentation for cPanel, VPS, Ubuntu Apache/Nginx, or AWS.</li>
          </ul>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>Can Tektrend customize a purchased template for our specific business?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            Yes! Our engineering team in Eldoret can brand, customize, and extend any turnkey template to integrate with your specific ERP, custom payment gateway, or mobile application. You can request customization during checkout or by scheduling a video consultation.
          </p>
        </div>
      </div>
    </div>

    <!-- Category 2: Partner Reseller Program & Dashboard -->
    <div class="faq-category">
      <h2 class="faq-category-title"><i class="fas fa-handshake"></i> Partner Program &amp; Dashboard</h2>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>How does the Tektrend Partner Program work?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            The Tektrend Partner Program is designed for software agencies, IT consultants, and independent brokers. Approved partners receive:
          </p>
          <ul>
            <li><strong>Up to 10% – 20% revenue share</strong> on all referred bespoke software development contracts.</li>
            <li><strong>Wholesale reseller pricing</strong> on all turnkey software store items to markup and resell to their clients.</li>
            <li>A dedicated <strong>Partner Dashboard</strong> with real-time deal pipeline tracking, referral link generators, and payout history.</li>
          </ul>
          <p>
            Interested in partnering? You can submit your application at <a href="<?= eurl('/partner-apply') ?>" style="color:var(--terra); font-weight:600;">Partner Application</a>.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>How and when are partner commissions paid?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            Commissions are disbursed on the 1st and 15th of each month once your available balance reaches at least $50 (or KES equivalent). You can receive payments via:
          </p>
          <ul>
            <li><strong>M-Pesa Express:</strong> Instant automated disbursement to Kenyan mobile numbers.</li>
            <li><strong>Direct Bank Wire / EFT:</strong> Domestic or international wire for registered corporate agencies.</li>
            <li><strong>PayPal / Wise:</strong> Cross-border settlements in USD.</li>
          </ul>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>Can partners buy software templates directly for their clients?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            Yes. Inside the Partner Portal, the <strong>Reseller Store</strong> automatically calculates wholesale discounts and your partner commission margin on every template, allowing you to deploy white-labeled software for your clients with zero hassle.
          </p>
        </div>
      </div>
    </div>

    <!-- Category 3: Authentication & Security -->
    <div class="faq-category">
      <h2 class="faq-category-title"><i class="fab fa-google"></i> Authentication &amp; Google Sign-In</h2>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>How does "Sign in with Google" work on Tektrend?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            We support one-click Google OAuth 2.0 authentication. Clicking "Sign in with Google" securely verifies your identity through Google's verified servers. If you already have an account with the matching email, you are signed in instantly; if you are new, a secure client account is created without needing to create or remember a password.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>Is my data shared or sold to third parties?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            Never. We maintain strict compliance with data privacy laws and the Google API Services User Data Policy. Your data is used exclusively to deliver contracted engineering services, consultation meetings, and software licensing. Read our <a href="<?= eurl('/privacy') ?>" style="color:var(--terra); font-weight:600;">Privacy Policy</a> for complete details.
          </p>
        </div>
      </div>
    </div>

    <!-- Category 4: Consultations & Custom Projects -->
    <div class="faq-category">
      <h2 class="faq-category-title"><i class="fas fa-video"></i> Video Consultations &amp; Custom Engineering</h2>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>How do I book a video consultation with the engineering leadership?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            You can book directly from our homepage under the <strong>"Book Video Consultation"</strong> section. Select your target date, time, and service category. An automated Google Meet session link and calendar invite are issued immediately upon confirmation.
          </p>
        </div>
      </div>

      <div class="faq-item">
        <div class="faq-question" onclick="toggleFaq(this)">
          <span>Where is Tektrend Softwares located?</span>
          <i class="fas fa-chevron-down faq-chevron"></i>
        </div>
        <div class="faq-answer">
          <p>
            Our core development center and consultancy headquarters are located in Eldoret, Kenya (Tek Cyber, Roadblock, Eldoret). We work with organizations across East Africa, Europe, and North America.
          </p>
        </div>
      </div>
    </div>

    <!-- CTA Box -->
    <div class="faq-cta-box">
      <h3 style="font-family:'Syne',sans-serif; font-size:2.4rem; font-weight:800; color:#fff; margin:0 0 1rem 0;">
        Have a question not listed here?
      </h3>
      <p style="font-size:1.5rem; color:rgba(255,255,255,0.7); max-width:600px; margin:0 auto 2rem; line-height:1.6;">
        Our engineering leads and solution architects are ready to assist you directly.
      </p>
      <div style="display:flex; justify-content:center; gap:1.2rem; flex-wrap:wrap;">
        <a href="<?= eurl('/#consultation') ?>" class="akar-btn-primary" style="padding:1.4rem 3rem; font-size:1.4rem;">
          <i class="fas fa-calendar-check" style="margin-right:0.6rem;"></i> Book Video Session
        </a>
        <a href="https://wa.me/<?= $companyWhatsApp ?>" target="_blank" class="akar-btn-text" style="padding:1.4rem 3rem; font-size:1.4rem; background:rgba(255,255,255,0.06); border-radius:10rem;">
          <i class="fab fa-whatsapp" style="margin-right:0.6rem; color:#25d366;"></i> WhatsApp Direct
        </a>
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

  <script>
    function toggleFaq(element) {
      const item = element.parentElement;
      const isActive = item.classList.contains('active');
      
      // Close other open FAQs in this category
      const siblingItems = item.parentElement.querySelectorAll('.faq-item');
      siblingItems.forEach(el => el.classList.remove('active'));

      if (!isActive) {
        item.classList.add('active');
      }
    }
  </script>
</body>
</html>
