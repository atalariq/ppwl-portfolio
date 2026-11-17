<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ApiRateLimitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_api_allows_sixty_requests_then_returns_429(): void
    {
        Project::create([
            'title' => 'Rate Limited Project',
            'description' => 'A project for the rate limit test.',
            'repository_url' => 'https://github.com/atalariq/rate-limit',
            'status' => 'published',
        ]);

        for ($i = 1; $i <= 60; $i++) {
            $this->getJson('/api/projects')->assertOk();
        }

        $response = $this->getJson('/api/projects');

        $response->assertStatus(429)
            ->assertHeader('Retry-After');

        $this->assertSame('60', $response->headers->get('X-RateLimit-Limit'));
    }

    public function test_api_rate_limit_headers_are_present(): void
    {
        $response = $this->getJson('/api/projects')->assertOk();

        $this->assertSame('60', $response->headers->get('X-RateLimit-Limit'));
        $this->assertSame('59', $response->headers->get('X-RateLimit-Remaining'));
    }
}
