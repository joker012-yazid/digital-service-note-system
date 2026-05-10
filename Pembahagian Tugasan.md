You are a senior full-stack developer and technical project manager.

I want you to build a project called “Digital Service Note System” based on the PRD below.

VERY IMPORTANT WORKING STYLE:
Do NOT build everything in one long run.

Because Codex may have token/time limitations, you must split the project into small, resumable development tasks.

You must work task by task.

After completing each task:
1. Update `PROGRESS.md`.
2. Update `TASKS.md`.
3. Write what was completed.
4. Write what files were created/changed.
5. Write what commands were run.
6. Write what errors happened, if any.
7. Write the next recommended task.
8. Stop and wait for the next instruction.

If you are continuing from an existing project:
1. Read `PROGRESS.md`.
2. Read `TASKS.md`.
3. Inspect the current codebase.
4. Continue from the next incomplete task.
5. Do not repeat completed work unless required to fix errors.

==================================================
PROJECT NAME
==================================================

Digital Service Note System

==================================================
PROJECT GOAL
==================================================

Build a web-based service note system for a computer repair shop.

The system replaces a manual/PDF service note form.

Main workflow:
Staff open website → service note form appears immediately → staff fills the form → data is saved into database → system generates PDF → staff can print/download PDF → staff can search old records from database.

==================================================
IMPORTANT PRODUCT DECISIONS
==================================================

- No login page.
- No authentication.
- No admin/staff role system for MVP.
- No dashboard.
- Homepage `/` must directly show the Service Note Form.
- Search old records must be available on the same homepage.
- The system is intended for local shop/internal use first.
- Deploy using Docker Compose.
- Use Laravel + MariaDB/MySQL + Blade + Tailwind CSS.
- Use PDF generation package such as DomPDF or equivalent.
- Prioritize working MVP over advanced features.

==================================================
PRD SUMMARY
==================================================

Core requirements:
1. Public web form without login.
2. Homepage directly shows service note form.
3. Same homepage has search section for old records.
4. Auto-generate service number using format `SN-YYYY-0001`.
5. Save customer data into database.
6. Save device data into database.
7. Save service note data into database.
8. Support view/edit old service notes.
9. Generate professional A4 PDF service report.
10. Print/download PDF.
11. Search records by service number, customer name, phone, model, serial number, issue, date, status.
12. Company settings page.
13. Backup guide.
14. Docker Compose deployment.
15. Mobile responsive UI.

==================================================
SERVICE NOTE FORM FIELDS
==================================================

A. Service Information
- service_no, auto-generated, unique, readonly.
- received_date, required, default today.
- status, required dropdown.
- technician_name, text field.

Status options:
- Received
- Checking
- Waiting Customer Approval
- In Progress
- Waiting Parts
- Completed
- Collected
- Cancelled

B. Customer Information
- customer_name, required.
- customer_phone, required.
- customer_email, optional.
- customer_address, optional textarea.

C. Device Information
- device_type, required dropdown.
- device_type_other, required only if Others.
- brand_model, required.
- serial_number, optional.
- specifications, optional textarea.
- device_password, optional sensitive field.
- device_password must not appear in generated PDF by default.

Device type options:
- Laptop
- Desktop
- Printer
- Monitor
- Phone
- Tablet
- Others

D. Customer Reported Issue
- reported_issue, required textarea.

E. Initial Diagnosis
- initial_diagnosis, optional textarea.

F. Repair Action Taken
- repair_action, optional textarea.

G. Parts Replaced
- parts_replaced, optional textarea.

H. Charges
- service_charge, decimal, default 0.00.
- parts_charge, decimal, default 0.00.
- total_charge, auto-calculate service_charge + parts_charge.

I. Warranty
- warranty_duration, optional number.
- warranty_unit, dropdown: Hari / Bulan.
- warranty_note, optional textarea.
Default note:
“Tidak termasuk kerosakan fizikal / liquid damage.”

J. Signatures
- customer_signature_path, optional.
- technician_signature_path, optional.
For MVP, prepare columns and UI placeholder if full signature pad is not implemented.

==================================================
DATABASE TABLES
==================================================

Create these main tables:

1. customers
- id
- name
- phone
- email
- address
- timestamps

2. devices
- id
- customer_id
- device_type
- device_type_other
- brand_model
- serial_number
- specifications
- device_password
- timestamps

3. service_notes
- id
- service_no
- customer_id
- device_id
- received_date
- reported_issue
- initial_diagnosis
- repair_action
- parts_replaced
- service_charge
- parts_charge
- total_charge
- warranty_duration
- warranty_unit
- warranty_note
- status
- technician_name
- customer_signature_path
- technician_signature_path
- pdf_original_path
- timestamps
- soft deletes if suitable

4. service_note_logs
- id
- service_note_id
- action
- description
- created_at

5. settings
- id
- key
- value
- timestamps

Do not create user management for MVP.

==================================================
TASK BREAKDOWN RULES
==================================================

Create `TASKS.md` with this task list.

Each task must be small enough to finish safely.

Do not continue to the next task unless I ask you to continue.

Use this exact task structure:

TASK 00 — Project Inspection / Setup Planning
TASK 01 — Laravel + Docker Compose Base Setup
TASK 02 — Environment, Database Connection, and README Setup
TASK 03 — Database Migrations and Models
TASK 04 — Seeders for Default Settings and Sample Data
TASK 05 — Main Homepage Route and Layout
TASK 06 — Service Note Form UI
TASK 07 — Store Service Note into Database
TASK 08 — Auto Service Number Generator
TASK 09 — Search Old Records on Homepage
TASK 10 — View Service Note Detail Page
TASK 11 — Edit and Update Service Note
TASK 12 — Delete Service Note with Confirmation
TASK 13 — PDF Template and PDF Generation
TASK 14 — Print and Download PDF Buttons
TASK 15 — Company Settings Page
TASK 16 — Customer and Device History
TASK 17 — Audit Log
TASK 18 — Backup Guide Page and README Backup Section
TASK 19 — Mobile Responsive UI Polish
TASK 20 — Full Testing and Bug Fixing
TASK 21 — Final Documentation and Handover Summary

==================================================
TASK DETAILS
==================================================

TASK 00 — Project Inspection / Setup Planning
Goal:
- Check whether the project folder is empty or already has code.
- If empty, prepare to create new Laravel project.
- If existing, inspect files and determine current progress.
Deliverables:
- Create/update `TASKS.md`.
- Create/update `PROGRESS.md`.
- Write project plan.
Stop after this task.

TASK 01 — Laravel + Docker Compose Base Setup
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
Stop after this task.

TASK 02 — Environment, Database Connection, and README Setup
Goal:
- Configure `.env.example`.
- Configure database connection.
- Add README initial setup.
- Add basic Docker commands.
Deliverables:
- `.env.example`.
- README installation section.
- Confirm app can connect to DB.
Stop after this task.

TASK 03 — Database Migrations and Models
Goal:
Create migrations and Eloquent models for:
- Customer
- Device
- ServiceNote
- ServiceNoteLog
- Setting

Add relationships:
- Customer has many devices.
- Customer has many service notes.
- Device belongs to customer.
- Device has many service notes.
- ServiceNote belongs to customer.
- ServiceNote belongs to device.

Add indexes for:
- customers.phone
- customers.name
- devices.serial_number
- devices.brand_model
- service_notes.service_no
- service_notes.received_date
- service_notes.status
- service_notes.technician_name

Deliverables:
- Migration files.
- Model files.
- Relationships.
Stop after this task.

TASK 04 — Seeders for Default Settings and Sample Data
Goal:
- Create default settings.
- Create sample customer.
- Create sample device.
- Create sample service note.
Do not create login users.

Default settings:
- company_name: LaptopPro
- address: Address placeholder
- phone: Phone placeholder
- office_phone: Office phone placeholder
- support_email: support@example.com
- default_warranty_note: Tidak termasuk kerosakan fizikal / liquid damage.

Deliverables:
- Seeder files.
- Sample data.
Stop after this task.

TASK 05 — Main Homepage Route and Layout
Goal:
- Create route `/`.
- Homepage must not show dashboard.
- Homepage must directly show Service Note Form + Search section.
- Create main Blade layout.
- Add Tailwind CSS.
Deliverables:
- Main layout.
- Homepage view.
- No dashboard.
- No login page.
Stop after this task.

TASK 06 — Service Note Form UI
Goal:
Build the full service note form UI with sections:
1. Maklumat Service
2. Maklumat Pelanggan
3. Maklumat Peranti
4. Masalah Dilaporkan
5. Pemeriksaan Awal
6. Kerja Baik Pulih
7. Alat Ganti & Kos
8. Warranty & Signature

Requirements:
- Bahasa Melayu labels.
- Responsive layout.
- Default received_date today.
- Default warranty note.
- Auto-calculate total charge on frontend.
- Device password warning.
Deliverables:
- Complete form UI.
Stop after this task.

TASK 07 — Store Service Note into Database
Goal:
- Validate form submission.
- Save customer.
- Save device.
- Save service note.
- Reuse existing customer by phone if found.
- Reuse existing device if suitable.
- Calculate total_charge in backend.
- Show success message.
Deliverables:
- Controller store method.
- Validation.
- Database save working.
Stop after this task.

TASK 08 — Auto Service Number Generator
Goal:
- Implement service number format `SN-YYYY-0001`.
- Make service_no unique.
- Number resets yearly.
- Prevent duplicate numbers as much as possible.
Deliverables:
- Service number generator class/function.
- Store flow uses generated service number.
Stop after this task.

TASK 09 — Search Old Records on Homepage
Goal:
- Add search box on homepage.
- Search database by:
  - service_no
  - customer name
  - customer phone
  - customer email
  - brand_model
  - serial_number
  - reported_issue
  - received_date
  - status
  - technician_name
- Show result table/list.
- Include actions:
  - View
  - Edit
  - Print PDF
  - Download PDF
Deliverables:
- Search backend.
- Search UI.
- Search result table.
Stop after this task.

TASK 10 — View Service Note Detail Page
Goal:
- Create view page for one service note.
- Show full customer, device, and service note details.
- Do not show device_password openly by default.
- Add buttons:
  - Edit
  - Print PDF
  - Download PDF
  - Back
Deliverables:
- Detail route.
- Detail controller.
- Detail Blade page.
Stop after this task.

TASK 11 — Edit and Update Service Note
Goal:
- Create edit page.
- Allow editing normal fields.
- Do not allow editing service_no by default.
- Recalculate total_charge.
- Save changes.
- Add update log.
Deliverables:
- Edit route.
- Update route.
- Edit form.
- Update controller.
Stop after this task.

TASK 12 — Delete Service Note with Confirmation
Goal:
- Add delete option if practical.
- Use soft delete if available.
- Add confirmation message:
  “Are you sure you want to delete this service note? This action cannot be undone.”
- Add delete log.
Deliverables:
- Delete route.
- Delete button.
- Confirmation.
Stop after this task.

TASK 13 — PDF Template and PDF Generation
Goal:
- Install/configure PDF package.
- Create A4 PDF service report template.
- PDF sections:
  - Header with company settings
  - Service information
  - Customer information
  - Device information
  - Reported issue
  - Initial diagnosis
  - Repair action
  - Parts replaced
  - Charges
  - Warranty
  - Signature area
- Do not include device_password in PDF.
Deliverables:
- PDF Blade template.
- PDF generation route/controller.
Stop after this task.

TASK 14 — Print and Download PDF Buttons
Goal:
- Add Download PDF button.
- Add Print PDF button.
- Filename format:
  `service-note-{service_no}-{customer_name}.pdf`
- Log downloaded/printed where possible.
Deliverables:
- Working PDF download.
- Print-friendly view or print route.
Stop after this task.

TASK 15 — Company Settings Page
Goal:
- Create settings page.
- No login required for MVP.
- Allow update:
  - company_name
  - logo
  - address
  - phone
  - office_phone
  - support_email
  - footer_note
  - default_warranty_note
- Use settings in PDF and header.
Deliverables:
- Settings route.
- Settings form.
- Settings update logic.
Stop after this task.

TASK 16 — Customer and Device History
Goal:
- Customer detail page shows previous service notes.
- Device detail/history if practical.
- If autocomplete is too much, add simple history links.
Deliverables:
- Customer history page.
- Device history page or simple linked list.
Stop after this task.

TASK 17 — Audit Log
Goal:
- Log important actions:
  - created
  - updated
  - deleted
  - downloaded
  - printed if possible
- Since no login, log technician_name or system action.
Deliverables:
- Log creation in relevant actions.
- Basic log display if practical.
Stop after this task.

TASK 18 — Backup Guide Page and README Backup Section
Goal:
- Create Backup Guide page.
- Add README backup/restore section.
Include:
- How to backup database.
- How to restore database.
- How to backup uploaded files.
- How to restore uploaded files.
- Docker Compose examples.
Deliverables:
- Backup guide page.
- README backup section.
Stop after this task.

TASK 19 — Mobile Responsive UI Polish
Goal:
- Improve mobile layout.
- Ensure form is easy on phone.
- Buttons are large enough.
- Search appears below form on mobile.
- PDF buttons are easy to find.
Deliverables:
- Responsive UI fixes.
Stop after this task.

TASK 20 — Full Testing and Bug Fixing
Goal:
Test full workflow:
1. Open `/`.
2. Create service note.
3. Search old record.
4. View old record.
5. Edit record.
6. Generate PDF.
7. Download PDF.
8. Print PDF.
9. Restart Docker.
10. Confirm data remains.

Fix all discovered bugs.
Deliverables:
- Tested working MVP.
- Updated progress notes.
Stop after this task.

TASK 21 — Final Documentation and Handover Summary
Goal:
- Finalize README.
- Explain installation.
- Explain usage.
- Explain backup/restore.
- Explain known limitations.
- Explain future improvements.
Deliverables:
- Final README.
- Final PROGRESS.md.
- Handover summary.
Stop after this task.

==================================================
PROGRESS FILE REQUIREMENTS
==================================================

Create `PROGRESS.md`.

After every task, update it using this format:

# Progress — Digital Service Note System

## Current Status
- Current task:
- Completed tasks:
- Next task:
- Last successful command:
- Known errors:
- Known limitations:

## Completed Work
### TASK XX — Task Name
- Date/time:
- Summary:
- Files created:
- Files changed:
- Commands run:
- Test result:
- Notes:

## Resume Instructions
To continue, tell Codex:
“Read PROGRESS.md and TASKS.md, inspect the codebase, then continue with the next incomplete task only.”

==================================================
CODING RULES
==================================================

- Build real working code.
- Do not create fake UI only.
- Keep code clean.
- Use Laravel best practices.
- Use Blade + Tailwind.
- Use Eloquent relationships.
- Use validation.
- Use database indexes.
- Escape output to prevent XSS.
- Use ORM to prevent SQL injection.
- Do not expose device_password in PDF.
- Do not create login page.
- Do not create dashboard.
- Do not create user role system.
- Do not add payment gateway.
- Do not add WhatsApp notification.
- Do not add inventory system.
- Do not overcomplicate MVP.

==================================================
STARTING INSTRUCTION
==================================================

Start with TASK 00 only.

Do not implement the full project yet.

For TASK 00:
1. Inspect the project folder.
2. Determine if it is empty or already has code.
3. Create `TASKS.md`.
4. Create `PROGRESS.md`.
5. Write the full task breakdown.
6. Tell me the next task should be TASK 01.
7. Stop.