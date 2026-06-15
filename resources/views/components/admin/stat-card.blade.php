@props(['label', 'value', 'icon' => null, 'trend' => null, 'trendUp' => true])

<div class="bg-surface-card rounded-xl border border-border shadow-card p-5">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-xs font-medium text-content-muted uppercase tracking-wide">{{ $label }}</p>
            <p class="text-stat text-content mt-3">{{ $value }}</p>
            @if($trend)
                <p class="mt-2 text-xs font-medium {{ $trendUp ? 'text-success' : 'text-danger' }}">
                    @if($trendUp)
                        <svg class="w-3 h-3 inline" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5l15-15m0 0H8.25m11.25 0v11.25" /></svg>
                    @else
                        <svg class="w-3 h-3 inline" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 4.5l15 15m0 0V8.25m0 11.25H8.25" /></svg>
                    @endif
                    {{ $trend }}
                </p>
            @endif
        </div>
        @if($icon)
            <div class="w-10 h-10 rounded-lg bg-primary-light flex items-center justify-center shrink-0">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
