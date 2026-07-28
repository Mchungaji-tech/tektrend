<?php $pageTitle = 'Meetings'; ?>
<div class="topbar"><div class="greeting"><h1>Meetings</h1><p>Manage video meetings</p></div>
    <a href="/meetings/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Schedule Meeting</a>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Meetings (<?= count($meetings) ?>)</h3></div>
    <table>
        <thead><tr><th>Title</th><th>Host</th><th>Scheduled</th><th>Status</th><th>Room ID</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($meetings)): ?>
                <tr><td colspan="6" style="text-align: center; color: rgba(245,240,235,0.3);">No meetings scheduled</td></tr>
            <?php else: ?>
                <?php foreach ($meetings as $m): ?>
                    <tr>
                        <td><?= sanitize($m['title']) ?></td>
                        <td><?= sanitize($m['first_name'] . ' ' . $m['last_name']) ?></td>
                        <td><?= formatDateTime($m['scheduled_at']) ?></td>
                        <td><span class="status <?= $m['status'] ?>"><?= ucfirst($m['status']) ?></span></td>
                        <td><?= sanitize($m['room_id']) ?></td>
                        <td>
                            <?php if ($m['status'] === 'scheduled'): ?><a href="/meetings/<?= $m['id'] ?>/start" style="color: #10b981;"><i class="fas fa-video"></i> Start</a><?php endif; ?>
                            <?php if ($m['status'] === 'in_progress'): ?><a href="/meetings/<?= $m['id'] ?>/join" style="color: #3b82f6; margin-left: 0.5rem;"><i class="fas fa-sign-in-alt"></i> Join</a><?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.scheduled { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.in_progress { color: #10b981; background: rgba(16,185,129,0.04); } table .status.ended { color: #6b7280; background: rgba(107,114,128,0.04); }</style>
