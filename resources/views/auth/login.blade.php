@extends('layouts.auth')

@section('title', 'Login')

@section('content')
    <h1 class="text-xl font-semibold">Masuk</h1>
    <p class="mt-1 text-sm text-muted">Gunakan akun pengelola untuk membuka area admin.</p>

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <x-ui.field label="Email" name="email" :error="$errors->first('email')">
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </x-ui.field>

        <x-ui.field label="Password" name="password" :error="$errors->first('password')">
            <input id="password" class="input" type="password" name="password" required autocomplete="current-password">
        </x-ui.field>

        <label class="flex items-center gap-2 text-sm text-muted">
            <input type="checkbox" name="remember" class="rounded border-border text-brand-600 focus:ring-brand-500">
            Remember me
        </label>

        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('password.request') }}" class="text-sm">Forgot password?</a>
            <x-ui.button type="submit">Log in</x-ui.button>
        </div>
    </form>
@endsection
