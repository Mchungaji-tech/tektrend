<?php 
$pageTitle = 'Virtual Company Operations Console'; 
$currentWorkStatus = $user['work_status'] ?? 'working';
?>

<!-- Virtual Company Command Ribbon -->
<div class="card mb-4" style="background: linear-gradient(135deg, var(--bg-card) 0%, var(--bg-card-subtle) 100%); border-left: 5px solid var(--primary); padding: 1.5rem 1.75rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 0.35rem;">
                <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); border: 1px solid rgba(16, 185, 129, 0.3); font-weight: 700;">
                    <i class="fas fa-circle" style="font-size: 0.55rem; animation: pulseDot 2s infinite;"></i> Virtual HQ Online
                </span>
                <span style="font-size: 0.8rem; color: var(--text-muted);">
                    <i class="fas fa-clock" style="margin-right: 0.25rem;"></i> <?= date('H:i') ?> EAT · Eldoret HQ & Remote Operations
                </span>
            </div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.02em;">
                Tek Trend Virtual Company Operations
            </h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin-top: 0.2rem;">
                Logged in as <strong><?= sanitize($user['first_name'] . ' ' . $user['last_name']) ?></strong> (<?= sanitize($user['position'] ?? ucfirst($user['role'])) ?>)
            </p>
        </div>

        <!-- Work Status Switcher & Command Actions -->
        <div style="display: flex; flex-direction: column; gap: 0.75rem; align-items: flex-end;">
            <!-- Work Status Switcher Pills -->
            <form method="POST" action="<?= eurl('/work-status') ?>" style="display: flex; align-items: center; gap: 0.35rem; background: var(--bg-card); padding: 0.3rem 0.5rem; border-radius: 9999px; border: 1px solid var(--border);">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <span style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); margin: 0 0.4rem;">Status:</span>
                
                <button type="submit" name="status" value="working" class="status-pill-btn <?= $currentWorkStatus === 'working' ? 'active working' : '' ?>" title="Working">
                    <span class="dot" style="background: #10b981;"></span> Working
                </button>
                <button type="submit" name="status" value="meeting" class="status-pill-btn <?= $currentWorkStatus === 'meeting' ? 'active meeting' : '' ?>" title="In Meeting">
                    <span class="dot" style="background: #8b5cf6;"></span> Meeting
                </button>
                <button type="submit" name="status" value="break" class="status-pill-btn <?= $currentWorkStatus === 'break' ? 'active break' : '' ?>" title="On Break">
                    <span class="dot" style="background: #f59e0b;"></span> Break
                </button>
                <button type="submit" name="status" value="away" class="status-pill-btn <?= $currentWorkStatus === 'away' ? 'active away' : '' ?>" title="Away">
                    <span class="dot" style="background: #94a3b8;"></span> Away
                </button>
            </form>

            <!-- Quick Jump Shortcuts -->
            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                <a href="<?= eurl('/content') ?>" class="btn btn-primary btn-sm" style="box-shadow: 0 2px 8px rgba(79,70,229,0.3);">
                    <i class="fas fa-magic"></i> Front-End CMS Studio
                </a>
                <a href="<?= eurl('/invoices/create') ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-plus"></i> New Invoice
                </a>
                <a href="<?= eurl('/leads/create') ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-user-plus"></i> New Lead
                </a>
                <a href="<?= eurl('/consultations') ?>" class="btn btn-zoom btn-sm">
                    <i class="fas fa-video"></i> Zoom Boardroom
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Virtual Company Key Vitals (KPI Grid) -->
<div class="stats-grid mb-4">
    <!-- Revenue & Invoicing Pulse -->
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(79, 70, 229, 0.12); color: var(--primary);">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <div class="label">Total Invoiced Volume</div>
        <div class="value"><?= formatCurrency($stats['total_revenue'] ?? 48500) ?></div>
        <div class="footer-text" style="color: var(--success); font-weight: 600; display: flex; justify-content: space-between;">
            <span><i class="fas fa-arrow-up"></i> <?= formatCurrency($stats['revenue_this_month'] ?? 14200) ?> this month</span>
            <a href="<?= eurl('/invoices') ?>" style="color: var(--primary); text-decoration: none;">View Invoices →</a>
        </div>
    </div>

    <!-- CRM Sales Pipeline Pulse -->
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(245, 158, 11, 0.12); color: var(--accent);">
            <i class="fas fa-columns"></i>
        </div>
        <div class="label">Active CRM Pipeline</div>
        <div class="value"><?= formatCurrency($totalPipelineValue ?? 143000) ?></div>
        <div class="footer-text" style="display: flex; justify-content: space-between; align-items: center;">
            <span style="color: var(--text-muted); font-weight: 600;"><?= $stats['total_leads'] ?? 18 ?> Deals in Stages</span>
            <a href="<?= eurl('/leads/pipeline') ?>" style="color: var(--accent); text-decoration: none; font-weight: 700;">Open Kanban →</a>
        </div>
    </div>

    <!-- Live Demos & Domains -->
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(16, 185, 129, 0.12); color: var(--success);">
            <i class="fas fa-laptop-code"></i>
        </div>
        <div class="label">Turnkey Demos & Domains</div>
        <div class="value"><?= $portfolioDemosCount ?? 11 ?> Active</div>
        <div class="footer-text" style="display: flex; justify-content: space-between;">
            <a href="<?= eurl('/live-demos') ?>" target="_blank" style="color: var(--success); text-decoration: none; font-weight: 600;">
                <i class="fas fa-external-link-alt"></i> Public Showcase
            </a>
            <a href="<?= eurl('/demos') ?>" style="color: var(--text-muted); text-decoration: none;">Manage Domains →</a>
        </div>
    </div>

    <!-- Virtual Office Live Presence -->
    <div class="stat-card">
        <div class="stat-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
            <i class="fas fa-users-viewfinder"></i>
        </div>
        <div class="label">Corporate Team Online</div>
        <div class="value"><?= count($onlineUsers ?? []) ?: 4 ?> / <?= $stats['total_users'] ?? 6 ?></div>
        <div class="footer-text" style="display: flex; justify-content: space-between;">
            <span style="color: var(--success); font-weight: 600;"><i class="fas fa-circle" style="font-size: 0.6rem;"></i> Live Active</span>
            <a href="<?= eurl('/chat') ?>" style="color: #8b5cf6; text-decoration: none; font-weight: 600;">Open Office Chat →</a>
        </div>
    </div>
</div>

<!-- Virtual Company Department Wings (Virtual Corporate Floor) -->
<div class="card mb-4">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                <i class="fas fa-building" style="color: var(--primary); margin-right: 0.4rem;"></i> Virtual Company Corporate Wings
            </h3>
            <p class="card-subtitle">Active operational departments, appointed leadership, and live presence</p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/departments') ?>" class="btn btn-outline btn-sm"><i class="fas fa-sitemap"></i> Department Directory</a>
            <a href="<?= eurl('/employees') ?>" class="btn btn-outline btn-sm"><i class="fas fa-user-tie"></i> All Staff</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(310px, 1fr)); gap: 1rem;">
        <?php if (!empty($departments)): ?>
            <?php foreach ($departments as $dept): ?>
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-md); padding: 1.15rem; border-top: 4px solid <?= $dept['color'] ?? '#3b82f6' ?>; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                            <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.15rem;">
                                <?= sanitize($dept['name']) ?>
                            </h4>
                            <span class="badge" style="background: var(--bg-card); color: var(--text-main); border: 1px solid var(--border); font-size: 0.7rem;">
                                <i class="fas fa-users" style="color: <?= $dept['color'] ?? 'var(--primary)' ?>;"></i> <?= (int)$dept['employee_count'] ?> Staff
                            </span>
                        </div>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 0.85rem; line-height: 1.4;">
                            <?= sanitize($dept['description'] ?? 'Departmental operations and delivery.') ?>
                        </p>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; padding-top: 0.75rem; border-top: 1px solid var(--border); font-size: 0.8rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 24px; height: 24px; border-radius: 50%; background: <?= $dept['color'] ?? '#3b82f6' ?>; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700;">
                                <?= strtoupper(substr($dept['head_first_name'] ?? 'H', 0, 1)) ?>
                            </div>
                            <span style="color: var(--text-main); font-weight: 600;">
                                <?= sanitize(($dept['head_first_name'] ?? 'Lead') . ' ' . ($dept['head_last_name'] ?? '')) ?>
                            </span>
                        </div>
                        <a href="<?= eurl('/departments/' . $dept['id'] . '/edit') ?>" style="color: var(--text-muted); text-decoration: none; font-size: 0.75rem; font-weight: 600;">
                            Configure →
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: var(--text-muted); padding: 1.5rem; text-align: center;">No active corporate departments configured.</p>
        <?php endif; ?>
    </div>
</div>

<!-- 2-COLUMN OPERATIONAL SUITE: VIRTUAL CHAT ROOM & CRM PIPELINE -->
<div style="display: grid; grid-template-columns: 1.1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    
    <!-- LEFT: INTEGRATED VIRTUAL OFFICE CHAT ROOM -->
    <div class="card" style="display: flex; flex-direction: column; height: 560px;">
        <div class="card-header" style="padding-bottom: 0.75rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <h3 class="card-title">
                        <i class="fas fa-comments" style="color: var(--primary);"></i> Virtual Office Chat Room
                    </h3>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.15); color: var(--success); border: 1px solid rgba(16,185,129,0.3);">
                        <i class="fas fa-hashtag"></i> <?= sanitize($activeRoom['name'] ?? 'General') ?>
                    </span>
                </div>
                <p class="card-subtitle">Real-time team communication & announcement stream</p>
            </div>
            <a href="<?= eurl('/chat') ?>" class="btn btn-outline btn-sm">Full Screen Chat →</a>
        </div>

        <!-- Room Switcher Pills -->
        <div style="display: flex; gap: 0.4rem; padding: 0.5rem 0.75rem; background: var(--bg-card-subtle); border-radius: var(--radius-sm); border: 1px solid var(--border); margin-bottom: 0.75rem; overflow-x: auto;">
            <?php foreach ($chatRooms as $r): ?>
                <a href="<?= eurl('/chat/room/' . $r['id']) ?>" class="btn btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.6rem; border-radius: 9999px; <?= ($activeRoom['id'] ?? 0) === $r['id'] ? 'background: var(--primary); color: #fff;' : 'background: var(--bg-card); color: var(--text-muted); border: 1px solid var(--border);' ?>">
                    # <?= sanitize($r['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Live Message Scroll Container -->
        <div id="dashChatBox" style="flex: 1; overflow-y: auto; padding: 1rem; background: var(--bg-card-subtle); border-radius: var(--radius-md); border: 1px solid var(--border); display: flex; flex-direction: column; gap: 0.85rem;">
            <?php if (empty($recentChatMessages)): ?>
                <p style="color: var(--text-muted); text-align: center; margin: auto; font-size: 0.85rem;">
                    No recent messages in this channel. Say hello to the team below!
                </p>
            <?php else: ?>
                <?php foreach ($recentChatMessages as $m): ?>
                    <div style="display: flex; flex-direction: column; gap: 0.2rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 22px; height: 22px; border-radius: 50%; background: var(--primary-light); color: var(--primary); font-size: 0.65rem; font-weight: 700; display: flex; align-items: center; justify-content: center;">
                                <?= strtoupper(substr($m['first_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <span style="font-weight: 700; color: var(--text-main); font-size: 0.8rem;">
                                <?= sanitize(($m['first_name'] ?? 'User') . ' ' . ($m['last_name'] ?? '')) ?>
                            </span>
                            <span style="font-size: 0.68rem; color: var(--text-light); margin-left: auto;">
                                <?= timeAgo($m['created_at']) ?>
                            </span>
                        </div>
                        <div style="margin-left: 1.85rem; padding: 0.55rem 0.85rem; background: var(--bg-card); border-radius: var(--radius-sm); border: 1px solid var(--border); font-size: 0.85rem; color: var(--text-main); line-height: 1.4; width: fit-content; max-width: 90%;">
                            <?= nl2br(sanitize($m['message'])) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Inline Quick Chat Input Form -->
        <form method="POST" action="<?= eurl('/chat/room/' . ($activeRoom['id'] ?? 1) . '/send') ?>" style="margin-top: 0.75rem;">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <div style="display: flex; gap: 0.5rem;">
                <input type="text" name="message" class="form-control" placeholder="Quick message to #<?= sanitize($activeRoom['name'] ?? 'General') ?>..." required style="flex: 1; font-size: 0.85rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.55rem 0.95rem;">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- RIGHT: VISUAL CRM SALES PIPELINE SNAPSHOT -->
    <div class="card" style="display: flex; flex-direction: column; height: 560px;">
        <div class="card-header" style="padding-bottom: 0.75rem;">
            <div>
                <h3 class="card-title">
                    <i class="fas fa-chart-line" style="color: var(--accent);"></i> Sales Deal Pipeline
                </h3>
                <p class="card-subtitle">Active consultative deals & retainer negotiations</p>
            </div>
            <div style="display: flex; gap: 0.4rem;">
                <a href="<?= eurl('/leads/create') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> New Deal</a>
                <a href="<?= eurl('/leads/pipeline') ?>" class="btn btn-outline btn-sm">Full Kanban →</a>
            </div>
        </div>

        <!-- Pipeline Stages Tabs & Totals -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; margin-bottom: 0.75rem;">
            <?php 
            $stageHighlights = ['proposal', 'negotiation', 'closed_won'];
            foreach ($stageHighlights as $shKey): 
                $stObj = $stages[$shKey] ?? ['name' => ucfirst($shKey), 'total' => 0, 'deals' => [], 'color' => '#3b82f6'];
            ?>
                <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-left: 3px solid <?= $stObj['color'] ?>; border-radius: var(--radius-sm); padding: 0.5rem 0.75rem;">
                    <div style="font-size: 0.68rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">
                        <?= $stObj['name'] ?>
                    </div>
                    <div style="font-size: 1.05rem; font-weight: 800; color: var(--text-main); margin-top: 0.15rem;">
                        <?= formatCurrency($stObj['total']) ?>
                    </div>
                    <div style="font-size: 0.68rem; color: var(--text-light);"><?= count($stObj['deals']) ?> active deals</div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Deal Cards Stream -->
        <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column; gap: 0.65rem;">
            <?php 
            $allFlattenedDeals = [];
            foreach ($stages as $sk => $sData) {
                foreach ($sData['deals'] as $d) {
                    $d['stage_key'] = $sk;
                    $d['stage_name'] = $sData['name'];
                    $d['stage_color'] = $sData['color'];
                    $allFlattenedDeals[] = $d;
                }
            }
            ?>
            <?php if (empty($allFlattenedDeals)): ?>
                <p style="color: var(--text-muted); text-align: center; margin: auto; font-size: 0.85rem;">No active pipeline deals.</p>
            <?php else: ?>
                <?php foreach (array_slice($allFlattenedDeals, 0, 5) as $deal): ?>
                    <div style="background: var(--bg-card-subtle); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 0.85rem; display: flex; justify-content: space-between; align-items: center; gap: 0.75rem;">
                        <div>
                            <div style="font-weight: 700; color: var(--text-main); font-size: 0.88rem;">
                                <?= sanitize($deal['first_name'] . ' ' . $deal['last_name']) ?>
                            </div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 0.5rem; margin-top: 0.15rem;">
                                <span><i class="fas fa-building"></i> <?= sanitize($deal['company'] ?? 'Client') ?></span>
                                <span>·</span>
                                <span style="color: <?= $deal['stage_color'] ?>; font-weight: 700;"><?= $deal['stage_name'] ?></span>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <div style="font-weight: 800; color: var(--primary); font-size: 0.95rem;">
                                <?= formatCurrency($deal['value']) ?>
                            </div>
                            <form method="POST" action="<?= eurl('/leads/' . $deal['id'] . '/stage') ?>" style="margin-top: 0.25rem;">
                                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                <select name="stage" onchange="this.form.submit()" style="font-size: 0.7rem; padding: 0.2rem 0.4rem; border-radius: 4px; background: var(--bg-card); border: 1px solid var(--border);">
                                    <?php foreach ($stages as $stageKey => $st): ?>
                                        <option value="<?= $stageKey ?>" <?= $deal['stage_key'] === $stageKey ? 'selected' : '' ?>>
                                            <?= $st['name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- 2-COLUMN LOWER SUITE: INVOICING & CASH FLOW + FRONT-END CMS QUICK CONTROL -->
<div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
    
    <!-- LEFT: INVOICING & CASH FLOW SUITE -->
    <div class="card">
        <div class="card-header">
            <div>
                <h3 class="card-title">
                    <i class="fas fa-receipt" style="color: var(--success); margin-right: 0.4rem;"></i> Invoicing & Client Billing
                </h3>
                <p class="card-subtitle">Manage customer invoices, retainers, and settlement status</p>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <a href="<?= eurl('/invoices/create') ?>" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Create Invoice</a>
                <a href="<?= eurl('/invoices') ?>" class="btn btn-outline btn-sm">All Invoices →</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Invoice #</th>
                        <th>Client / Company</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th style="text-align: right;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentInvoices)): ?>
                        <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2rem;">No recent invoices recorded.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentInvoices as $inv): ?>
                            <tr>
                                <td>
                                    <strong style="font-family: monospace; color: var(--primary); font-size: 0.85rem;">
                                        <?= sanitize($inv['invoice_number']) ?>
                                    </strong>
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: var(--text-main); font-size: 0.85rem;">
                                        <?= sanitize(($inv['first_name'] ?? '') . ' ' . ($inv['last_name'] ?? '')) ?>
                                    </div>
                                    <div style="font-size: 0.72rem; color: var(--text-muted);">
                                        <?= sanitize($inv['company'] ?? '-') ?>
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: var(--text-main); font-size: 0.9rem;">
                                        <?= formatCurrency($inv['total']) ?>
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge <?= $inv['status'] ?>">
                                        <?= ucfirst($inv['status']) ?>
                                    </span>
                                </td>
                                <td style="color: var(--text-muted); font-size: 0.82rem;">
                                    <?= formatDate($inv['due_date']) ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= eurl('/invoices/' . $inv['id']) ?>" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" title="View Invoice">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= eurl('/invoices/' . $inv['id'] . '/pdf') ?>" target="_blank" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.55rem; font-size: 0.75rem;" title="Download PDF">
                                        <i class="fas fa-file-pdf" style="color: var(--danger);"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- RIGHT: FRONT-END CMS & PHOTO CONTROL HUB -->
    <div class="card" style="display: flex; flex-direction: column; justify-content: space-between;">
        <div>
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="fas fa-magic" style="color: var(--primary); margin-right: 0.4rem;"></i> Front-End Visual CMS Hub
                    </h3>
                    <p class="card-subtitle">Real-time control over public landing page copy and photos</p>
                </div>
                <a href="<?= eurl('/content') ?>" class="btn btn-primary btn-sm"><i class="fas fa-sliders-h"></i> Open Studio</a>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: var(--radius-sm); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <i class="fas fa-heading" style="color: var(--primary);"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-main);">Hero Section & Kinetic Brand Letters</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);"><?= sanitize(cms('hero_brand_letters', 'TEKTREND')) ?> · Main Headings</div>
                        </div>
                    </div>
                    <a href="<?= eurl('/content?tab=hero') ?>" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">Edit</a>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: var(--radius-sm); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <i class="fas fa-cubes" style="color: #06b6d4;"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-main);">4 Core Capabilities & Services</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Custom titles, descriptions & hover photo inputs</div>
                        </div>
                    </div>
                    <a href="<?= eurl('/content?tab=services') ?>" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">Edit</a>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: var(--radius-sm); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <i class="fas fa-camera" style="color: var(--accent);"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-main);">Master Photos & Media Gallery</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);">Replace any front-end image through URL or direct upload</div>
                        </div>
                    </div>
                    <a href="<?= eurl('/content?tab=photos') ?>" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">Upload</a>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; border-radius: var(--radius-sm); background: var(--bg-card-subtle); border: 1px solid var(--border);">
                    <div style="display: flex; align-items: center; gap: 0.65rem;">
                        <i class="fas fa-chart-line" style="color: #10b981;"></i>
                        <div>
                            <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-main);">Trust Metrics & Performance Stats</div>
                            <div style="font-size: 0.72rem; color: var(--text-muted);"><?= sanitize(cms('metric_1_val', '99.9%')) ?> · <?= sanitize(cms('metric_2_val', '< 85ms')) ?> · <?= sanitize(cms('metric_3_val', '15+')) ?></div>
                        </div>
                    </div>
                    <a href="<?= eurl('/content?tab=metrics') ?>" class="btn btn-outline btn-sm" style="font-size: 0.75rem;">Edit</a>
                </div>
            </div>
        </div>

        <div style="padding-top: 1rem; border-top: 1px solid var(--border); margin-top: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
            <span style="font-size: 0.75rem; color: var(--text-muted);">
                <i class="fas fa-check-circle" style="color: var(--success);"></i> Live front-end in sync
            </span>
            <a href="<?= eurl('/') ?>" target="_blank" class="btn btn-sm btn-outline" style="border-color: var(--primary-border); color: var(--primary);">
                <i class="fas fa-external-link-alt"></i> Preview Public Landing Page
            </a>
        </div>
    </div>
</div>

<style>
@keyframes pulseDot {
    0% { transform: scale(0.95); opacity: 0.8; }
    50% { transform: scale(1.3); opacity: 1; }
    100% { transform: scale(0.95); opacity: 0.8; }
}
.status-pill-btn {
    border: none;
    background: transparent;
    padding: 0.25rem 0.55rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    color: var(--text-muted);
    transition: all 0.2s ease;
}
.status-pill-btn:hover {
    background: var(--bg-card-subtle);
    color: var(--text-main);
}
.status-pill-btn.active {
    font-weight: 700;
}
.status-pill-btn.active.working {
    background: rgba(16, 185, 129, 0.15);
    color: var(--success);
}
.status-pill-btn.active.meeting {
    background: rgba(139, 92, 246, 0.15);
    color: #8b5cf6;
}
.status-pill-btn.active.break {
    background: rgba(245, 158, 11, 0.15);
    color: var(--accent);
}
.status-pill-btn.active.away {
    background: rgba(148, 163, 184, 0.15);
    color: var(--text-light);
}
.status-pill-btn .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
}
</style>

<script>
// Auto scroll chat box to bottom
const chatEl = document.getElementById('dashChatBox');
if (chatEl) {
    chatEl.scrollTop = chatEl.scrollHeight;
}
</script>
