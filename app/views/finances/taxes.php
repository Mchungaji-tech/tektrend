<?php $pageTitle = 'Tax Rates & Compliance'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-calculator" style="color: var(--primary); margin-right: 0.5rem;"></i> Tax Rates & Statutory Compliance
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Manage standard VAT, withholding tax, and automated transaction levies</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/taxes/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Tax Rate
            </a>
            <a href="<?= eurl('/finances') ?>" class="btn btn-outline">
                <i class="fas fa-wallet"></i> Financial Ledger
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Configured Tax Rates (<?= count($taxRates) ?>)</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tax Name</th>
                        <th>Type / Region</th>
                        <th style="text-align: right;">Rate</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($taxRates)): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 2rem; color: var(--text-muted);">No tax rates defined.</td></tr>
                    <?php else: ?>
                        <?php foreach ($taxRates as $t): ?>
                            <tr>
                                <td><strong style="color: var(--text-main);"><?= sanitize($t['name']) ?></strong></td>
                                <td><span style="color: var(--text-muted); font-size: 0.85rem;"><?= ucfirst(sanitize($t['type'])) ?> · <?= sanitize($t['country'] ?? 'Global') ?></span></td>
                                <td style="text-align: right;"><strong style="color: var(--primary); font-size: 1rem;"><?= $t['rate'] ?>%</strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tax Collected Records (<?= count($taxRecords) ?>)</h3>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Tax Code</th>
                        <th>Date</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($taxRecords)): ?>
                        <tr><td colspan="3" style="text-align: center; padding: 2rem; color: var(--text-muted);">No tax records logged yet.</td></tr>
                    <?php else: ?>
                        <?php foreach ($taxRecords as $tr): ?>
                            <tr>
                                <td><strong style="color: var(--text-main);"><?= sanitize($tr['tax_rate_name'] ?? 'General Tax') ?></strong></td>
                                <td style="color: var(--text-muted);"><?= formatDate($tr['created_at'] ?? '') ?></td>
                                <td style="text-align: right;"><strong style="color: var(--success);"><?= formatCurrency($tr['amount'] ?? 0) ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
