<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-layer-group"></i> Tektrend<span style="color: #e49366;">.</span></div>
        <p>Tektrend Softwares · Enterprise Management & Operations Portal</p>
    </div>

    <?php if (flash('error')): ?>
        <div class="flash-message"><?= sanitize(flash('error')) ?></div>
    <?php endif; ?>
    <?php if (flash('success')): ?>
        <div class="flash-message success"><?= sanitize(flash('success')) ?></div>
    <?php endif; ?>
    <?php if (flash('debug_token')): ?>
        <div class="flash-message" style="background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.2);"><?= sanitize(flash('debug_token')) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= eurl('/login') ?>" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label for="email" class="required">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email"
                   value="<?= sanitize($_SESSION['old']['email'] ?? '') ?>" required autofocus>
        </div>

        <div class="form-group">
            <label for="password" class="required">Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
        </div>

        <div class="checkbox-group">
            <input type="checkbox" id="remember" name="remember" value="1">
            <label for="remember" style="color: rgba(255,255,255,0.7); font-size: 0.85rem;">Remember me for 30 days</label>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-sign-in-alt"></i> Sign In
        </button>
    </form>

    <?php
        $googleOn = defined('GOOGLE_CLIENT_ID') && GOOGLE_CLIENT_ID !== '' && defined('GOOGLE_CLIENT_SECRET') && GOOGLE_CLIENT_SECRET !== '';
        $githubOn = defined('GITHUB_CLIENT_ID') && GITHUB_CLIENT_ID !== '' && defined('GITHUB_CLIENT_SECRET') && GITHUB_CLIENT_SECRET !== '';
    ?>
    <?php if ($googleOn || $githubOn): ?>
        <div style="display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:rgba(255,255,255,0.5);font-size:.8rem;">
            <span style="flex:1;height:1px;background:rgba(255,255,255,0.1);"></span> or <span style="flex:1;height:1px;background:rgba(255,255,255,0.1);"></span>
        </div>
    <?php endif; ?>

    <?php if ($googleOn): ?>
        <a href="<?= eurl('/auth/google') ?>" class="btn" style="display:flex;align-items:center;justify-content:center;gap:.6rem;width:100%;background:#fff;color:#3c4043;font-weight:600;border:none;text-decoration:none;<?= $githubOn ? 'margin-bottom:.6rem;' : '' ?>">
            <svg width="18" height="18" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.7 29.3 36 24 36c-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 6.5 29.5 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.3-.4-3.5z"/>
                <path fill="#FF3D00" d="M6.3 14.7l6.6 6.6C14.7 17.7 19 15 24 15c3.1 0 5.9 1.2 8 3.1l5.7-5.7C34.5 9.5 29.5 7 24 7 16.3 7 9.6 11.5 6.3 14.7z"/>
                <path fill="#4CAF50" d="M24 41c5.4 0 10.3-2.1 14-5.4l-6.4-6.4C29.3 30.8 26.8 31 24 31c-5.2 0-9.6-3.3-11.3-7.9l-6.6 6.6C9.5 36.4 16.1 41 24 41z"/>
                <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.2-2.2 4.1-4.1 5.5l6.4 6.4C41 36 44 30.6 44 24c0-1.3-.1-2.3-.4-3.5z"/>
            </svg>
            Sign in with Google
        </a>
    <?php endif; ?>

    <?php if ($githubOn): ?>
        <a href="<?= eurl('/auth/github') ?>" class="btn" style="display:flex;align-items:center;justify-content:center;gap:.6rem;width:100%;background:#24292e;color:#fff;font-weight:600;border:none;text-decoration:none;">
            <svg width="18" height="18" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="#fff" aria-hidden="true">
                <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.68-.02-1.34-2.23.49-2.7-1.07-2.7-1.07-.36-.93-.89-1.18-.89-1.18-.73-.5.05-.49.05-.49.81.06 1.23.83 1.23.83.72 1.23 1.88.87 2.34.67.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.83-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.83 1.27.83 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.94-.01 2.21 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
            </svg>
            Sign in with GitHub
        </a>
    <?php endif; ?>

    <?php if (($googleOn || $githubOn) && APP_DEBUG): ?>
        <p style="margin:.85rem 0 0;font-size:.72rem;color:rgba(255,255,255,0.45);line-height:1.6;">
            <strong>Set up in your OAuth app &rarr; Authorized redirect URI:</strong><br>
            <?php if ($googleOn): ?>Google: <code style="color:#e49366;word-break:break-all;"><?= htmlspecialchars(url('/auth/google/callback'), ENT_QUOTES, 'UTF-8') ?></code><br><?php endif; ?>
            <?php if ($githubOn): ?>GitHub: <code style="color:#e49366;word-break:break-all;"><?= htmlspecialchars(url('/auth/github/callback'), ENT_QUOTES, 'UTF-8') ?></code><?php endif; ?>
        </p>
    <?php endif; ?>


    <div class="auth-footer" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06);">
        <a href="<?= eurl('/forgot-password') ?>">Forgot password?</a> · <a href="<?= eurl('/') ?>">← Return to Public Site</a>
    </div>
</div>
