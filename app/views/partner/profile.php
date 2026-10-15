<div class="card" style="max-width: 680px;">
    <div class="card-header"><h3 class="card-title">Partner Profile</h3></div>

    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" value="<?= sanitize($user['email'] ?? '') ?>" disabled>
    </div>

    <form method="post" action="<?= eurl('/partners/profile') ?>">
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
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= sanitize($user['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Company Name</label>
            <input type="text" name="company_name" class="form-control" required value="<?= sanitize($partner['company_name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Website</label>
            <input type="text" name="website" class="form-control" value="<?= sanitize($partner['website'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>About</label>
            <textarea name="about" class="form-control"><?= sanitize($partner['about'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
    </form>
</div>
