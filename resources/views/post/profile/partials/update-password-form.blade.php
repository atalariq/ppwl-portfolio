<form method="POST" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    @method('put')

    <x-ui.field label="Password saat ini" name="current_password" :error="$errors->updatePassword->first('current_password')">
        <input id="current_password" class="input" type="password" name="current_password" autocomplete="current-password">
    </x-ui.field>

    <x-ui.field label="Password baru" name="password" :error="$errors->updatePassword->first('password')">
        <input id="password" class="input" type="password" name="password" autocomplete="new-password">
    </x-ui.field>

    <x-ui.field label="Konfirmasi password baru" name="password_confirmation" :error="$errors->updatePassword->first('password_confirmation')">
        <input id="password_confirmation" class="input" type="password" name="password_confirmation" autocomplete="new-password">
    </x-ui.field>

    <div class="flex items-center gap-3">
        <x-ui.button type="submit">Simpan</x-ui.button>
        @if (session('status') === 'password-updated')
            <span class="text-sm text-success">Tersimpan.</span>
        @endif
    </div>
</form>
