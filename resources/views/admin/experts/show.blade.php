@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="{{ $expert->name }}" subtitle="Expert trader profile and active copy positions">
    <x-slot name="actions">
        <a href="{{ route('admin.experts.edit', $expert->id) }}" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium">Edit Expert</a>
        <a href="{{ route('admin.experts.index') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium">Back to List</a>
    </x-slot>
</x-admin.page-header>

{{-- Expert Profile Card --}}
<div class="mt-6">
    <x-admin.card>
        <div class="flex flex-col md:flex-row gap-6">
            @if($expert->profile_picture)
                <img src="{{ asset('storage/app/public/' . $expert->profile_picture) }}" alt="{{ $expert->name }}" class="w-32 h-32 rounded-xl object-cover shrink-0">
            @else
                <div class="w-32 h-32 rounded-xl bg-surface-alt flex items-center justify-center shrink-0">
                    <svg class="w-12 h-12 text-content-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </div>
            @endif
            <div>
                <h2 class="text-xl font-semibold text-content">{{ $expert->name }}</h2>
                <div class="mt-1">
                    <x-admin.badge>{{ $expert->area_of_expertise }}</x-admin.badge>
                    @if($expert->is_active)
                        <x-admin.badge type="success">Active</x-admin.badge>
                    @else
                        <x-admin.badge type="danger">Inactive</x-admin.badge>
                    @endif
                </div>
                @if($expert->bio)
                    <p class="text-sm text-content-secondary mt-3">{{ $expert->bio }}</p>
                @endif
            </div>
        </div>
    </x-admin.card>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-6">
    <x-admin.stat-card label="Daily ROI" :value="number_format($expert->daily_roi, 2) . '%'" />
    <x-admin.stat-card label="Duration" :value="$expert->duration_days . ' days'" />
    <x-admin.stat-card label="Followers" :value="number_format($expert->followers_count)" />
    <x-admin.stat-card label="Total ROI" :value="number_format($expert->total_roi, 2) . '%'" />
    <x-admin.stat-card label="Min Capital" :value="'$' . number_format($expert->min_startup_capital, 2)" />
    <x-admin.stat-card label="Win Rate" :value="number_format($expert->win_rate, 1) . '%'" />
</div>

{{-- Active Copiers Table --}}
<div class="mt-6">
    <x-admin.table-card title="Copy Positions">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">User</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Invested</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Profit</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Started</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Expires</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-right text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($positions as $pos)
                    <tr class="border-b border-border hover:bg-surface-alt/50">
                        <td class="px-5 py-3">
                            <div class="font-medium text-content">{{ $pos->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-content-muted">{{ $pos->user->email ?? '' }}</div>
                        </td>
                        <td class="px-5 py-3 text-content">${{ number_format($pos->invested_amount, 2) }}</td>
                        <td class="px-5 py-3 text-success font-medium">${{ number_format($pos->accumulated_profit, 2) }}</td>
                        <td class="px-5 py-3 text-content-secondary text-xs">{{ $pos->started_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3 text-content-secondary text-xs">{{ $pos->expires_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3">
                            @php
                                $badgeType = match($pos->status) { 'active' => 'success', 'stopped' => 'danger', 'completed' => 'info', 'settled' => 'warning', default => 'neutral' };
                            @endphp
                            <x-admin.badge :type="$badgeType">{{ ucfirst($pos->status) }}</x-admin.badge>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.copy-trades.show', $pos->id) }}" class="text-primary hover:text-primary-hover text-sm font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-5 py-8 text-center text-content-muted">No copy positions for this expert.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>
    <div class="mt-4">{{ $positions->links() }}</div>
</div>

@endsection
