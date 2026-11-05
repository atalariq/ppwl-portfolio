@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Projects" :value="$totalProjects" />
        <x-stat-card label="Published" :value="$publishedProjects" color="text-green-600" />
        <x-stat-card label="Draft" :value="$draftProjects" color="text-amber-600" />
        <x-stat-card label="Skills" :value="$totalSkills" color="text-orange-600" />
    </div>

    <div class="mt-4 grid gap-4 sm:grid-cols-3">
        <x-stat-card label="Categories" :value="$totalCategories" color="text-purple-600" />
        <x-stat-card label="Technologies" :value="$totalTechnologies" color="text-cyan-600" />
        <x-stat-card label="Gallery Images" :value="$totalGalleryImages" color="text-rose-600" />
    </div>

    <div class="mt-8 grid gap-6 lg:grid-cols-2">
        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Skill Teratas</h2>
            <p class="mt-1 text-sm text-gray-600">Lima skill dengan level tertinggi.</p>

            <ul class="mt-4 space-y-3">
                @forelse ($topSkills as $skill)
                    <li>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-medium">{{ $skill->name }}</span>
                            <span class="text-gray-500">{{ $skill->level }}</span>
                        </div>
                        <div class="mt-1 h-2 rounded bg-gray-100">
                            <div class="h-2 rounded bg-orange-500" style="width: {{ $skill->level }}%"></div>
                        </div>
                    </li>
                @empty
                    <li class="text-sm text-gray-500">Belum ada skill.</li>
                @endforelse
            </ul>
        </section>

        <section class="rounded-lg bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold">Project per Kategori</h2>
            <p class="mt-1 text-sm text-gray-600">Dihitung dengan <code>withCount('projects')</code>.</p>

            <table class="mt-4 min-w-full divide-y divide-gray-200 text-sm">
                <thead class="text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="py-2">Kategori</th>
                        <th class="py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="py-2">{{ $category->name }}</td>
                            <td class="py-2 text-right">{{ $category->projects_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="py-3 text-gray-500">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>
    </div>

    <section class="mt-8 rounded-lg bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold">Project Terbaru</h2>
                <p class="mt-1 text-sm text-gray-600">Lima project yang terakhir diperbarui.</p>
            </div>
            <a href="{{ route('post.projects.index') }}" class="text-sm font-medium text-blue-600">Kelola project</a>
        </div>

        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="text-left text-xs uppercase tracking-wide text-gray-500">
                    <tr>
                        <th class="py-2">Project</th>
                        <th class="py-2">Kategori</th>
                        <th class="py-2">Status</th>
                        <th class="py-2 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($latestProjects as $project)
                        <tr>
                            <td class="py-2 font-medium">{{ $project->title }}</td>
                            <td class="py-2 text-gray-600">{{ $project->category?->name ?? 'Tanpa kategori' }}</td>
                            <td class="py-2">
                                <span class="rounded px-2 py-0.5 text-xs {{ $project->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </td>
                            <td class="py-2 text-right">
                                <a href="{{ route('post.projects.edit', $project) }}" class="text-blue-600">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-3 text-gray-500">Belum ada project.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
