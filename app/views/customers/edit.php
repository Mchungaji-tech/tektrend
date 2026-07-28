<?php $pageTitle = 'Edit Customer'; ?>
<div class="topbar"><div class="greeting"><h1>Edit Customer</h1><p>Update customer information</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Customer Information</h3></div>
    <form method="POST" action="/customers/<?= $customer['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">First Name</label><input type="text" name="first_name" class="form-control" required value="<?= sanitize($customer['first_name']) ?>"></div>
            <div class="form-group"><label class="required">Last Name</label><input type="text" name="last_name" class="form-control" required value="<?= sanitize($customer['last_name']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="<?= sanitize($customer['email']) ?>"></div>
            <div class="form-group"><label>Phone</label><input type="text" name="phone" class="form-control" value="<?= sanitize($customer['phone']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Company</label><input type="text" name="company" class="form-control" value="<?= sanitize($customer['company']) ?>"></div>
            <div class="form-group"><label>Tax ID</label><input type="text" name="tax_id" class="form-control" value="<?= sanitize($customer['tax_id']) ?>"></div>
        </div>
        <div class="form-group"><label>Address</label><input type="text" name="address" class="form-control" value="<?= sanitize($customer['address']) ?>"></div>
        <div class="form-row">
            <div class="form-group"><label>City</label><input type="text" name="city" class="form-control" value="<?= sanitize($customer['city']) ?>"></div>
            <div class="form-group"><label>State</label><input type="text" name="state" class="form-control" value="<?= sanitize($customer['state']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>Zip Code</label><input type="text" name="zip_code" class="form-control" value="<?= sanitize($customer['zip_code']) ?>"></div>
            <div class="form-group"><label>Country</label><input type="text" name="country" class="form-control" value="<?= sanitize($customer['country']) ?>"></div>
        </div>
        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="3"><?= sanitize($customer['notes']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
