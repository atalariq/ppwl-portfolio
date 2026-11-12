@php
    $icons = ['mail', 'message-circle', 'github', 'linkedin', 'globe', 'instagram', 'link'];
@endphp

<div class="card card-body space-y-5">
    <div class="grid gap-5 sm:grid-cols-2">
        <x-ui.field label="Label" name="label" :error="$errors->first('label')" hint="For example: Email, GitHub, LinkedIn.">
            <input id="label" class="input" type="text" name="label" value="{{ old('label', $contact?->label) }}" required>
        </x-ui.field>
        <x-ui.field label="Icon" name="icon" :error="$errors->first('icon')">
            <select id="icon" name="icon" class="select">
                @foreach ($icons as $icon)
                    <option value="{{ $icon }}" @selected(old('icon', $contact?->icon ?? 'link') === $icon)>{{ $icon }}</option>
                @endforeach
            </select>
        </x-ui.field>
    </div>

    <x-ui.field label="Display value" name="value" :error="$errors->first('value')" hint="The text visitors see, for example atalariq.dev@outlook.com.">
        <input id="value" class="input" type="text" name="value" value="{{ old('value', $contact?->value) }}" required>
    </x-ui.field>

    <x-ui.field label="URL" name="url" :error="$errors->first('url')" hint="Use https:// for websites, mailto: for email, or tel: for phone links.">
        <input id="url" class="input" type="text" name="url" value="{{ old('url', $contact?->url) }}" required placeholder="https://…">
    </x-ui.field>

    <x-ui.field label="Sort order" name="sort_order" :error="$errors->first('sort_order')" hint="Lower numbers appear first.">
        <input id="sort_order" class="input" type="number" min="0" max="999" name="sort_order" value="{{ old('sort_order', $contact?->sort_order ?? 10) }}">
    </x-ui.field>
</div>

<div class="flex items-center gap-3">
    <x-ui.button type="submit">
        <x-ui.icon name="check" class="h-4 w-4" /> Save contact
    </x-ui.button>
    <x-ui.button :href="route('admin.about.edit')" variant="ghost">Cancel</x-ui.button>
</div>
