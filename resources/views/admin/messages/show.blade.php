@extends('layouts.admin')

@section('title', 'Message')

@section('content')
    <x-ui.page-header title="Message from {{ $message->name }}" description="{{ $message->email }}">
        <x-slot:actions>
            <x-ui.button :href="route('admin.messages.index')" variant="secondary" size="sm">Back to messages</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <x-ui.card class="mt-6">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wider text-muted">Nama</dt>
                <dd class="mt-1 font-medium">{{ $message->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wider text-muted">Email</dt>
                <dd class="mt-1">{{ $message->email }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wider text-muted">Subjek</dt>
                <dd class="mt-1">{{ $message->subject ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wider text-muted">Diterima</dt>
                <dd class="mt-1">{{ $message->created_at->format('d M Y H:i') }}</dd>
            </div>
        </dl>

        <div class="mt-6 border-t border-border pt-6">
            <p class="whitespace-pre-line leading-relaxed">{{ $message->message }}</p>
        </div>
    </x-ui.card>
@endsection
