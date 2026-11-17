<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_include_the_published_project_count(): void
    {
        $web = Category::create(['name' => 'Web']);
        $mobile = Category::create(['name' => 'Mobile']);

        Project::create([
            'title' => 'Web One',
            'description' => 'Web project.',
            'repository_url' => 'https://github.com/atalariq/web',
            'status' => 'published',
            'category_id' => $web->id,
        ]);
        Project::create([
            'title' => 'Web Two',
            'description' => 'Web project.',
            'repository_url' => 'https://github.com/atalariq/web',
            'status' => 'published',
            'category_id' => $web->id,
        ]);
        Project::create([
            'title' => 'Draft Web',
            'description' => 'Draft project.',
            'repository_url' => 'https://github.com/atalariq/draft-web',
            'status' => 'draft',
            'category_id' => $web->id,
        ]);

        $this->getJson('/api/categories')
            ->assertOk()
            ->assertJsonPath('data.0.name', 'Mobile')
            ->assertJsonPath('data.0.projects_count', 0)
            ->assertJsonPath('data.1.name', 'Web')
            ->assertJsonPath('data.1.projects_count', 2);
    }
}
