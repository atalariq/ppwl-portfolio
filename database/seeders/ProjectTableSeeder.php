<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Database\Seeder;

class ProjectTableSeeder extends Seeder
{
    public function run(): void
    {
        $categoryIds = Category::pluck('id')->all();
        $technologyIds = Technology::pluck('id')->all();

        for ($i = 1; $i <= 25; $i++) {
            $title = fake()->unique()->sentence(3);

            $project = Project::create([
                'title' => $title,
                'repo' => 'atalariq/'.str($title)->slug(),
                'description' => fake()->paragraph(),
                'category_id' => fake()->randomElement($categoryIds),
                'status' => fake()->randomElement(['published', 'published', 'draft']),
            ]);

            $project->technologies()->sync(
                fake()->randomElements($technologyIds, fake()->numberBetween(1, 3))
            );
        }
    }
}
