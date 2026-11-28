<?php

namespace App\Jobs;

use App\Models\ContactMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendContactNotification implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;

    public function __construct(public ContactMessage $contactMessage) {}

    public function handle(): void
    {
        sleep(3);

        Log::info('Notifikasi pesan kontak dikirim.', [
            'contact_message_id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'email' => $this->contactMessage->email,
        ]);
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Notifikasi pesan kontak gagal dikirim.', [
            'contact_message_id' => $this->contactMessage->id,
            'error' => $exception->getMessage(),
        ]);
    }
}
