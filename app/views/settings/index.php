<?php $pageTitle = 'Settings'; ?>
<div class="topbar"><div class="greeting"><h1>Settings</h1><p>Manage system settings</p></div></div>
<div class="chart-card reveal">
    <div class="header"><h3>System Settings</h3></div>
    <form method="POST" action="/settings">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <?php foreach ($settings as $group => $groupSettings): ?>
            <h4 style="color: #b8943c; margin: 1.5rem 0 0.8rem; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.8px;"><?= ucfirst($group) ?> Settings</h4>
            <?php foreach ($groupSettings as $s): ?>
                <div class="form-row">
                    <div class="form-group" style="flex: 2;"><label style="color: rgba(245,240,235,0.5); font-size: 0.8rem;"><?= sanitize($s['label'] ?? ucfirst(str_replace('_', ' ', $s['key']))) ?></label></div>
                    <div class="form-group" style="flex: 3;">
                        <?php if ($s['type'] === 'textarea'): ?>
                            <textarea name="settings[<?= $s['key'] ?>]" class="form-control" rows="3"><?= sanitize($s['value']) ?></textarea>
                        <?php elseif ($s['type'] === 'select'): ?>
                            <select name="settings[<?= $s['key'] ?>]" class="form-control">
                                <?php foreach (json_decode($s['options'] ?? '[]', true) as $optVal => $optLabel): ?>
                                    <option value="<?= $optVal ?>" <?= ($s['value'] == $optVal) ? 'selected' : '' ?>><?= $optLabel ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="<?= $s['type'] ?? 'text' ?>" name="settings[<?= $s['key'] ?>]" class="form-control" value="<?= sanitize($s['value']) ?>">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <button type="submit" class="btn btn-primary" style="margin-top: 1.5rem;"><i class="fas fa-save"></i> Save Settings</button>
    </form>
</div>
<style>.form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }</style>
