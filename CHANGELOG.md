# Changelog

All changes to the PPWL portfolio application. One section per practicum meeting;
add a new section every Thursday after the practicum.

Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/).

## [Pertemuan 11] - 2026-11-12

Search, filter, sorting, and a more complete portfolio admin.

### Added

- Project detail columns, public search, category and technology filters, four sort modes, and per-page pagination.
- Real project, skill, about, and contact seed data with committed seed images.
- About profile and contact CRUD; public About page is data-driven.
- Quick edit, bulk actions, and bulk add for projects, categories, skills, and technologies.
- Lucide icon actions, custom confirmation dialogs, profile dropdown, username login, and upload dropzones.
- Friendly 413/419 handling and larger local/production upload limits.
- Unified public page rhythm, Experience/Education entry-card grid, shared project card, and a featured-project carousel.
- Skills grouped by capability without subjective levels; curated technology tags; letter A favicon.

### Changed

- Admin area moved from `/post/*` to `/admin/*`; legacy `/post/*` URLs redirect.
- Public UI and admin UI use English consistently.
- `repo` became `repository_url`; short and long project descriptions are separate fields.
- Project cards use a thumbnail, first gallery image, or placeholder fallback.
- Projects use a three-column desktop grid, matching the default nine items per page.
- Technology tags are limited to the stack actually used by the seeded projects.

### Removed

- Public styleguide route and view.

Commits: `f1c6ba1`, `0a4bb81`, `02ee91e`, `597c988`, `7df34e8`, `1f2fdbe`.

## [Unreleased]

### Added

- Custom design system: Tailwind 4 tokens and UI Blade components.
- `mise` tasks and a Podman/Docker MariaDB service.
- Full container image (`Dockerfile`, `compose.prod.yaml`) for deploy/demo.
- `CHANGELOG.md` and this update process.

### Changed

- Local development moved from devenv/Lerd/Herd to `mise` + Podman/Docker.
- Canonical local URL is `http://127.0.0.1:8000`; cookie/session/Vite hosts are aligned.
- Public, admin, and auth layouts share one design system.
- Removed Alpine and unused Breeze components; frontend build uses Tailwind plus a small vanilla JS entry for dialogs, quick edit, bulk actions, and upload previews.
- Removed unused Composer/npm packages.

### Fixed

- Login loop caused by inconsistent hosts and `.env.local`/`APP_ENV` layering.
- Flash messages now survive redirects consistently.

Commits: `622e2a9`, `464caef`, `ebab9b0`, `271ee04`, `7c97cdc`, `0deccd2`.

## [Pertemuan 10] - 2026-11-05

Dashboard, file upload, and gallery.

### Added

- Dashboard with `count()`, `withCount()`, top skills, and latest projects.
- `stat-card` component and admin sidebar layout.
- Category, technology, and skill CRUD.
- Thumbnail and avatar upload with dimension validation.
- Project gallery: multiple upload, caption edit, delete, 10-image limit.
- Public project detail by slug with gallery grid.
- Public `/experience` and `/education` pages backed by models and seeders.
- Feature tests for dashboard, uploads, gallery, profile, taxonomy, and public pages.

### Changed

- Projects gain `category_id`, `status`, `slug`, and `thumbnail`.
- Users gain `avatar`.
- Public project index only shows `published` projects; drafts are admin previews.

### Migrations

- `2026_11_05_021350_create_categories_table`
- `2026_11_05_021351_create_technologies_table`
- `2026_11_05_021352_create_skills_table`
- `2026_11_05_021353_create_project_images_table`
- `2026_11_05_021354_create_experiences_table`
- `2026_11_05_021355_create_education_table`
- `2026_11_05_021356_add_dashboard_fields_to_projects_table`
- `2026_11_05_021357_add_avatar_to_users_table`
- `2026_11_05_021358_create_project_technology_table`

Commits: `43a5291`, `34f9654`, `05e6f03`, `f5dd14b`, `1c84322`, `d7db130`, `17a64d1`.

## [Pertemuan 9] - 2026-10-29

Authentication and middleware.

### Added

- Laravel Breeze authentication, `/post` admin area, role column, `EnsureAdmin`.
- Disabled public registration; admin seeder; profile page; non-admin 403.
- Auth feature tests.

### Changed

- Custom `LoginRegisterController` replaced by Breeze controllers.
- Post-login redirect: admin to `/post/projects`, non-admin to home.

Commit: `cc227ab`.

## [Pertemuan 8] - 2026-10-29

Seeder and pagination.

### Added

- Seeders, search, pagination, per-page control, and tests.

Commits: `3b0ed22`, `74a4feb`.

## [Pertemuan 7] - 2026-10-29

Eloquent ORM practice. No submission slot; no separate application commit. The
meeting branch points to the same environment/docs commit as P8.

## [Pertemuan 6] - 2026-09-28

CRUD completion.

### Added

- Edit and delete project flows.

Commit: `38db3ed`.

## [Pertemuan 5] - 2026-09-24

Controller and model.

### Added

- `Project` model, `ProjectController`, and insert form.

Commit: `6fa9cb2`.

## [Pertemuan 4] - 2026-09-16

Routing and Blade views.

### Added

- Home, about, education, projects routes and views.

Commit: `6d4bdbd`.

## [Pertemuan 3] - 2026-09-03

PHP CRUD and Laravel introduction. Covered by the initial Laravel scaffold; no
separate application commit.

## [Pertemuan 2] - 2026-09-03

Laravel installation. Initial scaffold commit: `8b50a6f`.
