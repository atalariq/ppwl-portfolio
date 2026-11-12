@extends('layouts.app')

@section('title', 'Experience — Atalariq Portfolio')
@section('meta_description', 'Organizational experience and technical contributions by Atalariq Barra Hadinugraha.')

@section('content')
    <x-ui.page-header title="Experience" description="Organizational experience and technical contributions." />

    <div class="mt-10 grid gap-4 sm:grid-cols-2">
        @forelse ($experiences as $experience)
            <x-ui.entry-card
                :period="$experience->period"
                :title="$experience->role"
                :subtitle="$experience->organization"
                :description="$experience->description"
            />
        @empty
            <x-ui.empty-state class="sm:col-span-2" title="No experience yet" description="Experience entries will appear here once they are added." />
        @endforelse
    </div>
@endsection
