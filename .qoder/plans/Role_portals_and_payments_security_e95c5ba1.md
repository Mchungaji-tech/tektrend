# Role Portals, Public Auth, and Payments Security

## Summary
Turn the current single shared console (where every logged-in user sees admin pages) into three separate, role-gated areas:
- `/admin` - staff console (existing controllers + `layouts/app.php`), only internal roles.
- `/portal` - client self-service portal (new), scoped to the client's own data.
- `/partners` - partner/referral portal (new), with application + admin approval.

Plus public sign-up, social sign-in across areas, and a written Paystack/payment-security design (no code this phase).

## 1. Roles and Database migration
`users.role` ENUM is `admin,manager,employee,accountant,sales,support`; `users.status` is `active,inactive,suspended`. Add two roles and a pending status, plus a customer link.

Runtime auto-migration (same `ensureXTable` pattern already used in `Controller::ensurePortfolioDemosTable` and `BlogController::ensureBlogTable`), executed idempotently from a new helper `App::ensureRoleSchema()` called in `bootstrap()`:
- `ALTER TABLE users MODIFY role ENUM('admin','manager','employee','accountant','sales','support','client','partner') DEFAULT 'employee'`
- `ALTER TABLE users MODIFY status ENUM('active','inactive','suspended','pending') DEFAULT 'active'`
- Add `customers.user_id INT UNSIGNED NULL` + `KEY user_id` (links a login user to their customer record).
- Add `partners` table: `id,user_id,company_name,website,about,status ENUM('pending','approved','rejected'),commission_rate DECIMAL(5,2),created_at,updated_at`.
- Update `database/schema.sql` to match the above for fresh installs.

`employee_id` is NOT NULL/UNIQUE: self-registered clients get `CL` + unique suffix, partners get `PB` + suffix (reuse the collision-checked generator pattern in `GoogleAuthController::generateEmployeeId`). Password column stays NOT NULL (store bcrypt; social-only accounts get a random hash).

## 2. Router area guards (`app/core/Router.php`)
Extend `runMiddleware()` with new cases (keeps `auth`,`guest`,`admin`,`manager`):
- `staff`: `auth` AND `role in (admin,manager,employee,accountant,sales,support)`; a client/partner hitting a staff route is redirected to their own home.
- `client`: `auth` AND `role == client`.
- `partner`: `auth` AND `role == partner` AND `status == active`; `status == pending` -> redirect `/partners/pending`.
- `approved_partner`: partner with approved status (for payouts/referral pages).
Add a private `homePath()` map (role -> `/admin` | `/portal` | `/partners`).

## 3. Login and post-login redirect (`app/core/Auth.php`, `AuthController`, layouts, OAuth controllers)
- Add helper `homeUrlFor($role, $status)` returning `/admin` (staff), `/partners` (partner active), `/partners/pending` (partner pending), `/portal` (client), used everywhere login succeeds.
- Update `AuthController::authenticate` (currently `redirect('/dashboard')`), `Auth::attempt`/`loginUser` callers, guest middleware default (`/dashboard`), `GoogleAuthController`/`GithubAuthController` success redirect, and `layouts/app.php` brand/logout links to use the role home.
- Keep `/dashboard` as a redirect alias to `/admin` for staff (backwards compatibility) and to the caller's home otherwise.

## 4. Staff console gating (existing routes in `app/App.php`)
Change all internal app routes from `['auth']` to `['staff']` (dashboard, leads, customers, contracts, invoices, finances, employees, departments, content, blog-manage, users, settings, emails, events, tasks, investments, marketplace, consultations, chat/meetings). Move the staff home to `/admin` (`DashboardController@index`) and set `DashboardController@index` to use `layouts/app` unchanged. This is what "removes admin from the public/regular-user domain": clients and partners can no longer reach any of these.
- Add admin partner-approval under the console: `PartnerAdminController@index/approve/reject` at `/admin/partners` (`['staff']` + effectively admin via `requireRole('admin')`), with a "Partner Applications" link in `layouts/app.php` sidebar.

## 5. Client portal (new)
- `app/controllers/ClientController.php` + `app/views/layouts/portal.php` (a trimmed sidebar: Dashboard, My Invoices, My Projects, Messages, Profile; reuses the CSS from `layouts/app.php` via an include of the `<style>` or a shared partial).
- Routes (all `['client']`): `/portal` (dashboard), `/portal/invoices`, `/portal/invoices/{id}`, `/portal/projects`, `/portal/profile`, `/portal/logout`.
- Data scoping: resolve `customer_id` from `customers.user_id = session user_id`; if none exists, auto-link by email on first login (create/attach a `customers` row). Invoices/consultations/demos queries are filtered by that `customer_id` only.
- Client dashboard: open/paid invoice totals, upcoming consultations, requested-system status.
- `GET /portal` when not logged in -> redirect `/login?area=client`.

## 6. Partner portal and approval flow (new)
- `app/controllers/PartnerController.php` + `app/views/layouts/partner.php` (sidebar: Dashboard, Referrals, Applications, Profile).
- Public application: `GET/POST /partner-apply` (`['guest']`) -> creates `users` (role=partner,status=pending) + `partners` row (pending); no portal access until approved.
- Partner area (`['partner']`/`['approved_partner']`): `/partners`, `/partners/referrals`, `/partners/payouts`, `/partners/profile`, `/partners/pending` (awaiting approval view).
- Admin approval lives in section 4 (`/admin/partners`): approve sets `users.status=active` and `partners.status=approved`.

## 7. Public self-registration
- Keep the current admin-only staff form but move it to the console: `AuthController@register/store` guarded `['admin']` at `/admin/register` (staff creation only).
- New `AuthController@signup` / `@createClient` at `/signup` (GET/POST, `['guest']`) using `layouts/auth.php`: first/last name, email, password, phone, company -> creates role=client, status=active, links `customers.user_id`, then `loginUser($user,'Self-signup')` and redirect `/portal`. Includes reCAPTCHA hook placeholder (documented, not enabled).
- New `app/views/auth/signup.php`.
- Update login view: add "Create account" (client) and "Become a partner" (`/partner-apply`) links; when `?area=client|partner` is present, adjust heading.

## 8. Social sign-in across all areas (Google/GitHub already built)
- Accept an intent via query: `/auth/google?to=client|partner|staff` (and same for `/auth/github`); store `$_SESSION['oauth_intent']`.
- In `GoogleAuthController::findOrCreateUser` and `GithubAuthController::findOrCreateUser`, when creating a brand-new user use the intent role (default stays `employee`); when `to=partner`, create role=partner,status=pending + pending `partners` row and redirect to `/partners/pending`.
- Add the existing Google/GitHub button block to `/signup`, `/partner-apply`, and the portal/partner login views (link to `/auth/google?to=client` etc.). The provider is still only shown when its `.env` credentials are set.

## 9. Payments / Paystack security design (documentation deliverable, no code)
Produce a `docs/payments-security.md` (or inline section in README) covering:
- Threats: webhook spoofing, replay attacks, price tampering on the client, secret leakage, reference forgery, double credit.
- Controls: server-side secret key only (never in DB/client/JS); verify `x-paystack-signature` as HMAC-SHA512 of raw body with the secret (`hash_equals`); re-verify every transaction server-side via `GET /transaction/verify/:reference` before marking an invoice paid; treat payment status transitions idempotently with a `payment_reference` UNIQUE guard; recompute totals server-side and never trust client amounts; require HTTPS + CSRF on any checkout-initiating POST; store raw webhook payload for audit; restrict webhook endpoint route to `POST` with no auth middleware but signature-gated; log all payment events to `audit_logs`.
- Schema sketch for a future phase: `invoice_payments` add `provider ENUM('paystack','manual','mpesa')`, `paystack_reference UNIQUE`, `status`, `verified_at`. (Not added now.)
- Kenyan context: note M-Pesa (Daraja) callback signature checks if that path is chosen later.

## Test Plan
- `php -l` (C:\xampp\php\php.exe) on every new/changed file.
- Route matrix check: client session requesting `/admin/*`, `/users`, `/settings` is redirected to `/portal`; partner likewise; staff reaches all; pending partner reaches `/partners/pending` only.
- `/signup` creates active client, links `customers` by email, lands on `/portal` with only their invoices.
- `/partner-apply` creates pending partner (no portal until `/admin/partners` approve).
- Login redirect resolves correct home for each role (password and social).
- Google sign-in from `/signup?area=client` provisions a client; from staff `/login` provisions employee (regression check).
- Confirm `/dashboard` still works for staff (alias to `/admin`).

## Assumptions and Decisions
- Clients (role=client) are active immediately; partners are pending until admin approval (as chosen).
- Existing admin/staff routes move to `['staff']` guards but keep their current URL paths; `/admin` is the staff home alias of `/dashboard` (no mass URL rewrites of every resource this phase, lower risk).
- `users` remains the single identity table; `customers` gains `user_id` to scope the client portal.
- Paystack itself is NOT integrated this phase; only the security design is delivered.
- All DB changes are applied via idempotent runtime migration (matches the project's existing `ensure*` pattern) so no manual SQL is required, and `schema.sql` is updated for clean installs.
