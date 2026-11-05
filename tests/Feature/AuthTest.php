<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    private function regularUser(): User
    {
        return User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role' => 'user',
        ]);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Log in');
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [
            'name' => 'New User',
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertNotFound();
    }

    public function test_admin_is_redirected_to_the_management_area_after_login(): void
    {
        $admin = $this->admin();

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('post.projects.index', absolute: false));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_is_redirected_home_with_a_status_message(): void
    {
        $user = $this->regularUser();

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('status');
        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_invalid_credentials_shows_an_error(): void
    {
        $user = $this->admin();

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        $user = $this->admin();

        foreach (range(1, 5) as $attempt) {
            $this->post(route('login'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ]);
        }

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = $this->admin();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_guest_is_redirected_from_the_management_area_to_login(): void
    {
        $this->get(route('post.projects.index'))->assertRedirect(route('login'));
    }

    public function test_non_admin_gets_forbidden_from_the_management_area(): void
    {
        $this->actingAs($this->regularUser())
            ->get(route('post.projects.index'))
            ->assertForbidden();
    }

    public function test_admin_can_open_the_management_area(): void
    {
        $this->actingAs($this->admin())
            ->get(route('post.projects.index'))
            ->assertOk();
    }

    public function test_authenticated_user_is_redirected_away_from_login(): void
    {
        $this->actingAs($this->admin())
            ->get(route('login'))
            ->assertRedirect('/');
    }

    public function test_profile_page_requires_login_and_is_available_to_non_admin(): void
    {
        $this->get(route('post.profile.edit'))->assertRedirect(route('login'));

        $this->actingAs($this->regularUser())
            ->get(route('post.profile.edit'))
            ->assertOk()
            ->assertSee('Regular User');
    }
}
