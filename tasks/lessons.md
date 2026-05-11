# Lessons Learned

## 2026-05-11 - Scope visual polish and preserve sticky behavior

### Mistake

Initial CSS polish used a shared form selector and `overflow: hidden` on the animated form wrapper, which could unintentionally affect the edit page and interfere with the sticky submit bar.

### Correct Rule

Scope UI polish to the requested page with a dedicated class, and avoid overflow clipping on ancestors of sticky elements.

### Prevention

When adding animation styles, check selector scope and sticky/positioned ancestors before verification.

## 2026-05-11 - Windows Docker storage symlink fallback

### Mistake

Assumed `php artisan storage:link --force` would work inside Docker Desktop with a Windows bind mount.

### Correct Rule

Do not let symlink creation failure stop the container on Windows bind mounts; provide an application-level fallback for public storage URLs.

### Prevention

When public uploads are required in this project, verify Docker startup on the actual Windows host and keep `/storage/...` serving functional even if symlinks are blocked.
