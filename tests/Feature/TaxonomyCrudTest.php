<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Skill;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_skill_can_be_created_updated_and_deleted(): void
    {
        $this->post(route('admin.skills.store'), [
            'name' => 'Laravel',
            'category' => 'Frameworks',
        ])->assertRedirect(route('admin.skills.index'));

        $skill = Skill::where('name', 'Laravel')->firstOrFail();

        $this->put(route('admin.skills.update', $skill), [
            'name' => 'Laravel Framework',
            'category' => 'Frameworks',
        ])->assertRedirect(route('admin.skills.index'));

        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'Laravel Framework', 'category' => 'Frameworks']);

        $this->delete(route('admin.skills.destroy', $skill))
            ->assertRedirect(route('admin.skills.index'));

        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_skill_category_is_required(): void
    {
        $this->post(route('admin.skills.store'), [
            'name' => 'Invalid Skill',
        ])->assertSessionHasErrors('category');

        $this->assertDatabaseMissing('skills', ['name' => 'Invalid Skill']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Web Application']);

        $this->post(route('admin.categories.store'), [
            'name' => 'Web Application',
        ])->assertSessionHasErrors('name');
    }

    public function test_category_can_be_created_and_deleted(): void
    {
        $this->post(route('admin.categories.store'), ['name' => 'Backend API'])
            ->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Backend API')->firstOrFail();
        $this->assertSame('backend-api', $category->slug);

        $this->delete(route('admin.categories.destroy', $category))
            ->assertRedirect(route('admin.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_technology_can_be_created_and_deleted(): void
    {
        $this->post(route('admin.technologies.store'), ['name' => 'Docker'])
            ->assertRedirect(route('admin.technologies.index'));

        $technology = Technology::where('name', 'Docker')->firstOrFail();
        $this->assertSame('docker', $technology->slug);

        $this->delete(route('admin.technologies.destroy', $technology))
            ->assertRedirect(route('admin.technologies.index'));

        $this->assertDatabaseMissing('technologies', ['id' => $technology->id]);
    }

    public function test_non_admin_cannot_manage_taxonomy(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('admin.skills.index'))
            ->assertForbidden();
    }
}
