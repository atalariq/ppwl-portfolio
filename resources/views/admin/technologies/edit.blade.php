@extends('layouts.admin')

@section('title', 'Edit Technology')

@section('content')
    <x-ui.page-header title="Edit technology" :description="$technology->name" />

    <form action="{{ route('admin.technologies.update', $technology) }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf
        @method('PUT')
        <div class="card card-body">
            <x-ui.field label="Technology name" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name', $technology->name) }}" required>
            </x-ui.field>
            <p class="help mt-2">Slug: {{ $technology->slug }}</p>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" /> Save changes
            </x-ui.button>
            <x-ui.button :href="route('admin.technologies.index')" variant="ghost">Cancel</x-ui.button>
        </div>
    </form>
@endsection
