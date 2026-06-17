# Repository Guidelines

## Project Structure & Module Organization

Laravel 12, Inertia, Vue 3, TypeScript, Vite app for Tech Context Radar.

- `app/Http/Controllers/` contains radar, saved signals, sources, settings, auth, and profile controllers.
- `app/Models/` contains Eloquent models such as `Signal`, `Source`, `SavedSignal`, and `UserPreference`.
- `resources/js/Pages/` contains Inertia pages. `Home.vue` is main radar/home and `/today`.
- `resources/js/Components/` contains reusable Vue UI.
- `resources/css/app.css` imports Tailwind and exported design CSS.
- `open-design-export/` is the source design handoff. Preserve layout, copy, and visual intent for frontend changes.
- `database/migrations`, `database/factories`, and `database/seeders` define data.
- `tests/Feature` and `tests/Unit` contain PHPUnit tests.

## Build, Test, and Development Commands

- `composer install` installs PHP dependencies.
- `npm install` installs frontend dependencies.
- `composer run dev` starts Laravel, queue, logs, and Vite.
- `php artisan serve` starts only the Laravel server.
- `npm run dev` starts only Vite.
- `npm run build` runs `vue-tsc` and production Vite build.
- `composer test` clears config and runs `php artisan test`.
- `./vendor/bin/pint` formats PHP; `./vendor/bin/pint --test` checks formatting.

## Agent-Specific Instructions

Use these skills every time this repository is handled:

- `caveman` (`/Users/metamartin/.agents/skills/caveman/SKILL.md`): respond terse, technical, no filler. Keep exact code, commands, API names, and errors.
- `caveman-commit` (`/Users/metamartin/.agents/skills/caveman-commit/SKILL.md`): for commit messages or staging summaries, use Conventional Commits, short imperative subject, body only when needed.
- `graphify` (`/Users/metamartin/.codex/skills/graphify/SKILL.md`): for codebase, architecture, file relationship, or project-content questions. If `graphify-out/graph.json` exists, query it before broad exploration.

## Coding Style & Naming Conventions

Follow `.editorconfig`: UTF-8, LF endings, 4-space indentation, final newline, trimmed trailing whitespace. PHP uses Laravel conventions: StudlyCase classes, camelCase methods, singular Eloquent models. Vue components use PascalCase filenames; composables use `useName.ts`.

## Testing Guidelines

Use PHPUnit via `php artisan test` or `composer test`. Add feature tests for route, controller, auth, and Inertia behavior. Name tests after behavior: `AuthenticationTest`, `ProfileTest`. For frontend changes, also run `npm run build`.

## Commit & Pull Request Guidelines

This working copy does not include `.git` history, so no local commit convention can be verified. Use concise imperative commits or Conventional Commits, for example `fix: align today route with home page`.

Pull requests need summary, test results, linked issue if applicable, and screenshots for UI changes. Mention intentional deviations from `open-design-export`.

## Security & Configuration Tips

Never commit `.env` secrets. Use `.env.example` for config shape. Local SQLite lives at `database/database.sqlite`; reset with migrations only when data loss is acceptable.
