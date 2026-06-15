@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'My Plans', 'subtitle' => 'Manage your active investment plans'])

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        @include('user.partials.stat-card', [
            'label' => 'Total Invested',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($totalInvested),
            'icon' => 'banknotes',
            'color' => 'primary',
        ])
        @include('user.partials.stat-card', [
            'label' => 'Total Profit',
            'value' => \App\Helpers\CurrencyHelper::formatForUser($totalProfit),
            'icon' => 'arrow-trending-up',
            'color' => 'gain',
        ])
        @include('user.partials.stat-card', [
            'label' => 'Active Plans',
            'value' => $activePlanCount,
            'icon' => 'chart-bar',
            'color' => 'info',
        ])
    </div>

    {{-- Sort Filter --}}
    @if ($numOfPlan > 0)
        <div class="flex items-center justify-between gap-2 mb-4">
            <a href="{{ route('mplans') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">
                <x-icon name="banknotes" class="w-4 h-4" />
                Browse Plans
            </a>
            <div class="flex items-center gap-2">
                <select name="sortplan" id="sortvalue"
                        class="px-3 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                    <option value="All">All</option>
                    <option value="yes">Active</option>
                    <option value="cancelled">Cancelled/Inactive</option>
                    <option value="expired">Expired</option>
                </select>
                <a href="javascript:;" id="sortform" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border hover:bg-surface-border text-content-secondary text-sm font-medium transition-colors">
                    Sort
                </a>
            </div>
        </div>
    @endif

    {{-- Plans List --}}
    <div class="space-y-3">
        @forelse ($plans as $plan)
            @php
                // Calculate time progress for active plans
                $progressPercent = 0;
                $daysLeft = 0;
                $totalDays = 0;
                if ($plan->active == 'yes' && $plan->created_at && $plan->expire_date) {
                    $start = $plan->created_at;
                    $end = $plan->expire_date;
                    $now = now();
                    $totalDays = max(1, $start->diffInDays($end));
                    $elapsed = $start->diffInDays($now->min($end));
                    $progressPercent = round(($elapsed / $totalDays) * 100, 1);
                    $daysLeft = max(0, $end->diffInDays($now));
                }
            @endphp
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5 hover:border-primary/30 transition-colors">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    {{-- Plan Name + Amount --}}
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                            <x-icon name="banknotes" class="w-5 h-5 text-primary" />
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-sm font-semibold text-content-primary truncate">{{ $plan->dplan->name }}</h4>
                            <p class="text-xs text-content-tertiary">@money($plan->amount) invested</p>
                        </div>
                    </div>

                    {{-- Progress Bar (Active plans only) --}}
                    @if ($plan->active == 'yes')
                        <div class="hidden md:block flex-1 max-w-xs mx-4">
                            <div class="flex items-center justify-between text-xs text-content-tertiary mb-1">
                                <span>Progress</span>
                                <span>{{ $daysLeft }} days left</span>
                            </div>
                            <div class="w-full h-1.5 bg-surface-overlay rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full transition-all" style="width: {{ min(100, $progressPercent) }}%"></div>
                            </div>
                        </div>
                    @endif

                    {{-- Profit + Dates --}}
                    <div class="hidden md:flex items-center gap-6 text-xs">
                        <div class="text-center">
                            <p class="text-gain font-semibold">@money($plan->profit_earned ?? 0)</p>
                            <p class="text-content-tertiary">Profit</p>
                        </div>
                        <div class="text-center">
                            <p class="text-content-primary font-medium">{{ $plan->created_at->format('M d, Y') }}</p>
                            <p class="text-content-tertiary">Start Date</p>
                        </div>
                        <div class="text-center">
                            <p class="text-content-primary font-medium">{{ \Carbon\Carbon::parse($plan->expire_date)->format('M d, Y') }}</p>
                            <p class="text-content-tertiary">End Date</p>
                        </div>
                    </div>

                    {{-- Status + Arrow --}}
                    <div class="flex items-center gap-3">
                        @if ($plan->active == 'yes')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gain/10 text-gain">Active</span>
                        @elseif($plan->active == 'expired')
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-loss/10 text-loss">Expired</span>
                        @else
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-loss/10 text-loss">Inactive</span>
                        @endif
                        <a href="{{ route('plandetails', $plan->id) }}" class="text-content-tertiary hover:text-primary transition-colors">
                            <x-icon name="chevron-right" class="w-5 h-5" />
                        </a>
                    </div>
                </div>

                {{-- Mobile: Progress + Profit (shown on small screens) --}}
                @if ($plan->active == 'yes')
                    <div class="md:hidden mt-3 pt-3 border-t border-surface-border">
                        <div class="flex items-center justify-between text-xs text-content-tertiary mb-1">
                            <span>Progress &middot; {{ $daysLeft }} days left</span>
                            <span class="text-gain font-semibold">@money($plan->profit_earned ?? 0) earned</span>
                        </div>
                        <div class="w-full h-1.5 bg-surface-overlay rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all" style="width: {{ min(100, $progressPercent) }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="rounded-xl bg-surface-raised border border-surface-border p-8 text-center">
                <x-icon name="folder" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                <p class="text-content-secondary mb-3">You do not have an investment plan at the moment.</p>
                <a href="{{ route('mplans') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">
                    Browse Plans
                </a>
            </div>
        @endforelse
    </div>

    @if (count($plans) > 0)
        <div class="mt-4">{{ $plans->links() }}</div>
    @endif

@endsection

@section('scripts')
@parent
<script>
    const sortvalue = document.getElementById('sortvalue');
    const sortform = document.getElementById('sortform');
    if (sortvalue && sortform) {
        sortform.href = "{{ url('/dashboard/sort-plans/All') }}";
        sortvalue.addEventListener('change', function() {
            sortform.href = "{{ url('/dashboard/sort-plans/') }}" + '/' + sortvalue.value;
        });
    }
</script>
@endsection
