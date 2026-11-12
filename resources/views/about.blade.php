@extends('layouts.app')

@section('title', 'About — Atalariq Portfolio')
@section('meta_description', $profile->summary)

@section('content')
    <x-ui.page-header title="About" :description="$profile->summary" />

    <div class="mt-10 grid gap-10 lg:grid-cols-[1.4fr_0.8fr]">
        <div class="space-y-6 text-base leading-relaxed">
            @foreach (preg_split('/\r\n\r\n|\n\n/', (string) $profile->bio) as $paragraph)
                @if (trim($paragraph) !== '')
                    <p>{{ trim($paragraph) }}</p>
                @endif
            @endforeach
        </div>

        <aside class="card card-body">
            <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Contact</h2>
            <div class="mt-4 space-y-3 text-sm">
                @forelse ($contacts as $contact)
                    <a href="{{ $contact->url }}" class="flex items-center gap-3 rounded-md px-2 py-2 transition hover:bg-slate-50" @if (str_starts_with($contact->url, 'http')) target="_blank" rel="noopener noreferrer" @endif>
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                            <x-ui.icon :name="$contact->icon" class="h-4 w-4" />
                        </span>
                        <span class="min-w-0">
                            <span class="block text-xs text-muted">{{ $contact->label }}</span>
                            <span class="block truncate font-medium text-text">{{ $contact->value }}</span>
                        </span>
                    </a>
                @empty
                    <p class="text-muted">Contact details will appear here.</p>
                @endforelse
            </div>

            @if ($profile->location || $profile->website_url || $profile->github_url)
                <div class="mt-6 border-t border-border pt-4 text-sm">
                    @if ($profile->location)
                        <p class="text-muted">{{ $profile->location }}</p>
                    @endif
                    <div class="mt-2 flex flex-wrap gap-3">
                        @if ($profile->website_url)
                            <a href="{{ $profile->website_url }}" target="_blank" rel="noopener noreferrer">Website</a>
                        @endif
                        @if ($profile->github_url)
                            <a href="{{ $profile->github_url }}" target="_blank" rel="noopener noreferrer">GitHub</a>
                        @endif
                        @if ($profile->linkedin_url)
                            <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener noreferrer">LinkedIn</a>
                        @endif
                    </div>
                </div>
            @endif
        </aside>
    </div>

    <section class="mt-12 border-t border-border pt-10">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="text-sm font-medium uppercase tracking-wider text-brand-600">Toolbox</p>
                <h2 class="mt-2 text-2xl font-semibold tracking-tight">Skills</h2>
                <p class="mt-2 max-w-2xl text-sm text-muted">Grouped by capability. No levels or percentages—just the tools I actually use.</p>
            </div>
            @auth
                @if (auth()->user()->isAdmin())
                    <x-ui.button :href="route('admin.skills.index')" variant="ghost" size="sm">Manage skills</x-ui.button>
                @endif
            @endauth
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            @forelse ($skillGroups as $category => $skills)
                <div class="card card-body">
                    <h3 class="text-sm font-semibold uppercase tracking-wider text-muted">{{ $category }}</h3>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($skills as $skill)
                            <x-ui.badge variant="muted">{{ $skill->name }}</x-ui.badge>
                        @endforeach
                    </div>
                </div>
            @empty
                <x-ui.empty-state class="sm:col-span-2" title="No skills yet" description="Skills will appear here once they are added in the admin area." />
            @endforelse
        </div>
    </section>
@endsection
