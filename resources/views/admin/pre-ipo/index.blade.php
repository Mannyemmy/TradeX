@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <x-admin.page-header title="Pre-IPO Companies" subtitle="Manage pre-IPO company listings and share offerings">
        <x-slot name="actions">
            <a href="{{ route('admin.pre-ipo.all-holdings') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" /></svg>
                All Holdings
            </a>
            <a href="{{ route('admin.pre-ipo.create') }}"
               class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Company
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
        <x-admin.stat-card label="Total Companies" :value="$companies->total()" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>' />
        <x-admin.stat-card label="Open Rounds" :value="App\Models\PreIpoCompany::where('status','open')->count()" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>' />
        <x-admin.stat-card label="Total Shares Sold" :value="number_format(App\Models\PreIpoCompany::sum('shares_sold'))" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z" /></svg>' />
        <x-admin.stat-card label="Total Revenue" :value="'$' . number_format(App\Models\PreIpoHolding::sum('total_cost'), 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    </div>

    {{-- Table --}}
    <x-admin.table-card title="All Companies">
        <table class="w-full">
            <thead>
                <tr class="bg-surface-alt">
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Company</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Symbol</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Sector</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Share Price</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Sold / Total</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Featured</th>
                    <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($companies as $company)
                    <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                        <td class="px-4 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($company->logo)
                                    <img src="{{ asset('storage/app/public/' . $company->logo) }}" alt="{{ $company->name }}" class="w-8 h-8 rounded-lg object-cover border border-border">
                                @else
                                    <div class="w-8 h-8 rounded-lg bg-primary-light flex items-center justify-center text-primary text-xs font-bold">{{ substr($company->symbol, 0, 2) }}</div>
                                @endif
                                <span class="text-sm font-medium text-content">{{ $company->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3.5 text-sm font-mono text-content-secondary">{{ $company->symbol }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $company->sector ?? '—' }}</td>
                        <td class="px-4 py-3.5 text-sm text-content text-right font-medium">${{ number_format($company->share_price, 2) }}</td>
                        <td class="px-4 py-3.5 text-sm text-content-secondary text-right">{{ number_format($company->shares_sold) }} / {{ number_format($company->total_shares) }}</td>
                        <td class="px-4 py-3.5 text-center">
                            @php
                                $statusColors = [
                                    'upcoming' => 'info',
                                    'open' => 'success',
                                    'closed' => 'warning',
                                    'ipo' => 'neutral',
                                    'public' => 'success',
                                ];
                            @endphp
                            <x-admin.badge :type="$statusColors[$company->status] ?? 'neutral'">{{ ucfirst($company->status) }}</x-admin.badge>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($company->is_featured)
                                <span class="text-warning">★</span>
                            @else
                                <span class="text-content-muted">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.pre-ipo.show', $company->id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">View</a>
                                <a href="{{ route('admin.pre-ipo.edit', $company->id) }}" class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Edit</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-content-muted">
                            No Pre-IPO companies found. <a href="{{ route('admin.pre-ipo.create') }}" class="text-primary hover:underline">Add one now</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($companies->hasPages())
            <div class="px-4 py-3 border-t border-border">
                {{ $companies->links() }}
            </div>
        @endif
    </x-admin.table-card>

</div>
@endsection
