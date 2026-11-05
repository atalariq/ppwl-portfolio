@extends('layouts.admin')

@section('title', 'Edit Teknologi')

@section('content')
    <x-ui.page-header title="Edit teknologi" :description="$technology->name" />

    <form action="{{ route('post.technologies.update', $technology) }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf @method('PUT')
        <div class="card card-body">
            <x-ui.field label="Nama teknologi" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name', $technology->name) }}">
            </x-ui.field>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('post.technologies.index')" variant="ghost">Batal</x-ui.button>
        </div>
    </form>
@endsection
