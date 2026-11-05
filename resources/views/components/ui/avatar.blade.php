@props(['user', 'size' => 'h-9 w-9'])

@if ($user->avatar)
    <img src="{{ $user->avatarUrl() }}" alt="{{ $user->name }}" class="{{ $size }} rounded-full object-cover">
@else
    <div class="{{ $size }} flex items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
        {{ strtoupper(substr($user->name, 0, 1)) }}
    </div>
@endif
