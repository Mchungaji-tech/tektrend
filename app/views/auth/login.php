<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-code"></i> Tek Trend</div>
        <p>Virtual Company Management System</p>
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

    <form method="POST" action="/login" autocomplete="off">
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

    <div class="auth-footer">
        <a href="/forgot-password">Forgot your password?</a>
    </div>
</div>
