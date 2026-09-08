<?php $pageTitle = 'Edit Customer'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Edit Customer: <?= sanitize($customer['first_name'] . ' ' . $customer['last_name']) ?></h3>
            <p class="card-subtitle">Update organization details, tax profile, and billing address</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/customers/' . $customer['id']) ?>" class="btn btn-outline"><i class="fas fa-eye"></i> View Profile</a>
            <a href="<?= eurl('/customers') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> All Customers</a>
        </div>
    </div>

    <form method="POST" action="<?= eurl('/customers/' . $customer['id']) ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" required value="<?= sanitize($customer['first_name']) ?>">
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" required value="<?= sanitize($customer['last_name']) ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="<?= sanitize($customer['email'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="<?= sanitize($customer['phone'] ?? '') ?>">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="<?= sanitize($customer['company'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Tax PIN / VAT ID</label>
                <input type="text" name="tax_id" class="form-control" value="<?= sanitize($customer['tax_id'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Address</label>
            <input type="text" name="address" class="form-control" value="<?= sanitize($customer['address'] ?? '') ?>">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control" value="<?= sanitize($customer['city'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" class="form-control" value="<?= sanitize($customer['state'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Zip Code</label>
                <input type="text" name="zip_code" class="form-control" value="<?= sanitize($customer['zip_code'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" class="form-control" value="<?= sanitize($customer['country'] ?? '') ?>">
            </div>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" class="form-control" rows="3"><?= sanitize($customer['notes'] ?? '') ?></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/customers/' . $customer['id']) ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
        </div>
    </form>
</div>
