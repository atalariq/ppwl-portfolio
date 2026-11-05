@extends('layouts.admin')

@section('title', 'Technologies')

@section('content')
    <x-ui.page-header title="Technologies" description="Teknologi dipakai sebagai tag project dan dihitung pada dashboard.">
        <x-slot:actions>
            <x-ui.button :href="route('post.technologies.create')">Tambah teknologi</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-6 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead><tr><th>Nama</th><th>Slug</th><th>Project</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($technologies as $technology)
                        <tr>
                            <td class="font-medium">{{ $technology->name }}</td>
                            <td class="text-muted">{{ $technology->slug }}</td>
                            <td>{{ $technology->projects_count }}</td>
                            <td class="text-right">
                                <a href="{{ route('post.technologies.edit', $technology) }}" class="text-sm">Edit</a>
                                <form action="{{ route('post.technologies.destroy', $technology) }}" method="POST" class="inline" onsubmit="return confirm('Hapus teknologi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ml-3 text-sm text-danger hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada teknologi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $technologies->links() }}</div>
@endsection
