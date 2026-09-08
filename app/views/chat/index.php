<?php $pageTitle = 'Virtual Office & Chat'; ?>

<div class="card mb-4">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.25rem;">
                <i class="fas fa-comments" style="color: var(--primary); margin-right: 0.5rem;"></i> Virtual Office & Team Communications
            </h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Internal messaging channels, live team presence, and video meeting rooms</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/meetings/create') ?>" class="btn btn-primary">
                <i class="fas fa-video"></i> Schedule Meeting
            </a>
            <a href="<?= eurl('/meetings') ?>" class="btn btn-outline">
                <i class="fas fa-list"></i> All Meetings
            </a>
            <a href="<?= eurl('/chat/online') ?>" class="btn btn-secondary">
                <i class="fas fa-user-clock"></i> Active Presence
            </a>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
    <!-- Chat Rooms -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Chat Channels (<?= count($rooms) ?>)</h3>
            <span class="period" style="font-size: 0.75rem; color: var(--text-muted);">Active channels</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 350px; overflow-y: auto;">
            <?php if (empty($rooms)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">No chat rooms available.</p>
            <?php else: ?>
                <?php foreach ($rooms as $room): ?>
                    <a href="<?= eurl('/chat/room/' . $room['id']) ?>" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border); text-decoration: none; transition: border-color 0.2s;">
                        <div style="width: 38px; height: 38px; border-radius: 10px; background: var(--primary-light); display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 0.95rem;">
                            <i class="fas fa-<?= $room['type'] === 'broadcast' ? 'bullhorn' : ($room['type'] === 'private' ? 'user' : 'users') ?>"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem;"><?= sanitize($room['name']) ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: capitalize;"><?= sanitize($room['type']) ?> Channel</div>
                        </div>
                        <i class="fas fa-chevron-right" style="color: var(--text-light); font-size: 0.8rem;"></i>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Active Personnel -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Online Team (<?= count($onlineUsers) ?>)</h3>
            <span class="period" style="font-size: 0.75rem; color: var(--success); font-weight: 700;">● Active Now</span>
        </div>
        <div style="display: flex; flex-direction: column; gap: 0.5rem; max-height: 350px; overflow-y: auto;">
            <?php if (empty($onlineUsers)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 2rem 0;">No team members currently active.</p>
            <?php else: ?>
                <?php foreach ($onlineUsers as $ou): ?>
                    <div style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                        <div style="position: relative; width: 38px; height: 38px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                            <?= strtoupper(substr($ou['first_name'], 0, 1)) ?>
                            <span style="position: absolute; bottom: 0; right: 0; width: 9px; height: 9px; border-radius: 50%; background: #10b981; border: 2px solid var(--bg-card);"></span>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.88rem;"><?= sanitize($ou['first_name'] . ' ' . $ou['last_name']) ?></div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: capitalize;"><?= sanitize($ou['work_status'] ?? 'Available') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
