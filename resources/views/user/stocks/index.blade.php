@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Stock Shares', 'subtitle' => 'Buy and sell fractional shares of real stocks'])

    {{-- Action Links --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.stocks.portfolio') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80 rounded-lg transition-colors">
            <x-icon name="chart-bar" class="w-4 h-4" /> My Portfolio
        </a>
        <a href="{{ route('user.stocks.history') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80 rounded-lg transition-colors">
            <x-icon name="clock" class="w-4 h-4" /> Trade History
        </a>
    </div>

    {{-- Search --}}
    <div x-data="{ search: '' }" class="space-y-6">
        <div class="relative">
            <input type="text" x-model="search" placeholder="Search stocks by name or symbol..."
                   class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 pl-10 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary" />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-tertiary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
        </div>

        {{-- Stock Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($stocks as $stock)
                <div x-show="!search || '{{ strtolower($stock->symbol . ' ' . $stock->name) }}'.includes(search.toLowerCase())"
                     class="bg-surface-raised border border-surface-border rounded-xl p-5 hover:border-primary/30 transition-colors">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            @if($stock->logo_url)
                                <img src="{{ $stock->logo_url }}" alt="{{ $stock->symbol }}" class="w-10 h-10 rounded-full object-cover bg-surface-overlay">
                            @else
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-sm">
                                    {{ substr($stock->symbol, 0, 2) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-sm font-semibold text-content-primary">{{ $stock->symbol }}</h3>
                                <p class="text-xs text-content-tertiary truncate max-w-[120px]">{{ $stock->name }}</p>
                            </div>
                        </div>
                        @if(isset($userPositions[$stock->id]))
                            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded bg-primary/10 text-primary">HOLDING</span>
                        @endif
                    </div>

                    <div class="flex items-end justify-between mb-4">
                        <div>
                            <span class="text-lg font-bold text-content-primary">@money($stock->price)</span>
                            @if($stock->price_change_pct_24h)
                                <span class="text-xs font-medium ml-1 {{ $stock->price_change_pct_24h >= 0 ? 'text-gain' : 'text-loss' }}">
                                    {{ $stock->price_change_pct_24h >= 0 ? '+' : '' }}{{ number_format($stock->price_change_pct_24h, 2) }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    @if(isset($userPositions[$stock->id]))
                        <p class="text-xs text-content-tertiary mb-3">You hold {{ number_format($userPositions[$stock->id], 4) }} shares</p>
                    @endif

                    <a href="{{ route('user.stocks.show', $stock->id) }}"
                       class="block w-full text-center bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2 text-sm font-medium transition-colors">
                        Trade
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <x-icon name="chart-bar" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                    <p class="text-content-secondary">No stocks available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection
