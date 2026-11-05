<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectGalleryTest extends TestCase
{
    use RefreshDatabase;

    private Project $project;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->project = Project::create([
            'title' => 'Gallery Project',
            'repo' => 'atalariq/gallery',
            'description' => 'A sufficiently long description.',
            'status' => 'published',
        ]);
    }

    public function test_multiple_images_are_uploaded_and_ordered(): void
    {
        $response = $this->post(route('post.projects.images.store', $this->project), [
            'images' => [
                UploadedFile::fake()->image('one.jpg', 800, 600),
                UploadedFile::fake()->image('two.jpg', 800, 600),
                UploadedFile::fake()->image('three.jpg', 800, 600),
            ],
        ]);

        $response->assertRedirect();

        $this->assertSame(3, $this->project->images()->count());
        $this->assertSame([1, 2, 3], $this->project->images()->pluck('sort_order')->all());

        foreach ($this->project->images as $image) {
            Storage::disk('public')->assertExists($image->image);
        }
    }

    public function test_eleventh_image_is_rejected(): void
    {
        for ($i = 1; $i <= 9; $i++) {
            ProjectImage::create([
                'project_id' => $this->project->id,
                'image' => 'projects/gallery/existing-'.$i.'.jpg',
                'sort_order' => $i,
            ]);
        }

        $response = $this->post(route('post.projects.images.store', $this->project), [
            'images' => [
                UploadedFile::fake()->image('ten.jpg', 800, 600),
                UploadedFile::fake()->image('eleven.jpg', 800, 600),
            ],
        ]);

        $response->assertSessionHasErrors('images');
        $this->assertSame(9, $this->project->images()->count());
    }

    public function test_caption_can_be_updated(): void
    {
        $image = ProjectImage::create([
            'project_id' => $this->project->id,
            'image' => 'projects/gallery/caption.jpg',
            'caption' => 'Old caption',
            'sort_order' => 1,
        ]);

        $response = $this->put(route('post.projects.images.update', [$this->project, $image]), [
            'caption' => 'New caption',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('project_images', [
            'id' => $image->id,
            'caption' => 'New caption',
        ]);
    }

    public function test_image_can_be_deleted_from_storage_and_database(): void
    {
        Storage::disk('public')->put('projects/gallery/delete.jpg', 'content');

        $image = ProjectImage::create([
            'project_id' => $this->project->id,
            'image' => 'projects/gallery/delete.jpg',
            'sort_order' => 1,
        ]);

        $this->delete(route('post.projects.images.destroy', [$this->project, $image]))
            ->assertRedirect();

        Storage::disk('public')->assertMissing('projects/gallery/delete.jpg');
        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
    }

    public function test_image_from_another_project_returns_404(): void
    {
        $other = Project::create([
            'title' => 'Other Project',
            'repo' => 'atalariq/other',
            'description' => 'Another project description.',
            'status' => 'draft',
        ]);

        $image = ProjectImage::create([
            'project_id' => $other->id,
            'image' => 'projects/gallery/other.jpg',
            'sort_order' => 1,
        ]);

        $this->delete(route('post.projects.images.destroy', [$this->project, $image]))
            ->assertNotFound();

        $this->assertDatabaseHas('project_images', ['id' => $image->id]);
    }
}
