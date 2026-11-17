<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    private function project(array $attributes = []): Project
    {
        return Project::create(array_merge([
            'title' => 'Portfolio API',
            'repository_url' => 'https://github.com/atalariq/portfolio',
            'description' => 'A portfolio project with an API.',
            'status' => 'published',
        ], $attributes));
    }

    public function test_index_returns_published_projects_with_pagination_metadata(): void
    {
        $category = Category::create(['name' => 'Web']);
        $technology = Technology::create(['name' => 'Laravel']);
        $project = $this->project(['title' => 'Portfolio API', 'category_id' => $category->id]);
        $project->technologies()->attach($technology);
        $this->project(['title' => 'Draft API', 'status' => 'draft']);

        $this->getJson('/api/projects')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Portfolio API')
            ->assertJsonPath('data.0.category', 'Web')
            ->assertJsonPath('data.0.technologies.0', 'Laravel')
            ->assertJsonStructure([
                'data' => [['title', 'slug', 'summary', 'thumbnail', 'category', 'technologies', 'url']],
                'links',
                'meta',
            ]);
    }

    public function test_index_supports_search_category_technology_and_sort(): void
    {
        $web = Category::create(['name' => 'Web']);
        $mobile = Category::create(['name' => 'Mobile']);
        $laravel = Technology::create(['name' => 'Laravel']);
        $kotlin = Technology::create(['name' => 'Kotlin']);

        $first = $this->project(['title' => 'Laravel API', 'category_id' => $web->id, 'project_date' => '2025-01-01']);
        $first->technologies()->attach($laravel);
        $second = $this->project(['title' => 'Kotlin App', 'category_id' => $mobile->id, 'project_date' => '2026-01-01']);
        $second->technologies()->attach($kotlin);

        $this->getJson('/api/projects?search=laravel')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Laravel API');

        $this->getJson('/api/projects?category=mobile')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Kotlin App');

        $this->getJson('/api/projects?technology=laravel')
            ->assertOk()
            ->assertJsonPath('meta.total', 1)
            ->assertJsonPath('data.0.title', 'Laravel API');

        $this->getJson('/api/projects?sort=title')
            ->assertOk()
            ->assertJsonPath('data.0.title', 'Kotlin App');
    }

    public function test_show_returns_a_published_project_and_hides_drafts(): void
    {
        $published = $this->project(['title' => 'Published Project']);
        $draft = $this->project(['title' => 'Draft Project', 'status' => 'draft']);

        $this->getJson('/api/projects/'.$published->slug)
            ->assertOk()
            ->assertJsonPath('data.title', 'Published Project');

        $this->getJson('/api/projects/'.$draft->slug)->assertNotFound();
        $this->getJson('/api/projects/tidak-ada')->assertNotFound();
    }

    public function test_per_page_is_limited_to_supported_sizes(): void
    {
        foreach (range(1, 10) as $index) {
            $this->project(['title' => "Project {$index}"]);
        }

        $this->getJson('/api/projects?per_page=100')
            ->assertOk()
            ->assertJsonPath('meta.per_page', 9);
    }
}
