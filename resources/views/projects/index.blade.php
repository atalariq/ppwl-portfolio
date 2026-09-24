@extends('layouts.app')

@section('title', 'Projects — Portfolio')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Projects</h1>
        <a href="{{ route('projects.create') }}" class="text-sm font-medium text-blue-600">Add Project</a>
    </div>

    @php($total = count($projects))
    <p class="mt-2 text-sm text-gray-600">Total: {{ $total }} projects</p>

    <div class="mt-4 space-y-4">
        @forelse($projects as $project)
            @include('partials.card', ['project' => $project])
        @empty
            <p>There is no projects yet.</p>
        @endforelse
    </div>
@endsection
