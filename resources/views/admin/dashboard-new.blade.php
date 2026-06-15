@extends('layouts.admin-dash')
@section('title', 'Dashboard')

@section('content')

    {{-- ═══════════════════════ WELCOME BANNER ═══════════════════════ --}}
    <div class="bg-gradient-to-br from-primary to-primary-hover rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-primary-foreground">Dashboard</h2>
                <p class="text-primary-foreground/80 mt-1 text-sm">
                    Welcome back, {{ Auth('admin')->User()->firstName }} {{ Auth('admin')->User()->lastName }}!
                </p>
            </div>
            @if (Auth('admin')->User()->type == 'Super Admin' || Auth('admin')->User()->type == 'Admin')
                <div class="flex items-center gap-3">
                    <a href="{{ route('mdeposits') }}" class="bg-primary-foreground/20 hover:bg-primary-foreground/30 text-primary-foreground border border-primary-foreground/30 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Deposits
                    </a>
                    <a href="{{ route('mwithdrawals') }}" class="bg-primary-foreground/20 hover:bg-primary-foreground/30 text-primary-foreground border border-primary-foreground/30 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Withdrawals
                    </a>
                    <a href="{{ route('manageusers') }}" class="bg-primary-foreground text-primary rounded-lg px-4 py-2 text-sm font-medium hover:bg-primary-foreground/90 transition-colors">
                        Users
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- ═══════════════════════ FINANCIAL STATS ═══════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <x-admin.stat-card
            label="Total Deposits"
            :value="$settings->currency . number_format($total_deposited->first()->count ?? 0)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>'
        />
        <x-admin.stat-card
            label="Pending Deposits"
            :value="$settings->currency . number_format($pending_deposited->first()->count ?? 0)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Total Withdrawals"
            :value="$settings->currency . number_format($total_withdrawn->first()->count ?? 0)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>'
        />
        <x-admin.stat-card
            label="Pending Withdrawals"
            :value="$settings->currency . number_format($pending_withdrawn->first()->count ?? 0)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
    </div>

    {{-- ═══════════════════════ USER & SYSTEM STATS ═══════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <x-admin.stat-card
            label="Total Users"
            :value="number_format($user_count)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Active Users"
            :value="number_format($activeusers)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Blocked Users"
            :value="number_format($blockeusers)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>'
        />
        <x-admin.stat-card
            label="Investment Plans"
            :value="number_format($plans)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>'
        />
    </div>

    {{-- ═══════════════════════ TRADING STATS ═══════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <x-admin.stat-card
            label="Total Trades"
            :value="number_format($totalTrades)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'
        />
        <x-admin.stat-card
            label="Open Trades"
            :value="number_format($openTrades)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>'
        />
        <x-admin.stat-card
            label="Total Profit"
            :value="$settings->currency . number_format($totalProfit, 2)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Total Loss"
            :value="$settings->currency . number_format(abs($totalLoss), 2)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.286-4.286a11.948 11.948 0 014.306 6.43l.776 2.898m0 0l3.182-5.511m-3.182 5.51l-5.511-3.181" /></svg>'
        />
    </div>

    {{-- ═══════════════════════ PRE-IPO STATS ═══════════════════════ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <x-admin.stat-card
            label="Pre-IPO Companies"
            :value="number_format($preIpoCompanies)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>'
        />
        <x-admin.stat-card
            label="Active Offerings"
            :value="number_format($preIpoActive)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Total Shares Sold"
            :value="number_format($preIpoTotalSold)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'
        />
        <x-admin.stat-card
            label="Pre-IPO Revenue"
            :value="$settings->currency . number_format($preIpoRevenue, 2)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
    </div>

    {{-- ═══════════════════════ CHART ═══════════════════════ --}}
    <x-admin.card>
        <h3 class="text-lg font-semibold text-content mb-4">System Statistics</h3>
        <div class="overflow-auto">
            <canvas id="adminChart" height="100"></canvas>
        </div>
    </x-admin.card>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const isDark = document.documentElement.classList.contains('dark');
    const mutedColor = isDark ? 'rgb(100, 116, 139)' : 'rgb(148, 163, 184)';

    const ctx = document.getElementById('adminChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Deposits', 'Pending Deposits', 'Withdrawals', 'Pending Withdrawals', 'Total Transactions'],
            datasets: [{
                label: 'Amount in {{ $settings->currency }}',
                data: [
                    {{ $chart_pdepsoit }},
                    {{ $chart_pendepsoit }},
                    {{ $chart_pwithdraw }},
                    {{ $chart_pendwithdraw }},
                    {{ $chart_trans }}
                ],
                backgroundColor: [
                    'rgb(var(--chart-1) / 0.8)',
                    'rgb(var(--chart-2) / 0.8)',
                    'rgb(var(--chart-3) / 0.8)',
                    'rgb(var(--chart-4) / 0.8)',
                    'rgb(var(--chart-5) / 0.8)',
                ],
                borderColor: [
                    'rgb(var(--chart-1))',
                    'rgb(var(--chart-2))',
                    'rgb(var(--chart-3))',
                    'rgb(var(--chart-4))',
                    'rgb(var(--chart-5))',
                ],
                borderWidth: 1,
                borderRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: { color: mutedColor, font: { family: 'Inter', size: 12 } }
                },
                tooltip: {
                    backgroundColor: isDark ? 'rgb(51, 65, 85)' : 'rgb(255, 255, 255)',
                    titleColor: isDark ? 'rgb(241, 245, 249)' : 'rgb(15, 23, 42)',
                    bodyColor: isDark ? 'rgb(203, 213, 225)' : 'rgb(71, 85, 105)',
                    borderColor: isDark ? 'rgb(71, 85, 105)' : 'rgb(226, 232, 240)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    padding: 12,
                    titleFont: { family: 'Inter', weight: '600' },
                    bodyFont: { family: 'Inter' },
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: isDark ? 'rgba(51, 65, 85, 0.5)' : 'rgba(226, 232, 240, 0.5)', drawBorder: false },
                    ticks: { color: mutedColor, font: { family: 'Inter', size: 11 } },
                },
                x: {
                    grid: { display: false },
                    ticks: { color: mutedColor, font: { family: 'Inter', size: 11 } },
                }
            }
        }
    });
});
</script>
@endpush
