# BlogHub — Technical Publication & CMS

A full-stack blog platform built with **Laravel 12** and **Bootstrap 5.3** (server-rendered Blade, vanilla JS — no React/Vue/jQuery/Tailwind). It ships with a public-facing publication (home, blog listing, search, categories, authors, contact, newsletter) and a complete role-based admin panel for managing posts, categories, tags, users, comments, messages and subscribers.

## Tech Stack

| Layer | Technology |
| --- | --- |
| Backend | PHP 8.2, Laravel 12 |
| Database | SQLite (MySQL-ready — switch via `.env`) |
| Frontend | Blade templates, Bootstrap 5.3.3, Bootstrap Icons, vanilla JavaScript |
| Auth | Laravel session-based auth with role middleware (`admin` / `author` / `reader`) |

## Features

- **Public**: home page with featured posts, blog listing with category/tag filters, full-text search with AJAX suggestions, post detail (likes, bookmarks, nested comments), author profiles with follow, categories directory, contact form, newsletter subscription.
- **Author**: dashboard with stats, create/edit/publish posts with image upload, live slug generation, manage own posts and profile.
- **Admin**: everything authors get plus category CRUD (safe delete — blocked while posts exist), tag management, user management (role changes, no privilege escalation from registration), comment moderation (approve / reject / delete), contact messages inbox, newsletter subscribers.
- **UX**: dark/light theme toggle, toast notifications, scroll progress bar, back-to-top, responsive admin sidebar (offcanvas on mobile), accessible forms with validation feedback.

## Requirements

- PHP >= 8.2 with `pdo_sqlite` (or MySQL)
- Composer
- Node.js not required (no build step — assets are CDN + plain files in `public/`)

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

The default `.env` uses SQLite at `database/database.sqlite`. For MySQL, set `DB_CONNECTION=mysql` plus host/database/user/password.

## Running Locally

```bash
php artisan serve --port=8090
```

Then open http://localhost:8090

## Test Accounts (after seeding)

All seeded accounts use password `password`.

| Role | Email |
| --- | --- |
| Admin | `admin@bloghub.test` |
| Author | `sophia.chen@bloghub.test` |
| Reader | `lucas.wright@reader.test` |

Admin panel: `/admin` (admins and authors only; readers get 403). New registrations can only choose `reader` or `author` roles.

## Useful Commands

```bash
php artisan test                 # run the test suite
php artisan migrate:fresh --seed # reset database to seeded state
php artisan route:list           # inspect all routes
php artisan storage:link         # link storage for uploaded images
```

## Project Structure

```
app/
  Http/Controllers/          # Public + Auth controllers
  Http/Controllers/Admin/    # Admin panel controllers (posts, categories, tags, users, comments, messages)
  Http/Middleware/           # EnsureUserRole (role => admin|author guard)
  Models/                    # User, Post, Category, Tag, Comment, ContactMessage, Subscriber, ...
resources/views/
  layouts/                   # app.blade.php (public) + admin.blade.php (panel)
  components/                # navbar, footer, alerts, toast, cards, pagination
  admin/                     # admin panel views
public/
  css/bloghub.css            # design system (CSS variables, light/dark themes)
  css/admin.css              # admin-specific styles
  js/bloghub.js              # theme, toasts, counters, scroll UI
  js/admin.js                # slug generation, image previews, confirm modals
routes/web.php               # all web routes
database/seeders/BlogHubSeeder.php  # demo content + accounts
```

## Security Notes

- CSRF protection on all state-changing forms; XSS-safe Blade output escaping.
- Uploads validated by type/size and stored under `storage/app/public` with hashed filenames.
- Admin panel is `noindex, nofollow` and role-gated; destructive actions require a confirm modal.
- Registration cannot escalate privileges (role whitelist: `reader`, `author`).
