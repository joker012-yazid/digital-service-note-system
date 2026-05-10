You are a senior full-stack developer. Build a complete working MVP web application called “Digital Service Note System”.

This is not a mockup. Build a real working application that can run locally or on a self-hosted server using Docker Compose.

Project context:
This system is for a computer repair shop. The shop currently uses a manual/PDF service note form. The goal is to convert that workflow into a web-based form where staff can fill in service notes, save all data into a database, generate a PDF similar to the original service note, print/download the PDF, and search old service note records from the database.

System name:
Digital Service Note System

Core workflow:
Staff open website → service note form appears immediately → staff fills the form → data is saved into database → system can generate PDF → staff can print/download PDF → staff can search old records from database.

Important MVP decisions:
- No login page.
- No authentication.
- No admin/staff role system for MVP.
- No dashboard.
- Homepage must directly show the service note form.
- Search old database records must be available on the same homepage.
- Prioritize fast daily shop workflow over analytics or complex menus.

Recommended tech stack:
- Laravel latest stable
- MariaDB or MySQL
- Blade templates
- Tailwind CSS
- Docker Compose
- Laravel ORM / Eloquent
- PDF generation using a reliable Laravel PDF package such as DomPDF or equivalent
- Responsive UI for laptop, tablet, and phone

Deployment target:
The app will be hosted on the user’s own server, likely inside Proxmox using a Debian/Ubuntu VM or container with Docker Compose.

==================================================
1. PUBLIC ACCESS MODE
==================================================

Do not create authentication for MVP.

Requirements:
- Do not create login page.
- Do not create logout.
- Do not create admin/staff roles.
- Do not create user management.
- Do not create default admin/staff seed users.
- Anyone with the system link can access the web form.
- Anyone with the system link can create, view, edit, search, print, and download service notes.
- The app is intended for local shop/internal use first.

Security note:
Even though the app has no login page, protect the system at network/server level. The app is intended for local shop/internal use first. If exposed to the internet, recommend protection using VPN, Tailscale, Cloudflare Access, reverse proxy basic auth, or IP allowlist.

==================================================
2. HOMEPAGE: SERVICE NOTE FORM + DATABASE SEARCH
==================================================

Do not create a dashboard for MVP.

When staff open the web app, the first page must directly show the service note form.

The homepage must contain two main sections:

A. Service Note Web Form
- Show the service note form immediately when the website loads.
- No dashboard page.
- No extra click needed before filling the form.
- Auto-generate service_no.
- Staff can fill customer information.
- Staff can fill device information.
- Staff can fill reported issue.
- Staff can fill initial diagnosis.
- Staff can fill repair action.
- Staff can fill parts replaced.
- Staff can fill service charge, parts charge, and total charge.
- Staff can fill warranty information.
- Staff can add optional customer and technician signatures if practical for MVP.
- Main buttons:
  - Save Service Note
  - Save & Generate PDF
  - Print PDF
  - Download PDF
  - Clear Form / New Form

B. Search Old Records
- Add a search section on the same homepage.
- Staff can search old service notes from the database.
- Search results should appear as a simple table or list.
- Each search result should have actions:
  - View
  - Edit
  - Print PDF
  - Download PDF

Search must support:
- service_no
- customer_name
- customer_phone
- brand_model
- serial_number
- reported_issue
- received_date
- status

Layout requirement:
- On desktop/laptop:
  - Service note form should be the main focus.
  - Search section can be on the right side, top section, or below the form.
- On mobile:
  - Use one-column layout.
  - Show the service note form first.
  - Show search section below the form.

Do not build dashboard summary cards.
Do not show:
- Total service notes today
- Total service notes this month
- Pending/checking jobs
- Completed jobs
- Collected jobs

Default route:
The default route `/` must open the Service Note Form + Search page directly.

==================================================
3. SERVICE NOTE FORM FIELDS
==================================================

Create a form based on a computer repair service report.

A. Service Information
Fields:
- service_no
  - Auto-generated
  - Unique
  - Readonly in form
- received_date
  - Required
  - Default to today
- status
  - Required dropdown
- technician_name
  - Text field
  - Since there is no login, do not auto-detect user

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
Fields:
- customer_name
  - Required
- customer_phone
  - Required
- customer_email
  - Optional
- customer_address
  - Optional textarea

C. Device Information
Fields:
- device_type
  - Required dropdown
- device_type_other
  - Required only if device_type is Others
- brand_model
  - Required
- serial_number
  - Optional but recommended
- specifications
  - Optional textarea
- device_password
  - Optional
  - Sensitive field
  - Must not appear in generated PDF by default

Device type options:
- Laptop
- Desktop
- Printer
- Monitor
- Phone
- Tablet
- Others

D. Customer Reported Issue
Field:
- reported_issue
  - Required textarea

Examples:
- Laptop tidak boleh ON.
- Windows corrupt.
- Keyboard rosak.
- Printer paper jam.
- No display.

E. Initial Diagnosis
Field:
- initial_diagnosis
  - Optional textarea

F. Repair Action Taken
Field:
- repair_action
  - Optional textarea

G. Parts Replaced
For MVP, use textarea first.

Field:
- parts_replaced
  - Optional textarea

Future version may use itemized table:
- Item
- Quantity
- Unit price
- Total

H. Charges
Fields:
- service_charge
  - Optional decimal
  - Default 0.00
- parts_charge
  - Optional decimal
  - Default 0.00
- total_charge
  - Auto-calculate as service_charge + parts_charge
  - Store in database

Currency:
Use RM / Malaysian Ringgit display format.

I. Warranty
Fields:
- warranty_duration
  - Optional number
- warranty_unit
  - Dropdown:
    - Hari
    - Bulan
- warranty_note
  - Optional textarea

Default warranty note:
“Tidak termasuk kerosakan fizikal / liquid damage.”

J. Signatures
For MVP, support optional signatures if practical:
- customer_signature_path
- technician_signature_path

If full signature pad takes too long:
- Prepare database columns
- Add UI placeholder
- Add TODO comment
- Do not block MVP completion

==================================================
4. SAVE SERVICE NOTE WORKFLOW
==================================================

When staff submits the form:

1. Validate input.
2. If customer_phone already exists:
   - Reuse existing customer if possible.
   - Update customer details if new values are provided.
3. If device with same customer_id and serial_number exists:
   - Reuse existing device if suitable.
   - Otherwise create new device.
4. Create service note record.
5. Auto-calculate total_charge.
6. Save all data into database.
7. Show success message.
8. Allow staff to:
   - Continue editing
   - Generate PDF
   - Print PDF
   - Download PDF
   - Clear form for new service note

==================================================
5. SERVICE NUMBER FORMAT
==================================================

Use this format:
SN-YYYY-0001

Examples:
- SN-2026-0001
- SN-2026-0002
- SN-2026-0003

Rules:
- service_no must be unique.
- Number should be based on current year.
- Number can reset yearly.
- Use database-safe logic to avoid duplicate service numbers.

==================================================
6. SEARCH AND OLD RECORDS
==================================================

The homepage must include a search area for old service notes.

Search input:
- Single keyword search box
- Optional filters if easy:
  - status
  - date_from
  - date_to
  - device_type

Search must match:
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

Search result columns:
- No. Service
- Received Date
- Customer Name
- Phone
- Device Type
- Brand / Model
- Status
- Technician Name
- Total Charge
- Actions

Actions:
- View
- Edit
- Print PDF
- Download PDF
- Delete, optional for MVP, but must have confirmation dialog

Important:
Since there is no login and no role system, do not label delete as “admin only” in MVP. If delete is implemented, add a clear confirmation prompt:
“Are you sure you want to delete this service note? This action cannot be undone.”

==================================================
7. VIEW AND EDIT RECORD
==================================================

Each old record must be viewable.

View page should show:
- Full service note information
- Customer information
- Device information
- Charges
- Warranty
- Created date
- Updated date

Edit page:
- Allow editing all normal fields.
- Do not allow editing service_no unless absolutely necessary.
- Recalculate total_charge when service_charge or parts_charge changes.
- Save updated record.
- Log update action.

==================================================
8. PDF GENERATOR
==================================================

Create PDF output that looks like a professional service report/service note.

PDF title:
SERVICE REPORT

Header:
- Company logo placeholder
- Company name: LaptopPro
- Address placeholder
- Phone placeholder
- Office phone placeholder
- Email placeholder

PDF sections:
1. Service Information
   - No. Service
   - Received Date
   - Status
   - Technician Name

2. Customer Information
   - Customer Name
   - Phone
   - Email
   - Address

3. Device Information
   - Device Type
   - Brand / Model
   - Serial Number
   - Specifications

4. Customer Reported Issue

5. Initial Diagnosis

6. Repair Action Taken

7. Parts Replaced

8. Charges
   - Service Charge
   - Parts Charge
   - Total Charge

9. Warranty Service
   - Warranty Duration
   - Warranty Unit
   - Warranty Note

10. Signature Area
   - Customer Signature
   - Technician Signature

PDF requirements:
- A4 size.
- Print-friendly.
- Clean border/table layout.
- Must be readable when printed.
- Download button.
- Print button.
- Filename format:
  service-note-{service_no}-{customer_name}.pdf
- PDF can be regenerated from database anytime.
- Do not expose device_password in the PDF by default.
- Add PDF preview if practical.

PDF storage approach:
- Primary data must be stored in database.
- PDF can be generated on demand.
- Optional: save original PDF snapshot after submission if practical.
- If PDF snapshot is implemented, store path in pdf_original_path.

==================================================
9. CUSTOMER AND DEVICE HISTORY
==================================================

Implement simple customer and device history.

Customer:
- Store customer data in customers table.
- Match existing customer by phone number.
- Customer detail page can show previous service notes.

Device:
- Store device data in devices table.
- Link device to customer.
- If serial_number exists, use it to help identify existing devices.
- Device detail/history can show previous service notes if practical.

Autocomplete:
If practical, when staff enters customer_phone:
- Show existing customer suggestion.
- Allow staff to reuse customer details.

If autocomplete is too much for first pass:
- Implement backend structure first.
- Add simple customer history page.
- Add TODO for autocomplete.

==================================================
10. COMPANY SETTINGS
==================================================

Create settings page accessible without login for MVP.

Settings fields:
- company_name
- logo
- address
- phone
- office_phone
- support_email
- footer_note
- default_warranty_note

Use settings in:
- Web app header
- PDF header
- Warranty default note

Default values:
- company_name: LaptopPro
- address: Address placeholder
- phone: Phone placeholder
- office_phone: Office phone placeholder
- support_email: support@example.com
- default_warranty_note: Tidak termasuk kerosakan fizikal / liquid damage.

==================================================
11. DATABASE DESIGN
==================================================

Create migrations and models for the following:

Table: customers
Columns:
- id
- name
- phone
- email
- address
- timestamps

Indexes:
- phone
- name

Table: devices
Columns:
- id
- customer_id
- device_type
- device_type_other
- brand_model
- serial_number
- specifications
- device_password
- timestamps

Indexes:
- customer_id
- serial_number
- brand_model

Table: service_notes
Columns:
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

Indexes:
- service_no unique
- customer_id
- device_id
- received_date
- status
- technician_name

Table: service_note_logs
Columns:
- id
- service_note_id
- action
- description
- created_at

Actions to log:
- created
- updated
- printed
- downloaded
- deleted

Table: settings
Columns:
- id
- key
- value
- timestamps

Important:
Do not create users table unless Laravel requires it by default. Since there is no login for MVP, user management is not required.

==================================================
12. VALIDATION RULES
==================================================

Validation:
- customer_name required
- customer_phone required
- received_date required
- status required
- device_type required
- device_type_other required if device_type = Others
- brand_model required
- reported_issue required
- service_charge numeric nullable
- parts_charge numeric nullable
- warranty_duration numeric nullable
- customer_email valid email nullable

Sanitization:
- Escape output in Blade templates.
- Use Laravel validation.
- Use Eloquent ORM.
- Prevent SQL injection.
- Prevent XSS.

Sensitive field:
- device_password should not be printed in PDF.
- Hide device_password in normal view by default.
- Provide “show/reveal” UI if displayed.
- Add warning text near device_password:
  “Data ini sensitif. Jangan isi jika tidak perlu.”

==================================================
13. UI/UX REQUIREMENTS
==================================================

Design style:
- Clean
- Modern
- Fast
- Simple
- Suitable for repair shop counter workflow
- Mobile responsive
- Easy for non-technical staff

Main feeling:
This should feel like a “digital counter form”.
Staff opens the web page, fills the service note, saves, prints, and continues to the next customer.

Homepage layout:
- Header with system name: Digital Service Note System
- Small company name/logo area
- Main form section
- Search old records section
- Recent records table if useful
- Sticky action buttons if practical

Form section design:
- Use clear cards/sections:
  1. Maklumat Service
  2. Maklumat Pelanggan
  3. Maklumat Peranti
  4. Masalah Dilaporkan
  5. Pemeriksaan Awal
  6. Kerja Baik Pulih
  7. Alat Ganti & Kos
  8. Warranty & Signature

Language:
Use Bahasa Melayu labels with English technical terms where useful.

Example labels:
- No. Service
- Tarikh Terima
- Status Service
- Nama Pelanggan
- No. Telefon
- Jenis Peranti
- Jenama / Model
- Serial Number
- Masalah Dilaporkan
- Pemeriksaan Awal
- Kerja Baik Pulih
- Alat Ganti
- Upah Servis
- Kos Alat Ganti
- Jumlah
- Warranty Service
- Nota Warranty

Mobile:
- One-column layout.
- Large tap-friendly buttons.
- Search appears below form.
- Avoid tiny text.
- Make PDF/print buttons easy to find.

==================================================
14. NAVIGATION / MENU
==================================================

Keep navigation simple.

Do not create dashboard navigation.

Navigation/Menu for MVP:
- Main Form
- Search Records
- Settings
- Backup Guide

Routes:
- `/` should show the main Service Note Form + Search page.
- `/service-notes/{id}` view service note.
- `/service-notes/{id}/edit` edit service note.
- `/service-notes/{id}/pdf` generate/download PDF.
- `/settings` company settings.
- `/backup-guide` backup documentation page.

==================================================
15. BACKUP GUIDE
==================================================

Create a Backup Guide page and README section.

Include:
- How to backup database.
- How to backup uploaded files.
- How to restore database.
- How to restore uploaded files.

For Docker Compose, include example commands:
- database dump command
- database restore command
- copy storage/uploads command

Also mention:
- Backup database regularly.
- Backup PDF/signature uploads if used.
- Store backup outside the server if possible.

==================================================
16. DOCKER COMPOSE REQUIREMENTS
==================================================

Provide working Docker setup.

Include:
- docker-compose.yml
- Dockerfile if needed
- .env.example
- README.md

Containers:
- app container
- database container using MariaDB/MySQL
- optional nginx container if needed

Volumes:
- database persistent volume
- app storage/uploads volume if needed

The app must survive container restart without losing database data.

README must include:
- Requirements
- Installation steps
- Docker Compose commands
- How to run migrations
- How to seed default settings/sample data
- How to access the app
- How to backup
- How to restore
- Troubleshooting notes

==================================================
17. SAMPLE DATA
==================================================

Create seeders for:
- Default company settings
- Optional sample customer
- Optional sample device
- Optional sample service note

Do not create admin/staff users because MVP has no login.

Sample customer:
- name: Ahmad Test
- phone: 0123456789
- email: ahmad@example.com

Sample device:
- device_type: Laptop
- brand_model: Lenovo ThinkPad Test
- serial_number: TEST12345

Sample service note:
- service_no: SN-currentyear-0001 or generated dynamically
- status: Received
- reported_issue: Laptop tidak boleh ON
- initial_diagnosis: Perlu pemeriksaan lanjut
- service_charge: 0
- parts_charge: 0

==================================================
18. AUDIT LOG
==================================================

Create simple service note log.

Log:
- service note created
- service note updated
- service note deleted
- PDF downloaded
- PDF printed if possible

Since there is no login, log description can include:
- action type
- service_no
- timestamp
- optional technician_name if available

Do not require user_id.

==================================================
19. PERFORMANCE REQUIREMENTS
==================================================

The app should be fast for local network use.

Requirements:
- Search should be fast even with thousands of records.
- Add database indexes for common search fields.
- PDF generation should complete within a few seconds for one record.
- Avoid unnecessary heavy frontend frameworks.
- Use Blade + Tailwind unless there is a strong reason otherwise.

==================================================
20. ACCEPTANCE CRITERIA
==================================================

The project is complete when:

1. Laravel app runs successfully using Docker Compose.
2. The homepage opens without login.
3. No login page exists for MVP.
4. No dashboard page exists for MVP.
5. The homepage directly shows the service note form.
6. The homepage includes search for old database records.
7. Staff/user can create a new service note.
8. service_no is auto-generated and unique.
9. Data is saved into database.
10. Customer data is saved.
11. Device data is saved.
12. Service note data is saved.
13. Total charge is calculated correctly.
14. Staff/user can search old service notes.
15. Staff/user can view old service note details.
16. Staff/user can edit old service notes.
17. Staff/user can generate PDF.
18. Staff/user can download PDF.
19. Staff/user can print PDF.
20. PDF layout is close to a professional service report style.
21. Device password is not shown in PDF by default.
22. Settings page can update company details.
23. Company settings appear in PDF.
24. App works on laptop and phone.
25. Database data remains after container restart.
26. README explains installation, usage, backup, and restore.

==================================================
21. DEVELOPMENT INSTRUCTIONS
==================================================

Build this as a real working MVP, not just UI mockup.

Implementation priorities:
1. Docker Compose working setup
2. Database migrations
3. Main homepage form
4. Save service note
5. Search old records
6. View/edit service notes
7. PDF generation
8. Settings page
9. Backup guide
10. Polish UI/mobile responsiveness

Use Laravel best practices:
- Models
- Controllers
- Migrations
- Seeders
- Request validation if appropriate
- Blade components if helpful
- Eloquent relationships
- Clean route structure

Relationships:
- Customer has many devices
- Customer has many service notes
- Device belongs to customer
- Device has many service notes
- Service note belongs to customer
- Service note belongs to device

Do not overcomplicate MVP:
- No authentication
- No dashboard
- No payment gateway
- No WhatsApp notification
- No inventory system
- No advanced analytics
- No native mobile app

Use comments only where helpful.

After implementation:
- Run migrations.
- Run seeders.
- Test form submission.
- Test search.
- Test PDF generation.
- Test edit.
- Test Docker restart persistence.
- Fix any errors found.

At the end, provide:
1. Summary of what was built.
2. List of important files created/changed.
3. Exact commands to run the system.
4. Exact URL to open.
5. Default sample data if created.
6. Any known limitations or TODOs.