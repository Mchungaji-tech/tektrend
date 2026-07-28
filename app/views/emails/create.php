<?php $pageTitle = 'Create Campaign'; ?>
<div class="topbar"><div class="greeting"><h1>Create Campaign</h1><p>Create a new email campaign</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>Campaign Information</h3></div>
    <form method="POST" action="/emails">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <div class="form-row">
            <div class="form-group"><label class="required">Campaign Name</label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label class="required">Subject</label><input type="text" name="subject" class="form-control" required></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label>From Name</label><input type="text" name="from_name" class="form-control" value="Tek Trend"></div>
            <div class="form-group"><label>From Email</label><input type="email" name="from_email" class="form-control" value="info@tektrend.com"></div>
        </div>
        <div class="form-group"><label>Status</label><select name="status" class="form-control">
            <option value="draft">Draft</option><option value="scheduled">Scheduled</option>
        </select></div>
        <div class="form-group"><label>Scheduled At</label><input type="datetime-local" name="scheduled_at" class="form-control"></div>
        <div class="form-group"><label>HTML Content</label><textarea name="content_html" class="form-control" rows="10" placeholder="<h2>Welcome!</h2><p>Enter your email content here...</p>"></textarea></div>
        <div class="form-group"><label>Text Content</label><textarea name="content_text" class="form-control" rows="5"></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Campaign</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
