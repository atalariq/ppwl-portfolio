<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::query()->delete();

        $categories = Category::pluck('id', 'slug');
        $technologies = Technology::pluck('id', 'slug');

        $projects = [
            [
                'title' => 'Menu Catalog API',
                'repo' => 'atalariq/menu-api',
                'description' => 'Functional backend API built collaboratively in GDGoC UGM.',
                'category_id' => $categories['backend-api'] ?? null,
                'status' => 'published',
                'technologies' => ['laravel', 'php', 'mysql'],
            ],
            [
                'title' => 'Sift - AI Recruitment Screening',
                'repo' => 'atalariq/sift',
                'description' => 'AI recruitment screening and talent matching platform.',
                'category_id' => $categories['web-application'] ?? null,
                'status' => 'published',
                'technologies' => ['laravel', 'php', 'tailwind-css'],
            ],
            [
                'title' => 'Sipilah - Circular Resilience App',
                'repo' => 'ayashar/sipilah',
                'description' => 'Circular resilience application for community waste management.',
                'category_id' => $categories['web-application'] ?? null,
                'status' => 'published',
                'technologies' => ['laravel', 'php', 'mysql'],
            ],
            [
                'title' => 'Nyalara - Carbon Footprint Tracker',
                'repo' => 'atalariq/nyalara',
                'description' => 'Carbon footprint tracker app for daily activities.',
                'category_id' => $categories['web-application'] ?? null,
                'status' => 'draft',
                'technologies' => ['laravel', 'php'],
            ],
            [
                'title' => 'Digital Garden',
                'repo' => 'atalariq/digital-garden',
                'description' => 'Personal knowledge base and technical blog using Quartz static site generator.',
                'category_id' => $categories['design-system'] ?? null,
                'status' => 'published',
                'technologies' => ['git', 'docker'],
            ],
        ];

        foreach ($projects as $data) {
            $technologySlugs = $data['technologies'];
            unset($data['technologies']);

            $project = Project::create($data);
            $project->technologies()->sync(
                collect($technologySlugs)
                    ->map(fn (string $slug) => $technologies[$slug] ?? null)
                    ->filter()
                    ->all()
            );
        }
    }
}
