@props(['type' => 'neutral'])

@php
$classes = match($type) {
    'success', 'processed' => 'bg-success-light text-success',
    'danger', 'rejected', 'failed' => 'bg-danger-light text-danger',
    'warning', 'pending' => 'bg-warning-light text-warning',
    'info' => 'bg-info-light text-info',
    default => 'bg-surface-alt text-content-secondary',
};
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full $classes"]) }}>
    {{ $slot }}
</span>
