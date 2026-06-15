@extends('layouts.admin-dash')
@section('title', 'Dashboard')

@section('content')
    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-primary to-primary-hover rounded-xl p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-content-inverse">Welcome back, {{ Auth('admin')->User()->firstName }}!</h2>
                <p class="mt-1 text-sm text-content-inverse/70">Here's what's happening with your platform today.</p>
            </div>
            @if (Auth('admin')->User()->type == 'Super Admin' || Auth('admin')->User()->type == 'Admin')
                <div class="flex items-center gap-2">
                    <a href="{{ route('mdeposits') }}"
                        class="bg-white/15 text-content-inverse hover:bg-white/25 rounded-lg px-4 py-2 text-sm font-medium transition-colors backdrop-blur-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Deposits
                    </a>
                    <a href="{{ route('mwithdrawals') }}"
                        class="bg-white/15 text-content-inverse hover:bg-white/25 rounded-lg px-4 py-2 text-sm font-medium transition-colors backdrop-blur-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                        Withdrawals
                    </a>
                    <a href="{{ route('manageusers') }}"
                        class="bg-white/15 text-content-inverse hover:bg-white/25 rounded-lg px-4 py-2 text-sm font-medium transition-colors backdrop-blur-sm inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-1.053M18 10.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM12.75 6.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                        Users
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mb-6">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    {{-- Stat Cards Grid --}}
    @php
        $depositTotal = 0;
        foreach ($total_deposited as $deposited) {
            $depositTotal = !empty($deposited->count) ? $deposited->count : 0;
        }
        $depositPending = 0;
        foreach ($pending_deposited as $deposited) {
            $depositPending = !empty($deposited->count) ? $deposited->count : 0;
        }
        $withdrawTotal = 0;
        foreach ($total_withdrawn as $deposited) {
            $withdrawTotal = !empty($deposited->count) ? $deposited->count : 0;
        }
        $withdrawPending = 0;
        foreach ($pending_withdrawn as $deposited) {
            $withdrawPending = !empty($deposited->count) ? $deposited->count : 0;
        }
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card
            label="Total Deposit"
            :value="$settings->currency . number_format($depositTotal)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>'
        />
        <x-admin.stat-card
            label="Pending Deposits"
            :value="$settings->currency . number_format($depositPending)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Total Withdrawal"
            :value="$settings->currency . number_format($withdrawTotal)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>'
        />
        <x-admin.stat-card
            label="Pending Withdrawal"
            :value="$settings->currency . number_format($withdrawPending)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card
            label="Total Users"
            :value="number_format($user_count)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-1.053M18 10.5a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM12.75 6.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Blocked Users"
            :value="number_format($blockeusers)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>'
        />
        <x-admin.stat-card
            label="Active Users"
            :value="number_format($activeusers)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Investment Plans"
            :value="number_format($plans)"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'
        />
    </div>

    {{-- Chart --}}
    <x-admin.card>
        <h3 class="text-base font-medium text-content mb-4">System Statistics ({{ $settings->currency }})</h3>
        <div class="w-full" style="min-height: 300px;">
            <canvas id="myChart"></canvas>
        </div>
    </x-admin.card>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('myChart').getContext('2d');

            // Use semantic token colors via computed style
            var style = getComputedStyle(document.documentElement);
            function tokenColor(name, alpha) {
                var rgb = style.getPropertyValue(name).trim();
                return 'rgba(' + rgb.replace(/ /g, ', ') + ', ' + alpha + ')';
            }

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Deposit', 'Pending Deposit', 'Withdrawal', 'Pending Withdrawal', 'Total Transactions'],
                    datasets: [{
                        label: "System Statistics in {{ $settings->currency }}",
                        data: [
                            {{ $chart_pdepsoit }},
                            {{ $chart_pendepsoit }},
                            {{ $chart_pwithdraw }},
                            {{ $chart_pendwithdraw }},
                            {{ $chart_trans }}
                        ],
                        backgroundColor: [
                            tokenColor('--chart-1', 0.15),
                            tokenColor('--chart-2', 0.15),
                            tokenColor('--chart-3', 0.15),
                            tokenColor('--chart-4', 0.15),
                            tokenColor('--chart-5', 0.15)
                        ],
                        borderColor: [
                            tokenColor('--chart-1', 1),
                            tokenColor('--chart-2', 1),
                            tokenColor('--chart-3', 1),
                            tokenColor('--chart-4', 1),
                            tokenColor('--chart-5', 1)
                        ],
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: tokenColor('--border', 0.5) },
                            ticks: { color: tokenColor('--content-muted', 1) }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: tokenColor('--content-muted', 1) }
                        }
                    }
                }
            });
        });
    </script>
@endsection
