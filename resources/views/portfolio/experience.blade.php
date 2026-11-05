@extends('layouts.app')

@section('title', 'Experience — Portfolio')

@section('content')
    <x-ui.page-header title="Experience" description="Organizational experience and technical contributions." />

    <div class="mt-8 space-y-4">
        @forelse ($experiences as $experience)
            <article class="card card-body">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-lg font-semibold">{{ $experience->role }}</h2>
                    <span class="text-sm text-muted">{{ $experience->period }}</span>
                </div>
                <p class="mt-1 text-sm font-medium text-brand-700">{{ $experience->organization }}</p>
                @if ($experience->description)
                    <p class="mt-3 text-sm leading-relaxed">{{ $experience->description }}</p>
                @endif
            </article>
        @empty
            <x-ui.empty-state title="Belum ada pengalaman" description="Data pengalaman akan tampil setelah seeder dijalankan." />
        @endforelse
    </div>
@endsection
