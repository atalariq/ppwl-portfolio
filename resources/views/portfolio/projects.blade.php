@extends('layouts.app')

@section('title', 'Projects — Atalariq Portfolio')
@section('meta_description', 'Selected applications, APIs, mobile apps, design systems, and tools by Atalariq Barra Hadinugraha.')

@section('content')
    <x-ui.page-header title="Projects" description="Applications, APIs, mobile apps, design systems, and tools I have built or contributed to.">
        <x-slot:actions>
            @auth
                @if (auth()->user()->isAdmin())
                    <x-ui.button :href="route('admin.projects.index')" variant="secondary">Manage projects</x-ui.button>
                @endif
            @endauth
        </x-slot:actions>
    </x-ui.page-header>

    <form method="GET" action="{{ route('projects.index') }}" class="card card-body mt-10">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-12">
            <x-ui.field label="Search" name="search" class="md:col-span-2 xl:col-span-3">
                <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Title or description…" class="input">
            </x-ui.field>

            <x-ui.field label="Category" name="category" class="xl:col-span-2">
                <select id="category" name="category" class="select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>

            <x-ui.field label="Technology" name="technology" class="md:col-span-2 xl:col-span-3">
                <select id="technology" name="technology" class="select">
                    <option value="">All technologies</option>
                    @foreach ($technologies as $technology)
                        <option value="{{ $technology->slug }}" @selected(request('technology') === $technology->slug)>{{ $technology->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>

            <x-ui.field label="Sort" name="sort" class="xl:col-span-2">
                <select id="sort" name="sort" class="select">
                    <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                    <option value="oldest" @selected(request('sort') === 'oldest')>Oldest</option>
                    <option value="title" @selected(request('sort') === 'title')>Title A–Z</option>
                    <option value="project_date" @selected(request('sort') === 'project_date')>Project date</option>
                </select>
            </x-ui.field>
            <x-ui.field label="Per page" name="per_page" class="xl:col-span-2">
                <select id="per_page" name="per_page" class="select">
                    @foreach ([9, 18, 27] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 9) === $size)>{{ $size }}</option>
                    @endforeach
                </select>
            </x-ui.field>
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <p class="text-sm text-muted">
                {{ $projects->total() }} {{ \Illuminate\Support\Str::plural('project', $projects->total()) }} found
                @if (request()->filled('search') || request()->filled('category') || request()->filled('technology'))
                    · filters active
                @endif
            </p>
            <div class="flex items-center gap-2">
                @if (request()->filled('search') || request()->filled('category') || request()->filled('technology') || request()->filled('sort') || request()->filled('per_page'))
                    <a href="{{ route('projects.index') }}" class="btn btn-ghost btn-sm">
                        <x-ui.icon name="rotate-ccw" class="h-4 w-4" /> Reset
                    </a>
                @endif
                <x-ui.button type="submit" size="sm">
                    <x-ui.icon name="search" class="h-4 w-4" /> Apply
                </x-ui.button>
            </div>
        </div>
    </form>

    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($projects as $project)
            <x-project-card :project="$project" />
        @empty
            <x-ui.empty-state class="sm:col-span-2 lg:col-span-3" title="No projects found" description="Try a different keyword or reset the filters." />
        @endforelse
    </div>

    <div class="mt-8 flex flex-wrap items-center justify-between gap-3 text-sm text-muted">
        <p>Page {{ $projects->currentPage() }} · {{ $projects->perPage() }} per page</p>
        {{ $projects->links() }}
    </div>
@endsection
