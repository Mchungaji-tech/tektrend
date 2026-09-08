<?php $pageTitle = 'Add Customer Account'; ?>

<div class="card" style="max-width: 800px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h3 class="card-title">Add Client / Customer Account</h3>
            <p class="card-subtitle">Register new organization, primary contact, and billing details</p>
        </div>
        <a href="<?= eurl('/customers') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Customers</a>
    </div>

    <form method="POST" action="<?= eurl('/customers') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label class="required">First Name</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="required">Last Name</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control">
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>Company / Organization Name</label>
                <input type="text" name="company" class="form-control">
            </div>
            <div class="form-group">
                <label>Tax PIN / VAT ID</label>
                <input type="text" name="tax_id" class="form-control">
            </div>
        </div>

        <div class="form-group">
            <label>Physical / Postal Address</label>
            <input type="text" name="address" class="form-control">
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control">
            </div>
            <div class="form-group">
                <label>State / Region</label>
                <input type="text" name="state" class="form-control">
            </div>
            <div class="form-group">
                <label>Zip Code</label>
                <input type="text" name="zip_code" class="form-control">
            </div>
            <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" class="form-control" value="Kenya">
            </div>
        </div>

        <div class="form-group">
            <label>Internal Notes</label>
            <textarea name="notes" class="form-control" rows="3" placeholder="Account history, custom retainer terms, preferences..."></textarea>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <a href="<?= eurl('/customers') ?>" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Customer Account</button>
        </div>
    </form>
</div>
