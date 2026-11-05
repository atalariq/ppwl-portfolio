@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <x-ui.page-header title="Projects" description="Seluruh project yang dikelola dari area admin.">
        <x-slot:actions>
            <x-ui.button :href="route('post.projects.create')">Add project</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <form method="GET" action="{{ route('post.projects.index') }}" class="mt-6 flex flex-wrap items-end gap-3 text-sm">
        <label class="flex flex-col gap-1">
            <span class="label">Status</span>
            <select name="status" class="select">
                <option value="">Semua</option>
                <option value="published" @selected(request('status') === 'published')>Published</option>
                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            </select>
        </label>
        <x-ui.button type="submit" size="sm">Filter</x-ui.button>
    </form>

    <div class="mt-6 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Gallery</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $index => $project)
                        <tr>
                            <td class="text-muted">{{ $projects->firstItem() + $index }}</td>
                            <td>
                                <p class="font-medium">{{ $project->title }}</p>
                                <p class="text-xs text-muted">{{ $project->repo }}</p>
                            </td>
                            <td class="text-muted">{{ $project->category?->name ?? 'Tanpa kategori' }}</td>
                            <td>
                                <x-ui.badge :variant="$project->status === 'published' ? 'success' : 'warning'">
                                    {{ ucfirst($project->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="text-muted">{{ $project->images_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('projects.show', $project) }}" class="text-sm">Lihat</a>
                                <a href="{{ route('post.projects.edit', $project) }}" class="ml-3 text-sm">Edit</a>
                                <form action="{{ route('post.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Delete this project?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ml-3 text-sm text-danger hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Belum ada project.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>
@endsection
