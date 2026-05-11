# Task Plan

## Objective

Perkemaskan UI/UX service note form supaya nampak lebih moden, kemas, dan ada animasi ringan tanpa menambah ciri baru atau mengubah aliran kerja sedia ada.

## Requirements

- Ikut `Codex/AGENTS.md` dan `Codex/SUBAGENTS.md`.
- Baca `tasks/lessons.md` sebelum coding jika wujud.
- Kekalkan homepage sebagai service note form + search, tanpa dashboard/login.
- Fokus pada form animation dan polish UI sahaja.
- Buat perubahan paling kecil yang betul.
- Verify sebelum final.

## Assumptions

- `tasks/lessons.md` belum wujud, jadi tiada lesson terdahulu untuk diaplikasikan.
- ToolSearch/ruflo MCP tidak tersedia dalam tool aktif sesi ini.
- Subagent khusus bernama `code_mapper`, `frontend_engineer`, dan `tester/reviewer` tidak tersedia secara literal; subagent Codex `explorer`, `worker`, dan semakan utama akan digunakan mengikut maksud peranan.
- Perubahan dijangka melibatkan Blade/CSS/JS sahaja, bukan backend atau database.

## Steps

- [x] Inspect fail UI yang relevan dan tentukan skop minimum.
- [x] Kemas kini styling/animation form dengan perubahan kecil yang konsisten.
- [x] Pastikan interaksi sedia ada seperti kiraan jumlah dan conditional field masih berfungsi.
- [x] Jalankan build/check yang relevan.
- [x] Semak hasil akhir dan update Review / Result.

## Verification

- [x] Run `npm run build`.
- [x] Semak fail yang berubah.
- [x] Manual/static check untuk behavior form JS yang disentuh.

## Risks

- Animasi berlebihan boleh mengganggu kerja kaunter yang perlu laju.
- Perubahan CSS global boleh memberi kesan kepada page lain jika selector terlalu luas.
- Build asset mungkin berubah di `public/build` jika build dijalankan.

## Review / Result

### Files Changed

- `resources/css/app.css`
- `resources/views/home.blade.php`
- `tasks/todo.md`
- `tasks/lessons.md`

### Summary

- Added homepage-scoped `home-service-form` styling for a more modern animated form.
- Added staggered fieldset entrance animation, animated top accent bar, focus elevation, smoother input/button transitions, glass-style sticky action bar, and reduced-motion fallback.
- Kept existing `data-service-note-form` and related JS hooks unchanged so total charge calculation and device type conditional field behavior remain intact.
- Fixed reviewer findings by removing global body background styling and removing `overflow: hidden` from the form wrapper to preserve sticky behavior.

### Verification

- Command/check run: `npm run build`
- Result: Passed.
- Command/check run: static review of changed selectors and JS data hooks.
- Result: Passed; homepage-specific class is used and existing JS attributes remain unchanged.
- Command/check run: PHP/Docker runtime availability check.
- Result: Could not run browser/manual Laravel route check because `php` is not installed locally and Docker Desktop Linux engine is not running.

### Known Limitations

- No live browser smoke test was possible in this environment due to missing local PHP and unavailable Docker engine.

### Follow-Up

- When Docker or PHP is available, open `/` and visually confirm the homepage form animation, sticky action bar, reset button, total charge calculation, and `Others` device type field.

---

# Docker Deployment Update

## Objective

Update Docker Desktop local stack supaya perubahan UI/UX form terbaru digunakan oleh app yang berjalan di laptop.

## Requirements

- Gunakan Docker Compose sedia ada.
- Jangan ubah backend/database.
- Rebuild/restart container dengan asset frontend terbaru.
- Verify container status dan akses app jika boleh.

## Steps

- [x] Validate Docker Compose config.
- [x] Rebuild frontend assets.
- [x] Rebuild/restart Docker Compose stack.
- [x] Check container status/logs.
- [x] Update Review / Result.

## Verification

- [x] Run `docker compose config`.
- [x] Run `npm run build`.
- [x] Run `docker compose up -d --build`.
- [x] Run `docker compose ps`.

## Review / Result

### Files Changed

- `tasks/todo.md`

### Summary

- Rebuilt frontend assets with the latest UI/UX changes.
- Rebuilt and restarted the Docker Compose app container in Docker Desktop.
- Confirmed the app is running on port `8000`.

### Verification

- Command/check run: `docker compose config`
- Result: Passed.
- Command/check run: `npm run build`
- Result: Passed.
- Command/check run: `docker compose up -d --build`
- Result: Passed; app container recreated and started.
- Command/check run: `docker compose ps`
- Result: `digital-service-note-app` is up on `0.0.0.0:8000->8000/tcp`; database is healthy.
- Command/check run: `Invoke-WebRequest http://localhost:8000`
- Result: HTTP `200`.
- Command/check run: served HTML/CSS scan.
- Result: `home-service-form` found in served HTML and served CSS asset `assets/app-Cvzsse2y.css`.

### Known Limitations

- Visual browser inspection was not performed in the in-app browser because no browser automation tool is available in this session.

---

# Digital Signature Draw Pad Implementation

## Objective

Implement fungsi tanda tangan digital untuk pelanggan dan teknisyen menggunakan canvas draw pad, simpan sebagai PNG, dan paparkan semula di detail page serta PDF.

## Requirements

- Gunakan column sedia ada `customer_signature_path` dan `technician_signature_path`; tiada migration baru.
- Signature optional supaya service note masih boleh disimpan tanpa signature.
- Gunakan vanilla JavaScript tanpa dependency baru.
- Simpan signature dalam `storage/app/public/signatures`.
- Kekalkan signature lama semasa edit jika tiada signature baru dilukis.
- Pastikan Docker startup menyediakan public storage symlink.

## Steps

- [x] Add reusable signature pad markup for create/edit forms.
- [x] Add canvas drawing JavaScript with mouse/touch/stylus support and blank detection.
- [x] Add controller validation and PNG storage helpers.
- [x] Render saved signatures on detail page and PDF.
- [x] Ensure `storage:link` runs in Docker startup.
- [x] Run build/tests/checks and update Review / Result.

## Verification

- [x] Run `npm run build`.
- [x] Run PHP tests if available.
- [x] Run Docker/container checks if needed.
- [x] Static check saved signature paths and served assets.

## Risks

- Base64 signature validation must reject non-PNG data.
- Canvas scaling must work on mobile/high-DPI screens.
- PDF rendering needs file paths that DomPDF can read.
- Existing sticky form behavior must not be broken by signature pad styling.

## Re-Plan

### What changed

Docker Desktop on Windows failed to create the Laravel public storage symlink with `symlink(): Operation not permitted`, which prevented the app container from starting.

### New plan

- [x] Make Docker startup continue with a clear warning if public storage symlink is blocked.
- [x] Add a Laravel `/public-storage/{path}` fallback route backed by the public disk so saved signatures remain viewable without a filesystem symlink.
- [x] Rebuild/restart Docker and re-run HTTP/storage checks.

### Reason

Signature previews need a public URL, but Windows bind mounts may block symlink creation inside the container.

## Review / Result

### Files Changed

- `app/Http/Controllers/ServiceNoteController.php`
- `docker/entrypoint.sh`
- `resources/js/app.js`
- `resources/css/app.css`
- `resources/views/home.blade.php`
- `resources/views/service-notes/edit.blade.php`
- `resources/views/service-notes/show.blade.php`
- `resources/views/service-notes/pdf.blade.php`
- `resources/views/partials/signature-pad.blade.php`
- `routes/web.php`
- `tests/Feature/ServiceNoteMvpTest.php`
- `tasks/todo.md`
- `tasks/lessons.md`

### Summary

- Implemented optional customer and technician canvas signature pads for create and edit forms.
- Hidden form fields now submit PNG data URLs only when a new signature is drawn.
- Controller validates PNG data, stores signature files under `storage/app/public/signatures`, and saves paths to existing service note columns.
- Edit keeps existing signatures when no new signature is drawn.
- Detail page and PDF now render saved signature images.
- Added `/public-storage/{path}` public disk fallback route for Windows Docker environments where symlink creation is blocked.
- Docker startup now warns and continues if public storage symlink creation is blocked.
- Added feature test coverage for storing, previewing, PDF rendering, and preserving signatures.

### Verification

- Command/check run: `npm run build`
- Result: Passed.
- Command/check run: `docker compose run --rm app php artisan test`
- Result: Passed, 7 tests / 56 assertions.
- Command/check run: `docker compose run --rm app php -l tests/Feature/ServiceNoteMvpTest.php`
- Result: Passed.
- Command/check run: `docker compose run --rm app sh -n docker/entrypoint.sh`
- Result: Passed.
- Command/check run: `docker compose run --rm app php artisan route:list | Select-String -Pattern 'public-storage|storage.public'`
- Result: Passed; `GET|HEAD public-storage/{path}` route is registered.
- Command/check run: `docker compose up -d --build`
- Result: Passed; app and database containers running.
- Command/check run: `Invoke-WebRequest http://localhost:8000`
- Result: HTTP `200`.
- Command/check run: `git diff --check`
- Result: Passed.

### Known Limitations

- Docker Desktop on this Windows host blocks symlink creation for `public/storage`; app uses the `/public-storage/{path}` fallback route for signature previews.
- Browser drawing was not manually tested with touch/stylus automation in this session; canvas behavior is covered by code review and server-side storage tests.
