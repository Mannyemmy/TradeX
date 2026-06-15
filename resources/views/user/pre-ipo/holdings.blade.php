@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'My Pre-IPO Holdings', 'subtitle' => 'Track your pre-IPO investments and sell post-IPO shares'])

    {{-- Portfolio Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        @include('user.partials.stat-card', ['label' => 'Total Invested', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalInvested), 'icon' => 'banknotes'])
        @include('user.partials.stat-card', ['label' => 'Current Value', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalCurrentValue), 'icon' => 'chart-bar'])
        @include('user.partials.stat-card', ['label' => 'Unrealized P/L', 'value' => ($totalPnl >= 0 ? '+' : '') . \App\Helpers\CurrencyHelper::formatForUser($totalPnl), 'icon' => 'chart-pie'])
    </div>

    {{-- Active Holdings (Pre-IPO) --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl">
        <div class="px-5 py-4 border-b border-surface-border">
            <h3 class="text-sm font-semibold text-content-primary">Active Holdings</h3>
            <p class="text-xs text-content-tertiary mt-0.5">Pre-IPO shares — sell becomes available after IPO</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-overlay/50">
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-left">Company</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Shares</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Avg Cost</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Current Price</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Current Value</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">P/L</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeHoldings as $holding)
                        <tr class="border-b border-surface-border last:border-0 hover:bg-surface-overlay/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('user.pre-ipo.show', $holding->pre_ipo_company_id) }}" class="flex items-center gap-3 group">
                                    @if($holding->company->logo)
                                        <img src="{{ asset('storage/app/public/' . $holding->company->logo) }}" alt="" class="w-8 h-8 rounded-lg object-cover border border-surface-border">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ substr($holding->company->symbol, 0, 2) }}</div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-content-primary group-hover:text-primary transition-colors">{{ $holding->company->name }}</p>
                                        <p class="text-xs text-content-tertiary font-mono">{{ $holding->company->symbol }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-sm font-medium text-content-primary text-right">{{ number_format($holding->shares) }}</td>
                            <td class="px-5 py-3.5 text-sm text-content-secondary text-right">@money($holding->purchase_price)</td>
                            <td class="px-5 py-3.5 text-sm text-content-secondary text-right">@money($holding->company->current_price)</td>
                            <td class="px-5 py-3.5 text-sm font-medium text-content-primary text-right">@money($holding->current_value)</td>
                            <td class="px-5 py-3.5 text-sm font-medium text-right {{ $holding->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                {{ $holding->unrealized_pnl >= 0 ? '+' : '' }}@money($holding->unrealized_pnl)
                                <span class="text-xs">({{ $holding->unrealized_pnl_percent >= 0 ? '+' : '' }}{{ $holding->unrealized_pnl_percent }}%)</span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gain/10 text-gain">Active</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-content-tertiary text-sm">
                                No active pre-IPO holdings.
                                <a href="{{ route('user.pre-ipo.index') }}" class="text-primary hover:underline">Browse offerings</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Converted Holdings (Post-IPO) --}}
    @if($convertedHoldings->count() > 0)
    <div class="bg-surface-raised border border-surface-border rounded-xl">
        <div class="px-5 py-4 border-b border-surface-border">
            <h3 class="text-sm font-semibold text-content-primary">Converted Holdings</h3>
            <p class="text-xs text-content-tertiary mt-0.5">Post-IPO shares — sell at live market price</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-overlay/50">
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-left">Company</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Shares</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Avg Cost</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Live Price</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">Current Value</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-right">P/L</th>
                        <th class="px-5 py-3 text-xs font-medium text-content-tertiary uppercase tracking-wide text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($convertedHoldings as $holding)
                        <tr class="border-b border-surface-border last:border-0 hover:bg-surface-overlay/30 transition-colors">
                            <td class="px-5 py-3.5">
                                <a href="{{ route('user.pre-ipo.show', $holding->pre_ipo_company_id) }}" class="flex items-center gap-3 group">
                                    @if($holding->company->logo)
                                        <img src="{{ asset('storage/app/public/' . $holding->company->logo) }}" alt="" class="w-8 h-8 rounded-lg object-cover border border-surface-border">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ substr($holding->company->symbol, 0, 2) }}</div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-content-primary group-hover:text-primary transition-colors">{{ $holding->company->name }}</p>
                                        <p class="text-xs text-content-tertiary font-mono">{{ $holding->company->symbol }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-5 py-3.5 text-sm font-medium text-content-primary text-right">{{ number_format($holding->shares) }}</td>
                            <td class="px-5 py-3.5 text-sm text-content-secondary text-right">@money($holding->purchase_price)</td>
                            <td class="px-5 py-3.5 text-sm text-content-secondary text-right">@money($holding->company->current_price)</td>
                            <td class="px-5 py-3.5 text-sm font-medium text-content-primary text-right">@money($holding->current_value)</td>
                            <td class="px-5 py-3.5 text-sm font-medium text-right {{ $holding->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                {{ $holding->unrealized_pnl >= 0 ? '+' : '' }}@money($holding->unrealized_pnl)
                            </td>
                            <td class="px-5 py-3.5 text-center" x-data="{ showSell: false }">
                                <button @click="showSell = !showSell"
                                        class="bg-loss/10 text-loss border border-loss/20 hover:bg-loss/20 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                    Sell
                                </button>
                                <div x-show="showSell" x-cloak class="mt-2 text-left">
                                    <form action="{{ route('user.pre-ipo.sell', $holding->id) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <input type="number" name="quantity" min="1" max="{{ $holding->shares }}" value="1" required
                                               class="w-20 bg-surface-overlay border border-surface-border rounded-lg px-2 py-1.5 text-xs text-content-primary focus:outline-none focus:ring-1 focus:ring-primary">
                                        <button type="submit" class="bg-loss text-white rounded-lg px-3 py-1.5 text-xs font-medium hover:bg-loss/90 transition-colors">Confirm</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('user.pre-ipo.index') }}" class="text-sm text-primary hover:underline">Browse Pre-IPO Offerings →</a>
    </div>

@endsection
