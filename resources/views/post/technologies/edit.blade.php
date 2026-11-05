@extends('layouts.admin')

@section('title', 'Edit Teknologi')

@section('content')
    <form action="{{ route('post.technologies.update', $technology) }}" method="POST" class="max-w-xl space-y-4 rounded-lg bg-white p-6 shadow-sm">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="block text-sm font-medium">Nama teknologi</label>
            <input type="text" name="name" id="name" value="{{ old('name', $technology->name) }}" class="mt-1 w-full rounded border px-3 py-2">
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('post.technologies.index') }}" class="text-sm text-gray-600 underline">Batal</a>
        </div>
    </form>
@endsection
