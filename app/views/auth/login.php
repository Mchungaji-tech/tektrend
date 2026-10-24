<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-layer-group"></i> Tektrend<span style="color: #e49366;"></span></div>
        <?php $area = $area ?? ''; ?>
        <p>
            <?php if ($area === 'client'): ?>Client &amp; Buyer Portal · Sign in to access your source downloads and projects<?php elseif ($area === 'partner'): ?>Partner Portal · Sign in to your Tek Trend partner account<?php else: ?>Sign in to your Tektrend account (Buyers, Clients &amp; Team)<?php endif; ?>
        </p>
    </div>

    <?php 
        $flashError   = flash('error');
        $flashSuccess = flash('success');
        $flashDebug   = flash('debug_token');
    ?>

    <?php if (!empty($flashError)): ?>
        <div class="flash-message" style="display:flex;align-items:center;gap:.75rem;text-align:left;background:rgba(239,83,80,0.15);border-color:rgba(239,83,80,0.35);">
            <i class="fas fa-circle-exclamation" style="color:#ef4444;font-size:1.15rem;flex-shrink:0;"></i>
            <span style="font-size:0.9rem;line-height:1.4;color:#fee2e2;"><?= sanitize($flashError) ?></span>
        </div>
    <?php endif; ?>
    <?php if (!empty($flashSuccess)): ?>
        <div class="flash-message success" style="display:flex;align-items:center;gap:.75rem;text-align:left;background:rgba(16,185,129,0.15);border-color:rgba(16,185,129,0.35);">
            <i class="fas fa-circle-check" style="color:#10b981;font-size:1.15rem;flex-shrink:0;"></i>
            <span style="font-size:0.9rem;line-height:1.4;color:#d1fae5;"><?= sanitize($flashSuccess) ?></span>
        </div>
    <?php endif; ?>
    <?php if (!empty($flashDebug)): ?>
        <div class="flash-message" style="background:rgba(59,130,246,0.15);border-color:rgba(59,130,246,0.3);display:flex;align-items:center;gap:.75rem;text-align:left;">
            <i class="fas fa-circle-info" style="color:#3b82f6;font-size:1.15rem;flex-shrink:0;"></i>
            <span style="font-size:0.9rem;line-height:1.4;"><?= sanitize($flashDebug) ?></span>
        </div>
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
        $oauthIntent = ($area === 'partner') ? 'partner' : 'client';
        include VIEW_PATH . '/partials/social_buttons.php';
    ?>


    <div class="auth-footer" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06); line-height: 1.9;">
        <a href="<?= eurl('/forgot-password') ?>">Forgot password?</a> · <a href="<?= eurl('/') ?>">← Return to Public Site</a><br>
        <a href="<?= eurl('/signup') ?>" style="color:#d6c29d;"><i class="fas fa-user-plus"></i> Create a client account</a> ·
        <a href="<?= eurl('/partner-apply') ?>" style="color:#d6c29d;"><i class="fas fa-handshake"></i> Become a partner</a>
    </div>
</div>
