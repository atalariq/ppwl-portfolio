@props(['label', 'name', 'error' => null, 'hint' => null])

<div {{ $attributes }}>
    <label for="{{ $name }}" class="label">{{ $label }}</label>
    {{ $slot }}
    @if ($hint)
        <p class="help">{{ $hint }}</p>
    @endif
    @if ($error)
        <p class="error">{{ $error }}</p>
    @endif
</div>
