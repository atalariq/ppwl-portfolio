<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::query()->delete();
        Storage::disk('public')->deleteDirectory('projects');

        $categories = Category::pluck('id', 'slug');
        $technologies = Technology::pluck('id', 'slug');

        foreach ($this->projects() as $data) {
            $slug = $data['slug'];
            $technologySlugs = $data['technologies'];
            $gallery = $data['gallery'] ?? [];
            $thumbnail = $data['thumbnail'] ?? null;
            $category = $data['category'];
            unset($data['technologies'], $data['gallery'], $data['thumbnail'], $data['category']);

            $project = Project::create($data + [
                'category_id' => $categories[$category] ?? null,
            ]);

            $project->technologies()->sync(
                collect($technologySlugs)
                    ->map(fn (string $technology) => $technologies[$technology] ?? null)
                    ->filter()
                    ->all()
            );

            if ($path = $this->copyImage($slug, $thumbnail, "projects/{$slug}/thumbnail")) {
                $project->update(['thumbnail' => $path]);
            }

            foreach ($gallery as $index => $image) {
                $path = $this->copyImage($slug, $image['file'], "projects/{$slug}/gallery/".pathinfo($image['file'], PATHINFO_FILENAME));

                if ($path) {
                    $project->images()->create([
                        'image' => $path,
                        'caption' => $image['caption'] ?? null,
                        'sort_order' => $index + 1,
                    ]);
                }
            }
        }
    }

    private function copyImage(string $slug, ?string $file, string $target): ?string
    {
        if (blank($file)) {
            return null;
        }

        $source = database_path("seeders/assets/projects/{$slug}/{$file}");

        if (! File::exists($source)) {
            return null;
        }

        $target .= '.'.pathinfo($file, PATHINFO_EXTENSION);

        Storage::disk('public')->put($target, File::get($source));

        return $target;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function projects(): array
    {
        return [
            [
                'title' => 'Menu Catalog API',
                'slug' => 'menu-catalog-api',
                'category' => 'api',
                'short_description' => 'A Go REST API for browsing a menu catalog, with search, filters, and Gemini-powered recommendations.',
                'description' => "Menu Catalog API is a backend service built for the GDGoC UGM 2025 hacker study case. It follows the Standard Go Project Layout and separates HTTP handlers, business logic, and database access.\n\nThe API supports CRUD, category and price filters, full-text search, OpenAPI documentation, and Gemini-powered descriptions and recommendations. It runs on PostgreSQL with GORM and is deployed with Fly.io.",
                'project_date' => '2025-11-27',
                'is_featured' => false,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/menu-api',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['go', 'postgresql', 'gemini-api', 'docker', 'flyio'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Swagger documentation for the menu catalog endpoints.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Repository overview and project structure.'],
                ],
            ],
            [
                'title' => 'Sift',
                'slug' => 'sift',
                'category' => 'web',
                'short_description' => 'An AI recruitment platform that screens CVs and matches candidates to job openings.',
                'description' => "Sift is a collaborative recruitment platform that pairs a Next.js dashboard with an Express and Prisma backend. Recruiters can create job openings, upload CVs, and review AI-generated candidate summaries powered by Gemini.\n\nThe backend uses JWT authentication, Zod validation, PostgreSQL, and a documented REST API. The interface focuses on a clear hiring pipeline: jobs, candidates, and structured analysis.",
                'project_date' => '2026-02-02',
                'is_featured' => false,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/sift',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['nextjs', 'react', 'tailwind-css', 'express', 'typescript', 'prisma', 'postgresql', 'gemini-api'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Recruiter dashboard with job openings and candidate activity.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Candidate review flow with AI-assisted screening.'],
                ],
            ],
            [
                'title' => 'Journey',
                'slug' => 'journey',
                'category' => 'web',
                'short_description' => 'A personal digital garden and weekly journal built from scratch with Astro.',
                'description' => "Journey is a public digital garden for weekly logs and structured notes. Content lives in a private knowledge vault and is exposed to the build through opt-in symlinks, so only published notes reach the site.\n\nThe frontend is an Astro project deployed to Cloudflare Pages, with search, RSS, and a sitemap. The design uses a quiet reading layout that keeps long-form notes comfortable on both desktop and mobile.",
                'project_date' => '2026-07-04',
                'is_featured' => true,
                'project_url' => 'https://journey.atalariq.dev',
                'repository_url' => 'https://github.com/atalariq/dev-journey',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['astro', 'typescript', 'cloudflare-pages'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Journey home page with the latest weekly logs.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'A long-form note rendered by the Astro content pipeline.'],
                ],
            ],
            [
                'title' => 'Personal Picks',
                'slug' => 'personal-picks',
                'category' => 'web',
                'short_description' => 'A curated collection of favourite albums, films, and series.',
                'description' => "Personal Picks is a small static site for album, film, and series recommendations. Each entry pairs artwork with a short note, and the page is organized into listening, watching, and series sections.\n\nIt was built with plain HTML and CSS and deployed to GitHub Pages. The project is intentionally simple: no framework, no build step, and a layout that puts the artwork first.",
                'project_date' => '2026-04-03',
                'is_featured' => false,
                'project_url' => 'https://atalariq.github.io/personal-picks',
                'repository_url' => 'https://github.com/atalariq/personal-picks',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['html', 'css', 'javascript'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Album and film recommendations arranged by mood.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Series section with artwork-led cards.'],
                ],
            ],
            [
                'title' => 'LaptopVal',
                'slug' => 'laptopval',
                'category' => 'web',
                'short_description' => 'A transparent evaluator that scores used-laptop deals and explains every factor.',
                'description' => "LaptopVal turns laptop specs and asking prices into a clear verdict: Great Deal, Fair, Overpriced, or Avoid. The scoring engine weighs CPU, GPU, RAM, storage, condition, warranty, age, and region, then shows a per-factor breakdown.\n\nThe project includes a public catalog, a self-service evaluator, comparison views, and an admin area for managing the catalog. It uses native PHP, MySQL triggers, and Bootstrap, and runs in Docker.",
                'project_date' => '2026-07-04',
                'is_featured' => true,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/LaptopVal',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['php', 'mysql', 'bootstrap', 'docker'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Laptop catalog with verdict badges and spec chips.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Score breakdown for a single listing.'],
                ],
            ],
            [
                'title' => 'Wana',
                'slug' => 'wana',
                'category' => 'design-system',
                'short_description' => 'A CSS design system with a base24 palette, terminal themes, and an app theme pipeline.',
                'description' => "Wana is a personal design system built on CSS custom properties. It defines a warm light palette and a Gruvbox-inspired dark palette, then generates matching themes for Neovim and Yazi.\n\nThere is no build step or framework: one CSS file provides tokens for colour, spacing, radius, and motion. The project also documents how the same palette is carried into terminal and editor themes.",
                'project_date' => '2026-07-04',
                'is_featured' => false,
                'project_url' => 'https://atalariq.dev/wana/',
                'repository_url' => 'https://github.com/atalariq/wana',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['css', 'python'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Wana light and dark palettes.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Theme previews generated from the same palette.'],
                ],
            ],
            [
                'title' => 'atalariq.dev',
                'slug' => 'atalariq-dev',
                'category' => 'web',
                'short_description' => 'A terminal-inspired personal site that brings projects, notes, and links together.',
                'description' => "atalariq.dev is a small, fast personal site with a terminal-inspired interface. It acts as a central index for projects, writing, and experiments.\n\nThe site is built with plain HTML, CSS, and JavaScript, and is covered by Playwright end-to-end tests. It is designed to stay lightweight while still feeling personal and distinctive.",
                'project_date' => '2026-10-24',
                'is_featured' => false,
                'project_url' => 'https://atalariq.dev',
                'repository_url' => 'https://github.com/atalariq/atalariq.github.io',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['html', 'css', 'javascript', 'playwright'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Terminal-inspired home page.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Project and writing index.'],
                ],
            ],
            [
                'title' => 'PPW1',
                'slug' => 'ppw1',
                'category' => 'web',
                'short_description' => 'An archive of exercises and assignments from Web Programming 1.',
                'description' => "PPW1 collects the weekly exercises from the first web programming course, from HTML fundamentals and CSS layouts to Bootstrap and JavaScript.\n\nEach meeting lives in its own folder and can be opened directly in the browser. The archive is useful as a record of the fundamentals behind the later Laravel work.",
                'project_date' => '2026-06-17',
                'is_featured' => false,
                'project_url' => 'https://atalariq.dev/PPW1/',
                'repository_url' => 'https://github.com/atalariq/PPW1',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['html', 'css', 'javascript', 'bootstrap'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Course archive index.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'One of the weekly exercise pages.'],
                ],
            ],
            [
                'title' => 'PPWL Portfolio',
                'slug' => 'ppwl-portfolio',
                'category' => 'web',
                'short_description' => 'The Laravel portfolio application that grows across the Advanced Web Programming practicum.',
                'description' => "This portfolio is a Laravel application built meeting by meeting. It combines public pages, an authenticated admin area, project and gallery management, file uploads, and a searchable project catalog.\n\nThe stack is PHP 8.5, Laravel 13, Tailwind CSS 4, MariaDB, and Vite. The source is organized so each practicum meeting can be reviewed as a separate, reproducible step.",
                'project_date' => '2026-11-12',
                'is_featured' => false,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/ppwl-portfolio',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['laravel', 'php', 'tailwind-css', 'mariadb', 'docker'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Public project catalog with search and filters.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Admin dashboard with project and gallery management.'],
                ],
            ],
            [
                'title' => 'Typst Packages',
                'slug' => 'typst-packages',
                'category' => 'tool',
                'short_description' => 'A collection of Typst packages for practical lab reports and academic documents.',
                'description' => "Typst Packages contains reusable templates and helpers for Typst-based reports. The packages cover cover pages, tables, code blocks, and bibliography conventions used in coursework.\n\nThey are installed locally through a small setup script and shared across reports. The goal is to make academic documents reproducible without copying the same layout code into every file.",
                'project_date' => '2026-09-28',
                'is_featured' => true,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/typst-packages',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['typst', 'shell'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Repository layout and package setup.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'A report page rendered with the shared package.'],
                ],
            ],
            [
                'title' => 'Dotfiles',
                'slug' => 'dotfiles',
                'category' => 'tool',
                'short_description' => 'A reproducible Linux setup built with Nix, home-manager, and a terminal-first workflow.',
                'description' => "Dotfiles keeps the configuration for a reproducible Linux environment: shell, editor, terminal, window manager, and development tools.\n\nNix and home-manager make the setup portable, while the repository documents the choices behind each module. The result is a machine that can be rebuilt from a flake instead of remembered by hand.",
                'project_date' => '2026-11-12',
                'is_featured' => true,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/dotfiles',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['nix', 'shell'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Repository overview and module layout.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Terminal workflow configured by the flake.'],
                ],
            ],
            [
                'title' => 'Nyalara',
                'slug' => 'nyalara',
                'category' => 'mobile',
                'short_description' => 'A mobile app that turns household device usage into carbon-footprint insights.',
                'description' => "Nyalara tracks electricity use from everyday devices and translates it into daily patterns, carbon estimates, and practical recommendations.\n\nThe Expo and React Native client talks to a Hono API backed by Firestore, with Gemini-generated monthly insights and verified emission factors for eight countries. The app is designed to make energy habits understandable without asking people to think in raw technical numbers first.",
                'project_date' => '2026-05-28',
                'is_featured' => true,
                'project_url' => null,
                'repository_url' => 'https://github.com/atalariq/nyalara',
                'status' => 'published',
                'thumbnail' => 'thumbnail.jpg',
                'technologies' => ['react-native', 'expo', 'typescript', 'hono', 'firestore', 'firebase', 'gemini-api'],
                'gallery' => [
                    ['file' => 'gallery/01.jpg', 'caption' => 'Daily energy dashboard with device activity.'],
                    ['file' => 'gallery/02.jpg', 'caption' => 'Carbon budget and monthly insight screen.'],
                ],
            ],
        ];
    }
}
