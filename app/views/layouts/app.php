<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? sanitize($pageTitle) . ' · ' : '' ?>Tek Trend Virtual Enterprise</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        // Immediate theme load to prevent flicker
        (function() {
            const saved = localStorage.getItem('tektrend_theme') || 'light';
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>
    <style>
        :root, [data-theme="light"] {
            --bg-canvas: #f8fafc;
            --bg-card: #ffffff;
            --bg-card-subtle: #f1f5f9;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --primary-border: #c7d2fe;
            --accent: #d97706;
            --accent-light: #fef3c7;
            --success: #059669;
            --success-light: #ecfdf5;
            --danger: #dc2626;
            --danger-light: #fef2f2;
            --sidebar-bg: #ffffff;
            --shadow-sm: 0 1px 3px rgba(15,23,42,0.06);
            --shadow-md: 0 4px 12px -2px rgba(15,23,42,0.06), 0 2px 6px -1px rgba(15,23,42,0.04);
            --shadow-lg: 0 10px 25px -5px rgba(15,23,42,0.08), 0 8px 10px -6px rgba(15,23,42,0.04);
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 18px;
        }

        [data-theme="dark"] {
            --bg-canvas: #0b0f17;
            --bg-card: #111827;
            --bg-card-subtle: #1f2937;
            --border: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(214, 194, 157, 0.3);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-light: #64748b;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --primary-light: rgba(99, 102, 241, 0.18);
            --primary-border: rgba(99, 102, 241, 0.35);
            --accent: #d6c29d;
            --accent-light: rgba(214, 194, 157, 0.15);
            --success: #10b981;
            --success-light: rgba(16, 185, 129, 0.15);
            --danger: #ef4444;
            --danger-light: rgba(239, 68, 68, 0.15);
            --sidebar-bg: #0f172a;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.5);
            --shadow-md: 0 4px 14px rgba(0,0,0,0.6);
            --shadow-lg: 0 12px 30px rgba(0,0,0,0.8);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-canvas);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.5;
            font-size: 0.92rem;
            transition: background 0.3s ease, color 0.3s ease;
        }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg-card-subtle); }
        ::-webkit-scrollbar-thumb { background: var(--border-hover); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        .dashboard-container { display: flex; min-height: 100vh; position: relative; }

        /* Sidebar Styles */
        .sidebar {
            width: 270px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            padding: 1.5rem 1.2rem;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
            overflow-y: auto;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
            margin-bottom: 1.25rem;
            text-decoration: none;
        }
        .brand-icon {
            width: 40px; height: 40px;
            border-radius: var(--radius-md);
            background: linear-gradient(135deg, var(--primary), #818cf8);
            color: #ffffff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(79,70,229,0.25);
        }
        .brand-text h2 { font-size: 1.15rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.02em; }
        .brand-text span { color: var(--primary); }
        .brand-text p { font-size: 0.7rem; color: var(--text-muted); font-weight: 500; }

        .sidebar-nav { display: flex; flex-direction: column; gap: 0.15rem; flex: 1; }
        .nav-group-title {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-light);
            margin: 1.1rem 0.5rem 0.35rem;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.55rem 0.85rem;
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .nav-link i { width: 20px; font-size: 0.95rem; color: var(--text-light); transition: color 0.2s; }
        .nav-link:hover { background: var(--bg-card-subtle); color: var(--primary); }
        .nav-link:hover i { color: var(--primary); }
        .nav-link.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 700;
            border: 1px solid var(--primary-border);
        }
        .nav-link.active i { color: var(--primary); }
        .nav-badge {
            margin-left: auto;
            background: var(--bg-canvas);
            border: 1px solid var(--border);
            padding: 0.15rem 0.5rem;
            border-radius: 9999px;
            font-size: 0.65rem;
            color: var(--text-muted);
            font-weight: 700;
        }
        .nav-badge.accent { background: var(--accent-light); color: var(--accent); border-color: rgba(214, 194, 157, 0.3); }

        .sidebar-footer {
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        /* Main Content */
        .main-wrapper {
            margin-left: 270px;
            flex: 1;
            padding: 1.8rem 2.2rem 3rem;
            min-height: 100vh;
            background: var(--bg-canvas);
            transition: margin-left 0.3s ease, background 0.3s ease;
        }

        /* Topbar Header */
        .topbar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.8rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--border);
        }

        .header-title h1 { font-size: 1.55rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.02em; }
        .header-title p { font-size: 0.85rem; color: var(--text-muted); margin-top: 0.15rem; }

        .header-actions { display: flex; align-items: center; gap: 0.85rem; flex-wrap: wrap; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.55rem 1.15rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            font-family: inherit;
        }
        .btn-primary { background: var(--primary); color: #ffffff; box-shadow: 0 2px 8px rgba(79,70,229,0.25); }
        .btn-primary:hover { background: var(--primary-hover); transform: translateY(-1px); }
        .btn-secondary { background: var(--bg-card-subtle); border-color: var(--border); color: var(--text-main); }
        .btn-secondary:hover { background: var(--border); }
        .btn-outline { background: var(--bg-card); border-color: var(--border); color: var(--text-main); }
        .btn-outline:hover { background: var(--bg-card-subtle); border-color: var(--border-hover); }
        .btn-whatsapp { background: #25d366; color: #ffffff; box-shadow: 0 2px 8px rgba(37,211,102,0.25); }
        .btn-whatsapp:hover { background: #20ba5a; }
        .btn-zoom { background: #2d8cff; color: #ffffff; box-shadow: 0 2px 8px rgba(45,140,255,0.25); }
        .btn-zoom:hover { background: #1a77eb; }
        .btn-danger { background: var(--danger-light); color: var(--danger); border-color: rgba(239, 68, 68, 0.3); }
        .btn-danger:hover { background: var(--danger); color: #ffffff; }

        .theme-toggle-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.9rem;
            border-radius: 9999px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-main);
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .theme-toggle-btn:hover { background: var(--bg-card-subtle); border-color: var(--primary); }

        .user-profile-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.75rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 9999px;
            box-shadow: var(--shadow-sm);
        }
        .user-avatar-wrap {
            position: relative;
            width: 34px; height: 34px;
            border-radius: 50%;
            background: var(--primary-light);
            color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.85rem;
        }
        .online-dot {
            position: absolute; bottom: -1px; right: -1px;
            width: 10px; height: 10px;
            border-radius: 50%;
            background: #10b981;
            border: 2px solid var(--bg-card);
        }
        .user-meta { display: flex; flex-direction: column; }
        .user-meta .name { font-size: 0.8rem; font-weight: 700; color: var(--text-main); }
        .user-meta .role { font-size: 0.65rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }

        .mobile-toggle {
            display: none;
            width: 38px; height: 38px;
            border-radius: var(--radius-sm);
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-main);
            font-size: 1.1rem;
            cursor: pointer;
            align-items: center; justify-content: center;
        }

        /* Generic Dashboard Card Components */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            transition: box-shadow 0.2s ease, background 0.3s ease;
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }
        .card-title { font-size: 1.05rem; font-weight: 700; color: var(--text-main); }
        .card-subtitle { font-size: 0.8rem; color: var(--text-muted); }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 1.25rem;
            margin-bottom: 1.8rem;
        }
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.35rem 1.5rem;
            box-shadow: var(--shadow-sm);
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            transition: all 0.2s ease;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        .stat-card .label { font-size: 0.78rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.04em; }
        .stat-card .value { font-size: 1.85rem; font-weight: 800; color: var(--text-main); margin: 0.25rem 0; letter-spacing: -0.02em; }
        .stat-card .footer-text { font-size: 0.75rem; color: var(--text-light); }

        /* Flash Messages */
        .flash-container { position: fixed; top: 20px; right: 20px; z-index: 10000; max-width: 420px; width: calc(100% - 40px); display: flex; flex-direction: column; gap: 0.5rem; }
        .flash-alert {
            padding: 0.9rem 1.25rem;
            border-radius: var(--radius-md);
            background: var(--bg-card);
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            color: var(--text-main);
            font-size: 0.88rem;
            font-weight: 500;
            display: flex; align-items: center; gap: 0.75rem;
            animation: slideDown 0.3s ease-out;
        }
        .flash-alert.success { border-left: 4px solid var(--success); }
        .flash-alert.error { border-left: 4px solid var(--danger); }
        .flash-alert.info { border-left: 4px solid var(--primary); }
        @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        /* Data Tables */
        .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        table.table, table.data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.88rem;
        }
        table.table th, table.data-table th {
            background: var(--bg-card-subtle);
            color: var(--text-muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.75rem 1rem;
            text-align: left;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }
        table.table th:first-child, table.data-table th:first-child { border-top-left-radius: var(--radius-sm); border-bottom-left-radius: var(--radius-sm); }
        table.table th:last-child, table.data-table th:last-child { border-top-right-radius: var(--radius-sm); border-bottom-right-radius: var(--radius-sm); }
        table.table td, table.data-table td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
            vertical-align: middle;
        }
        table.table tr:hover td, table.data-table tr:hover td { background: var(--bg-card-subtle); }

        /* Badges & Status */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .badge.active, .badge.paid, .badge.signed, .badge.closed_won, .badge.working, .badge.badge-success { background: var(--success-light); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.3); }
        .badge.pending, .badge.scheduled, .badge.proposal, .badge.draft, .badge.badge-warning { background: var(--accent-light); color: var(--accent); border: 1px solid rgba(217, 119, 6, 0.3); }
        .badge.in_progress, .badge.qualified, .badge.sent, .badge.badge-primary { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }
        .badge.inactive, .badge.overdue, .badge.cancelled, .badge.closed_lost, .badge.offline, .badge.badge-danger { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }
        .badge.badge-info { background: var(--bg-card-subtle); color: var(--text-muted); border: 1px solid var(--border); }
        .badge.badge-secondary { background: var(--bg-canvas); color: var(--text-light); border: 1px solid var(--border); }

        /* Form Controls */
        .form-row { display: flex; gap: 1.25rem; flex-wrap: wrap; margin-bottom: 0.5rem; }
        .form-group { margin-bottom: 1.15rem; }
        .form-group label { display: block; font-size: 0.82rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.35rem; }
        .form-control, select.form-control, input.form-control, textarea.form-control {
            width: 100%;
            padding: 0.65rem 0.95rem;
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text-main);
            font-size: 0.9rem;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.15); }
        textarea.form-control { min-height: 100px; resize: vertical; }

        /* Legacy & Generic Container Styles */
        .table-section, .chart-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            overflow-x: auto;
        }
        .table-section .header, .chart-card .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border);
        }
        .table-section .header h3, .chart-card .header h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .topbar .greeting h1 { font-size: 1.4rem; font-weight: 800; color: var(--text-main); }
        .topbar .greeting p { font-size: 0.85rem; color: var(--text-muted); }

        .status {
            display: inline-flex;
            align-items: center;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .status.active, .status.paid, .status.completed, .status.signed, .status.closed_won { background: var(--success-light); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.3); }
        .status.pending, .status.proposal, .status.draft, .status.in_progress, .status.medium { background: var(--accent-light); color: var(--accent); border: 1px solid rgba(217, 119, 6, 0.3); }
        .status.inactive, .status.overdue, .status.cancelled, .status.closed_lost, .status.urgent, .status.high { background: var(--danger-light); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.3); }
        .status.sent, .status.admin, .status.manager, .status.employee, .status.todo, .status.low, .status.new, .status.contacted { background: var(--primary-light); color: var(--primary); border: 1px solid var(--primary-border); }

        select, input[type="text"], input[type="email"], input[type="password"], input[type="number"], input[type="date"], input[type="datetime-local"] {
            color: var(--text-main);
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.55rem 0.85rem;
            font-family: inherit;
            font-size: 0.88rem;
        }

        /* Helpers */
        .d-flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .align-center { align-items: center; }
        .mb-4 { margin-bottom: 1.5rem; }
        .text-center { text-align: center; }
        .py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }

        /* Mobile Drawer */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); width: 270px; }
            .sidebar.mobile-open { transform: translateX(0); box-shadow: var(--shadow-lg); }
            .main-wrapper { margin-left: 0; padding: 1.25rem 1.25rem 2.5rem; }
            .mobile-toggle { display: inline-flex; }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(15,23,42,0.6);
                backdrop-filter: blur(3px);
                z-index: 99;
            }
            .sidebar-backdrop.active { display: block; }
        }
    </style>
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
        <!-- Sidebar Navigation -->
        <aside class="sidebar" id="mainSidebar">
            <a href="<?= eurl('/dashboard') ?>" class="sidebar-brand">
                <div class="brand-icon"><i class="fas fa-layer-group"></i></div>
                <div class="brand-text">
                    <h2>Tek<span>Trend</span></h2>
                    <p>Virtual Enterprise</p>
                </div>
            </a>

            <nav class="sidebar-nav">
                <div class="nav-group-title">Executive Core</div>
                <a href="<?= eurl('/dashboard') ?>" class="nav-link <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
                <a href="<?= eurl('/dashboard/work-status') ?>" class="nav-link <?= ($currentPage ?? '') === 'work-status' ? 'active' : '' ?>">
                    <i class="fas fa-user-clock"></i> Live Work Status
                </a>

                <div class="nav-group-title">Showcase & Domains</div>
                <a href="<?= eurl('/live-demos') ?>" target="_blank" class="nav-link" style="color: #60a5fa;">
                    <i class="fas fa-external-link-alt" style="color: #60a5fa;"></i> Public Live Demos <span class="nav-badge" style="background: rgba(96,165,250,0.15); color: #60a5fa;">Public</span>
                </a>
                <a href="<?= eurl('/demos') ?>" class="nav-link <?= ($currentPage ?? '') === 'demos' ? 'active' : '' ?>">
                    <i class="fas fa-globe"></i> Manage Demos & Domains <span class="nav-badge accent">Domains</span>
                </a>
                <a href="<?= eurl('/marketplace') ?>" class="nav-link <?= ($currentPage ?? '') === 'marketplace' ? 'active' : '' ?>">
                    <i class="fas fa-trophy"></i> Design Marketplace
                </a>
                <a href="<?= eurl('/marketplace/bids') ?>" class="nav-link <?= ($currentPage ?? '') === 'marketplace_bids' ? 'active' : '' ?>">
                    <i class="fas fa-gavel"></i> Client Bids
                </a>

                <div class="nav-group-title">Consultancy & Deals</div>
                <a href="<?= eurl('/consultations') ?>" class="nav-link <?= ($currentPage ?? '') === 'consultations' ? 'active' : '' ?>">
                    <i class="fas fa-video"></i> Consultations & Zoom <span class="nav-badge accent"><i class="fas fa-video"></i> Live</span>
                </a>
                <a href="<?= eurl('/leads/pipeline') ?>" class="nav-link <?= ($currentPage ?? '') === 'pipeline' ? 'active' : '' ?>">
                    <i class="fas fa-columns"></i> Sales Pipeline <span class="nav-badge">Kanban</span>
                </a>
                <a href="<?= eurl('/leads') ?>" class="nav-link <?= ($currentPage ?? '') === 'leads' ? 'active' : '' ?>">
                    <i class="fas fa-bullseye"></i> Leads & CRM
                </a>

                <div class="nav-group-title">Contracts & Clients</div>
                <a href="<?= eurl('/contracts') ?>" class="nav-link <?= ($currentPage ?? '') === 'contracts' ? 'active' : '' ?>">
                    <i class="fas fa-file-contract"></i> Contracts
                </a>
                <a href="<?= eurl('/customers') ?>" class="nav-link <?= ($currentPage ?? '') === 'customers' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Customers
                </a>
                <a href="<?= eurl('/invoices') ?>" class="nav-link <?= ($currentPage ?? '') === 'invoices' ? 'active' : '' ?>">
                    <i class="fas fa-file-invoice-dollar"></i> Invoices
                </a>

                <div class="nav-group-title">Financial Control</div>
                <a href="<?= eurl('/finances') ?>" class="nav-link <?= ($currentPage ?? '') === 'finances' ? 'active' : '' ?>">
                    <i class="fas fa-wallet"></i> Transactions
                </a>
                <a href="<?= eurl('/budgets') ?>" class="nav-link <?= ($currentPage ?? '') === 'budgets' ? 'active' : '' ?>">
                    <i class="fas fa-chart-line"></i> Budgets
                </a>
                <a href="<?= eurl('/taxes') ?>" class="nav-link <?= ($currentPage ?? '') === 'taxes' ? 'active' : '' ?>">
                    <i class="fas fa-calculator"></i> Tax Rates
                </a>

                <div class="nav-group-title">Productivity & Office</div>
                <a href="<?= eurl('/chat') ?>" class="nav-link <?= ($currentPage ?? '') === 'chat' ? 'active' : '' ?>">
                    <i class="fas fa-comments"></i> Virtual Office Chat
                </a>
                <a href="<?= eurl('/meetings') ?>" class="nav-link <?= ($currentPage ?? '') === 'meetings' ? 'active' : '' ?>">
                    <i class="fas fa-video"></i> Video Meetings
                </a>
                <a href="<?= eurl('/tasks') ?>" class="nav-link <?= ($currentPage ?? '') === 'tasks' ? 'active' : '' ?>">
                    <i class="fas fa-tasks"></i> Tasks
                </a>
                <a href="<?= eurl('/events') ?>" class="nav-link <?= ($currentPage ?? '') === 'events' ? 'active' : '' ?>">
                    <i class="fas fa-calendar-alt"></i> Events & Calendar
                </a>
                <a href="<?= eurl('/timetable') ?>" class="nav-link <?= ($currentPage ?? '') === 'timetable' ? 'active' : '' ?>">
                    <i class="fas fa-clock"></i> Timetable
                </a>
                <a href="<?= eurl('/emails') ?>" class="nav-link <?= ($currentPage ?? '') === 'emails' ? 'active' : '' ?>">
                    <i class="fas fa-envelope"></i> Email Marketing
                </a>

                <div class="nav-group-title">Administration</div>
                <a href="<?= eurl('/employees') ?>" class="nav-link <?= ($currentPage ?? '') === 'employees' ? 'active' : '' ?>">
                    <i class="fas fa-user-tie"></i> Employees
                </a>
                <a href="<?= eurl('/departments') ?>" class="nav-link <?= ($currentPage ?? '') === 'departments' ? 'active' : '' ?>">
                    <i class="fas fa-building"></i> Departments
                </a>
                <a href="<?= eurl('/content') ?>" class="nav-link <?= ($currentPage ?? '') === 'content' ? 'active' : '' ?>">
                    <i class="fas fa-edit"></i> CMS Content
                </a>
                <a href="<?= eurl('/users') ?>" class="nav-link <?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>">
                    <i class="fas fa-user-shield"></i> System Users
                </a>
                <a href="<?= eurl('/settings') ?>" class="nav-link <?= ($currentPage ?? '') === 'settings' ? 'active' : '' ?>">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="<?= eurl('/') ?>" target="_blank" class="nav-link" style="color: var(--primary);">
                    <i class="fas fa-external-link-alt" style="color: var(--primary);"></i> Visit Public Site
                </a>
                <a href="<?= eurl('/logout') ?>" class="nav-link" style="color: var(--danger);">
                    <i class="fas fa-sign-out-alt" style="color: var(--danger);"></i> Sign Out
                </a>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <main class="main-wrapper">
            <header class="topbar-header">
                <div style="display: flex; align-items: center; gap: 0.85rem;">
                    <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                    <div class="header-title">
                        <h1><?= sanitize($pageTitle ?? 'Enterprise Dashboard') ?></h1>
                        <p><?= date('l, F j, Y') ?> · Tek Trend Enterprise Operations</p>
                    </div>
                </div>

                <div class="header-actions">
                    <!-- Light / Dark Theme Switcher Button -->
                    <button id="themeToggleBtn" onclick="toggleTheme()" class="theme-toggle-btn" title="Toggle Light / Dark Theme">
                        <i id="themeIcon" class="fas fa-moon"></i>
                        <span id="themeLabel">Dark Mode</span>
                    </button>

                    <a href="https://wa.me/254707246273?text=Hello%20Tek%20Trend%20Consulting%20Team" target="_blank" class="btn btn-whatsapp">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                    <a href="<?= eurl('/consultations') ?>" class="btn btn-zoom">
                        <i class="fas fa-video"></i> Zoom Sessions
                    </a>
                    <div class="user-profile-menu">
                        <div class="user-avatar-wrap">
                            <?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)) ?>
                            <span class="online-dot"></span>
                        </div>
                        <div class="user-meta">
                            <span class="name"><?= sanitize($_SESSION['user_name'] ?? 'Admin') ?></span>
                            <span class="role"><?= sanitize($_SESSION['user_role'] ?? 'Executive') ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Page View Content -->
            <?= $content ?? '' ?>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sb = document.getElementById('mainSidebar');
            const bd = document.getElementById('sidebarBackdrop');
            sb.classList.toggle('mobile-open');
            bd.classList.toggle('active');
        }

        // Theme Toggle Functionality with Persistence
        function updateThemeUI(theme) {
            const icon = document.getElementById('themeIcon');
            const label = document.getElementById('themeLabel');
            if (!icon || !label) return;
            
            if (theme === 'dark') {
                icon.className = 'fas fa-sun';
                icon.style.color = '#fbbf24';
                label.innerText = 'Light Mode';
            } else {
                icon.className = 'fas fa-moon';
                icon.style.color = '#6366f1';
                label.innerText = 'Dark Mode';
            }
        }

        function toggleTheme() {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('tektrend_theme', next);
            updateThemeUI(next);
        }

        // Init Theme UI on load
        document.addEventListener('DOMContentLoaded', () => {
            const current = document.documentElement.getAttribute('data-theme') || 'light';
            updateThemeUI(current);
        });

        // Auto-fade flash alerts
        setTimeout(() => {
            const alerts = document.querySelectorAll('.flash-alert');
            alerts.forEach(a => {
                a.style.transition = 'all 0.4s ease';
                a.style.opacity = '0';
                a.style.transform = 'translateY(-10px)';
                setTimeout(() => a.remove(), 400);
            });
        }, 5000);
    </script>
</body>
</html>
