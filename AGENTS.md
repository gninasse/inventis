# AGENTS.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

Inventis (internally "CHU-YO-KEYSTONE") is a modular Laravel 12 application for asset/patrimony management. It uses the **nwidart/laravel-modules** package to organize functionality into self-contained modules under `Modules/`. The frontend uses **AdminLTE** with server-rendered **Blade** templates, **jQuery** for AJAX interactions, and **Tailwind CSS v4** (via Vite).

## Tech Stack

- PHP 8.3 / Laravel 12
- nwidart/laravel-modules v12 (modular architecture)
- spatie/laravel-permission v6 (roles & permissions per module)
- spatie/laravel-activitylog v4 (audit logging with module context)
- Tailwind CSS v4 (CSS-first config via `@theme`, no `tailwind.config.js`)
- AdminLTE (layout framework, loaded via `public/adminlte/`)
- jQuery + Bootstrap Table (AJAX data tables pattern)
- Ziggy v2 (Laravel named routes in JS via `@routes`)
- Vite 7 with `laravel-vite-plugin` and `@tailwindcss/vite`
- PHPUnit 11 (not Pest — convert any Pest tests to PHPUnit)

## Build & Development Commands

```bash
# Start full dev environment (server + queue + logs + vite)
composer run dev

# Frontend only
npm run dev          # Vite dev server
npm run build        # Production build

# Run all tests
php artisan test

# Run a single test file
php artisan test tests/Feature/ActivityTest.php

# Run a single test by name
php artisan test --filter=testName

# Code formatting (run before finalizing changes)
vendor/bin/pint --dirty

# Database
php artisan migrate
php artisan module:migrate Core          # Run migrations for a specific module
php artisan db:seed

# Module management
php artisan module:make ModuleName       # Create a new module
php artisan module:migrate ModuleName    # Run module migrations
php artisan module:seed ModuleName       # Run module seeders
```

## Architecture

### Modular Structure (nwidart/laravel-modules)

All business logic lives in `Modules/`, not in `app/`. The `app/` directory contains only shared infrastructure (base User model, middleware, service providers).

Each module is a self-contained Laravel application under `Modules/{ModuleName}/` with its own:
- `app/` — Controllers, Models, Services, Traits, Requests, View Components
- `config/permissions.php` — Module-specific permission definitions (synced to DB)
- `routes/web.php` and `routes/api.php` — Module routes
- `resources/views/` — Blade templates (accessed as `{module}::view.name`, e.g. `core::users.index`)
- `database/migrations/` and `database/seeders/`
- `module.json` — Module metadata and provider registration

Module activation is tracked in `modules_statuses.json` (file-based activator). Currently only the **Core** module exists.

### Core Module (`Modules/Core/`)

The Core module handles: authentication, users, roles, permissions, module management, activity logging, and all reference data (referentiels) and organization structure.

Key subdomain areas within Core:
- **Referentiel** — Categories, SousCategories, Familles, Articles, UniteMesure, Fournisseurs, Fabricants (with Marques/Modeles), Sources de financement, Modes d'acquisition, Budgets, Magasins (with Emplacements)
- **Organisation** — Sites → Directions → Services → Unites (hierarchical)
- **Patrimoine** — StatutBien, EtatBien (asset statuses/conditions)

### Permission System

Permissions follow the naming convention `{module}.{resource}.{action}` (e.g. `cores.users.store`). Each module defines permissions in `config/permissions.php` as a `name => label` map. `PermissionService` syncs these to the database.

The `HasModulePermissions` trait (on User model) provides `hasModuleAccess($module)`, `getAccessibleModules()`, and related methods. The `CheckModuleAccess` middleware gates route groups by module.

Custom Blade directives:
- `@hasModuleAccess('module')` — Check if user can access a module
- `@canModule('permission.name')` / `@endcanModule`
- `@hasCoreAccess` — Shorthand for Core module access

The `super-admin` role bypasses all permission checks via a `Gate::before` hook in `CoreServiceProvider`.

### Activity Logging

The `LogsActivityWithModule` trait extends Spatie's activity logging to add `module`, `context`, `ip_address`, `user_agent`, `causer_roles`, and `expires_at` (retention) to every log entry. Apply this trait to models that need audit logging and set `protected static $activityModule = 'module_name'`.

### Controller & View Patterns

Controllers return Blade views for index/show pages and JSON responses for AJAX data endpoints (used by Bootstrap Table). The standard pattern for CRUD resources:
- `index()` returns a Blade view
- `getData()` returns paginated JSON (`{ total, rows }`) for Bootstrap Table
- `store()` / `update()` / `destroy()` return JSON `{ success, message, data }`
- Validation uses Form Request classes (string-based rules with French error messages)

Views use `@extends('core::layouts.master')` with `@section('content')`, `@push('css')`, and `@push('js')`. Modal-based forms use partials named `_modal.blade.php`.

### Route Naming

All Core routes are prefixed with `cores.` (e.g. `cores.users.index`, `cores.referentiel.categories.store`). Module routes are registered via each module's `RouteServiceProvider`. The root `routes/web.php` is minimal — module routes are defined within each module.

## Key Conventions

- **Language**: Codebase uses French for model names, route names, comments, validation messages, and view content.
- **Table naming**: Referentiel tables use `referentiel_` prefix (e.g. `referentiel_categories`). Organisation tables use `organisation_` prefix. Patrimoine tables use `patrimoine_` prefix.
- **Models**: Set `$table` explicitly for non-standard table names. Use `$casts` as a property or `casts()` method following existing patterns. Include a `scopeActif` scope on models with an `actif` boolean.
- **Artisan commands**: Always pass `--no-interaction` to artisan commands. Use `php artisan make:` commands to generate files.
- **Code style**: Run `vendor/bin/pint --dirty` before finalizing. Always use curly braces for control structures. Use PHP 8 constructor promotion. Always declare return types and type hints.
- **No `env()` outside config files**: Use `config()` helper instead.
- **No `DB::` facade**: Prefer `Model::query()` and Eloquent relationships.

## Creating a New Module

1. Run `php artisan module:make ModuleName --no-interaction`
2. Create `config/permissions.php` in the module with permission definitions
3. Register the module's service provider in `module.json`
4. Add models with `LogsActivityWithModule` trait where audit logging is needed
5. Use `HasModulePermissions` on the User model (already applied in Core's User)
6. Sync permissions with `php artisan core:sync-permissions`

## Testing

- Uses PHPUnit (not Pest). Tests use in-memory SQLite (`phpunit.xml` configures `DB_DATABASE=:memory:`).
- Use `php artisan make:test --phpunit {name}` for feature tests, `--unit` for unit tests.
- Always use model factories in tests. Check existing factory states before manually setting up models.
- Run the minimal number of tests with `--filter` after making changes.
