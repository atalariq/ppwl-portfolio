@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('content')
    <h1 class="text-xl font-semibold">Forgot password</h1>
    <p class="mt-1 text-sm text-muted">Enter your account email. A reset link will be sent through the configured mailer.</p>

    @if (session('status'))
        <x-ui.alert variant="success" class="mt-4">{{ session('status') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <x-ui.field label="Email" name="email" :error="$errors->first('email')">
            <input id="email" class="input" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </x-ui.field>

        <x-ui.button type="submit">Send reset link</x-ui.button>
    </form>
@endsection
