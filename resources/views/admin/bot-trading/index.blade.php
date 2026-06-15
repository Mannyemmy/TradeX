@extends('layouts.admin-dash')
@section('title', $title)
@section('content')

<x-admin.page-header title="Manage Trading Bots" subtitle="Create and manage AI trading bots">
    <x-slot name="actions">
        <a href="{{ route('admin.bot-trading.subscriptions') }}" class="bg-surface-alt text-content border border-border hover:bg-surface-alt/80 rounded-lg px-4 py-2 text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
            Subscriptions
        </a>
        <a href="{{ route('admin.bot-trading.create') }}" class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Create Bot
        </a>
    </x-slot>
</x-admin.page-header>

{{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mt-6">
    <x-admin.stat-card label="Total Bots" :value="$totalBots"
        icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9V7.5z" /></svg>' />
    <x-admin.stat-card label="Active Bots" :value="$activeBots"
        icon='<svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    <x-admin.stat-card label="Active Subscribers" :value="$totalActiveSubscribers"
        icon='<svg class="w-5 h-5 text-info" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>' />
    <x-admin.stat-card label="Total Invested" :value="'$' . number_format($totalInvested, 2)"
        icon='<svg class="w-5 h-5 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
</div>

{{-- Bots Table --}}
<div class="mt-6">
    <x-admin.table-card title="Trading Bots">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Bot</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Strategy</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Win Rate</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Daily ROI</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Investment Range</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Interval</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Subscribers</th>
                    <th class="text-left text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Status</th>
                    <th class="text-right text-xs font-medium text-content-muted uppercase tracking-wide px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bots as $bot)
                    <tr class="border-b border-border hover:bg-surface-alt/50" x-data="{ active: {{ $bot->is_active ? 'true' : 'false' }} }">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-primary-light flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9V7.5z" /></svg>
                                </div>
                                <div>
                                    <p class="font-medium text-content">{{ $bot->name }}</p>
                                    <p class="text-xs text-content-muted">Max {{ $bot->max_duration_days }} days</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <x-admin.badge type="{{ $bot->strategy_type === 'scalping' ? 'info' : ($bot->strategy_type === 'day_trading' ? 'warning' : 'success') }}">
                                {{ $bot->strategy_label }}
                            </x-admin.badge>
                        </td>
                        <td class="px-5 py-3 font-medium text-content">{{ number_format($bot->win_rate, 1) }}%</td>
                        <td class="px-5 py-3 text-success font-medium">{{ number_format($bot->expected_roi, 2) }}%</td>
                        <td class="px-5 py-3 text-content">${{ number_format($bot->min_investment) }} – ${{ number_format($bot->max_investment) }}</td>
                        <td class="px-5 py-3 text-content">{{ $bot->trade_interval_minutes }}m</td>
                        <td class="px-5 py-3 text-content">{{ $bot->active_subscribers_count ?? 0 }}</td>
                        <td class="px-5 py-3">
                            <span x-show="active" class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-success-light text-success">Active</span>
                            <span x-show="!active" class="inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full bg-danger-light text-danger">Inactive</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.bot-trading.edit', $bot->id) }}" class="text-content-secondary hover:text-content transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </a>
                                <button @click="fetch('{{ route('admin.bot-trading.toggle', $bot->id) }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(r => r.json()).then(d => { active = d.is_active })" class="text-content-secondary hover:text-warning transition-colors" title="Toggle Active">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1012.728 0M12 3v9" /></svg>
                                </button>
                                <form action="{{ route('admin.bot-trading.destroy', $bot->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this bot?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-content-secondary hover:text-danger transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-8 text-center text-content-muted">No trading bots found. Create one to get started.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>
    <div class="mt-4">{{ $bots->links() }}</div>
</div>

@endsection
