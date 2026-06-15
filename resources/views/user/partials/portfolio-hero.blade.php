{{--
    Portfolio Hero Card
    Consolidates balance, profit, and KYC status into a single prominent card.
    Used on the main dashboard only. Expects $settings from controller; uses Auth::user() directly.
--}}
@php
    $user = Auth::user();
    $balance = $user->account_bal ?? 0;
    $profit  = $user->roi ?? 0;
    $isVerified = $user->account_verify === 'Verified';

    // Profit percentage relative to balance
    $profitPct = $balance > 0 ? round(($profit / $balance) * 100, 2) : null;
    $profitPositive = $profit >= 0;
@endphp

<div class="relative rounded-xl overflow-hidden group/hero">

    {{-- Top accent gradient line --}}
    <div class="absolute top-0 inset-x-0 h-[2px] bg-gradient-to-r from-primary/0 via-primary to-primary/0"></div>

    {{-- Card body --}}
    <div class="bg-gradient-to-br from-surface-raised via-surface-raised to-surface-overlay border border-surface-border rounded-xl p-5 sm:p-6 relative">

        {{-- Decorative background elements --}}
        <div class="absolute -top-20 -right-20 w-56 h-56 bg-primary/[0.04] rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-16 -left-16 w-40 h-40 bg-info/[0.03] rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute top-1/2 right-8 w-24 h-24 border border-primary/[0.06] rounded-full pointer-events-none hidden sm:block"></div>
        <div class="absolute top-1/3 right-16 w-12 h-12 border border-primary/[0.04] rounded-full pointer-events-none hidden sm:block"></div>

        {{-- Top Row: Welcome + Action Badges --}}
        <div class="flex items-center justify-between mb-4 relative">
            <div>
                <p class="text-xs text-content-tertiary font-medium mb-0.5">Welcome back,</p>
                <p class="text-sm font-semibold text-content-primary">{{ $user->name }} {{ $user->l_name }}</p>
            </div>

            <div class="flex items-center gap-2">
                {{-- Connect Wallet Badge --}}
                @if ($settings->wallet_status == 'on' && $user->wallet_connect_status == 'on')
                    <a href="{{ route('connect-wallet') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-primary bg-primary/10 border border-primary/20 px-3 py-1.5 rounded-full hover:bg-primary/20 transition-colors">
                        <x-icon name="wallet" class="w-3.5 h-3.5" />
                        Connect Wallet
                    </a>
                @endif

                {{-- KYC Badge --}}
                @if ($settings->enable_kyc == 'yes')
                    @if ($isVerified)
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gain bg-gain/10 border border-gain/20 px-3 py-1.5 rounded-full">
                            @include('components.icons.shield-check', ['class' => 'w-3.5 h-3.5'])
                            Verified
                        </span>
                    @elseif ($user->account_verify === 'Under review')
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-info bg-info/10 border border-info/20 px-3 py-1.5 rounded-full">
                            @include('components.icons.shield-check', ['class' => 'w-3.5 h-3.5'])
                            Under Review
                        </span>
                    @else
                        <a href="{{ route('account.verify') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-warning bg-warning/10 border border-warning/20 px-3 py-1.5 rounded-full hover:bg-warning/20 transition-colors">
                            @include('components.icons.shield-check', ['class' => 'w-3.5 h-3.5'])
                            Unverified
                        </a>
                    @endif
                @endif
            </div>
        </div>

        {{-- Divider --}}
        <div class="h-px bg-gradient-to-r from-surface-border/0 via-surface-border to-surface-border/0 mb-5"></div>

        {{-- Balance + Profit Row --}}
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 relative mb-5 overflow-hidden">

            {{-- Portfolio Value --}}
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-2">
                    <div class="p-1.5 rounded-md bg-primary-subtle">
                        @include('components.icons.wallet', ['class' => 'w-4 h-4 text-primary'])
                    </div>
                    <span class="text-xs font-medium uppercase tracking-widest text-content-tertiary">Portfolio Value</span>
                </div>
                <p class="text-content-primary tracking-tight truncate">
                    <span class="text-base sm:text-2xl font-semibold text-content-secondary align-top">@userCurrency</span><span class="text-2xl sm:text-4xl font-bold">{{ number_format(Auth::user()->convertToUserCurrency($balance), 2, '.', ',') }}</span>
                </p>
            </div>

            {{-- Profit Indicator --}}
            <div class="bg-surface-overlay/40 border border-surface-border rounded-lg p-3 sm:p-3.5 flex items-center gap-3 sm:min-w-[220px] overflow-hidden">
                <div class="p-2 rounded-lg {{ $profitPositive ? 'bg-gain/10' : 'bg-loss/10' }} shrink-0 ring-1 {{ $profitPositive ? 'ring-gain/20' : 'ring-loss/20' }}">
                    @include('components.icons.arrow-trending-up', ['class' => 'w-4 h-4 ' . ($profitPositive ? 'text-gain' : 'text-loss')])
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[10px] text-content-tertiary font-medium uppercase tracking-widest mb-0.5">Profit / Loss</p>
                    <div class="flex items-baseline gap-1.5 sm:gap-2">
                        <span class="text-sm sm:text-lg font-bold text-content-primary truncate min-w-0">@money($profit)</span>
                        @if ($profitPct !== null)
                            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold px-1.5 py-0.5 rounded shrink-0 {{ $profitPositive ? 'bg-gain/10 text-gain' : 'bg-loss/10 text-loss' }}">
                                {{ $profitPositive ? '↑' : '↓' }} {{ abs($profitPct) }}%
                            </span>
                        @else
                            <span class="text-xs font-medium text-content-tertiary shrink-0">N/A</span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- Bottom Divider --}}
        <div class="h-px bg-gradient-to-r from-surface-border/0 via-surface-border to-surface-border/0 mb-4"></div>

        {{-- Quick Action Strip --}}
        <div class="flex items-center gap-2 sm:gap-3 relative">
            <a href="{{ route('deposits') }}" class="flex-1 flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-dark text-content-inverse text-xs sm:text-sm font-medium py-2 sm:py-2.5 rounded-lg transition-colors">
                @include('components.icons.arrow-down-tray', ['class' => 'w-4 h-4'])
                Deposit
            </a>
            <a href="{{ route('withdrawalsdeposits') }}" class="flex-1 flex items-center justify-center gap-1.5 bg-surface-overlay hover:bg-surface-border text-content-primary text-xs sm:text-sm font-medium py-2 sm:py-2.5 rounded-lg border border-surface-border transition-colors">
                @include('components.icons.arrow-up-tray', ['class' => 'w-4 h-4'])
                Withdraw
            </a>
            @if(!empty($mod['trading']))
            <a href="{{ route('trade') }}" class="flex-1 flex items-center justify-center gap-1.5 bg-surface-overlay hover:bg-surface-border text-content-primary text-xs sm:text-sm font-medium py-2 sm:py-2.5 rounded-lg border border-surface-border transition-colors">
                @include('components.icons.chart-bar', ['class' => 'w-4 h-4'])
                Trade
            </a>
            @endif
        </div>
    </div>
</div>
