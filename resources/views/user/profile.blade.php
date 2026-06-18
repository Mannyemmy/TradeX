@extends('layouts.dash1')
@section('title', $title)
@section('content')

{{-- ── Alerts ── --}}
<x-danger-alert />
<x-success-alert />
<x-error-alert />

{{-- ── Ticker tape ── --}}
@include('user.partials.ticker-tape')

{{-- ── Quick nav ── --}}
@include('user.partials.quick-nav')

{{-- ── Page header ── --}}
@include('user.partials.page-header', ['title' => 'Account Settings', 'subtitle' => 'Manage your profile & security'])

{{-- ── Profile header card ── --}}
<div class="rounded-xl bg-surface-raised border border-surface-border p-6 mb-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
        {{-- Avatar --}}
        <div class="relative shrink-0">
            @if (Auth::user()->profile_photo_path)
                <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}"
                     alt="{{ Auth::user()->name }}"
                     class="w-20 h-20 rounded-full border-2 border-primary object-cover bg-surface-overlay">
            @else
                <div class="w-20 h-20 rounded-full border-2 border-primary bg-surface-overlay flex items-center justify-center">
                    <x-icon name="user-circle" class="w-12 h-12 text-content-tertiary" />
                </div>
            @endif
            @if ($settings->enable_kyc == 'yes' && Auth::user()->account_verify == 'Verified')
                <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-primary rounded-full flex items-center justify-center border-2 border-surface-raised">
                    @include('components.icons.check-circle', ['class' => 'w-4 h-4 text-content-inverse'])
                </div>
            @endif
        </div>

        {{-- Name & email --}}
        <div class="flex-1 min-w-0">
            <h2 class="text-xl font-bold text-content-primary">{{ Auth::user()->name }}</h2>
            <p class="text-sm text-content-secondary mt-0.5">{{ Auth::user()->email }}</p>
            @if ($settings->enable_kyc == 'yes')
            <div class="flex items-center gap-2 mt-2">
                @if (Auth::user()->account_verify == 'Verified')
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-gain/10 text-gain text-xs font-medium">
                        @include('components.icons.shield-check', ['class' => 'w-3.5 h-3.5'])
                        Verified
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-warning/10 text-warning text-xs font-medium">
                        @include('components.icons.exclamation-triangle', ['class' => 'w-3.5 h-3.5'])
                        Not Verified
                    </span>
                @endif
            </div>
            @endif
        </div>

        {{-- Balance summary --}}
        <div class="flex gap-6 text-center sm:text-right">
            <div>
                <p class="text-xs text-content-tertiary font-medium uppercase tracking-wide">Profit</p>
                <p class="text-lg font-bold text-content-primary">@money(Auth::user()->roi)</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary font-medium uppercase tracking-wide">Balance</p>
                <p class="text-lg font-bold text-content-primary">@money(Auth::user()->account_bal)</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary font-medium uppercase tracking-wide">Bonus</p>
                <p class="text-lg font-bold text-content-primary">@money(Auth::user()->bonus)</p>
            </div>
        </div>
    </div>
</div>

{{-- ── Tabs ── --}}
<div x-data="{ tab: 'profile' }" class="space-y-6">

    {{-- Tab navigation --}}
    <div class="flex gap-1 border-b border-surface-border">
        <button @click="tab = 'profile'"
                :class="tab === 'profile' ? 'border-primary text-primary' : 'border-transparent text-content-tertiary hover:text-content-secondary'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition-colors -mb-px">
            Personal Profile
        </button>
        <button @click="tab = 'records'"
                :class="tab === 'records' ? 'border-primary text-primary' : 'border-transparent text-content-tertiary hover:text-content-secondary'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition-colors -mb-px">
            Account Records
        </button>
        <button @click="tab = 'settings'"
                :class="tab === 'settings' ? 'border-primary text-primary' : 'border-transparent text-content-tertiary hover:text-content-secondary'"
                class="px-4 py-3 text-sm font-medium border-b-2 transition-colors -mb-px">
            Account Settings
        </button>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 1: Personal Profile --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <form method="POST" action="{{ route('profile.update') }}" class="rounded-xl bg-surface-raised border border-surface-border p-6">
            @csrf
            <h3 class="text-lg font-semibold text-content-primary mb-6">Personal Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Full Name --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" readonly
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Username</label>
                    <input type="text" value="{{ Auth::user()->username }}" readonly
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary/60 focus:outline-none">
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Email Address</label>
                    <input type="email" value="{{ Auth::user()->email }}" readonly
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary/60 focus:outline-none">
                </div>

                {{-- Phone --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Phone Number</label>
                    <input type="text" value="{{ Auth::user()->phone }}" readonly
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary/60 focus:outline-none">
                </div>

                {{-- Country --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Country</label>
                    <input type="text" name="country" value="{{ Auth::user()->country }}"
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                </div>

                {{-- Preferred Currency --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Preferred Currency</label>
                    <select name="currency_code"
                            class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                        @foreach(\App\Models\ExchangeRate::where('is_active', true)->orderBy('currency_code')->get() as $rate)
                            <option value="{{ $rate->currency_code }}" {{ Auth::user()->currency_code == $rate->currency_code ? 'selected' : '' }}>
                                {{ $rate->currency_code }} ({{ html_entity_decode($rate->currency_symbol) }}){{ $rate->currency_name ? ' — ' . $rate->currency_name : '' }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-content-tertiary">Amounts will be displayed in this currency</p>
                </div>

                {{-- State --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">State / Province</label>
                    <input type="text" name="state" value="{{ Auth::user()->state }}"
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                </div>

                {{-- Zip --}}
                <div>
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Postal / Zip Code</label>
                    <input type="text" name="zipcode" value="{{ Auth::user()->zipcode }}"
                           class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                </div>

                {{-- Address --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Address</label>
                    <textarea name="address" rows="3"
                              class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition resize-none">{{ Auth::user()->address }}</textarea>
                </div>
            </div>

            {{-- Account status sidebar --}}
            <div class="mt-6 p-4 rounded-lg bg-surface-overlay border border-surface-border-light">
                <div class="flex flex-wrap gap-6">
                    @if ($settings->enable_kyc == 'yes')
                    <div>
                        <p class="text-xs text-content-tertiary font-medium uppercase tracking-wide mb-1">Account Status</p>
                        @if (Auth::user()->account_verify == 'Verified')
                            <span class="inline-flex items-center gap-1 text-sm text-gain font-medium">
                                @include('components.icons.check-circle', ['class' => 'w-4 h-4']) Verified
                            </span>
                        @else
                            <span class="text-sm text-warning font-medium">Not Verified</span>
                        @endif
                    </div>
                    @endif
                    {{-- <div>
                        <p class="text-xs text-content-tertiary font-medium uppercase tracking-wide mb-1">Account Type</p>
                        @php
                            $accounts = json_decode($userinfo->account ?? '[]', true);
                        @endphp
                        @if (!empty($accounts))
                            @foreach ($accounts as $act)
                                <span class="inline-flex items-center px-2 py-0.5 rounded bg-primary/10 text-primary text-xs font-medium mr-1">{{ $act }}</span>
                            @endforeach
                        @else
                            <span class="text-sm text-content-tertiary">None</span>
                        @endif
                    </div> --}}
                </div>
            </div>

            <div class="flex items-center justify-between mt-6 pt-6 border-t border-surface-border">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" name="client_update_info"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-content-inverse font-semibold hover:bg-primary-dark transition">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 2: Account Records --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'records'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="space-y-6">

            {{-- Row 1: Summary Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @include('user.partials.stat-card', [
                    'label' => 'Net Worth',
                    'value' => \App\Helpers\CurrencyHelper::formatForUser($netWorth),
                    'icon'  => 'wallet',
                    'color' => 'primary',
                ])
                @include('user.partials.stat-card', [
                    'label' => 'Total Invested',
                    'value' => \App\Helpers\CurrencyHelper::formatForUser($totalInvested),
                    'icon'  => 'banknotes',
                    'color' => 'info',
                ])
                @include('user.partials.stat-card', [
                    'label' => 'Total P/L',
                    'value' => ($totalPL >= 0 ? '+' : '-') . \App\Helpers\CurrencyHelper::formatForUser(abs($totalPL)),
                    'icon'  => 'arrow-trending-up',
                    'color' => $totalPL >= 0 ? 'gain' : 'loss',
                ])
                @include('user.partials.stat-card', [
                    'label' => 'Win Rate',
                    'value' => $winRate !== null ? $winRate . '%' : 'N/A',
                    'icon'  => 'chart-bar',
                    'color' => ($winRate !== null && $winRate >= 50) ? 'gain' : (($winRate !== null) ? 'loss' : 'primary'),
                    'sub'   => ($winCount + $lossCount) > 0 ? $winCount . 'W / ' . $lossCount . 'L' : '',
                ])
            </div>

            {{-- Row 2: Allocation Chart + Legend --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                {{-- Doughnut Chart --}}
                <div class="lg:col-span-2 rounded-xl bg-surface-raised border border-surface-border p-5">
                    <h4 class="text-sm font-semibold text-content-primary mb-4">Portfolio Allocation</h4>
                    @if (count($categoryAllocation) > 0)
                        <div class="flex items-center justify-center">
                            <div class="relative w-48 h-48">
                                <canvas id="allocationChart"></canvas>
                                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                    <span class="text-xs text-content-tertiary">Total</span>
                                    <span class="text-lg font-bold text-content-primary">{{ \App\Helpers\CurrencyHelper::formatForUser(array_sum($categoryAllocation), null, 0) }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-48">
                            <div class="text-center">
                                <x-icon name="chart-bar" class="w-10 h-10 text-content-tertiary mx-auto mb-2" />
                                <p class="text-sm text-content-tertiary">No allocations yet</p>
                                <p class="text-xs text-content-tertiary mt-1">Start trading or investing to see your breakdown</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Allocation Legend + Quick Stats --}}
                <div class="lg:col-span-3 rounded-xl bg-surface-raised border border-surface-border p-5">
                    <h4 class="text-sm font-semibold text-content-primary mb-4">Breakdown</h4>

                    @php
                        $allocTotal = array_sum($categoryAllocation) ?: 1;
                        $colorMap = [
                            'Trading'      => ['bg' => 'bg-primary',   'text' => 'text-primary'],
                            'Investments'  => ['bg' => 'bg-info',      'text' => 'text-info'],
                            'Copy Trading' => ['bg' => 'bg-warning',   'text' => 'text-warning'],
                            'Pre-IPO'      => ['bg' => 'bg-gain',      'text' => 'text-gain'],
                            'Stocks'       => ['bg' => 'bg-[#8B5CF6]', 'text' => 'text-[#8B5CF6]'],
                            'NFTs'         => ['bg' => 'bg-[#EC4899]', 'text' => 'text-[#EC4899]'],
                        ];
                    @endphp

                    @if (count($categoryAllocation) > 0)
                        <div class="space-y-3">
                            @foreach ($categoryAllocation as $cat => $amount)
                                @php
                                    $pct = round(($amount / $allocTotal) * 100, 1);
                                    $colors = $colorMap[$cat] ?? ['bg' => 'bg-content-tertiary', 'text' => 'text-content-tertiary'];
                                @endphp
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center gap-2">
                                            <span class="w-3 h-3 rounded-full {{ $colors['bg'] }} shrink-0"></span>
                                            <span class="text-sm text-content-primary">{{ $cat }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-sm font-medium text-content-primary">@money($amount)</span>
                                            <span class="text-xs {{ $colors['text'] }} font-medium min-w-[40px] text-right">{{ $pct }}%</span>
                                        </div>
                                    </div>
                                    <div class="w-full bg-surface-overlay rounded-full h-1.5">
                                        <div class="{{ $colors['bg'] }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-content-tertiary">No active allocations to display.</p>
                    @endif

                    {{-- Account balances strip --}}
                    <div class="mt-5 pt-4 border-t border-surface-border grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-[10px] text-content-tertiary font-medium uppercase tracking-widest mb-0.5">Balance</p>
                            <p class="text-sm font-bold text-content-primary">@money(Auth::user()->account_bal)</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-content-tertiary font-medium uppercase tracking-widest mb-0.5">Profit</p>
                            <p class="text-sm font-bold text-content-primary">@money(Auth::user()->roi)</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-content-tertiary font-medium uppercase tracking-widest mb-0.5">Bonus</p>
                            <p class="text-sm font-bold text-content-primary">@money(Auth::user()->bonus)</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-content-tertiary font-medium uppercase tracking-widest mb-0.5">Referral</p>
                            <p class="text-sm font-bold text-content-primary">@money(Auth::user()->ref_bonus)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Row 3: Module Breakdown Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Trading Card --}}
                @if (!empty($mod['trading']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-primary-subtle">
                            <x-icon name="chart-bar" class="w-5 h-5 text-primary" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Trading</h4>
                            <p class="text-xs text-content-tertiary">{{ $totalTrades }} total trades</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Open</p>
                            <p class="text-base font-bold text-content-primary">{{ $openTradesCount }}</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Invested</p>
                            <p class="text-base font-bold text-content-primary">@money($totalTradeInvested)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Total Profit</p>
                            <p class="text-base font-bold text-gain">+@money($totalTradeProfit)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Total Loss</p>
                            <p class="text-base font-bold text-loss">-@money($totalTradeLoss)</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Copy Trading Card --}}
                @if (!empty($mod['copy_trading']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-warning/10">
                            <x-icon name="copy" class="w-5 h-5 text-warning" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Copy Trading</h4>
                            <p class="text-xs text-content-tertiary">{{ $activeCopyCount }} active positions</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Invested</p>
                            <p class="text-base font-bold text-content-primary">@money($totalCopyInvested)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Profit</p>
                            <p class="text-base font-bold {{ $totalCopyProfit >= 0 ? 'text-gain' : 'text-loss' }}">{{ $totalCopyProfit >= 0 ? '+' : '-' }}@money(abs($totalCopyProfit))</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Investment Plans Card --}}
                @if (!empty($mod['investment']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-info/10">
                            <x-icon name="banknotes" class="w-5 h-5 text-info" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Investment Plans</h4>
                            <p class="text-xs text-content-tertiary">{{ $activePlansCount }} active plans</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Invested</p>
                            <p class="text-base font-bold text-content-primary">@money($totalPlanInvested)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Earned</p>
                            <p class="text-base font-bold {{ $totalPlanProfit >= 0 ? 'text-gain' : 'text-loss' }}">+@money($totalPlanProfit)</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Pre-IPO Card --}}
                @if (!empty($mod['pre_ipo']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-gain/10">
                            <x-icon name="building-office" class="w-5 h-5 text-gain" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Pre-IPO</h4>
                            <p class="text-xs text-content-tertiary">{{ $preIpoCount }} holdings</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Cost</p>
                            <p class="text-base font-bold text-content-primary">@money($totalPreIpoCost)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Value</p>
                            @php $preIpoGain = $totalPreIpoValue - $totalPreIpoCost; @endphp
                            <p class="text-base font-bold text-content-primary">@money($totalPreIpoValue)</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Stocks Card --}}
                @if (!empty($mod['stocktrading']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-[#8B5CF6]/10">
                            <x-icon name="chart-bar" class="w-5 h-5 text-[#8B5CF6]" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Stock Shares</h4>
                            <p class="text-xs text-content-tertiary">{{ $stockCount }} positions</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Invested</p>
                            <p class="text-base font-bold text-content-primary">@money($totalStockInvested)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Value</p>
                            <p class="text-base font-bold text-content-primary">@money($totalStockValue)</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- NFTs Card --}}
                @if (!empty($mod['nft']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-[#EC4899]/10">
                            <x-icon name="gem" class="w-5 h-5 text-[#EC4899]" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">NFTs</h4>
                            <p class="text-xs text-content-tertiary">{{ $nftCount }} owned</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Collection Value</p>
                            <p class="text-base font-bold text-content-primary">@money($totalNftValue)</p>
                        </div>
                        <div class="bg-surface-overlay rounded-lg p-3">
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Items</p>
                            <p class="text-base font-bold text-content-primary">{{ $nftCount }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Row 4: Wallet & Loans Summary --}}
            <div class="grid grid-cols-1 {{ !empty($mod['loan']) ? 'md:grid-cols-3' : 'md:grid-cols-2' }} gap-4">
                {{-- Deposits --}}
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-gain/10">
                            <x-icon name="arrow-down-tray" class="w-5 h-5 text-gain" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Deposits</h4>
                            <p class="text-xs text-content-tertiary">{{ $depositCount }} transactions</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-content-primary">@money($totalDeposited)</p>
                    <p class="text-xs text-content-tertiary mt-1">Total deposited (processed)</p>
                </div>

                {{-- Withdrawals --}}
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-loss/10">
                            <x-icon name="arrow-up-tray" class="w-5 h-5 text-loss" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Withdrawals</h4>
                            <p class="text-xs text-content-tertiary">{{ $withdrawalCount }} transactions</p>
                        </div>
                    </div>
                    <p class="text-2xl font-bold text-content-primary">@money($totalWithdrawn)</p>
                    <p class="text-xs text-content-tertiary mt-1">Total withdrawn (processed)</p>
                </div>

                {{-- Loans --}}
                @if (!empty($mod['loan']))
                <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 rounded-lg bg-warning/10">
                            <x-icon name="hand-raised" class="w-5 h-5 text-warning" />
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-content-primary">Loans</h4>
                            <p class="text-xs text-content-tertiary">{{ $activeLoansCount }} active</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Outstanding</p>
                            <p class="text-base font-bold text-warning">@money($totalLoanOutstanding)</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-content-tertiary uppercase tracking-widest mb-0.5">Repaid</p>
                            <p class="text-base font-bold text-gain">@money($totalRepaid)</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════ --}}
    {{-- TAB 3: Account Settings --}}
    {{-- ═══════════════════════════════════════════ --}}
    <div x-show="tab === 'settings'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Change Password --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
                <h3 class="text-lg font-semibold text-content-primary mb-1">Change Password</h3>
                <p class="text-xs text-content-tertiary mb-5">You will be logged out after changing your password.</p>

                <form method="POST" action="{{ route('updateuserpass') }}" class="space-y-4">
                    @method('PUT')
                    @csrf

                    <div>
                        <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Current Password</label>
                        <input type="password" name="current_password" required autocomplete="off"
                               class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">New Password</label>
                        <input type="password" name="password" required autocomplete="off"
                               class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1.5">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required autocomplete="off"
                               class="w-full bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none focus:border-primary transition">
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-content-inverse text-sm font-semibold hover:bg-primary-dark transition">
                            Change Password
                        </button>
                        <button type="reset"
                                class="px-5 py-2.5 rounded-lg border border-surface-border-light text-content-secondary text-sm font-medium hover:border-surface-border hover:text-content-primary transition">
                            Clear
                        </button>
                    </div>
                </form>
            </div>

            {{-- Change Profile Image --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
                <h3 class="text-lg font-semibold text-content-primary mb-1">Change Profile Image</h3>
                <p class="text-xs text-content-tertiary mb-5">Upload a new profile photo.</p>

                <form method="POST" action="{{ route('updateprofileimage') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="flex justify-center">
                        @if (Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}"
                                 alt="{{ Auth::user()->name }}"
                                 class="w-32 h-32 rounded-full border-2 border-surface-border-light object-cover bg-surface-overlay">
                        @else
                            <div class="w-32 h-32 rounded-full border-2 border-surface-border-light bg-surface-overlay flex items-center justify-center">
                                <x-icon name="user-circle" class="w-20 h-20 text-content-tertiary" />
                            </div>
                        @endif
                    </div>

                    <input type="file" name="profileimage"
                           class="block w-full text-sm text-content-secondary file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition cursor-pointer">

                    <div class="pt-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary text-content-inverse text-sm font-semibold hover:bg-primary-dark transition">
                            Change Profile Image
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ── Referral link ── --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
        <h3 class="text-sm font-semibold text-content-primary mb-3">Your Referral Link</h3>
        <div x-data="{ copied: false }" class="flex items-stretch gap-2">
            <input type="text" id="referral_link" value="{{ Auth::user()->ref_link }}" readonly
                   class="flex-1 bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary font-mono select-all focus:outline-none">
            <button @click="navigator.clipboard.writeText(document.getElementById('referral_link').value); copied = true; setTimeout(() => copied = false, 2000)"
                    class="px-5 rounded-lg bg-primary text-content-inverse text-sm font-semibold hover:bg-primary-dark transition flex items-center gap-2">
                @include('components.icons.copy', ['class' => 'w-4 h-4'])
                <span x-text="copied ? 'Copied!' : 'Copy Link'"></span>
            </button>
        </div>
    </div>

</div>

@endsection

@section('scripts')
@if (count($categoryAllocation) > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('allocationChart');
    if (!ctx) return;

    const data = @json($categoryAllocation);
    const labels = Object.keys(data);
    const values = Object.values(data);

    const colorMap = {
        'Trading':      '#2E5C8A',
        'Investments':  '#3B82F6',
        'Copy Trading': '#F59E0B',
        'Pre-IPO':      '#1A3A7F',
        'Stocks':       '#8B5CF6',
        'NFTs':         '#EC4899',
    };
    const defaultColor = '#6B7280';
    const colors = labels.map(l => colorMap[l] || defaultColor);

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderColor: '#161A1E',
                borderWidth: 2,
                hoverBorderColor: '#2A2F36',
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1C2127',
                    titleColor: '#E8EAED',
                    bodyColor: '#9AA0AB',
                    borderColor: '#2A2F36',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 10,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = ((context.raw / total) * 100).toFixed(1);
                            return context.label + ': {{ $userCurrencySymbol }}' + context.raw.toLocaleString(undefined, {minimumFractionDigits: 2}) + ' (' + pct + '%)';
                        }
                    }
                }
            },
            animation: {
                animateRotate: true,
                duration: 800,
            }
        }
    });
});
</script>
@endif
@endsection
