# TASKS — Digital Service Note System

Project will be built task by task. Complete one task, update `PROGRESS.md`, update this file, then stop for the next instruction.

## Task Status

- [x] TASK 00 — Project Inspection / Setup Planning
- [x] TASK 01 — Laravel + Docker Compose Base Setup
- [x] TASK 02 — Environment, Database Connection, and README Setup
- [x] TASK 03 — Database Migrations and Models
- [x] TASK 04 — Seeders for Default Settings and Sample Data
- [x] TASK 05 — Main Homepage Route and Layout
- [x] TASK 06 — Service Note Form UI
- [x] TASK 07 — Store Service Note into Database
- [x] TASK 08 — Auto Service Number Generator
- [x] TASK 09 — Search Old Records on Homepage
- [x] TASK 10 — View Service Note Detail Page
- [x] TASK 11 — Edit and Update Service Note
- [x] TASK 12 — Delete Service Note with Confirmation
- [x] TASK 13 — PDF Template and PDF Generation
- [x] TASK 14 — Print and Download PDF Buttons
- [ ] TASK 15 — Company Settings Page
- [ ] TASK 16 — Customer and Device History
- [ ] TASK 17 — Audit Log
- [ ] TASK 18 — Backup Guide Page and README Backup Section
- [ ] TASK 19 — Mobile Responsive UI Polish
- [ ] TASK 20 — Full Testing and Bug Fixing
- [ ] TASK 21 — Final Documentation and Handover Summary

## TASK 00 — Project Inspection / Setup Planning

Goal:
- Check whether the project folder is empty or already has code.
- If empty, prepare to create a new Laravel project.
- If existing, inspect files and determine current progress.

Findings:
- Project folder currently contains documentation only:
  - `PRD.md`
  - `Pembahagian Tugasan.md`
- No Laravel application exists yet.
- No Docker Compose setup exists yet.
- No Git repository is initialized in this folder.
- Reference PDF exists at `c:\Users\Jokeryazid\Downloads\servicenote_one_service.pdf`.
- PDF is image-based/scanned; text extraction returned no text.
- Visual PDF reference shows:
  - Header: LaptopPro, Service Report, address, phone, office phone, email.
  - Top right: No. Service.
  - Left top section: Maklumat Pelanggan / Customer Information.
  - Right top section: Maklumat Peranti / Device Information.
  - Main sections: Masalah Dilaporkan, Pemeriksaan Awal, Kerja Baik Pulih.
  - Bottom sections: Alat Ganti, Kos Baiki, Waranti Servis, customer signature, technician signature.

Deliverables:
- `TASKS.md`
- `PROGRESS.md`
- Initial implementation plan

Status:
- Completed.

Next task:
- TASK 01 — Laravel + Docker Compose Base Setup

## TASK 01 — Laravel + Docker Compose Base Setup

Goal:
- Create Laravel project if not existing.
- Add Docker Compose setup.
- Add app container.
- Add database container using MariaDB/MySQL.
- Ensure persistent database volume.

Deliverables:
- Laravel base app.
- `docker-compose.yml`.
- Dockerfile if needed.
- Basic app can start.

Status:
- Completed with environment limitation: Docker Compose configuration validates, but `docker compose up -d --build` could not start because the Docker Desktop Linux engine / Docker daemon is not running on this machine.

Stop after this task.

## TASK 02 — Environment, Database Connection, and README Setup

Goal:
- Configure `.env.example`.
- Configure database connection.
- Add README initial setup.
- Add basic Docker commands.

Deliverables:
- `.env.example`.
- README installation section.
- Confirm app can connect to DB.

Status:
- Completed with environment limitation: `.env.example` and README are configured, and `docker compose config --quiet` validates successfully. Actual app-to-database connection could not be confirmed because Docker Desktop Linux engine / Docker daemon is not running on this machine.

Stop after this task.

## TASK 03 — Database Migrations and Models

Goal:
- Create migrations and Eloquent models for Customer, Device, ServiceNote, ServiceNoteLog, and Setting.
- Add relationships between customers, devices, and service notes.
- Add indexes for common search fields.

Deliverables:
- Migration files.
- Model files.
- Relationships.

Status:
- Completed with environment limitation: migrations and models were created manually, and Docker Compose configuration still validates. PHP syntax checks and `php artisan migrate` could not be run because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 04 — Seeders for Default Settings and Sample Data

Goal:
- Create default settings.
- Create sample customer.
- Create sample device.
- Create sample service note.
- Do not create login users.

Deliverables:
- Seeder files.
- Sample data.

Status:
- Completed with environment limitation: seeder files were created and wired into `DatabaseSeeder`, but `php artisan db:seed` could not be run because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 05 — Main Homepage Route and Layout

Goal:
- Create route `/`.
- Homepage must directly show Service Note Form + Search section.
- Create main Blade layout.
- Add Tailwind CSS.
- No dashboard and no login page.

Deliverables:
- Main layout.
- Homepage view.

Status:
- Completed with environment limitation: route, Blade layout, homepage view, and Vite/Tailwind build assets were created. PHP route rendering could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 06 — Service Note Form UI

Goal:
- Build the full service note form UI using Bahasa Melayu labels.
- Match the PDF reference layout where practical.
- Add frontend total charge calculation.
- Add device password warning.

Deliverables:
- Complete responsive service note form UI.

Status:
- Completed with environment limitation: full responsive form UI and frontend calculation script were built, and `npm run build` passed. PHP route rendering/browser testing could not be run because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 07 — Store Service Note into Database

Goal:
- Validate form submission.
- Save customer, device, and service note.
- Reuse customer by phone.
- Reuse device if suitable.
- Calculate total charge in backend.

Deliverables:
- Controller store method.
- Validation.
- Database save working.

Status:
- Completed with environment limitation: controller store method, validation, form POST, customer/device reuse, total calculation, and created log code were implemented. Runtime database save could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running. Service number uses a temporary unique format in this task; official `SN-YYYY-0001` generation is reserved for TASK 08.

Stop after this task.

## TASK 08 — Auto Service Number Generator

Goal:
- Implement format `SN-YYYY-0001`.
- Keep `service_no` unique.
- Reset number yearly.
- Reduce duplicate risk.

Deliverables:
- Service number generator class/function.
- Store flow uses generated number.

Status:
- Completed with environment limitation: `ServiceNumberGenerator` now generates `SN-YYYY-0001` style numbers from existing yearly records and the store flow uses it. Runtime generation/database save could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 09 — Search Old Records on Homepage

Goal:
- Add search box and filters where practical.
- Search service notes by service number, customer, phone, email, model, serial number, issue, date, status, and technician.
- Show result actions.

Deliverables:
- Search backend.
- Search UI.
- Search result table.

Status:
- Completed with environment limitation: homepage search backend, filters, and result table were implemented. Frontend assets build successfully and Docker Compose configuration validates. Runtime search/database behavior could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 10 — View Service Note Detail Page

Goal:
- Show full service note details.
- Hide device password by default.
- Add action buttons.

Deliverables:
- Detail route.
- Detail controller.
- Detail Blade page.

Status:
- Completed with environment limitation: detail route, controller method, and Blade detail page were implemented. The detail page hides device password by default and includes action buttons. Frontend assets build successfully and Docker Compose configuration validates. Runtime detail page rendering could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 11 — Edit and Update Service Note

Goal:
- Create edit page.
- Allow editing normal fields.
- Keep service number readonly.
- Recalculate total charge.
- Add update log.

Deliverables:
- Edit route.
- Update route.
- Edit form.
- Update controller.

Status:
- Completed with environment limitation: edit route, update route, edit form, and update controller were implemented. Service number remains readonly, total charge is recalculated in backend, and update log creation was added. Frontend assets build successfully and Docker Compose configuration validates. Runtime edit/update behavior could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 12 — Delete Service Note with Confirmation

Goal:
- Add delete option if practical.
- Use soft delete if available.
- Add irreversible action confirmation text.
- Add delete log.

Deliverables:
- Delete route.
- Delete button.
- Confirmation.

Status:
- Completed with environment limitation: delete route, delete button, confirmation text, service number confirmation input, soft delete, and delete log creation were implemented. Frontend assets build successfully and Docker Compose configuration validates. Runtime delete behavior could not be tested because PHP is not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 13 — PDF Template and PDF Generation

Goal:
- Install/configure PDF package.
- Create A4 service report PDF template based on PDF reference.
- Exclude device password.

Deliverables:
- PDF Blade template.
- PDF generation route/controller.

Status:
- Completed with environment limitation: DomPDF package requirement was added to `composer.json`, Dockerfile was prepared with required PHP extensions, PDF route/controller method was added, and A4 PDF Blade template was created. Runtime PDF generation could not be tested because Composer and PHP are not installed locally and Docker Desktop Linux engine / Docker daemon is not running.

Stop after this task.

## TASK 14 — Print and Download PDF Buttons

Goal:
- Add Download PDF button.
- Add Print PDF button.
- Use filename `service-note-{service_no}-{customer_name}.pdf`.
- Log downloaded/printed where possible.

Deliverables:
- Working PDF download.
- Print-friendly route/view.

Stop after this task.

## TASK 15 — Company Settings Page

Goal:
- Create public settings page.
- Allow update of company details and default warranty note.
- Use settings in header and PDF.

Deliverables:
- Settings route.
- Settings form.
- Settings update logic.

Stop after this task.

## TASK 16 — Customer and Device History

Goal:
- Customer detail page shows previous service notes.
- Device history page if practical.

Deliverables:
- Customer history page.
- Device history page or linked service list.

Stop after this task.

## TASK 17 — Audit Log

Goal:
- Log created, updated, deleted, downloaded, and printed actions where possible.

Deliverables:
- Log creation in relevant actions.
- Basic log display if practical.

Stop after this task.

## TASK 18 — Backup Guide Page and README Backup Section

Goal:
- Create Backup Guide page.
- Add README backup/restore section for database and uploaded files.

Deliverables:
- Backup guide page.
- README backup section.

Stop after this task.

## TASK 19 — Mobile Responsive UI Polish

Goal:
- Improve mobile layout.
- Ensure form and buttons are usable on phone.
- Keep search below form on mobile.

Deliverables:
- Responsive UI fixes.

Stop after this task.

## TASK 20 — Full Testing and Bug Fixing

Goal:
- Test create, search, view, edit, PDF, download, print, and Docker persistence.
- Fix discovered bugs.

Deliverables:
- Tested working MVP.
- Updated progress notes.

Stop after this task.

## TASK 21 — Final Documentation and Handover Summary

Goal:
- Finalize README.
- Explain installation, usage, backup/restore, limitations, and future improvements.

Deliverables:
- Final README.
- Final PROGRESS.md.
- Handover summary.

Stop after this task.
