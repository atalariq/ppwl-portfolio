<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-full items-center justify-center bg-surface-muted px-4 py-12">
    <div class="w-full max-w-md">
        <div class="mb-6 text-center">
            <a href="{{ route('home') }}" class="text-lg font-semibold tracking-tight text-text">Atalariq's Portfolio</a>
            <p class="mt-1 text-sm text-muted">Portfolio management</p>
        </div>

        <div class="card">
            <div class="card-body">
                @if (session('status'))
                    <x-ui.alert variant="info" class="mb-4">{{ session('status') }}</x-ui.alert>
                @endif
                @if (session('error'))
                    <x-ui.alert variant="danger" class="mb-4">{{ session('error') }}</x-ui.alert>
                @endif
                @if ($errors->any())
                    <x-ui.alert variant="danger" class="mb-4">
                        <p class="font-medium">Please check the form and try again.</p>
                        <ul class="mt-1 list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-ui.alert>
                @endif

                @yield('content')
            </div>
        </div>

        <p class="mt-6 text-center text-xs text-muted">
            <a href="{{ route('home') }}">Back to site</a>
        </p>
    </div>
</body>
</html>
