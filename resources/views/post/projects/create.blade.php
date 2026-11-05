@extends('layouts.admin')

@section('title', 'Tambah Project')

@section('content')
    <form action="{{ route('post.projects.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl space-y-4 rounded-lg bg-white p-6 shadow-sm">
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

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="category_id" class="block text-sm font-medium">Kategori</label>
                <select name="category_id" id="category_id" class="mt-1 w-full rounded border px-3 py-2">
                    <option value="">Tanpa kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="block text-sm font-medium">Status</label>
                <select name="status" id="status" class="mt-1 w-full rounded border px-3 py-2">
                    <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                    <option value="published" @selected(old('status') === 'published')>Published</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="thumbnail" class="block text-sm font-medium">Thumbnail</label>
            <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="mt-1 w-full rounded border px-3 py-2">
            <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, atau WebP. Maksimal 2 MB. Ukuran minimal 400 x 300 piksel.</p>
            @error('thumbnail')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <span class="block text-sm font-medium">Teknologi</span>
            <div class="mt-2 grid gap-2 sm:grid-cols-2">
                @foreach ($technologies as $technology)
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="technologies[]" value="{{ $technology->id }}" @checked(in_array($technology->id, old('technologies', [])))>
                        {{ $technology->name }}
                    </label>
                @endforeach
            </div>
            @error('technologies')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Save</button>
            <a href="{{ route('post.projects.index') }}" class="text-sm text-gray-600 underline">Batal</a>
        </div>
    </form>
@endsection
