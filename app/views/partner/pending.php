<div class="card" style="max-width: 640px; margin: 0 auto; text-align: center;">
    <div class="empty-state" style="padding: 2rem 1rem;">
        <i class="fas fa-hourglass-half" style="color: var(--accent);"></i>
        <h3 style="margin-bottom: .5rem;">Application under review</h3>
        <p>Thanks<?= !empty($partner['company_name']) ? ', ' . sanitize($partner['company_name']) : '' ?>! Your partner application has been received and is pending approval by the Tek Trend team.</p>
        <p style="margin-top: .75rem; color: var(--text-muted);">Once approved you'll get full access to the partner dashboard, referrals and payouts. You can update your details from your <a href="<?= eurl('/partners/profile') ?>">profile</a> in the meantime.</p>
    </div>
</div>
