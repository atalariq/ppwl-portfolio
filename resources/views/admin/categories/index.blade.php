@extends('layouts.admin')

@section('title', 'Categories')

@section('content')
    <x-ui.page-header title="Categories" description="Group projects and power the category filter on the public site.">
        <x-slot:actions>
            <x-ui.button variant="secondary" size="sm" data-dialog-open="bulk-add-categories">
                <x-ui.icon name="layers" class="h-4 w-4" /> Bulk add
            </x-ui.button>
            <x-ui.button :href="route('admin.categories.create')">
                <x-ui.icon name="plus" class="h-4 w-4" /> Add category
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <form id="bulk-form" method="POST" action="{{ route('admin.categories.bulk') }}" class="mt-6" data-bulk-form data-bulk-delete-message="Delete the selected categories? Projects will become uncategorized.">
        @csrf
        <div class="flex flex-wrap items-end gap-3 rounded-lg border border-border bg-white p-3 shadow-sm">
            <label class="flex flex-col gap-1 text-sm">
                <span class="label">Bulk action</span>
                <select name="action" class="select min-w-48" data-bulk-action>
                    <option value="delete">Delete</option>
                </select>
            </label>
            <x-ui.button type="submit" variant="secondary" size="sm">Apply</x-ui.button>
            <p class="ml-auto text-xs text-muted">Select categories with the checkboxes, then apply an action.</p>
        </div>
    </form>

    <div class="mt-4 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-all aria-label="Select all categories"></th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Projects</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><input form="bulk-form" type="checkbox" name="ids[]" value="{{ $category->id }}" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-row aria-label="Select {{ $category->name }}"></td>
                            <td class="font-medium">{{ $category->name }}</td>
                            <td class="text-muted">{{ $category->slug }}</td>
                            <td>{{ $category->projects_count }}</td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.icon-button icon="pencil" label="Quick edit {{ $category->name }}" data-dialog-open="quick-edit-category-{{ $category->id }}" />
                                    <x-ui.icon-button icon="settings" label="Edit {{ $category->name }}" :href="route('admin.categories.edit', $category)" />
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Delete the “{{ $category->name }}” category? Projects will become uncategorized." data-confirm-title="Delete category">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.icon-button icon="trash-2" label="Delete {{ $category->name }}" variant="danger" type="submit" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-muted">No categories yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $categories->links() }}</div>

    @foreach ($categories as $category)
        <dialog id="quick-edit-category-{{ $category->id }}" class="modal">
            <form method="POST" action="{{ route('admin.categories.quick-update', $category) }}" class="modal-card">
                @csrf
                @method('PATCH')
                <h2 class="text-lg font-semibold">Quick edit category</h2>
                <div class="mt-5">
                    <x-ui.field label="Name" name="name-{{ $category->id }}">
                        <input id="name-{{ $category->id }}" type="text" name="name" value="{{ $category->name }}" class="input" required>
                    </x-ui.field>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                    <x-ui.button type="submit" size="sm">Save changes</x-ui.button>
                </div>
            </form>
        </dialog>
    @endforeach

    <dialog id="bulk-add-categories" class="modal">
        <form method="POST" action="{{ route('admin.categories.bulk-store') }}" class="modal-card">
            @csrf
            <h2 class="text-lg font-semibold">Bulk add categories</h2>
            <p class="mt-1 text-sm text-muted">One category name per line. Duplicates and empty lines are skipped.</p>
            <div class="mt-5">
                <x-ui.field label="Category names" name="names">
                    <textarea id="names" name="names" rows="8" class="textarea" placeholder="Web&#10;API&#10;Mobile"></textarea>
                </x-ui.field>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                <x-ui.button type="submit" size="sm">Add categories</x-ui.button>
            </div>
        </form>
    </dialog>
@endsection
