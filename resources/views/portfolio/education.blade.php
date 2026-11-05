@extends('layouts.app')

@section('title', 'Education — Portfolio')

@section('content')
    <x-ui.page-header title="Education" description="Formal education and academic activities." />

    <div class="mt-8 space-y-4">
        @forelse ($educations as $education)
            <article class="card card-body">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h2 class="text-lg font-semibold">{{ $education->institution }}</h2>
                    <span class="text-sm text-muted">{{ $education->period }}</span>
                </div>
                <p class="mt-1 text-sm font-medium text-brand-700">{{ $education->program }}</p>

                @if (! empty($education->details))
                    <ul class="mt-3 list-disc space-y-1 pl-5 text-sm leading-relaxed">
                        @foreach ($education->details as $detail)
                            <li>{{ $detail }}</li>
                        @endforeach
                    </ul>
                @endif
            </article>
        @empty
            <x-ui.empty-state title="Belum ada riwayat pendidikan" description="Data pendidikan akan tampil setelah seeder dijalankan." />
        @endforelse
    </div>
@endsection
