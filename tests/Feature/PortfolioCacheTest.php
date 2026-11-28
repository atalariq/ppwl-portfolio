<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Support\PortfolioCache;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PortfolioCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function project(array $attributes = []): Project
    {
        return Project::create(array_merge([
            'title' => 'Featured Project',
            'description' => 'A project used by the home page cache test.',
            'repository_url' => 'https://github.com/atalariq/ppwl-portfolio',
            'status' => 'published',
            'is_featured' => true,
            'project_date' => '2026-11-01',
        ], $attributes));
    }

    public function test_home_page_caches_featured_projects(): void
    {
        $this->project();

        $this->get(route('home'))->assertOk();

        $this->assertTrue(Cache::has(PortfolioCache::FEATURED));
    }

    public function test_second_home_request_does_not_query_projects_again(): void
    {
        $this->project();

        $this->get(route('home'))->assertOk();
        $this->app['db']->flushQueryLog();
        DB::enableQueryLog();
        $this->get(route('home'))->assertOk();
        $queries = collect(DB::getQueryLog())->pluck('query');

        $this->assertFalse(
            $queries->contains(fn (string $query) => str_contains($query, 'from "projects"')),
            'The project table was queried again on a cache hit.',
        );
    }

    public function test_experience_and_education_pages_cache_their_rows(): void
    {
        Experience::create([
            'role' => 'Backend Developer',
            'organization' => 'GDGoC UGM',
            'period' => '2025 - Present',
        ]);

        Education::create([
            'institution' => 'Universitas Gadjah Mada',
            'program' => 'D-IV Teknologi Rekayasa Perangkat Lunak',
            'period' => '2025 - Present',
        ]);

        $this->get(route('experience'))->assertOk();
        $this->get(route('education'))->assertOk();

        $this->assertTrue(Cache::has(PortfolioCache::EXPERIENCES));
        $this->assertTrue(Cache::has(PortfolioCache::EDUCATIONS));
    }

    public function test_project_update_forgets_featured_cache(): void
    {
        $project = $this->project();
        Cache::put(PortfolioCache::FEATURED, collect(), 1800);

        $this->actingAs($this->admin())
            ->put(route('admin.projects.update', $project), [
                'title' => 'Edited Project',
                'description' => 'Edited description.',
                'status' => 'published',
            ])
            ->assertRedirect(route('admin.projects.index'));

        $this->assertFalse(Cache::has(PortfolioCache::FEATURED));
    }

    public function test_category_update_forgets_categories_and_featured_cache(): void
    {
        $category = Category::create(['name' => 'Web']);
        Cache::put(PortfolioCache::CATEGORIES, collect(), 3600);
        Cache::put(PortfolioCache::FEATURED, collect(), 1800);

        $this->actingAs($this->admin())
            ->put(route('admin.categories.update', $category), ['name' => 'Web Application'])
            ->assertRedirect(route('admin.categories.index'));

        $this->assertFalse(Cache::has(PortfolioCache::CATEGORIES));
        $this->assertFalse(Cache::has(PortfolioCache::FEATURED));
    }

    public function test_skill_change_forgets_skills_cache(): void
    {
        $skill = Skill::create(['name' => 'PHP', 'category' => 'Languages']);
        Cache::put(PortfolioCache::SKILLS, collect(), 3600);

        $this->actingAs($this->admin())
            ->put(route('admin.skills.update', $skill), ['name' => 'PHP 8', 'category' => 'Languages'])
            ->assertRedirect(route('admin.skills.index'));

        $this->assertFalse(Cache::has(PortfolioCache::SKILLS));
    }

    public function test_experience_and_education_changes_forget_their_cache(): void
    {
        Cache::put(PortfolioCache::EXPERIENCES, collect(), 3600);
        Cache::put(PortfolioCache::EDUCATIONS, collect(), 3600);

        Experience::create([
            'role' => 'Backend Developer',
            'organization' => 'GDGoC UGM',
            'period' => '2025 - Present',
        ]);

        Education::create([
            'institution' => 'Universitas Gadjah Mada',
            'program' => 'D-IV Teknologi Rekayasa Perangkat Lunak',
            'period' => '2025 - Present',
        ]);

        $this->assertFalse(Cache::has(PortfolioCache::EXPERIENCES));
        $this->assertFalse(Cache::has(PortfolioCache::EDUCATIONS));
    }

    public function test_admin_can_clear_all_portfolio_cache(): void
    {
        foreach (PortfolioCache::KEYS as $key) {
            Cache::put($key, collect(), 3600);
        }

        $this->actingAs($this->admin())
            ->post(route('admin.cache.clear'))
            ->assertRedirect();

        foreach (PortfolioCache::KEYS as $key) {
            $this->assertFalse(Cache::has($key), "Cache key {$key} was not cleared.");
        }
    }

    public function test_guest_cannot_clear_portfolio_cache(): void
    {
        Cache::put(PortfolioCache::FEATURED, collect(), 1800);

        $this->post(route('admin.cache.clear'))->assertRedirect(route('login'));

        $this->assertTrue(Cache::has(PortfolioCache::FEATURED));
    }
}
