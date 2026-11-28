@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-ui.stat label="Projects" :value="$totalProjects" />
        <x-ui.stat label="Published" :value="$publishedProjects" tone="text-success" />
        <x-ui.stat label="Draft" :value="$draftProjects" tone="text-warning" />
        <x-ui.stat label="Skills" :value="$totalSkills" tone="text-brand-700" />
        <x-ui.stat label="Messages" :value="$totalMessages" tone="text-brand-700" />
        <x-ui.stat label="Unread messages" :value="$unreadMessages" tone="text-warning" />
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <x-ui.stat label="Categories" :value="$totalCategories" tone="text-brand-700" />
        <x-ui.stat label="Technologies" :value="$totalTechnologies" tone="text-brand-700" />
        <x-ui.stat label="Gallery images" :value="$totalGalleryImages" tone="text-brand-700" />
        <x-ui.stat label="Pending jobs" :value="$pendingJobs" tone="text-brand-700" />
        <x-ui.stat label="Failed jobs" :value="$failedJobs" :tone="$failedJobs > 0 ? 'text-danger' : 'text-brand-700'" />
    </div>

    <x-ui.card class="mt-8" title="Portfolio cache" description="Status key cache yang dipakai halaman publik. Tombol membersihkan kelima key sekaligus.">
        <x-slot:actions>
            <form method="POST" action="{{ route('admin.cache.clear') }}">
                @csrf
                <x-ui.button type="submit" variant="secondary" size="sm">Bersihkan cache</x-ui.button>
            </form>
        </x-slot:actions>

        <div class="grid gap-3 sm:grid-cols-2">
            @foreach ($cacheStatus as $key => $cached)
                <div class="flex items-center justify-between gap-3 rounded-lg border border-border px-3 py-2">
                    <code class="text-xs">{{ $key }}</code>
                    <x-ui.badge :variant="$cached ? 'success' : 'warning'">
                        {{ $cached ? 'Tersimpan' : 'Kosong' }}
                    </x-ui.badge>
                </div>
            @endforeach
        </div>
    </x-ui.card>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <x-ui.card title="Skills by category" description="A count of capabilities, without subjective scores.">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Skills</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skillsByCategory as $group)
                        <tr>
                            <td>{{ $group->category }}</td>
                            <td class="text-right font-medium">{{ $group->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted">No skills yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.card>

        <x-ui.card title="Projects per category" description="Calculated with withCount('projects').">
            <table class="table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th class="text-right">Projects</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td class="text-right font-medium">{{ $category->projects_count }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="text-muted">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </x-ui.card>
    </div>

    <x-ui.card class="mt-8" title="Recent projects" description="The five projects updated most recently.">
        <x-slot:actions>
            <x-ui.button :href="route('admin.projects.index')" variant="secondary" size="sm">
                <x-ui.icon name="folder" class="h-4 w-4" /> Manage projects
            </x-ui.button>
        </x-slot:actions>

        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestProjects as $project)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $project->thumbnailUrl() }}" alt="" class="h-9 w-12 rounded object-cover">
                                    <span class="font-medium">{{ $project->title }}</span>
                                </div>
                            </td>
                            <td class="text-muted">{{ $project->category?->name ?? 'Uncategorized' }}</td>
                            <td>
                                <x-ui.badge :variant="$project->status === 'published' ? 'success' : 'warning'">
                                    {{ ucfirst($project->status) }}
                                </x-ui.badge>
                            </td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.icon-button icon="eye" label="Preview {{ $project->title }}" :href="route('projects.show', $project)" target="_blank" rel="noopener noreferrer" />
                                    <x-ui.icon-button icon="pencil" label="Edit {{ $project->title }}" :href="route('admin.projects.edit', $project)" />
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">No projects yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
@endsection
