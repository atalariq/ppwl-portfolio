@extends('layouts.app')

@section('title', 'Dashboard — Portfolio')

@section('content')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <a href="{{ route('projects.index') }}" class="text-sm font-medium text-blue-600">Lihat projects</a>
    </div>

    <p class="mt-2 text-sm text-gray-600">
        Masuk sebagai <strong>{{ $user->name }}</strong> ({{ $user->email }}).
    </p>

    <div class="mt-6 grid gap-4 sm:grid-cols-3">
        <div class="rounded border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Total project</p>
            <p class="mt-1 text-2xl font-bold">{{ \App\Models\Project::count() }}</p>
        </div>
        <div class="rounded border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Project terbaru</p>
            <p class="mt-1 text-sm font-medium">{{ \App\Models\Project::latest()->value('title') ?? '—' }}</p>
        </div>
        <div class="rounded border border-gray-200 bg-white p-4">
            <p class="text-sm text-gray-600">Akses</p>
            <p class="mt-1 text-sm">Hanya pengguna login yang dapat menambah, mengubah, atau menghapus project.</p>
        </div>
    </div>

    <div class="mt-6 flex flex-wrap gap-3 text-sm">
        <a href="{{ route('projects.create') }}" class="rounded bg-blue-600 px-4 py-2 font-medium text-white">Tambah project</a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="rounded border border-red-300 px-4 py-2 font-medium text-red-700">Logout</button>
        </form>
    </div>
@endsection
