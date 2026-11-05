@extends('layouts.admin')

@section('title', 'Edit Skill')

@section('content')
    <x-ui.page-header title="Edit skill" :description="$skill->name" />

    <form action="{{ route('post.skills.update', $skill) }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf @method('PUT')
        <div class="card card-body space-y-5">
            <x-ui.field label="Nama skill" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name', $skill->name) }}">
            </x-ui.field>
            <x-ui.field label="Level" name="level" :error="$errors->first('level')">
                <input id="level" class="input" type="number" min="1" max="100" name="level" value="{{ old('level', $skill->level) }}">
            </x-ui.field>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('post.skills.index')" variant="ghost">Batal</x-ui.button>
        </div>
    </form>
@endsection
