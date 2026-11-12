<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class UploadErrorTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_too_large_redirects_back_with_a_friendly_error(): void
    {
        Route::middleware('web')->get('/__test/post-too-large', function () {
            throw new PostTooLargeException;
        });

        $response = $this->from('/admin/projects')->get('/__test/post-too-large');

        $response->assertRedirect('/admin/projects');
        $response->assertSessionHas('error', 'The upload is too large. Please choose smaller files and try again.');
    }

    public function test_expired_session_redirects_back_with_a_friendly_error(): void
    {
        Route::middleware('web')->get('/__test/token-mismatch', function () {
            throw new TokenMismatchException;
        });

        $response = $this->from('/login')->get('/__test/token-mismatch');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error', 'Your session expired. Please try again.');
    }
}
