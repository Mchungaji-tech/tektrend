<?php $pageTitle = 'Taxes'; ?>
<div class="topbar"><div class="greeting"><h1>Taxes</h1><p>Manage tax rates and records</p></div>
    <a href="/taxes/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Tax Rate</a>
</div>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="chart-card reveal"><div class="header"><h3>Tax Rates</h3><span class="period"><?= count($taxRates) ?> rates</span></div>
        <div style="margin-top: 1rem;">
            <?php if (empty($taxRates)): ?>
                <p style="color: rgba(245,240,235,0.3);">No tax rates defined</p>
            <?php else: ?>
                <?php foreach ($taxRates as $t): ?>
                    <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div><div style="font-weight: 500;"><?= sanitize($t['name']) ?></div><div style="font-size: 0.7rem; color: rgba(245,240,235,0.3);"><?= $t['type'] ?> - <?= sanitize($t['country'] ?? '') ?></div></div>
                        <div style="font-weight: 600;"><?= $t['rate'] ?>%</div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="chart-card reveal"><div class="header"><h3>Tax Records</h3><span class="period"><?= count($taxRecords) ?> records</span></div>
        <div style="margin-top: 1rem;">
            <?php if (empty($taxRecords)): ?>
                <p style="color: rgba(245,240,235,0.3);">No tax records</p>
            <?php else: ?>
                <?php foreach ($taxRecords as $tr): ?>
                    <div style="padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div style="display: flex; justify-content: space-between;"><span style="font-weight: 500;"><?= sanitize($tr['tax_rate_name'] ?? 'Unknown') ?></span><span style="font-size: 0.7rem; color: rgba(245,240,235,0.3);"><?= formatCurrency($tr['amount'] ?? 0) ?></span></div>
                        <div style="font-size: 0.7rem; color: rgba(245,240,235,0.3);"><?= formatDate($tr['created_at'] ?? '') ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .chart-card .header .period { font-size: 0.7rem; color: rgba(245,240,235,0.15); background: rgba(245,240,235,0.02); padding: 0.2rem 1rem; border-radius: 40px; border: 1px solid rgba(245,240,235,0.02); }</style>
