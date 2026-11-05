@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <x-ui.page-header title="Edit project" :description="$project->title">
        <x-slot:actions>
            <x-ui.button :href="route('projects.show', $project)" variant="secondary" size="sm">Lihat halaman publik</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="mt-6 grid gap-6 lg:grid-cols-[1.5fr_1fr]">
        <form action="{{ route('post.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="card card-body space-y-5">
                <x-ui.field label="Title" name="title" :error="$errors->first('title')">
                    <input id="title" class="input" type="text" name="title" value="{{ old('title', $project->title) }}">
                </x-ui.field>

                <x-ui.field label="Repo" name="repo" :error="$errors->first('repo')">
                    <input id="repo" class="input" type="text" name="repo" value="{{ old('repo', $project->repo) }}">
                </x-ui.field>

                <x-ui.field label="Description" name="description" :error="$errors->first('description')">
                    <textarea id="description" class="textarea" name="description" rows="5">{{ old('description', $project->description) }}</textarea>
                </x-ui.field>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-ui.field label="Kategori" name="category_id" :error="$errors->first('category_id')">
                        <select id="category_id" name="category_id" class="select">
                            <option value="">Tanpa kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $project->category_id) === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </x-ui.field>

                    <x-ui.field label="Status" name="status" :error="$errors->first('status')">
                        <select id="status" name="status" class="select">
                            <option value="draft" @selected(old('status', $project->status) === 'draft')>Draft</option>
                            <option value="published" @selected(old('status', $project->status) === 'published')>Published</option>
                        </select>
                    </x-ui.field>
                </div>

                <x-ui.field label="Thumbnail" name="thumbnail" :error="$errors->first('thumbnail')" hint="Kosongkan bila tidak ingin mengganti thumbnail. Ukuran minimal 400 x 300 piksel.">
                    @if ($project->thumbnail)
                        <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="Thumbnail {{ $project->title }}" class="mb-2 h-32 w-48 rounded-md object-cover">
                    @endif
                    <input id="thumbnail" type="file" name="thumbnail" accept="image/*" class="input">
                </x-ui.field>

                <div>
                    <span class="label">Teknologi</span>
                    <div class="mt-2 grid gap-2 sm:grid-cols-2">
                        @foreach ($technologies as $technology)
                            <label class="flex items-center gap-2 text-sm">
                                <input type="checkbox" name="technologies[]" value="{{ $technology->id }}" @checked(in_array($technology->id, old('technologies', $project->technologies->pluck('id')->all()))) class="rounded border-border text-brand-600 focus:ring-brand-500">
                                {{ $technology->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <x-ui.button type="submit">Simpan perubahan</x-ui.button>
        </form>

        <div class="space-y-6">
            <x-ui.card title="Gallery" :description="$project->images->count().' dari 10 gambar tersimpan.'">
                @if ($project->images->isNotEmpty())
                    <div class="space-y-4">
                        @foreach ($project->images as $image)
                            <div class="rounded-md border border-border p-3">
                                <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: 'Gallery '.$project->title }}" class="h-36 w-full rounded object-cover">

                                <form action="{{ route('post.projects.images.update', [$project, $image]) }}" method="POST" class="mt-3 flex gap-2">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="caption" value="{{ $image->caption }}" placeholder="Caption gambar" class="input">
                                    <x-ui.button type="submit" size="sm">Simpan</x-ui.button>
                                </form>

                                <form action="{{ route('post.projects.images.destroy', [$project, $image]) }}" method="POST" class="mt-2" onsubmit="return confirm('Hapus gambar ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-danger hover:underline">Hapus gambar</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-muted">Belum ada gambar gallery.</p>
                @endif
            </x-ui.card>

            <x-ui.card title="Tambah gambar" description="Pilih beberapa gambar sekaligus. Maksimal 10 gambar per project, masing-masing maksimal 4 MB.">
                <form action="{{ route('post.projects.images.store', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <input type="file" name="images[]" accept="image/*" multiple class="input">
                    @error('images')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    <x-ui.button type="submit" size="sm">Upload gallery</x-ui.button>
                </form>
            </x-ui.card>
        </div>
    </div>
@endsection
