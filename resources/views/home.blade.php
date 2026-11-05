@extends('layouts.app')

@section('title', 'Home — Portfolio')

@section('content')
    <section class="py-8 sm:py-12">
        <p class="text-sm font-medium uppercase tracking-wider text-brand-600">Portfolio</p>
        <h1 class="mt-3 text-4xl font-semibold tracking-tight">{{ $name }}</h1>
        <p class="mt-2 text-lg text-muted">{{ $role }}</p>
        <p class="mt-6 max-w-2xl text-base leading-relaxed">{{ $summary }}</p>

        <div class="mt-8 flex flex-wrap gap-3">
            <x-ui.button :href="route('about')">About me</x-ui.button>
            <x-ui.button :href="route('projects.index')" variant="secondary">View projects</x-ui.button>
        </div>

        <dl class="mt-12 grid gap-4 text-sm sm:grid-cols-3">
            <div class="card card-body">
                <dt class="text-muted">Location</dt>
                <dd class="mt-1 font-medium">Sleman, Yogyakarta</dd>
            </div>
            <div class="card card-body">
                <dt class="text-muted">Website</dt>
                <dd class="mt-1 font-medium"><a href="https://atalariq.github.io">atalariq.dev</a></dd>
            </div>
            <div class="card card-body">
                <dt class="text-muted">GitHub</dt>
                <dd class="mt-1 font-medium"><a href="https://github.com/atalariq">github.com/atalariq</a></dd>
            </div>
        </dl>
    </section>
@endsection
