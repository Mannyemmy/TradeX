@php
    $score = Auth::user()->signal_strength_score ?? 0;
    if ($score >= 50) {
        $tierLabel = 'Strong Signal';
        $tierColor = 'gain';
        $tierMessage = 'Strong signal strength! Optimal conditions for trading opportunities.';
        $tierEmoji = "\xF0\x9F\x9A\x80";
    } elseif ($score >= 25) {
        $tierLabel = 'Moderate';
        $tierColor = 'warning';
        $tierMessage = 'Moderate signal strength. Proceed with caution on new positions.';
        $tierEmoji = "\xE2\x9A\xA1";
    } else {
        $tierLabel = 'Weak Signal';
        $tierColor = 'loss';
        $tierMessage = 'Weak signal strength. Consider waiting for better conditions.';
        $tierEmoji = "\xE2\x9A\xA0\xEF\xB8\x8F";
    }
@endphp

<div class="bg-surface-raised border border-surface-border rounded-xl p-5">
    {{-- Header Row: Icon + Title + Score + Badge --}}
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-3">
            <x-icon name="signal" class="w-6 h-6 text-content-secondary" />
            <h3 class="text-base font-bold text-content-primary leading-tight">Trading Signal<br>Strength</h3>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-3xl font-bold text-content-primary">{{ $score }}%</span>
            <span class="px-3 py-1.5 rounded-lg text-xs font-bold bg-{{ $tierColor }}/10 text-{{ $tierColor }}">
                {{ $tierLabel }}
            </span>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div class="w-full h-3 rounded-full bg-surface-overlay overflow-hidden mb-2">
        <div class="h-full rounded-full transition-all duration-500"
             style="width: {{ $score }}%; background: linear-gradient(90deg, #22C55E, #10B981);"></div>
    </div>

    {{-- Scale Labels --}}
    <div class="flex items-center justify-between text-xs text-content-secondary mb-4">
        <span>0% Weak</span>
        <span>25% Moderate</span>
        <span>50%+ Strong</span>
    </div>

    {{-- Tier Message --}}
    <p class="text-sm text-content-secondary text-center">
        {{ $tierEmoji }} {{ $tierMessage }}
    </p>
</div>
