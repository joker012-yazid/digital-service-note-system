# Gap List

## Critical Gaps
- [x] Docker runtime is verified. Docker Desktop/Linux engine is running, `docker compose up -d --build app` starts the app, `/` returns HTTP 200, migrations are applied, and `docker compose exec app php artisan test` passes.
- [ ] Local Laravel runtime commands still cannot be verified because local PHP/Composer are not installed; Docker-based Laravel commands are now verified.

## High Priority Gaps
- [ ] Missing `/backup-guide` route and Backup Guide page required by PRD.
- [ ] README backup/restore does not cover uploaded files, signatures, or PDF snapshots.
- [ ] Full end-to-end tests for create, search, view, edit, PDF download/print, settings, and Docker persistence have not been run.

## Medium Priority Gaps
- [ ] Web header still hard-codes `LaptopPro` instead of using company settings.
- [ ] Settings page does not support the PRD `logo` field.
- [ ] README does not include public/no-login network protection guidance such as VPN, Tailscale, Cloudflare Access, reverse proxy basic auth, or IP allowlist.
- [ ] Extra `audit_logs` migration includes a future-auth `user_id` reference and is not part of the PRD MVP database design.
- [ ] Edit/update flow may reassign an existing device to a different customer when customer phone changes.
- [ ] Customer/device history pages are not implemented.
- [ ] Runtime mobile UI testing has not been performed.

## Low Priority Gaps
- [ ] Create form button labels do not exactly match PRD wording: `Save & Download PDF` vs `Save & Generate PDF`.
- [ ] Delete warning copy can be made clearer and closer to PRD wording.
- [ ] PDF preview is not implemented; PRD marks it practical/optional.
- [ ] Signature capture/upload is only represented as placeholders/columns, not an actual signature pad.
