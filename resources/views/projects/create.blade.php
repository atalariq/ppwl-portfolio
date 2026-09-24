@extends('layouts.app')

@section('title', 'Add Project — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">Add Project</h1>

    <form action="{{ route('projects.store') }}" method="POST" class="mt-4 space-y-4">
        @csrf

        <div>
            <label for="title" class="block text-sm font-medium">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 w-full rounded border px-3 py-2">
            @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="repo" class="block text-sm font-medium">Repo</label>
            <input type="text" name="repo" id="repo" value="{{ old('repo') }}" class="mt-1 w-full rounded border px-3 py-2">
            @error('repo')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium">Description</label>
            <textarea name="description" id="description" rows="4" class="mt-1 w-full rounded border px-3 py-2">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-sm font-medium text-white">Save</button>
    </form>
@endsection
