@extends('layouts.admin-dash')

@section('title', $title)

@section('content')
    <div class="space-y-6">
        {{-- Page Header --}}
        <x-admin.page-header :title="$title" subtitle="Manage loan plans and applications" />

        {{-- Flash Messages --}}
        @if(session('success'))
            <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
        @endif
        @if(session('error'))
            <x-admin.alert type="danger" :dismissible="true">{{ session('error') }}</x-admin.alert>
        @endif

        {{-- Summary Stats --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <x-admin.stat-card
                label="Total Disbursed"
                :value="$settings->currency . number_format($stats['total_disbursed'], 2)"
                :icon="'<svg class=\'w-5 h-5 text-primary\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
            />
            <x-admin.stat-card
                label="Outstanding"
                :value="$settings->currency . number_format($stats['total_outstanding'], 2)"
                :icon="'<svg class=\'w-5 h-5 text-primary\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z\' /></svg>'"
            />
            <x-admin.stat-card
                label="Total Collected"
                :value="$settings->currency . number_format($stats['total_collected'], 2)"
                :icon="'<svg class=\'w-5 h-5 text-primary\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z\' /></svg>'"
            />
            <x-admin.stat-card
                label="Pending / Defaulted"
                :value="$stats['pending_count'] . ' / ' . $stats['default_count']"
                :icon="'<svg class=\'w-5 h-5 text-primary\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z\' /></svg>'"
            />
        </div>

        {{-- Tab Navigation --}}
        <div class="flex gap-1 border-b border-border overflow-x-auto">
            @php
                $tabs = [
                    'plans' => 'Loan Plans',
                    'pending' => 'Pending',
                    'active' => 'Active',
                    'completed' => 'Completed',
                    'defaulted' => 'Defaulted',
                    'all' => 'All',
                ];
            @endphp
            @foreach($tabs as $key => $label)
                <a href="{{ route('admin.loans.index', ['tab' => $key]) }}"
                   class="px-4 py-2.5 text-sm font-medium transition-colors border-b-2 whitespace-nowrap {{ $tab === $key ? 'text-primary border-primary' : 'text-content-muted hover:text-content border-transparent' }}">
                    {{ $label }}
                    @if($key === 'pending' && $stats['pending_count'] > 0)
                        <x-admin.badge type="warning" class="ml-1">{{ $stats['pending_count'] }}</x-admin.badge>
                    @endif
                </a>
            @endforeach
        </div>

        @if($tab === 'plans')
            {{-- ── LOAN PLANS TAB ── --}}
            <x-admin.table-card title="Loan Plans">
                <x-slot name="actions">
                    <a href="{{ route('admin.loan-plans.create') }}"
                       class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Create Plan
                    </a>
                </x-slot>
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-alt">
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">#</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Name</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Rate (APR)</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Amount Range</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Duration</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Fee</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Active Loans</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Status</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $plan->name }}</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $plan->interest_rate }}% ({{ ucfirst($plan->interest_type) }})</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($plan->min_amount) }} – {{ $settings->currency }}{{ number_format($plan->max_amount) }}</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $plan->min_duration }}–{{ $plan->max_duration }} mo</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $plan->processing_fee }}%</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $plan->active_loans_count }}</td>
                                <td class="px-4 py-3.5">
                                    <x-admin.badge :type="$plan->is_active ? 'success' : 'neutral'">
                                        {{ $plan->is_active ? 'Active' : 'Inactive' }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.loan-plans.edit', $plan) }}"
                                           class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.loan-plans.toggle', $plan) }}" method="POST" class="inline">
                                            @csrf @method('PUT')
                                            <button type="submit"
                                                    class="{{ $plan->is_active ? 'bg-warning-light text-warning' : 'bg-success-light text-success' }} hover:opacity-80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                                {{ $plan->is_active ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-12 text-center text-content-muted">
                                    No loan plans yet. Create one to get started.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-admin.table-card>
        @else
            {{-- ── LOANS TAB ── --}}
            <x-admin.table-card :title="'Loan Applications — ' . ucfirst($tab)">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-alt">
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">#</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">User</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Plan</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Amount</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Rate</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Duration</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Total Repayable</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Repaid</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Status</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Date</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="text-sm font-medium text-content">{{ $loan->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-content-muted">{{ $loan->user->email ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $loan->loanPlan->name ?? $loan->credit_facility }}</td>
                                <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $settings->currency }}{{ number_format($loan->approved_amount ?? $loan->amount, 2) }}</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $loan->interest_rate }}%</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $loan->duration }} mo</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($loan->total_repayable, 2) }}</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $settings->currency }}{{ number_format($loan->total_repaid, 2) }}</td>
                                <td class="px-4 py-3.5">
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
                                </td>
                                <td class="px-4 py-3.5 text-sm text-content-muted">{{ $loan->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.loans.show', $loan) }}"
                                           class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            View
                                        </a>
                                        <a href="{{ route('admin.loans.edit', $loan) }}"
                                           class="inline-flex items-center gap-1 bg-warning text-content-inverse hover:bg-warning/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                                           title="Edit / Backdate">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                            Edit
                                        </a>
                                        @if($loan->status === 'pending')
                                            <form action="{{ route('admin.loans.approve', $loan) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <button type="submit"
                                                        class="bg-success-light text-success hover:opacity-80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                                    Approve
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-12 text-center text-content-muted">
                                    No loan applications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-admin.table-card>
        @endif
    </div>
@endsection
