@extends('layouts.app')

@section('title', $project->title . ' — Portfolio')

@section('content')
    <a href="{{ route('projects.index') }}" class="text-sm">&larr; Back to projects</a>

    @if ($project->thumbnail)
        <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="{{ $project->title }}" class="mt-4 h-64 w-full rounded-lg object-cover">
    @endif

    <div class="mt-6 flex flex-wrap items-center gap-2">
        @if ($project->category)
            <x-ui.badge>{{ $project->category->name }}</x-ui.badge>
        @endif
        @if ($project->status !== 'published')
            <x-ui.badge variant="warning">Draft preview</x-ui.badge>
        @endif
    </div>

    <h1 class="mt-3 text-3xl font-semibold tracking-tight">{{ $project->title }}</h1>
    <p class="mt-2 text-sm text-muted">{{ $project->repo }}</p>
    <p class="mt-6 max-w-3xl text-base leading-relaxed">{{ $project->description }}</p>

    @if ($project->technologies->isNotEmpty())
        <div class="mt-5 flex flex-wrap gap-2">
            @foreach ($project->technologies as $technology)
                <x-ui.badge variant="muted">{{ $technology->name }}</x-ui.badge>
            @endforeach
        </div>
    @endif

    @if ($project->images->isNotEmpty())
        <section class="mt-10">
            <h2 class="text-xl font-semibold">Gallery</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach ($project->images as $image)
                    <figure class="card overflow-hidden">
                        <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: $project->title }}" class="h-52 w-full object-cover">
                        @if ($image->caption)
                            <figcaption class="border-t border-border px-4 py-3 text-sm text-muted">{{ $image->caption }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </section>
    @endif

    @auth
        @if (auth()->user()->isAdmin())
            <div class="mt-8 flex flex-wrap gap-3 border-t border-border pt-6">
                <x-ui.button :href="route('post.projects.edit', $project)" variant="secondary">Edit project</x-ui.button>
                <form action="{{ route('post.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?')">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger">Delete</x-ui.button>
                </form>
            </div>
        @endif
    @endauth
@endsection
