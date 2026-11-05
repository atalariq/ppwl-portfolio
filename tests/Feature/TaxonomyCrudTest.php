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
        $this->post(route('post.skills.store'), [
            'name' => 'Laravel',
            'level' => 90,
        ])->assertRedirect(route('post.skills.index'));

        $skill = Skill::where('name', 'Laravel')->firstOrFail();

        $this->put(route('post.skills.update', $skill), [
            'name' => 'Laravel Framework',
            'level' => 95,
        ])->assertRedirect(route('post.skills.index'));

        $this->assertDatabaseHas('skills', ['id' => $skill->id, 'name' => 'Laravel Framework', 'level' => 95]);

        $this->delete(route('post.skills.destroy', $skill))
            ->assertRedirect(route('post.skills.index'));

        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    public function test_skill_level_must_be_between_one_and_one_hundred(): void
    {
        $this->post(route('post.skills.store'), [
            'name' => 'Invalid Skill',
            'level' => 101,
        ])->assertSessionHasErrors('level');

        $this->assertDatabaseMissing('skills', ['name' => 'Invalid Skill']);
    }

    public function test_category_name_must_be_unique(): void
    {
        Category::create(['name' => 'Web Application']);

        $this->post(route('post.categories.store'), [
            'name' => 'Web Application',
        ])->assertSessionHasErrors('name');
    }

    public function test_category_can_be_created_and_deleted(): void
    {
        $this->post(route('post.categories.store'), ['name' => 'Backend API'])
            ->assertRedirect(route('post.categories.index'));

        $category = Category::where('name', 'Backend API')->firstOrFail();
        $this->assertSame('backend-api', $category->slug);

        $this->delete(route('post.categories.destroy', $category))
            ->assertRedirect(route('post.categories.index'));

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_technology_can_be_created_and_deleted(): void
    {
        $this->post(route('post.technologies.store'), ['name' => 'Docker'])
            ->assertRedirect(route('post.technologies.index'));

        $technology = Technology::where('name', 'Docker')->firstOrFail();
        $this->assertSame('docker', $technology->slug);

        $this->delete(route('post.technologies.destroy', $technology))
            ->assertRedirect(route('post.technologies.index'));

        $this->assertDatabaseMissing('technologies', ['id' => $technology->id]);
    }

    public function test_non_admin_cannot_manage_taxonomy(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('post.skills.index'))
            ->assertForbidden();
    }
}
