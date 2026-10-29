<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_project_and_flashes_message(): void
    {
        $response = $this->post(route('projects.store'), [
            'title' => 'My Project',
            'repo' => 'atalariq/my-project',
            'description' => 'A sufficiently long description.',
        ]);

        $response->assertRedirect(route('projects.index'));
        $response->assertSessionHas('success', 'Project created successfully.');
        $this->assertDatabaseHas('projects', ['title' => 'My Project']);
    }

    public function test_store_fails_validation_for_short_title(): void
    {
        $response = $this->post(route('projects.store'), [
            'title' => 'Abcd',
            'repo' => 'atalariq/my-project',
            'description' => 'A sufficiently long description.',
        ]);

        $response->assertSessionHasErrors('title');
        $this->assertDatabaseMissing('projects', ['repo' => 'atalariq/my-project']);
    }

    public function test_store_fails_validation_for_short_description(): void
    {
        $response = $this->post(route('projects.store'), [
            'title' => 'My Project',
            'repo' => 'atalariq/my-project',
            'description' => 'Too short',
        ]);

        $response->assertSessionHasErrors('description');
        $this->assertDatabaseMissing('projects', ['repo' => 'atalariq/my-project']);
    }

    public function test_edit_page_is_accessible(): void
    {
        $project = Project::create([
            'title' => 'Existing Project',
            'repo' => 'atalariq/existing',
            'description' => 'An existing project description.',
        ]);

        $response = $this->get(route('projects.edit', $project));

        $response->assertStatus(200);
    }

    public function test_update_changes_project_and_flashes_message(): void
    {
        $project = Project::create([
            'title' => 'Existing Project',
            'repo' => 'atalariq/existing',
            'description' => 'An existing project description.',
        ]);

        $response = $this->put(route('projects.update', $project), [
            'title' => 'Updated Project',
            'repo' => 'atalariq/existing',
            'description' => 'An updated project description.',
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $response->assertSessionHas('success', 'Project updated successfully.');
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'title' => 'Updated Project']);
    }

    public function test_destroy_deletes_project_and_flashes_message(): void
    {
        $project = Project::create([
            'title' => 'Disposable Project',
            'repo' => 'atalariq/disposable',
            'description' => 'A project that will be deleted.',
        ]);

        $response = $this->delete(route('projects.destroy', $project));

        $response->assertRedirect(route('projects.index'));
        $response->assertSessionHas('success', 'Project deleted successfully.');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_show_returns_404_for_missing_project(): void
    {
        $response = $this->get('/projects/999');

        $response->assertStatus(404);
    }
}
