# Codex Master Prompt — Runtime Verify Lifter’s Touch Laravel Backend

You are working on the uploaded Laravel backend ZIP:

`lifters_touch_laravel_backend_final_deployable_validated.zip`

Your mission is to make this Laravel backend truly executable, minimally repair anything blocking execution, verify it, and package a runtime-verified ZIP.

Do not add new features.
Do not refactor architecture.
Do not prepare production deployment yet.
Do not bypass failing functionality by deleting it.
Your only goal is runtime validation, minimal repair, evidence collection, and verified packaging.

---

## Project Purpose

This backend is for Lifter’s Touch Empowerment Foundation.

It is intended to manage:

```text
Member registration
→ Member verification/activation
→ Disbursement request creation
→ Branch approval
→ Finance review
→ Super-admin authorization
→ Treasury mark-paid
→ Audit history
→ Period close/lock
→ Reconciliation blockers
→ Workbook/trial-balance import review
→ cPanel/Namecheap deployment preparation
```

This is a financial-governance backend. Preserve audit, ledger, treasury, status history, and approval data integrity.

---

## Phase 1 — Extract and Inspect

Extract the ZIP.

Confirm the project root contains:

- `composer.json`
- `artisan`
- `bootstrap/app.php`
- `routes/api.php`
- `database/migrations`
- `.env.example`

Create:

```text
docs/COMMAND_RESULTS.md
```

In `docs/COMMAND_RESULTS.md`, record:

- PHP version
- Composer version
- current working directory
- file structure summary
- every command run
- command result
- any error output
- any fix applied

Run:

```bash
php -v
composer --version
pwd
ls -la
```

---

## Phase 2 — Runtime Validation

Run exactly:

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan route:list
php artisan test
```

Then start server:

```bash
php artisan serve
```

Test health:

```bash
curl http://127.0.0.1:8000/api/v1/health
```

Test login:

```bash
curl -X POST http://127.0.0.1:8000/api/v1/login \
-H "Content-Type: application/json" \
-d '{"email":"admin@example.com","password":"password","device_name":"codex-test"}'
```

---

## Phase 3 — Repair Rules

If any command fails:

1. Stop.
2. Record the exact error in `docs/COMMAND_RESULTS.md`.
3. Identify the root cause.
4. Apply the smallest safe fix.
5. Re-run the failed command.
6. Continue only after it passes.

Allowed fixes:

- PHP syntax errors
- missing imports
- missing classes
- wrong namespaces
- migration ordering issues
- foreign-key ordering issues
- seeder failures
- broken route/controller references
- bootstrap/config issues
- missing test imports
- SQLite-local validation problems

Forbidden fixes:

- deleting core workflows
- deleting financial/audit/ledger/history tables
- bypassing auth
- disabling failing features instead of fixing them
- exposing `.env`
- committing secrets
- running destructive production commands
- adding AI integration
- replacing the project with a new scaffold

Placeholder tests do not prove business correctness. If tests are placeholders, state that clearly in the report.

---

## Phase 4 — Required Smoke Tests

After Laravel boots, verify:

1. `GET /api/v1/health`
2. `POST /api/v1/login` returns token
3. authenticated `GET /api/v1/me`
4. authenticated `GET /api/v1/members`
5. authenticated `GET /api/v1/periods`
6. authenticated `GET /api/v1/treasury/transactions`

Use the token from login.

Optional but recommended if routes and seed data allow:

1. create member
2. verify member
3. activate member
4. create disbursement
5. submit disbursement
6. branch approve
7. finance review
8. authorize
9. mark paid
10. confirm treasury transaction created

---

## Phase 5 — Final Reports

Create:

```text
docs/COMMAND_RESULTS.md
docs/FINAL_VALIDATION_REPORT.md
```

`docs/FINAL_VALIDATION_REPORT.md` must include this JSON summary:

```json
{
  "php_version": "string",
  "composer_version": "string",
  "composer_install": "success | failed",
  "environment_setup": "success | failed",
  "migration_status": "success | failed",
  "seed_status": "success | failed",
  "route_list_status": "success | failed",
  "test_status": "success | failed",
  "server_boot": "success | failed",
  "health_check": "success | failed",
  "auth_test": "success | failed",
  "smoke_tests": "success | failed",
  "placeholder_tests_found": true,
  "fixes_applied": [],
  "remaining_risks": [],
  "final_status": "READY | BLOCKED"
}
```

Also include:

- commands run
- exact errors found
- files changed
- why each fix was necessary
- remaining risks
- deployment readiness recommendation

---

## Phase 6 — Packaging

Only if `final_status` is `READY`, create:

```text
lifters_touch_laravel_backend_runtime_verified.zip
```

The ZIP must include:

- repaired Laravel project
- `composer.json`
- `artisan`
- `bootstrap/app.php`
- `routes/api.php`
- `database/migrations/`
- `app/`
- `config/`
- `.env.example`
- deployment docs
- `docs/COMMAND_RESULTS.md`
- `docs/FINAL_VALIDATION_REPORT.md`

The ZIP must exclude:

- `.env`
- `vendor/`
- `node_modules/`
- `storage/logs/*.log`
- temporary diagnostic files
- OS junk files
- secrets

---

## Phase 7 — Archive Verification

After creating:

```text
lifters_touch_laravel_backend_runtime_verified.zip
```

Run:

```bash
unzip -l lifters_touch_laravel_backend_runtime_verified.zip
```

Verify the archive contains:

- `composer.json`
- `artisan`
- `bootstrap/app.php`
- `routes/api.php`
- `database/migrations/`
- `app/`
- `config/`
- `docs/COMMAND_RESULTS.md`
- `docs/FINAL_VALIDATION_REPORT.md`

Verify the archive excludes:

- `.env`
- `vendor/`
- `node_modules/`
- `storage/logs/*.log`
- temporary diagnostic files

Append the archive verification results to:

```text
docs/FINAL_VALIDATION_REPORT.md
```

---

## Final Rule

If validation fails and cannot be fixed safely, do not package as verified.

Return:

```text
final_status: BLOCKED
```

with exact blocker details.
