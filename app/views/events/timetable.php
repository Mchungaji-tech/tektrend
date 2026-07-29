<?php $pageTitle = 'Timetable'; ?>
<div class="table-section reveal">
    <div class="header">
        <h3>Weekly Timetable</h3>
        <span class="period">Department schedules</span>
    </div>
    <div style="margin-top: 1rem;">
        <?php if (empty($slots)): ?>
            <p style="color: rgba(245,240,235,0.3); font-size: 0.85rem;">No timetable slots scheduled.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Day</th>
                        <th>Time</th>
                        <th>Title</th>
                        <th>Department</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($slots as $slot): ?>
                        <tr>
                            <td><?= sanitize($slot['day_of_week']) ?></td>
                            <td><?= sanitize($slot['start_time'] . ' - ' . $slot['end_time']) ?></td>
                            <td><?= sanitize($slot['title']) ?></td>
                            <td><?= sanitize($slot['department_name'] ?? '-') ?></td>
                            <td><?= sanitize($slot['description'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
