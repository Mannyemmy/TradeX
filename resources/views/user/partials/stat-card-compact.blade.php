{{--
    Compact Stat Card Partial
    Usage: @include('user.partials.stat-card-compact', ['label' => 'Total Trades', 'value' => 12, 'icon' => 'chart-bar', 'color' => 'info'])
    Horizontal layout, smaller footprint — ideal for secondary metrics in 2-col grids.
--}}
@php
    $label = $label ?? '';
    $value = $value ?? '';
    $icon  = $icon  ?? 'chart-bar';
    $color = $color ?? 'primary';

    $colorMap = [
        'primary' => ['bg' => 'bg-primary-subtle', 'icon' => 'text-primary'],
        'gain'    => ['bg' => 'bg-gain/10', 'icon' => 'text-gain'],
        'loss'    => ['bg' => 'bg-loss/10', 'icon' => 'text-loss'],
        'warning' => ['bg' => 'bg-warning/10', 'icon' => 'text-warning'],
        'info'    => ['bg' => 'bg-info/10', 'icon' => 'text-info'],
    ];
    $c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div class="bg-surface-raised border border-surface-border rounded-xl p-3 hover:border-surface-border-light transition-colors flex items-center gap-3">
    <div class="p-2 rounded-lg {{ $c['bg'] }} shrink-0">
        @include("components.icons.{$icon}", ['class' => 'w-4 h-4 ' . $c['icon']])
    </div>
    <div class="min-w-0">
        <p class="text-xs text-content-tertiary font-medium uppercase tracking-wide truncate">{{ $label }}</p>
        <p class="text-lg font-bold text-content-primary">{{ $value }}</p>
        @if(!empty($subvalue))
            <p class="text-xs font-medium {{ ($color ?? 'primary') === 'gain' ? 'text-gain' : (($color ?? 'primary') === 'loss' ? 'text-loss' : 'text-content-secondary') }}">{{ $subvalue }}</p>
        @endif
    </div>
</div>
