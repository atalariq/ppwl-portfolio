<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_login_and_register_pages(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Masuk');
        $this->get(route('register'))->assertOk()->assertSee('Buat akun');
    }

    public function test_register_creates_user_and_logs_in(): void
    {
        $response = $this->post(route('store'), [
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', ['email' => 'atalariq@example.com']);
    }

    public function test_register_rejects_mismatched_password(): void
    {
        $response = $this->from(route('register'))->post(route('store'), [
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'beda123456',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'atalariq@example.com']);
    }

    public function test_login_with_valid_credentials_redirects_to_dashboard(): void
    {
        $user = User::create([
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'password' => 'rahasia123',
        ]);

        $response = $this->post(route('authenticate'), [
            'email' => $user->email,
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_shows_an_error(): void
    {
        User::create([
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'password' => 'rahasia123',
        ]);

        $response = $this->from(route('login'))->post(route('authenticate'), [
            'email' => 'atalariq@example.com',
            'password' => 'salah-total',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_logout_invalidates_session(): void
    {
        $user = User::create([
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'password' => 'rahasia123',
        ]);

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_dashboard_to_login(): void
    {
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $user = User::create([
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'password' => 'rahasia123',
        ]);

        $this->actingAs($user)->get(route('login'))->assertRedirect(route('dashboard'));
    }

    public function test_guest_cannot_mutate_projects_but_can_read_them(): void
    {
        $this->get(route('projects.index'))->assertOk();
        $this->get(route('projects.create'))->assertRedirect(route('login'));
        $this->post(route('projects.store'), [
            'title' => 'Blocked Project',
            'repo' => 'atalariq/blocked',
            'description' => 'Should never be stored by a guest.',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('projects', ['title' => 'Blocked Project']);
    }
}
