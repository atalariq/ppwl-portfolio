@extends('layouts.admin')

@section('title', 'About')

@section('content')
    <x-ui.page-header title="About page" description="Edit the public profile, biography, and contact links.">
        <x-slot:actions>
            <x-ui.button :href="route('about')" variant="secondary" size="sm" target="_blank" rel="noopener noreferrer">
                <x-ui.icon name="eye" class="h-4 w-4" /> Preview
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-6 grid gap-6 xl:grid-cols-[1.3fr_0.7fr]">
        <form action="{{ route('admin.about.update') }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="card card-body space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-ui.field label="Full name" name="full_name" :error="$errors->first('full_name')">
                        <input id="full_name" class="input" type="text" name="full_name" value="{{ old('full_name', $profile->full_name) }}" required>
                    </x-ui.field>
                    <x-ui.field label="Role" name="role" :error="$errors->first('role')">
                        <input id="role" class="input" type="text" name="role" value="{{ old('role', $profile->role) }}" required>
                    </x-ui.field>
                </div>

                <x-ui.field label="Summary" name="summary" :error="$errors->first('summary')" hint="Used in the page header and meta description.">
                    <input id="summary" class="input" type="text" name="summary" value="{{ old('summary', $profile->summary) }}">
                </x-ui.field>

                <x-ui.field label="Biography" name="bio" :error="$errors->first('bio')" hint="Separate paragraphs with a blank line.">
                    <textarea id="bio" class="textarea" name="bio" rows="10">{{ old('bio', $profile->bio) }}</textarea>
                </x-ui.field>

                <x-ui.field label="Location" name="location" :error="$errors->first('location')">
                    <input id="location" class="input" type="text" name="location" value="{{ old('location', $profile->location) }}">
                </x-ui.field>

                <div class="grid gap-5 sm:grid-cols-3">
                    <x-ui.field label="Website URL" name="website_url" :error="$errors->first('website_url')">
                        <input id="website_url" class="input" type="url" name="website_url" value="{{ old('website_url', $profile->website_url) }}">
                    </x-ui.field>
                    <x-ui.field label="GitHub URL" name="github_url" :error="$errors->first('github_url')">
                        <input id="github_url" class="input" type="url" name="github_url" value="{{ old('github_url', $profile->github_url) }}">
                    </x-ui.field>
                    <x-ui.field label="LinkedIn URL" name="linkedin_url" :error="$errors->first('linkedin_url')">
                        <input id="linkedin_url" class="input" type="url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url) }}">
                    </x-ui.field>
                </div>
            </div>

            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" /> Save about page
            </x-ui.button>
        </form>

        <div class="space-y-4">
            <x-ui.card title="Contacts" description="Clickable links shown on the About page.">
                <x-slot:actions>
                    <x-ui.button :href="route('admin.contacts.create')" size="sm">
                        <x-ui.icon name="plus" class="h-4 w-4" /> Add contact
                    </x-ui.button>
                </x-slot:actions>

                <div class="space-y-2">
                    @forelse ($contacts as $contact)
                        <div class="flex items-center gap-3 rounded-md border border-border px-3 py-2">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-brand-50 text-brand-700">
                                <x-ui.icon :name="$contact->icon" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0 grow">
                                <p class="text-sm font-medium">{{ $contact->label }}</p>
                                <p class="truncate text-xs text-muted">{{ $contact->value }}</p>
                            </div>
                            <div class="flex items-center gap-1">
                                <x-ui.icon-button icon="pencil" label="Edit {{ $contact->label }}" :href="route('admin.contacts.edit', $contact)" />
                                <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" data-confirm="Delete the “{{ $contact->label }}” contact?" data-confirm-title="Delete contact">
                                    @csrf
                                    @method('DELETE')
                                    <x-ui.icon-button icon="trash-2" label="Delete {{ $contact->label }}" variant="danger" type="submit" />
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-muted">No contacts yet.</p>
                    @endforelse
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection
