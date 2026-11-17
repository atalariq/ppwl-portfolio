@extends('layouts.admin')

@section('title', 'Messages')

@section('content')
    <x-ui.page-header title="Messages" description="Pesan yang dikirim melalui form contact." />

    <div class="mt-6 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Subjek</th>
                        <th>Diterima</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($messages as $message)
                        <tr>
                            <td class="font-medium">{{ $message->name }}</td>
                            <td class="text-muted">{{ $message->email }}</td>
                            <td>{{ $message->subject ?: '—' }}</td>
                            <td class="text-muted">{{ $message->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <x-ui.badge :variant="$message->is_read ? 'success' : 'warning'">
                                    {{ $message->is_read ? 'Sudah dibaca' : 'Belum dibaca' }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.icon-button icon="eye" label="Buka pesan dari {{ $message->name }}" :href="route('admin.messages.show', $message)" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="py-10 text-center text-muted">Belum ada pesan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $messages->links() }}</div>
@endsection
