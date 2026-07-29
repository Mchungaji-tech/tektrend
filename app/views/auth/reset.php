<?php $pageTitle = 'Reset Password'; ?>
<div class="auth-card">
    <div class="auth-header">
        <div class="logo"><i class="fas fa-code"></i> Tek Trend</div>
        <p>Choose a new password</p>
    </div>

    <?php if (flash('error')): ?>
        <div class="flash-message"><?= sanitize(flash('error')) ?></div>
    <?php endif; ?>

    <form method="POST" action="/reset-password">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="token" value="<?= sanitize($token ?? '') ?>">

        <div class="form-group">
            <label for="password" class="required">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" required minlength="8">
        </div>

        <div class="form-group">
            <label for="password_confirm" class="required">Confirm Password</label>
            <input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="Confirm new password" required minlength="8">
        </div>

        <button type="submit" class="btn btn-primary">
            <i class="fas fa-check"></i> Update Password
        </button>
    </form>

    <div class="auth-footer">
        <a href="/login">Back to login</a>
    </div>
</div>
