@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Back + Page Header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('myplans', 'All') }}" class="p-2 rounded-lg bg-surface-overlay hover:bg-surface-border text-content-secondary transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-content-primary">{{ $plan->dplan->name }}</h2>
            <p class="text-sm text-content-secondary mt-0.5">{{ $plan->dplan->increment_amount }}{{ $plan->dplan->increment_type == 'Percentage' ? '%' : $settings->currency }} {{ $plan->dplan->increment_interval }} for {{ $plan->dplan->expiration }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if ($plan->active == 'yes')
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gain/10 text-gain">Active</span>
            @elseif($plan->active == 'expired')
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-loss/10 text-loss">Expired</span>
            @else
                <span class="px-3 py-1 text-xs font-medium rounded-full bg-loss/10 text-loss">Inactive</span>
            @endif
            @if ($settings->should_cancel_plan && $plan->active == 'yes')
                <button x-data x-on:click="$dispatch('open-cancel-modal')"
                        class="px-4 py-2 rounded-lg bg-loss/10 hover:bg-loss/20 text-loss text-sm font-medium transition-colors">
                    Cancel Plan
                </button>
            @endif
        </div>
    </div>

    {{-- Time Progress Bar --}}
    @if ($plan->active == 'yes')
        <div class="rounded-xl bg-surface-raised border border-surface-border p-5 mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-content-primary">Investment Progress</span>
                <span class="text-sm text-content-secondary">Day {{ $daysElapsed }} of {{ $totalDays }}</span>
            </div>
            <div class="w-full h-2.5 bg-surface-overlay rounded-full overflow-hidden mb-2">
                <div class="h-full rounded-full transition-all {{ $progressPercent >= 100 ? 'bg-gain' : 'bg-primary' }}" style="width: {{ min(100, $progressPercent) }}%"></div>
            </div>
            <div class="flex items-center justify-between text-xs text-content-tertiary">
                <span>{{ $plan->created_at->format('M d, Y') }}</span>
                <span class="font-semibold {{ $progressPercent >= 100 ? 'text-gain' : 'text-primary' }}">{{ $progressPercent }}%</span>
                <span>{{ \Carbon\Carbon::parse($plan->expire_date)->format('M d, Y') }}</span>
            </div>
        </div>
    @endif

    {{-- Investment Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @include('user.partials.stat-card', [
            'label' => 'Invested Amount',
            'value' => $settings->currency . number_format($plan->amount, 2, '.', ','),
            'icon' => 'banknotes',
            'color' => 'primary',
        ])
        @include('user.partials.stat-card', [
            'label' => 'Profit Earned',
            'value' => $settings->currency . number_format($plan->profit_earned, 2, '.', ','),
            'icon' => 'arrow-trending-up',
            'color' => 'gain',
        ])
        @include('user.partials.stat-card', [
            'label' => 'Total Return',
            'value' => $settings->currency . number_format(($settings->return_capital ? $plan->amount : 0) + $plan->profit_earned, 2, '.', ','),
            'icon' => 'wallet',
            'color' => 'primary',
        ])
    </div>

    {{-- Earnings Summary --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6 mb-6">
        <h3 class="text-sm font-semibold text-content-primary mb-4 flex items-center gap-2">
            <x-icon name="chart-bar" class="w-4 h-4 text-primary" />
            Earnings Summary
        </h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="rounded-lg bg-surface-overlay p-3">
                <p class="text-xs text-content-tertiary mb-1">ROI Per {{ $plan->dplan->increment_interval }}</p>
                <p class="text-sm font-bold text-gain">{{ $settings->currency }}{{ number_format($roiPerInterval, 2) }}</p>
            </div>
            <div class="rounded-lg bg-surface-overlay p-3">
                <p class="text-xs text-content-tertiary mb-1">Payments Received</p>
                <p class="text-sm font-bold text-content-primary">{{ $totalPayments }}</p>
            </div>
            <div class="rounded-lg bg-surface-overlay p-3">
                <p class="text-xs text-content-tertiary mb-1">Projected Total ROI</p>
                <p class="text-sm font-bold text-primary">{{ $settings->currency }}{{ number_format($projectedTotal, 2) }}</p>
            </div>
            <div class="rounded-lg bg-surface-overlay p-3">
                <p class="text-xs text-content-tertiary mb-1">Next Payment</p>
                @if ($plan->active == 'yes')
                    <p class="text-sm font-bold text-content-primary">{{ $nextPaymentDate->format('M d, g:i A') }}</p>
                @else
                    <p class="text-sm font-bold text-content-tertiary">—</p>
                @endif
            </div>
        </div>
        {{-- Earnings Progress --}}
        @if ($projectedTotal > 0)
            <div class="mt-4 pt-4 border-t border-surface-border">
                <div class="flex items-center justify-between text-xs mb-1.5">
                    <span class="text-content-tertiary">Earnings Progress</span>
                    <span class="text-gain font-medium">{{ $settings->currency }}{{ number_format($plan->profit_earned, 2) }} / {{ $settings->currency }}{{ number_format($projectedTotal, 2) }}</span>
                </div>
                <div class="w-full h-1.5 bg-surface-overlay rounded-full overflow-hidden">
                    <div class="h-full bg-gain rounded-full transition-all" style="width: {{ min(100, round(($plan->profit_earned / $projectedTotal) * 100, 1)) }}%"></div>
                </div>
            </div>
        @endif
    </div>

    {{-- Plan Info --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border p-6 mb-6">
        <h3 class="text-sm font-semibold text-content-primary mb-4">Plan Information</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-content-tertiary mb-1">Duration</p>
                <p class="text-sm font-medium text-content-primary">{{ $plan->dplan->expiration }}</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-1">Start Date</p>
                <p class="text-sm font-medium text-content-primary">{{ $plan->created_at->toDayDateTimeString() }}</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-1">End Date</p>
                <p class="text-sm font-medium text-content-primary">{{ \Carbon\Carbon::parse($plan->expire_date)->toDayDateTimeString() }}</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-1">Min Return</p>
                <p class="text-sm font-medium text-content-primary">{{ $plan->dplan->minr }}%</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-1">Max Return</p>
                <p class="text-sm font-medium text-content-primary">{{ $plan->dplan->maxr }}%</p>
            </div>
            <div>
                <p class="text-xs text-content-tertiary mb-1">ROI Interval</p>
                <p class="text-sm font-medium text-content-primary">{{ $plan->dplan->increment_interval }}</p>
            </div>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
            <h3 class="text-sm font-semibold text-content-primary">ROI Transactions</h3>
            <span class="text-xs text-content-tertiary">{{ $totalPayments }} payments</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Type</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Running Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @php $runningTotal = 0; @endphp
                    @forelse($transactions as $history)
                        @php $runningTotal += floatval($history->amount); @endphp
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gain/10 text-gain">Profit</span>
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-content-primary text-xs">{{ $history->created_at->format('M d, Y g:i A') }}</p>
                                <p class="text-content-tertiary text-xs">{{ $history->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-5 py-3 text-right text-gain font-medium">+{{ $settings->currency }}{{ number_format($history->amount, 2, '.', ',') }}</td>
                            <td class="px-5 py-3 text-right text-content-secondary font-medium">{{ $settings->currency }}{{ number_format($runningTotal, 2, '.', ',') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-content-tertiary">No ROI transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if (count($transactions) > 0)
            <div class="px-5 py-3 border-t border-surface-border">{{ $transactions->links() }}</div>
        @endif
    </div>

    {{-- Cancel Plan Modal --}}
    @if ($settings->should_cancel_plan && $plan->active == 'yes')
        <div x-data="{ open: false }" @open-cancel-modal.window="open = true">
            <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <div x-show="open" class="fixed inset-0 bg-black/60" @click="open = false"></div>
                <div x-show="open" x-transition class="relative bg-surface-raised border border-surface-border rounded-xl p-6 w-full max-w-md mx-4 z-10">
                    <h3 class="text-lg font-semibold text-content-primary mb-2">Cancel Plan</h3>
                    <p class="text-sm text-content-secondary mb-4">Are you sure you want to cancel your <strong class="text-content-primary">{{ $plan->dplan->name }}</strong> plan?</p>
                    <p class="text-xs text-content-tertiary mb-6">Your invested capital of <strong class="text-gain">{{ $settings->currency }}{{ number_format($plan->amount, 2) }}</strong> will be refunded to your account.</p>
                    <div class="flex gap-3 justify-end">
                        <button @click="open = false" class="px-4 py-2 rounded-lg bg-surface-overlay text-content-secondary text-sm font-medium hover:bg-surface-border transition-colors">Close</button>
                        <a href="{{ route('cancelplan', $plan->id) }}" class="px-4 py-2 rounded-lg bg-loss hover:bg-loss/80 text-white text-sm font-medium transition-colors">Cancel Plan</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
