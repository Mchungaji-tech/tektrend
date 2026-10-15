<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">My Projects</h3>
            <p class="card-subtitle">Contracts and engagements scoped to your account</p>
        </div>
    </div>
    <?php if (empty($projects)): ?>
        <div class="empty-state"><i class="fas fa-diagram-project"></i><p>You have no projects yet.</p></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead><tr><th>Reference</th><th>Project</th><th>Start</th><th>End</th><th>Value</th><th>Status</th></tr></thead>
                <tbody>
                <?php foreach ($projects as $p): ?>
                    <tr>
                        <td><?= sanitize($p['contract_number']) ?></td>
                        <td><strong><?= sanitize($p['title']) ?></strong></td>
                        <td><?= formatDate($p['start_date']) ?></td>
                        <td><?= formatDate($p['end_date']) ?></td>
                        <td><?= formatCurrency($p['value']) ?></td>
                        <td><span class="badge <?= sanitize($p['status']) ?>"><?= sanitize(ucfirst(str_replace('_',' ',$p['status']))) ?></span></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
