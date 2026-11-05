@extends('layouts.admin')

@section('title', 'Tambah Project')

@section('content')
    <x-ui.page-header title="Tambah project" description="Isi detail project, thumbnail, kategori, dan teknologi." />

    <form action="{{ route('post.projects.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 max-w-3xl space-y-5">
        @csrf

        <div class="card card-body space-y-5">
            <x-ui.field label="Title" name="title" :error="$errors->first('title')">
                <input id="title" class="input" type="text" name="title" value="{{ old('title') }}">
            </x-ui.field>

            <x-ui.field label="Repo" name="repo" :error="$errors->first('repo')" hint="Format: owner/repository">
                <input id="repo" class="input" type="text" name="repo" value="{{ old('repo') }}">
            </x-ui.field>

            <x-ui.field label="Description" name="description" :error="$errors->first('description')">
                <textarea id="description" class="textarea" name="description" rows="5">{{ old('description') }}</textarea>
            </x-ui.field>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-ui.field label="Kategori" name="category_id" :error="$errors->first('category_id')">
                    <select id="category_id" name="category_id" class="select">
                        <option value="">Tanpa kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </x-ui.field>

                <x-ui.field label="Status" name="status" :error="$errors->first('status')">
                    <select id="status" name="status" class="select">
                        <option value="draft" @selected(old('status', 'draft') === 'draft')>Draft</option>
                        <option value="published" @selected(old('status') === 'published')>Published</option>
                    </select>
                </x-ui.field>
            </div>

            <x-ui.field label="Thumbnail" name="thumbnail" :error="$errors->first('thumbnail')" hint="JPG, JPEG, PNG, atau WebP. Maksimal 2 MB. Ukuran minimal 400 x 300 piksel.">
                <input id="thumbnail" type="file" name="thumbnail" accept="image/*" class="input">
            </x-ui.field>

            <div>
                <span class="label">Teknologi</span>
                <div class="mt-2 grid gap-2 sm:grid-cols-2">
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
            <x-ui.button type="submit">Simpan</x-ui.button>
            <x-ui.button :href="route('post.projects.index')" variant="ghost">Batal</x-ui.button>
        </div>
    </form>
@endsection
