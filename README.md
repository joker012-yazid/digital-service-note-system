# Digital Service Note System

Digital Service Note System is a Laravel-based MVP for a computer repair shop service note workflow. Staff will use it to create service notes, save customer/device/service data, search old records, and later generate printable PDF service reports.

Current implementation status:
- Laravel base application is installed.
- Docker Compose app and MariaDB services are configured.
- Database environment defaults are configured for Docker.
- Product features such as service note form, migrations, search, PDF, settings, and backup guide will be added in later tasks.

## Requirements

For Docker-based development:
- Docker Desktop or Docker Engine
- Docker Compose v2

Local PHP and Composer are optional. The app container installs PHP dependencies when Docker is running.

## Services

Docker Compose defines these services:
- `app`: Laravel app container, exposed at `http://localhost:8000`
- `database`: MariaDB database container, exposed on host port `3306`

Persistent volumes:
- `database_data`: MariaDB data
- `vendor`: Composer dependencies inside the app container

## Environment

The default `.env.example` is configured for Docker:

```env
APP_NAME="Digital Service Note System"
APP_URL=http://localhost:8000
DB_CONNECTION=mariadb
DB_HOST=database
DB_PORT=3306
DB_DATABASE=digital_service_note
DB_USERNAME=service_note
DB_PASSWORD=service_note_password
```

When the app container starts, `docker/entrypoint.sh` will:
- install Composer dependencies if `vendor/autoload.php` is missing
- copy `.env.example` to `.env` if `.env` does not exist
- generate `APP_KEY` if missing
- start Laravel on `0.0.0.0:8000`

## Docker Commands

Start the app:

```bash
docker compose up -d --build
```

View logs:

```bash
docker compose logs -f app
```

Run Laravel commands:

```bash
docker compose exec app php artisan about
docker compose exec app php artisan migrate:status
```

Stop containers:

```bash
docker compose down
```

Stop containers and remove volumes:

```bash
docker compose down -v
```

Use `down -v` only when you intentionally want to delete local database data.

## Database Connection Check

After Docker is running, use:

```bash
docker compose exec app php artisan migrate:status
```

If the command reaches MariaDB, Laravel can connect to the database. If migrations are pending, that is expected until the database tasks are completed.

## Access

Open:

```text
http://localhost:8000
```

At this stage, the default Laravel welcome page may still appear. The service note homepage will be implemented in a later task.

## Notes

- No login or authentication should be added for the MVP.
- No dashboard should be added for the MVP.
- The default route `/` will later show the service note form and search records on the same page.
- Backup and restore documentation will be completed in TASK 18.
