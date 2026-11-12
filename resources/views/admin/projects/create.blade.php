@extends('layouts.admin')

@section('title', 'Add Project')

@section('content')
    <x-ui.page-header title="Add project" description="Create a project with its story, links, category, and technologies." />

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-4xl space-y-5">
        @csrf

        <div class="card card-body space-y-5">
            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.field label="Title" name="title" :error="$errors->first('title')">
                    <input id="title" class="input" type="text" name="title" value="{{ old('title') }}" required>
                </x-ui.field>
                <x-ui.field label="Category" name="category_id" :error="$errors->first('category_id')">
                    <select id="category_id" name="category_id" class="select">
                        <option value="">Uncategorized</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </x-ui.field>
            </div>

            <x-ui.field label="Short description" name="short_description" :error="$errors->first('short_description')" hint="Used on cards and as the meta description. Max 255 characters.">
                <input id="short_description" class="input" type="text" name="short_description" value="{{ old('short_description') }}">
            </x-ui.field>

            <x-ui.field label="Description" name="description" :error="$errors->first('description')" hint="The long story shown on the project detail page. Separate paragraphs with a blank line.">
                <textarea id="description" class="textarea" name="description" rows="7">{{ old('description') }}</textarea>
            </x-ui.field>

            <div class="grid gap-5 sm:grid-cols-3">
                <x-ui.field label="Status" name="status" :error="$errors->first('status')">
                    <select id="status" name="status" class="select">
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status') === 'published')>Published</option>
                    </select>
                </x-ui.field>
                <x-ui.field label="Project date" name="project_date" :error="$errors->first('project_date')">
                    <input id="project_date" class="input" type="date" name="project_date" value="{{ old('project_date') }}">
                </x-ui.field>
                <label class="flex items-center gap-2 self-end pb-2 text-sm">
                    <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="rounded border-border text-brand-600 focus:ring-brand-500">
                    Featured project
                </label>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.field label="Project URL" name="project_url" :error="$errors->first('project_url')" hint="Optional live demo or product link.">
                    <input id="project_url" class="input" type="url" name="project_url" value="{{ old('project_url') }}" placeholder="https://…">
                </x-ui.field>
                <x-ui.field label="Repository URL" name="repository_url" :error="$errors->first('repository_url')" hint="Full GitHub or GitLab repository URL.">
                    <input id="repository_url" class="input" type="url" name="repository_url" value="{{ old('repository_url') }}" placeholder="https://github.com/…">
                </x-ui.field>
            </div>

            <x-ui.file-dropzone
                name="thumbnail"
                label="Thumbnail"
                :error="$errors->first('thumbnail')"
                hint="JPG, PNG, or WebP. Max 4 MB. Minimum 400 × 300 px."
                :max-size="4"
                :min-width="400"
                :min-height="300"
            />

            <div>
                <span class="label">Technologies</span>
                <div class="mt-2 grid gap-2 sm:grid-cols-3">
                    @foreach ($technologies as $technology)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="technologies[]" value="{{ $technology->id }}" @checked(in_array($technology->id, old('technologies', []))) class="rounded border-border text-brand-600 focus:ring-brand-500">
                            {{ $technology->name }}
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <x-ui.button type="submit">
                <x-ui.icon name="check" class="h-4 w-4" /> Save project
            </x-ui.button>
            <x-ui.button :href="route('admin.projects.index')" variant="ghost">Cancel</x-ui.button>
        </div>
    </form>
@endsection
