<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-layer-group"></i> Tektrend<span style="color: #e49366;"></span></div>
        <?php $area = $area ?? ''; ?>
        <p>
            <?php if ($area === 'client'): ?>Client Portal · Sign in to your Tek Trend account<?php elseif ($area === 'partner'): ?>Partner Portal · Sign in to your Tek Trend partner account<?php else: ?>Tektrend Softwares · Enterprise Management & Operations Portal<?php endif; ?>
        </p>
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
        $oauthIntent = ($area === 'client') ? 'client' : (($area === 'partner') ? 'partner' : 'staff');
        include VIEW_PATH . '/partials/social_buttons.php';
    ?>

    <?php if (($googleOn || $githubOn) && APP_DEBUG): ?>
        <p style="margin:.85rem 0 0;font-size:.72rem;color:rgba(255,255,255,0.45);line-height:1.6;">
            <strong>Set up in your OAuth app &rarr; Authorized redirect URI:</strong><br>
            <?php if ($googleOn): ?>Google: <code style="color:#e49366;word-break:break-all;"><?= htmlspecialchars(url('/auth/google/callback'), ENT_QUOTES, 'UTF-8') ?></code><br><?php endif; ?>
            <?php if ($githubOn): ?>GitHub: <code style="color:#e49366;word-break:break-all;"><?= htmlspecialchars(url('/auth/github/callback'), ENT_QUOTES, 'UTF-8') ?></code><?php endif; ?>
        </p>
    <?php endif; ?>


    <div class="auth-footer" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06); line-height: 1.9;">
        <a href="<?= eurl('/forgot-password') ?>">Forgot password?</a> · <a href="<?= eurl('/') ?>">← Return to Public Site</a><br>
        <a href="<?= eurl('/signup') ?>" style="color:#d6c29d;"><i class="fas fa-user-plus"></i> Create a client account</a> ·
        <a href="<?= eurl('/partner-apply') ?>" style="color:#d6c29d;"><i class="fas fa-handshake"></i> Become a partner</a>
    </div>
</div>
