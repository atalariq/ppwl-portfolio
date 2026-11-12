@props(['project'])

<article class="card flex h-full flex-col overflow-hidden">
    <a href="{{ route('projects.show', $project) }}" class="block aspect-[16/10] overflow-hidden bg-slate-100">
        <img src="{{ $project->thumbnailUrl() }}" alt="{{ $project->title }}" class="h-full w-full object-cover transition duration-300 hover:scale-[1.02]">
    </a>

    <div class="card-body flex grow flex-col">
        <div class="flex flex-wrap items-center gap-2">
            @if ($project->category)
                <x-ui.badge>{{ $project->category->name }}</x-ui.badge>
            @endif
            @if ($project->is_featured)
                <x-ui.badge variant="warning">
                    <x-ui.icon name="star" class="mr-1 h-3 w-3" /> Featured
                </x-ui.badge>
            @endif
            @if ($project->projectDateLabel())
                <span class="text-xs text-muted">{{ $project->projectDateLabel() }}</span>
            @endif
        </div>

        <h2 class="mt-3 text-lg font-semibold">
            <a href="{{ route('projects.show', $project) }}" class="text-text hover:text-brand-700">{{ $project->title }}</a>
        </h2>
        <p class="mt-2 line-clamp-3 text-sm leading-relaxed text-muted">{{ $project->summary() }}</p>

        @if ($project->technologies->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach ($project->technologies->take(3) as $technology)
                    <x-ui.badge variant="muted">{{ $technology->name }}</x-ui.badge>
                @endforeach
                @if ($project->technologies->count() > 3)
                    <x-ui.badge variant="muted">+{{ $project->technologies->count() - 3 }}</x-ui.badge>
                @endif
            </div>
        @endif

        <div class="mt-auto flex items-center justify-between gap-3 pt-5 text-sm">
            @if ($project->repository_url)
                <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-w-0 items-center gap-1 text-muted hover:text-text">
                    <x-ui.icon name="github" class="h-4 w-4 shrink-0" />
                    <span class="truncate">{{ $project->repositoryLabel() }}</span>
                </a>
            @else
                <span class="text-muted">Repository private</span>
            @endif
            <a href="{{ route('projects.show', $project) }}" class="inline-flex shrink-0 items-center gap-1 font-medium">
                Details <x-ui.icon name="arrow-up-right" class="h-3.5 w-3.5" />
            </a>
        </div>
    </div>
</article>
