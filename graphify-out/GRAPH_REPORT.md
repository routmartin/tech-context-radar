# Graph Report - .  (2026-06-17)

## Corpus Check
- 202 files · ~60,218 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 590 nodes · 665 edges · 154 communities (146 shown, 8 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 11 edges (avg confidence: 0.82)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Auth Test Suite|Auth Test Suite]]
- [[_COMMUNITY_Auth Test Suite|Auth Test Suite]]
- [[_COMMUNITY_Demo Data Factories|Demo Data Factories]]
- [[_COMMUNITY_Laravel Controllers|Laravel Controllers]]
- [[_COMMUNITY_Demo Data Factories|Demo Data Factories]]
- [[_COMMUNITY_Settings Experience|Settings Experience]]
- [[_COMMUNITY_Design System|Design System]]
- [[_COMMUNITY_Project Config|Project Config]]
- [[_COMMUNITY_Vue Frontend|Vue Frontend]]
- [[_COMMUNITY_Project Config|Project Config]]
- [[_COMMUNITY_User Model|User Model]]
- [[_COMMUNITY_Login Request|Login Request]]
- [[_COMMUNITY_Laravel Controllers|Laravel Controllers]]
- [[_COMMUNITY_Laravel Controllers|Laravel Controllers]]
- [[_COMMUNITY_Project Config|Project Config]]
- [[_COMMUNITY_scripts|scripts]]
- [[_COMMUNITY_require dev|require dev]]
- [[_COMMUNITY_Laravel Controllers|Laravel Controllers]]
- [[_COMMUNITY_app|app]]
- [[_COMMUNITY_pestphp pest plugin|pestphp pest plugin]]
- [[_COMMUNITY_require|require]]
- [[_COMMUNITY_Laravel Controllers|Laravel Controllers]]
- [[_COMMUNITY_Settings Experience|Settings Experience]]
- [[_COMMUNITY_Demo Data Factories|Demo Data Factories]]
- [[_COMMUNITY_Export JavaScript|Export JavaScript]]
- [[_COMMUNITY_App Service Provider|App Service Provider]]
- [[_COMMUNITY_Vue Frontend|Vue Frontend]]
- [[_COMMUNITY_Vue Frontend|Vue Frontend]]
- [[_COMMUNITY_Vue Frontend|Vue Frontend]]
- [[_COMMUNITY_Vue Frontend|Vue Frontend]]
- [[_COMMUNITY_autoload dev|autoload dev]]
- [[_COMMUNITY_extra|extra]]
- [[_COMMUNITY_Laravel README|Laravel README]]
- [[_COMMUNITY_index d|index d]]

## God Nodes (most connected - your core abstractions)
1. `User` - 36 edges
2. `Controller` - 27 edges
3. `TestCase` - 20 edges
4. `compilerOptions` - 13 edges
5. `User` - 10 edges
6. `SavedSignal` - 9 edges
7. `Signal` - 9 edges
8. `scripts` - 9 edges
9. `UserPreference` - 8 edges
10. `require-dev` - 8 edges

## Surprising Connections (you probably didn't know these)
- `Robots Policy` --supports--> `Tech Context Radar`  [INFERRED]
  public/robots.txt → open-design-export/index.html
- `Developer Impact` --semantically_similar_to--> `AI Intelligence Brief`  [INFERRED] [semantically similar]
  open-design-export/signal-detail.html → open-design-export/index.html
- `AuthenticatedSessionController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/AuthenticatedSessionController.php → app/Http/Controllers/Controller.php
- `RegisteredUserController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Auth/RegisteredUserController.php → app/Http/Controllers/Controller.php
- `HomeController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/HomeController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Core App Navigation** — open_design_export_index_tech_context_radar, open_design_export_saved_saved_intelligence, open_design_export_sources_source_transparency, open_design_export_settings_settings [EXTRACTED 1.00]
- **Intelligence Filtering Loop** — open_design_export_sources_curated_inputs, open_design_export_index_noise_filtering, open_design_export_index_today_radar_preview, open_design_export_signal_detail_openai_improves_coding_agent_workflow, open_design_export_saved_intelligence_library [INFERRED 0.85]
- **Shared Design System** — open_design_export_components_component_library, open_design_export_components_design_tokens, open_design_export_components_ui_component_library, open_design_export_components_layout_patterns [EXTRACTED 1.00]

## Communities (154 total, 8 thin omitted)

### Community 0 - "Auth Test Suite"
Cohesion: 0.08
Nodes (13): AuthenticationTest, EmailVerificationTest, PasswordConfirmationTest, PasswordResetTest, PasswordUpdateTest, RegistrationTest, BaseTestCase, ExampleTest (+5 more)

### Community 1 - "Auth Test Suite"
Cohesion: 0.07
Nodes (26): RedirectResponse, Request, Response, RedirectResponse, Request, RedirectResponse, Request, Response (+18 more)

### Community 2 - "Demo Data Factories"
Cohesion: 0.09
Nodes (15): HasMany, BelongsTo, BelongsTo, BelongsTo, HasMany, BelongsTo, HasMany, HasFactory (+7 more)

### Community 3 - "Laravel Controllers"
Cohesion: 0.12
Nodes (17): Response, RedirectResponse, Request, Response, Signal, Request, Response, Signal (+9 more)

### Community 4 - "Demo Data Factories"
Cohesion: 0.10
Nodes (10): BriefingFactory, CategoryFactory, ReadSignalFactory, SavedSignalFactory, SignalFactory, SourceFactory, UserFactory, UserPreferenceFactory (+2 more)

### Community 5 - "Settings Experience"
Cohesion: 0.12
Nodes (15): Signal, RedirectResponse, Request, Response, BelongsTo, Collection, categories(), signalCollection() (+7 more)

### Community 6 - "Design System"
Cohesion: 0.09
Nodes (24): Component Library, Design Tokens, Spacing and Layout Patterns, UI Component Library, Design Fidelity Contract, Implementation Handoff, Responsive Contract, Visual Contract (+16 more)

### Community 7 - "Project Config"
Cohesion: 0.10
Nodes (19): dependencies, axios, devDependencies, concurrently, @inertiajs/vue3, laravel-vite-plugin, tailwindcss, @tailwindcss/vite (+11 more)

### Community 8 - "Vue Frontend"
Cohesion: 0.14
Nodes (10): ComponentCustomProperties, PageProps, Window, Briefing, Category, Preference, SharedPageProps, Signal (+2 more)

### Community 9 - "Project Config"
Cohesion: 0.12
Nodes (16): compilerOptions, allowJs, esModuleInterop, forceConsistentCasingInFileNames, isolatedModules, jsx, module, moduleResolution (+8 more)

### Community 10 - "User Model"
Cohesion: 0.23
Nodes (6): HasMany, Authenticatable, BelongsToMany, HasOne, User, Notifiable

### Community 11 - "Login Request"
Cohesion: 0.27
Nodes (3): LoginRequest, FormRequest, ProfileUpdateRequest

### Community 12 - "Laravel Controllers"
Cohesion: 0.36
Nodes (5): RedirectResponse, Request, Response, AuthenticatedSessionController, LoginRequest

### Community 13 - "Laravel Controllers"
Cohesion: 0.39
Nodes (5): RedirectResponse, Request, Response, ProfileController, ProfileUpdateRequest

### Community 14 - "Project Config"
Cohesion: 0.22
Nodes (8): description, keywords, license, minimum-stability, name, prefer-stable, $schema, type

### Community 15 - "scripts"
Cohesion: 0.22
Nodes (9): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+1 more)

### Community 16 - "require dev"
Cohesion: 0.25
Nodes (8): require-dev, fakerphp/faker, laravel/breeze, laravel/pail, laravel/pint, mockery/mockery, nunomaduro/collision, phpunit/phpunit

### Community 17 - "Laravel Controllers"
Cohesion: 0.43
Nodes (4): RedirectResponse, Request, Response, RegisteredUserController

### Community 18 - "app"
Cohesion: 0.43
Nodes (3): Request, Middleware, HandleInertiaRequests

### Community 19 - "pestphp pest plugin"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 20 - "require"
Cohesion: 0.29
Nodes (7): require, inertiajs/inertia-laravel, laravel/framework, laravel/sanctum, laravel/tinker, php, tightenco/ziggy

### Community 21 - "Laravel Controllers"
Cohesion: 0.53
Nodes (4): RedirectResponse, Request, Signal, ReadSignalController

### Community 23 - "Demo Data Factories"
Cohesion: 0.40
Nodes (5): autoload, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 30 - "autoload dev"
Cohesion: 0.67
Nodes (3): autoload-dev, psr-4, Tests\\

### Community 31 - "extra"
Cohesion: 0.67
Nodes (3): extra, laravel, dont-discover

### Community 44 - "Laravel README"
Cohesion: 0.67
Nodes (3): Agentic Development, Laravel Framework, Laravel Boost

## Knowledge Gaps
- **95 isolated node(s):** `$schema`, `name`, `type`, `description`, `keywords` (+90 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **8 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `Auth Test Suite` to `Demo Data Factories`, `Settings Experience`, `User Model`, `Login Request`, `Laravel Controllers`?**
  _High betweenness centrality (0.115) - this node is a cross-community bridge._
- **Why does `Controller` connect `Auth Test Suite` to `Laravel Controllers`, `Settings Experience`, `Laravel Controllers`, `Laravel Controllers`, `Laravel Controllers`, `Laravel Controllers`?**
  _High betweenness centrality (0.100) - this node is a cross-community bridge._
- **Why does `SavedSignal` connect `Demo Data Factories` to `Laravel Controllers`, `Settings Experience`?**
  _High betweenness centrality (0.018) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _98 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Auth Test Suite` be split into smaller, more focused modules?**
  _Cohesion score 0.08163265306122448 - nodes in this community are weakly interconnected._
- **Should `Auth Test Suite` be split into smaller, more focused modules?**
  _Cohesion score 0.07293868921775898 - nodes in this community are weakly interconnected._
- **Should `Demo Data Factories` be split into smaller, more focused modules?**
  _Cohesion score 0.09041835357624832 - nodes in this community are weakly interconnected._