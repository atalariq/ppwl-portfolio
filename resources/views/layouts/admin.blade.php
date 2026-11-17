<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Atalariq Portfolio</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-surface-muted">
    <input id="sidebar-toggle" type="checkbox" class="peer sr-only lg:hidden">

    <aside class="fixed inset-y-0 left-0 z-30 w-64 -translate-x-full border-r border-slate-800 bg-slate-900 text-slate-100 transition-transform peer-checked:translate-x-0 lg:translate-x-0">
        <div class="flex h-16 items-center border-b border-slate-800 px-6">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-tight text-white">Atalariq's Portfolio</a>
        </div>

        <nav class="space-y-1 px-3 py-4" aria-label="Admin">
            <x-ui.sidebar-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">Dashboard</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.projects.index')" :active="request()->routeIs('admin.projects.*')">Projects</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">Categories</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.technologies.index')" :active="request()->routeIs('admin.technologies.*')">Technologies</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.skills.index')" :active="request()->routeIs('admin.skills.*')">Skills</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.about.edit')" :active="request()->routeIs('admin.about.*')">About</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.messages.index')" :active="request()->routeIs('admin.messages.*')">Messages</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('admin.profile.edit')" :active="request()->routeIs('admin.profile.*')">Profile</x-ui.sidebar-link>
            <div class="my-3 border-t border-slate-800"></div>
            <x-ui.sidebar-link :href="route('projects.index')">View site</x-ui.sidebar-link>
        </nav>
    </aside>

    <div class="lg:pl-64">
        <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-border bg-white px-4 sm:px-6">
            <div class="flex items-center gap-3">
                <label for="sidebar-toggle" class="btn btn-secondary btn-sm cursor-pointer lg:hidden">Menu</label>
                <div>
                    <p class="text-[11px] font-medium uppercase tracking-wider text-muted">Admin</p>
                    <h1 class="text-lg font-semibold">@yield('title', 'Dashboard')</h1>
                </div>
            </div>

            <details class="relative">
                <summary class="flex cursor-pointer list-none items-center gap-2 rounded-md px-2 py-1.5 text-sm font-medium text-muted transition hover:bg-slate-100 hover:text-text">
                    <x-ui.avatar :user="auth()->user()" />
                    <span class="hidden max-w-32 truncate sm:inline">{{ auth()->user()->name }}</span>
                    <x-ui.icon name="chevron-down" class="h-3.5 w-3.5" />
                </summary>
                <div class="absolute right-0 z-30 mt-2 w-56 rounded-lg border border-border bg-white p-2 shadow-lg">
                    <x-ui.nav-link :href="route('admin.dashboard')">Dashboard</x-ui.nav-link>
                    <x-ui.nav-link :href="route('admin.profile.edit')">Profile</x-ui.nav-link>
                    <x-ui.nav-link :href="route('projects.index')">View site</x-ui.nav-link>
                    <div class="my-2 border-t border-border"></div>
                    <form method="POST" action="{{ route('logout') }}" data-confirm="Log out of your account?" data-confirm-title="Log out">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm font-medium text-danger transition hover:bg-red-50">
                            <x-ui.icon name="log-out" class="h-4 w-4" /> Log out
                        </button>
                    </form>
                </div>
            </details>
        </header>

        <main class="container-admin py-8">
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
    </div>

    <x-ui.confirm-dialog />
</body>
</html>
