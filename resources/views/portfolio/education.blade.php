@extends('layouts.app')

@section('title', 'Education — Atalariq Portfolio')
@section('meta_description', 'Formal education and academic activities of Atalariq Barra Hadinugraha.')

@section('content')
    <x-ui.page-header title="Education" description="Formal education and academic activities." />

    <div class="mt-10 grid gap-4 sm:grid-cols-2">
        @forelse ($educations as $education)
            <x-ui.entry-card
                :period="$education->period"
                :title="$education->institution"
                :subtitle="$education->program"
                :items="$education->details ?? []"
            />
        @empty
            <x-ui.empty-state class="sm:col-span-2" title="No education history yet" description="Education entries will appear here once they are added." />
        @endforelse
    </div>
@endsection
