{{--
    P/L Display Partial — Profit/Loss value with sign, color, and optional percentage
    Usage: @include('user.trades.partials.pnl-display', ['value' => $trade->profit_loss, 'size' => 'sm'])
    Params:
        $value   — numeric P/L value (positive = profit, negative = loss, null = pending)
        $size    — 'sm' (default) | 'base' | 'lg'
        $showSign — bool (default true)
        $currency — string (default '$')
--}}
@php
    $value = $value ?? null;
    $size = $size ?? 'sm';
    $showSign = $showSign ?? true;
    $currency = $currency ?? ($userCurrencySymbol ?? '$');

    $sizeClass = match($size) {
        'lg' => 'text-xl font-bold',
        'base' => 'text-base font-semibold',
        default => 'text-sm font-medium',
    };

    if ($value === null) {
        $colorClass = 'text-content-tertiary';
        $display = '—';
    } elseif ($value >= 0) {
        $colorClass = 'text-gain';
        $sign = $showSign ? '+' : '';
        $display = $sign . $currency . number_format(abs($value), 2);
    } else {
        $colorClass = 'text-loss';
        $sign = $showSign ? '-' : '';
        $display = $sign . $currency . number_format(abs($value), 2);
    }
@endphp

<span class="{{ $sizeClass }} {{ $colorClass }}" aria-label="Profit and loss: {{ $display }}">
    {{ $display }}
</span>
