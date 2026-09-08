<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . ' · ' : '' ?>Tek Trend Virtual Office</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #0b0d0f; color: #fff; min-height: 100vh; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .auth-bg { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: url('https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat; filter: brightness(0.4) saturate(1.1); z-index: 0; }
        .auth-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: radial-gradient(circle at 30% 40%, rgba(40,50,80,0.4) 0%, rgba(10,12,18,0.85) 90%); z-index: 1; }
        .auth-container { position: relative; z-index: 10; width: 100%; max-width: 420px; }
        .auth-card { background: rgba(18,22,28,0.6); backdrop-filter: blur(12px); border-radius: 30px; padding: 3rem; border: 1px solid rgba(255,215,150,0.06); box-shadow: 0 40px 80px -12px rgba(0,0,0,0.7); }
        .auth-header { text-align: center; margin-bottom: 2rem; }
        .auth-header .logo { font-size: 1.8rem; font-weight: 800; background: linear-gradient(135deg,#f0e9d0,#b7a88b); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem; }
        .auth-header .logo i { color: #c7b18b; -webkit-text-fill-color: #c7b18b; margin-right: 8px; }
        .auth-header p { color: rgba(255,255,255,0.6); font-size: 0.9rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label { display: block; margin-bottom: 0.5rem; font-size: 0.85rem; color: rgba(255,255,255,0.7); font-weight: 500; }
        .form-group .required::after { content: ' *'; color: #ef4444; }
        .form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-size: 0.95rem; font-family: 'Inter', sans-serif; transition: 0.3s; }
        .form-control:focus { outline: none; border-color: #b8943c; background: rgba(0,0,0,0.3); box-shadow: 0 0 0 2px rgba(184,148,60,0.1); }
        .form-control::placeholder { color: rgba(255,255,255,0.3); }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; padding: 0.8rem 1.5rem; border: none; border-radius: 12px; font-size: 0.95rem; font-weight: 600; cursor: pointer; transition: 0.3s; font-family: 'Inter', sans-serif; }
        .btn-primary { background: linear-gradient(135deg,#b8943c,#d6c29d); color: #0e0d0c; }
        .btn-primary:hover { background: linear-gradient(135deg,#d6c29d,#b8943c); transform: translateY(-1px); box-shadow: 0 5px 20px rgba(184,148,60,0.3); }
        .btn-secondary { background: rgba(255,255,255,0.03); color: #f5f0eb; border: 1px solid rgba(255,255,255,0.06); }
        .btn-secondary:hover { background: rgba(255,255,255,0.06); border-color: #b8943c; color: #fff; }
        .auth-footer { text-align: center; margin-top: 1.5rem; font-size: 0.85rem; color: rgba(255,255,255,0.5); }
        .auth-footer a { color: #b8943c; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
        .flash-message { background: rgba(239,83,80,0.1); border: 1px solid rgba(239,83,80,0.2); border-radius: 10px; padding: 0.7rem 1rem; margin-bottom: 1rem; color: #f5f0eb; text-align: center; }
        .flash-message.success { background: rgba(76,175,80,0.1); border-color: rgba(76,175,80,0.2); }
        .form-row { display: flex; gap: 1rem; }
        .form-row .form-group { flex: 1; margin-bottom: 0; }
        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; }
        .checkbox-group input { width: 16px; height: 16px; cursor: pointer; }
        @media (max-width: 480px) { .auth-card { padding: 2rem; } .form-row { flex-direction: column; gap: 0; } }
    </style>
</head>
<body>
    <div class="auth-bg"></div>
    <div class="auth-overlay"></div>
    <div class="auth-container">
        <?= $content ?? '' ?>
    </div>
</body>
</html>
