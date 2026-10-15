<?php
/**
 * Shared CSS for the client (/portal) and partner (/partners) layouts.
 * Mirrors the design system used by layouts/app.php but scoped to the components
 * those portals actually render (sidebar, cards, stats, tables, buttons, forms, badges, flash).
 */
?>
<style>
    :root, [data-theme="light"] {
        --bg-canvas: #f8fafc; --bg-card: #ffffff; --bg-card-subtle: #f1f5f9;
        --border: #e2e8f0; --border-hover: #cbd5e1; --text-main: #0f172a; --text-muted: #64748b; --text-light: #94a3b8;
        --primary: #4f46e5; --primary-hover: #4338ca; --primary-light: #eef2ff; --primary-border: #c7d2fe;
        --accent: #d97706; --accent-light: #fef3c7; --success: #059669; --success-light: #ecfdf5; --danger: #dc2626; --danger-light: #fef2f2;
        --sidebar-bg: #ffffff; --shadow-sm: 0 1px 3px rgba(15,23,42,0.06); --shadow-md: 0 4px 12px -2px rgba(15,23,42,0.06);
        --shadow-lg: 0 10px 25px -5px rgba(15,23,42,0.08); --radius-sm: 8px; --radius-md: 12px; --radius-lg: 18px;
    }
    [data-theme="dark"] {
        --bg-canvas: #0b0f17; --bg-card: #111827; --bg-card-subtle: #1f2937; --border: rgba(255,255,255,0.08); --border-hover: rgba(214,194,157,0.3);
        --text-main: #f8fafc; --text-muted: #94a3b8; --text-light: #64748b; --primary: #6366f1; --primary-hover: #4f46e5; --primary-light: rgba(99,102,241,0.18); --primary-border: rgba(99,102,241,0.35);
        --accent: #d6c29d; --accent-light: rgba(214,194,157,0.15); --success: #10b981; --success-light: rgba(16,185,129,0.15); --danger: #ef4444; --danger-light: rgba(239,68,68,0.15); --sidebar-bg: #0f172a;
    }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Plus Jakarta Sans', sans-serif; background: var(--bg-canvas); color: var(--text-main); overflow-x: hidden; line-height: 1.5; font-size: 0.92rem; transition: background .3s, color .3s; }
    ::-webkit-scrollbar { width: 6px; height: 6px; } ::-webkit-scrollbar-thumb { background: var(--border-hover); border-radius: 10px; }
    .dashboard-container { display: flex; min-height: 100vh; position: relative; }
    .sidebar { width: 260px; background: var(--sidebar-bg); border-right: 1px solid var(--border); padding: 1.5rem 1.2rem; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; display: flex; flex-direction: column; transition: all .3s; overflow-y: auto; }
    .sidebar-brand { display: flex; align-items: center; gap: .75rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border); margin-bottom: 1.25rem; text-decoration: none; }
    .brand-icon { width: 40px; height: 40px; border-radius: var(--radius-md); background: linear-gradient(135deg, var(--primary), #818cf8); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .brand-text h2 { font-size: 1.1rem; font-weight: 800; color: var(--text-main); } .brand-text span { color: var(--primary); }
    .brand-text p { font-size: .68rem; color: var(--text-muted); font-weight: 500; }
    .sidebar-nav { display: flex; flex-direction: column; gap: .15rem; flex: 1; }
    .nav-group-title { font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--text-light); margin: 1.1rem .5rem .35rem; }
    .nav-link { display: flex; align-items: center; gap: .75rem; padding: .55rem .85rem; border-radius: var(--radius-sm); color: var(--text-muted); text-decoration: none; font-size: .85rem; font-weight: 600; transition: all .2s; }
    .nav-link i { width: 20px; font-size: .95rem; color: var(--text-light); } .nav-link:hover { background: var(--bg-card-subtle); color: var(--primary); } .nav-link:hover i { color: var(--primary); }
    .nav-link.active { background: var(--primary-light); color: var(--primary); font-weight: 700; border: 1px solid var(--primary-border); } .nav-link.active i { color: var(--primary); }
    .sidebar-footer { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border); display: flex; flex-direction: column; gap: .35rem; }
    .main-wrapper { margin-left: 260px; flex: 1; padding: 1.8rem 2.2rem 3rem; min-height: 100vh; background: var(--bg-canvas); transition: margin-left .3s; }
    .topbar-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.8rem; padding-bottom: 1.25rem; border-bottom: 1px solid var(--border); }
    .header-title h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; } .header-title p { font-size: .82rem; color: var(--text-muted); margin-top: .15rem; }
    .header-actions { display: flex; align-items: center; gap: .85rem; flex-wrap: wrap; }
    .user-profile-menu { display: flex; align-items: center; gap: .6rem; padding: .35rem .75rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: 9999px; }
    .user-avatar-wrap { position: relative; width: 34px; height: 34px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: .85rem; }
    .user-meta .name { font-size: .8rem; font-weight: 700; } .user-meta .role { font-size: .64rem; color: var(--text-muted); text-transform: uppercase; }
    .theme-toggle-btn { display: inline-flex; align-items: center; gap: .5rem; padding: .45rem .9rem; border-radius: 9999px; background: var(--bg-card); border: 1px solid var(--border); color: var(--text-main); font-size: .8rem; font-weight: 600; cursor: pointer; }
    .mobile-toggle { display: none; width: 38px; height: 38px; border-radius: var(--radius-sm); background: var(--bg-card); border: 1px solid var(--border); color: var(--text-main); font-size: 1.1rem; cursor: pointer; align-items: center; justify-content: center; }
    .card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.5rem; box-shadow: var(--shadow-sm); margin-bottom: 1.5rem; }
    .card:hover { box-shadow: var(--shadow-md); }
    .card-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: .75rem; margin-bottom: 1.25rem; padding-bottom: .75rem; border-bottom: 1px solid var(--border); }
    .card-title { font-size: 1.05rem; font-weight: 700; } .card-subtitle { font-size: .8rem; color: var(--text-muted); }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 1.25rem; margin-bottom: 1.8rem; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 1.35rem 1.5rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; }
    .stat-icon { width: 44px; height: 44px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 1rem; }
    .stat-card .label { font-size: .76rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: .04em; }
    .stat-card .value { font-size: 1.7rem; font-weight: 800; margin: .25rem 0; letter-spacing: -.02em; }
    .flash-container { position: fixed; top: 20px; right: 20px; z-index: 10000; max-width: 420px; width: calc(100% - 40px); display: flex; flex-direction: column; gap: .5rem; }
    .flash-alert { padding: .9rem 1.25rem; border-radius: var(--radius-md); background: var(--bg-card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); font-size: .88rem; display: flex; align-items: center; gap: .75rem; animation: slideDown .3s ease-out; }
    .flash-alert.success { border-left: 4px solid var(--success); } .flash-alert.error { border-left: 4px solid var(--danger); } .flash-alert.info { border-left: 4px solid var(--primary); }
    @keyframes slideDown { from { transform: translateY(-20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .table-responsive { width: 100%; overflow-x: auto; } table.table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: .88rem; }
    table.table th { background: var(--bg-card-subtle); color: var(--text-muted); font-size: .72rem; font-weight: 700; text-transform: uppercase; letter-spacing: .05em; padding: .75rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
    table.table td { padding: .9rem 1rem; border-bottom: 1px solid var(--border); vertical-align: middle; } table.table tr:hover td { background: var(--bg-card-subtle); }
    .badge { display: inline-flex; align-items: center; gap: .35rem; padding: .2rem .65rem; border-radius: 9999px; font-size: .72rem; font-weight: 700; text-transform: uppercase; }
    .badge.paid, .badge.approved, .badge.active, .badge.closed_won, .badge.completed, .badge.signed { background: var(--success-light); color: var(--success); }
    .badge.pending, .badge.sent, .badge.draft, .badge.in_progress, .badge.partial { background: var(--accent-light); color: var(--accent); }
    .badge.overdue, .badge.cancelled, .badge.rejected, .badge.inactive { background: var(--danger-light); color: var(--danger); }
    .btn { display: inline-flex; align-items: center; justify-content: center; gap: .5rem; padding: .55rem 1.15rem; border-radius: var(--radius-sm); font-size: .85rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: all .2s; border: 1px solid transparent; font-family: inherit; }
    .btn-primary { background: var(--primary); color: #fff; } .btn-primary:hover { background: var(--primary-hover); }
    .btn-secondary { background: var(--bg-card-subtle); border-color: var(--border); color: var(--text-main); } .btn-outline { background: var(--bg-card); border-color: var(--border); color: var(--text-main); } .btn-outline:hover { background: var(--bg-card-subtle); }
    .btn-danger { background: var(--danger-light); color: var(--danger); border-color: rgba(239,68,68,0.3); }
    .form-row { display: flex; gap: 1.25rem; flex-wrap: wrap; } .form-group { margin-bottom: 1.15rem; flex: 1; } .form-group label { display: block; font-size: .82rem; font-weight: 700; margin-bottom: .35rem; }
    .form-control { width: 100%; padding: .65rem .95rem; background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-sm); color: var(--text-main); font-size: .9rem; font-family: inherit; }
    .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.15); } textarea.form-control { min-height: 100px; resize: vertical; }
    .empty-state { text-align: center; padding: 3rem 1rem; color: var(--text-muted); } .empty-state i { font-size: 2.5rem; opacity: .35; margin-bottom: .75rem; }
    .sidebar-backdrop { display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(3px); z-index: 99; }
    .sidebar-backdrop.active { display: block; }
    @media (max-width: 1024px) { .sidebar { transform: translateX(-100%); } .sidebar.mobile-open { transform: translateX(0); box-shadow: var(--shadow-lg); } .main-wrapper { margin-left: 0; padding: 1.25rem; } .mobile-toggle { display: inline-flex; } }
</style>
