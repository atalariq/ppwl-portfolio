@extends('layouts.app')

@section('title', 'Home — Atalariq Portfolio')
@section('meta_description', $profile->summary)

@section('content')
    <x-ui.page-header eyebrow="Portfolio" :title="$profile->full_name" :description="$profile->role">
        <x-slot:actions>
            <x-ui.button :href="route('about')">About me</x-ui.button>
            <x-ui.button :href="route('projects.index')" variant="secondary">View projects</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-10 grid gap-10 lg:grid-cols-[1.4fr_0.8fr]">
        <div class="max-w-2xl space-y-4 text-base leading-relaxed">
            <p>{{ $profile->summary }}</p>
        </div>

        <dl class="grid gap-3 text-sm sm:grid-cols-2 lg:grid-cols-1">
            @if ($profile->location)
                <div class="card card-body">
                    <dt class="text-muted">Location</dt>
                    <dd class="mt-1 font-medium">{{ $profile->location }}</dd>
                </div>
            @endif
            @if ($profile->website_url)
                <div class="card card-body">
                    <dt class="text-muted">Website</dt>
                    <dd class="mt-1 font-medium">
                        <a href="{{ $profile->website_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1">
                            {{ parse_url($profile->website_url, PHP_URL_HOST) }}
                            <x-ui.icon name="arrow-up-right" class="h-3.5 w-3.5" />
                        </a>
                    </dd>
                </div>
            @endif
            @if ($profile->github_url)
                <div class="card card-body">
                    <dt class="text-muted">GitHub</dt>
                    <dd class="mt-1 font-medium">
                        <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1">
                            {{ parse_url($profile->github_url, PHP_URL_HOST).parse_url($profile->github_url, PHP_URL_PATH) }}
                            <x-ui.icon name="arrow-up-right" class="h-3.5 w-3.5" />
                        </a>
                    </dd>
                </div>
            @endif
        </dl>
    </div>

    @if ($featuredProjects->isNotEmpty())
        <section class="mt-12 border-t border-border pt-10">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-sm font-medium uppercase tracking-wider text-brand-600">Selected work</p>
                    <h2 class="mt-2 text-2xl font-semibold tracking-tight">Featured projects</h2>
                </div>
                <x-ui.button :href="route('projects.index')" variant="ghost" size="sm">All projects</x-ui.button>
            </div>

            <div data-carousel class="relative mt-6">
                <div data-carousel-track class="carousel-track flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-smooth pb-2">
                    @foreach ($featuredProjects as $project)
                        <div class="w-[85%] shrink-0 snap-start sm:w-[calc(50%-0.625rem)] lg:w-[calc(33.333%-0.834rem)]">
                            <x-project-card :project="$project" />
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 flex items-center justify-between gap-3">
                    <div data-carousel-dots class="flex gap-1.5" aria-label="Featured project slides"></div>
                    <div class="flex gap-2">
                        <button type="button" data-carousel-prev class="icon-btn border border-border bg-white" aria-label="Previous featured projects">
                            <x-ui.icon name="chevron-left" />
                        </button>
                        <button type="button" data-carousel-next class="icon-btn border border-border bg-white" aria-label="Next featured projects">
                            <x-ui.icon name="chevron-right" />
                        </button>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
