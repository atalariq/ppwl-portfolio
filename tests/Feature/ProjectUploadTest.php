<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_store_saves_thumbnail_to_public_disk(): void
    {
        $response = $this->post(route('post.projects.store'), [
            'title' => 'Thumbnail Project',
            'repo' => 'atalariq/thumbnail',
            'description' => 'A sufficiently long description.',
            'status' => 'published',
            'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg', 800, 600),
        ]);

        $response->assertRedirect(route('post.projects.index'));

        $project = Project::where('title', 'Thumbnail Project')->firstOrFail();
        $this->assertNotNull($project->thumbnail);
        Storage::disk('public')->assertExists($project->thumbnail);
    }

    public function test_update_replaces_old_thumbnail_and_deletes_the_old_file(): void
    {
        $oldPath = 'projects/thumbnails/old.jpg';
        Storage::disk('public')->put($oldPath, 'old');

        $project = Project::create([
            'title' => 'Replace Thumbnail',
            'repo' => 'atalariq/replace',
            'description' => 'A sufficiently long description.',
            'status' => 'draft',
            'thumbnail' => $oldPath,
        ]);

        $response = $this->put(route('post.projects.update', $project), [
            'title' => 'Replace Thumbnail',
            'repo' => 'atalariq/replace',
            'description' => 'A sufficiently long description.',
            'status' => 'draft',
            'thumbnail' => UploadedFile::fake()->image('new.jpg', 800, 600),
        ]);

        $response->assertRedirect(route('post.projects.index'));

        $project->refresh();
        $this->assertNotSame($oldPath, $project->thumbnail);
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($project->thumbnail);
    }

    public function test_thumbnail_must_meet_minimum_dimensions(): void
    {
        $response = $this->post(route('post.projects.store'), [
            'title' => 'Small Thumbnail',
            'repo' => 'atalariq/small',
            'description' => 'A sufficiently long description.',
            'status' => 'draft',
            'thumbnail' => UploadedFile::fake()->image('small.jpg', 200, 200),
        ]);

        $response->assertSessionHasErrors('thumbnail');
        $this->assertDatabaseMissing('projects', ['repo' => 'atalariq/small']);
    }

    public function test_thumbnail_must_be_an_image(): void
    {
        $response = $this->post(route('post.projects.store'), [
            'title' => 'PDF Thumbnail',
            'repo' => 'atalariq/pdf',
            'description' => 'A sufficiently long description.',
            'status' => 'draft',
            'thumbnail' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors('thumbnail');
        $this->assertDatabaseMissing('projects', ['repo' => 'atalariq/pdf']);
    }

    public function test_destroy_deletes_thumbnail_and_gallery_files(): void
    {
        $thumbnail = 'projects/thumbnails/delete.jpg';
        $gallery = 'projects/gallery/delete.jpg';
        Storage::disk('public')->put($thumbnail, 'thumb');
        Storage::disk('public')->put($gallery, 'gallery');

        $project = Project::create([
            'title' => 'Delete Files',
            'repo' => 'atalariq/delete-files',
            'description' => 'A sufficiently long description.',
            'status' => 'draft',
            'thumbnail' => $thumbnail,
        ]);

        ProjectImage::create([
            'project_id' => $project->id,
            'image' => $gallery,
            'sort_order' => 1,
        ]);

        $this->delete(route('post.projects.destroy', $project))
            ->assertRedirect(route('post.projects.index'));

        Storage::disk('public')->assertMissing($thumbnail);
        Storage::disk('public')->assertMissing($gallery);
        $this->assertDatabaseMissing('project_images', ['project_id' => $project->id]);
    }
}
