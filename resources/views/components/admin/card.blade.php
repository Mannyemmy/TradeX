@props(['padding' => 'p-6', 'hover' => false])

<div {{ $attributes->merge([
    'class' => 'bg-surface-card rounded-xl border border-border shadow-card ' . $padding
        . ($hover ? ' hover:shadow-card-hover hover:border-primary/20 transition-all duration-200' : '')
]) }}>
    {{ $slot }}
</div>
