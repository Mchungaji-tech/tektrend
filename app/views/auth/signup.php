<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-layer-group"></i> Tektrend<span style="color: #e49366;"></span></div>
        <p>Create your Tek Trend client account</p>
    </div>

    <?php if (flash('error')): ?>
        <div class="flash-message"><?= sanitize(flash('error')) ?></div>
    <?php endif; ?>
    <?php if (flash('success')): ?>
        <div class="flash-message success"><?= sanitize(flash('success')) ?></div>
    <?php endif; ?>

    <?php $old = $_SESSION['old'] ?? []; ?>
    <form method="POST" action="<?= eurl('/signup') ?>" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-row" style="margin-bottom:1.5rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?= sanitize($old['first_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?= sanitize($old['last_name'] ?? '') ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label class="required">Email Address</label>
            <input type="email" name="email" class="form-control" value="<?= sanitize($old['email'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label class="required">Password</label>
            <input type="password" name="password" class="form-control" placeholder="At least 8 characters" required minlength="8">
        </div>

        <div class="form-row" style="margin-bottom:1.5rem;">
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($old['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="<?= sanitize($old['company'] ?? '') ?>">
            </div>
        </div>

        <!-- reCAPTCHA hook: add site key widget here when enabled in .env -->

        <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Create Account</button>
    </form>

    <?php $oauthIntent = 'client'; include VIEW_PATH . '/partials/social_buttons.php'; ?>

    <div class="auth-footer" style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.06); line-height: 1.9;">
        Already have an account? <a href="<?= eurl('/login?area=client') ?>">Sign in</a><br>
        Looking to partner with us? <a href="<?= eurl('/partner-apply') ?>">Become a partner</a>
    </div>
</div>
