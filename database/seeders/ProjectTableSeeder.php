<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 25; $i++) {
            $title = fake()->unique()->sentence(3);

            Project::create([
                'title' => $title,
                'repo' => 'atalariq/'.str($title)->slug(),
                'description' => fake()->paragraph(),
            ]);
        }
    }
}
