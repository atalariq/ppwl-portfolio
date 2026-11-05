<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed_for_authenticated_user(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('post.profile.edit'))
            ->assertOk();
    }

    public function test_guest_is_redirected_from_profile_page(): void
    {
        $this->get(route('post.profile.edit'))->assertRedirect(route('login'));
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('post.profile.update'), [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ]);

        $response->assertSessionHasNoErrors()->assertRedirect(route('post.profile.edit'));

        $user->refresh();
        $this->assertSame('Updated User', $user->name);
        $this->assertSame('updated@example.com', $user->email);
    }

    public function test_avatar_can_be_uploaded_and_old_avatar_is_deleted(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('avatars/old.jpg', 'old');

        $user = User::factory()->create(['avatar' => 'avatars/old.jpg']);

        $response = $this->actingAs($user)->patch(route('post.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
        ]);

        $response->assertSessionHasNoErrors();

        $user->refresh();
        $this->assertNotSame('avatars/old.jpg', $user->avatar);
        Storage::disk('public')->assertMissing('avatars/old.jpg');
        Storage::disk('public')->assertExists($user->avatar);
    }

    public function test_avatar_must_meet_minimum_dimensions(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->patch(route('post.profile.update'), [
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => UploadedFile::fake()->image('small-avatar.jpg', 100, 100),
        ]);

        $response->assertSessionHasErrors('avatar');
        $this->assertNull($user->refresh()->avatar);
    }
}
