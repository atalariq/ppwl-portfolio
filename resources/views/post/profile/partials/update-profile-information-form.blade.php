<form method="POST" action="{{ route('post.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('patch')

    <div class="flex items-center gap-4">
        @if ($user->avatar)
            <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover">
        @else
            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-slate-900 text-2xl font-semibold text-white">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        @endif

        <div class="grow">
            <x-ui.field label="Foto profil" name="avatar" :error="$errors->first('avatar')" hint="JPG, JPEG, PNG, atau WebP. Maksimal 2 MB. Ukuran minimal 200 x 200 piksel.">
                <input id="avatar" type="file" name="avatar" accept="image/*" class="input">
            </x-ui.field>
        </div>
    </div>

    <x-ui.field label="Nama" name="name" :error="$errors->first('name')">
        <input id="name" class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
    </x-ui.field>

    <x-ui.field label="Email" name="email" :error="$errors->first('email')">
        <input id="email" class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
    </x-ui.field>

    <div class="flex items-center gap-3">
        <x-ui.button type="submit">Simpan</x-ui.button>
        @if (session('status') === 'profile-updated')
            <span class="text-sm text-success">Tersimpan.</span>
        @endif
    </div>
</form>
