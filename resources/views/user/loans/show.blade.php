@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-bold text-content-primary">{{ $title }}</h2>
            <p class="text-sm text-content-secondary mt-1">
                {{ $loan->loanPlan->name ?? 'Legacy Loan' }}
                &middot;
                @php
                    $statusColors = [
                        'pending' => 'bg-warning/10 text-warning',
                        'active' => 'bg-info/10 text-info',
                        'repaying' => 'bg-primary-subtle text-primary',
                        'completed' => 'bg-gain/10 text-gain',
                        'rejected' => 'bg-loss/10 text-loss',
                        'defaulted' => 'bg-loss/10 text-loss',
                        'cancelled' => 'bg-surface-overlay text-content-tertiary',
                    ];
                    $color = $statusColors[$loan->status] ?? 'bg-surface-overlay text-content-tertiary';
                @endphp
                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $color }}">{{ ucfirst($loan->status) }}</span>
            </p>
        </div>
        <a href="{{ route('loans.my') }}" class="px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">
            Back to My Loans
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Loan Summary --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Rejection Notice --}}
            @if($loan->status === 'rejected' && $loan->rejection_reason)
                <div class="rounded-xl bg-loss/10 border border-loss/20 p-4">
                    <h4 class="text-sm font-semibold text-loss mb-1">Application Rejected</h4>
                    <p class="text-sm text-loss/80">{{ $loan->rejection_reason }}</p>
                </div>
            @endif

            {{-- Loan Details Card --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h3 class="text-sm font-semibold text-content-secondary uppercase tracking-wider mb-4">Loan Details</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-content-tertiary">Requested Amount</p>
                        <p class="font-medium text-content-primary">@money($loan->amount)</p>
                    </div>
                    @if($loan->approved_amount)
                    <div>
                        <p class="text-content-tertiary">Approved Amount</p>
                        <p class="font-medium text-content-primary">@money($loan->approved_amount)</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-content-tertiary">Duration</p>
                        <p class="font-medium text-content-primary">{{ $loan->duration }} months</p>
                    </div>
                    @if($loan->interest_rate)
                    <div>
                        <p class="text-content-tertiary">Interest Rate</p>
                        <p class="font-medium text-content-primary">{{ $loan->interest_rate }}% {{ ucfirst($loan->interest_type ?? '') }}</p>
                    </div>
                    @endif
                    @if($loan->processing_fee)
                    <div>
                        <p class="text-content-tertiary">Processing Fee</p>
                        <p class="font-medium text-content-primary">@money($loan->processing_fee)</p>
                    </div>
                    @endif
                    @if($loan->total_repayable)
                    <div>
                        <p class="text-content-tertiary">Total Repayable</p>
                        <p class="font-bold text-content-primary">@money($loan->total_repayable)</p>
                    </div>
                    @endif
                    @if($loan->disbursed_at)
                    <div>
                        <p class="text-content-tertiary">Disbursed</p>
                        <p class="font-medium text-content-primary">{{ $loan->disbursed_at->format('M d, Y') }}</p>
                    </div>
                    @endif
                    @if($loan->maturity_date)
                    <div>
                        <p class="text-content-tertiary">Maturity Date</p>
                        <p class="font-medium text-content-primary">{{ $loan->maturity_date->format('M d, Y') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-content-tertiary">Applied</p>
                        <p class="font-medium text-content-primary">{{ $loan->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
                @if($loan->purpose)
                    <div class="mt-4 pt-4 border-t border-surface-border">
                        <p class="text-content-tertiary text-sm">Purpose</p>
                        <p class="text-content-primary text-sm mt-1">{{ $loan->purpose }}</p>
                    </div>
                @endif
            </div>

            {{-- Repayment Schedule --}}
            @if($loan->repaymentSchedules->count() > 0)
            <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
                <div class="px-5 py-4 border-b border-surface-border">
                    <h3 class="text-sm font-semibold text-content-secondary uppercase tracking-wider">Repayment Schedule</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-surface-border">
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">#</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">Due Date</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">Principal</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">Interest</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">Total</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">Late Fee</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-border">
                            @foreach($loan->repaymentSchedules as $schedule)
                                @php
                                    $rowClass = match($schedule->status) {
                                        'paid' => 'bg-gain/5',
                                        'overdue' => 'bg-loss/5',
                                        'due' => 'bg-warning/5',
                                        'partial' => 'bg-info/5',
                                        default => '',
                                    };
                                    $schedStatusColor = match($schedule->status) {
                                        'paid' => 'bg-gain/10 text-gain',
                                        'overdue' => 'bg-loss/10 text-loss',
                                        'due' => 'bg-warning/10 text-warning',
                                        'partial' => 'bg-info/10 text-info',
                                        default => 'bg-surface-overlay text-content-tertiary',
                                    };
                                @endphp
                                <tr class="{{ $rowClass }} hover:bg-surface-overlay/50 transition-colors">
                                    <td class="px-5 py-3 text-content-tertiary">{{ $schedule->installment_number }}</td>
                                    <td class="px-5 py-3 text-content-primary">{{ $schedule->due_date->format('M d, Y') }}</td>
                                    <td class="px-5 py-3 text-content-primary">@money($schedule->principal_amount)</td>
                                    <td class="px-5 py-3 text-content-primary">@money($schedule->interest_amount)</td>
                                    <td class="px-5 py-3 font-medium text-content-primary">@money($schedule->total_amount)</td>
                                    <td class="px-5 py-3 text-content-primary">
                                        @if($schedule->late_fee > 0)
                                            <span class="text-loss">@money($schedule->late_fee)</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $schedStatusColor }}">
                                            {{ ucfirst($schedule->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if(in_array($schedule->status, ['due', 'overdue', 'upcoming', 'partial']) && in_array($loan->status, ['active', 'repaying']))
                                            <div x-data="{ showPayModal: false }">
                                                <button @click="showPayModal = true"
                                                        class="px-3 py-1 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-xs font-medium transition-colors">
                                                    Pay @money($schedule->total_due)
                                                </button>

                                                {{-- Payment Option Modal --}}
                                                <template x-teleport="body">
                                                    <div x-show="showPayModal" x-cloak
                                                         class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                         @keydown.escape.window="showPayModal = false">
                                                        <div class="fixed inset-0 bg-black/60" @click="showPayModal = false"></div>
                                                        <div class="relative bg-surface-raised border border-surface-border rounded-xl w-full max-w-lg max-h-[90vh] overflow-y-auto z-10"
                                                             @click.stop>

                                                            {{-- Modal Header --}}
                                                            <div class="px-5 py-4 border-b border-surface-border flex items-center justify-between">
                                                                <div>
                                                                    <h3 class="text-base font-semibold text-content-primary">Pay Installment #{{ $schedule->installment_number }}</h3>
                                                                    <p class="text-sm text-content-secondary mt-0.5">Amount due: <span class="text-primary font-semibold">@money($schedule->total_due)</span></p>
                                                                </div>
                                                                <button @click="showPayModal = false" class="text-content-tertiary hover:text-content-primary transition-colors">
                                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                </button>
                                                            </div>

                                                            <div class="p-5 space-y-5">
                                                                {{-- Option A: Pay from Balance --}}
                                                                <div class="rounded-lg border border-surface-border p-4">
                                                                    <h4 class="text-sm font-semibold text-content-primary mb-2">Pay from Account Balance</h4>
                                                                    <p class="text-xs text-content-secondary mb-3">
                                                                        Available balance: <span class="font-medium text-content-primary">@money(auth()->user()->available_bal)</span>
                                                                    </p>
                                                                    <form action="{{ route('loans.repay', $loan) }}" method="POST"
                                                                          onsubmit="return confirm('Deduct {{ \App\Helpers\CurrencyHelper::formatForUser($schedule->total_due) }} from your account balance?')">
                                                                        @csrf
                                                                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                                        <button type="submit"
                                                                                class="w-full px-4 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors
                                                                                       {{ auth()->user()->available_bal < $schedule->total_due ? 'opacity-50 cursor-not-allowed' : '' }}"
                                                                                {{ auth()->user()->available_bal < $schedule->total_due ? 'disabled' : '' }}>
                                                                            Pay @money($schedule->total_due) from Balance
                                                                        </button>
                                                                    </form>
                                                                    @if(auth()->user()->available_bal < $schedule->total_due)
                                                                        <p class="text-xs text-loss mt-2">Insufficient balance. Use the deposit option below.</p>
                                                                    @endif
                                                                </div>

                                                                {{-- Divider --}}
                                                                <div class="flex items-center gap-3">
                                                                    <div class="flex-1 border-t border-surface-border"></div>
                                                                    <span class="text-xs text-content-tertiary uppercase tracking-wider">or</span>
                                                                    <div class="flex-1 border-t border-surface-border"></div>
                                                                </div>

                                                                {{-- Option B: Pay via Deposit --}}
                                                                <div class="rounded-lg border border-surface-border p-4" x-data="{ selectedMethod: null }">
                                                                    <h4 class="text-sm font-semibold text-content-primary mb-2">Pay via New Deposit</h4>
                                                                    <p class="text-xs text-content-secondary mb-3">
                                                                        Make a deposit using one of the methods below. Your loan payment will be applied once admin verifies the deposit.
                                                                    </p>

                                                                    @if($dmethods->count() > 0)
                                                                        <form action="{{ route('loans.repay-deposit', $loan) }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                                                            <input type="hidden" name="payment_method" x-model="selectedMethod">

                                                                            <div class="grid grid-cols-2 gap-2 mb-3">
                                                                                @foreach($dmethods as $dm)
                                                                                    <button type="button"
                                                                                            @click="selectedMethod = '{{ $dm->id }}'"
                                                                                            :class="selectedMethod == '{{ $dm->id }}' ? 'border-primary bg-primary/10 ring-1 ring-primary' : 'border-surface-border hover:border-content-tertiary'"
                                                                                            class="flex items-center gap-2 p-3 rounded-lg border transition-colors text-left">
                                                                                        @if($dm->img_url)
                                                                                            <img src="{{ asset($dm->img_url) }}" alt="{{ $dm->name }}" class="w-8 h-8 rounded object-contain bg-surface-overlay p-0.5">
                                                                                        @else
                                                                                            <div class="w-8 h-8 rounded bg-surface-overlay flex items-center justify-center">
                                                                                                <x-icon name="banknotes" class="w-4 h-4 text-content-tertiary" />
                                                                                            </div>
                                                                                        @endif
                                                                                        <span class="text-xs font-medium text-content-primary truncate">{{ $dm->name }}</span>
                                                                                    </button>
                                                                                @endforeach
                                                                            </div>

                                                                            <button type="submit"
                                                                                    x-bind:disabled="!selectedMethod"
                                                                                    :class="selectedMethod ? 'bg-primary hover:bg-primary-dark' : 'bg-surface-overlay cursor-not-allowed opacity-50'"
                                                                                    class="w-full px-4 py-2.5 rounded-lg text-content-inverse text-sm font-medium transition-colors">
                                                                                Continue to Payment
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <p class="text-xs text-content-tertiary">No deposit methods available.</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        @elseif($schedule->status === 'paid')
                                            <span class="text-xs text-content-tertiary">{{ $schedule->paid_at ? $schedule->paid_at->format('M d') : '' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>

        {{-- Right: Progress & Summary --}}
        <div class="space-y-4">
            @if($loan->total_repayable > 0)
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h4 class="text-xs font-semibold text-content-tertiary uppercase tracking-wider mb-4">Repayment Progress</h4>
                <div class="flex items-center justify-center mb-4">
                    <div class="relative w-32 h-32">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="currentColor" stroke-width="3" class="text-surface-overlay"/>
                            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                  fill="none" stroke="currentColor" stroke-width="3" class="text-primary"
                                  stroke-dasharray="{{ $loan->progress_percentage }}, 100"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xl font-bold text-content-primary">{{ $loan->progress_percentage }}%</span>
                        </div>
                    </div>
                </div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-content-tertiary">Paid</span>
                        <span class="text-gain font-medium">@money($loan->total_repaid)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-content-tertiary">Remaining</span>
                        <span class="text-content-primary font-medium">@money($loan->remaining_balance)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-content-tertiary">Total</span>
                        <span class="text-content-primary font-medium">@money($loan->total_repayable)</span>
                    </div>
                </div>
            </div>
            @endif

            @if($loan->next_installment && in_array($loan->status, ['active', 'repaying']))
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h4 class="text-xs font-semibold text-content-tertiary uppercase tracking-wider mb-3">Next Payment</h4>
                <p class="text-lg font-bold text-content-primary">@money($loan->next_installment->total_due)</p>
                <p class="text-sm text-content-secondary">Due {{ $loan->next_installment->due_date->format('M d, Y') }}</p>
                @if($loan->next_installment->due_date->isPast())
                    <p class="text-xs text-loss mt-1 font-medium">Overdue</p>
                @elseif($loan->next_installment->due_date->diffInDays(now()) <= 7)
                    <p class="text-xs text-warning mt-1">Due in {{ $loan->next_installment->due_date->diffInDays(now()) }} days</p>
                @endif
            </div>
            @endif

            @if($loan->status === 'completed')
            <div class="rounded-xl bg-gain/10 border border-gain/20 p-5 text-center">
                <p class="text-gain font-semibold">Loan Fully Repaid</p>
                <p class="text-xs text-gain/70 mt-1">All installments have been paid</p>
            </div>
            @endif
        </div>
    </div>

@endsection