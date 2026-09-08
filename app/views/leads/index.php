<?php $pageTitle = 'Leads & CRM'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-bullseye" style="color: var(--primary); margin-right: 0.5rem;"></i> Leads & CRM Pipeline
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage prospective enterprise clients, follow-ups, and project contracts</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/leads/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Lead
            </a>
            <a href="<?= eurl('/leads/pipeline') ?>" class="btn btn-outline">
                <i class="fas fa-columns"></i> Kanban Board
            </a>
            <a href="<?= eurl('/leads/followup') ?>" class="btn btn-secondary" title="Upcoming Follow-ups">
                <i class="fas fa-bell"></i> Reminders
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">All Leads (<?= count($leads) ?>)</h3>
            <p class="card-subtitle">Active prospects across consulting & web design domains</p>
        </div>
        <form method="GET" action="<?= eurl('/leads') ?>" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <input type="text" name="search" placeholder="Search leads..." value="<?= sanitize($search ?? '') ?>" class="form-control" style="width: 180px;">
            <select name="status" onchange="this.form.submit()" class="form-control" style="width: auto;">
                <option value="">All Statuses</option>
                <option value="new" <?= ($statusFilter ?? '') === 'new' ? 'selected' : '' ?>>New</option>
                <option value="contacted" <?= ($statusFilter ?? '') === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                <option value="qualified" <?= ($statusFilter ?? '') === 'qualified' ? 'selected' : '' ?>>Qualified</option>
                <option value="proposal" <?= ($statusFilter ?? '') === 'proposal' ? 'selected' : '' ?>>Proposal</option>
                <option value="negotiation" <?= ($statusFilter ?? '') === 'negotiation' ? 'selected' : '' ?>>Negotiation</option>
                <option value="closed_won" <?= ($statusFilter ?? '') === 'closed_won' ? 'selected' : '' ?>>Won</option>
                <option value="closed_lost" <?= ($statusFilter ?? '') === 'closed_lost' ? 'selected' : '' ?>>Lost</option>
            </select>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Lead Name</th>
                    <th>Company</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Deal Value</th>
                    <th>Stage</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No leads found. <a href="<?= eurl('/leads/create') ?>" style="color: var(--primary); font-weight: 600;">Add your first lead</a>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($leads as $lead): ?>
                        <tr>
                            <td>
                                <strong style="color: var(--text-main); font-weight: 700;"><?= sanitize($lead['first_name'] . ' ' . $lead['last_name']) ?></strong>
                            </td>
                            <td><span style="color: var(--text-main); font-weight: 600;"><?= sanitize($lead['company'] ?? '-') ?></span></td>
                            <td style="color: var(--text-muted);"><?= sanitize($lead['email'] ?? '-') ?></td>
                            <td style="color: var(--text-muted);"><?= sanitize($lead['phone'] ?? '-') ?></td>
                            <td><strong style="color: var(--text-main);"><?= formatCurrency($lead['value']) ?></strong></td>
                            <td><span class="badge <?= sanitize($lead['status']) ?>"><?= ucfirst(str_replace('_', ' ', sanitize($lead['status']))) ?></span></td>
                            <td><span class="badge status <?= sanitize($lead['priority']) ?>"><?= ucfirst(sanitize($lead['priority'])) ?></span></td>
                            <td><span style="color: var(--text-muted); font-size: 0.85rem;"><?= sanitize(($lead['assigned_first'] ?? '') . ' ' . ($lead['assigned_last'] ?? '')) ?: 'Unassigned' ?></span></td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem;">
                                    <a href="<?= eurl('/leads/' . $lead['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Lead">
                                        <i class="fas fa-eye" style="color: var(--primary);"></i>
                                    </a>
                                    <a href="<?= eurl('/leads/' . $lead['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="Edit Lead">
                                        <i class="fas fa-edit" style="color: var(--accent);"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
