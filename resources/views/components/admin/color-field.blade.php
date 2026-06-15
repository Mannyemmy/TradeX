@props(['label' => 'Color'])

<div x-data="{
    val: {{ $attributes->first('x-model') ? '' : "'#000000'" }},
    sync(e) { this.val = e.target.value; }
}" class="flex flex-col gap-1.5">
    <label class="text-xs font-medium text-content-secondary">{{ $label }}</label>
    <div class="flex items-center gap-2">
        <input type="color"
            {{ $attributes->whereStartsWith('x-model') }}
            @input="sync($event)"
            class="w-10 h-10 rounded-lg border border-border cursor-pointer bg-transparent p-0.5 shrink-0
                   [&::-webkit-color-swatch-wrapper]:p-0 [&::-webkit-color-swatch]:rounded-md [&::-webkit-color-swatch]:border-0
                   [&::-moz-color-swatch]:rounded-md [&::-moz-color-swatch]:border-0">
        <input type="text"
            {{ $attributes->whereStartsWith('x-model') }}
            maxlength="7"
            pattern="^#[0-9A-Fa-f]{6}$"
            class="flex-1 bg-surface-alt border border-border rounded-lg px-3 py-2 text-sm text-content font-mono
                   focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors"
            placeholder="#000000">
    </div>
</div>
