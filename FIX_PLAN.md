# Fix Plan

## Phase 1 — Critical Fixes
- [x] Task 1: Start Docker Desktop/Linux engine or move to a Docker-ready host.
- [x] Task 2: Run `docker compose up -d --build` and confirm app starts at `http://localhost:8000`.
- Task 3: Run `docker compose exec app php artisan migrate:fresh --seed`.
- [x] Task 4: Run `docker compose exec app php artisan route:list` and verify no login/dashboard routes.
- [x] Task 5: Run `docker compose exec app php artisan test`.
- Task 6: Manually test create, search, view, edit, PDF download, PDF print, settings update, and Docker restart persistence.

### Phase 1 Update - 2026-05-10 22:19:37 +08:00
- Docker runtime blocker resolved.
- `docker compose up -d --build app` starts the app and database services.
- `http://localhost:8000` returns HTTP 200.
- `docker compose exec app php artisan migrate:status` shows all migrations ran.
- `docker compose exec app php artisan route:list` shows MVP public routes with no login/dashboard routes.
- `docker compose exec app php artisan test` passes: 6 tests, 41 assertions.
- Task 3 and Task 6 remain open because `migrate:fresh --seed` and full manual persistence testing were not part of this first Critical Gap fix.

## Phase 2 — PRD Alignment
- Task 1: Add `/backup-guide` route, navigation link, and Blade page.
- Task 2: Extend README backup/restore docs to include uploaded files/signatures/PDF snapshots and network protection guidance.
- Task 3: Update app layout header to use `company_name` from settings.
- Task 4: Add or explicitly defer the settings `logo` field.
- Task 5: Review and remove the unused `audit_logs` migration or align it with no-auth MVP rules.
- Task 6: Fix edit/update device ownership behavior so changing customer does not unintentionally reassign shared device records.
- Task 7: Decide strict button wording for `Save & Generate PDF` vs `Save & Download PDF` and align UI with PRD.
- Task 8: Add customer/device history pages or mark as deferred if outside current MVP cut.

## Phase 3 — Testing
- Task 1: Add feature tests for backup guide route, settings in web header, and no `/backup-guide` regression.
- Task 2: Add feature tests for create/update device ownership edge cases.
- Task 3: Add PDF assertions that settings appear and `device_password` never appears.
- Task 4: Browser-test desktop and mobile widths for homepage, search results, detail, edit, settings, and PDF actions.
- Task 5: Test Docker persistence by creating a record, running `docker compose restart`, and confirming the record remains.
- Task 6: Re-run `npm run build`, `docker compose config --quiet`, and `docker compose exec app php artisan test` before final handover.
