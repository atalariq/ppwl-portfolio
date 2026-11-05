<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get(route('post.dashboard'))->assertRedirect(route('login'));
    }

    public function test_non_admin_is_forbidden_from_dashboard(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'user']))
            ->get(route('post.dashboard'))
            ->assertForbidden();
    }

    public function test_dashboard_shows_counts_and_published_draft_split(): void
    {
        $category = Category::create(['name' => 'Web Application']);
        Technology::create(['name' => 'Laravel']);
        Skill::create(['name' => 'PHP', 'level' => 90]);
        Skill::create(['name' => 'Laravel', 'level' => 95]);

        Project::create([
            'title' => 'Published Project',
            'repo' => 'atalariq/published',
            'description' => 'A published project description.',
            'status' => 'published',
            'category_id' => $category->id,
        ]);

        Project::create([
            'title' => 'Draft Project',
            'repo' => 'atalariq/draft',
            'description' => 'A draft project description.',
            'status' => 'draft',
            'category_id' => $category->id,
        ]);

        $response = $this->actingAs($this->admin())->get(route('post.dashboard'));

        $response->assertOk()
            ->assertSee('Published')
            ->assertSee('Draft')
            ->assertSee('PHP')
            ->assertSee('Laravel')
            ->assertSee('Web Application');

        $this->assertSame(2, Project::count());
        $this->assertSame(1, Project::where('status', 'published')->count());
        $this->assertSame(1, Project::where('status', 'draft')->count());
        $this->assertSame(2, Project::where('status', 'published')->count() + Project::where('status', 'draft')->count());
    }
}
