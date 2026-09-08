<?php $pageTitle = 'Forgot Password'; ?>
<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-code"></i> Tek Trend</div>
        <p>Reset your password</p>
    </div>

    <?php if (flash('error')): ?>
        <div class="flash-message"><?= sanitize(flash('error')) ?></div>
    <?php endif; ?>
    <?php if (flash('success')): ?>
        <div class="flash-message success"><?= sanitize(flash('success')) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= eurl('/forgot-password') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div class="form-group">
            <label for="email" class="required">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your registered email" required>
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-paper-plane"></i> Send Reset Link
        </button>
    </form>

    <div class="auth-footer">
        <a href="<?= eurl('/login') ?>">Back to login</a>
    </div>
</div>
