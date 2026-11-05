@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-gray-600">Daftar seluruh project yang dikelola dari area admin.</p>
        <a href="{{ route('post.projects.create') }}" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">
            Add Project
        </a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">#</th>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Repo</th>
                    <th class="px-4 py-3">Updated</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($projects as $index => $project)
                    <tr>
                        <td class="px-4 py-3 text-gray-500">{{ $projects->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-medium">{{ $project->title }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $project->repo }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $project->updated_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('post.projects.edit', $project) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('post.projects.destroy', $project) }}" method="POST" class="inline" data-confirm="Delete this project?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada project.</td>
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
