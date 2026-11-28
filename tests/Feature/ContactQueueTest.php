<?php

namespace Tests\Feature;

use App\Jobs\SendContactNotification;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ContactQueueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_contact_form_dispatches_notification_job(): void
    {
        Queue::fake();

        $this->post('/contact', [
            'name' => 'Atalariq',
            'email' => 'atalariq.dev@outlook.com',
            'subject' => 'Kolaborasi',
            'message' => 'Saya ingin berdiskusi tentang proyek web.',
        ])->assertRedirect();

        $message = ContactMessage::firstOrFail();

        Queue::assertPushed(
            SendContactNotification::class,
            fn (SendContactNotification $job) => $job->contactMessage->is($message),
        );
    }

    public function test_notification_job_is_tried_twice(): void
    {
        $job = new SendContactNotification(new ContactMessage);

        $this->assertSame(2, $job->tries);
    }

    public function test_failed_job_logs_error_with_message_id(): void
    {
        Log::spy();

        $message = ContactMessage::create([
            'name' => 'Atalariq',
            'email' => 'atalariq.dev@outlook.com',
            'subject' => 'Kolaborasi',
            'message' => 'Saya ingin berdiskusi tentang proyek web.',
        ]);

        (new SendContactNotification($message))->failed(new \RuntimeException('Simulasi gagal'));

        Log::shouldHaveReceived('error')->withArgs(
            fn (string $text, array $context) => $context['contact_message_id'] === $message->id
                && $context['error'] === 'Simulasi gagal',
        )->once();
    }

    public function test_dashboard_counts_pending_and_failed_jobs(): void
    {
        config(['queue.default' => 'database']);

        $now = now()->timestamp;

        DB::table('jobs')->insert([
            'queue' => 'default',
            'payload' => '{}',
            'attempts' => 0,
            'reserved_at' => null,
            'available_at' => $now,
            'created_at' => $now,
        ]);

        DB::table('failed_jobs')->insert([
            'uuid' => (string) str()->uuid(),
            'connection' => 'database',
            'queue' => 'default',
            'payload' => '{}',
            'exception' => 'RuntimeException: Simulasi gagal',
            'failed_at' => now(),
        ]);

        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('pendingJobs', 1)
            ->assertViewHas('failedJobs', 1)
            ->assertSee('Pending jobs')
            ->assertSee('Failed jobs');
    }
}
