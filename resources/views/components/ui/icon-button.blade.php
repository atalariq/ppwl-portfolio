@props(['icon', 'label', 'href' => null, 'variant' => 'ghost'])

@php
    $classes = 'icon-btn'.($variant === 'danger' ? ' icon-btn-danger' : '');
@endphp

@if ($href)
    <a href="{{ $href }}" title="{{ $label }}" aria-label="{{ $label }}" {{ $attributes->class($classes) }}>
        <x-ui.icon :name="$icon" />
    </a>
@else
    <button type="{{ $attributes->get('type', 'button') }}" title="{{ $label }}" aria-label="{{ $label }}" {{ $attributes->except('type')->class($classes) }}>
        <x-ui.icon :name="$icon" />
    </button>
@endif
