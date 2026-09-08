<?php $pageTitle = 'Contracts & Retainers'; ?>
<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-file-contract" style="color: var(--primary); margin-right: 0.5rem;"></i> Consulting Contracts & Retainers
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage client engagement agreements, legal terms, and signed scopes</p>
        </div>
        <a href="<?= eurl('/contracts/create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Contract
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Service Contracts (<?= count($contracts) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Contract #</th>
                    <th>Title & Client</th>
                    <th>Contract Value</th>
                    <th>Term (Start - End)</th>
                    <th>Signed By</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contracts)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2.5rem; color: var(--text-muted);">
                            <i class="fas fa-file-alt" style="font-size: 2rem; display: block; margin-bottom: 0.5rem; opacity: 0.4;"></i>
                            No contracts found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contracts as $c): ?>
                        <tr>
                            <td><strong style="font-family: monospace; font-size: 0.85rem; color: var(--primary);"><?= sanitize($c['contract_number']) ?></strong></td>
                            <td>
                                <div><strong style="color: var(--text-main);"><?= sanitize($c['title']) ?></strong></div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);"><?= sanitize($c['customer_company'] ?? ($c['customer_first'] . ' ' . $c['customer_last'])) ?></div>
                            </td>
                            <td><strong style="color: var(--primary);">$<?= number_format($c['value'], 2) ?></strong></td>
                            <td style="color: var(--text-muted);">
                                <?= formatDate($c['start_date']) ?>
                                <?php if (!empty($c['end_date'])): ?>
                                    to <?= formatDate($c['end_date']) ?>
                                <?php endif; ?>
                            </td>
                            <td><span style="color: var(--text-main);"><?= sanitize($c['signed_by_name'] ?? 'Pending Signature') ?></span></td>
                            <td><span class="badge <?= sanitize($c['status']) ?>"><?= ucfirst(sanitize($c['status'])) ?></span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 0.35rem;">
                                    <a href="<?= eurl('/contracts/' . $c['id']) ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="<?= eurl('/contracts/' . $c['id'] . '/print') ?>" target="_blank" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Print Agreement"><i class="fas fa-print"></i></a>
                                    <a href="<?= eurl('/contracts/' . $c['id'] . '/edit') ?>" class="btn btn-outline" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Edit"><i class="fas fa-edit"></i></a>
                                    <a href="<?= eurl('/contracts/' . $c['id'] . '/delete') ?>" onclick="return confirm('Delete this contract?')" class="btn btn-danger" style="padding: 0.25rem 0.6rem; font-size: 0.78rem;" title="Delete"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
