@extends('layouts.admin')

@section('title', 'Edit Project')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.5fr_1fr]">
        <form action="{{ route('post.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-4 rounded-lg bg-white p-6 shadow-sm">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $project->title) }}" class="mt-1 w-full rounded border px-3 py-2">
                @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="repo" class="block text-sm font-medium">Repo</label>
                <input type="text" name="repo" id="repo" value="{{ old('repo', $project->repo) }}" class="mt-1 w-full rounded border px-3 py-2">
                @error('repo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium">Description</label>
                <textarea name="description" id="description" rows="4" class="mt-1 w-full rounded border px-3 py-2">{{ old('description', $project->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="category_id" class="block text-sm font-medium">Kategori</label>
                    <select name="category_id" id="category_id" class="mt-1 w-full rounded border px-3 py-2">
                        <option value="">Tanpa kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $project->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium">Status</label>
                    <select name="status" id="status" class="mt-1 w-full rounded border px-3 py-2">
                        <option value="draft" @selected(old('status', $project->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $project->status) === 'published')>Published</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="thumbnail" class="block text-sm font-medium">Thumbnail</label>

                @if ($project->thumbnail)
                    <img src="{{ asset('storage/'.$project->thumbnail) }}" alt="Thumbnail {{ $project->title }}" class="mt-2 h-32 w-48 rounded object-cover">
                @endif

                <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="mt-2 w-full rounded border px-3 py-2">
                <p class="mt-1 text-xs text-gray-500">Kosongkan bila tidak ingin mengganti thumbnail. Ukuran minimal 400 x 300 piksel.</p>
                @error('thumbnail')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <span class="block text-sm font-medium">Teknologi</span>
                <div class="mt-2 grid gap-2 sm:grid-cols-2">
                    @foreach ($technologies as $technology)
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="technologies[]" value="{{ $technology->id }}" @checked(in_array($technology->id, old('technologies', $project->technologies->pluck('id')->all())))>
                            {{ $technology->name }}
                        </label>
                    @endforeach
                </div>
                @error('technologies')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Save</button>
                <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-600 underline">Lihat halaman publik</a>
            </div>
        </form>

        <section class="space-y-4 rounded-lg bg-white p-6 shadow-sm">
            <div>
                <h2 class="text-lg font-semibold">Gallery</h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $project->images->count() }} dari 10 gambar tersimpan.
                </p>
            </div>

            @if ($project->images->isNotEmpty())
                <div class="space-y-3">
                    @foreach ($project->images as $image)
                        <div class="rounded border p-3">
                            <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->caption ?: 'Gallery '.$project->title }}" class="h-32 w-full rounded object-cover">

                            <form action="{{ route('post.projects.images.update', [$project, $image]) }}" method="POST" class="mt-2 flex gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="caption" value="{{ $image->caption }}" placeholder="Caption gambar" class="w-full rounded border px-2 py-1 text-sm">
                                <button type="submit" class="rounded bg-gray-900 px-2 py-1 text-xs font-medium text-white">Simpan</button>
                            </form>

                            <form action="{{ route('post.projects.images.destroy', [$project, $image]) }}" method="POST" class="mt-2" data-confirm="Hapus gambar ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-600 hover:underline">Hapus gambar</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">Belum ada gambar gallery.</p>
            @endif

            <form action="{{ route('post.projects.images.store', $project) }}" method="POST" enctype="multipart/form-data" class="space-y-2 border-t pt-4">
                @csrf
                <label for="images" class="block text-sm font-medium">Tambah gambar</label>
                <input type="file" name="images[]" id="images" accept="image/*" multiple class="w-full rounded border px-3 py-2">
                <p class="text-xs text-gray-500">Pilih beberapa gambar sekaligus. Maksimal 10 gambar per project, masing-masing maksimal 4 MB.</p>
                @error('images')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
                <button type="submit" class="rounded bg-gray-900 px-4 py-2 text-sm font-medium text-white">Upload gallery</button>
            </form>
        </section>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('form[data-confirm]').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    if (!confirm(form.dataset.confirm)) {
                        event.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection
