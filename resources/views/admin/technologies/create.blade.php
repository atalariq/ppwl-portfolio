@extends('layouts.admin')

@section('title', 'Add Technology')

@section('content')
    <x-ui.page-header title="Add technology" description="The slug is generated automatically from the name." />

    <form action="{{ route('admin.technologies.store') }}" method="POST" class="mt-6 max-w-xl space-y-5">
        @csrf
        <div class="card card-body">
            <x-ui.field label="Technology name" name="name" :error="$errors->first('name')">
                <input id="name" class="input" type="text" name="name" value="{{ old('name') }}" required>
            </x-ui.field>
        </div>
        <div class="flex gap-3">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" /> Save technology
            </x-ui.button>
            <x-ui.button :href="route('admin.technologies.index')" variant="ghost">Cancel</x-ui.button>
        </div>
    </form>
@endsection
