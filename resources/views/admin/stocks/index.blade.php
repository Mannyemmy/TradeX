@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <x-admin.page-header title="Stock Shares" subtitle="Manage stock listings and user positions">
        <x-slot name="actions">
            <a href="{{ route('admin.stocks.trades') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                All Trades
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif
    @if(session('message'))
        <x-admin.alert type="danger" :dismissible="true">{{ session('message') }}</x-admin.alert>
    @endif

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-admin.stat-card label="Total Stocks" :value="$stocks->count()" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>' />
        <x-admin.stat-card label="Active Stocks" :value="$stocks->where('is_active', true)->count()" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        <x-admin.stat-card label="Total Holders" :value="array_sum(array_map(function($v){ $p = explode('|', $v); return (int)$p[0]; }, $positionStats))" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>' />
        <x-admin.stat-card label="Total Invested" :value="'$' . number_format(array_sum(array_map(function($v){ $p = explode('|', $v); return (float)($p[2] ?? 0); }, $positionStats)), 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    </div>

    {{-- Stocks Table --}}
    <x-admin.table-card title="All Stocks">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Stock</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Price</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">24h Change</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Holders</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Total Shares</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Total Invested</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $stock)
                    @php
                        $stats = isset($positionStats[$stock->id]) ? explode('|', $positionStats[$stock->id]) : [0, 0, 0];
                        $holders = (int)$stats[0];
                        $totalShares = (float)$stats[1];
                        $totalInv = (float)$stats[2];
                    @endphp
                    <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($stock->logo_url)
                                    <img src="{{ $stock->logo_url }}" alt="{{ $stock->symbol }}" class="w-8 h-8 rounded-lg object-cover border border-border">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center text-primary text-xs font-bold">{{ substr($stock->symbol, 0, 2) }}</div>
                                @endif
                                <div>
                                    <span class="text-sm font-medium text-content">{{ $stock->symbol }}</span>
                                    <p class="text-xs text-content-muted">{{ $stock->name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content text-right font-medium">${{ number_format($stock->price, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-right">
                            <span class="{{ $stock->price_change_pct_24h >= 0 ? 'text-success' : 'text-danger' }}">
                                {{ $stock->price_change_pct_24h >= 0 ? '+' : '' }}{{ number_format($stock->price_change_pct_24h, 2) }}%
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">{{ $holders }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">{{ number_format($totalShares, 4) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content text-right font-medium">${{ number_format($totalInv, 2) }}</td>
                        <td class="px-4 py-3.5 text-center">
                            <x-admin.badge :type="$stock->is_active ? 'success' : 'neutral'">{{ $stock->is_active ? 'Active' : 'Inactive' }}</x-admin.badge>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-content-muted">
                            No stock assets found. Add stocks via <a href="{{ route('admin.assets.index') }}" class="text-primary hover:underline">Trading Assets</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

</div>
@endsection
