@extends('layouts.app')

@section('title', 'Education — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">Education</h1>
    <p class="mt-2 text-sm text-gray-600">Riwayat pendidikan formal dan kegiatan akademik.</p>

    @forelse ($educations as $education)
        <article class="mt-4 rounded border bg-white p-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h2 class="font-semibold">{{ $education->institution }}</h2>
                <span class="text-sm text-gray-500">{{ $education->period }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ $education->program }}</p>

            @if (! empty($education->details))
                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach ($education->details as $detail)
                        <li>{{ $detail }}</li>
                    @endforeach
                </ul>
            @endif
        </article>
    @empty
        <p class="mt-4 text-sm text-gray-500">Belum ada riwayat pendidikan.</p>
    @endforelse
@endsection
