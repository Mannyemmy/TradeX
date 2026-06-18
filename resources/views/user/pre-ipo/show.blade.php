@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.page-header', ['title' => $company->name, 'subtitle' => $company->symbol . ' — ' . ($company->sector ?? 'Pre-IPO')])

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Company Detail --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Stats Row --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @include('user.partials.stat-card', ['label' => 'Share Price', 'value' => \App\Helpers\CurrencyHelper::formatForUser($company->share_price), 'icon' => 'banknotes'])
                @include('user.partials.stat-card', ['label' => 'Initial Price', 'value' => \App\Helpers\CurrencyHelper::formatForUser($company->initial_price), 'icon' => 'clock'])
                @include('user.partials.stat-card', ['label' => 'Change', 'value' => ($company->price_change_percent >= 0 ? '+' : '') . $company->price_change_percent . '%', 'icon' => 'chart-bar'])
                @include('user.partials.stat-card', ['label' => 'Available', 'value' => number_format($company->shares_remaining), 'icon' => 'chart-pie'])
            </div>

            {{-- Company Description --}}
            @if($company->description)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-content-primary mb-2">About {{ $company->name }}</h3>
                    <p class="text-sm text-content-secondary leading-relaxed">{{ $company->description }}</p>
                </div>
            @endif

            {{-- Price History Chart --}}
            @if($priceHistory->count() > 1)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-content-primary mb-3">Price History</h3>
                    <div style="height: 250px;">
                        <canvas id="priceChart"></canvas>
                    </div>
                </div>
            @endif

            {{-- Company Info --}}
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <h3 class="text-sm font-semibold text-content-primary mb-3">Details</h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-content-tertiary text-xs">Sector</p>
                        <p class="text-content-primary">{{ $company->sector ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-content-tertiary text-xs">Total Shares</p>
                        <p class="text-content-primary">{{ number_format($company->total_shares) }}</p>
                    </div>
                    <div>
                        <p class="text-content-tertiary text-xs">Shares Sold</p>
                        <p class="text-content-primary">{{ number_format($company->shares_sold) }}</p>
                    </div>
                    <div>
                        <p class="text-content-tertiary text-xs">Min Purchase</p>
                        <p class="text-content-primary">{{ $company->min_shares }} share{{ $company->min_shares > 1 ? 's' : '' }}</p>
                    </div>
                    @if($company->max_shares_per_user)
                        <div>
                            <p class="text-content-tertiary text-xs">Max Per User</p>
                            <p class="text-content-primary">{{ number_format($company->max_shares_per_user) }}</p>
                        </div>
                    @endif
                    @if($company->expected_ipo_date)
                        <div>
                            <p class="text-content-tertiary text-xs">Expected IPO</p>
                            <p class="text-content-primary">{{ $company->expected_ipo_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-content-tertiary text-xs">Status</p>
                        @php
                            $statusColors = [
                                'upcoming' => 'bg-info/10 text-info',
                                'open' => 'bg-gain/10 text-gain',
                                'closed' => 'bg-warning/10 text-warning',
                                'ipo' => 'bg-primary/10 text-primary',
                                'public' => 'bg-gain/10 text-gain',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$company->status] }}">
                            {{ ucfirst($company->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Buy Form + My Position --}}
        <div class="space-y-5">

            {{-- Buy Form --}}
            @if($company->status === 'open')
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5" x-data="{ qty: {{ $company->min_shares }}, price: {{ Auth::user()->convertToUserCurrency($company->share_price) }} }">
                    <h3 class="text-sm font-semibold text-content-primary mb-3">Buy Shares</h3>
                    <form action="{{ route('user.pre-ipo.buy', $company->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs text-content-tertiary block mb-1">Quantity</label>
                            <input type="number" name="quantity" x-model.number="qty" min="{{ $company->min_shares }}" max="{{ $company->max_shares_per_user ?? $company->shares_remaining }}" required
                                   class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div class="flex items-center justify-between py-2 border-t border-surface-border">
                            <span class="text-xs text-content-tertiary">Price per Share</span>
                            <span class="text-sm font-medium text-content-primary">@money($company->share_price)</span>
                        </div>
                        <div class="flex items-center justify-between pb-2">
                            <span class="text-xs text-content-tertiary">Total Cost</span>
                            <span class="text-sm font-bold text-primary" x-text="'@userCurrency' + (qty * price).toFixed(2)"></span>
                        </div>
                        <div class="text-xs text-content-tertiary">
                            Available Balance: <span class="text-content-primary font-medium">@money(Auth::user()->available_bal)</span>
                        </div>
                        <button type="submit"
                                class="w-full bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 text-sm font-medium transition-colors">
                            Buy Shares
                        </button>
                    </form>
                </div>
            @elseif($company->status === 'upcoming')
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5 text-center">
                    <div class="text-info mb-2">
                        <x-icon name="clock" class="w-8 h-8 mx-auto" />
                    </div>
                    <p class="text-sm text-content-primary font-medium">Coming Soon</p>
                    <p class="text-xs text-content-tertiary mt-1">This offering is not yet open for purchases.</p>
                </div>
            @elseif($company->status === 'closed')
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5 text-center">
                    <p class="text-sm text-warning font-medium">Pre-IPO Round Closed</p>
                    <p class="text-xs text-content-tertiary mt-1">This offering is no longer accepting new investments.</p>
                </div>
            @elseif(in_array($company->status, ['ipo', 'public']))
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5 text-center">
                    <p class="text-sm text-gain font-medium">
                        {{ $company->status === 'ipo' ? 'Going Public' : 'Now Public' }}
                    </p>
                    <p class="text-xs text-content-tertiary mt-1">
                        {{ $company->status === 'ipo' ? 'This company is transitioning to public markets.' : 'This company is now publicly traded.' }}
                    </p>
                </div>
            @endif

            {{-- My Position --}}
            @if($holding)
                <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-content-primary mb-3">My Position</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-content-tertiary">Shares Held</span>
                            <span class="text-content-primary font-medium">{{ number_format($holding->shares) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-content-tertiary">Avg Cost</span>
                            <span class="text-content-primary">@money($holding->purchase_price)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-content-tertiary">Total Invested</span>
                            <span class="text-content-primary">@money($holding->total_cost)</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-content-tertiary">Current Value</span>
                            <span class="text-content-primary font-medium">@money($holding->current_value)</span>
                        </div>
                        <div class="flex justify-between border-t border-surface-border pt-2">
                            <span class="text-content-tertiary">Unrealized P/L</span>
                            <span class="font-medium {{ $holding->unrealized_pnl >= 0 ? 'text-gain' : 'text-loss' }}">
                                {{ $holding->unrealized_pnl >= 0 ? '+' : '' }}@money($holding->unrealized_pnl)
                                ({{ $holding->unrealized_pnl_percent >= 0 ? '+' : '' }}{{ $holding->unrealized_pnl_percent }}%)
                            </span>
                        </div>
                    </div>

                    {{-- Sell form (post-IPO only) --}}
                    @if($company->status === 'public')
                        <div class="mt-4 pt-4 border-t border-surface-border" x-data="{ sellQty: 1, currentPrice: {{ Auth::user()->convertToUserCurrency($company->current_price) }} }">
                            <form action="{{ route('user.pre-ipo.sell', $holding->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="text-xs text-content-tertiary block mb-1">Sell Quantity</label>
                                    <input type="number" name="quantity" x-model.number="sellQty" min="1" max="{{ $holding->shares }}" required
                                           class="w-full bg-surface-overlay border border-surface-border rounded-lg px-3 py-2.5 text-sm text-content-primary focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="text-content-tertiary">Proceeds</span>
                                    <span class="text-gain font-medium" x-text="'@userCurrency' + (sellQty * currentPrice).toFixed(2)"></span>
                                </div>
                                <button type="submit"
                                        class="w-full bg-loss/10 text-loss border border-loss/20 hover:bg-loss/20 rounded-lg py-2.5 text-sm font-medium transition-colors">
                                    Sell Shares
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            <a href="{{ route('user.pre-ipo.index') }}"
               class="block text-center text-sm text-content-secondary hover:text-primary transition-colors">
                ← Back to Listings
            </a>
        </div>

    </div>

    {{-- Chart.js --}}
    @if($priceHistory->count() > 1)
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        new Chart(document.getElementById('priceChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($priceHistory->pluck('created_at')->map(fn($d) => $d->format('M d'))) !!},
                datasets: [{
                    label: 'Price ($)',
                    data: {!! json_encode($priceHistory->pluck('price')) !!},
                    borderColor: '#2E5C8A',
                    backgroundColor: 'rgba(5, 150, 105, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3,
                    pointBackgroundColor: '#2E5C8A',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: 'rgba(255,255,255,0.5)', callback: v => '@userCurrency' + v }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: 'rgba(255,255,255,0.5)' }
                    }
                }
            }
        });
    </script>
    @endpush
    @endif

@endsection
