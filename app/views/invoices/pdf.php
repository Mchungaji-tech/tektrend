<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice <?= sanitize($invoice['invoice_number']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page {
            size: auto;
            margin: 12mm 15mm 12mm 15mm;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #ffffff; color: #0f172a; padding: 2rem 3rem; line-height: 1.5; }
        .invoice-paper { max-width: 800px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #0f172a; padding-bottom: 1.5rem; margin-bottom: 2rem; }
        .header h1 { font-size: 1.8rem; font-weight: 800; color: #0f172a; }
        .meta { text-align: right; }
        .meta .inv-no { font-size: 1.3rem; font-weight: 800; color: #4f46e5; }
        .parties { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2.5rem; background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; }
        table.items-table { width: 100%; border-collapse: collapse; margin-bottom: 2rem; font-size: 0.92rem; }
        table.items-table th { background: #f1f5f9; padding: 0.75rem 1rem; text-align: left; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; border-bottom: 1px solid #cbd5e1; }
        table.items-table td { padding: 0.85rem 1rem; border-bottom: 1px solid #e2e8f0; }
        .totals { margin-left: auto; width: 320px; margin-bottom: 2.5rem; }
        .totals-row { display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.9rem; }
        .totals-row.grand { font-size: 1.25rem; font-weight: 800; border-top: 2px solid #0f172a; padding-top: 0.6rem; margin-top: 0.4rem; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
            a[href]:after { content: none !important; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="max-width: 800px; margin: 0 auto 1.5rem; display: flex; justify-content: space-between; align-items: center;">
        <button onclick="window.print()" style="padding: 0.6rem 1.5rem; background: #4f46e5; color: #fff; font-weight: 700; border: none; border-radius: 6px; cursor: pointer;">
            Print / Save as PDF
        </button>
        <button onclick="window.close()" style="padding: 0.6rem 1.2rem; background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 6px; cursor: pointer;">
            Close
        </button>
    </div>

    <div class="invoice-paper">
        <div class="header">
            <div>
                <h1>Tek Trend Innovations</h1>
                <p style="font-size: 0.85rem; color: #64748b;">Enterprise Software Architecture & Technology Consultancy</p>
                <p style="font-size: 0.85rem; color: #64748b;">Nairobi, Kenya · info@tektrend.com</p>
            </div>
            <div class="meta">
                <div class="inv-no"><?= sanitize($invoice['invoice_number']) ?></div>
                <div style="font-size: 0.85rem; color: #475569;">Date: <?= formatDate($invoice['issue_date']) ?></div>
                <div style="font-size: 0.85rem; color: #475569;">Due: <?= formatDate($invoice['due_date']) ?></div>
                <div style="margin-top: 0.25rem;"><span style="display: inline-block; padding: 0.15rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;"><?= strtoupper($invoice['status']) ?></span></div>
            </div>
        </div>

        <div class="parties">
            <div>
                <h4 style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; margin-bottom: 0.4rem;">Bill To:</h4>
                <p><strong><?= sanitize($invoice['company'] ?? ($invoice['first_name'] . ' ' . $invoice['last_name'])) ?></strong></p>
                <p>Attn: <?= sanitize($invoice['first_name'] . ' ' . $invoice['last_name']) ?></p>
                <p><?= sanitize($invoice['email'] ?? '') ?></p>
            </div>
            <div>
                <h4 style="font-size: 0.75rem; text-transform: uppercase; color: #64748b; margin-bottom: 0.5rem;">Payment Method:</h4>
                <div style="font-size: 0.9rem; line-height: 1.5; background: #ffffff; padding: 0.85rem 1rem; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <div style="font-weight: 800; color: #059669; margin-bottom: 0.35rem; font-size: 0.95rem;">
                        M-Pesa (Send Money)
                    </div>
                    <div>Send Money to: <strong style="color: #0f172a; font-size: 1.05rem;">0707246273</strong></div>
                    <div style="color: #475569; margin-top: 0.2rem;">Recipient: <strong>Timothy omondi /Tektrend innovations</strong></div>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center; width: 70px;">Qty</th>
                    <th style="text-align: right; width: 140px;">Unit Price (KSh)</th>
                    <th style="text-align: right; width: 140px;">Total (KSh)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td>Enterprise Software Consulting & Platform Delivery</td>
                        <td style="text-align: center;">1.00</td>
                        <td style="text-align: right;"><?= formatCurrency($invoice['subtotal']) ?></td>
                        <td style="text-align: right; font-weight: 700;"><?= formatCurrency($invoice['subtotal']) ?></td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <td><?= sanitize($item['description']) ?></td>
                            <td style="text-align: center;"><?= number_format($item['quantity'], 0) ?></td>
                            <td style="text-align: right;"><?= formatCurrency($item['unit_price']) ?></td>
                            <td style="text-align: right; font-weight: 700;"><?= formatCurrency($item['line_total']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-row">
                <span>Subtotal:</span>
                <span><?= formatCurrency($invoice['subtotal']) ?></span>
            </div>
            <div class="totals-row">
                <span>Tax / VAT:</span>
                <span><?= formatCurrency($invoice['tax_amount']) ?></span>
            </div>
            <?php if ($invoice['discount_amount'] > 0): ?>
                <div class="totals-row">
                    <span>Discount:</span>
                    <span>- <?= formatCurrency($invoice['discount_amount']) ?></span>
                </div>
            <?php endif; ?>
            <div class="totals-row grand">
                <span>Total Due:</span>
                <span style="color: #4f46e5;"><?= formatCurrency($invoice['total']) ?></span>
            </div>
        </div>

        <div style="border-top: 1px solid #e2e8f0; padding-top: 1rem; font-size: 0.82rem; color: #64748b;">
            <strong>Terms & Notes:</strong> <?= nl2br(sanitize($invoice['notes'] ?? 'Payment due upon receipt in Kenyan Shillings (KSh). Send Money via M-Pesa to 0707246273 (Timothy omondi /Tektrend innovations). Thank you for partnering with Tek Trend Innovations.')) ?>
        </div>
    </div>
</body>
</html>
