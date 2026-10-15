# Payments & Paystack Security Design

> Status: **design only** — no payment gateway code is wired up in this phase.
> This document describes the threat model and the controls that MUST be in place
> before any live money movement (Paystack card/mobile checkout, or M-Pesa Daraja)
> is enabled for invoices in the Tek Trend portal.

---

## 1. Scope

The client portal (`/portal`) will eventually let a customer pay an invoice online.
The two candidate rails for our Kenyan market are:

- **Paystack** — cards, bank transfer, USSD, and M-Pesa via one integration.
- **M-Pesa (Safaricom Daraja)** — STK Push, direct M-Pesa (an alternative to Paystack's M-Pesa).

Both share the same security requirements: **never trust the client**, verify
everything **server-side**, and make every credit **idempotent**.

---

## 2. Threat Model

| # | Threat | How it happens | Impact |
|---|--------|----------------|--------|
| T1 | **Webhook spoofing** | Attacker POSTs a fake `charge.success`/`payment.success` event to our webhook URL claiming an invoice is paid | Invoice marked paid without money received |
| T2 | **Replay attacks** | A legitimate signed webhook is captured and resent many times | Double credit / duplicated fulfilment |
| T3 | **Client-side price tampering** | User edits the amount in the browser (or the checkout iframe params) before paying | Pays less than owed, or the recorded amount is manipulated |
| T4 | **Secret leakage** | `PAYSTACK_SECRET_KEY` / Daraja API keys committed, shipped to the browser, or logged | Full account compromise, refunds, data exposure |
| T5 | **Reference forgery** | Attacker guesses/reuses a `payment_reference` and binds a real payment to a *different* invoice | Someone else's invoice gets marked paid |
| T6 | **Double credit** | Two concurrent callbacks (or callback + manual verify) both apply the same payment | Ledger imbalance, accounting errors |
| T7 | **CSRF on checkout initiation** | A third-party site forces a logged-in client to kick off a charge | Confused-deputy payment actions |
| T8 | **MITM** | Plain HTTP in the payment flow | Tampering with amounts and interception of tokens |

---

## 3. Required Controls

### 3.1 Server-side secret key ONLY
- The Paystack **secret key** (`sk_test_…` / `sk_live_…`) and Daraja **consumer secret** live
  **only** in `.env` / server config (same pattern as `GOOGLE_CLIENT_SECRET`).
- They are **never** placed in the database, sent to any view, returned by any API,
  or embedded in JavaScript. The only value safe for the browser is the **public key**
  (`pk_test_…` / `pk_live_…`).
- `.cpanel.yml` and `.gitignore` must keep excluding `.env` on deploy (already configured).

### 3.2 Verify webhook signatures (anti-spoofing T1)
- Paystack signs every event: header `x-paystack-signature` is an **HMAC-SHA512** of the
  **raw request body** using the secret key.
- Compute it over the *untouched* bytes and compare with a timing-safe check:

```php
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';
$payload   = file_get_contents('php://input');          // raw body, NOT re-serialised
$expected  = hash_hmac('sha512', $payload, PAYSTACK_SECRET_KEY);
if (!hash_equals($expected, $signature)) {
    http_response_code(400);
    exit('Invalid signature');
}
```

- **Never** re-encode the JSON before hashing — signature is over the exact bytes received.

### 3.3 Re-verify every transaction server-side (anti-forgery T5, anti-tamper T3)
- A webhook, or the client's "payment successful" callback, is treated as a **hint only**.
- Before marking any invoice paid, call Paystack:

  `GET https://api.paystack.co/transaction/verify/:reference`

  with `Authorization: Bearer sk_...`, and only trust the **server's** verdict:
  `status == success`, and that `data.amount` (in **minor units**, kobo/cents) equals the
  invoice total we computed, and that `data.currency` matches, and `metadata.invoice_id`
  binds the payment to the intended invoice.

### 3.4 Amount is recomputed server-side (anti-tamper T3)
- When initiating checkout, the amount sent to Paystack is derived from the **database**
  invoice total, not from any `$_POST` amount. The browser cannot choose what is charged.
- Amounts use **minor units** (multiply by 100) — Paystack expects the smallest currency unit.

### 3.5 Idempotent status transitions (anti-replay T2, anti-double-credit T6)
- Generate a **unique** `payment_reference` per attempt (e.g. `INV{invoiceId}-{randomHex}`),
  stored with a **UNIQUE** index so a replayed webhook maps to the same row.
- On success handling, guard the transition so it only fires once:

```php
// only move pending -> paid if this reference has not already been applied
$applied = $db->execute(
    "UPDATE invoice_payments
        SET status = 'success', verified_at = NOW()
      WHERE paystack_reference = ? AND status <> 'success'",
    [$reference]
);
if ($applied === 1) {
    // this is the first time we have seen this reference succeed -> safe to mark invoice paid
}
```

- Because the guard is a single atomic `UPDATE ... WHERE status <> 'success'`, two
  concurrent callbacks cannot both apply the credit.

### 3.6 Transport + CSRF
- The whole payment flow requires **HTTPS** (set `APP_URL`/`BASE_URL` to `https://`).
  Paystack live mode rejects non-secure callback URLs.
- Any POST that initiates a charge requires the existing **CSRF token**
  (`csrfToken()` / `verifyCsrf()`), same as every other form in the app.

### 3.7 Webhook endpoint hardening
- Route is **POST only**, e.g. `POST /webhooks/paystack`, with **no auth/session middleware**
  (Paystack is not logged in) — but it is **signature-gated** (3.2) as the first thing it does.
- Store the **raw webhook payload** (JSON) for audit/reconciliation before processing.
- Respond `200 OK` quickly after verifying + enqueuing work; do the heavy lifting
  (verify + credit) after the signature check.

### 3.8 Logging & audit
- Log every payment lifecycle event to `audit_logs` (`payment_initiated`,
  `webhook_received`, `transaction_verified`, `invoice_marked_paid`) with the reference,
  invoice id, amounts, and outcome. Never log secret keys or full card data
  (Paystack is PCI-DSS — we only ever handle tokens/references, never PAN/CVV).

---

## 4. Schema sketch (future phase, NOT applied now)

Add a provider dimension and uniqueness guards to the payment ledger so the controls in
§3.5 / §3.2 have somewhere to live:

```sql
ALTER TABLE `invoice_payments`
  ADD COLUMN `provider` ENUM('paystack','manual','mpesa') NOT NULL DEFAULT 'manual',
  ADD COLUMN `paystack_reference` VARCHAR(64) DEFAULT NULL,
  ADD COLUMN `status` ENUM('pending','success','failed','refunded') NOT NULL DEFAULT 'pending',
  ADD COLUMN `verified_at` TIMESTAMP NULL DEFAULT NULL,
  ADD COLUMN `gateway_response` LONGTEXT DEFAULT NULL,
  ADD UNIQUE KEY `paystack_reference` (`paystack_reference`);
```

The existing `invoice_payments.invoice_id`, `amount`, `payment_method`, `reference`
columns are retained. `payment_reference` uniqueness is the idempotency anchor.

---

## 5. Kenyan context — M-Pesa (Daraja) notes

If the direct Daraja path is chosen instead of / alongside Paystack:

- STK Push callbacks (`POST /mpesa/daraja-callback`) carry the result. Daraja v1 uses
  `mpesa_checkout_request_id` + `result_code`; guard idempotency on the
  `CheckoutRequestID` (UNIQUE) exactly as in §3.5.
- Daraja API calls require an **OAuth bearer token** (generated from
  consumer key + secret) — same "secret key server-side only" rule as §3.1.
- Validate `ResultCode == 0` (success) and that `Amount` and `MpesaReceiptNumber`
  match what we initiated server-side before crediting.
- Consider verifying the callback authenticity by correlating the `CheckoutRequestID`
  back to a transaction we started (Daraja callbacks are not HMAC-signed the way
  Paystack webhooks are, so the *lookup-and-compare* step is the primary defence).

---

## 6. Pre-launch checklist

- [ ] Secret keys only in `.env`; confirmed absent from git, DB, views, JS, and logs.
- [ ] `APP_URL`/`BASE_URL` on HTTPS in production.
- [ ] Webhook route is POST-only, signature verified with `hash_equals` before any write.
- [ ] Transaction re-verified via `GET /transaction/verify/:reference` before any invoice is marked paid.
- [ ] `payment_reference` UNIQUE; credit applied via atomic status-guarded update.
- [ ] Amount sourced from the DB invoice total, in minor units.
- [ ] CSRF required on checkout-initiating POST.
- [ ] Raw webhook payloads + `audit_logs` retained for reconciliation.
- [ ] Idempotency tested with duplicate + out-of-order callbacks.
