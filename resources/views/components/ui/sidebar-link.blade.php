@props(['href', 'active' => false])

<a href="{{ $href }}" {{ $attributes->class(['sidebar-link', 'sidebar-link-active' => $active]) }}>
    {{ $slot }}
</a>
