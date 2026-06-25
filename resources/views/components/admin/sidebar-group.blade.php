@props(['label', 'icon' => '', 'active' => false, 'id', 'badge' => null])

<div x-data="{ expanded: {{ $active ? 'true' : 'false' }} }">
    {{-- Group Trigger --}}
    <button @click="expanded = !expanded"
            class="mx-3 w-[calc(100%-1.5rem)] flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors cursor-pointer
                   {{ $active ? 'bg-sidebar-active text-primary font-medium' : 'text-sidebar-text hover:bg-sidebar-hover hover:text-content' }}">
        @if($icon)
            {!! $icon !!}
        @endif
        <span class="flex-1 text-left">{{ $label }}</span>
        @if($badge !== null)
            <span class="bg-danger/20 text-danger text-[0.6rem] font-semibold px-1.5 py-0.5 rounded-full">{{ $badge }}</span>
        @endif
        <svg class="w-4 h-4 transition-transform duration-200" :class="expanded && 'rotate-90'" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
    </button>
    {{-- Sub Items --}}
    <div x-show="expanded" x-collapse class="mt-0.5">
        {{ $slot }}
    </div>
</div>
