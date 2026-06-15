@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')

    @include('user.partials.page-header', ['title' => 'Pre-IPO Shares', 'subtitle' => 'Invest in companies before they go public'])

    {{-- Filter & Search --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <a href="{{ route('user.pre-ipo.holdings') }}"
           class="bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2.5 px-4 text-sm font-medium transition-colors inline-flex items-center gap-2">
            <x-icon name="briefcase" class="w-4 h-4" /> My Holdings
        </a>
    </div>

    {{-- Companies Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($companies as $company)
            <a href="{{ route('user.pre-ipo.show', $company->id) }}"
               class="bg-surface-raised border border-surface-border rounded-xl p-5 hover:border-primary/30 transition-colors group">

                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        @if($company->logo)
                            <img src="{{ asset('storage/app/public/' . $company->logo) }}" alt="{{ $company->name }}" class="w-10 h-10 rounded-lg object-cover border border-surface-border">
                        @else
                            <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary text-xs font-bold">{{ substr($company->symbol, 0, 2) }}</div>
                        @endif
                        <div>
                            <h3 class="text-sm font-semibold text-content-primary group-hover:text-primary transition-colors">{{ $company->name }}</h3>
                            <p class="text-xs text-content-tertiary font-mono">{{ $company->symbol }}</p>
                        </div>
                    </div>
                    @if($company->is_featured)
                        <span class="text-warning text-xs">★ Featured</span>
                    @endif
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Share Price</span>
                        <span class="text-sm font-semibold text-content-primary">@money($company->share_price)</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Price Change</span>
                        <span class="text-xs font-medium {{ $company->price_change_percent >= 0 ? 'text-gain' : 'text-loss' }}">
                            {{ $company->price_change_percent >= 0 ? '+' : '' }}{{ $company->price_change_percent }}%
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-content-tertiary">Available</span>
                        <span class="text-xs text-content-secondary">{{ number_format($company->shares_remaining) }} / {{ number_format($company->total_shares) }}</span>
                    </div>

                    {{-- Progress bar --}}
                    <div class="w-full bg-surface-overlay rounded-full h-1.5 mt-1">
                        <div class="bg-primary rounded-full h-1.5" style="width: {{ $company->total_shares > 0 ? round(($company->shares_sold / $company->total_shares) * 100) : 0 }}%"></div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        @php
                            $statusColors = [
                                'upcoming' => 'bg-info/10 text-info',
                                'open' => 'bg-gain/10 text-gain',
                                'closed' => 'bg-warning/10 text-warning',
                                'ipo' => 'bg-primary/10 text-primary',
                                'public' => 'bg-gain/10 text-gain',
                            ];
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$company->status] ?? 'bg-surface-overlay text-content-secondary' }}">
                            {{ ucfirst($company->status) }}
                        </span>
                        @if(isset($userHoldings[$company->id]))
                            <span class="text-xs text-primary font-medium">{{ $userHoldings[$company->id] }} shares held</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-content-tertiary">
                <x-icon name="building-office" class="w-12 h-12 mx-auto mb-3 opacity-50" />
                <p class="text-sm">No Pre-IPO offerings available right now.</p>
            </div>
        @endforelse
    </div>

    @if($companies->hasPages())
        <div class="mt-4">{{ $companies->links() }}</div>
    @endif

@endsection
