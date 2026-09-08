<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contract Agreement - <?= sanitize($contract['contract_number']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #ffffff; color: #0f172a; padding: 3rem; line-height: 1.6; }
        .contract-paper { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #0f172a; padding-bottom: 1.5rem; margin-bottom: 2rem; }
        .header h1 { font-size: 1.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: -0.02em; }
        .header .meta { text-align: right; font-size: 0.85rem; color: #475569; }
        .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; }
        .parties h3 { font-size: 0.95rem; font-weight: 800; text-transform: uppercase; margin-bottom: 0.5rem; color: #4f46e5; }
        .section-title { font-size: 1.1rem; font-weight: 800; margin: 1.5rem 0 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.35rem; }
        .terms-body { font-size: 0.92rem; color: #334155; line-height: 1.7; white-space: pre-line; margin-bottom: 3rem; }
        .signatures { display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-top: 3rem; page-break-inside: avoid; }
        .sig-block { border-top: 1px solid #0f172a; padding-top: 0.75rem; }
        .sig-title { font-size: 0.82rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 800px; margin: 0 auto 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <button onclick="window.print()" style="padding: 0.6rem 1.5rem; background: #4f46e5; color: #fff; font-weight: 700; border: none; border-radius: 6px; cursor: pointer;">
            Print / Save as PDF
        </button>
        <button onclick="window.close()" style="padding: 0.6rem 1.2rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer;">
            Close Window
        </button>
    </div>

    <div class="contract-paper">
        <div class="header">
            <div>
                <h1>Tek Trend Innovations</h1>
                <p style="font-size: 0.85rem; color: #64748b;">Enterprise Software Architecture & Technology Consultancy</p>
                <p style="font-size: 0.85rem; color: #64748b;">123 Innovation Drive, Tech City, Nairobi · info@tektrend.com</p>
            </div>
            <div class="meta">
                <div style="font-size: 1.1rem; font-weight: 800; color: #4f46e5;"><?= sanitize($contract['contract_number']) ?></div>
                <div>Date: <?= formatDate($contract['start_date']) ?></div>
                <div>Status: <?= strtoupper($contract['status']) ?></div>
                <div style="font-weight: 800; margin-top: 0.35rem;">Total Value: $<?= number_format($contract['value'], 2) ?> USD</div>
            </div>
        </div>

        <h2 style="font-size: 1.35rem; font-weight: 800; margin-bottom: 1.5rem;"><?= sanitize($contract['title']) ?></h2>

        <div class="parties">
            <div>
                <h3>Service Provider</h3>
                <p><strong>Tek Trend Innovations Ltd.</strong></p>
                <p>David Kimani, Chief Executive Officer</p>
                <p>Email: info@tektrend.com</p>
                <p>Phone: +254 707 246 273</p>
            </div>
            <div>
                <h3>Client</h3>
                <p><strong><?= sanitize($contract['customer_company'] ?? ($contract['customer_first'] . ' ' . $contract['customer_last'])) ?></strong></p>
                <p>Representative: <?= sanitize($contract['signed_by_name'] ?? ($contract['customer_first'] . ' ' . $contract['customer_last'])) ?></p>
                <p>Email: <?= sanitize($contract['customer_email'] ?? 'On File') ?></p>
                <p>Address: <?= sanitize($contract['address'] ?? ($contract['city'] . ', ' . $contract['country'])) ?></p>
            </div>
        </div>

        <div class="section-title">Contract Scope, Terms & Obligations</div>
        <div class="terms-body">
            <?= sanitize($contract['terms'] ?? 'Standard enterprise terms and deliverables apply.') ?>
        </div>

        <div class="signatures">
            <div class="sig-block">
                <div style="font-family: cursive; font-size: 1.3rem; margin-bottom: 0.25rem; color: #1e3a8a;">David Kimani</div>
                <div class="sig-title">For: Tek Trend Innovations Ltd.</div>
                <div style="font-size: 0.85rem; font-weight: 600;">David Kimani, CEO & Principal Consultant</div>
            </div>
            <div class="sig-block">
                <div style="font-family: cursive; font-size: 1.3rem; margin-bottom: 0.25rem; color: #1e3a8a;"><?= sanitize($contract['signed_by_name'] ?? 'Authorized Representative') ?></div>
                <div class="sig-title">For: <?= sanitize($contract['customer_company'] ?? 'Client Organization') ?></div>
                <div style="font-size: 0.85rem; font-weight: 600;"><?= sanitize($contract['signed_by_name'] ?? 'Client Signatory') ?></div>
            </div>
        </div>
    </div>
</body>
</html>
