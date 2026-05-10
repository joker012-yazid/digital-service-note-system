You are a senior full-stack developer and technical auditor.

I have a project called “Digital Service Note System”.

The full product requirements are saved in this file:

PRD.md

Your job now is NOT to build new features immediately.

Your job is to audit the current codebase against PRD.md and tell me whether the project already follows the requirements.

IMPORTANT:
Read `PRD.md` first before checking the code.

Then inspect the whole project:
- routes
- controllers
- models
- migrations
- seeders
- Blade views
- PDF templates
- Docker files
- README
- environment files
- database structure
- frontend UI
- search function
- print/download PDF function

==================================================
MAIN REQUIREMENTS TO VERIFY
==================================================

The most important MVP requirements are:

1. No login page.
2. No authentication required.
3. No admin/staff role system for MVP.
4. No dashboard page for MVP.
5. Homepage `/` must directly show the Service Note Web Form.
6. Homepage must also include Search Old Records section.
7. Staff/user can create service note without login.
8. service_no must auto-generate using format `SN-YYYY-0001`.
9. Customer data must save into database.
10. Device data must save into database.
11. Service note data must save into database.
12. Old records must be searchable from database.
13. User can view old service note detail.
14. User can edit old service note.
15. User can generate PDF.
16. User can download PDF.
17. User can print PDF.
18. PDF must not show `device_password`.
19. Company settings must be usable in PDF/header.
20. Docker Compose must work.
21. Data must remain after Docker restart.
22. README must explain install, usage, backup, and restore.

==================================================
SPECIAL INSTRUCTION ABOUT PRD CONFLICTS
==================================================

If `PRD.md` contains conflicting requirements, use these rules as the final decision:

- No login page is required.
- No dashboard is required.
- Homepage `/` must directly show Service Note Form + Search.
- Do not create user role system for MVP.
- Do not require admin/staff login.
- The system is public/internal access only for MVP.
- Delete function, if available, must use confirmation.
- Device password must not appear in PDF.

If you find old dashboard/login/user-role references in PRD.md, mark them as “PRD conflict / outdated requirement” in the report.

==================================================
AUDIT OUTPUT FILES
==================================================

Create or update these files:

1. `AUDIT_REPORT.md`
2. `GAP_LIST.md`
3. `FIX_PLAN.md`

==================================================
AUDIT_REPORT.md FORMAT
==================================================

Use this format:

# Audit Report — Digital Service Note System

## Audit Summary
- Overall status:
- Can the app run? Yes/No
- Is it aligned with PRD.md? Yes/No/Partially
- Biggest issue:
- Recommended next action:

## Requirement Checklist

Use this table:

| No | Requirement | Status | Evidence / File Path | Notes |
|---|---|---|---|---|
| 1 | No login page | Pass/Fail/Partial/Not Checked | file path | notes |
| 2 | No dashboard | Pass/Fail/Partial/Not Checked | file path | notes |
| 3 | `/` opens Service Note Form + Search | Pass/Fail/Partial/Not Checked | file path | notes |

Continue until all important PRD requirements are checked.

## Codebase Findings

Group findings by area:

### Routes
### Controllers
### Models
### Migrations
### Views
### PDF
### Search
### Docker
### README
### Security
### Mobile UI

## Bugs Found

For every bug, include:
- Bug title
- Severity: Critical / High / Medium / Low
- File path
- What is wrong
- How to fix

## PRD Conflicts Found

List any conflicting/outdated PRD requirement, especially:
- Dashboard references
- Login references
- Admin/staff role references
- Users table references
- Any requirement that conflicts with public access MVP

## Test Commands Run

List all commands you ran and results.

Example:
- `docker compose up -d`
- `php artisan migrate`
- `php artisan route:list`
- `php artisan test`
- `npm run build`

## Final Recommendation

Tell me what should be fixed first.

==================================================
GAP_LIST.md FORMAT
==================================================

Create a simple checklist:

# Gap List

## Critical Gaps
- [ ] Gap 1
- [ ] Gap 2

## High Priority Gaps
- [ ] Gap 1
- [ ] Gap 2

## Medium Priority Gaps
- [ ] Gap 1
- [ ] Gap 2

## Low Priority Gaps
- [ ] Gap 1
- [ ] Gap 2

==================================================
FIX_PLAN.md FORMAT
==================================================

Create a safe step-by-step fix plan.

# Fix Plan

## Phase 1 — Critical Fixes
- Task 1:
- Task 2:

## Phase 2 — PRD Alignment
- Task 1:
- Task 2:

## Phase 3 — Testing
- Task 1:
- Task 2:

Important:
Do not fix everything yet unless the issue is very small and safe.

This task is mainly for audit and planning.

==================================================
WHAT YOU MUST CHECK DIRECTLY
==================================================

Run or inspect these where possible:

1. Check route list:
- Make sure `/` opens the correct page.
- Make sure there is no login route.
- Make sure there is no dashboard route unless unused.

2. Check migrations:
- customers table exists.
- devices table exists.
- service_notes table exists.
- service_note_logs table exists.
- settings table exists.
- service_no is unique.
- important indexes exist.

3. Check models:
- Customer model.
- Device model.
- ServiceNote model.
- ServiceNoteLog model.
- Setting model.
- Relationships are correct.

4. Check homepage:
- Service note form appears immediately.
- Search old records appears on same page.
- No dashboard cards.

5. Check store function:
- Validates input.
- Saves customer.
- Saves device.
- Saves service note.
- Calculates total_charge.
- Generates service_no.

6. Check search:
- Can search by service_no.
- Can search by customer name.
- Can search by phone.
- Can search by brand/model.
- Can search by serial number.
- Can search by status/date if implemented.

7. Check PDF:
- PDF generates from database.
- PDF uses company settings.
- PDF has service info, customer info, device info, issue, diagnosis, repair action, parts, charges, warranty, signature area.
- PDF does not show device_password.
- PDF is A4 and print-friendly.

8. Check Docker:
- docker-compose.yml exists.
- app container exists.
- database container exists.
- database volume is persistent.
- README explains how to run.

9. Check security:
- No login for MVP, but mention network protection in README.
- CSRF protection exists.
- Validation exists.
- Output escaping exists.
- Device password is hidden and not in PDF.

10. Check documentation:
- README has install steps.
- README has usage steps.
- README has backup steps.
- README has restore steps.

==================================================
STOPPING RULE
==================================================

After audit is complete:
1. Write `AUDIT_REPORT.md`.
2. Write `GAP_LIST.md`.
3. Write `FIX_PLAN.md`.
4. Show me a short summary in terminal.
5. Stop.

Do not start fixing major issues yet.

Only fix tiny obvious errors if they are required to run the audit commands, and document them clearly.