<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectAdminActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_quick_edit_updates_the_essentials(): void
    {
        $category = Category::create(['name' => 'Web']);
        $project = $this->project(['title' => 'Old Title', 'status' => 'draft']);

        $this->patch(route('admin.projects.quick-update', $project), [
            'title' => 'New Title',
            'category_id' => $category->id,
            'status' => 'published',
            'project_date' => '2026-04-01',
            'is_featured' => 1,
        ])->assertRedirect();

        $project->refresh();
        $this->assertSame('New Title', $project->title);
        $this->assertSame($category->id, $project->category_id);
        $this->assertSame('published', $project->status);
        $this->assertSame('2026-04-01', $project->project_date->format('Y-m-d'));
        $this->assertTrue($project->is_featured);
    }

    public function test_bulk_publish_draft_feature_and_unfeature(): void
    {
        $first = $this->project(['title' => 'First', 'status' => 'draft']);
        $second = $this->project(['title' => 'Second', 'status' => 'draft']);

        $this->post(route('admin.projects.bulk'), [
            'action' => 'publish',
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();

        $this->assertSame(2, Project::where('status', 'published')->count());

        $this->post(route('admin.projects.bulk'), [
            'action' => 'draft',
            'ids' => [$first->id],
        ])->assertRedirect();

        $this->assertSame('draft', $first->refresh()->status);

        $this->post(route('admin.projects.bulk'), [
            'action' => 'feature',
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();

        $this->assertSame(2, Project::where('is_featured', true)->count());

        $this->post(route('admin.projects.bulk'), [
            'action' => 'unfeature',
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();

        $this->assertSame(0, Project::where('is_featured', true)->count());
    }

    public function test_bulk_change_category(): void
    {
        $category = Category::create(['name' => 'API']);
        $first = $this->project(['title' => 'First']);
        $second = $this->project(['title' => 'Second']);

        $this->post(route('admin.projects.bulk'), [
            'action' => 'category',
            'ids' => [$first->id, $second->id],
            'category_id' => $category->id,
        ])->assertRedirect();

        $this->assertSame(2, Project::where('category_id', $category->id)->count());
    }

    public function test_bulk_delete_removes_projects_images_and_files(): void
    {
        Storage::disk('public')->put('projects/delete/thumbnail.jpg', 'thumb');
        Storage::disk('public')->put('projects/delete/gallery/one.jpg', 'image');

        $project = $this->project(['title' => 'Delete Me', 'thumbnail' => 'projects/delete/thumbnail.jpg']);
        ProjectImage::create([
            'project_id' => $project->id,
            'image' => 'projects/delete/gallery/one.jpg',
            'sort_order' => 1,
        ]);

        $this->post(route('admin.projects.bulk'), [
            'action' => 'delete',
            'ids' => [$project->id],
        ])->assertRedirect();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_images', ['project_id' => $project->id]);
        Storage::disk('public')->assertMissing('projects/delete/thumbnail.jpg');
        Storage::disk('public')->assertMissing('projects/delete/gallery/one.jpg');
    }

    public function test_bulk_requires_at_least_one_project(): void
    {
        $this->post(route('admin.projects.bulk'), [
            'action' => 'delete',
            'ids' => [],
        ])->assertSessionHasErrors('ids');
    }

    public function test_non_admin_cannot_use_quick_edit_or_bulk(): void
    {
        $project = $this->project(['title' => 'Protected']);

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->patch(route('admin.projects.quick-update', $project), [
                'title' => 'Changed',
                'status' => 'published',
            ])
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->post(route('admin.projects.bulk'), [
                'action' => 'delete',
                'ids' => [$project->id],
            ])
            ->assertForbidden();
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
