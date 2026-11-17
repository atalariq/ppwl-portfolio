@extends('layouts.app')

@section('title', 'Contact — Atalariq Portfolio')
@section('meta_description', 'Send a message to Atalariq Barra Hadinugraha.')

@section('content')
    <x-ui.page-header title="Contact" description="Send a message. The form accepts up to three messages per minute from one IP address." />

    <div class="mt-8 max-w-2xl">
        <form method="POST" action="{{ route('contact.store') }}" class="card card-body space-y-5">
            @csrf

            <x-ui.field label="Nama" name="name">
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="input" required maxlength="100">
            </x-ui.field>

            <x-ui.field label="Email" name="email">
                <input id="email" type="email" name="email" value="{{ old('email') }}" class="input" required maxlength="100">
            </x-ui.field>

            <x-ui.field label="Subjek" name="subject">
                <input id="subject" type="text" name="subject" value="{{ old('subject') }}" class="input" maxlength="200">
            </x-ui.field>

            <x-ui.field label="Pesan" name="message">
                <textarea id="message" name="message" rows="6" class="textarea" required minlength="10" maxlength="5000">{{ old('message') }}</textarea>
            </x-ui.field>

            <div class="flex items-center justify-between gap-3">
                <p class="text-sm text-muted">Pesan dikirim ke admin dan ditandai belum dibaca.</p>
                <x-ui.button type="submit">Kirim pesan</x-ui.button>
            </div>
        </form>
    </div>
@endsection
