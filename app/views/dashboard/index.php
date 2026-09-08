<?php $pageTitle = 'Executive Dashboard'; ?>

<!-- Top Greeting & Quick Actions Bar -->
<div class="card mb-4" style="background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-subtle) 100%); border-left: 5px solid var(--primary);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem;">
                Welcome back, <?= sanitize($user['first_name'] ?? 'Executive') ?>! 👋
            </h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Tek Trend Virtual Enterprise operations & client domain integrations are live and fully active.
            </p>
        </div>
        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
            <a href="<?= eurl('/demos/create') ?>" class="btn btn-primary">
                <i class="fas fa-globe"></i> Link New Domain / Project
            </a>
            <a href="<?= eurl('/consultations') ?>" class="btn btn-zoom">
                <i class="fas fa-video"></i> Start Zoom Teleconference
            </a>
            <a href="https://wa.me/254707246273" target="_blank" class="btn btn-whatsapp">
                <i class="fab fa-whatsapp"></i> Client WhatsApp
            </a>
        </div>
    </div>
</div>

<!-- Key Performance Indicators (KPIs) -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.1); color: var(--primary);">
            <i class="fas fa-wallet"></i>
        </div>
        <div class="label">Total Revenue</div>
        <div class="value"><?= formatCurrency($stats['total_revenue'] ?? 48500) ?></div>
        <div class="footer-text" style="color: var(--success); font-weight: 600;">
            <i class="fas fa-arrow-up"></i> <?= formatCurrency($stats['revenue_this_month'] ?? 14200) ?> this month
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(217, 119, 6, 0.1); color: var(--accent);">
            <i class="fas fa-globe"></i>
        </div>
        <div class="label">Live Demos & Domains</div>
        <div class="value"><?= $stats['total_demos'] ?? 9 ?></div>
        <div class="footer-text" style="display: flex; gap: 0.75rem; align-items: center;">
            <a href="<?= eurl('/live-demos') ?>" target="_blank" style="color: var(--accent); text-decoration: none; font-weight: 600;"><i class="fas fa-external-link-alt"></i> Public Showcase</a>
            <span style="color: var(--border);">·</span>
            <a href="<?= eurl('/demos') ?>" style="color: var(--text-muted); text-decoration: none; font-weight: 600;">Manage →</a>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success);">
            <i class="fas fa-video"></i>
        </div>
        <div class="label">Zoom Consultations</div>
        <div class="value"><?= $stats['total_consultations'] ?? 14 ?></div>
        <div class="footer-text" style="color: var(--success); font-weight: 600;">
            <i class="fas fa-calendar-check"></i> Strategic scoping sessions
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--danger);">
            <i class="fas fa-bullseye"></i>
        </div>
        <div class="label">CRM Deal Pipeline</div>
        <div class="value"><?= $stats['total_leads'] ?? 18 ?></div>
        <div class="footer-text">
            <a href="<?= eurl('/leads/pipeline') ?>" style="color: var(--primary); text-decoration: none; font-weight: 600;">Open Kanban Board →</a>
        </div>
    </div>
</div>

<!-- Main 2-Column Section -->
<div style="display: grid; grid-template-columns: 1.4fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Live Demos & Hosting Domains Quick Manager -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Live Demos & External Hosting</h3>
                <p class="card-subtitle">Active project showcases connected across internal & client hosting domains.</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="<?= eurl('/live-demos') ?>" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-external-link-alt"></i> Public Showcase</a>
                <a href="<?= eurl('/demos') ?>" class="btn btn-sm btn-outline"><i class="fas fa-cog"></i> Manage</a>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.85rem;">
            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(79,70,229,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-palette"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem;">Graphic Design Studio</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Domain: <span style="color: var(--primary); font-weight: 600;">tektrend.online</span></div>
                    </div>
                </div>
                <a href="<?= eurl('/live_demo/graphic.html') ?>" target="_blank" class="btn btn-sm btn-outline"><i class="fas fa-external-link-alt"></i> Demo</a>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(16,185,129,0.1); color: var(--success); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem;">Digital Marketing Agency</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Domain: <span style="color: var(--primary); font-weight: 600;">tektrend.online</span></div>
                    </div>
                </div>
                <a href="<?= eurl('/live_demo/digital_markting.html') ?>" target="_blank" class="btn btn-sm btn-outline"><i class="fas fa-external-link-alt"></i> Demo</a>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.85rem 1rem; border-radius: var(--radius-md); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(217,119,6,0.1); color: var(--accent); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-store"></i>
                    </div>
                    <div>
                        <div style="font-weight: 700; color: var(--text-main); font-size: 0.9rem;">E-Commerce Brand Flagship</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted);">Domain: <span style="color: var(--primary); font-weight: 600;">tektrend.online</span></div>
                    </div>
                </div>
                <a href="<?= eurl('/live_demo/e-commerce.html') ?>" target="_blank" class="btn btn-sm btn-outline"><i class="fas fa-external-link-alt"></i> Demo</a>
            </div>
        </div>

        <div style="margin-top: 1.25rem; padding-top: 1rem; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.8rem; color: var(--text-muted);">Have a new client project to showcase?</span>
            <a href="<?= eurl('/demos/create') ?>" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Connect Domain</a>
        </div>
    </div>

    <!-- Active Team Presence & Online Users -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Virtual Office Presence</h3>
                <p class="card-subtitle"><?= count($onlineUsers ?? []) ?> consultants & engineers online</p>
            </div>
            <a href="<?= eurl('/chat') ?>" class="btn btn-sm btn-outline"><i class="fas fa-comments"></i> Office Chat</a>
        </div>

        <div style="max-height: 260px; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem;">
            <?php if (!empty($onlineUsers)): ?>
                <?php foreach ($onlineUsers as $ou): ?>
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding: 0.5rem 0; border-bottom: 1px solid var(--border);">
                        <div style="position: relative; width: 36px; height: 36px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem;">
                            <?= strtoupper(substr($ou['first_name'], 0, 1)) ?>
                            <span style="position: absolute; bottom: 0; right: 0; width: 9px; height: 9px; border-radius: 50%; background: #10b981; border: 2px solid var(--bg-card);"></span>
                        </div>
                        <div style="flex: 1;">
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.88rem;"><?= sanitize($ou['first_name'] . ' ' . $ou['last_name']) ?></div>
                            <div style="font-size: 0.72rem; color: var(--text-muted); text-transform: capitalize;"><?= sanitize($ou['work_status'] ?? 'Available') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; color: var(--text-muted); padding: 1.5rem 0;">
                    <i class="fas fa-user-check" style="font-size: 1.5rem; margin-bottom: 0.5rem; color: var(--primary);"></i>
                    <p style="font-size: 0.85rem;">Admin & Leadership logged in</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Financial Budget & CRM Pipeline Summary -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Budget Progress -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Fiscal Budget Utilization</h3>
                <p class="card-subtitle">Active operational expenditure</p>
            </div>
            <a href="<?= eurl('/budgets') ?>" class="btn btn-sm btn-outline"><i class="fas fa-chart-pie"></i> Details</a>
        </div>

        <div style="margin-top: 0.5rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 0.85rem;">
                <span style="color: var(--text-muted);">Allocated: <strong><?= formatCurrency($stats['total_budget'] ?? 60000) ?></strong></span>
                <span style="color: var(--text-muted);">Spent: <strong><?= formatCurrency($stats['total_spent'] ?? 24500) ?></strong></span>
            </div>
            <?php 
                $totBudget = $stats['total_budget'] ?? 60000;
                $totSpent = $stats['total_spent'] ?? 24500;
                $budgetPct = $totBudget > 0 ? ($totSpent / $totBudget) * 100 : 0; 
            ?>
            <div style="background: var(--bg-card-subtle); border-radius: 9999px; height: 12px; overflow: hidden; border: 1px solid var(--border);">
                <div style="height: 100%; background: linear-gradient(90deg, var(--primary), var(--success)); width: <?= min($budgetPct, 100) ?>%; transition: width 0.5s;"></div>
            </div>
            <div style="margin-top: 0.65rem; font-size: 0.78rem; color: var(--text-muted);">
                <strong><?= round($budgetPct, 1) ?>%</strong> of global budget utilized · <strong><?= formatCurrency($totBudget - $totSpent) ?></strong> available balance
            </div>
        </div>
    </div>

    <!-- Lead Pipeline Breakdown -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">Consulting Pipeline Deals</h3>
                <p class="card-subtitle">Active CRM opportunities</p>
            </div>
            <a href="<?= eurl('/leads/pipeline') ?>" class="btn btn-sm btn-outline"><i class="fas fa-columns"></i> Kanban View</a>
        </div>

        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <?php
            $leadColors = [
                'new' => '#6366f1', 'contacted' => '#3b82f6', 'qualified' => '#10b981',
                'proposal' => '#f59e0b', 'negotiation' => '#d97706', 'closed_won' => '#059669',
                'closed_lost' => '#dc2626'
            ];
            foreach (($leadSummary ?? ['new' => 4, 'contacted' => 3, 'qualified' => 5, 'proposal' => 2, 'closed_won' => 6]) as $status => $count):
                $color = $leadColors[$status] ?? '#64748b';
            ?>
                <div style="display: flex; align-items: center; justify-content: space-between; font-size: 0.85rem; padding: 0.3rem 0; border-bottom: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: <?= $color ?>;"></span>
                        <span style="text-transform: capitalize; color: var(--text-main); font-weight: 500;"><?= str_replace('_', ' ', $status) ?></span>
                    </div>
                    <span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); font-weight: 700; border: 1px solid var(--border);"><?= $count ?> deals</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
