@props(['title', 'description' => null])

<div {{ $attributes->class('empty-state') }}>
    <h2 class="text-base font-semibold">{{ $title }}</h2>
    @if ($description)
        <p class="mx-auto mt-1 max-w-md text-sm text-muted">{{ $description }}</p>
    @endif
    @isset($action)
        <div class="mt-4">{{ $action }}</div>
    @endisset
</div>
