@extends('layouts.admin')

@section('title', 'Projects')

@section('content')
    <x-ui.page-header title="Projects" description="Manage every project, its media, and its public visibility.">
        <x-slot:actions>
            <x-ui.button :href="route('projects.index')" variant="secondary" size="sm">
                <x-ui.icon name="eye" class="h-4 w-4" /> View projects
            </x-ui.button>
            <x-ui.button :href="route('admin.projects.create')">
                <x-ui.icon name="plus" class="h-4 w-4" /> Add project
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <form method="GET" action="{{ route('admin.projects.index') }}" class="card card-body mt-6">
        <div class="grid gap-4 md:grid-cols-3">
            <x-ui.field label="Search" name="search">
                <input id="search" type="search" name="search" value="{{ request('search') }}" placeholder="Title or description…" class="input">
            </x-ui.field>
            <x-ui.field label="Status" name="status">
                <select id="status" name="status" class="select">
                    <option value="">All statuses</option>
                    <option value="published" @selected(request('status') === 'published')>Published</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                </select>
            </x-ui.field>
            <x-ui.field label="Category" name="category">
                <select id="category" name="category" class="select">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </x-ui.field>
        </div>
        <div class="mt-4 flex flex-wrap items-center gap-2">
            <x-ui.button type="submit" size="sm">
                <x-ui.icon name="filter" class="h-4 w-4" /> Filter
            </x-ui.button>
            @if (request()->filled('search') || request()->filled('status') || request()->filled('category'))
                <a href="{{ route('admin.projects.index') }}" class="btn btn-ghost btn-sm">
                    <x-ui.icon name="rotate-ccw" class="h-4 w-4" /> Reset
                </a>
            @endif
        </div>
    </form>

    <form id="bulk-form" method="POST" action="{{ route('admin.projects.bulk') }}" class="mt-6" data-bulk-form data-bulk-delete-message="Delete the selected projects? This also removes their gallery images.">
        @csrf
        <div class="flex flex-wrap items-end gap-3 rounded-lg border border-border bg-white p-3 shadow-sm">
            <label class="flex flex-col gap-1 text-sm">
                <span class="label">Bulk action</span>
                <select name="action" class="select min-w-48" data-bulk-action>
                    <option value="publish">Publish</option>
                    <option value="draft">Move to draft</option>
                    <option value="feature">Feature</option>
                    <option value="unfeature">Unfeature</option>
                    <option value="category">Change category</option>
                    <option value="delete">Delete</option>
                </select>
            </label>
            <label class="hidden flex-col gap-1 text-sm" data-bulk-category>
                <span class="label">Category</span>
                <select name="category_id" class="select min-w-48">
                    <option value="">Choose category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <x-ui.button type="submit" variant="secondary" size="sm">Apply</x-ui.button>
            <p class="ml-auto text-xs text-muted">Select projects with the checkboxes, then apply an action.</p>
        </div>
    </form>

    <div class="mt-4 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10">
                            <input type="checkbox" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-all aria-label="Select all projects">
                        </th>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Featured</th>
                        <th>Gallery</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td>
                                <input form="bulk-form" type="checkbox" name="ids[]" value="{{ $project->id }}" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-row aria-label="Select {{ $project->title }}">
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <img src="{{ $project->thumbnailUrl() }}" alt="" class="h-10 w-14 rounded object-cover">
                                    <div class="min-w-0">
                                        <p class="truncate font-medium">{{ $project->title }}</p>
                                        @if ($project->repositoryLabel())
                                            <a href="{{ $project->repository_url }}" target="_blank" rel="noopener noreferrer" class="text-xs text-muted hover:text-text">{{ $project->repositoryLabel() }}</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="text-muted">{{ $project->category?->name ?? 'Uncategorized' }}</td>
                            <td>
                                <x-ui.badge :variant="$project->status === 'published' ? 'success' : 'warning'">
                                    {{ ucfirst($project->status) }}
                                </x-ui.badge>
                            </td>
                            <td>
                                @if ($project->is_featured)
                                    <x-ui.badge variant="warning">Featured</x-ui.badge>
                                @else
                                    <span class="text-xs text-muted">—</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $project->images_count }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.icon-button icon="eye" label="Preview {{ $project->title }}" :href="route('projects.show', $project)" target="_blank" rel="noopener noreferrer" />
                                    <x-ui.icon-button icon="pencil" label="Quick edit {{ $project->title }}" data-dialog-open="quick-edit-project-{{ $project->id }}" />
                                    <x-ui.icon-button icon="settings" label="Edit {{ $project->title }}" :href="route('admin.projects.edit', $project)" />
                                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" data-confirm="Delete “{{ $project->title }}”? This also removes its gallery images." data-confirm-title="Delete project">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.icon-button icon="trash-2" label="Delete {{ $project->title }}" variant="danger" type="submit" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-10 text-center text-muted">No projects match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $projects->links() }}</div>

    @foreach ($projects as $project)
        <dialog id="quick-edit-project-{{ $project->id }}" class="modal">
            <form method="POST" action="{{ route('admin.projects.quick-update', $project) }}" class="modal-card">
                @csrf
                @method('PATCH')
                <h2 class="text-lg font-semibold">Quick edit</h2>
                <p class="mt-1 text-sm text-muted">Update the essentials without opening the full editor.</p>

                <div class="mt-5 space-y-4">
                    <x-ui.field label="Title" name="title-{{ $project->id }}">
                        <input id="title-{{ $project->id }}" type="text" name="title" value="{{ $project->title }}" class="input">
                    </x-ui.field>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-ui.field label="Category" name="category-{{ $project->id }}">
                            <select id="category-{{ $project->id }}" name="category_id" class="select">
                                <option value="">Uncategorized</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected($project->category_id === $category->id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </x-ui.field>
                        <x-ui.field label="Status" name="status-{{ $project->id }}">
                            <select id="status-{{ $project->id }}" name="status" class="select">
                                <option value="published" @selected($project->status === 'published')>Published</option>
                                <option value="draft" @selected($project->status === 'draft')>Draft</option>
                            </select>
                        </x-ui.field>
                    </div>
                    <x-ui.field label="Project date" name="project-date-{{ $project->id }}">
                        <input id="project-date-{{ $project->id }}" type="date" name="project_date" value="{{ $project->project_date?->format('Y-m-d') }}" class="input">
                    </x-ui.field>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_featured" value="1" @checked($project->is_featured) class="rounded border-border text-brand-600 focus:ring-brand-500">
                        Featured project
                    </label>
                </div>

                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                    <x-ui.button type="submit" size="sm">Save changes</x-ui.button>
                </div>
            </form>
        </dialog>
    @endforeach
@endsection
