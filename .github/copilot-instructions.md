# GitHub Copilot Instructions for LiftersTouch-Admin-

## Purpose
This repo is a Laravel 11 backend service for Lifter’s Touch Empowerment Foundation. Use Copilot to help with backend development, bug fixes, tests, and documentation updates while preserving the repository's existing conventions.

## Key project facts
- PHP backend only; the repo contains no frontend application code.
- Laravel 11 with Sanctum authentication.
- Primary directories:
  - `app/Http/Controllers`
  - `app/Models`
  - `app/Policies`
  - `app/Services`
  - `app/Enums`
  - `app/Http/Requests`
  - `app/Http/Resources`
  - `routes/api.php`
  - `database/migrations`
  - `database/seeders`
  - `tests/Feature`
- Tests run with `php artisan test`; PHPUnit is configured in `phpunit.xml`.
- Local install flow is described in `README.md`.

## Architecture Patterns
- **Service Layer**: Business logic in `app/Services/` with DB transactions, audit trails, and state validation
- **Policy-Based Auth**: Authorization via `app/Policies/` with role checks (SUPER_ADMIN bypasses via `before()`)
- **Enum-Driven Domain**: Use `app/Enums/` for status codes and domain values (e.g., MemberStatus, DisbursementStage)
- **Resource Transformers**: API responses via `app/Http/Resources/`
- **Request Validation**: Input validation in `app/Http/Requests/`

## Recommended workflows
- For setup and validation use the repository README:
  - `composer install`
  - `cp .env.example .env`
  - `php artisan key:generate`
  - `php artisan migrate --seed`
  - `php artisan test`
- For production/deploy notes consult `deployment/`.
- For API and frontend integration expectations consult `docs/API_REFERENCE.md` and `docs/FRONTEND_INTEGRATION_CONTRACT.md`.
- For error and idempotency behavior consult `docs/ERROR_AND_IDEMPOTENCY_CONTRACT.md`.
- For role permissions see `docs/ROLE_PERMISSION_MATRIX.md`.

## Conventions
- Prefer Laravel conventions over custom patterns unless the codebase already uses a specific alternative.
- Preserve existing naming, route structure, and service responsibilities.
- Use enums for domain values (not strings) and validate status transitions.
- Always use `DB::transaction()` and `lockForUpdate()` for financial operations.
- Include audit logging for all state changes via services.
- Require `Idempotency-Key` header for financial mutations (enforced by middleware).
- Return consistent JSON: `{ success, message, data, errors }`.
- If making schema or migration changes, ensure migrations and seeders remain consistent with the backend package and run tests.
- For policy/authorization changes, update both `app/Policies` and any relevant controller logic.
- Feature tests belong in `tests/Feature`.

## Common Pitfalls
- Never put business logic in controllers or models; use services.
- Always call `$this->authorize()` in controllers before service calls.
- Validate current status before transitions; throw validation exceptions for invalid ones.
- Never skip audit trails for financial or state changes.
- Test idempotency for mutations; use in-memory SQLite for tests.
- Ensure financial consistency with transactions and locks.

## Important notes
- The seeded admin login is documented in `README.md`.
- The codebase already includes a final deployable package mindset; avoid adding features that conflict with the existing stability and production deployment guidance.
- Do not invent frontend contracts; link to `docs/FRONTEND_INTEGRATION_CONTRACT.md` when frontend behavior is relevant.
- Financial workflows: Member verification → activation; Disbursement draft → submit → approve chain → mark paid; Period review → close → lock.

## Example prompts
- "Help me add validation for `DisbursementRequest` creation and ensure the correct policy is enforced in `app/Http/Controllers/DisbursementRequestController.php`."
- "Create a new feature test in `tests/Feature` that exercises the member onboarding flow with the seeded admin user."
- "Summarize the API endpoints defined in `routes/api.php` and identify any access-control gaps."
- "Review the repo for Laravel service classes and suggest where a new business rule should live."
- "Add audit logging to a new financial operation in the appropriate service class."
