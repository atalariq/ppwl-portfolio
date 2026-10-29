@extends('layouts.app')

@section('title', 'Projects — Portfolio')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Projects</h1>
        <a href="{{ route('projects.create') }}" class="text-sm font-medium text-blue-600">Add Project</a>
    </div>

    <form method="GET" action="{{ route('projects.index') }}" class="mt-4 flex flex-wrap items-end gap-3 text-sm">
        <label class="flex flex-col gap-1">
            <span class="text-gray-600">Cari judul</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Kata kunci judul…"
                   class="rounded border border-gray-300 px-3 py-1.5">
        </label>

        <label class="flex flex-col gap-1">
            <span class="text-gray-600">Data per halaman</span>
            <select name="per_page" class="rounded border border-gray-300 px-3 py-1.5">
                @foreach ([5, 10, 25] as $size)
                    <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </label>

        <button type="submit" class="rounded bg-blue-600 px-4 py-1.5 font-medium text-white">Terapkan</button>
        @if (request()->filled('search') || request()->filled('per_page'))
            <a href="{{ route('projects.index') }}" class="text-gray-600 underline">Reset</a>
        @endif
    </form>

    <div class="mt-4 space-y-4">
        @forelse($projects as $index => $project)
            <div class="flex items-start gap-3">
                <span class="mt-1 w-6 shrink-0 text-right text-sm text-gray-500">{{ $projects->firstItem() + $index }}.</span>
                <div class="grow">
                    @include('partials.card', ['project' => $project])
                </div>
            </div>
        @empty
            <p>There is no projects yet.</p>
        @endforelse
    </div>

    <div class="mt-4 text-sm text-gray-600">
        Halaman : {{ $projects->currentPage() }} <br>
        Jumlah Data : {{ $projects->total() }} <br>
        Data Per Halaman : {{ $projects->perPage() }}
    </div>

    <div class="mt-4">
        {{ $projects->links() }}
    </div>
@endsection
