@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <form action="{{ route('post.projects.update', $project) }}" method="POST" class="max-w-2xl space-y-4 rounded-lg bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div>
            <label for="title" class="block text-sm font-medium">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" class="mt-1 w-full rounded border px-3 py-2">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="repo" class="block text-sm font-medium">Repo</label>
            <input type="text" name="repo" id="repo" value="{{ old('repo', $project->repo) }}" class="mt-1 w-full rounded border px-3 py-2">
            @error('repo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium">Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 w-full rounded border px-3 py-2">{{ old('description', $project->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Save</button>
            <a href="{{ route('post.projects.index') }}" class="text-sm text-gray-600 underline">Batal</a>
        </div>
    </form>
@endsection
