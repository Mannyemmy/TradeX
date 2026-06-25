{{--
    Trade Filters Partial — Reusable filter bar for trade views
    Usage: @include('user.trades.partials.trade-filters', [
        'currentType' => request('type', 'all'),
        'currentStatus' => request('status', 'all'),
        'currentDemo' => request('demo', 'all'),
        'currentSearch' => request('search', ''),
        'baseUrl' => route('user.trades.history'),
        'stats' => $stats,    // optional — array with counts for badges
    ])
--}}
@php
    $currentType = $currentType ?? 'all';
    $currentStatus = $currentStatus ?? 'all';
    $currentDemo = $currentDemo ?? 'all';
    $currentSearch = $currentSearch ?? '';
    $baseUrl = $baseUrl ?? request()->url();
    $stats = $stats ?? [];
@endphp

<div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden" x-data="{ showAdvanced: false }">
    {{-- Header --}}
    <div class="px-5 py-3 border-b border-surface-border flex items-center justify-between">
        <div class="flex items-center gap-2">
            <x-icon name="funnel" class="w-4 h-4 text-content-tertiary" />
            <span class="text-sm font-semibold text-content-primary">Filters</span>
        </div>
        <button @click="showAdvanced = !showAdvanced"
                class="text-xs text-primary hover:text-primary font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary rounded"
                :aria-expanded="showAdvanced"
                aria-controls="advanced-filters">
            <span x-text="showAdvanced ? 'Less Filters' : 'More Filters'"></span>
        </button>
    </div>

    <form method="GET" action="{{ $baseUrl }}" class="p-4 space-y-3">
        {{-- Row 1: Trade Type --}}
        <div role="group" aria-label="Trade type filter">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wider font-medium mb-2">Type</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach(['all' => 'All', 'binary' => 'Binary', 'spot' => 'Spot'] as $val => $label)
                    <button type="submit" name="type" value="{{ $val }}"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary
                            {{ $currentType === $val ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}"
                            aria-pressed="{{ $currentType === $val ? 'true' : 'false' }}">
                        {{ $label }}
                        @if(isset($stats[$val . '_count']))
                            <span class="ml-1 opacity-70">({{ $stats[$val . '_count'] }})</span>
                        @endif
                    </button>
                @endforeach
                {{-- Carry other params --}}
                <input type="hidden" name="status" value="{{ $currentStatus }}">
                <input type="hidden" name="demo" value="{{ $currentDemo }}">
                <input type="hidden" name="search" value="{{ $currentSearch }}">
            </div>
        </div>

        {{-- Row 2: Status --}}
        <div role="group" aria-label="Status filter">
            <p class="text-[10px] text-content-tertiary uppercase tracking-wider font-medium mb-2">Status</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach(['all' => 'All', 'open' => 'Open', 'closed' => 'Closed'] as $val => $label)
                    <button type="submit" name="status" value="{{ $val }}"
                            class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary
                            {{ $currentStatus === $val ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}"
                            aria-pressed="{{ $currentStatus === $val ? 'true' : 'false' }}">
                        {{ $label }}
                        @if(isset($stats[$val . '_count']))
                            <span class="ml-1 opacity-70">({{ $stats[$val . '_count'] }})</span>
                        @endif
                    </button>
                @endforeach
                <input type="hidden" name="type" value="{{ $currentType }}">
                <input type="hidden" name="demo" value="{{ $currentDemo }}">
                <input type="hidden" name="search" value="{{ $currentSearch }}">
            </div>
        </div>

        {{-- Row 3: Advanced — Demo/Live + Search --}}
        <div x-show="showAdvanced" x-cloak x-transition id="advanced-filters" class="space-y-3 pt-2 border-t border-surface-border">
            {{-- Demo/Live Toggle --}}
            <div role="group" aria-label="Demo or live filter">
                <p class="text-[10px] text-content-tertiary uppercase tracking-wider font-medium mb-2">Mode</p>
                <div class="flex flex-wrap gap-1.5">
                    @foreach(['all' => 'All', 'live' => 'Live', 'demo' => 'Demo'] as $val => $label)
                        <button type="submit" name="demo" value="{{ $val }}"
                                class="px-3 py-1.5 text-xs font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary
                                {{ $currentDemo === $val ? 'bg-primary text-content-inverse' : 'bg-surface-overlay text-content-secondary hover:bg-surface-border hover:text-content-primary' }}"
                                aria-pressed="{{ $currentDemo === $val ? 'true' : 'false' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                    <input type="hidden" name="type" value="{{ $currentType }}">
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                    <input type="hidden" name="search" value="{{ $currentSearch }}">
                </div>
            </div>

            {{-- Search --}}
            <div>
                <label for="trade-search" class="text-[10px] text-content-tertiary uppercase tracking-wider font-medium mb-2 block">Search Asset</label>
                <div class="relative">
                    <input type="text" id="trade-search" name="search" value="{{ $currentSearch }}"
                           placeholder="Search by name or symbol..."
                           class="w-full bg-surface-overlay border border-surface-border rounded-lg pl-9 pr-3 py-2 text-sm text-content-primary placeholder-content-tertiary focus:outline-none focus:ring-2 focus:ring-primary" />
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-icon name="magnifying-glass" class="w-4 h-4 text-content-tertiary" />
                    </div>
                    {{-- Carry other params --}}
                    <input type="hidden" name="type" value="{{ $currentType }}">
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                    <input type="hidden" name="demo" value="{{ $currentDemo }}">
                </div>
            </div>

            {{-- Apply Search Button --}}
            <button type="submit"
                    class="w-full bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-surface-base">
                Apply Filters
            </button>
        </div>
    </form>

    {{-- Active Filters Chips --}}
    @if($currentType !== 'all' || $currentStatus !== 'all' || $currentDemo !== 'all' || $currentSearch)
        <div class="px-4 pb-3 flex flex-wrap gap-1.5" role="list" aria-label="Active filters">
            @if($currentType !== 'all')
                <a href="{{ $baseUrl }}?{{ http_build_query(array_merge(request()->except('type'), ['type' => 'all'])) }}"
                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium hover:bg-primary/20 transition-colors"
                   role="listitem" aria-label="Remove type filter: {{ $currentType }}">
                    Type: {{ ucfirst($currentType) }}
                    <x-icon name="x-mark" class="w-3 h-3" />
                </a>
            @endif
            @if($currentStatus !== 'all')
                <a href="{{ $baseUrl }}?{{ http_build_query(array_merge(request()->except('status'), ['status' => 'all'])) }}"
                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium hover:bg-primary/20 transition-colors"
                   role="listitem" aria-label="Remove status filter: {{ $currentStatus }}">
                    Status: {{ ucfirst($currentStatus) }}
                    <x-icon name="x-mark" class="w-3 h-3" />
                </a>
            @endif
            @if($currentDemo !== 'all')
                <a href="{{ $baseUrl }}?{{ http_build_query(array_merge(request()->except('demo'), ['demo' => 'all'])) }}"
                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium hover:bg-primary/20 transition-colors"
                   role="listitem" aria-label="Remove mode filter: {{ $currentDemo }}">
                    Mode: {{ ucfirst($currentDemo) }}
                    <x-icon name="x-mark" class="w-3 h-3" />
                </a>
            @endif
            @if($currentSearch)
                <a href="{{ $baseUrl }}?{{ http_build_query(array_merge(request()->except('search'), ['search' => ''])) }}"
                   class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-primary/10 text-primary text-xs font-medium hover:bg-primary/20 transition-colors"
                   role="listitem" aria-label="Remove search filter: {{ $currentSearch }}">
                    "{{ $currentSearch }}"
                    <x-icon name="x-mark" class="w-3 h-3" />
                </a>
            @endif
            <a href="{{ $baseUrl }}"
               class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-loss/10 text-loss text-xs font-medium hover:bg-loss/20 transition-colors"
               role="listitem">
                Clear All
            </a>
        </div>
    @endif
</div>
