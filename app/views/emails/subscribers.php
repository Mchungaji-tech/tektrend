<?php $pageTitle = 'Subscribers'; ?>
<div class="topbar"><div class="greeting"><h1>Email Subscribers</h1><p>Manage email subscribers</p></div>
    <a href="/emails/subscribers" onclick="document.getElementById('addModal').style.display='block'; return false;" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Subscriber</a>
</div>
<div class="table-section reveal">
    <div class="header"><h3>All Subscribers (<?= count($subscribers) ?>)</h3>
        <form method="GET" style="display: flex;">
            <input type="text" name="search" placeholder="Search subscribers..." value="<?= sanitize($search) ?>" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
        </form>
    </div>
    <table>
        <thead><tr><th>Email</th><th>Name</th><th>Source</th><th>Subscribed</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($subscribers)): ?>
                <tr><td colspan="6" style="text-align: center; color: rgba(245,240,235,0.3);">No subscribers found</td></tr>
            <?php else: ?>
                <?php foreach ($subscribers as $s): ?>
                    <tr>
                        <td><?= sanitize($s['email']) ?></td>
                        <td><?= sanitize(($s['first_name'] ?? '') . ' ' . ($s['last_name'] ?? '')) ?: '-' ?></td>
                        <td><?= sanitize($s['source'] ?? '-') ?></td>
                        <td><?= formatDate($s['subscribed_at']) ?></td>
                        <td><span class="status <?= $s['status'] ?>"><?= ucfirst($s['status']) ?></span></td>
                        <td><a href="/emails/subscribers/<?= $s['id'] ?>/delete" onclick="return confirm('Remove this subscriber?')" style="color: #ef4444;"><i class="fas fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<div id="addModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
    <div style="background: #1a1a1a; border-radius: 20px; padding: 2rem; width: 90%; max-width: 500px; margin: 50px auto;">
        <h3 style="color: #f5f0eb;">Add Subscriber</h3>
        <form method="POST" action="/emails/subscribers/add">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="form-row">
                <div class="form-group"><label>First Name</label><input type="text" name="first_name" class="form-control"></div>
                <div class="form-group"><label>Last Name</label><input type="text" name="last_name" class="form-control"></div>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 1rem;">
                <button type="button" onclick="document.getElementById('addModal').style.display='none'" class="btn" style="background: rgba(107,114,128,0.1); color: #9ca3af; border: none; padding: 0.5rem 1rem; border-radius: 8px;">Cancel</button>
                <button type="submit" class="btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.active { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.unsubscribed { color: #ef5350; background: rgba(239,83,80,0.04); } .form-row { display: flex; gap: 1rem; margin-bottom: 1rem; }.form-row .form-group { flex: 1; margin-bottom: 0; }.form-group { margin-bottom: 1rem; }.form-control { width: 100%; padding: 0.8rem 1rem; background: rgba(0,0,0,0.2); border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; color: #f5f0eb; font-family: 'Inter', sans-serif; }</style>
