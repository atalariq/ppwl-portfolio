@extends('layouts.admin')

@section('title', 'Tambah Skill')

@section('content')
    <x-ui.page-header title="Tambah skill" description="Level berada di antara 1 sampai 100." />

    <form action="{{ route('post.skills.store') }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf
        <div class="card card-body space-y-5">
            <x-ui.field label="Nama skill" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name') }}">
            </x-ui.field>
            <x-ui.field label="Level" name="level" :error="$errors->first('level')">
                <input id="level" class="input" type="number" min="1" max="100" name="level" value="{{ old('level', 50) }}">
            </x-ui.field>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('post.skills.index')" variant="ghost">Batal</x-ui.button>
        </div>
    </form>
@endsection
