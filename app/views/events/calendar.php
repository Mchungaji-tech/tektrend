<?php $pageTitle = 'Operational Calendar'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-calendar" style="color: var(--primary); margin-right: 0.5rem;"></i> Operational Calendar & Agenda
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Upcoming events, delivery deadlines, and team reviews</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/events/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Event
            </a>
            <a href="<?= eurl('/events') ?>" class="btn btn-outline">
                <i class="fas fa-list"></i> Table View
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Upcoming Agenda Items (<?= count($events) ?>)</h3>
    </div>

    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
        <?php if (empty($events)): ?>
            <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">No upcoming events.</p>
        <?php else: ?>
            <?php foreach ($events as $e): ?>
                <div style="display: flex; align-items: center; gap: 1.25rem; padding: 1rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                    <div style="width: 54px; text-align: center; background: var(--bg-card); padding: 0.5rem; border-radius: var(--radius-sm); border: 1px solid var(--border);">
                        <div style="font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase;"><?= date('M', strtotime($e['start_datetime'])) ?></div>
                        <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);"><?= date('d', strtotime($e['start_datetime'])) ?></div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin-bottom: 0.2rem;"><?= sanitize($e['title']) ?></div>
                        <div style="font-size: 0.78rem; color: var(--text-muted);">
                            <i class="far fa-clock"></i> <?= formatDateTime($e['start_datetime']) ?>
                            <?php if (!empty($e['location'])): ?>
                                <span style="margin-left: 1rem;"><i class="fas fa-map-marker-alt" style="color: var(--accent);"></i> <?= sanitize($e['location']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <span class="badge <?= sanitize($e['status']) ?>"><?= ucfirst(sanitize($e['status'])) ?></span>
                    <a href="<?= eurl('/events/' . $e['id']) ?>" class="btn btn-outline" style="padding: 0.35rem 0.65rem; font-size: 0.8rem;"><i class="fas fa-eye"></i></a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
