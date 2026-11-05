@props(['title' => null, 'description' => null])

<section {{ $attributes->class('card') }}>
    @if ($title || $description || isset($actions))
        <div class="flex flex-wrap items-start justify-between gap-4 border-b border-border px-6 py-4">
            <div>
                @if ($title)
                    <h2 class="text-lg font-semibold">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-muted">{{ $description }}</p>
                @endif
            </div>
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif

    <div class="card-body">
        {{ $slot }}
    </div>
</section>
