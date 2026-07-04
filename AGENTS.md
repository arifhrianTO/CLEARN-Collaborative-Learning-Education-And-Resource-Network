# CLEARN — Agent Guide

## Stack
- **Laravel 13** (PHP ^8.3) + **Tailwind CSS v4** + **Vite 8** + **Alpine.js**
- **Pest PHP v4** (tests), **Midtrans** (payments), **DOMPDF** (certificates)

## Dev Commands

```bash
composer setup          # full project bootstrap (install, .env, key:generate, migrate, npm build)
composer dev            # run dev server + queue:listen + pail logs + vite (all concurrent)
composer test           # config:clear then run tests (Pest)
npm run build           # vite build
npm run dev             # vite dev server
php artisan migrate     # run migrations (MySQL in .env, SQLite :memory: in tests)
php artisan db:seed     # seed with AdminSeeder, CourseSeeder, PythonCourseSeeder + test user
php artisan mentor:payout  # scheduled monthly on 10th at 01:00
```

- `.npmrc` has `ignore-scripts=true` — run `npm install --ignore-scripts` (or just `composer setup`).
- Tests use `RefreshDatabase` trait with SQLite `:memory:` — no external DB needed.
- `config:clear` is required before tests (already in `composer test`).

## Architecture

| Directory | Purpose |
|-----------|---------|
| `app/Http/Controllers/{Admin,Mentor,Student,Auth}/` | Role-based controllers |
| `app/Models/` | 26 Eloquent models |
| `app/Services/` | Business logic (LoginService, DashboardService) |
| `app/Http/Middleware/RoleMiddleware.php` | Custom role gate — `role:{student,mentor,admin}` |
| `routes/web.php` | All web routes in 3 role-prefixed groups |
| `resources/views/{admin,mentor,student,landing,auth,settings}/` | Blade views per role |
| `config/midtrans.php` | Midtrans payment gateway config |

### Roles
User `role` field is a plain string: `student`, `mentor`, `admin`. No spatie/laravel-permission. Middleware checks `Auth::user()->role !== $role` and redirects to the user's own dashboard on mismatch.

### Key Flows
- **Auth**: Custom (not Breeze UI). `LoginController` + `LoginService`. Register: separate `StudentRegisterController` and `MentorRegisterController`.
- **Payments**: Midtrans Snap via `midtrans/midtrans-php`. Webhook at `POST /api/midtrans/webhook`. `.env` has `MIDTRANS_IS_PRODUCTION=false`.
- **Courses**: Mentor creates → submits for review → admin approves/rejects. Two status fields: `status_publish` (draft/published) and `status_review` (pending/approved/rejected).
- **Queue/Cache**: Both use `database` driver by default (jobs + cache tables).
- **Session**: `file` driver (default).
- **Scheduler**: `mentor:payout` Artisan command runs monthly on 10th at 01:00 (defined in `routes/console.php`).

## Testing
- Pest v4 with `pestphp/pest-plugin-laravel`.
- Run: `composer test` or `php artisan test`.
- Tests in `tests/Feature/` use `RefreshDatabase`. Tests in `tests/Unit/` do not.
- Test DB is SQLite `:memory:` (see `phpunit.xml` env vars).
- Vite/HMR host is `0.0.0.0` with CORS enabled (for ngrok/tunnel compatibility).

## Code Style
- Laravel Pint (`laravel/pint`) for PHP CS fixer.
- `.editorconfig`: 4-space indent, LF line endings, UTF-8.
- Tailwind CSS v4 with custom dark mode colors (`dark-bg`, `dark-sidebar`, `dark-card`, etc.).

## Gotchas
- DO NOT use `spatie/laravel-permission` — roles are string-based with custom middleware.
- `midtrans/midtrans-php` is the PHP library, not the Laravel package — configs are manual in `config/midtrans.php` + service provider not needed (lib is used directly).
- `public/build/` is gitignored (Vite output). Run `npm run build` after any CSS/JS change.
- No CI workflows exist.
