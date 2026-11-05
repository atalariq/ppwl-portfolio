<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create([
            'name' => 'Admin',
            'email' => 'crud-test@example.com',
            'role' => 'admin',
        ]));
    }

    public function test_store_creates_project_with_category_technologies_and_status(): void
    {
        $category = Category::create(['name' => 'Web Application']);
        $technology = Technology::create(['name' => 'Laravel']);

        $response = $this->post(route('post.projects.store'), [
            'title' => 'My Project',
            'repo' => 'atalariq/my-project',
            'description' => 'A sufficiently long description.',
            'category_id' => $category->id,
            'status' => 'published',
            'technologies' => [$technology->id],
        ]);

        $response->assertRedirect(route('post.projects.index'));
        $response->assertSessionHas('success', 'Project created successfully.');
        $this->assertDatabaseHas('projects', [
            'title' => 'My Project',
            'category_id' => $category->id,
            'status' => 'published',
            'slug' => 'my-project',
        ]);
        $this->assertDatabaseHas('project_technology', [
            'project_id' => Project::where('title', 'My Project')->first()->id,
            'technology_id' => $technology->id,
        ]);
    }

    public function test_slug_is_unique_when_titles_collide(): void
    {
        Project::create([
            'title' => 'Same Title',
            'repo' => 'atalariq/one',
            'description' => 'First project description.',
            'status' => 'published',
        ]);

        Project::create([
            'title' => 'Same Title',
            'repo' => 'atalariq/two',
            'description' => 'Second project description.',
            'status' => 'published',
        ]);

        $this->assertDatabaseHas('projects', ['slug' => 'same-title']);
        $this->assertDatabaseHas('projects', ['slug' => 'same-title-2']);
    }

    public function test_store_fails_validation_for_short_title(): void
    {
        $response = $this->post(route('post.projects.store'), [
            'title' => 'Abcd',
            'repo' => 'atalariq/my-project',
            'description' => 'A sufficiently long description.',
            'status' => 'draft',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('projects', ['repo' => 'atalariq/my-project']);
    }

    public function test_store_fails_validation_for_invalid_status(): void
    {
        $response = $this->post(route('post.projects.store'), [
            'title' => 'My Project',
            'repo' => 'atalariq/my-project',
            'description' => 'A sufficiently long description.',
            'status' => 'archived',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertDatabaseMissing('projects', ['repo' => 'atalariq/my-project']);
    }

    public function test_edit_page_is_accessible(): void
    {
        $project = Project::create([
            'title' => 'Existing Project',
            'repo' => 'atalariq/existing',
            'description' => 'An existing project description.',
            'status' => 'draft',
        ]);

        $this->get(route('post.projects.edit', $project))->assertOk();
    }

    public function test_update_changes_project_and_flashes_message(): void
    {
        $project = Project::create([
            'title' => 'Existing Project',
            'repo' => 'atalariq/existing',
            'description' => 'An existing project description.',
            'status' => 'draft',
        ]);

        $response = $this->put(route('post.projects.update', $project), [
            'title' => 'Updated Project',
            'repo' => 'atalariq/existing',
            'description' => 'An updated project description.',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('post.projects.index'));
        $response->assertSessionHas('success', 'Project updated successfully.');
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Project',
            'status' => 'published',
        ]);
    }

    public function test_destroy_deletes_project_and_flashes_message(): void
    {
        $project = Project::create([
            'title' => 'Disposable Project',
            'repo' => 'atalariq/disposable',
            'description' => 'A project that will be deleted.',
            'status' => 'draft',
        ]);

        $response = $this->delete(route('post.projects.destroy', $project));

        $response->assertRedirect(route('post.projects.index'));
        $response->assertSessionHas('success', 'Project deleted successfully.');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_show_returns_404_for_missing_project(): void
    {
        $this->get('/projects/missing-project')->assertNotFound();
    }

    public function test_non_admin_cannot_mutate_projects(): void
    {
        $project = Project::create([
            'title' => 'Protected Project',
            'repo' => 'atalariq/protected',
            'description' => 'A protected project description.',
            'status' => 'published',
        ]);

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->delete(route('post.projects.destroy', $project))
            ->assertForbidden();

        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }
}
