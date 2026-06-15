@props(['id' => 'modal', 'title' => '', 'maxWidth' => 'max-w-lg'])

<div x-data="{ open: false }"
     @open-{{ $id }}.window="open = true"
     x-show="open" x-cloak
     {{ $attributes->merge(['class' => 'fixed inset-0 z-[60] flex items-center justify-center p-4']) }}>
    {{-- Backdrop --}}
    <div x-show="open" x-transition.opacity class="absolute inset-0 bg-surface-overlay/60" @click="open = false"></div>
    {{-- Container --}}
    <div x-show="open" x-transition class="relative w-full {{ $maxWidth }} bg-surface-card rounded-2xl shadow-xl overflow-hidden">
        {{-- Header --}}
        @if($title)
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-content">{{ $title }}</h3>
                <button @click="open = false" class="text-content-muted hover:text-content transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        @endif
        {{-- Body --}}
        <div class="px-6 py-5">
            {{ $slot }}
        </div>
        {{-- Footer --}}
        @if(isset($footer))
            <div class="px-6 py-4 border-t border-border flex justify-end gap-3">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>
