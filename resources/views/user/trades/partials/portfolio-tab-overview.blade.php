{{-- Portfolio Tab: Overview --}}
@php
    $allocationTotal = $categoryAllocation->sum();
    $categoryColors = [
        'Trading'      => ['bg' => 'bg-primary',    'text' => 'text-primary'],
        'Investments'  => ['bg' => 'bg-info',        'text' => 'text-info'],
        'Copy Trading' => ['bg' => 'bg-warning',     'text' => 'text-warning'],
        'Pre-IPO'      => ['bg' => 'bg-gain',        'text' => 'text-gain'],
        'NFTs'         => ['bg' => 'bg-loss',        'text' => 'text-loss'],
    ];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left: Allocation --}}
    <div class="lg:col-span-2 bg-surface-raised border border-surface-border rounded-xl p-5">
        <h3 class="text-sm font-semibold text-content-primary mb-4">Portfolio Allocation</h3>

        @if($allocationTotal > 0)
            {{-- Stacked Bar --}}
            <div class="flex rounded-full overflow-hidden h-3 bg-surface-overlay mb-5" role="img" aria-label="Portfolio allocation breakdown">
                @foreach($categoryAllocation as $category => $amount)
                    @php $color = $categoryColors[$category] ?? ['bg' => 'bg-content-tertiary', 'text' => 'text-content-tertiary']; @endphp
                    <div class="{{ $color['bg'] }}"
                         style="width: {{ ($amount / $allocationTotal) * 100 }}%"
                         title="{{ $category }}: {{ \App\Helpers\CurrencyHelper::formatForUser($amount) }}">
                    </div>
                @endforeach
            </div>

            {{-- Legend --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach($categoryAllocation as $category => $amount)
                    @php $color = $categoryColors[$category] ?? ['bg' => 'bg-content-tertiary', 'text' => 'text-content-tertiary']; @endphp
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full {{ $color['bg'] }} shrink-0"></div>
                        <div>
                            <span class="text-xs text-content-secondary block">{{ $category }}</span>
                            <span class="text-sm font-semibold text-content-primary">@money($amount)</span>
                            <span class="text-[10px] text-content-tertiary ml-1">({{ number_format(($amount / $allocationTotal) * 100, 1) }}%)</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-sm text-content-tertiary text-center py-8">No active investments across any module.</p>
        @endif
    </div>

    {{-- Right: Quick Stats --}}
    <div class="space-y-4">
        {{-- Account Balance --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
            <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Account Balance</span>
            <span class="text-xl font-bold text-content-primary">@money($user->account_bal ?? 0)</span>
        </div>

        {{-- Outstanding Loans --}}
        @if(!empty($tabs['loans']) && $totalLoanOutstanding > 0)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-1">Outstanding Loans</span>
                <span class="text-xl font-bold text-loss">@money($totalLoanOutstanding)</span>
                <span class="text-xs text-content-tertiary block mt-0.5">{{ $activeLoans->count() }} active loan{{ $activeLoans->count() !== 1 ? 's' : '' }}</span>
            </div>
        @endif

        {{-- Active Positions Summary --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
            <span class="text-xs text-content-tertiary uppercase tracking-wider block mb-2">Active Positions</span>
            <div class="space-y-2">
                @if(!empty($tabs['trading']))
                    <div class="flex justify-between text-xs">
                        <span class="text-content-secondary">Open Trades</span>
                        <span class="text-content-primary font-medium">{{ $openTrades->count() }}</span>
                    </div>
                @endif
                @if(!empty($tabs['investments']))
                    <div class="flex justify-between text-xs">
                        <span class="text-content-secondary">Active Plans</span>
                        <span class="text-content-primary font-medium">{{ $activePlans->count() }}</span>
                    </div>
                @endif
                @if(!empty($tabs['copy_trading']))
                    <div class="flex justify-between text-xs">
                        <span class="text-content-secondary">Copy Positions</span>
                        <span class="text-content-primary font-medium">{{ $activeCopyPositions->count() }}</span>
                    </div>
                @endif
                @if(!empty($tabs['pre_ipo']))
                    <div class="flex justify-between text-xs">
                        <span class="text-content-secondary">Pre-IPO Holdings</span>
                        <span class="text-content-primary font-medium">{{ $preIpoHoldings->count() }}</span>
                    </div>
                @endif
                @if(!empty($tabs['nfts']))
                    <div class="flex justify-between text-xs">
                        <span class="text-content-secondary">NFTs Owned</span>
                        <span class="text-content-primary font-medium">{{ $ownedNfts->count() }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Demo Balance --}}
        @if(($user->demo_bal ?? 0) > 0)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                <span class="inline-flex items-center gap-1.5 text-xs text-warning">
                    <x-icon name="exclamation-triangle" class="w-3.5 h-3.5" />
                    Demo Balance: @money($user->demo_bal)
                </span>
            </div>
        @endif
    </div>
</div>
