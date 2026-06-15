{{-- Portfolio Tab: Loans --}}

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @include('user.partials.stat-card-compact', ['label' => 'Active Loans', 'value' => $activeLoans->count(), 'icon' => 'hand-raised'])
    @include('user.partials.stat-card-compact', ['label' => 'Outstanding', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalLoanOutstanding), 'icon' => 'banknotes', 'color' => 'loss'])
    @include('user.partials.stat-card-compact', ['label' => 'Total Repaid', 'value' => \App\Helpers\CurrencyHelper::formatForUser($activeLoans->sum('total_repaid')), 'icon' => 'check-circle', 'color' => 'gain'])
</div>

@if($activeLoans->count() > 0)
    <div class="space-y-4">
        @foreach($activeLoans as $loan)
            @php
                $progress = $loan->progress_percentage ?? 0;
                $remaining = $loan->total_repayable - $loan->total_repaid;
                $isOverdue = $loan->is_overdue ?? false;
            @endphp
            <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-content-primary">@money($loan->amount) Loan</span>
                            <span class="px-2 py-0.5 text-[10px] font-medium rounded-full {{ $isOverdue ? 'bg-loss/10 text-loss' : 'bg-gain/10 text-gain' }}">
                                {{ $isOverdue ? 'Overdue' : ucfirst($loan->status) }}
                            </span>
                        </div>
                        <span class="text-xs text-content-tertiary">
                            {{ number_format($loan->interest_rate, 1) }}% interest &middot; {{ $loan->duration ?? '—' }} months
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-content-tertiary block">Remaining</span>
                        <span class="text-lg font-bold text-content-primary">@money($remaining)</span>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div class="mb-3">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-content-secondary">Repayment Progress</span>
                        <span class="text-content-primary font-medium">{{ number_format($progress, 1) }}%</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-surface-overlay overflow-hidden">
                        <div class="h-full rounded-full {{ $isOverdue ? 'bg-loss' : 'bg-primary' }} transition-all duration-300"
                             style="width: {{ min($progress, 100) }}%"></div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-content-tertiary block">Total Repayable</span>
                        <span class="text-content-primary font-medium">@money($loan->total_repayable)</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Repaid</span>
                        <span class="text-gain font-medium">@money($loan->total_repaid)</span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Next Payment</span>
                        <span class="{{ $isOverdue ? 'text-loss font-medium' : 'text-content-secondary' }}">
                            {{ $loan->next_payment_date ? \Carbon\Carbon::parse($loan->next_payment_date)->format('M d, Y') : '—' }}
                        </span>
                    </div>
                    <div>
                        <span class="text-content-tertiary block">Maturity</span>
                        <span class="text-content-secondary">
                            {{ $loan->maturity_date ? \Carbon\Carbon::parse($loan->maturity_date)->format('M d, Y') : '—' }}
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    @include('user.trades.partials.empty-state', [
        'icon' => 'hand-raised',
        'title' => 'No active loans',
        'message' => 'You don\'t have any outstanding loans.',
        'actionUrl' => route('loans.create'),
        'actionLabel' => 'Apply for Loan',
    ])
@endif
