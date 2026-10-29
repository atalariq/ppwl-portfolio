<nav class="border-b bg-white">
    <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="font-bold">Atalariq's Portfolio</a>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'font-semibold underline' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'font-semibold underline' : '' }}">About</a>
            <a href="{{ route('education') }}" class="{{ request()->routeIs('education') ? 'font-semibold underline' : '' }}">Education</a>
            <a href="{{ route('projects.index') }}" class="{{ request()->routeIs('projects.*') ? 'font-semibold underline' : '' }}">Projects</a>
            @auth
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'font-semibold underline' : '' }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="cursor-pointer underline-offset-2 hover:underline">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'font-semibold underline' : '' }}">Login</a>
            @endauth
        </div>
    </div>
</nav>
