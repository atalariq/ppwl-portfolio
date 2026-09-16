@extends('layouts.app')

@section('title', 'Projects — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">Projects</h1>

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
