<?php $pageTitle = 'Dashboard'; ?>

<div class="topbar">
    <div class="greeting">
        <h1>Welcome back, <?= sanitize($user['first_name']) ?>!</h1>
        <p>Your virtual office is ready. Here's what's happening today.</p>
    </div>
    <div class="actions">
        <span class="date"><i class="far fa-calendar"></i> <?= date('M j, Y g:i A') ?></span>
        <span class="notif" id="notifBtn"><i class="far fa-bell"></i>
            <?php
            $notifCount = 0;
            if (!empty($tasksDueSoon)) $notifCount += count($tasksDueSoon);
            if (!empty($upcomingEvents)) $notifCount += count($upcomingEvents);
            if ($notifCount > 0): ?>
                <span class="badge"><?= $notifCount ?></span>
            <?php endif; ?>
        </span>
        <div class="avatar" id="avatarBtn">
            <i class="fas fa-user"></i>
            <span class="status-dot" style="background: #10b981;"></span>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card reveal">
        <div class="label">Total Revenue</div>
        <div class="value"><?= formatCurrency($stats['total_revenue']) ?></div>
        <span class="change up"><i class="fas fa-arrow-up"></i> This month: <?= formatCurrency($stats['revenue_this_month']) ?></span>
    </div>
    <div class="stat-card reveal">
        <div class="label">Active Users</div>
        <div class="value"><?= $stats['total_users'] ?></div>
        <span class="change up"><i class="fas fa-user"></i> Online: <?= $stats['online_users'] ?></span>
    </div>
    <div class="stat-card reveal">
        <div class="label">Total Leads</div>
        <div class="value"><?= $stats['total_leads'] ?></div>
        <span class="change up"><i class="fas fa-bullseye"></i> This month: <?= $stats['leads_this_month'] ?></span>
    </div>
    <div class="stat-card reveal">
        <div class="label">Net Profit</div>
        <div class="value"><?= formatCurrency($financialSummary['net_profit']) ?></div>
        <span class="change <?= ($financialSummary['net_profit'] >= 0 ? 'up' : 'down') ?>"><i class="fas fa-<?= ($financialSummary['net_profit'] >= 0 ? 'arrow-up' : 'arrow-down') ?>"></i> Expenses: <?= formatCurrency($financialSummary['total_expenses']) ?></span>
    </div>
    <div class="stat-card reveal">
        <div class="label">Open Invoices</div>
        <div class="value"><?= $stats['open_invoices'] ?></div>
        <span class="change <?= ($stats['overdue_invoices'] > 0 ? 'down' : 'up') ?>"><i class="fas fa-file-invoice"></i> Overdue: <?= $stats['overdue_invoices'] ?></span>
    </div>
    <div class="stat-card reveal">
        <div class="label">Tasks Completed</div>
        <div class="value"><?= $stats['tasks_completed'] ?></div>
        <span class="change up"><i class="fas fa-tasks"></i> In Progress: <?= $stats['tasks_in_progress'] ?></span>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Budget Progress -->
    <div class="chart-card reveal">
        <div class="header">
            <h3>Budget Overview</h3>
            <span class="period">Active budgets</span>
        </div>
        <div style="margin-top: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                <span>Planned: <?= formatCurrency($stats['total_budget']) ?></span>
                <span>Spent: <?= formatCurrency($stats['total_spent']) ?></span>
                <span>Remaining: <?= formatCurrency($stats['total_budget'] - $stats['total_spent']) ?></span>
            </div>
            <?php $budgetPct = $stats['total_budget'] > 0 ? ($stats['total_spent'] / $stats['total_budget']) * 100 : 0; ?>
            <div style="background: rgba(245,240,235,0.02); border-radius: 10px; height: 20px; overflow: hidden;">
                <div style="height: 100%; background: linear-gradient(90deg, #b8943c, #4caf50); width: <?= min($budgetPct, 100) ?>%; transition: width 0.5s;"></div>
            </div>
            <div style="margin-top: 0.5rem; font-size: 0.8rem; color: rgba(245,240,235,0.3);">
                <?= round($budgetPct, 1) ?>% of budget utilized
            </div>
        </div>
    </div>

    <!-- Online Users -->
    <div class="chart-card reveal">
        <div class="header">
            <h3>Online Now (<?= count($onlineUsers) ?>)</h3>
            <span class="period">Active in last 5 min</span>
        </div>
        <div style="margin-top: 1rem; max-height: 200px; overflow-y: auto;">
            <?php if (empty($onlineUsers)): ?>
                <p style="color: rgba(245,240,235,0.3); font-size: 0.85rem;">No users online</p>
            <?php else: ?>
                <?php foreach ($onlineUsers as $ou): ?>
                    <div style="display: flex; align-items: center; gap: 0.8rem; padding: 0.5rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(184,148,60,0.06); display: flex; align-items: center; justify-content: center; color: #b8943c;">
                            <i class="fas fa-user"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 500;"><?= sanitize($ou['first_name'] . ' ' . $ou['last_name']) ?></div>
                            <div style="font-size: 0.7rem; color: rgba(245,240,235,0.3); text-transform: capitalize;"><?= $ou['work_status'] ?></div>
                        </div>
                        <div style="width: 10px; height: 10px; border-radius: 50%; background: #10b981;"></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Lead Summary & Upcoming Events -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Lead Pipeline -->
    <div class="chart-card reveal">
        <div class="header">
            <h3>Lead Pipeline</h3>
            <span class="period">By status</span>
        </div>
        <div style="margin-top: 1rem;">
            <?php
            $leadColors = [
                'new' => '#6366f1', 'contacted' => '#3b82f6', 'qualified' => '#10b981',
                'proposal' => '#f59e0b', 'negotiation' => '#f97316', 'closed_won' => '#4caf50',
                'closed_lost' => '#ef4444'
            ];
            foreach ($leadSummary as $status => $count):
                $color = $leadColors[$status] ?? '#6b7280';
            ?>
                <div style="display: flex; align-items: center; gap: 0.8rem; margin-bottom: 0.6rem;">
                    <div style="width: 12px; height: 12px; border-radius: 50%; background: <?= $color ?>;"></div>
                    <div style="flex: 1; font-size: 0.8rem; text-transform: capitalize;"><?= str_replace('_', ' ', $status) ?></div>
                    <div style="font-weight: 600;"><?= $count ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Upcoming Events -->
    <div class="chart-card reveal">
        <div class="header">
            <h3>Upcoming Events</h3>
            <span class="period">Next 10 events</span>
        </div>
        <div style="margin-top: 1rem; max-height: 250px; overflow-y: auto;">
            <?php if (empty($upcomingEvents)): ?>
                <p style="color: rgba(245,240,235,0.3); font-size: 0.85rem;">No upcoming events</p>
            <?php else: ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div style="padding: 0.6rem 0; border-bottom: 1px solid rgba(245,240,235,0.02);">
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <div style="font-weight: 500;"><?= sanitize($event['title']) ?></div>
                                <div style="font-size: 0.7rem; color: rgba(245,240,235,0.3);">
                                    <i class="far fa-clock"></i> <?= formatDateTime($event['start_datetime']) ?>
                                </div>
                            </div>
                            <span class="status <?= $event['status'] ?>" style="font-size: 0.6rem;"><?= ucfirst($event['status']) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Tasks Due Soon -->
<div class="table-section reveal">
    <div class="header">
        <h3>Tasks Due Soon</h3>
        <a href="/tasks" class="action">View All <i class="fas fa-arrow-right"></i></a>
    </div>
    <table>
        <thead>
            <tr>
                <th>Task</th>
                <th>Assignee</th>
                <th>Priority</th>
                <th>Due Date</th>
                <th>Progress</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($tasksDueSoon)): ?>
                <tr><td colspan="5" style="text-align: center; color: rgba(245,240,235,0.3);">No tasks due soon</td></tr>
            <?php else: ?>
                <?php foreach ($tasksDueSoon as $task): ?>
                    <tr>
                        <td><?= sanitize($task['title']) ?></td>
                        <td><?= sanitize(($task['first_name'] ?? '') . ' ' . ($task['last_name'] ?? '')) ?: 'Unassigned' ?></td>
                        <td><span class="status <?= $task['priority'] ?>"><?= ucfirst($task['priority']) ?></span></td>
                        <td><?= formatDateTime($task['due_date']) ?></td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="flex: 1; background: rgba(245,240,235,0.02); border-radius: 10px; height: 6px; overflow: hidden;">
                                    <div style="height: 100%; background: #b8943c; width: <?= $task['progress'] ?>%;"></div>
                                </div>
                                <span style="font-size: 0.7rem;"><?= $task['progress'] ?>%</span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem; }
    .stat-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.5rem 1.8rem; border: 1px solid rgba(245,240,235,0.03); transition: 0.3s; }
    .stat-card:hover { background: rgba(245,240,235,0.03); border-color: rgba(184,148,60,0.04); }
    .stat-card .label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.8px; color: rgba(245,240,235,0.15); font-weight: 600; }
    .stat-card .value { font-size: 2.2rem; font-weight: 800; color: #f5f0eb; margin: 0.3rem 0 0.5rem; }
    .stat-card .change { font-size: 0.75rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.3rem; padding: 0.1rem 0.8rem; border-radius: 40px; }
    .stat-card .change.up { color: #4caf50; background: rgba(76,175,80,0.04); }
    .stat-card .change.down { color: #ef5350; background: rgba(239,83,80,0.04); }
    .chart-card { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); }
    .chart-card .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    .chart-card .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }
    .chart-card .header .period { font-size: 0.7rem; color: rgba(245,240,235,0.15); background: rgba(245,240,235,0.02); padding: 0.2rem 1rem; border-radius: 40px; border: 1px solid rgba(245,240,235,0.02); }
    .table-section { background: rgba(245,240,235,0.02); border-radius: 20px; padding: 1.8rem; border: 1px solid rgba(245,240,235,0.03); overflow-x: auto; }
    .table-section .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 0.5rem; }
    .table-section .header h3 { font-size: 1rem; font-weight: 600; color: #f5f0eb; }
    .table-section .header .action { font-size: 0.7rem; color: #b8943c; cursor: pointer; transition: 0.3s; }
    .table-section .header .action:hover { color: #f5f0eb; }
    table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
    table th { text-align: left; padding: 0.8rem 0.5rem; color: rgba(245,240,235,0.15); font-weight: 600; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.8px; border-bottom: 1px solid rgba(245,240,235,0.03); }
    table td { padding: 0.8rem 0.5rem; border-bottom: 1px solid rgba(245,240,235,0.02); color: rgba(245,240,235,0.5); }
    table tr:hover td { background: rgba(245,240,235,0.01); }
    table .status { display: inline-block; padding: 0.1rem 0.8rem; border-radius: 40px; font-size: 0.6rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    table .status.high { color: #ef5350; background: rgba(239,83,80,0.04); }
    table .status.medium { color: #f59e0b; background: rgba(245,159,11,0.04); }
    table .status.low { color: #3b82f6; background: rgba(59,130,246,0.04); }
    table .status.urgent { color: #ef4444; background: rgba(239,68,68,0.04); }
    table .status.completed { color: #4caf50; background: rgba(76,175,80,0.04); }
    table .status.processing { color: #b8943c; background: rgba(184,148,60,0.04); }
    table .status.scheduled { color: #3b82f6; background: rgba(59,130,246,0.04); }
    table .status.in_progress { color: #f59e0b; background: rgba(245,159,11,0.04); }
</style>
