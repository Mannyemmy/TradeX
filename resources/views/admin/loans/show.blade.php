@extends('layouts.admin-dash')

@section('title', $title)

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header :title="$title" subtitle="Loan application details and actions">
            <x-slot name="actions">
                <a href="{{ route('admin.loans.index', ['tab' => $loan->status === 'pending' ? 'pending' : 'all']) }}"
                   class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to Loans
                </a>
            </x-slot>
        </x-admin.page-header>

        {{-- Flash Messages --}}
        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif
        @if(session('error'))
            <x-admin.alert type="danger" :dismissible="true">{{ session('error') }}</x-admin.alert>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Loan Summary --}}
            <x-admin.card>
                <h3 class="text-lg font-semibold text-content mb-4">Loan Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Loan ID</span>
                        <span class="text-sm font-medium text-content">#{{ $loan->id }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Plan</span>
                        <span class="text-sm text-content-secondary">{{ $loan->loanPlan->name ?? $loan->credit_facility }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Requested Amount</span>
                        <span class="text-sm font-medium text-content">{{ $settings->currency }}{{ number_format($loan->amount, 2) }}</span>
                    </div>
                    @if($loan->approved_amount)
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Approved Amount</span>
                        <span class="text-sm font-medium text-success">{{ $settings->currency }}{{ number_format($loan->approved_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Interest Rate</span>
                        <span class="text-sm text-content-secondary">{{ $loan->interest_rate }}% APR ({{ ucfirst($loan->interest_type) }})</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Duration</span>
                        <span class="text-sm text-content-secondary">{{ $loan->duration }} months</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Processing Fee</span>
                        <span class="text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($loan->processing_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Total Repayable</span>
                        <span class="text-sm font-medium text-content">{{ $settings->currency }}{{ number_format($loan->total_repayable, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Total Repaid</span>
                        <span class="text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($loan->total_repaid, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Status</span>
                        <span>
                            @php
                                $statusType = match($loan->status) {
                                    'pending' => 'warning',
                                    'active', 'repaying' => 'info',
                                    'completed' => 'success',
                                    'defaulted', 'rejected' => 'danger',
                                    default => 'neutral',
                                };
                            @endphp
                            <x-admin.badge :type="$statusType">{{ ucfirst($loan->status) }}</x-admin.badge>
                        </span>
                    </div>
                    @if($loan->rejection_reason)
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Rejection Reason</span>
                        <span class="text-sm text-danger">{{ $loan->rejection_reason }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Applied</span>
                        <span class="text-sm text-content-secondary">{{ $loan->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    @if($loan->disbursed_at)
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Disbursed</span>
                        <span class="text-sm text-content-secondary">{{ $loan->disbursed_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-2 border-b border-border">
                        <span class="text-sm text-content-muted">Monthly Income</span>
                        <span class="text-sm text-content-secondary">{{ $loan->monthly_income }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-sm text-content-muted">Purpose</span>
                        <span class="text-sm text-content-secondary">{{ $loan->purpose }}</span>
                    </div>
                </div>
            </x-admin.card>

            <div class="space-y-6">
                {{-- Applicant Info --}}
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">Applicant</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-border">
                            <span class="text-sm text-content-muted">Name</span>
                            <span class="text-sm font-medium text-content">{{ $loan->user->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-border">
                            <span class="text-sm text-content-muted">Email</span>
                            <span class="text-sm text-content-secondary">{{ $loan->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-sm text-content-muted">Account Balance</span>
                            <span class="text-sm font-medium text-content">{{ $settings->currency }}{{ number_format($loan->user->account_bal ?? 0, 2) }}</span>
                        </div>
                    </div>
                </x-admin.card>

                {{-- Eligibility Checks --}}
                @if($eligibility)
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">Eligibility Checks</h3>
                    <div class="space-y-2">
                        @foreach($eligibility as $check)
                        <div class="flex items-center gap-3 py-2 {{ !$loop->last ? 'border-b border-border' : '' }}">
                            <x-admin.badge :type="$check['passed'] ? 'success' : 'danger'">
                                {{ $check['passed'] ? 'PASS' : 'FAIL' }}
                            </x-admin.badge>
                            <span class="text-sm text-content">{{ $check['label'] }}</span>
                            <span class="text-xs text-content-muted ml-auto">{{ $check['detail'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </x-admin.card>
                @endif

                {{-- Actions for pending loans --}}
                @if($loan->status === 'pending')
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">Actions</h3>

                    {{-- Approve Form --}}
                    <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="mb-5">
                        @csrf @method('PUT')
                        <x-admin.form-group label="Approved Amount" for="approved_amount" helper="Leave blank for requested amount">
                            <input type="number" step="0.01" id="approved_amount" name="approved_amount" placeholder="{{ number_format($loan->amount, 2) }}"
                                   class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                        </x-admin.form-group>
                        <button type="submit"
                                class="mt-3 bg-success-light text-success hover:opacity-80 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                            Approve & Disburse
                        </button>
                    </form>

                    <div class="border-t border-border pt-5">
                        {{-- Reject Form --}}
                        <form action="{{ route('admin.loans.reject', $loan) }}" method="POST">
                            @csrf @method('PUT')
                            <x-admin.form-group label="Rejection Reason" for="rejection_reason" :required="true">
                                <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                                          class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors min-h-[80px] resize-y"></textarea>
                            </x-admin.form-group>
                            <button type="submit"
                                    class="mt-3 bg-danger text-white hover:bg-danger/90 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                Reject Application
                            </button>
                        </form>
                    </div>
                </x-admin.card>
                @endif

                {{-- Mark Defaulted (for active loans) --}}
                @if(in_array($loan->status, ['active', 'repaying']))
                <x-admin.card>
                    <h3 class="text-lg font-semibold text-content mb-4">Risk Actions</h3>
                    <form action="{{ route('admin.loans.default', $loan) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to mark this loan as defaulted?');">
                        @csrf @method('PUT')
                        <button type="submit"
                                class="bg-danger text-white hover:bg-danger/90 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                            Mark as Defaulted
                        </button>
                    </form>
                </x-admin.card>
                @endif
            </div>
        </div>

        {{-- Repayment Schedule --}}
        @if($loan->repaymentSchedules->count() > 0)
        <x-admin.card>
            <h3 class="text-lg font-semibold text-content mb-4">Repayment Schedule</h3>

            @if($loan->total_repayable > 0)
            <div class="w-full bg-surface-alt rounded-full h-4 mb-5 overflow-hidden">
                <div class="bg-success h-4 rounded-full flex items-center justify-center text-[10px] font-medium text-white transition-all"
                     style="width: {{ min($loan->progress_percentage, 100) }}%">
                    {{ $loan->progress_percentage }}%
                </div>
            </div>
            @endif

            <div class="overflow-x-auto rounded-xl border border-border">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-alt">
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">#</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Due Date</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Principal</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Interest</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Total</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Late Fee</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Paid</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Status</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Paid At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loan->repaymentSchedules as $schedule)
                        <tr class="border-b border-border last:border-0 {{ $schedule->status === 'overdue' ? 'bg-danger-light/30' : ($schedule->status === 'paid' ? 'bg-success-light/30' : '') }} hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $schedule->installment_number }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $schedule->due_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($schedule->principal_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($schedule->interest_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $settings->currency }}{{ number_format($schedule->total_amount, 2) }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $schedule->late_fee > 0 ? $settings->currency . number_format($schedule->late_fee, 2) : '—' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $schedule->paid_amount > 0 ? $settings->currency . number_format($schedule->paid_amount, 2) : '—' }}</td>
                            <td class="px-4 py-3.5">
                                @php
                                    $schedType = match($schedule->status) {
                                        'paid' => 'success',
                                        'overdue' => 'danger',
                                        'due' => 'warning',
                                        'partial' => 'info',
                                        default => 'neutral',
                                    };
                                @endphp
                                <x-admin.badge :type="$schedType">{{ ucfirst($schedule->status) }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-muted">{{ $schedule->paid_at ? $schedule->paid_at->format('M d, Y') : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-admin.card>
        @endif
    </div>
@endsection
