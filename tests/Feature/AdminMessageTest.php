<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMessageTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function message(): ContactMessage
    {
        return ContactMessage::create([
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'subject' => 'Halo',
            'message' => 'Pesan dari form contact.',
        ]);
    }

    public function test_guest_cannot_open_admin_messages(): void
    {
        $this->get(route('admin.messages.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_list_messages(): void
    {
        $this->message();

        $this->actingAs($this->admin())
            ->get(route('admin.messages.index'))
            ->assertOk()
            ->assertSee('Atalariq')
            ->assertSee('Belum dibaca');
    }

    public function test_opening_a_message_marks_it_as_read(): void
    {
        $message = $this->message();

        $this->actingAs($this->admin())
            ->get(route('admin.messages.show', $message))
            ->assertOk()
            ->assertSee('Pesan dari form contact.');

        $this->assertTrue($message->refresh()->is_read);
    }
}
