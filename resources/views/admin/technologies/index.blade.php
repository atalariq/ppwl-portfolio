@extends('layouts.admin')

@section('title', 'Technologies')

@section('content')
    <x-ui.page-header title="Technologies" description="Tag projects and power the technology filter on the public site.">
        <x-slot:actions>
            <x-ui.button variant="secondary" size="sm" data-dialog-open="bulk-add-technologies">
                <x-ui.icon name="layers" class="h-4 w-4" /> Bulk add
            </x-ui.button>
            <x-ui.button :href="route('admin.technologies.create')">
                <x-ui.icon name="plus" class="h-4 w-4" /> Add technology
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <form id="bulk-form" method="POST" action="{{ route('admin.technologies.bulk') }}" class="mt-6" data-bulk-form data-bulk-delete-message="Delete the selected technologies? They will be removed from every project.">
        @csrf
        <div class="flex flex-wrap items-end gap-3 rounded-lg border border-border bg-white p-3 shadow-sm">
            <label class="flex flex-col gap-1 text-sm">
                <span class="label">Bulk action</span>
                <select name="action" class="select min-w-48" data-bulk-action>
                    <option value="delete">Delete</option>
                </select>
            </label>
            <x-ui.button type="submit" variant="secondary" size="sm">Apply</x-ui.button>
            <p class="ml-auto text-xs text-muted">Select technologies with the checkboxes, then apply an action.</p>
        </div>
    </form>

    <div class="mt-4 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-all aria-label="Select all technologies"></th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Projects</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($technologies as $technology)
                        <tr>
                            <td><input form="bulk-form" type="checkbox" name="ids[]" value="{{ $technology->id }}" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-row aria-label="Select {{ $technology->name }}"></td>
                            <td class="font-medium">{{ $technology->name }}</td>
                            <td class="text-muted">{{ $technology->slug }}</td>
                            <td>{{ $technology->projects_count }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.icon-button icon="pencil" label="Quick edit {{ $technology->name }}" data-dialog-open="quick-edit-technology-{{ $technology->id }}" />
                                    <x-ui.icon-button icon="settings" label="Edit {{ $technology->name }}" :href="route('admin.technologies.edit', $technology)" />
                                    <form action="{{ route('admin.technologies.destroy', $technology) }}" method="POST" data-confirm="Delete the “{{ $technology->name }}” technology? It will be removed from every project." data-confirm-title="Delete technology">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.icon-button icon="trash-2" label="Delete {{ $technology->name }}" variant="danger" type="submit" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-muted">No technologies yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $technologies->links() }}</div>

    @foreach ($technologies as $technology)
        <dialog id="quick-edit-technology-{{ $technology->id }}" class="modal">
            <form method="POST" action="{{ route('admin.technologies.quick-update', $technology) }}" class="modal-card">
                @csrf
                @method('PATCH')
                <h2 class="text-lg font-semibold">Quick edit technology</h2>
                <div class="mt-5">
                    <x-ui.field label="Name" name="name-{{ $technology->id }}">
                        <input id="name-{{ $technology->id }}" type="text" name="name" value="{{ $technology->name }}" class="input" required>
                    </x-ui.field>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                    <x-ui.button type="submit" size="sm">Save changes</x-ui.button>
                </div>
            </form>
        </dialog>
    @endforeach

    <dialog id="bulk-add-technologies" class="modal">
        <form method="POST" action="{{ route('admin.technologies.bulk-store') }}" class="modal-card">
            @csrf
            <h2 class="text-lg font-semibold">Bulk add technologies</h2>
            <p class="mt-1 text-sm text-muted">One technology name per line. Duplicates and empty lines are skipped.</p>
            <div class="mt-5">
                <x-ui.field label="Technology names" name="names">
                    <textarea id="names" name="names" rows="8" class="textarea" placeholder="Laravel&#10;React&#10;Docker"></textarea>
                </x-ui.field>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                <x-ui.button type="submit" size="sm">Add technologies</x-ui.button>
            </div>
        </form>
    </dialog>
@endsection
