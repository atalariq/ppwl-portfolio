@extends('layouts.admin')

@section('title', 'Skills')

@section('content')
    <x-ui.page-header title="Skills" description="Skill dipakai pada card Skill Teratas di dashboard.">
        <x-slot:actions>
            <x-ui.button :href="route('post.skills.create')">Tambah skill</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-6 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead><tr><th>Nama</th><th>Level</th><th class="text-right">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($skills as $skill)
                        <tr>
                            <td class="font-medium">{{ $skill->name }}</td>
                            <td>{{ $skill->level }}</td>
                            <td class="text-right">
                                <a href="{{ route('post.skills.edit', $skill) }}" class="text-sm">Edit</a>
                                <form action="{{ route('post.skills.destroy', $skill) }}" method="POST" class="inline" onsubmit="return confirm('Hapus skill ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="ml-3 text-sm text-danger hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">Belum ada skill.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-4">{{ $skills->links() }}</div>
@endsection
