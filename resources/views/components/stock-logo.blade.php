@props([
    'asset',
    'size' => 'h-10 w-10',
    'shape' => 'rounded-full',
    'textSize' => 'text-xs',
    'background' => 'bg-primary/10',
    'textColor' => 'text-primary',
    'border' => '',
])

@php
    $symbol = strtoupper(trim((string) $asset->symbol));
    $fallbackLogo = 'https://static2.finnhub.io/file/publicdatany/finnhubimage/stock_logo/' . rawurlencode($symbol) . '.png';
@endphp

<span class="relative inline-flex {{ $size }} {{ $shape }} shrink-0 items-center justify-center overflow-hidden {{ $background }} font-bold {{ $textColor }} {{ $textSize }} {{ $border }}">
    <span aria-hidden="true">{{ substr($symbol, 0, 2) }}</span>
    <img src="{{ $asset->logo_url ?: $fallbackLogo }}"
         @if($asset->logo_url) data-fallback-logo="{{ $fallbackLogo }}" @endif
         alt="{{ $symbol }}"
         loading="lazy"
         class="absolute inset-0 h-full w-full object-cover"
         onerror="if (this.dataset.fallbackLogo && this.src !== this.dataset.fallbackLogo) { this.src = this.dataset.fallbackLogo; this.dataset.fallbackLogo = ''; } else { this.remove(); }">
</span>
