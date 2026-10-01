@extends('layouts.app')

@section('title', $project->title . ' — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">{{ $project->title }}</h1>
    <p class="mt-2 text-sm text-gray-500">{{ $project->repo }}</p>
    <p class="mt-4">{{ $project->description }}</p>

    <div class="mt-6 flex items-center gap-4">
        <a href="{{ route('projects.index') }}" class="text-sm font-medium text-blue-600">Back to Projects</a>
        <a href="{{ route('projects.edit', $project) }}" class="text-sm font-medium text-blue-600">Edit</a>
        <form action="{{ route('projects.destroy', $project) }}" method="POST" data-confirm="Delete this project?">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-sm font-medium text-red-600">Delete</button>
        </form>
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
