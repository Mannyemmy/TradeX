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
            <h2 class="text-xl font-bold text-content-primary">My Loans</h2>
            <p class="text-sm text-content-secondary mt-1">Track and manage your loan applications</p>
        </div>
        <a href="{{ route('loans.create') }}" class="px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-medium transition-colors">
            Apply for Loan
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        @include('user.partials.stat-card', ['label' => 'Active Loans', 'value' => $stats['active_count'], 'icon' => 'wallet'])
        @include('user.partials.stat-card', ['label' => 'Total Borrowed', 'value' => \App\Helpers\CurrencyHelper::formatForUser($stats['total_borrowed']), 'icon' => 'wallet'])
        @include('user.partials.stat-card', ['label' => 'Total Repaid', 'value' => \App\Helpers\CurrencyHelper::formatForUser($stats['total_repaid']), 'icon' => 'wallet'])
        @include('user.partials.stat-card', ['label' => 'Pending', 'value' => $stats['pending_count'], 'icon' => 'wallet'])
    </div>

    {{-- Loans Table --}}
    <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-surface-border">
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">#</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Plan</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Amount</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Duration</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Repayment</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider">Date</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-content-tertiary uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-border">
                    @forelse($loans as $loan)
                        <tr class="hover:bg-surface-overlay/50 transition-colors">
                            <td class="px-5 py-3 text-content-tertiary">{{ $loop->iteration }}</td>
                            <td class="px-5 py-3 text-content-primary">{{ $loan->loanPlan->name ?? 'Legacy' }}</td>
                            <td class="px-5 py-3 font-medium text-content-primary">
                                @money($loan->approved_amount ?? $loan->amount)
                            </td>
                            <td class="px-5 py-3 text-content-primary">{{ $loan->duration }} mo</td>
                            <td class="px-5 py-3">
                                @if($loan->total_repayable > 0)
                                    <div class="flex items-center space-x-2">
                                        <div class="flex-1 h-1.5 rounded-full bg-surface-overlay overflow-hidden">
                                            <div class="h-full rounded-full bg-primary" style="width: {{ $loan->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-xs text-content-tertiary">{{ $loan->progress_percentage }}%</span>
                                    </div>
                                @else
                                    <span class="text-xs text-content-tertiary">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
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
                                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $color }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                                @if($loan->is_overdue)
                                    <span class="ml-1 px-2 py-0.5 text-xs font-medium rounded-full bg-loss/10 text-loss">Overdue</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-content-tertiary text-xs">{{ $loan->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-3">
                                <a href="{{ route('loans.show', $loan) }}" class="text-primary hover:text-primary-dark text-xs font-medium">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-content-tertiary">
                                No loan applications yet.
                                <a href="{{ route('loans.create') }}" class="text-primary hover:underline">Apply for your first loan</a>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
