<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Atalariq Portfolio</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-full bg-surface-muted">
    <input id="sidebar-toggle" type="checkbox" class="peer sr-only lg:hidden">

    <aside class="fixed inset-y-0 left-0 z-30 w-64 -translate-x-full border-r border-slate-800 bg-slate-900 text-slate-100 transition-transform peer-checked:translate-x-0 lg:translate-x-0">
        <div class="flex h-16 items-center border-b border-slate-800 px-6">
            <a href="{{ route('home') }}" class="text-sm font-semibold tracking-tight text-white">Atalariq's Portfolio</a>
        </div>

        <nav class="space-y-1 px-3 py-4" aria-label="Admin">
            <x-ui.sidebar-link :href="route('post.dashboard')" :active="request()->routeIs('post.dashboard')">Dashboard</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('post.projects.index')" :active="request()->routeIs('post.projects.*')">Projects</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('post.categories.index')" :active="request()->routeIs('post.categories.*')">Categories</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('post.technologies.index')" :active="request()->routeIs('post.technologies.*')">Technologies</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('post.skills.index')" :active="request()->routeIs('post.skills.*')">Skills</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('post.profile.edit')" :active="request()->routeIs('post.profile.*')">Profile</x-ui.sidebar-link>
            <x-ui.sidebar-link :href="route('post.styleguide')" :active="request()->routeIs('post.styleguide')">Styleguide</x-ui.sidebar-link>
            <div class="my-3 border-t border-slate-800"></div>
            <x-ui.sidebar-link :href="route('home')">View site</x-ui.sidebar-link>
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

            <div class="flex items-center gap-3">
                <div class="hidden text-right sm:block">
                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-muted">{{ ucfirst(auth()->user()->role) }}</p>
                </div>
                <x-ui.avatar :user="auth()->user()" />
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-ui.button variant="ghost" size="sm" type="submit">Logout</x-ui.button>
                </form>
            </div>
        </header>

        <main class="container-admin py-8">
            @if (session('success'))
                <x-ui.alert variant="success" class="mb-4">{{ session('success') }}</x-ui.alert>
            @endif
            @if (session('status'))
                <x-ui.alert variant="info" class="mb-4">{{ session('status') }}</x-ui.alert>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
