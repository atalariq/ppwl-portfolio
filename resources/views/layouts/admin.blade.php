<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — Portfolio')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <aside
            class="fixed inset-y-0 left-0 z-30 w-64 -translate-x-full bg-gray-900 text-gray-100 transition-transform lg:static lg:translate-x-0"
            :class="{ 'translate-x-0': sidebarOpen }"
        >
            <div class="flex h-16 items-center border-b border-gray-800 px-6">
                <a href="{{ route('home') }}" class="text-sm font-semibold tracking-wide">Atalariq's Portfolio</a>
            </div>

            <nav class="space-y-1 px-3 py-4 text-sm">
                @if (Route::has('post.dashboard'))
                    <a href="{{ route('post.dashboard') }}"
                       class="block rounded px-3 py-2 {{ request()->routeIs('post.dashboard') ? 'bg-gray-800 font-semibold text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Dashboard
                    </a>
                @endif

                <a href="{{ route('post.projects.index') }}"
                   class="block rounded px-3 py-2 {{ request()->routeIs('post.projects.*') ? 'bg-gray-800 font-semibold text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    Projects
                </a>

                @if (Route::has('post.categories.index'))
                    <a href="{{ route('post.categories.index') }}"
                       class="block rounded px-3 py-2 {{ request()->routeIs('post.categories.*') ? 'bg-gray-800 font-semibold text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Categories
                    </a>
                @endif

                @if (Route::has('post.technologies.index'))
                    <a href="{{ route('post.technologies.index') }}"
                       class="block rounded px-3 py-2 {{ request()->routeIs('post.technologies.*') ? 'bg-gray-800 font-semibold text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Technologies
                    </a>
                @endif

                @if (Route::has('post.skills.index'))
                    <a href="{{ route('post.skills.index') }}"
                       class="block rounded px-3 py-2 {{ request()->routeIs('post.skills.*') ? 'bg-gray-800 font-semibold text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Skills
                    </a>
                @endif

                <a href="{{ route('post.profile.edit') }}"
                   class="block rounded px-3 py-2 {{ request()->routeIs('post.profile.*') ? 'bg-gray-800 font-semibold text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    Profile
                </a>

                <div class="my-3 border-t border-gray-800"></div>

                <a href="{{ route('home') }}" class="block rounded px-3 py-2 text-gray-300 hover:bg-gray-800 hover:text-white">
                    View site
                </a>
            </nav>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="flex h-16 items-center justify-between border-b bg-white px-4 shadow-sm sm:px-6">
                <div class="flex items-center gap-3">
                    <button type="button" class="rounded border px-2 py-1 text-sm lg:hidden" @click="sidebarOpen = ! sidebarOpen">
                        Menu
                    </button>
                    <div>
                        <p class="text-xs uppercase tracking-wide text-gray-500">Admin</p>
                        <h1 class="text-lg font-semibold">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-900 text-sm font-semibold text-white">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:underline">Logout</button>
                    </form>
                </div>
            </header>

            <main class="mx-auto w-full max-w-6xl grow px-4 py-8 sm:px-6">
                @if (session('success'))
                    <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-2 text-sm text-green-800">{{ session('success') }}</div>
                @endif

                @if (session('status'))
                    <div class="mb-4 rounded border border-blue-300 bg-blue-50 px-4 py-2 text-sm text-blue-800">{{ session('status') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
