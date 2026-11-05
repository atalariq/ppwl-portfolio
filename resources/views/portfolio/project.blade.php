@extends('layouts.app')

@section('title', $project->title . ' — Portfolio')

@section('content')
    @if ($project->thumbnail)
        <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="{{ $project->title }}" class="mb-6 h-64 w-full rounded object-cover">
    @endif

    <div class="flex flex-wrap items-center gap-2">
        @if ($project->category)
            <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-600">{{ $project->category->name }}</span>
        @endif
        @if ($project->status !== 'published')
            <span class="rounded bg-amber-100 px-2 py-0.5 text-xs text-amber-700">Draft preview</span>
        @endif
    </div>

    <h1 class="mt-3 text-2xl font-bold">{{ $project->title }}</h1>
    <p class="mt-2 text-sm text-gray-500">
        <a href="https://github.com/{{ $project->repo }}" class="hover:underline" target="_blank" rel="noopener">{{ $project->repo }}</a>
    </p>
    <p class="mt-4">{{ $project->description }}</p>

    @if ($project->technologies->isNotEmpty())
        <div class="mt-4 flex flex-wrap gap-2">
            @foreach ($project->technologies as $technology)
                <span class="rounded bg-blue-50 px-2 py-0.5 text-xs text-blue-700">{{ $technology->name }}</span>
            @endforeach
        </div>
    @endif

    @if ($project->images->isNotEmpty())
        <section class="mt-8">
            <h2 class="text-xl font-semibold">Gallery</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach ($project->images as $image)
                    <figure class="overflow-hidden rounded border bg-white">
                        <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: $project->title }}" class="h-48 w-full object-cover">
                        @if ($image->caption)
                            <figcaption class="px-3 py-2 text-sm text-gray-600">{{ $image->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </section>
    @endif

    <div class="mt-6 flex items-center gap-4">
        <a href="{{ route('projects.index') }}" class="text-sm font-medium text-blue-600">Back to Projects</a>
        @auth
            @if (auth()->user()->isAdmin())
                <a href="{{ route('post.projects.edit', $project) }}" class="text-sm font-medium text-blue-600">Edit</a>
                <form action="{{ route('post.projects.destroy', $project) }}" method="POST" data-confirm="Delete this project?">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm font-medium text-red-600">Delete</button>
                </form>
            @endif
        @endauth
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('form[data-confirm]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!confirm(form.dataset.confirm)) {
                        event.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
