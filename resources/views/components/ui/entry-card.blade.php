@props([
    'period',
    'title',
    'subtitle' => null,
    'description' => null,
    'items' => [],
])

<article class="card card-body flex h-full flex-col">
    <div class="flex items-start justify-between gap-3">
        <h2 class="min-w-0 text-lg font-semibold leading-snug">{{ $title }}</h2>
        <x-ui.badge variant="muted" class="shrink-0">{{ $period }}</x-ui.badge>
    </div>

    @if ($subtitle)
        <p class="mt-1 text-sm font-medium text-brand-700">{{ $subtitle }}</p>
    @endif

    @if ($description)
        <p class="mt-3 text-sm leading-relaxed">{{ $description }}</p>
    @endif

    @if (! empty($items))
        <ul class="mt-3 list-disc space-y-1 pl-5 text-sm leading-relaxed">
            @foreach ($items as $item)
                <li>{{ $item }}</li>
            @endforeach
        </ul>
    @endif
</article>
