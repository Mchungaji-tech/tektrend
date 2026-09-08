<?php $pageTitle = 'Weekly Timetable'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-table" style="color: var(--primary); margin-right: 0.5rem;"></i> Department Weekly Timetable
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Recurring standup meetings, team synchronization, and design critiques</p>
        </div>
        <a href="<?= eurl('/events') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> All Events</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Weekly Schedule Slots</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Day of Week</th>
                    <th>Time Slot</th>
                    <th>Session Title</th>
                    <th>Department</th>
                    <th>Agenda Summary</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($slots)): ?>
                    <tr><td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">No timetable slots scheduled.</td></tr>
                <?php else: ?>
                    <?php foreach ($slots as $slot): ?>
                        <tr>
                            <td><strong style="color: var(--primary);"><?= sanitize($slot['day_of_week']) ?></strong></td>
                            <td style="color: var(--text-muted);"><?= sanitize($slot['start_time'] . ' - ' . $slot['end_time']) ?></td>
                            <td><strong style="color: var(--text-main);"><?= sanitize($slot['title']) ?></strong></td>
                            <td><span style="color: var(--text-main); font-weight: 500;"><?= sanitize($slot['department_name'] ?? 'All Hands') ?></span></td>
                            <td style="color: var(--text-muted);"><?= sanitize($slot['description'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
