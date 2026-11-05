<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-600">
            Perbarui nama, alamat email, dan foto profil akun yang dipakai untuk masuk ke area pengelolaan.
        </p>
    </header>

    <form method="POST" action="{{ route('post.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="flex items-center gap-4">
            @if ($user->avatar)
                <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
            @else
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-gray-900 text-2xl font-semibold text-white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <div class="grow">
                <x-input-label for="avatar" value="Foto profil" />
                <input type="file" name="avatar" id="avatar" accept="image/*" class="mt-1 block w-full rounded border px-3 py-2 text-sm">
                <p class="mt-1 text-xs text-gray-500">JPG, JPEG, PNG, atau WebP. Maksimal 2 MB. Ukuran minimal 200 x 200 piksel.</p>
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" value="Nama" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600">Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
