<nav class="border-b bg-white">
    <div class="mx-auto flex max-w-4xl items-center justify-between px-4 py-4">
        <a href="{{ route('home') }}" class="font-bold">Atalariq's Portfolio</a>
        <div class="flex gap-4 text-sm">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'font-semibold underline' : '' }}">Home</a>
            <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'font-semibold underline' : '' }}">About</a>
            <a href="{{ route('education') }}" class="{{ request()->routeIs('education') ? 'font-semibold underline' : '' }}">Education</a>
            <a href="{{ route('projects') }}" class="{{ request()->routeIs('projects') ? 'font-semibold underline' : '' }}">Projects</a>
        </div>
    </div>
</nav>
