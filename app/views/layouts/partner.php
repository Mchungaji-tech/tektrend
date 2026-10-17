<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' · ' : '' ?>Tek Trend Partner Portal</title>
    <meta name="robots" content="noindex, nofollow, noarchive">
    <link rel="icon" type="image/svg+xml" href="<?= eurl('/favicon.svg') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        (function() {
            const saved = localStorage.getItem('tektrend_theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    <?php include VIEW_PATH . '/partials/portal_css.php'; ?>
</head>
<body>
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <div class="flash-container" id="flashContainer">
        <?php if (flash('success')): ?>
            <div class="flash-alert success"><i class="fas fa-check-circle" style="color: var(--success); font-size: 1.1rem;"></i> <?= sanitize(flash('success')) ?></div>
        <?php endif; ?>
        <?php if (flash('error')): ?>
            <div class="flash-alert error"><i class="fas fa-exclamation-circle" style="color: var(--danger); font-size: 1.1rem;"></i> <?= sanitize(flash('error')) ?></div>
        <?php endif; ?>
        <?php if (flash('info')): ?>
            <div class="flash-alert info"><i class="fas fa-info-circle" style="color: var(--primary); font-size: 1.1rem;"></i> <?= sanitize(flash('info')) ?></div>
        <?php endif; ?>
    </div>

    <div class="dashboard-container">
        <aside class="sidebar" id="mainSidebar">
            <a href="<?= eurl('/partners') ?>" class="sidebar-brand">
                <div class="brand-icon"><i class="fas fa-handshake"></i></div>
                <div class="brand-text">
                    <h2>Tek<span>Trend</span></h2>
                    <p>Partner Portal</p>
                </div>
            </a>

            <nav class="sidebar-nav">
                <div class="nav-group-title">Partnership</div>
                <a href="<?= eurl('/partners') ?>" class="nav-link <?= ($partnerPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
                <a href="<?= eurl('/partners/shop') ?>" class="nav-link <?= ($partnerPage ?? '') === 'shop' ? 'active' : '' ?>">
                    <i class="fas fa-shopping-bag"></i> Shop & Resell
                </a>
                <a href="<?= eurl('/partners/referrals') ?>" class="nav-link <?= ($partnerPage ?? '') === 'referrals' ? 'active' : '' ?>">
                    <i class="fas fa-users-rays"></i> Referrals
                </a>
                <a href="<?= eurl('/partners/payouts') ?>" class="nav-link <?= ($partnerPage ?? '') === 'payouts' ? 'active' : '' ?>">
                    <i class="fas fa-sack-dollar"></i> Payouts
                </a>
                <a href="<?= eurl('/partners/profile') ?>" class="nav-link <?= ($partnerPage ?? '') === 'profile' ? 'active' : '' ?>">
                    <i class="fas fa-user-gear"></i> Profile
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= eurl('/') ?>" target="_blank" class="nav-link" style="color: var(--primary);">
                    <i class="fas fa-external-link-alt" style="color: var(--primary);"></i> Visit Public Site
                </a>
                <a href="<?= eurl('/partners/logout') ?>" class="nav-link" style="color: var(--danger);">
                    <i class="fas fa-sign-out-alt" style="color: var(--danger);"></i> Sign Out
                </a>
            </div>
        </aside>

        <main class="main-wrapper">
            <header class="topbar-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                    <div class="header-title">
                        <h1><?= sanitize($pageTitle ?? 'Partner Portal') ?></h1>
                        <p><?= date('l, F j, Y') ?> · Tek Trend Partner Program</p>
                    </div>
                </div>

                <div class="header-actions">
                    <button id="themeToggleBtn" onclick="toggleTheme()" class="theme-toggle-btn" title="Toggle Light / Dark Theme">
                        <i id="themeIcon" class="fas fa-moon"></i>
                        <span id="themeLabel">Dark Mode</span>
                    </button>
                    <div class="user-profile-menu">
                        <div class="user-avatar-wrap"><?= strtoupper(substr($_SESSION['user_name'] ?? 'P', 0, 1)) ?></div>
                        <div class="user-meta">
                            <span class="name"><?= sanitize($_SESSION['user_name'] ?? 'Partner') ?></span>
                            <span class="role">Partner</span>
                        </div>
                    </div>
                </div>
            </header>

            <?= $content ?? '' ?>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('mainSidebar').classList.toggle('mobile-open');
            document.getElementById('sidebarBackdrop').classList.toggle('active');
        }
        function updateThemeUI(theme) {
            const icon = document.getElementById('themeIcon');
            const label = document.getElementById('themeLabel');
            if (!icon || !label) return;
            if (theme === 'dark') { icon.className = 'fas fa-sun'; icon.style.color = '#fbbf24'; label.innerText = 'Light Mode'; }
            else { icon.className = 'fas fa-moon'; icon.style.color = '#6366f1'; label.innerText = 'Dark Mode'; }
        }
        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('tektrend_theme', next);
            updateThemeUI(next);
        }
        document.addEventListener('DOMContentLoaded', () => updateThemeUI(document.documentElement.getAttribute('data-theme') || 'light'));
        setTimeout(() => {
            document.querySelectorAll('.flash-alert').forEach(a => {
                a.style.transition = 'all 0.4s ease'; a.style.opacity = '0'; a.style.transform = 'translateY(-10px)';
                setTimeout(() => a.remove(), 400);
            });
        }, 5000);
    </script>
</body>
</html>
