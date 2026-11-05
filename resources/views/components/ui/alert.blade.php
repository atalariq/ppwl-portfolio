@props(['variant' => 'info'])

<div {{ $attributes->class('alert alert-'.$variant) }} role="status">
    {{ $slot }}
</div>
