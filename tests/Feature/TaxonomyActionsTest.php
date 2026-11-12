<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Skill;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaxonomyActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create(['role' => 'admin']));
    }

    public function test_category_quick_edit_and_bulk_delete(): void
    {
        $first = Category::create(['name' => 'Web']);
        $second = Category::create(['name' => 'API']);

        $this->patch(route('admin.categories.quick-update', $first), ['name' => 'Websites'])
            ->assertRedirect();

        $this->assertDatabaseHas('categories', ['id' => $first->id, 'name' => 'Websites']);

        $this->post(route('admin.categories.bulk'), [
            'action' => 'delete',
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();

        $this->assertDatabaseCount('categories', 0);
    }

    public function test_technology_quick_edit_and_bulk_delete(): void
    {
        $first = Technology::create(['name' => 'Laravel']);
        $second = Technology::create(['name' => 'Docker']);

        $this->patch(route('admin.technologies.quick-update', $first), ['name' => 'Laravel Framework'])
            ->assertRedirect();

        $this->assertDatabaseHas('technologies', ['id' => $first->id, 'name' => 'Laravel Framework']);

        $this->post(route('admin.technologies.bulk'), [
            'action' => 'delete',
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();

        $this->assertDatabaseCount('technologies', 0);
    }

    public function test_skill_quick_edit_and_bulk_delete(): void
    {
        $first = Skill::create(['name' => 'PHP', 'category' => 'Languages']);
        $second = Skill::create(['name' => 'Go', 'category' => 'Languages']);

        $this->patch(route('admin.skills.quick-update', $first), [
            'name' => 'PHP 8',
            'category' => 'Frameworks',
        ])->assertRedirect();

        $this->assertDatabaseHas('skills', ['id' => $first->id, 'name' => 'PHP 8', 'category' => 'Frameworks']);

        $this->post(route('admin.skills.bulk'), [
            'action' => 'delete',
            'ids' => [$first->id, $second->id],
        ])->assertRedirect();

        $this->assertDatabaseCount('skills', 0);
    }

    public function test_bulk_add_categories_skips_duplicates_and_empty_lines(): void
    {
        Category::create(['name' => 'Web']);

        $this->post(route('admin.categories.bulk-store'), [
            'names' => "Web\nAPI\n\nMobile\nMobile\n",
        ])->assertRedirect();

        $this->assertDatabaseHas('categories', ['name' => 'API']);
        $this->assertDatabaseHas('categories', ['name' => 'Mobile']);
        $this->assertSame(1, Category::where('name', 'Web')->count());
        $this->assertSame(1, Category::where('name', 'Mobile')->count());
    }

    public function test_bulk_add_technologies_skips_duplicates(): void
    {
        Technology::create(['name' => 'Laravel']);

        $this->post(route('admin.technologies.bulk-store'), [
            'names' => "Laravel\nDocker\nRedis\n",
        ])->assertRedirect();

        $this->assertDatabaseCount('technologies', 3);
    }

    public function test_bulk_add_skills_uses_optional_categories_and_defaults(): void
    {
        Skill::create(['name' => 'PHP', 'category' => 'Languages']);

        $this->post(route('admin.skills.bulk-store'), [
            'entries' => "PHP\nLaravel, Frameworks\nDocker\nInvalid, ".str_repeat('x', 60),
        ])->assertRedirect();

        $this->assertDatabaseHas('skills', ['name' => 'Laravel', 'category' => 'Frameworks']);
        $this->assertDatabaseHas('skills', ['name' => 'Docker', 'category' => 'Tools']);
        $this->assertDatabaseMissing('skills', ['name' => 'Invalid']);
        $this->assertSame(1, Skill::where('name', 'PHP')->count());
    }

    public function test_non_admin_cannot_manage_taxonomy_actions(): void
    {
        $category = Category::create(['name' => 'Web']);

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->patch(route('admin.categories.quick-update', $category), ['name' => 'Changed'])
            ->assertForbidden();

        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->post(route('admin.categories.bulk'), [
                'action' => 'delete',
                'ids' => [$category->id],
            ])
            ->assertForbidden();
    }
}
