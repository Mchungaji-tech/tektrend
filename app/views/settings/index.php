<?php $pageTitle = 'System Settings'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-sliders" style="color: var(--primary); margin-right: 0.5rem;"></i> System Settings & Configuration
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Global platform identity, company metadata, and feature controls</p>
        </div>
        <a href="<?= eurl('/dashboard') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>

<div class="card" style="max-width: 850px; margin: 0 auto;">
    <form method="POST" action="<?= eurl('/settings') ?>">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

        <?php foreach ($settings as $group => $groupSettings): ?>
            <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border); padding-bottom: 1.5rem;">
                <h4 style="color: var(--primary); font-size: 0.95rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1.25rem;">
                    <?= ucfirst(sanitize($group)) ?> Settings
                </h4>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <?php foreach ($groupSettings as $s): ?>
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; align-items: center;">
                            <label style="color: var(--text-main); font-weight: 600; font-size: 0.88rem;">
                                <?= sanitize($s['label'] ?? ucfirst(str_replace('_', ' ', $s['key']))) ?>
                            </label>
                            <div>
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
                </div>
            </div>
        <?php endforeach; ?>

        <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1.5rem;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save All Settings</button>
        </div>
    </form>
</div>
