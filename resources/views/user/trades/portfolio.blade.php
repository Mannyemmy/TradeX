@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />
    <x-alert />

    {{-- Ticker + Quick Nav --}}
    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Portfolio', 'subtitle' => 'Consolidated view of all your assets and positions'])

    @php
        $tabLabels = [
            'overview'     => ['label' => 'Overview',      'icon' => 'squares-2x2'],
            'trading'      => ['label' => 'Trading',       'icon' => 'chart-bar'],
            'investments'  => ['label' => 'Investments',   'icon' => 'banknotes'],
            'copy_trading' => ['label' => 'Copy Trading',  'icon' => 'copy'],
            'bot_trading'  => ['label' => 'Bot Trading',   'icon' => 'cpu-chip'],
            'pre_ipo'      => ['label' => 'Pre-IPO',       'icon' => 'building-office'],
            'stocktrading' => ['label' => 'Stocks',         'icon' => 'chart-bar'],
            'nfts'         => ['label' => 'NFTs',          'icon' => 'gem'],
            'loans'        => ['label' => 'Loans',         'icon' => 'hand-raised'],
        ];
    @endphp

    {{-- Portfolio Hero --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-6 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Net Worth</span>
                <span class="text-2xl font-bold text-content-primary">@money($netWorth)</span>
                <span class="text-xs text-content-tertiary block mt-0.5">All assets combined</span>
            </div>
            <div>
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Total Invested</span>
                <span class="text-xl font-bold text-content-primary">@money($totalInvested)</span>
                <span class="text-xs text-content-tertiary block mt-0.5">Across all modules</span>
            </div>
            <div>
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Total P/L</span>
                <span class="text-xl font-bold {{ $totalPL >= 0 ? 'text-gain' : 'text-loss' }}">
                    @money($totalPL)
                </span>
                <span class="text-xs text-content-tertiary block mt-0.5">Realized + unrealized</span>
            </div>
            <div>
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Account Balance</span>
                <span class="text-xl font-bold text-content-primary">@money($user->account_bal ?? 0)</span>
                <span class="text-xs text-content-tertiary block mt-0.5">Available cash</span>
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'overview' }">
        {{-- Tab Bar --}}
        <div class="mb-6 -mx-1 overflow-x-auto scrollbar-none">
            <div class="flex items-center gap-1 min-w-max px-1" role="tablist" aria-label="Portfolio sections">
                @foreach($tabLabels as $key => $meta)
                    @if(!empty($tabs[$key]))
                        <button @click="tab = '{{ $key }}'"
                                :class="tab === '{{ $key }}' ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:text-content-primary hover:bg-surface-overlay/80'"
                                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium rounded-lg transition-colors whitespace-nowrap"
                                role="tab"
                                :aria-selected="tab === '{{ $key }}' ? 'true' : 'false'">
                            <x-icon name="{{ $meta['icon'] }}" class="w-4 h-4" />
                            {{ $meta['label'] }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Tab Panels --}}
        @if(!empty($tabs['overview']))
            <div x-show="tab === 'overview'" x-cloak>
                @include('user.trades.partials.portfolio-tab-overview')
            </div>
        @endif

        @if(!empty($tabs['trading']))
            <div x-show="tab === 'trading'" x-cloak>
                @include('user.trades.partials.portfolio-tab-trading')
            </div>
        @endif

        @if(!empty($tabs['investments']))
            <div x-show="tab === 'investments'" x-cloak>
                @include('user.trades.partials.portfolio-tab-investments')
            </div>
        @endif

        @if(!empty($tabs['copy_trading']))
            <div x-show="tab === 'copy_trading'" x-cloak>
                @include('user.trades.partials.portfolio-tab-copy-trading')
            </div>
        @endif

        @if(!empty($tabs['bot_trading']))
            <div x-show="tab === 'bot_trading'" x-cloak>
                @include('user.trades.partials.portfolio-tab-bot-trading')
            </div>
        @endif

        @if(!empty($tabs['pre_ipo']))
            <div x-show="tab === 'pre_ipo'" x-cloak>
                @include('user.trades.partials.portfolio-tab-pre-ipo')
            </div>
        @endif

        @if(!empty($tabs['stocktrading']))
            <div x-show="tab === 'stocktrading'" x-cloak>
                @include('user.trades.partials.portfolio-tab-stocks')
            </div>
        @endif

        @if(!empty($tabs['nfts']))
            <div x-show="tab === 'nfts'" x-cloak>
                @include('user.trades.partials.portfolio-tab-nfts')
            </div>
        @endif

        @if(!empty($tabs['loans']))
            <div x-show="tab === 'loans'" x-cloak>
                @include('user.trades.partials.portfolio-tab-loans')
            </div>
        @endif
    </div>

@endsection
