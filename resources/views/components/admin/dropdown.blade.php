@props(['align' => 'right', 'width' => 'w-48'])

<div x-data="{ open: false }" @click.away="open = false" {{ $attributes->merge(['class' => 'relative inline-block']) }}>
    {{-- Trigger --}}
    <div @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </div>
    {{-- Menu --}}
    <div x-show="open" x-transition
         class="absolute {{ $align === 'right' ? 'left-0 sm:left-auto sm:right-0' : 'left-0' }} mt-2 {{ $width }} bg-surface-raised rounded-xl border border-border shadow-lg py-1 z-50 max-h-[70vh] overflow-y-auto">
        {{ $slot }}
    </div>
</div>
