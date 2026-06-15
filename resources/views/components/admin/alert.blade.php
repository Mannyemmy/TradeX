@props(['type' => 'info', 'dismissible' => false])

@php
$classes = match($type) {
    'success' => 'bg-success-light border-success text-success',
    'danger' => 'bg-danger-light border-danger text-danger',
    'warning' => 'bg-warning-light border-warning text-warning',
    default => 'bg-info-light border-info text-info',
};
@endphp

<div {{ $attributes->merge(['class' => "border-l-4 rounded-lg p-4 flex items-start gap-3 $classes"]) }}
     @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>
    <div class="flex-1 text-sm">{{ $slot }}</div>
    @if($dismissible)
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    @endif
</div>
