# Tech Context Radar

Tech Context Radar is a Laravel, Inertia, and Vue intelligence brief for developers. It filters high-volume AI, developer tooling, framework, cloud, security, and engineering-blog updates into a short radar: what changed, why it matters, how it affects developers, and what to do next.

The product is built for busy builders who need a daily technical context check without reading every changelog, research note, and security feed.

## Core Experience

- Public home page at `/` with the radar preview and product positioning.
- Authenticated daily radar at `/today`, using the same `Home.vue` experience with all current signals.
- Signal detail pages at `/signals/{signal:slug}` with priority score, source context, related signals, save, read, and share actions.
- Saved signal library at `/saved` for keeping important briefs.
- Source management view at `/sources` for inspecting trusted feeds, scan health, and recent signals.
- User settings at `/settings` for briefing length, priority threshold, topic preferences, reminders, alerts, summaries, compact mode, and dark mode.
- Auth, profile, password reset, and email verification flows from Laravel Breeze/Inertia.

## Data Model

The app centers on a daily `Briefing` and ranked `Signal` records. Signals belong to `Category` and optionally to `Source`. Users can save and mark signals as read through `SavedSignal` and `ReadSignal`. `UserPreference` stores radar preferences per user.

Seed data creates:

- Categories: AI, DevTools, Frameworks, Cloud, Security, Blogs.
- Trusted sources such as OpenAI, GitHub Changelog, Flutter, Laravel, AWS, Cloudflare Blog, Snyk Blog, and Stripe Engineering.
- Example signals with summaries, developer impact, recommended action, priority score, read time, source count, and publish time.
- Demo user with saved/read activity and default preferences.

## Tech Stack

- PHP 8.3+
- Laravel 12
- Inertia Laravel 2
- Vue 3
- TypeScript
- Vite
- Tailwind CSS 4
- Laravel Sanctum
- Ziggy
- PostgreSQL for application data
- Redis-ready cache and queue configuration
- PHPUnit 12

## Dev Tooling

- `composer run dev` starts the full local stack with `concurrently`:
  - Laravel server
  - queue listener
  - Laravel Pail log stream
  - Vite dev server
- `npm run dev` starts Vite only.
- `npm run build` runs `vue-tsc` and `vite build`.
- `composer test` clears config and runs `php artisan test`.
- `./vendor/bin/pint` formats PHP.
- `./vendor/bin/pint --test` checks PHP formatting.

## Project Structure

- `app/Http/Controllers/` - Inertia controllers for home, today radar, signals, saved signals, sources, settings, auth, and profile.
- `app/Http/Controllers/Concerns/BuildsRadarPayloads.php` - shared payload builder for briefing, categories, signals, and sources.
- `app/Models/` - Eloquent models for briefings, categories, signals, sources, saved/read state, users, and preferences.
- `resources/js/Pages/` - Inertia pages. `Home.vue` powers both `/` and `/today`.
- `resources/js/Components/` - shared Vue UI components for shell, navigation, cards, controls, panels, search, toggles, and toast state.
- `resources/css/app.css` - Tailwind import plus exported product styling.
- `open-design-export/` - source design handoff. Keep layout, copy, spacing, color, radius, shadows, navigation, and motion aligned with this folder.
- `database/migrations/` - schema for product data and auth tables.
- `database/seeders/DatabaseSeeder.php` - demo radar data and demo account.
- `tests/Feature/` and `tests/Unit/` - PHPUnit tests.

## Local Setup

```bash
composer install
npm install
cp .env.example .env
createdb tech_context_radar
php artisan key:generate
php artisan migrate --seed
```

Tests use in-memory SQLite through `phpunit.xml`; the application `.env.example` is PostgreSQL and Redis-ready to match the MVP stack target.

Start the full development stack:

```bash
composer run dev
```

Or run Laravel and Vite separately:

```bash
php artisan serve
npm run dev
```

## Demo Account

```text
Email: builder@techcontextradar.test
Password: password
```

## Common Commands

```bash
composer run dev       # Laravel server, queue, logs, and Vite
php artisan serve      # Laravel server only
npm run dev            # Vite only
npm run build          # Vue type-check and production frontend build
php artisan test       # PHPUnit tests
composer test          # clear config, then PHPUnit tests
./vendor/bin/pint      # format PHP
./vendor/bin/pint --test
```

## Design Source

`open-design-export/` is the design source of truth:

- `index.html` - home and radar reference.
- `signal-detail.html` - signal detail reference.
- `saved.html` - saved library reference.
- `sources.html` - source management reference.
- `settings.html` - settings reference.
- `components.html` and `product.css` - shared component and visual-system reference.
- `DESIGN-HANDOFF.md` and `DESIGN-MANIFEST.json` - implementation notes and exported asset map.

Frontend changes should preserve the exported visual intent unless the change explicitly updates the design direction.

## Verification

Run these before shipping relevant changes:

```bash
npm run build
composer test
./vendor/bin/pint --test
```

For UI changes, compare `/`, `/today`, `/signals/{slug}`, `/saved`, `/sources`, `/settings`, and auth screens against `open-design-export/`.
