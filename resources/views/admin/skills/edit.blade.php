@extends('layouts.admin')

@section('title', 'Edit Skill')

@section('content')
    <x-ui.page-header title="Edit skill" :description="$skill->name" />

    <form action="{{ route('admin.skills.update', $skill) }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf
        @method('PUT')
        <div class="card card-body space-y-5">
            <x-ui.field label="Skill name" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name', $skill->name) }}" required>
            </x-ui.field>
            <x-ui.field label="Category" name="category" :error="$errors->first('category')" hint="Languages, Frameworks, Data, Tools, or Platforms.">
                <select id="category" name="category" class="select">
                    @foreach (['Languages', 'Frameworks', 'Data', 'Tools', 'Platforms'] as $category)
                        <option value="{{ $category }}" @selected(old('category', $skill->category) === $category)>{{ $category }}</option>
                    @endforeach
                </select>
            </x-ui.field>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" /> Save changes
            </x-ui.button>
            <x-ui.button :href="route('admin.skills.index')" variant="ghost">Cancel</x-ui.button>
        </div>
    </form>
@endsection
