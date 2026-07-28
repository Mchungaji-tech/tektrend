<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' · ' : '' ?>Tek Trend Virtual Office</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0e0d0c; color: #f5f0eb; overflow-x: hidden; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #0e0d0c; }
        ::-webkit-scrollbar-thumb { background: #b8943c; border-radius: 10px; }
        .dashboard { display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: rgba(245,240,235,0.02); border-right: 1px solid rgba(245,240,235,0.03); padding: 2rem 1.5rem; position: fixed; top: 0; left: 0; bottom: 0; z-index: 100; backdrop-filter: blur(12px); display: flex; flex-direction: column; transition: 0.3s; overflow-y: auto; }
        .sidebar .logo { font-size: 1.4rem; font-weight: 900; letter-spacing: -0.5px; color: #f5f0eb; padding-bottom: 2rem; border-bottom: 1px solid rgba(245,240,235,0.03); margin-bottom: 2rem; }
        .sidebar .logo span { color: #b8943c; }
        .sidebar .nav-item { display: flex; align-items: center; gap: 1rem; padding: 0.8rem 1rem; border-radius: 12px; color: rgba(245,240,235,0.25); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: 0.3s; margin-bottom: 0.2rem; }
        .sidebar .nav-item i { width: 20px; font-size: 1rem; }
        .sidebar .nav-item:hover { background: rgba(184,148,60,0.03); color: #f5f0eb; }
        .sidebar .nav-item.active { background: rgba(184,148,60,0.06); color: #b8943c; }
        .sidebar .nav-item .badge { margin-left: auto; background: rgba(184,148,60,0.06); padding: 0.1rem 0.6rem; border-radius: 40px; font-size: 0.55rem; color: #b8943c; }
        .sidebar .nav-section { margin-bottom: 1.5rem; }
        .sidebar .nav-section-title { font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; color: rgba(245,240,235,0.1); margin-bottom: 0.5rem; padding: 0 1rem; }
        .sidebar .bottom-nav { margin-top: auto; padding-top: 1.5rem; border-top: 1px solid rgba(245,240,235,0.03); }
        .main { margin-left: 260px; flex: 1; padding: 2rem 2.5rem 3rem; min-height: 100vh; }
        .topbar { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(245,240,235,0.03); margin-bottom: 2rem; }
        .topbar .greeting h1 { font-size: 1.6rem; font-weight: 700; color: #f5f0eb; }
        .topbar .greeting p { color: rgba(245,240,235,0.2); font-size: 0.85rem; margin-top: 0.2rem; }
        .topbar .actions { display: flex; align-items: center; gap: 1.5rem; }
        .topbar .actions .date { color: rgba(245,240,235,0.15); font-size: 0.8rem; }
        .topbar .actions .notif { color: rgba(245,240,235,0.15); font-size: 1.2rem; cursor: pointer; transition: 0.3s; position: relative; }
        .topbar .actions .notif:hover { color: #b8943c; }
        .topbar .actions .notif .badge { position: absolute; top: -5px; right: -5px; background: #ef4444; color: #fff; border-radius: 50%; width: 18px; height: 18px; font-size: 0.6rem; display: flex; align-items: center; justify-content: center; }
        .topbar .actions .avatar { width: 40px; height: 40px; border-radius: 50%; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c; border: 1px solid rgba(184,148,60,0.04); cursor: pointer; position: relative; }
        .topbar .actions .avatar .status-dot { position: absolute; bottom: 0; right: 0; width: 10px; height: 10px; border-radius: 50%; background: #10b981; border: 2px solid #0e0d0c; }
        .flash-messages { position: fixed; top: 20px; right: 20px; z-index: 1000; max-width: 400px; }
        .flash-message { background: rgba(184,148,60,0.1); border: 1px solid rgba(184,148,60,0.2); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 0.5rem; color: #f5f0eb; backdrop-filter: blur(10px); animation: slideIn 0.3s ease; }
        .flash-message.success { background: rgba(76,175,80,0.1); border-color: rgba(76,175,80,0.2); }
        .flash-message.error { background: rgba(239,83,80,0.1); border-color: rgba(239,83,80,0.2); }
        .flash-message.info { background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.2); }
        @keyframes slideIn { from { opacity: 0; transform: translateX(100%); } to { opacity: 1; transform: translateX(0); } }
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.23,1,0.32,1); }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        @media (max-width: 1024px) { .sidebar { width: 72px; padding: 1.5rem 0.8rem; } .sidebar .logo { font-size: 0; padding: 0 0 1.5rem 0; text-align: center; border-bottom: 1px solid rgba(245,240,235,0.03); } .sidebar .logo span { font-size: 1.2rem; } .sidebar .logo span::before { content: 'T'; } .sidebar .nav-item { padding: 0.6rem; justify-content: center; font-size: 0; } .sidebar .nav-item i { font-size: 1.2rem; width: auto; } .sidebar .nav-item .badge { display: none; } .sidebar .nav-section-title { display: none; } .sidebar .bottom-nav .nav-item { font-size: 0; } .main { margin-left: 72px; padding: 1.5rem; } }
        @media (max-width: 700px) { .main { padding: 1rem; } .sidebar { width: 60px; padding: 1rem 0.4rem; } .sidebar .logo { padding: 0 0 1rem 0; } .sidebar .logo span { font-size: 1rem; } .sidebar .nav-item { padding: 0.5rem; } .sidebar .nav-item i { font-size: 1rem; } .main { margin-left: 60px; } }
    </style>
</head>
<body>
    <div class="flash-messages" id="flashMessages">
        <?php if (flash('success')): ?>
            <div class="flash-message success"><?= sanitize(flash('success')) ?></div>
        <?php endif; ?>
        <?php if (flash('error')): ?>
            <div class="flash-message error"><?= sanitize(flash('error')) ?></div>
        <?php endif; ?>
        <?php if (flash('info')): ?>
            <div class="flash-message info"><?= sanitize(flash('info')) ?></div>
        <?php endif; ?>
    </div>

    <div class="dashboard">
        <aside class="sidebar">
            <div class="logo">Tek<span>Trend</span></div>
            <nav>
                <div class="nav-section-title">Main</div>
                <a href="/dashboard" class="nav-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-chart-pie"></i> Dashboard</a>
                <a href="/leads" class="nav-item <?= ($currentPage ?? '') === 'leads' ? 'active' : '' ?>"><i class="fas fa-bullseye"></i> Leads</a>
                <a href="/customers" class="nav-item <?= ($currentPage ?? '') === 'customers' ? 'active' : '' ?>"><i class="fas fa-users"></i> Customers</a>
                <a href="/invoices" class="nav-item <?= ($currentPage ?? '') === 'invoices' ? 'active' : '' ?>"><i class="fas fa-file-invoice"></i> Invoices</a>

                <div class="nav-section-title">Business</div>
                <a href="/finances" class="nav-item <?= ($currentPage ?? '') === 'finances' ? 'active' : '' ?>"><i class="fas fa-wallet"></i> Finances</a>
                <a href="/budgets" class="nav-item <?= ($currentPage ?? '') === 'budgets' ? 'active' : '' ?>"><i class="fas fa-chart-line"></i> Budgets</a>
                <a href="/taxes" class="nav-item <?= ($currentPage ?? '') === 'taxes' ? 'active' : '' ?>"><i class="fas fa-calculator"></i> Taxes</a>
                <a href="/emails" class="nav-item <?= ($currentPage ?? '') === 'emails' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Email Marketing</a>

                <div class="nav-section-title">Productivity</div>
                <a href="/events" class="nav-item <?= ($currentPage ?? '') === 'events' ? 'active' : '' ?>"><i class="fas fa-calendar-alt"></i> Events & Calendar</a>
                <a href="/timetable" class="nav-item <?= ($currentPage ?? '') === 'timetable' ? 'active' : '' ?>"><i class="fas fa-clock"></i> Timetable</a>
                <a href="/tasks" class="nav-item <?= ($currentPage ?? '') === 'tasks' ? 'active' : '' ?>"><i class="fas fa-tasks"></i> Tasks</a>
                <a href="/chat" class="nav-item <?= ($currentPage ?? '') === 'chat' ? 'active' : '' ?>"><i class="fas fa-comments"></i> Virtual Office</a>

                <div class="nav-section-title">Management</div>
                <a href="/employees" class="nav-item <?= ($currentPage ?? '') === 'employees' ? 'active' : '' ?>"><i class="fas fa-user-tie"></i> Employees</a>
                <a href="/departments" class="nav-item <?= ($currentPage ?? '') === 'departments' ? 'active' : '' ?>"><i class="fas fa-building"></i> Departments</a>
                <a href="/demos" class="nav-item <?= ($currentPage ?? '') === 'demos' ? 'active' : '' ?>"><i class="fas fa-rocket"></i> Demos</a>
                <a href="/content" class="nav-item <?= ($currentPage ?? '') === 'content' ? 'active' : '' ?>"><i class="fas fa-edit"></i> Content</a>
                <a href="/users" class="nav-item <?= ($currentPage ?? '') === 'users' ? 'active' : '' ?>"><i class="fas fa-user-cog"></i> Users</a>
                <a href="/settings" class="nav-item <?= ($currentPage ?? '') === 'settings' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Settings</a>
            </nav>
            <div class="bottom-nav">
                <a href="/dashboard/work-status" class="nav-item"><i class="fas fa-user-clock"></i> Work Status</a>
                <a href="/logout" class="nav-item"><i class="fas fa-right-from-bracket"></i> Logout</a>
            </div>
        </aside>

        <main class="main">
            <?= $content ?? '' ?>
        </main>
    </div>

    <script>
        // Scroll reveal animation
        (function() {
            const reveals = document.querySelectorAll('.reveal');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -20px 0px' });
            reveals.forEach(el => observer.observe(el));
            setTimeout(() => {
                reveals.forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.top < window.innerHeight) el.classList.add('visible');
                });
            }, 200);
        })();

        // Auto-hide flash messages
        setTimeout(() => {
            const messages = document.querySelectorAll('.flash-message');
            messages.forEach(m => { m.style.opacity = '0'; setTimeout(() => m.remove(), 300); });
        }, 5000);
    </script>
</body>
</html>
