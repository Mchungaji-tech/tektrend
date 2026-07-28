<?php $pageTitle = 'Virtual Office'; ?>
<div class="topbar"><div class="greeting"><h1>Virtual Office</h1><p>Chat and teleconferencing</p></div>
    <a href="/meetings/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-video"></i> New Meeting</a>
</div>
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <div class="chart-card reveal">
        <div class="header"><h3>Chat Rooms</h3><span class="period"><?= count($rooms) ?> rooms</span></div>
        <div style="margin-top: 1rem; max-height: 300px; overflow-y: auto;">
            <?php if (empty($rooms)): ?>
                <p style="color: rgba(245,240,235,0.3);">No chat rooms available</p>
            <?php else: ?>
                <?php foreach ($rooms as $room): ?>
                    <a href="/chat/room/<?= $room['id'] ?>" style="display: flex; align-items: center; gap: 0.8rem; padding: 0.6rem 0; border-bottom: 1px solid rgba(245,240,235,0.02); color: #f5f0eb; text-decoration: none;">
                        <div style="width: 40px; height: 40px; border-radius: 12px; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c;"><i class="fas fa-<?= $room['type'] === 'broadcast' ? 'bullhorn' : ($room['type'] === 'private' ? 'user' : 'users') ?>"></i></div>
                        <div style="flex: 1;"><div style="font-weight: 500;"><?= sanitize($room['name']) ?></div><div style="font-size: 0.7rem; color: rgba(245,240,235,0.3); text-transform: capitalize;"><?= $room['type'] ?></div></div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="chart-card reveal">
        <div class="header"><h3>Online Users (<?= count($onlineUsers) ?>)</h3><span class="period">Active now</span></div>
        <div style="margin-top: 1rem; max-height: 300px; overflow-y: auto;">
            <?php if (empty($onlineUsers)): ?>
                <p style="color: rgba(245,240,235,0.3);">No users online</p>
            <?php else: ?>
                <?php foreach ($onlineUsers as $ou): ?>
                    <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c;"><i class="fas fa-user"></i></div>
                        <div style="flex: 1;"><div style="font-weight: 500;"><?= sanitize($ou['first_name'] . ' ' . $ou['last_name']) ?></div><div style="font-size: 0.7rem; color: rgba(245,240,235,0.3); text-transform: capitalize;"><?= $ou['work_status'] ?></div></div>
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="chart-card reveal" style="margin-top: 1.5rem;">
    <div class="header"><h3>Upcoming Meetings</h3><a href="/meetings" class="action">View All</a></div>
    <div style="margin-top: 1rem;">
        <a href="/meetings/create" style="display: block; padding: 0.8rem; background: rgba(184,148,60,0.06); border-radius: 12px; color: #f5f0eb; text-decoration: none; text-align: center; border: 1px solid rgba(184,148,60,0.1);"><i class="fas fa-plus"></i> Schedule a new meeting</a>
    </div>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .chart-card .header .period { font-size: 0.7rem; color: rgba(245,240,235,0.15); background: rgba(245,240,235,0.02); padding: 0.2rem 1rem; border-radius: 40px; border: 1px solid rgba(245,240,235,0.02); } .chart-card .header .action { font-size: 0.7rem; color: #b8943c; cursor: pointer; }</style>
