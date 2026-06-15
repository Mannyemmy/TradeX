{{--
    Asset Cell Partial — Consistent asset display for table rows
    Usage: @include('user.trades.partials.asset-cell', ['trade' => $trade])
    Expects $trade with optional tradingAsset relationship loaded.
--}}
@php
    $asset = $trade->tradingAsset ?? null;
    $logoUrl = $asset->logo_url ?? null;
    $symbol = $asset ? $asset->symbol : ($trade->asset_name ?? '—');
    $name = $asset ? $asset->name : '';
@endphp

<div class="flex items-center gap-2.5 min-w-0">
    @if($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $symbol }}" class="w-6 h-6 rounded-full shrink-0 bg-surface-overlay" loading="lazy" />
    @else
        <div class="w-6 h-6 rounded-full bg-surface-overlay flex items-center justify-center shrink-0">
            <span class="text-[10px] font-bold text-content-tertiary">{{ strtoupper(substr($symbol, 0, 2)) }}</span>
        </div>
    @endif
    <div class="min-w-0">
        <p class="text-sm font-semibold text-content-primary truncate" title="{{ $symbol }}{{ $name ? ' — ' . $name : '' }}">{{ $symbol }}</p>
        @if($name)
            <p class="text-xs text-content-tertiary truncate" title="{{ $name }}">{{ $name }}</p>
        @endif
    </div>
</div>
