@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <form method="GET" action="{{ route('post.projects.index') }}" class="flex items-end gap-2 text-sm">
            <label class="flex flex-col gap-1">
                <span class="text-gray-600">Status</span>
                <select name="status" class="rounded border px-3 py-1.5">
                    <option value="">Semua</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </label>
            <button type="submit" class="rounded bg-gray-900 px-3 py-1.5 font-medium text-white">Filter</button>
        </form>

        <a href="{{ route('post.projects.create') }}" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">
            Add Project
        </a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Project</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Gallery</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($projects as $index => $project)
                    <tr>
                        <td class="px-4 py-3 text-gray-500">{{ $projects->firstItem() + $index }}</td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $project->title }}</p>
                            <p class="text-xs text-gray-500">{{ $project->repo }}</p>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $project->category?->name ?? 'Tanpa kategori' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded px-2 py-0.5 text-xs {{ $project->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $project->images_count ?? $project->images->count() }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('projects.show', $project) }}" class="text-gray-600 hover:underline">Lihat</a>
                            <a href="{{ route('post.projects.edit', $project) }}" class="ml-3 text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('post.projects.destroy', $project) }}" method="POST" class="inline" data-confirm="Delete this project?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada project.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>

    @push('scripts')
        <script>
            document.querySelectorAll('form[data-confirm]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!confirm(form.dataset.confirm)) {
                        event.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
