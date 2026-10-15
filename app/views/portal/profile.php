<div class="card" style="max-width: 640px;">
    <div class="card-header"><h3 class="card-title">My Profile</h3></div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" value="<?= sanitize($user['email'] ?? '') ?>" disabled>
        <small style="color: var(--text-muted);">Your email is used to sign in and cannot be changed here.</small>
    </div>

    <form method="post" action="<?= eurl('/portal/profile') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" required value="<?= sanitize($user['first_name'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" required value="<?= sanitize($user['last_name'] ?? '') ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="<?= sanitize($customer['company'] ?? '') ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
    </form>
</div>
