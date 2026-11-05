@props(['label', 'value', 'hint' => null, 'tone' => 'text-brand-600'])

<div {{ $attributes->class('stat') }}>
    <div class="stat-label">{{ $label }}</div>
    <div class="stat-value {{ $tone }}">{{ $value }}</div>
    @if ($hint)
        <p class="mt-1 text-xs text-muted">{{ $hint }}</p>
    @endif
</div>
