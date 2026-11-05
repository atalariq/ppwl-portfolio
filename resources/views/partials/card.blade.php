<div class="overflow-hidden rounded border bg-white">
    @if ($project->thumbnail)
        <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="{{ $project->title }}" class="h-40 w-full object-cover">
    @endif

    <div class="p-4">
        <div class="flex flex-wrap items-center gap-2">
            @if ($project->category)
                <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $project->category->name }}</span>
            @endif
            @if ($project->status !== 'published')
                <span class="rounded bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Draft</span>
            @endif
        </div>

        <h2 class="mt-2 font-semibold"><a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></h2>
        <p class="text-sm text-gray-500"><a href="https://github.com/{{ $project->repo }}" class="hover:underline" target="_blank" rel="noopener">{{ $project->repo }}</a></p>
        <p class="mt-2 text-sm">{{ $project->description }}</p>
    </div>
</div>
