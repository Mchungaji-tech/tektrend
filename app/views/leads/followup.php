<?php $pageTitle = 'Lead Follow-up Schedule'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-bell" style="color: var(--accent); margin-right: 0.5rem;"></i> Lead Follow-up Schedule
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Upcoming consultations, scheduled call backs, and negotiation milestones</p>
        </div>
        <a href="<?= eurl('/leads') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Leads</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Due Follow-ups (<?= count($leads) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Follow-up Date</th>
                    <th>Deal Value</th>
                    <th>Current Stage</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fas fa-calendar-check" style="font-size: 1.8rem; color: var(--success); margin-bottom: 0.5rem; display: block;"></i>
                            All follow-ups are up to date. No pending reminders due.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td><strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></strong></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($lead['company'] ?? '-') ?></span></td>
                            <td style="color: var(--text-muted);"><?= sanitize($lead['email'] ?? '-') ?></td>
                            <td><strong style="color: var(--accent);"><?= formatDateTime($lead['next_followup']) ?></strong></td>
                            <td><strong style="color: var(--text-main);"><?= formatCurrency($lead['value']) ?></strong></td>
                            <td><span class="badge <?= sanitize($lead['status']) ?>"><?= ucfirst(str_replace('_', ' ', sanitize($lead['status']))) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/leads/' . $lead['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Lead">
                                    <i class="fas fa-eye" style="color: var(--primary);"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
