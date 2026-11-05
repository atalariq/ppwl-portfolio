@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <x-ui.page-header title="Categories" description="Kategori mengelompokkan project dan menjadi dasar hitungan withCount().">
        <x-slot:actions>
            <x-ui.button :href="route('post.categories.create')">Tambah kategori</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-6 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr><th>Nama</th><th>Slug</th><th>Project</th><th class="text-right">Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td class="font-medium">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->slug }}</td>
                            <td>{{ $category->projects_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('post.categories.edit', $category) }}" class="text-sm">Edit</a>
                                <form action="{{ route('post.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ml-3 text-sm text-danger hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $categories->links() }}</div>
@endsection
