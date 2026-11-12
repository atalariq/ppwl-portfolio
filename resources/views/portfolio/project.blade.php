@extends('layouts.app')

@section('title', $project->title.' — Atalariq Portfolio')
@section('meta_description', $project->summary())

@section('content')
    <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 text-sm text-muted hover:text-text">
        <x-ui.icon name="arrow-left" class="h-4 w-4" /> Back to projects
    </a>

    <div class="mt-5 aspect-[16/8] overflow-hidden rounded-lg border border-border bg-slate-100">
        <img src="{{ $project->thumbnailUrl() }}" alt="{{ $project->title }}" class="h-full w-full object-cover">
    </div>

    <div class="mt-8 grid gap-10 lg:grid-cols-[1.5fr_0.5fr]">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                @if ($project->category)
                    <x-ui.badge>{{ $project->category->name }}</x-ui.badge>
                @endif
                @if ($project->is_featured)
                    <x-ui.badge variant="warning">
                        <x-ui.icon name="star" class="mr-1 h-3 w-3" /> Featured
                    </x-ui.badge>
                @endif
                @if ($project->status !== 'published')
                    <x-ui.badge variant="warning">Draft preview</x-ui.badge>
                @endif
                @if ($project->projectDateLabel())
                    <span class="inline-flex items-center gap-1 text-xs text-muted">
                        <x-ui.icon name="calendar" class="h-3.5 w-3.5" /> {{ $project->projectDateLabel() }}
                    </span>
                @endif
            </div>

            <h1 class="mt-4 text-3xl font-semibold tracking-tight sm:text-4xl">{{ $project->title }}</h1>
            <p class="mt-3 max-w-3xl text-lg leading-relaxed text-muted">{{ $project->summary() }}</p>

            <div class="mt-8 space-y-5 text-base leading-relaxed">
                @foreach (preg_split('/\r\n\r\n|\n\n/', (string) $project->description) as $paragraph)
                    @if (trim($paragraph) !== '')
                        <p>{{ trim($paragraph) }}</p>
                    @endif
                @endforeach
            </div>

            @if ($project->technologies->isNotEmpty())
                <section class="mt-10 border-t border-border pt-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wider text-muted">Technologies</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach ($project->technologies as $technology)
                            <x-ui.badge variant="muted">{{ $technology->name }}</x-ui.badge>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>

        <aside class="space-y-4">
            <x-ui.card title="Project details">
                <dl class="space-y-4 text-sm">
                    @if ($project->project_date)
                        <div>
                            <dt class="text-muted">Project date</dt>
                            <dd class="mt-1 font-medium">{{ $project->project_date->format('F Y') }}</dd>
                        </div>
                    @endif
                    @if ($project->category)
                        <div>
                            <dt class="text-muted">Category</dt>
                            <dd class="mt-1 font-medium">{{ $project->category->name }}</dd>
                        </div>
                    @endif
                    @if ($project->repositoryLabel())
                        <div>
                            <dt class="text-muted">Repository</dt>
                            <dd class="mt-1">
                                <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-medium">
                                    {{ $project->repositoryLabel() }}
                                    <x-ui.icon name="arrow-up-right" class="h-3.5 w-3.5" />
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>

                <div class="mt-5 flex flex-col gap-2">
                    @if ($project->project_url)
                        <x-ui.button :href="$project->project_url" target="_blank" rel="noopener noreferrer">
                            <x-ui.icon name="external-link" class="h-4 w-4" /> Visit project
                        </x-ui.button>
                    @endif
                    @if ($project->repository_url)
                        <x-ui.button :href="$project->repository_url" target="_blank" rel="noopener noreferrer" variant="secondary">
                            <x-ui.icon name="github" class="h-4 w-4" /> View repository
                        </x-ui.button>
                    @endif
                </div>
            </x-ui.card>

            @auth
                @if (auth()->user()->isAdmin())
                    <x-ui.card title="Admin">
                        <div class="flex flex-wrap gap-2">
                            <x-ui.button :href="route('admin.projects.edit', $project)" variant="secondary" size="sm">
                                <x-ui.icon name="pencil" class="h-4 w-4" /> Edit
                            </x-ui.button>
                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" data-confirm="Delete “{{ $project->title }}”? This also removes its gallery images." data-confirm-title="Delete project">
                                @csrf
                                @method('DELETE')
                                <x-ui.button type="submit" variant="danger" size="sm">
                                    <x-ui.icon name="trash-2" class="h-4 w-4" /> Delete
                                </x-ui.button>
                            </form>
                        </div>
                    </x-ui.card>
                @endif
            @endauth
        </aside>
    </div>

    @if ($project->images->isNotEmpty())
        <section class="mt-14 border-t border-border pt-10">
            <h2 class="text-2xl font-semibold tracking-tight">Gallery</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                @foreach ($project->images as $image)
                    <figure class="card overflow-hidden">
                        <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: $project->title }}" class="h-64 w-full object-cover">
                        @if ($image->caption)
                            <figcaption class="border-t border-border px-4 py-3 text-sm text-muted">{{ $image->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </section>
    @endif
@endsection
