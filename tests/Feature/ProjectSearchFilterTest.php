<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_matches_title_short_description_and_description(): void
    {
        $this->project(['title' => 'Laravel Portfolio', 'short_description' => 'A Laravel application.']);
        $this->project(['title' => 'Go API', 'short_description' => 'Menu catalog service.']);
        $this->project(['title' => 'Design System', 'description' => 'Tokens for Laravel projects.']);

        $this->get(route('projects.index', ['search' => 'laravel']))
            ->assertOk()
            ->assertSee('Laravel Portfolio')
            ->assertSee('Design System')
            ->assertDontSee('Go API');

        $this->get(route('projects.index', ['search' => 'menu']))
            ->assertOk()
            ->assertSee('Go API')
            ->assertDontSee('Laravel Portfolio');
    }

    public function test_category_filter_uses_the_category_slug(): void
    {
        $web = Category::create(['name' => 'Web']);
        $api = Category::create(['name' => 'API']);

        $this->project(['title' => 'Web Project', 'category_id' => $web->id]);
        $this->project(['title' => 'API Project', 'category_id' => $api->id]);

        $this->get(route('projects.index', ['category' => 'web']))
            ->assertOk()
            ->assertSee('Web Project')
            ->assertDontSee('API Project');
    }

    public function test_technology_filter_uses_the_technology_slug(): void
    {
        $laravel = Technology::create(['name' => 'Laravel']);
        $docker = Technology::create(['name' => 'Docker']);

        $first = $this->project(['title' => 'Laravel Project']);
        $first->technologies()->attach($laravel);

        $second = $this->project(['title' => 'Docker Project']);
        $second->technologies()->attach($docker);

        $this->get(route('projects.index', ['technology' => 'laravel']))
            ->assertOk()
            ->assertSee('Laravel Project')
            ->assertDontSee('Docker Project');
    }

    public function test_sorting_by_title_and_project_date(): void
    {
        $this->project(['title' => 'Zebra', 'project_date' => '2025-01-01']);
        $this->project(['title' => 'Alpha', 'project_date' => '2026-05-01']);

        $this->get(route('projects.index', ['sort' => 'title']))
            ->assertSeeInOrder(['Alpha', 'Zebra']);

        $this->get(route('projects.index', ['sort' => 'project_date']))
            ->assertSeeInOrder(['Alpha', 'Zebra']);
    }

    public function test_per_page_is_limited_to_the_supported_sizes(): void
    {
        foreach (range(1, 10) as $index) {
            $this->project(['title' => "Project {$index}"]);
        }

        $this->get(route('projects.index', ['per_page' => 9]))
            ->assertOk()
            ->assertViewHas('projects', fn ($projects) => $projects->perPage() === 9 && $projects->count() === 9);

        $this->get(route('projects.index', ['per_page' => 100]))
            ->assertOk()
            ->assertViewHas('projects', fn ($projects) => $projects->perPage() === 9);
    }

    public function test_query_string_is_preserved_on_the_next_page(): void
    {
        foreach (range(1, 10) as $index) {
            $this->project(['title' => "Alpha {$index}"]);
        }

        $response = $this->get(route('projects.index', [
            'search' => 'Alpha',
            'sort' => 'title',
            'per_page' => 9,
            'page' => 1,
        ]));

        $response->assertOk();
        $response->assertSee('search=Alpha', false);
        $response->assertSee('sort=title', false);
        $response->assertSee('per_page=9', false);
    }

    public function test_empty_state_is_shown_when_nothing_matches(): void
    {
        $this->project(['title' => 'Existing Project']);

        $this->get(route('projects.index', ['search' => 'no-such-project']))
            ->assertOk()
            ->assertSee('No projects found');
    }

    public function test_draft_projects_are_not_listed_publicly(): void
    {
        $this->project(['title' => 'Visible Project', 'status' => 'published']);
        $this->project(['title' => 'Hidden Draft', 'status' => 'draft']);

        $this->get(route('projects.index'))
            ->assertSee('Visible Project')
            ->assertDontSee('Hidden Draft');
    }

    private function project(array $attributes = []): Project
    {
        return Project::create(array_merge([
            'title' => 'Project '.uniqid(),
            'repository_url' => 'https://github.com/atalariq/example',
            'short_description' => 'A short project description.',
            'description' => 'A longer project description.',
            'status' => 'published',
        ], $attributes));
    }
}
