@extends('layouts.admin-dash')
@section('title', $title)
@section('content')
<div class="space-y-6">

    <x-admin.page-header title="{{ $company->name }}" subtitle="{{ $company->symbol }} — {{ $company->sector ?? 'Pre-IPO Company' }}">
        <x-slot name="actions">
            <a href="{{ route('admin.pre-ipo.edit', $company->id) }}"
               class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                Edit
            </a>
            <a href="{{ route('admin.pre-ipo.index') }}"
               class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
                Back
            </a>
        </x-slot>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <x-admin.stat-card label="Share Price" :value="'$' . number_format($company->share_price, 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        <x-admin.stat-card label="Initial Price" :value="'$' . number_format($company->initial_price, 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
        <x-admin.stat-card label="Shares Sold" :value="number_format($company->shares_sold) . ' / ' . number_format($company->total_shares)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75z" /></svg>' />
        <x-admin.stat-card label="Revenue" :value="'$' . number_format($holdings->sum('total_cost'), 2)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75" /></svg>' />
        <x-admin.stat-card label="Status" :value="ucfirst($company->status)" icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Company Info --}}
        <div class="lg:col-span-1">
            <x-admin.card title="Company Profile">
                <div class="space-y-4">
                    @if($company->logo)
                        <img src="{{ asset('storage/app/public/' . $company->logo) }}" alt="{{ $company->name }}" class="w-20 h-20 rounded-xl object-cover border border-border">
                    @endif
                    <div>
                        <p class="text-xs text-content-muted uppercase">Sector</p>
                        <p class="text-sm text-content">{{ $company->sector ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted uppercase">Expected IPO</p>
                        <p class="text-sm text-content">{{ $company->expected_ipo_date ? $company->expected_ipo_date->format('M d, Y') : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-muted uppercase">Price Change</p>
                        <p class="text-sm font-medium {{ $company->price_change_percent >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $company->price_change_percent >= 0 ? '+' : '' }}{{ $company->price_change_percent }}%
                        </p>
                    </div>
                    @if($company->description)
                        <div>
                            <p class="text-xs text-content-muted uppercase">Description</p>
                            <p class="text-sm text-content-secondary mt-1">{{ $company->description }}</p>
                        </div>
                    @endif
                </div>
            </x-admin.card>
        </div>

        {{-- Holdings Table --}}
        <div class="lg:col-span-2">
            <x-admin.table-card title="Shareholders ({{ $holdings->total() }})">
                <table class="w-full">
                    <thead>
                        <tr class="bg-surface-alt">
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">User</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Shares</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Avg Cost</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Total Cost</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right">Current Value</th>
                            <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($holdings as $holding)
                            <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                                <td class="px-4 py-3.5 text-sm text-content">
                                    {{ $holding->user->name ?? 'N/A' }}
                                    <span class="text-content-muted text-xs">#{{ $holding->user_id }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-sm text-content text-right font-medium">{{ number_format($holding->shares) }}</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($holding->purchase_price, 2) }}</td>
                                <td class="px-4 py-3.5 text-sm text-content-secondary text-right">${{ number_format($holding->total_cost, 2) }}</td>
                                <td class="px-4 py-3.5 text-sm text-right font-medium {{ $holding->unrealized_pnl >= 0 ? 'text-success' : 'text-danger' }}">
                                    ${{ number_format($holding->current_value, 2) }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    <x-admin.badge :type="$holding->status === 'active' ? 'success' : 'info'">{{ ucfirst($holding->status) }}</x-admin.badge>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-content-muted">No shareholders yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if($holdings->hasPages())
                    <div class="px-4 py-3 border-t border-border">{{ $holdings->links() }}</div>
                @endif
            </x-admin.table-card>
        </div>

    </div>

    {{-- Price History Chart --}}
    @if($priceHistory->count() > 1)
    <x-admin.card title="Price History">
        <div style="height: 300px;">
            <canvas id="priceChart"></canvas>
        </div>
    </x-admin.card>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        new Chart(document.getElementById('priceChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($priceHistory->pluck('created_at')->map(fn($d) => $d->format('M d, Y'))->reverse()->values()) !!},
                datasets: [{
                    label: 'Share Price ($)',
                    data: {!! json_encode($priceHistory->pluck('price')->reverse()->values()) !!},
                    borderColor: 'rgb(15, 118, 110)',
                    backgroundColor: 'rgba(15, 118, 110, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: false, grid: { color: 'rgba(0,0,0,0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    </script>
    @endpush
    @endif

</div>
@endsection
