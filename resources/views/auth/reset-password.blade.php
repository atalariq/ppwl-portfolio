@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
    <h1 class="text-xl font-semibold">Reset password</h1>
    <p class="mt-1 text-sm text-muted">Buat password baru untuk akun Anda.</p>

    <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <x-ui.field label="Email" name="email" :error="$errors->first('email')">
            <input id="email" class="input" type="email" name="email" value="{{ old('email', $request->email) }}" required autocomplete="username">
        </x-ui.field>

        <x-ui.field label="Password" name="password" :error="$errors->first('password')">
            <input id="password" class="input" type="password" name="password" required autocomplete="new-password">
        </x-ui.field>

        <x-ui.field label="Konfirmasi password" name="password_confirmation" :error="$errors->first('password_confirmation')">
            <input id="password_confirmation" class="input" type="password" name="password_confirmation" required autocomplete="new-password">
        </x-ui.field>

        <x-ui.button type="submit">Simpan password</x-ui.button>
    </form>
@endsection
