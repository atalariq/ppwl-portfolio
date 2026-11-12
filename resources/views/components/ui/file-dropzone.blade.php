@props([
    'name',
    'label',
    'hint' => null,
    'multiple' => false,
    'accept' => 'image/jpeg,image/png,image/webp',
    'maxSize' => 4,
    'minWidth' => null,
    'minHeight' => null,
    'existing' => [],
    'error' => null,
    'maxFiles' => 10,
])

<div
    data-dropzone
    data-max-size-mb="{{ $maxSize }}"
    data-min-width="{{ $minWidth }}"
    data-min-height="{{ $minHeight }}"
    data-max-files="{{ $maxFiles }}"
    data-multiple="{{ $multiple ? 'true' : 'false' }}"
    class="space-y-3"
>
    <span class="label">{{ $label }}</span>

    <label
        for="dropzone-{{ $name }}"
        data-dropzone-area
        class="dropzone"
    >
        <input
            id="dropzone-{{ $name }}"
            type="file"
            name="{{ $name }}{{ $multiple ? '[]' : '' }}"
            accept="{{ $accept }}"
            @if ($multiple) multiple @endif
            class="sr-only"
            data-dropzone-input
        >
        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-brand-50 text-brand-700">
            <x-ui.icon name="upload" class="h-5 w-5" />
        </span>
        <span class="mt-3 text-sm font-medium">Drop files here or click to browse</span>
        <span class="mt-1 text-xs text-muted">
            {{ $multiple ? 'Up to 10 images' : 'One image' }} · Max {{ $maxSize }} MB each
            @if ($minWidth && $minHeight)
                · Min {{ $minWidth }}×{{ $minHeight }} px
            @endif
        </span>
    </label>

    <div class="grid gap-3 sm:grid-cols-3" data-dropzone-preview></div>

    @if (! empty($existing))
        <div>
            <p class="help">Currently saved</p>
            <div class="mt-2 grid gap-3 sm:grid-cols-3">
                @foreach ($existing as $image)
                    <figure class="overflow-hidden rounded-md border border-border bg-white">
                        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? $label }}" class="h-28 w-full object-cover">
                        @if (! empty($image['caption']))
                            <figcaption class="border-t border-border px-3 py-2 text-xs text-muted">{{ $image['caption'] }}</figcaption>
                        @endif
                    </figure>
                @endforeach
            </div>
        </div>
    @endif

    <p class="dropzone-error hidden text-sm text-danger" data-dropzone-error></p>
    @if ($hint)
        <p class="help">{{ $hint }}</p>
    @endif
    @if ($error)
        <p class="error">{{ $error }}</p>
    @endif
</div>
