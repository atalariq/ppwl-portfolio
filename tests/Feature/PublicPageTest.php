<?php

namespace Tests\Feature;

use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_experience_and_education_pages_render_database_records(): void
    {
        Experience::create([
            'role' => 'Backend Hacker',
            'organization' => 'GDGoC UGM',
            'period' => '2025 - Present',
            'sort_order' => 1,
        ]);

        Education::create([
            'institution' => 'Universitas Gadjah Mada',
            'program' => 'D-IV Teknologi Rekayasa Perangkat Lunak',
            'period' => '2025 - Present',
            'details' => ['GPA 3.88/4.00'],
            'sort_order' => 1,
        ]);

        $this->get(route('experience'))->assertOk()->assertSee('Backend Hacker')->assertSee('GDGoC UGM');
        $this->get(route('education'))->assertOk()->assertSee('Universitas Gadjah Mada')->assertSee('GPA 3.88/4.00');
    }

    public function test_public_index_only_lists_published_projects(): void
    {
        Project::create([
            'title' => 'Published Project',
            'repo' => 'atalariq/published',
            'description' => 'A published project description.',
            'status' => 'published',
        ]);

        Project::create([
            'title' => 'Draft Project',
            'repo' => 'atalariq/draft',
            'description' => 'A draft project description.',
            'status' => 'draft',
        ]);

        $response = $this->get(route('projects.index'));

        $response->assertOk()
            ->assertSee('Published Project')
            ->assertDontSee('Draft Project');
    }

    public function test_published_project_detail_is_public(): void
    {
        $project = Project::create([
            'title' => 'Published Detail',
            'repo' => 'atalariq/published-detail',
            'description' => 'A published project description.',
            'status' => 'published',
        ]);

        $this->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Published Detail');
    }

    public function test_draft_project_detail_is_hidden_from_guests_but_visible_to_admin(): void
    {
        $project = Project::create([
            'title' => 'Draft Detail',
            'repo' => 'atalariq/draft-detail',
            'description' => 'A draft project description.',
            'status' => 'draft',
        ]);

        $this->get(route('projects.show', $project))->assertNotFound();

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('projects.show', $project))
            ->assertOk()
            ->assertSee('Draft preview');
    }
}
