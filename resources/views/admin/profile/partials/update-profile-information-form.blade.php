<form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data" class="space-y-5">
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
        <div class="grow text-sm text-muted">
            <p class="font-medium text-text">{{ $user->name }}</p>
            <p>{{ $user->email }}</p>
            @if ($user->username)
                <p>Username: {{ $user->username }}</p>
            @endif
        </div>
    </div>

    <x-ui.file-dropzone
        name="avatar"
        label="Profile photo"
        :error="$errors->first('avatar')"
        hint="JPG, PNG, or WebP. Max 2 MB. Minimum 200 × 200 px."
        :max-size="2"
        :min-width="200"
        :min-height="200"
        :existing="$user->avatar ? [['url' => $user->avatarUrl(), 'alt' => $user->name, 'caption' => 'Current photo']] : []"
    />

    <x-ui.field label="Name" name="name" :error="$errors->first('name')">
        <input id="name" class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
    </x-ui.field>

    <x-ui.field label="Email" name="email" :error="$errors->first('email')">
        <input id="email" class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
    </x-ui.field>

    <div class="flex items-center gap-3">
        <x-ui.button type="submit">
            <x-ui.icon name="check" class="h-4 w-4" /> Save profile
        </x-ui.button>
        @if (session('status') === 'profile-updated')
            <span class="text-sm text-success">Saved.</span>
        @endif
    </div>
</form>
