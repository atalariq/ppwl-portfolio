@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <x-ui.page-header title="Edit project" :description="$project->title">
        <x-slot:actions>
            <x-ui.button :href="route('projects.show', $project)" variant="secondary" size="sm" target="_blank" rel="noopener noreferrer">
                <x-ui.icon name="eye" class="h-4 w-4" /> Preview
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1.4fr_0.6fr]">
        <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="card card-body space-y-5">
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-ui.field label="Title" name="title" :error="$errors->first('title')">
                        <input id="title" class="input" type="text" name="title" value="{{ old('title', $project->title) }}" required>
                    </x-ui.field>
                    <x-ui.field label="Category" name="category_id" :error="$errors->first('category_id')">
                        <select id="category_id" name="category_id" class="select">
                            <option value="">Uncategorized</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $project->category_id) === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>
                </div>

                <x-ui.field label="Short description" name="short_description" :error="$errors->first('short_description')" hint="Used on cards and as the meta description. Max 255 characters.">
                    <input id="short_description" class="input" type="text" name="short_description" value="{{ old('short_description', $project->short_description) }}">
                </x-ui.field>

                <x-ui.field label="Description" name="description" :error="$errors->first('description')" hint="Separate paragraphs with a blank line.">
                    <textarea id="description" class="textarea" name="description" rows="8">{{ old('description', $project->description) }}</textarea>
                </x-ui.field>

                <div class="grid gap-5 sm:grid-cols-3">
                    <x-ui.field label="Status" name="status" :error="$errors->first('status')">
                        <select id="status" name="status" class="select">
                            <option value="draft" @selected(old('status', $project->status) === 'draft')>Draft</option>
                            <option value="published" @selected(old('status', $project->status) === 'published')>Published</option>
                        </select>
                    </x-ui.field>
                    <x-ui.field label="Project date" name="project_date" :error="$errors->first('project_date')">
                        <input id="project_date" class="input" type="date" name="project_date" value="{{ old('project_date', $project->project_date?->format('Y-m-d')) }}">
                    </x-ui.field>
                    <label class="flex items-center gap-2 self-end pb-2 text-sm">
                        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured)) class="rounded border-border text-brand-600 focus:ring-brand-500">
                        Featured project
                    </label>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-ui.field label="Project URL" name="project_url" :error="$errors->first('project_url')">
                        <input id="project_url" class="input" type="url" name="project_url" value="{{ old('project_url', $project->project_url) }}" placeholder="https://…">
                    </x-ui.field>
                    <x-ui.field label="Repository URL" name="repository_url" :error="$errors->first('repository_url')">
                        <input id="repository_url" class="input" type="url" name="repository_url" value="{{ old('repository_url', $project->repository_url) }}" placeholder="https://github.com/…">
                    </x-ui.field>
                </div>

                <x-ui.file-dropzone
                    name="thumbnail"
                    label="Thumbnail"
                    :error="$errors->first('thumbnail')"
                    hint="Leave empty to keep the current thumbnail. JPG, PNG, or WebP. Max 4 MB. Minimum 400 × 300 px."
                    :max-size="4"
                    :min-width="400"
                    :min-height="300"
                    :existing="$project->thumbnail ? [['url' => asset('storage/'.$project->thumbnail), 'alt' => $project->title, 'caption' => 'Current thumbnail']] : []"
                />

                <div>
                    <span class="label">Technologies</span>
                    <div class="mt-2 grid gap-2 sm:grid-cols-3">
                        @foreach ($technologies as $technology)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="technologies[]" value="{{ $technology->id }}" @checked(in_array($technology->id, old('technologies', $project->technologies->pluck('id')->all()))) class="rounded border-border text-brand-600 focus:ring-brand-500">
                                {{ $technology->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-ui.button type="submit">
                    <x-ui.icon name="check" class="h-4 w-4" /> Save changes
                </x-ui.button>
                <x-ui.button :href="route('admin.projects.index')" variant="ghost">Cancel</x-ui.button>
            </div>
        </form>

        <div class="space-y-6">
            <x-ui.card title="Gallery" :description="$project->images->count().' of 10 images saved.'">
                @if ($project->images->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($project->images as $image)
                            <div class="rounded-md border border-border p-3">
                                <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: $project->title }}" class="h-36 w-full rounded object-cover">

                                <form action="{{ route('admin.projects.images.update', [$project, $image]) }}" method="POST" class="mt-3 flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="caption" value="{{ $image->caption }}" placeholder="Image caption" class="input">
                                    <x-ui.button type="submit" size="sm">Save</x-ui.button>
                                </form>

                                <form action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" method="POST" class="mt-2" data-confirm="Delete this gallery image?" data-confirm-title="Delete image">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 text-xs text-danger hover:underline">
                                        <x-ui.icon name="trash-2" class="h-3.5 w-3.5" /> Delete image
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-muted">No gallery images yet.</p>
                @endif
            </x-ui.card>

            <x-ui.card title="Add gallery images" description="Upload up to 10 images per project. Each image can be up to 4 MB.">
                <form action="{{ route('admin.projects.images.store', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <x-ui.file-dropzone
                        name="images"
                        label="Images"
                        :error="$errors->first('images') ?: $errors->first('images.*')"
                        :multiple="true"
                        :max-size="4"
                        :max-files="10"
                    />
                    <x-ui.button type="submit" size="sm">
                        <x-ui.icon name="upload" class="h-4 w-4" /> Upload gallery
                    </x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection
