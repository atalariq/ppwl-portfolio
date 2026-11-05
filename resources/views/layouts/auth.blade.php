<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-full items-center justify-center bg-surface-muted px-4 py-12">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-text">Atalariq's Portfolio</a>
            <p class="mt-1 text-sm text-muted">Area pengelolaan portofolio</p>
        </div>

        <div class="card">
            <div class="card-body">
                @if (session('status'))
                    <x-ui.alert variant="info" class="mb-4">{{ session('status') }}</x-ui.alert>
                @endif

                @yield('content')
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-muted">
            <a href="{{ route('home') }}">Kembali ke situs</a>
        </p>
    </div>
</body>
</html>
