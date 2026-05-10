# Digital Service Note System

Laravel MVP for a repair shop service note workflow. The app opens directly to the service note form and search screen. There is no login page and no dashboard in the MVP.

## Features

- Create service notes from `/`
- Save customer, device, and service note data
- Auto-generate service numbers using `SN-YYYY-0001`
- Search old records by service number, customer, phone, email, model, serial number, status, device type, and received date
- View and edit service note detail pages
- Generate, download, and print PDF service reports
- Hide device password from the PDF
- Manage company settings used in PDFs
- Run with Docker Compose and persistent MariaDB storage

## Requirements

- Docker Desktop or Docker Engine
- Docker Compose v2

Local PHP and Composer are optional. The app container installs Composer dependencies automatically.

## Install

1. Copy the environment file if it does not exist:

```bash
cp .env.example .env
```

2. Start the app:

```bash
docker compose up -d --build
```

On first startup, `docker/entrypoint.sh` installs Composer dependencies, generates `APP_KEY`, runs migrations, seeds default company settings, and starts Laravel.

3. Open the app:

```text
http://localhost:8000
```

## Usage

- Main form and search: `http://localhost:8000/`
- Settings: `http://localhost:8000/settings`
- Detail page: click `View` from search results
- Edit record: click `Edit` from search results or the detail page
- Create and immediately download PDF: click `Save & Download PDF`
- PDF download: click `Download PDF`
- PDF print preview/stream: click `Print PDF`

The device password is stored for internal use but is not displayed in the PDF.

## Docker Commands

Start:

```bash
docker compose up -d --build
```

View logs:

```bash
docker compose logs -f app
```

Run Laravel commands:

```bash
docker compose exec app php artisan migrate:status
docker compose exec app php artisan test
```

Stop without deleting data:

```bash
docker compose down
```

Restart:

```bash
docker compose up -d
```

Data remains after restart because MariaDB uses the `database_data` Docker volume. Do not run `docker compose down -v` unless you intentionally want to delete database data.

## Backup

Create a database backup:

```bash
docker compose exec database mariadb-dump -uroot -proot_password digital_service_note > backup.sql
```

Create a timestamped backup from PowerShell:

```powershell
$stamp = Get-Date -Format "yyyyMMdd-HHmmss"
docker compose exec database mariadb-dump -uroot -proot_password digital_service_note > "backup-$stamp.sql"
```

Keep backup files outside the project folder or copy them to external storage.

## Restore

Restore from a backup file:

```bash
docker compose exec -T database mariadb -uroot -proot_password digital_service_note < backup.sql
```

After restore, restart the app if needed:

```bash
docker compose restart app
```

## Verification Notes

Static and build checks passed in the current environment:

- `npm run build`
- `docker compose config`
- Static scan confirmed no login or dashboard route/view exists.
- Static scan confirmed the PDF template does not reference `device_password`.

Runtime PHP/Docker tests could not be executed on this machine because local PHP/Composer are not installed and the Docker Desktop Linux engine is not running. Once Docker is active, run:

```bash
docker compose up -d --build
docker compose exec app php artisan test
```
