@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm text-gray-600">Kategori dipakai untuk mengelompokkan project dan menghitung jumlah project per kategori.</p>
        <a href="{{ route('post.categories.create') }}" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Tambah Kategori</a>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50 text-left text-xs uppercase tracking-wide text-gray-500">
                <tr>
                    <th class="px-4 py-3">Nama</th>
                    <th class="px-4 py-3">Slug</th>
                    <th class="px-4 py-3">Project</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($categories as $category)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $category->slug }}</td>
                        <td class="px-4 py-3">{{ $category->projects_count }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('post.categories.edit', $category) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('post.categories.destroy', $category) }}" method="POST" class="inline" data-confirm="Hapus kategori ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ml-3 text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada kategori.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>

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
