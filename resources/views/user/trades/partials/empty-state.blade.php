{{--
    Empty State Partial
    Usage: @include('user.trades.partials.empty-state', [
        'icon' => 'chart-bar',           // Heroicon name
        'title' => 'No trades found',   
        'message' => 'You haven\'t placed any trades yet.',
        'actionUrl' => route('trade'),   // optional
        'actionLabel' => 'Place a Trade' // optional
    ])
--}}
@php
    $icon = $icon ?? 'chart-bar';
    $title = $title ?? 'No data found';
    $message = $message ?? 'There\'s nothing to show here yet.';
    $actionUrl = $actionUrl ?? null;
    $actionLabel = $actionLabel ?? 'Get Started';
@endphp

<div class="flex flex-col items-center justify-center py-12 px-6 text-center" role="status">
    <div class="p-4 rounded-full bg-surface-overlay mb-4">
        @include("components.icons.{$icon}", ['class' => 'w-10 h-10 text-content-tertiary'])
    </div>
    <h3 class="text-base font-semibold text-content-primary mb-1">{{ $title }}</h3>
    <p class="text-sm text-content-tertiary max-w-sm mb-4">{{ $message }}</p>
    @if($actionUrl)
        <a href="{{ $actionUrl }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface-base">
            @include("components.icons.{$icon}", ['class' => 'w-4 h-4'])
            {{ $actionLabel }}
        </a>
    @endif
</div>
