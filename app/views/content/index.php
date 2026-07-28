<?php $pageTitle = 'Content Management'; ?>
<div class="topbar"><div class="greeting"><h1>Content Management</h1><p>Manage site content</p></div>
    <form method="GET" style="display: flex;">
        <select name="page" onchange="window.location='/content?page='+this.value" style="padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06); background: rgba(0,0,0,0.2); color: #f5f0eb;">
            <option value="home">Home</option>
            <option value="about">About</option>
            <option value="services">Services</option>
            <option value="contact">Contact</option>
        </select>
    </form>
</div>
<div class="table-section reveal">
    <div class="header"><h3>Content for: <?= ucfirst($currentPage) ?> (<?= count($contents) ?> sections)</h3></div>
    <table>
        <thead><tr><th>Key</th><th>Title</th><th>Type</th><th>Status</th><th>Last Updated</th><th>Actions</th></tr></thead>
        <tbody>
            <?php if (empty($contents)): ?>
                <tr><td colspan="6" style="text-align: center; color: rgba(245,240,235,0.3);">No content sections found</td></tr>
            <?php else: ?>
                <?php foreach ($contents as $c): ?>
                    <tr>
                        <td><?= sanitize($c['key']) ?></td>
                        <td><?= sanitize($c['title'] ?? '-') ?></td>
                        <td><?= ucfirst($c['type'] ?? 'html') ?></td>
                        <td><span class="status <?= $c['is_active'] ? 'active' : 'inactive' ?>"><?= $c['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                        <td><?= formatDate($c['updated_at']) ?></td>
                        <td><a href="/content/<?= $c['key'] ?>/edit" style="color: #f59e0b;"><i class="fas fa-edit"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<style>.table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; } .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } table { width: 100%; border-collapse: collapse; font-size: 0.85rem; } table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); } table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); } table tr:hover td { background: rgba(245,240,235,0.01); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.active { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.inactive { color: #ef5350; background: rgba(239,83,80,0.04); }</style>
