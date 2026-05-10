# Audit Report — Digital Service Note System

## Audit Summary
- Overall status: Partially aligned MVP; core service note workflow is implemented and Docker runtime is now verified.
- Can the app run? Yes via Docker. `docker compose up -d --build app` starts the app and database, `http://localhost:8000` returns HTTP 200, and `docker compose exec app php artisan test` passes. Local PHP/Composer are still not installed.
- Is it aligned with PRD.md? Partially.
- Biggest issue: Local PHP/Composer runtime remains unavailable, and `/backup-guide` required by PRD navigation is missing.
- Recommended next action: Resolve the local PHP/Composer verification gap or standardize on Docker-only commands, then implement the missing backup guide route/page and settings/header gaps.

## Requirement Checklist

| No | Requirement | Status | Evidence / File Path | Notes |
|---|---|---|---|---|
| 1 | No login page | Pass | `routes/web.php`, `resources/views` | No login route or login Blade view found. |
| 2 | No authentication required | Pass | `routes/web.php` | Routes are public; no auth middleware found on MVP routes. |
| 3 | No admin/staff role system for MVP | Pass | `app`, `database`, `routes/web.php` | No role/admin model, route, middleware, or seed user found. |
| 4 | No dashboard page for MVP | Pass | `routes/web.php`, `resources/views` | No dashboard route or dashboard Blade view found. |
| 5 | Homepage `/` directly shows Service Note Form | Pass | `routes/web.php`, `resources/views/home.blade.php` | `/` maps to `ServiceNoteController@index`; home view shows form immediately. |
| 6 | Homepage includes Search Old Records | Pass | `resources/views/home.blade.php` | Search panel and results table are on the same page. |
| 7 | Staff/user can create service note without login | Partial | `routes/web.php`, `ServiceNoteController@store` | Store route is public and implementation exists; runtime submit not tested. |
| 8 | `service_no` auto-generates as `SN-YYYY-0001` | Pass | `app/Services/ServiceNumberGenerator.php`, `ServiceNoteController.php` | Uses yearly prefix and 4-digit sequence with `lockForUpdate()`. Runtime concurrency not tested. |
| 9 | Customer data saves into database | Partial | `ServiceNoteController@store`, `Customer.php` | Store flow saves customer by phone; runtime DB save not tested. |
| 10 | Device data saves into database | Partial | `ServiceNoteController@store`, `Device.php` | Store flow saves device and password field; runtime DB save not tested. |
| 11 | Service note data saves into database | Partial | `ServiceNoteController@store`, `ServiceNote.php` | Store flow creates service note and total; runtime DB save not tested. |
| 12 | Old records searchable from database | Pass | `ServiceNoteController@index`, `home.blade.php` | Searches service no, issue, date, status, technician, customer, phone, email, brand/model, serial; filters status/device/date. |
| 13 | User can view old service note detail | Pass | `routes/web.php`, `service-notes/show.blade.php` | Detail route and view exist. Runtime route rendering not tested. |
| 14 | User can edit old service note | Pass | `routes/web.php`, `service-notes/edit.blade.php`, `ServiceNoteController@update` | Edit/update route and form exist. Runtime update not tested. |
| 15 | User can generate PDF | Partial | `ServiceNoteController@pdf`, `service-notes/pdf.blade.php` | DomPDF integration exists; runtime generation not tested. |
| 16 | User can download PDF | Partial | `ServiceNoteController@pdf`, `home.blade.php`, `show.blade.php` | Download path returns `$pdf->download(...)`; runtime download not tested. |
| 17 | User can print PDF | Partial | `ServiceNoteController@pdf`, `home.blade.php`, `show.blade.php` | `?print=1` streams PDF; runtime print/stream not tested. |
| 18 | PDF must not show `device_password` | Pass | `resources/views/service-notes/pdf.blade.php` | PDF template has no `device_password` reference. |
| 19 | Company settings usable in PDF/header | Partial | `SettingsController.php`, `settings.blade.php`, `pdf.blade.php`, `layouts/app.blade.php` | PDF uses settings. Web header still hard-codes `LaptopPro`; logo setting is missing. |
| 20 | Docker Compose must work | Pass | `docker-compose.yml`, `Dockerfile`, `docker/entrypoint.sh` | Docker runtime verified on 2026-05-10: app/database start, `/` returns HTTP 200, migrations are applied, and container test suite passes. |
| 21 | Data remains after Docker restart | Partial | `docker-compose.yml` | `database_data` volume exists. Persistence not runtime-tested. |
| 22 | README explains install, usage, backup, restore | Partial | `README.md` | Has install/usage/backup/restore. Missing uploaded files backup/restore and network protection guidance from PRD. |

## Codebase Findings

### Routes
- `/` maps directly to `ServiceNoteController@index`.
- Public CRUD routes exist for service notes: create, view, edit, update, delete, PDF.
- `/settings` GET/PUT exists.
- No login, logout, dashboard, admin, or role route found.
- PRD lists `/backup-guide`, but no `/backup-guide` route exists.

### Controllers
- `ServiceNoteController@index` builds search filters and eager-loads customer/device.
- `store` validates input, reuses customer by phone, saves device, generates service number, calculates total charge, and logs `created`.
- `update` validates input, updates customer/device/service note, preserves device password if blank, recalculates total, and logs `updated`.
- `destroy` validates service number confirmation, logs `deleted`, and soft-deletes.
- `pdf` loads settings, renders DomPDF, streams for print, downloads otherwise, and logs `printed`/`downloaded`.
- `SettingsController` can show and save settings, but it does not handle logo.

### Models
- `Customer`, `Device`, `ServiceNote`, `ServiceNoteLog`, and `Setting` models exist.
- Relationships match PRD: customer has devices/service notes, device belongs to customer and has service notes, service note belongs to customer/device and has logs.
- `Device` hides `device_password` in serialized output.

### Migrations
- Required tables exist: `customers`, `devices`, `service_notes`, `service_note_logs`, `settings`.
- `service_no` is unique.
- Important indexes exist on customer name/phone, device brand/serial, service date/status/technician.
- `service_notes` supports soft deletes.
- Extra `audit_logs` migration exists and includes `user_id` comment for possible auth later; this is not required by PRD and conflicts with the no-user MVP direction.

### Views
- `home.blade.php` contains service note form and search on the same page.
- Detail, edit, settings, layout views exist.
- The create form shows `Save Service Note`, `Save & Download PDF`, and `Clear Form / New Form`.
- PRD names main buttons as `Save & Generate PDF`, `Print PDF`, `Download PDF`; print/download are available after records exist, not as active pre-save buttons.
- Navigation includes Main Form, Search Records, Settings. It omits Backup Guide, which PRD lists.

### PDF
- A4 print-friendly PDF template exists.
- PDF includes company, service, customer, device, issue, diagnosis, repair action, parts, charges, warranty, and signature sections.
- Company settings are used in PDF header and footer.
- `device_password` is not rendered.
- Runtime PDF generation not tested due to local PHP/Docker limitation.

### Search
- Keyword search supports service number, reported issue, received date, status, technician, customer name/phone/email, device brand/model, and serial number.
- Filters support status, device type, received date from, and received date to.
- Results table includes the columns required by PRD and actions for View/Edit/Print/Download.

### Docker
- `docker-compose.yml` defines app and MariaDB services.
- `database_data` volume persists MariaDB data.
- `vendor` volume persists Composer dependencies in the container.
- `docker/entrypoint.sh` installs dependencies, creates `.env`, generates key, runs migrations, seeds default settings, and starts Laravel.
- `docker compose config --quiet` passes.
- `docker compose up -d --build app` now passes with Docker Desktop/Linux engine active.
- App is reachable at `http://localhost:8000` and returns HTTP 200.

### README
- README has install, usage, Docker, backup, and restore sections.
- README states database persistence using `database_data`.
- README lacks uploaded files/signature backup and restore steps.
- README lacks PRD security guidance for network-level protection despite public/no-login mode.

### Security
- CSRF tokens are present on forms.
- Controller validation exists.
- Blade output uses escaped `{{ }}` output.
- Database queries use Eloquent/query builder, not raw SQL string concatenation.
- Device password is hidden in detail and PDF.
- Public no-login mode is intentional, but README should explicitly recommend VPN/Tailscale/Cloudflare Access/basic auth/IP allowlist.

### Mobile UI
- Layout uses responsive Tailwind classes and one-column behavior at smaller breakpoints.
- Search is placed beside the form on large screens and below/stacked on smaller screens.
- Runtime mobile browser testing was not performed.

## Bugs Found

### Docker runtime verification gap
- Severity: Critical
- Status: Fixed on 2026-05-10 22:19:37 +08:00
- File path: `.env.example`, `docker-compose.yml`, `docker/entrypoint.sh`, `app/Http/Controllers/ServiceNoteController.php`, Blade views with validation error output
- What was wrong: Docker Desktop/Linux engine was initially unavailable. After Docker started, the generated `.env` kept `SESSION_DRIVER=database`, which caused no-auth MVP pages to hit Laravel auth session handling. Middleware-disabled tests also exposed route model binding and missing error-bag assumptions.
- Fix applied: Docker app uses file sessions, the entrypoint normalizes generated `.env` session driver, views tolerate a missing error bag, and service note routes defensively resolve route models when binding middleware is disabled.
- Verification: `docker compose up -d --build app` passed, `/` returns HTTP 200, `docker compose exec app php artisan migrate:status` shows all migrations ran, `docker compose exec app php artisan route:list` shows no login/dashboard routes, and `docker compose exec app php artisan test` passes with 6 tests and 41 assertions.

### PHP/Composer runtime commands cannot run locally
- Severity: Critical
- File path: environment
- What is wrong: `php`, `composer`, `php artisan route:list`, and `php artisan test` fail because PHP/Composer are not installed locally.
- How to fix: Use Docker container commands after Docker starts, or install PHP/Composer locally.

### Missing `/backup-guide` route/page
- Severity: High
- File path: `routes/web.php`, `resources/views/layouts/app.blade.php`
- What is wrong: PRD requires `/backup-guide` and navigation item. Current app has no route/page and nav omits it.
- How to fix: Add a public backup guide route, Blade page, and nav link with database and uploaded-file backup/restore guidance.

### README backup/restore is incomplete for uploaded files
- Severity: High
- File path: `README.md`
- What is wrong: README covers database backup/restore but not uploaded files/signatures/PDF snapshots as required by PRD.
- How to fix: Add commands and notes for backing up/restoring `storage/app` or the upload directory used by signatures/PDF snapshots.

### Web header does not use company settings
- Severity: Medium
- File path: `resources/views/layouts/app.blade.php`
- What is wrong: Header still hard-codes `LaptopPro`; PRD requires company settings to be usable in header and PDF.
- How to fix: Load settings globally or via a view composer and render `company_name` in the layout.

### Settings page missing logo field
- Severity: Medium
- File path: `resources/views/settings.blade.php`, `SettingsController.php`, `settings` migration
- What is wrong: PRD settings fields include `logo`, but current settings form/controller do not support logo.
- How to fix: Add logo upload/path setting if required for MVP, or document as deferred.

### Extra `audit_logs` migration conflicts with no-user MVP direction
- Severity: Medium
- File path: `database/migrations/2026_05_10_000001_create_audit_logs_table.php`
- What is wrong: Extra audit log table is outside the PRD database design and includes `user_id` for possible auth later.
- How to fix: Remove unused migration or align with `service_note_logs`; avoid user/auth references in MVP.

### Update flow may reassign an existing device when customer phone changes
- Severity: Medium
- File path: `app/Http/Controllers/ServiceNoteController.php`
- What is wrong: If editing a service note and changing customer phone with no matching serial device, the existing service note device may be associated to the new customer. This can affect other service notes sharing that device.
- How to fix: When customer changes, create a new device unless the selected existing device truly belongs to the target customer.

### Delete warning text does not exactly match PRD and says restore is not built
- Severity: Low
- File path: `resources/views/service-notes/show.blade.php`
- What is wrong: PRD asks for clear irreversible confirmation. Current warning is close but says “irreversible sehingga fungsi restore dibina,” while soft delete means restore could be implemented later.
- How to fix: Use clearer copy: “Are you sure you want to delete this service note? This action cannot be undone from the app.”

### Create form button labels differ from PRD
- Severity: Low
- File path: `resources/views/home.blade.php`
- What is wrong: PRD lists `Save & Generate PDF`, `Print PDF`, and `Download PDF`. Current create form uses `Save & Download PDF` and only exposes Print/Download on saved records.
- How to fix: Decide expected UX. If strict PRD alignment is needed, rename to `Save & Generate PDF` and expose post-save print/download actions in the success flow.

## PRD Conflicts Found

- No conflict found for login/dashboard in current `PRD.md`; it consistently says no login, no authentication, no dashboard, and no role system for MVP.
- `config/auth.php` still contains Laravel framework comments about auth/users, but app routes and migrations do not create a user/auth workflow. This is framework boilerplate rather than an active PRD conflict.
- `database/migrations/2026_05_10_000001_create_audit_logs_table.php` contains a `user_id` field comment “if auth added later”; this is an outdated/future-auth reference and should be removed or deferred outside MVP.

## Test Commands Run

- `Get-Content -Raw 'Audit Project.md'` — passed.
- `Get-Content -Raw PRD.md` — passed.
- `rg --files` — passed.
- `rg -n -i "login|dashboard|auth|role|user|admin|staff|password|users" PRD.md routes app resources database config README.md` — passed; found PRD no-login rules, framework auth comments, device password references, and audit migration `user_id`.
- `Get-Content routes\web.php` — passed.
- `Get-Content app\Http\Controllers\ServiceNoteController.php` — passed.
- `Get-Content app\Http\Controllers\SettingsController.php` — passed.
- `Get-Content resources\views\layouts\app.blade.php` — passed.
- `Get-Content README.md` — passed.
- `Get-Content app\Models\*.php` relevant models — passed.
- `Get-Content database\migrations\*.php` relevant migrations — passed.
- `Get-Content database\seeders\*.php` — passed.
- `Get-Content resources\views\home.blade.php` — passed.
- `Get-Content resources\views\service-notes\show.blade.php` — passed.
- `Get-Content resources\views\service-notes\edit.blade.php` — passed.
- `Get-Content resources\views\service-notes\pdf.blade.php` — passed.
- `Get-Content resources\views\settings.blade.php` — passed.
- `Get-Content docker-compose.yml` — passed.
- `Get-Content Dockerfile` — passed.
- `Get-Content docker\entrypoint.sh` — passed.
- `Get-Content .env.example` — passed.
- `Get-Content composer.json; Get-Content package.json` — passed.
- `npm run build` — passed.
- `docker compose config --quiet` — passed.
- `docker version` — failed; Docker Desktop Linux engine unavailable.
- `php -v` — failed; PHP not installed locally.
- `composer --version` — failed; Composer not installed locally.
- `php artisan route:list` — failed; PHP not installed locally.
- `php artisan test` — failed; PHP not installed locally.
- `docker compose up -d --build` — failed; Docker Desktop Linux engine unavailable.
- `Get-Date -Format 'yyyy-MM-dd HH:mm:ss zzz'` — passed; audit timestamp `2026-05-10 22:01:48 +08:00`.

## Critical Gap 1 Fix Verification - 2026-05-10 22:19:37 +08:00

- `Start-Process -FilePath 'C:\Program Files\Docker\Docker\Docker Desktop.exe' -WindowStyle Hidden` - passed; Docker Desktop processes started.
- `docker version` - passed after Docker Desktop/Linux engine became available.
- `docker compose up -d --build` - passed; app and database containers started.
- `docker compose up -d --build app` - passed after code fixes; app container recreated successfully.
- `docker compose ps` - passed; app is up and database is healthy.
- `docker compose exec app printenv SESSION_DRIVER` - passed; value is `file`.
- `docker compose exec app sh -lc "grep '^SESSION_DRIVER=' .env"` - passed; generated `.env` now has `SESSION_DRIVER=file`.
- `docker compose exec app php artisan migrate:status` - passed; all migrations ran.
- `docker compose exec app php artisan route:list` - passed; no login/dashboard routes present.
- `Invoke-WebRequest -Uri http://localhost:8000 -UseBasicParsing -TimeoutSec 30` - passed; HTTP 200.
- `docker compose exec app php artisan test` - passed; 6 tests, 41 assertions.
- `npm run build` - passed.

## Final Recommendation

The Docker runtime blocker is fixed. Next resolve the remaining critical local PHP/Composer verification gap or document Docker as the required runtime path. Then implement `/backup-guide` and complete README backup/security guidance. After that, align company settings with the web header/logo and run full end-to-end tests for create, search, view, edit, PDF download/print, settings, and Docker restart persistence.
