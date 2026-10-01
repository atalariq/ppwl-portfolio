<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $projects = [
            ['title' => 'Menu Catalog API', 'repo' => 'atalariq/menu-api', 'description' => 'Functional backend API built collaboratively in GDGoC UGM.'],
            ['title' => 'Sift - AI Recruitment Screening', 'repo' => 'atalariq/sift', 'description' => 'AI recruitment screening & talent matching platform.'],
            ['title' => 'Sipilah - Circular Resilience App', 'repo' => 'ayashar/sipilah', 'description' => 'Circular resilience application.'],
            ['title' => 'Nyalara - Carbon Footprint Tracker', 'repo' => 'atalariq/nyalara', 'description' => 'Carbon footprint tracker app.'],
            ['title' => 'Digital Garden', 'repo' => 'atalariq/digital-garden', 'description' => 'Personal knowledge base and technical blog using Quartz static site generator.'],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
