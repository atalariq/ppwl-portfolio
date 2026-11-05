@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <h1 class="text-xl font-semibold">Lupa password</h1>
    <p class="mt-1 text-sm text-muted">Masukkan email akun. Tautan reset akan dikirim melalui mailer yang dikonfigurasi.</p>

    @if (session('status'))
        <x-ui.alert variant="success" class="mt-4">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <x-ui.field label="Email" name="email" :error="$errors->first('email')">
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </x-ui.field>

        <x-ui.button type="submit">Kirim tautan reset</x-ui.button>
    </form>
@endsection
