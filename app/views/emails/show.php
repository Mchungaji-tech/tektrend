<?php $pageTitle = 'Campaign Details'; ?>
<div class="table-section reveal">
    <div class="header">
        <h3>Campaign: <?= sanitize($campaign['name'] ?? 'Unknown') ?></h3>
    </div>
    <div style="margin-top: 1rem;">
        <p><strong>Subject:</strong> <?= sanitize($campaign['subject'] ?? '-') ?></p>
        <p><strong>Status:</strong> <?= ucfirst($campaign['status'] ?? '-') ?></p>
        <p><strong>Sent At:</strong> <?= $campaign['sent_at'] ? formatDateTime($campaign['sent_at']) : 'Not sent' ?></p>
        <p><strong>Total Recipients:</strong> <?= (int)($campaign['total_recipients'] ?? 0) ?></p>
        <p><strong>Total Sent:</strong> <?= (int)($campaign['total_sent'] ?? 0) ?></p>
    </div>
</div>
