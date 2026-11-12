@extends('layouts.admin')

@section('title', 'Skills')

@section('content')
    <x-ui.page-header title="Skills" description="Grouped skills shown on the About page and summarized on the dashboard.">
        <x-slot:actions>
            <x-ui.button variant="secondary" size="sm" data-dialog-open="bulk-add-skills">
                <x-ui.icon name="layers" class="h-4 w-4" /> Bulk add
            </x-ui.button>
            <x-ui.button :href="route('admin.skills.create')">
                <x-ui.icon name="plus" class="h-4 w-4" /> Add skill
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <form id="bulk-form" method="POST" action="{{ route('admin.skills.bulk') }}" class="mt-6" data-bulk-form data-bulk-delete-message="Delete the selected skills?">
        @csrf
        <div class="flex flex-wrap items-end gap-3 rounded-lg border border-border bg-white p-3 shadow-sm">
            <label class="flex flex-col gap-1 text-sm">
                <span class="label">Bulk action</span>
                <select name="action" class="select min-w-48" data-bulk-action>
                    <option value="delete">Delete</option>
                </select>
            </label>
            <x-ui.button type="submit" variant="secondary" size="sm">Apply</x-ui.button>
            <p class="ml-auto text-xs text-muted">Select skills with the checkboxes, then apply an action.</p>
        </div>
    </form>

    <div class="mt-4 overflow-hidden rounded-lg border border-border bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th class="w-10"><input type="checkbox" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-all aria-label="Select all skills"></th>
                        <th>Name</th>
                        <th>Category</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($skills as $skill)
                        <tr>
                            <td><input form="bulk-form" type="checkbox" name="ids[]" value="{{ $skill->id }}" class="rounded border-border text-brand-600 focus:ring-brand-500" data-select-row aria-label="Select {{ $skill->name }}"></td>
                            <td class="font-medium">{{ $skill->name }}</td>
                            <td><x-ui.badge variant="muted">{{ $skill->category }}</x-ui.badge></td>
                            <td>
                                <div class="flex items-center justify-end gap-1">
                                    <x-ui.icon-button icon="pencil" label="Quick edit {{ $skill->name }}" data-dialog-open="quick-edit-skill-{{ $skill->id }}" />
                                    <x-ui.icon-button icon="settings" label="Edit {{ $skill->name }}" :href="route('admin.skills.edit', $skill)" />
                                    <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" data-confirm="Delete the “{{ $skill->name }}” skill?" data-confirm-title="Delete skill">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.icon-button icon="trash-2" label="Delete {{ $skill->name }}" variant="danger" type="submit" />
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-10 text-center text-muted">No skills yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $skills->links() }}</div>

    @foreach ($skills as $skill)
        <dialog id="quick-edit-skill-{{ $skill->id }}" class="modal">
            <form method="POST" action="{{ route('admin.skills.quick-update', $skill) }}" class="modal-card">
                @csrf
                @method('PATCH')
                <h2 class="text-lg font-semibold">Quick edit skill</h2>
                <div class="mt-5 space-y-4">
                    <x-ui.field label="Name" name="name-{{ $skill->id }}">
                        <input id="name-{{ $skill->id }}" type="text" name="name" value="{{ $skill->name }}" class="input" required>
                    </x-ui.field>
                    <x-ui.field label="Category" name="category-{{ $skill->id }}">
                        <select id="category-{{ $skill->id }}" name="category" class="select">
                            @foreach (['Languages', 'Frameworks', 'Data', 'Tools', 'Platforms'] as $category)
                                <option value="{{ $category }}" @selected($skill->category === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                </div>
                <div class="mt-6 flex justify-end gap-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                    <x-ui.button type="submit" size="sm">Save changes</x-ui.button>
                </div>
            </form>
        </dialog>
    @endforeach

    <dialog id="bulk-add-skills" class="modal">
        <form method="POST" action="{{ route('admin.skills.bulk-store') }}" class="modal-card">
            @csrf
            <h2 class="text-lg font-semibold">Bulk add skills</h2>
            <p class="mt-1 text-sm text-muted">One skill per line: <code>Name, Category</code>. The category is optional and defaults to Tools.</p>
            <div class="mt-5">
                <x-ui.field label="Skills" name="entries">
                    <textarea id="entries" name="entries" rows="8" class="textarea" placeholder="Laravel, Frameworks&#10;PostgreSQL, Data&#10;Docker"></textarea>
                </x-ui.field>
            </div>
            <div class="mt-6 flex justify-end gap-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dialog-close>Cancel</button>
                <x-ui.button type="submit" size="sm">Add skills</x-ui.button>
            </div>
        </form>
    </dialog>
@endsection
