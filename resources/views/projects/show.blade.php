@extends('layouts.app')

@section('title', $project->title . ' — Portfolio')

@section('content')
    <h1 class="text-2xl font-bold">{{ $project->title }}</h1>
    <p class="mt-2 text-sm text-gray-500">{{ $project->repo }}</p>
    <p class="mt-4">{{ $project->description }}</p>

    <a href="{{ route('projects.index') }}" class="mt-6 inline-block text-sm font-medium text-blue-600">Back to Projects</a>
@endsection
