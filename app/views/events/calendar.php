<?php $pageTitle = 'Calendar'; ?>
<div class="topbar"><div class="greeting"><h1>Calendar</h1><p>View events on calendar</p></div>
    <a href="/events/create" style="color: #b8943c; background: rgba(184,148,60,0.1); padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none;"><i class="fas fa-plus"></i> Add Event</a>
</div>
<div class="chart-card reveal">
    <div class="header"><h3>Upcoming Events</h3><span class="period"><?= count($events) ?> events</span></div>
    <div style="margin-top: 1rem;">
        <?php if (empty($events)): ?>
            <p style="color: rgba(245,240,235,0.3);">No upcoming events</p>
        <?php else: ?>
            <?php foreach ($events as $e): ?>
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.8rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                    <div style="width: 50px; text-align: center;">
                        <div style="font-size: 1.2rem; font-weight: 700; color: #b8943c;"><?= date('M', strtotime($e['start_datetime'])) ?></div>
                        <div style="font-size: 1.5rem; font-weight: 700;"><?= date('d', strtotime($e['start_datetime'])) ?></div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-weight: 500; color: #f5f0eb;"><?= sanitize($e['title']) ?></div>
                        <div style="font-size: 0.7rem; color: rgba(245,240,235,0.3);">
                            <i class="far fa-clock"></i> <?= formatDateTime($e['start_datetime']) ?>
                            <?php if ($e['location']): ?><span style="margin-left: 1rem;"><i class="fas fa-map-marker-alt"></i> <?= sanitize($e['location']) ?></span><?php endif; ?>
                        </div>
                    </div>
                    <span class="status <?= $e['status'] ?>"><?= ucfirst($e['status']) ?></span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<style>.chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); } .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; } .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; } .chart-card .header .period { font-size: 0.7rem; color: rgba(245,240,235,0.15); background: rgba(245,240,235,0.02); padding: 0.2rem 1rem; border-radius: 40px; border: 1px solid rgba(245,240,235,0.02); } table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; } table .status.scheduled { color: #f59e0b; background: rgba(245,159,11,0.04); } table .status.in_progress { color: #10b981; background: rgba(16,185,129,0.04); } table .status.completed { color: #4caf50; background: rgba(76,175,80,0.04); } table .status.cancelled { color: #ef4444; background: rgba(239,68,68,0.04); }</style>
