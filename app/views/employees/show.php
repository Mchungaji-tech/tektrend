<?php $pageTitle = 'Employee Profile'; ?>
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-header">
        <div>
            <h3 class="card-title"><?= sanitize($employee['first_name'] . ' ' . $employee['last_name']) ?></h3>
            <p class="card-subtitle">Employee ID: <strong><?= sanitize($employee['employee_id'] ?? 'EMP-00' . $employee['id']) ?></strong> · <?= sanitize($employee['position'] ?? ucfirst($employee['role'])) ?></p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="<?= eurl('/employees/' . $employee['id'] . '/edit') ?>" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</a>
            <a href="<?= eurl('/employees') ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Employees List</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <div style="text-align: center; background: var(--bg-card-subtle); padding: 2rem; border-radius: var(--radius-md); border: 1px solid var(--border);">
            <div style="width: 90px; height: 90px; border-radius: 50%; background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1rem;">
                <i class="fas fa-user-tie"></i>
            </div>
            <h4 style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);"><?= sanitize($employee['first_name'] . ' ' . $employee['last_name']) ?></h4>
            <div style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1rem;"><?= sanitize($employee['position'] ?? 'Team Member') ?></div>
            <span class="badge <?= sanitize($employee['status']) ?>"><?= ucfirst(sanitize($employee['status'])) ?></span>
        </div>

        <div>
            <h4 style="font-size: 1rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem; color: var(--text-main);">Professional Information</h4>
            <table style="width: 100%; font-size: 0.9rem;">
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted); width: 140px;">Work Email:</td><td><a href="mailto:<?= sanitize($employee['email']) ?>" style="color: var(--primary); font-weight: 600;"><?= sanitize($employee['email']) ?></a></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Phone:</td><td><a href="https://wa.me/<?= preg_replace('/\D/', '', $employee['phone'] ?? '254707246273') ?>" target="_blank" style="color: #25d366; font-weight: 600;"><i class="fab fa-whatsapp"></i> <?= sanitize($employee['phone'] ?? '-') ?></a></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Department:</td><td style="color: var(--text-main); font-weight: 600;"><?= sanitize($employee['department_name'] ?? 'Executive Board') ?></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">System Role:</td><td><span class="badge" style="background: var(--bg-card-subtle); color: var(--text-main); border: 1px solid var(--border);"><?= strtoupper($employee['role']) ?></span></td></tr>
                <tr><td style="padding: 0.5rem 0; color: var(--text-muted);">Joined On:</td><td style="color: var(--text-muted);"><?= formatDate($employee['created_at']) ?></td></tr>
            </table>
        </div>
    </div>
</div>
