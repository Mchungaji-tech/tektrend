<?php $pageTitle = 'Events & Schedules'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-calendar-alt" style="color: var(--primary); margin-right: 0.5rem;"></i> Events & Operational Calendar
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Track corporate briefings, deployment releases, holidays, and milestones</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/events/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Event
            </a>
            <a href="<?= eurl('/events/calendar') ?>" class="btn btn-outline">
                <i class="fas fa-calendar"></i> Calendar View
            </a>
            <a href="<?= eurl('/events/timetable') ?>" class="btn btn-secondary">
                <i class="fas fa-table"></i> Timetable
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Scheduled Events (<?= count($events) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Event Title</th>
                    <th>Type</th>
                    <th>Date & Time</th>
                    <th>Location / Channel</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($events)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            No events scheduled.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($events as $e): ?>
                        <tr>
                            <td><strong style="color: var(--text-main);"><?= sanitize($e['title']) ?></strong></td>
                            <td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= ucfirst(sanitize($e['type'])) ?></span></td>
                            <td style="color: var(--text-muted);"><?= formatDateTime($e['start_datetime']) ?></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($e['location'] ?? 'Virtual Office') ?></span></td>
                            <td><span style="color: var(--text-main);"><?= sanitize($e['department_name'] ?? 'General') ?></span></td>
                            <td><span class="badge <?= sanitize($e['status']) ?>"><?= ucfirst(sanitize($e['status'])) ?></span></td>
                            <td style="text-align: right;">
                                <a href="<?= eurl('/events/' . $e['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;" title="View Details">
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
