<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_contact_form_is_public(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('Kirim pesan');
    }

    public function test_a_valid_message_is_stored(): void
    {
        $this->post('/contact', [
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'subject' => 'Halo',
            'message' => 'Ini adalah pesan yang valid.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'is_read' => false,
        ]);
    }

    public function test_invalid_message_is_rejected(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'message' => 'short',
        ])->assertRedirect('/contact')
            ->assertSessionHasErrors(['name', 'email', 'message']);

        $this->assertSame(0, ContactMessage::count());
    }

    public function test_contact_form_is_throttled_after_three_messages(): void
    {
        $payload = [
            'name' => 'Atalariq',
            'email' => 'atalariq@example.com',
            'message' => 'Pesan yang cukup panjang.',
        ];

        foreach (range(1, 3) as $attempt) {
            $this->post('/contact', $payload)->assertRedirect();
        }

        $this->post('/contact', $payload)->assertStatus(429);
        $this->assertSame(3, ContactMessage::count());
    }
}
