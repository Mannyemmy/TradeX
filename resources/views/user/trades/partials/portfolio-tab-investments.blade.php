{{-- Portfolio Tab: Investments --}}

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'Active Plans', 'value' => $activePlans->count(), 'icon' => 'banknotes'])
    @include('user.partials.stat-card-compact', ['label' => 'Total Invested', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalPlanInvested), 'icon' => 'chart-bar', 'color' => 'info'])
    @include('user.partials.stat-card-compact', ['label' => 'Profit Earned', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalPlanProfit), 'icon' => 'arrow-trending-up', 'color' => 'gain'])
</div>

@if($activePlans->count() > 0)
    {{-- Desktop Table --}}
    <div class="hidden md:block bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-surface-border">
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Plan</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Invested</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-content-tertiary uppercase tracking-wider">Profit</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Started</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Expires</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-content-tertiary uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-border">
                @foreach($activePlans as $plan)
                    <tr class="hover:bg-surface-overlay/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="text-sm font-semibold text-content-primary">{{ $plan->planDetails->name ?? 'Unknown Plan' }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-medium text-content-primary">@money($plan->amount ?? 0)</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-sm font-medium text-gain">@money($plan->profit_earned ?? 0)</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-content-secondary">
                            {{ $plan->activated_at ? \Carbon\Carbon::parse($plan->activated_at)->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-4 py-3">
                            @if($plan->expire_date)
                                @php $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($plan->expire_date), false); @endphp
                                <span class="text-xs {{ $daysLeft <= 3 ? 'text-warning font-medium' : 'text-content-secondary' }}">
                                    {{ \Carbon\Carbon::parse($plan->expire_date)->format('M d, Y') }}
                                    @if($daysLeft <= 3 && $daysLeft >= 0)
                                        <span class="text-[10px] text-warning block">{{ $daysLeft }} day{{ $daysLeft !== 1 ? 's' : '' }} left</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-xs text-content-tertiary">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 text-[10px] font-medium rounded-full bg-gain/10 text-gain">Active</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
        @foreach($activePlans as $plan)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-content-primary">{{ $plan->planDetails->name ?? 'Unknown Plan' }}</span>
                    <span class="px-2 py-0.5 text-[10px] font-medium rounded-full bg-gain/10 text-gain">Active</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-content-tertiary block">Invested</span>
                        <span class="text-content-primary font-medium">@money($plan->amount ?? 0)</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Profit</span>
                        <span class="text-gain font-medium">@money($plan->profit_earned ?? 0)</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Started</span>
                        <span class="text-content-secondary">{{ $plan->activated_at ? \Carbon\Carbon::parse($plan->activated_at)->format('M d, Y') : '—' }}</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Expires</span>
                        <span class="text-content-secondary">{{ $plan->expire_date ? \Carbon\Carbon::parse($plan->expire_date)->format('M d, Y') : '—' }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    @include('user.trades.partials.empty-state', [
        'icon' => 'banknotes',
        'title' => 'No active investment plans',
        'message' => 'You haven\'t subscribed to any investment plans yet.',
        'actionUrl' => route('mplans'),
        'actionLabel' => 'Browse Plans',
    ])
@endif
