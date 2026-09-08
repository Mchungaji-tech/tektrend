<?php $pageTitle = 'Visual Sales Pipeline'; ?>

<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h3 class="card-title"><i class="fas fa-columns" style="color: var(--primary); margin-right: 0.4rem;"></i> Visual CRM Sales Pipeline</h3>
            <p class="card-subtitle">Manage deal stages, forecast revenue, and track consultations</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/leads/create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> New Deal / Lead</a>
            <a href="<?= eurl('/leads') ?>" class="btn btn-outline"><i class="fas fa-list"></i> Table View</a>
        </div>
    </div>
</div>

<div class="kanban-board">
    <?php foreach ($stages as $stageKey => $stage): ?>
        <div class="kanban-column">
            <div class="column-header" style="border-top: 3px solid <?= $stage['color'] ?>;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span class="stage-name"><?= $stage['name'] ?></span>
                    <span class="stage-count"><?= count($stage['deals']) ?></span>
                </div>
                <div class="stage-total"><?= formatCurrency($stage['total']) ?></div>
            </div>

            <div class="deal-list">
                <?php if (empty($stage['deals'])): ?>
                    <div class="empty-column">No deals in this stage</div>
                <?php else: ?>
                    <?php foreach ($stage['deals'] as $deal): ?>
                        <div class="deal-card">
                            <div class="deal-title"><?= sanitize($deal['first_name'] . ' ' . $deal['last_name']) ?></div>
                            <div class="deal-company"><i class="fas fa-building" style="color: var(--text-light); font-size: 0.75rem;"></i> <?= sanitize($deal['company'] ?? 'Independent Client') ?></div>
                            <div class="deal-value"><?= formatCurrency($deal['value']) ?></div>
                            
                            <div class="deal-meta">
                                <span><i class="fas fa-user-circle"></i> <?= sanitize($deal['first_name']) ?></span>
                                <span class="deal-priority <?= $deal['priority'] ?>"><?= ucfirst($deal['priority']) ?></span>
                            </div>

                            <div class="deal-actions">
                                <form method="POST" action="<?= eurl('/leads/' . $deal['id'] . '/stage') ?>" style="display: inline-block;">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <select name="stage" onchange="this.form.submit()" class="stage-select">
                                        <?php foreach ($stages as $sk => $s): ?>
                                            <option value="<?= $sk ?>" <?= $sk === $stageKey ? 'selected' : '' ?>>Move: <?= $s['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                                <a href="<?= eurl('/leads/' . $deal['id']) ?>" class="btn-icon" title="View Lead"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
    .kanban-board {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding-bottom: 2rem;
        -webkit-overflow-scrolling: touch;
    }
    .kanban-column {
        flex: 0 0 280px;
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-md);
        display: flex;
        flex-direction: column;
        max-height: 80vh;
    }
    .column-header {
        padding: 1rem;
        background: var(--bg-card-subtle);
        border-bottom: 1px solid var(--border);
        border-top-left-radius: var(--radius-md);
        border-top-right-radius: var(--radius-md);
    }
    .stage-name { font-size: 0.85rem; font-weight: 700; color: var(--text-main); }
    .stage-count {
        font-size: 0.72rem; font-weight: 700; background: var(--bg-card);
        border: 1px solid var(--border); padding: 0.1rem 0.5rem; border-radius: 9999px; color: var(--text-muted);
    }
    .stage-total { font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-top: 0.35rem; }
    .deal-list { padding: 0.75rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem; flex: 1; }
    .empty-column { padding: 2rem 1rem; text-align: center; font-size: 0.8rem; color: var(--text-muted); }
    .deal-card {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: var(--radius-sm);
        padding: 1rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }
    .deal-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); border-color: var(--primary); }
    .deal-title { font-size: 0.9rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.2rem; }
    .deal-company { font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.5rem; }
    .deal-value { font-size: 1.15rem; font-weight: 800; color: var(--primary); margin-bottom: 0.65rem; }
    .deal-meta { display: flex; justify-content: space-between; align-items: center; font-size: 0.72rem; color: var(--text-muted); margin-bottom: 0.65rem; }
    .deal-priority {
        padding: 0.1rem 0.45rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; font-size: 0.65rem;
    }
    .deal-priority.urgent { background: var(--danger-light); color: var(--danger); }
    .deal-priority.high { background: var(--accent-light); color: var(--accent); }
    .deal-priority.medium { background: var(--primary-light); color: var(--primary); }
    .deal-priority.low { background: var(--bg-card-subtle); color: var(--text-muted); }
    .deal-actions { display: flex; align-items: center; justify-content: space-between; gap: 0.35rem; padding-top: 0.5rem; border-top: 1px solid var(--border); }
    .stage-select {
        font-size: 0.72rem; padding: 0.25rem 0.4rem; border-radius: 4px; border: 1px solid var(--border);
        background: var(--bg-card-subtle); color: var(--text-main); max-width: 190px;
    }
    .btn-icon { color: var(--primary); padding: 0.25rem; font-size: 0.85rem; }
</style>
