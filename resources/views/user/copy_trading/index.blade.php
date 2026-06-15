@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')
    @include('user.partials.page-header', ['title' => 'Copy Trading', 'subtitle' => 'Follow expert traders and earn automated profits'])

    {{-- Tabs --}}
    <div x-data="{ tab: 'experts' }">
        <div class="flex items-center gap-1 border-b border-surface-border mb-6">
            <button @click="tab = 'experts'" :class="tab === 'experts' ? 'border-b-2 border-primary text-primary font-medium' : 'text-content-tertiary hover:text-content-secondary'" class="px-4 py-2.5 text-sm transition-colors">
                Available Experts
            </button>
            <button @click="tab = 'positions'" :class="tab === 'positions' ? 'border-b-2 border-primary text-primary font-medium' : 'text-content-tertiary hover:text-content-secondary'" class="px-4 py-2.5 text-sm transition-colors">
                My Active Copies
                @if($positions->where('status', 'active')->count() > 0)
                    <span class="ml-1 bg-primary/20 text-primary text-xs px-1.5 py-0.5 rounded-full">{{ $positions->where('status', 'active')->count() }}</span>
                @endif
            </button>
        </div>

        {{-- Tab 1: Available Experts --}}
        <div x-show="tab === 'experts'">
            @if($experts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @foreach($experts as $expert)
                        <a href="{{ route('copyTrading.expert', $expert->id) }}" class="group block bg-surface-raised border border-surface-border rounded-xl p-5 hover:border-primary/40 hover:shadow-lg hover:shadow-primary/5 transition-all duration-200">
                            {{-- Expert Identity --}}
                            <div class="flex items-center gap-3.5 mb-4">
                                <div class="relative flex-shrink-0">
                                    @if($expert->profile_picture)
                                        <img src="{{ asset('storage/app/public/' . $expert->profile_picture) }}" alt="{{ $expert->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-surface-border group-hover:ring-primary/40 transition-all">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-surface-overlay flex items-center justify-center ring-2 ring-surface-border">
                                            <x-icon name="user" class="w-6 h-6 text-content-tertiary" />
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 bg-gain rounded-full border-2 border-surface-raised" title="Active"></span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-semibold text-content-primary truncate group-hover:text-primary transition-colors">{{ $expert->name }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="bg-primary/10 text-primary text-[10px] font-medium px-1.5 py-0.5 rounded">{{ $expert->area_of_expertise }}</span>
                                        <span class="text-content-tertiary text-[10px]">{{ number_format($expert->followers_count) }} followers</span>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-base font-bold text-gain">{{ number_format($expert->daily_roi, 2) }}%</p>
                                    <p class="text-[10px] text-content-tertiary">daily ROI</p>
                                </div>
                            </div>

                            {{-- Stats Row --}}
                            <div class="grid grid-cols-4 gap-3 py-3 border-t border-surface-border">
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-content-primary">{{ $expert->duration_days }}d</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Duration</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-gain">{{ number_format($expert->total_roi, 1) }}%</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Total ROI</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-content-primary">{{ number_format($expert->win_rate ?? 0) }}%</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Win Rate</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs font-semibold text-content-primary">@money($expert->min_startup_capital)</p>
                                    <p class="text-[10px] text-content-tertiary mt-0.5">Min Capital</p>
                                </div>
                            </div>

                            {{-- CTA --}}
                            <div class="mt-3 flex items-center justify-center gap-1.5 py-2 rounded-lg bg-primary/10 text-primary text-xs font-semibold group-hover:bg-primary group-hover:text-content-inverse transition-all duration-200">
                                View Expert
                                <svg class="w-3.5 h-3.5 opacity-0 -ml-1 group-hover:opacity-100 group-hover:ml-0 transition-all" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">{{ $experts->links() }}</div>
            @else
                <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
                    <x-icon name="users" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                    <p class="text-content-secondary">No experts available at the moment.</p>
                </div>
            @endif
        </div>

        {{-- Tab 2: My Active Copies --}}
        <div x-show="tab === 'positions'" x-cloak>
            @if($positions->count() > 0)
                <div class="space-y-4">
                    @foreach($positions as $pos)
                        <div class="bg-surface-raised border border-surface-border rounded-xl p-5">
                            <div class="flex items-center gap-3 mb-4">
                                @if($pos->expert && $pos->expert->profile_picture)
                                    <img src="{{ asset('storage/app/public/' . $pos->expert->profile_picture) }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center">
                                        <x-icon name="user" class="w-5 h-5 text-content-tertiary" />
                                    </div>
                                @endif
                                <div>
                                    <h5 class="font-semibold text-content-primary">{{ $pos->expert->name ?? 'Unknown' }}</h5>
                                    <span class="bg-primary-subtle text-primary text-xs px-2 py-0.5 rounded-full">{{ $pos->expert->area_of_expertise ?? '' }}</span>
                                </div>
                                <div class="ml-auto">
                                    @php
                                        $statusClass = match($pos->status) {
                                            'active' => 'bg-gain/10 text-gain',
                                            'stopped' => 'bg-loss/10 text-loss',
                                            'completed' => 'bg-primary-subtle text-primary',
                                            'settled' => 'bg-warning/10 text-warning',
                                            default => 'bg-surface-overlay text-content-tertiary',
                                        };
                                    @endphp
                                    <span class="text-xs px-2.5 py-1 rounded-full {{ $statusClass }}">{{ ucfirst($pos->status) }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
                                <div>
                                    <p class="text-xs text-content-tertiary">Invested</p>
                                    <p class="text-sm font-semibold text-content-primary">@money($pos->invested_amount)</p>
                                </div>
                                <div>
                                    <p class="text-xs text-content-tertiary">Profit</p>
                                    <p class="text-sm font-semibold text-gain">@money($pos->accumulated_profit)</p>
                                </div>
                                <div>
                                    <p class="text-xs text-content-tertiary">Daily ROI</p>
                                    <p class="text-sm font-semibold text-gain">{{ number_format($pos->daily_roi_snapshot, 2) }}%</p>
                                </div>
                                <div>
                                    <p class="text-xs text-content-tertiary">Total Payout</p>
                                    <p class="text-sm font-semibold text-content-primary">@money($pos->totalPayout())</p>
                                </div>
                            </div>

                            {{-- Progress Bar --}}
                            @php
                                $totalDays = $pos->started_at->diffInDays($pos->expires_at);
                                $elapsed = $pos->started_at->diffInDays(now());
                                $pct = $totalDays > 0 ? min(100, round(($elapsed / $totalDays) * 100)) : 100;
                                $remaining = max(0, $totalDays - $elapsed);
                            @endphp
                            <div class="mb-4">
                                <div class="bg-surface-overlay rounded-full h-2">
                                    <div class="bg-primary rounded-full h-2" style="width: {{ $pct }}%"></div>
                                </div>
                                <p class="text-content-tertiary text-xs mt-1">Day {{ min($elapsed, $totalDays) }} of {{ $totalDays }} ({{ $pct }}%) &mdash; {{ $remaining }} days remaining</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <a href="{{ route('copyTrading.position', $pos->id) }}" class="flex-1 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold text-center transition-colors">
                                    View Position
                                </a>
                                @if($pos->status === 'active')
                                    <form id="stop-form-{{ $pos->id }}" action="{{ route('copyTrading.stop', $pos->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="button" class="stopCopyBtn w-full py-2 rounded-lg bg-loss/10 hover:bg-loss/20 text-loss text-sm font-semibold transition-colors" data-id="{{ $pos->id }}">
                                            Stop Copying
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
                    <x-icon name="users" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                    <p class="text-content-secondary mb-2">You're not copying any experts yet.</p>
                    <button @click="tab = 'experts'" class="text-primary hover:text-primary-dark text-sm font-medium">Browse Experts</button>
                </div>
            @endif
        </div>
    </div>

@endsection

@section('scripts')
@parent
<script>
document.querySelectorAll('.stopCopyBtn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.dataset.id;
        Swal.fire({
            title: 'Stop Copying?',
            text: 'Your invested amount plus accumulated profit will be credited to your balance.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#2A2F36',
            confirmButtonText: 'Yes, Stop',
            cancelButtonText: 'Cancel',
            background: '#161A1E',
            color: '#E8EAED'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('stop-form-' + id).submit();
        });
    });
});
</script>
@endsection
