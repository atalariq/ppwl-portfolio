@extends('layouts.app')

@section('title', 'Experience — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">Experience</h1>
    <p class="mt-2 text-sm text-gray-600">Pengalaman organisasi dan kontribusi teknis yang relevan.</p>

    @forelse ($experiences as $experience)
        <article class="mt-4 rounded border bg-white p-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h2 class="font-semibold">{{ $experience->role }}</h2>
                <span class="text-sm text-gray-500">{{ $experience->period }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $experience->organization }}</p>
            @if ($experience->description)
                <p class="mt-2 text-sm">{{ $experience->description }}</p>
            @endif
        </article>
    @empty
        <p class="mt-4 text-sm text-gray-500">Belum ada pengalaman yang ditambahkan.</p>
    @endforelse
@endsection
