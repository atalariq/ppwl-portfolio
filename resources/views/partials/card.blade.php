<div class="rounded border bg-white p-4">
    <h2 class="font-semibold"><a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a></h2>
    <p class="text-sm text-gray-500"><a href="https://github.com/{{ $project->repo }}" class="hover:underline" target="_blank" rel="noopener">{{ $project->repo }}</a></p>
    <p class="mt-2 text-sm">{{ $project->description }}</p>
</div>
