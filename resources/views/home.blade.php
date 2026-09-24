@extends('layouts.app')

@section('title', 'Home — Portfolio')

@section('content')
    <h1 class="text-3xl font-bold">{{ $name }}</h1>
    <p class="mt-2 text-lg text-gray-600">{{ $role }}</p>
    <p class="mt-4">{{ $summary }}</p>

    <div class="mt-6 flex gap-3">
        <a href="{{ route('about') }}" class="rounded bg-gray-900 px-4 py-2 text-sm text-white">About Me</a>
        <a href="{{ route('projects.index') }}" class="rounded border px-4 py-2 text-sm">View Projects</a>
    </div>

    <ul class="mt-6 space-y-1 text-sm text-gray-600">
        <li>Sleman, Yogyakarta</li>
        <li><a href="https://atalariq.github.io">atalariq.dev</a> · <a href="https://github.com/atalariq">github.com/atalariq</a> · <a href="https://linkedin.com/in/atalariq">linkedin.com/in/atalariq</a></li>
    </ul>
@endsection

@push('scripts')
    <script>console.log('home loaded');</script>
@endpush
