{{--
    Recent Transactions Partial
    Displays the last 5 transactions from $t_history.
    Expects $t_history (Tp_Transaction collection) and $settings from parent view.
--}}
<div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-surface-border flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-info"></span>
            <h3 class="text-sm font-semibold text-content-primary">Recent Transactions</h3>
        </div>
        <a href="{{ route('accounthistory') }}" class="text-xs text-primary hover:text-primary-light font-medium transition-colors">
            View All &rarr;
        </a>
    </div>

    @if ($t_history->count() > 0)
        <div class="divide-y divide-surface-border">
            @foreach ($t_history->take(3) as $tx)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        @php
                            $typeColors = [
                                'Deposit'    => 'bg-gain/10 text-gain',
                                'Withdrawal' => 'bg-loss/10 text-loss',
                                'Bonus'      => 'bg-warning/10 text-warning',
                                'Trade'      => 'bg-info/10 text-info',
                            ];
                            $colorClass = $typeColors[$tx->type] ?? 'bg-primary-subtle text-primary';
                        @endphp
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-[10px] font-bold {{ $colorClass }}">
                            {{ strtoupper(substr($tx->type ?? 'TX', 0, 2)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm text-content-primary font-medium truncate">{{ $tx->type ?? 'Transaction' }}</p>
                            <p class="text-[10px] text-content-tertiary">{{ $tx->created_at ? $tx->created_at->format('M d, Y') : '' }}</p>
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-content-primary flex-shrink-0">
                        {{ $settings->currency }}{{ number_format($tx->amount ?? 0, 2, '.', ',') }}
                    </span>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-5 py-10 text-center">
            @include('components.icons.folder', ['class' => 'w-8 h-8 text-content-tertiary mx-auto mb-2'])
            <p class="text-xs text-content-tertiary">No Transactions</p>
        </div>
    @endif
</div>
