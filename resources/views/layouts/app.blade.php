<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css'])
</head>
<body class="flex min-h-full flex-col">
    <header class="border-b border-border bg-white">
        <div class="container-page flex items-center justify-between gap-4 py-4">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-tight text-text">Atalariq's Portfolio</a>

            <nav class="hidden items-center gap-1 md:flex" aria-label="Utama">
                <x-ui.nav-link :href="route('home')" :active="request()->routeIs('home')">Home</x-ui.nav-link>
                <x-ui.nav-link :href="route('about')" :active="request()->routeIs('about')">About</x-ui.nav-link>
                <x-ui.nav-link :href="route('experience')" :active="request()->routeIs('experience')">Experience</x-ui.nav-link>
                <x-ui.nav-link :href="route('education')" :active="request()->routeIs('education')">Education</x-ui.nav-link>
                <x-ui.nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Projects</x-ui.nav-link>
                @auth
                    <x-ui.nav-link :href="route('post.dashboard')" :active="request()->routeIs('post.*')">{{ auth()->user()->name }}</x-ui.nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-sm">Logout</button>
                    </form>
                @else
                    <x-ui.nav-link :href="route('login')" :active="request()->routeIs('login')">Login</x-ui.nav-link>
                @endauth
            </nav>

            <details class="relative md:hidden">
                <summary class="btn btn-secondary btn-sm cursor-pointer list-none">Menu</summary>
                <div class="absolute right-0 z-20 mt-2 w-56 rounded-lg border border-border bg-white p-2 shadow-lg">
                    <x-ui.nav-link :href="route('home')">Home</x-ui.nav-link>
                    <x-ui.nav-link :href="route('about')">About</x-ui.nav-link>
                    <x-ui.nav-link :href="route('experience')">Experience</x-ui.nav-link>
                    <x-ui.nav-link :href="route('education')">Education</x-ui.nav-link>
                    <x-ui.nav-link :href="route('projects.index')">Projects</x-ui.nav-link>
                    @auth
                        <x-ui.nav-link :href="route('post.dashboard')">Dashboard</x-ui.nav-link>
                        <form method="POST" action="{{ route('logout') }}" class="px-1 pt-1">
                            @csrf
                            <button type="submit" class="btn btn-ghost btn-sm w-full justify-start">Logout</button>
                        </form>
                    @else
                        <x-ui.nav-link :href="route('login')">Login</x-ui.nav-link>
                    @endauth
                </div>
            </details>
        </div>
    </header>

    <main class="container-page grow py-8">
        @if (session('success'))
            <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert>
        @endif
        @if (session('status'))
            <x-ui.alert variant="info" class="mb-4">{{ session('status') }}</x-ui.alert>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-border bg-white">
        <div class="container-page py-6 text-xs text-muted">
            &copy; {{ date('Y') }} Atalariq Barra Hadinugraha. Portfolio praktikum PPWL.
        </div>
    </footer>
</body>
</html>
