@extends('layouts.app')

@section('title', 'Login — Portfolio')

@section('content')
    <div class="mx-auto max-w-md">
        <h1 class="text-2xl font-bold">Masuk</h1>
        <p class="mt-1 text-sm text-gray-600">Masuk untuk mengelola data project pada portofolio.</p>

        <form method="POST" action="{{ route('authenticate') }}" class="mt-6 space-y-4">
            @csrf

            <label class="block">
                <span class="text-sm font-medium">Email</span>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2">
                @error('email')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="block">
                <span class="text-sm font-medium">Password</span>
                <input type="password" name="password" required
                       class="mt-1 w-full rounded border border-gray-300 px-3 py-2">
                @error('password')
                    <span class="mt-1 block text-sm text-red-600">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex items-center gap-2 text-sm">
                <input type="checkbox" name="remember" value="1">
                <span>Ingat saya</span>
            </label>

            <button type="submit" class="w-full rounded bg-blue-600 px-4 py-2 font-medium text-white">
                Masuk
            </button>
        </form>

        <p class="mt-4 text-sm text-gray-600">
            Belum punya akun? <a href="{{ route('register') }}" class="text-blue-600 underline">Daftar di sini</a>.
        </p>
    </div>
@endsection
