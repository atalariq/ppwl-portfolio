@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <x-ui.page-header title="Edit kategori" :description="$category->name" />

    <form action="{{ route('post.categories.update', $category) }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf @method('PUT')
        <div class="card card-body">
            <x-ui.field label="Nama kategori" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name', $category->name) }}">
            </x-ui.field>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('post.categories.index')" variant="ghost">Batal</x-ui.button>
        </div>
    </form>
@endsection
