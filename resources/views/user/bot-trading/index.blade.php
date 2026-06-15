@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')
    @include('user.partials.page-header', ['title' => 'Bot Trading', 'subtitle' => 'Subscribe to automated trading bots and earn passive profits'])

    {{-- Tabs --}}
    <div x-data="{ tab: 'bots' }">
        <div class="flex items-center gap-1 border-b border-surface-border mb-6">
            <button @click="tab = 'bots'" :class="tab === 'bots' ? 'border-b-2 border-primary text-primary font-medium' : 'text-content-tertiary hover:text-content-secondary'" class="px-4 py-2.5 text-sm transition-colors">
                Available Bots
            </button>
            <button @click="tab = 'subscriptions'" :class="tab === 'subscriptions' ? 'border-b-2 border-primary text-primary font-medium' : 'text-content-tertiary hover:text-content-secondary'" class="px-4 py-2.5 text-sm transition-colors">
                My Subscriptions
                @if($subscriptions->where('status', 'active')->count() > 0)
                    <span class="ml-1 bg-primary/20 text-primary text-xs px-1.5 py-0.5 rounded-full">{{ $subscriptions->where('status', 'active')->count() }}</span>
                @endif
            </button>
        </div>

        {{-- Tab 1: Available Bots --}}
        <div x-show="tab === 'bots'">
            @if($bots->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($bots as $bot)
                        <a href="{{ route('botTrading.bot', $bot->id) }}" class="group block bg-surface-raised border border-surface-border rounded-xl p-5 hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5 transition-all duration-200">
                            {{-- Bot Identity --}}
                            <div class="flex items-center gap-3.5 mb-4">
                                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center ring-2 ring-surface-border group-hover:ring-primary/40 transition-all">
                                    <x-icon name="cpu-chip" class="w-6 h-6 text-primary" />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-semibold text-content-primary truncate group-hover:text-primary transition-colors">{{ $bot->name }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        @php
                                            $strategyColors = ['scalping' => 'bg-warning/10 text-warning', 'day_trading' => 'bg-info/10 text-info', 'swing' => 'bg-primary/10 text-primary'];
                                        @endphp
                                        <span class="{{ $strategyColors[$bot->strategy_type] ?? 'bg-surface-overlay text-content-tertiary' }} text-[10px] font-medium px-1.5 py-0.5 rounded">{{ $bot->strategy_label }}</span>
                                        <span class="text-content-tertiary text-[10px]">{{ $bot->subscribers_count }} subscribers</span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-base font-bold text-gain">{{ number_format($bot->expected_roi, 2) }}%</p>
                                    <p class="text-[10px] text-content-tertiary">daily ROI</p>
                                </div>
                            </div>

                            {{-- Stats Row --}}
                            <div class="grid grid-cols-4 gap-3 py-3 border-t border-surface-border">
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-content-primary">{{ $bot->max_duration_days }}d</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Max Duration</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gain">{{ number_format($bot->win_rate) }}%</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Win Rate</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-content-primary">@money($bot->min_investment)</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Min Invest</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-content-primary">{{ $bot->trade_interval_minutes }}m</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Trade Interval</p>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <div class="mt-3 flex items-center justify-center gap-1.5 py-2 rounded-lg bg-primary/10 text-primary text-xs font-semibold group-hover:bg-primary group-hover:text-content-inverse transition-all duration-200">
                                View Bot
                                <svg class="w-3.5 h-3.5 opacity-0 -ml-1 group-hover:opacity-100 group-hover:ml-0 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $bots->links() }}</div>
            @else
                <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
                    <x-icon name="cpu-chip" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                    <p class="text-content-secondary">No trading bots available at the moment.</p>
                </div>
            @endif
        </div>

        {{-- Tab 2: My Subscriptions --}}
        <div x-show="tab === 'subscriptions'" x-cloak>
            @if($subscriptions->count() > 0)
                <div class="space-y-4">
                    @foreach($subscriptions as $sub)
                        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                                    <x-icon name="cpu-chip" class="w-5 h-5 text-primary" />
                                </div>
                                <div>
                                    <h5 class="font-semibold text-content-primary">{{ $sub->tradingBot->name ?? 'Deleted Bot' }}</h5>
                                    @if($sub->tradingBot)
                                        @php
                                            $strategyColors = ['scalping' => 'bg-warning/10 text-warning', 'day_trading' => 'bg-info/10 text-info', 'swing' => 'bg-primary/10 text-primary'];
                                        @endphp
                                        <span class="{{ $strategyColors[$sub->tradingBot->strategy_type] ?? 'bg-surface-overlay text-content-tertiary' }} text-xs px-2 py-0.5 rounded-full">{{ $sub->tradingBot->strategy_label }}</span>
                                    @endif
                                </div>
                                <div class="ml-auto">
                                    @php
                                        $statusClass = match($sub->status) {
                                            'active' => 'bg-gain/10 text-gain',
                                            'stopped' => 'bg-loss/10 text-loss',
                                            'completed' => 'bg-primary-subtle text-primary',
                                            'settled' => 'bg-warning/10 text-warning',
                                            default => 'bg-surface-overlay text-content-tertiary',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }} text-xs font-medium px-2.5 py-1 rounded-full">{{ ucfirst($sub->status) }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-4 gap-4 py-3 border-t border-surface-border">
                                <div>
                                    <p class="text-[10px] text-content-tertiary">Invested</p>
                                    <p class="text-sm font-semibold text-content-primary">@money($sub->invested_amount)</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-content-tertiary">Profit</p>
                                    <p class="text-sm font-semibold {{ $sub->accumulated_profit >= 0 ? 'text-gain' : 'text-loss' }}">@money($sub->accumulated_profit)</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-content-tertiary">Daily ROI</p>
                                    <p class="text-sm font-semibold text-gain">{{ number_format($sub->daily_roi_snapshot, 2) }}%</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-content-tertiary">Expires</p>
                                    <p class="text-sm text-content-primary">{{ $sub->expires_at ? $sub->expires_at->format('M d, Y') : '—' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 mt-4">
                                <a href="{{ route('botTrading.subscription', $sub->id) }}" class="text-xs font-medium text-primary hover:text-primary-dark transition-colors">View Details →</a>
                                @if($sub->status === 'active')
                                    <form action="{{ route('botTrading.stop', $sub->id) }}" method="POST" class="ml-auto" onsubmit="return confirm('Stop this subscription? Your balance (invested + profit) will be credited back.')">
                                        @csrf
                                        <button type="submit" class="text-xs font-medium text-loss hover:text-loss/80 transition-colors">Stop & Withdraw</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
                    <x-icon name="cpu-chip" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                    <p class="text-content-secondary mb-2">You haven't subscribed to any bots yet.</p>
                    <button @click="tab = 'bots'" class="text-sm font-medium text-primary hover:text-primary-dark transition-colors">Browse Available Bots →</button>
                </div>
            @endif
        </div>
    </div>

@endsection
