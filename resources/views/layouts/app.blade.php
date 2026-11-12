<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Portfolio of Atalariq Barra Hadinugraha, software engineering student at UGM.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col">
    <header class="sticky top-0 z-40 border-b border-border bg-white/95 backdrop-blur">
        <div class="container-page flex items-center justify-between gap-4 py-4">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-tight text-text">Atalariq's Portfolio</a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Main">
                <x-ui.nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-ui.nav-link>
                <x-ui.nav-link :href="route('about')" :active="request()->routeIs('about')">About</x-ui.nav-link>
                <x-ui.nav-link :href="route('experience')" :active="request()->routeIs('experience')">Experience</x-ui.nav-link>
                <x-ui.nav-link :href="route('education')" :active="request()->routeIs('education')">Education</x-ui.nav-link>
                <x-ui.nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Projects</x-ui.nav-link>

                @auth
                    <details class="relative ml-1">
                        <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium text-muted transition hover:bg-slate-100 hover:text-text">
                            <x-ui.avatar :user="auth()->user()" />
                            <span class="max-w-32 truncate">{{ auth()->user()->name }}</span>
                            <x-ui.icon name="chevron-down" class="h-3.5 w-3.5" />
                        </summary>
                        <div class="absolute right-0 z-30 mt-2 w-56 rounded-lg border border-border bg-white p-2 shadow-lg">
                            @if (auth()->user()->isAdmin())
                                <x-ui.nav-link :href="route('admin.dashboard')">Dashboard</x-ui.nav-link>
                                <x-ui.nav-link :href="route('admin.profile.edit')">Profile</x-ui.nav-link>
                            @endif
                            <x-ui.nav-link :href="route('projects.index')">View projects</x-ui.nav-link>
                            <div class="my-2 border-t border-border"></div>
                            <form method="POST" action="{{ route('logout') }}" data-confirm="Log out of your account?" data-confirm-title="Log out">
                                @csrf
                                <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm font-medium text-danger transition hover:bg-red-50">
                                    <x-ui.icon name="log-out" class="h-4 w-4" /> Log out
                                </button>
                            </form>
                        </div>
                    </details>
                @else
                    <x-ui.nav-link :href="route('login')" :active="request()->routeIs('login')">Login</x-ui.nav-link>
                @endauth
            </nav>

            <details class="relative md:hidden">
                <summary class="btn btn-secondary btn-sm cursor-pointer list-none">Menu</summary>
                <div class="absolute right-0 z-30 mt-2 w-60 rounded-lg border border-border bg-white p-2 shadow-lg">
                    <x-ui.nav-link :href="route('home')">Home</x-ui.nav-link>
                    <x-ui.nav-link :href="route('about')">About</x-ui.nav-link>
                    <x-ui.nav-link :href="route('experience')">Experience</x-ui.nav-link>
                    <x-ui.nav-link :href="route('education')">Education</x-ui.nav-link>
                    <x-ui.nav-link :href="route('projects.index')">Projects</x-ui.nav-link>
                    @auth
                        @if (auth()->user()->isAdmin())
                            <x-ui.nav-link :href="route('admin.dashboard')">Dashboard</x-ui.nav-link>
                            <x-ui.nav-link :href="route('admin.profile.edit')">Profile</x-ui.nav-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="px-1 pt-1" data-confirm="Log out of your account?" data-confirm-title="Log out">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm font-medium text-danger transition hover:bg-red-50">
                                <x-ui.icon name="log-out" class="h-4 w-4" /> Log out
                            </button>
                        </form>
                    @else
                        <x-ui.nav-link :href="route('login')">Login</x-ui.nav-link>
                    @endauth
                </div>
            </details>
        </div>
    </header>

    <main class="container-page grow py-8 sm:py-10">
        @if (session('success'))
            <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif
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
    </main>

    <footer class="border-t border-border bg-white">
        <div class="container-page flex flex-wrap items-center justify-between gap-2 py-6 text-xs text-muted">
            <p>&copy; {{ date('Y') }} Atalariq Barra Hadinugraha. Built for the PPWL practicum.</p>
            <div class="flex gap-4">
                <a href="https://github.com/atalariq" target="_blank" rel="noopener noreferrer" class="hover:text-text">GitHub</a>
                <a href="mailto:atalariq.dev@outlook.com" class="hover:text-text">Email</a>
            </div>
        </div>
    </footer>

    <x-ui.confirm-dialog />
</body>
</html>
