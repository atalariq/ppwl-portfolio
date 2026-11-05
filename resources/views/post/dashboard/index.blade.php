@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-ui.stat label="Projects" :value="$totalProjects" />
        <x-ui.stat label="Published" :value="$publishedProjects" tone="text-success" />
        <x-ui.stat label="Draft" :value="$draftProjects" tone="text-warning" />
        <x-ui.stat label="Skills" :value="$totalSkills" tone="text-brand-700" />
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <x-ui.stat label="Categories" :value="$totalCategories" tone="text-brand-700" />
        <x-ui.stat label="Technologies" :value="$totalTechnologies" tone="text-brand-700" />
        <x-ui.stat label="Gallery images" :value="$totalGalleryImages" tone="text-brand-700" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Skill Teratas" description="Lima skill dengan level tertinggi.">
            <ul class="space-y-4">
                @forelse ($topSkills as $skill)
                    <li>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium">{{ $skill->name }}</span>
                            <span class="text-muted">{{ $skill->level }}</span>
                        </div>
                        <div class="mt-2 h-2 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full bg-brand-500" style="width: {{ $skill->level }}%"></div>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-muted">Belum ada skill.</li>
                @endforelse
            </ul>
        </x-ui.card>

        <x-ui.card title="Project per Kategori" description="Dihitung dengan withCount('projects').">
            <table class="table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th class="text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="text-right font-medium">{{ $category->projects_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted">Belum ada kategori.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.card>
    </div>

    <x-ui.card class="mt-8" title="Project Terbaru" description="Lima project yang terakhir diperbarui.">
        <x-slot:actions>
            <x-ui.button :href="route('post.projects.index')" variant="secondary" size="sm">Kelola project</x-ui.button>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestProjects as $project)
                        <tr>
                            <td class="font-medium">{{ $project->title }}</td>
                            <td class="text-muted">{{ $project->category?->name ?? 'Tanpa kategori' }}</td>
                            <td>
                                <x-ui.badge :variant="$project->status === 'published' ? 'success' : 'warning'">
                                    {{ ucfirst($project->status) }}
                                </x-ui.badge>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('post.projects.edit', $project) }}" class="text-sm font-medium">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">Belum ada project.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
