@extends('layouts.app')

@section('title', 'Projects — Portfolio')

@section('content')
    <x-ui.page-header title="Projects" description="A selection of applications, APIs, and experiments.">
        <x-slot:actions>
            @auth
                @if (auth()->user()->isAdmin())
                    <x-ui.button :href="route('post.projects.index')" variant="secondary">Kelola project</x-ui.button>
                @endif
            @endauth
        </x-slot:actions>
    </x-ui.page-header>

    <form method="GET" action="{{ route('projects.index') }}" class="mt-6 flex flex-wrap items-end gap-3 text-sm">
        <label class="flex min-w-56 flex-col gap-1">
            <span class="label">Cari judul</span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Kata kunci judul…" class="input">
        </label>

        <label class="flex flex-col gap-1">
            <span class="label">Data per halaman</span>
            <select name="per_page" class="select">
                @foreach ([5, 10, 25] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </label>

        <x-ui.button type="submit" size="sm">Terapkan</x-ui.button>
        @if (request()->filled('search') || request()->filled('per_page'))
            <a href="{{ route('projects.index') }}" class="text-sm">Reset</a>
        @endif
    </form>

    <div class="mt-6 grid gap-4 sm:grid-cols-2">
        @forelse($projects as $project)
            <article class="card overflow-hidden">
                @if ($project->thumbnail)
                    <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="{{ $project->title }}" class="h-40 w-full object-cover">
                @endif
                <div class="card-body">
                    <div class="flex flex-wrap items-center gap-2">
                        @if ($project->category)
                            <x-ui.badge>{{ $project->category->name }}</x-ui.badge>
                        @endif
                        @if ($project->status !== 'published')
                            <x-ui.badge variant="warning">Draft</x-ui.badge>
                        @endif
                    </div>
                    <h2 class="mt-3 text-lg font-semibold">
                        <a href="{{ route('projects.show', $project) }}">{{ $project->title }}</a>
                    </h2>
                    <p class="mt-1 text-sm text-muted">{{ $project->repo }}</p>
                    <p class="mt-3 line-clamp-3 text-sm leading-relaxed">{{ $project->description }}</p>
                </div>
            </article>
        @empty
            <x-ui.empty-state class="sm:col-span-2" title="Belum ada project" description="Project yang sudah diterbitkan akan tampil di sini." />
        @endforelse
    </div>

    <div class="mt-6 flex flex-wrap items-center justify-between gap-3 text-sm text-muted">
        <p>Halaman {{ $projects->currentPage() }} · {{ $projects->total() }} project · {{ $projects->perPage() }} per halaman</p>
        {{ $projects->links() }}
    </div>
@endsection
