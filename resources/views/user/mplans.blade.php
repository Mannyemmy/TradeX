@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', [
        'title' => 'Investment Plans',
        'subtitle' => count($plans) . ' plans available' . ($activePlanCount > 0 ? ' &middot; ' . $activePlanCount . ' active' : '')
    ])

    {{-- Action Bar --}}
    <div class="flex items-center justify-end gap-3 mb-6">
        <a href="{{ route('myplans', 'All') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-surface-overlay border border-surface-border text-content-secondary hover:text-content-primary text-sm font-medium transition-colors">
            <x-icon name="folder" class="w-4 h-4" />
            My Plans
        </a>
    </div>

    {{-- Plans Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($plans as $plan)
            @php
                // Tier config based on plan tag
                $tag = strtolower($plan->tag ?? '');
                if (in_array($tag, ['elite', 'platinum', 'vip'])) {
                    $tierBorder = 'border-gain hover:border-gain';
                    $tierBadge = 'bg-gain/10 text-gain';
                    $tierIcon = 'sparkles';
                    $tierGlow = 'shadow-gain/5 shadow-lg';
                    $tierHeaderBg = 'bg-gain/5';
                } elseif (in_array($tag, ['premium', 'gold', 'best value'])) {
                    $tierBorder = 'border-warning hover:border-warning';
                    $tierBadge = 'bg-warning/10 text-warning';
                    $tierIcon = 'trophy';
                    $tierGlow = 'shadow-warning/5 shadow-lg';
                    $tierHeaderBg = 'bg-warning/5';
                } elseif (in_array($tag, ['popular', 'silver', 'recommended'])) {
                    $tierBorder = 'border-primary hover:border-primary';
                    $tierBadge = 'bg-primary/10 text-primary';
                    $tierIcon = 'bolt';
                    $tierGlow = 'shadow-primary/5 shadow-lg';
                    $tierHeaderBg = 'bg-primary/5';
                } else {
                    $tierBorder = 'border-surface-border hover:border-primary/50';
                    $tierBadge = 'bg-info/10 text-info';
                    $tierIcon = 'banknotes';
                    $tierGlow = '';
                    $tierHeaderBg = '';
                }
            @endphp
            <div class="rounded-xl bg-surface-raised border {{ $tierBorder }} overflow-hidden transition-all {{ $tierGlow }}"
                 x-data="{
                    amount: {{ $plan->min_price }},
                    min: {{ $plan->min_price }},
                    max: {{ $plan->max_price }},
                    rate: {{ $plan->increment_amount }},
                    type: '{{ $plan->increment_type }}',
                    interval: '{{ $plan->increment_interval }}',
                    get roi() {
                        let amt = Math.max(this.min, Math.min(this.max, this.amount || this.min));
                        return this.type === 'Percentage' ? (amt * this.rate / 100).toFixed(2) : parseFloat(this.rate).toFixed(2);
                    },
                    get projected() {
                        let r = parseFloat(this.roi);
                        let multipliers = {'Monthly': 1, 'Weekly': 4.3, 'Daily': 30, 'Hourly': 720, 'Every 30 Minutes': 1440};
                        let m = multipliers[this.interval] || 30;
                        return (r * m).toFixed(2);
                    }
                 }">
                {{-- Plan Header --}}
                <div class="p-6 text-center border-b border-surface-border {{ $tierHeaderBg }}">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-primary/10 mb-3">
                        <x-icon name="{{ $tierIcon }}" class="w-6 h-6 text-primary" />
                    </div>
                    <h3 class="text-lg font-bold text-content-primary">{{ $plan->name }}</h3>
                    @if ($plan->tag)
                        <span class="inline-block mt-1 px-2.5 py-0.5 text-xs font-semibold rounded-full {{ $tierBadge }}">{{ $plan->tag }}</span>
                    @endif
                    <div class="mt-3">
                        <span class="text-3xl font-bold text-primary">{{ $plan->increment_amount }}{{ $plan->increment_type == 'Percentage' ? '%' : \App\Helpers\CurrencyHelper::getUserSymbol() }}</span>
                        <span class="text-sm text-content-secondary ml-1">{{ $plan->increment_interval }}</span>
                    </div>
                </div>

                {{-- Plan Details --}}
                <div class="p-6 space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-content-tertiary flex items-center gap-1.5">
                            <x-icon name="arrow-down" class="w-3.5 h-3.5" /> Minimum
                        </span>
                        <span class="text-content-primary font-semibold">@money($plan->min_price)</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-content-tertiary flex items-center gap-1.5">
                            <x-icon name="arrow-up-right" class="w-3.5 h-3.5" /> Maximum
                        </span>
                        <span class="text-content-primary font-semibold">@money($plan->max_price)</span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-content-tertiary flex items-center gap-1.5">
                            <x-icon name="clock" class="w-3.5 h-3.5" /> Duration
                        </span>
                        <span class="text-content-primary font-semibold">{{ $plan->expiration }}</span>
                    </div>
                    @if ($plan->gift > 0)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-content-tertiary flex items-center gap-1.5">
                                <x-icon name="gift" class="w-3.5 h-3.5" /> Bonus
                            </span>
                            <span class="text-gain font-semibold">@money($plan->gift)</span>
                        </div>
                    @endif
                </div>

                {{-- ROI Calculator --}}
                <div class="px-6 pb-4">
                    <div class="rounded-lg bg-surface-overlay border border-surface-border p-3">
                        <label class="block text-xs font-medium text-content-tertiary mb-1.5">Calculate Your Returns</label>
                        <input type="number" x-model.number="amount"
                               :min="min" :max="max"
                               :placeholder="'@userCurrency' + min + ' - @userCurrency' + max"
                               class="w-full px-3 py-2 rounded-md bg-surface-base border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary mb-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-content-tertiary">Per {{ $plan->increment_interval }}</span>
                            <span class="text-gain font-semibold" x-text="'@userCurrency' + roi"></span>
                        </div>
                        <div class="flex items-center justify-between text-xs mt-1">
                            <span class="text-content-tertiary">Est. Monthly</span>
                            <span class="text-primary font-semibold" x-text="'@userCurrency' + projected"></span>
                        </div>
                    </div>
                </div>

                {{-- Join Button --}}
                <div class="px-6 pb-6">
                    <button type="button"
                            onclick="openInvestDrawer({{ $plan->id }})"
                            class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                        Invest Now
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    @if (count($plans) == 0)
        <div class="rounded-xl bg-surface-raised border border-surface-border p-8 text-center">
            <x-icon name="chart-bar" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
            <p class="text-content-secondary">No investment plans available at the moment.</p>
        </div>
    @endif

    {{-- Invest Slide-Over Drawer --}}
    <div x-data="{ open: false }"
         x-on:open-invest-drawer.window="open = true"
         x-on:keydown.escape.window="open = false">
        {{-- Backdrop --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60 z-40" @click="open = false"></div>
        {{-- Drawer Panel --}}
        <div x-show="open" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="fixed inset-y-0 right-0 z-50 w-full max-w-2xl bg-surface-base border-l border-surface-border overflow-y-auto">
            {{-- Drawer Header --}}
            <div class="sticky top-0 z-10 bg-surface-base border-b border-surface-border px-6 py-4 flex items-center justify-between">
                <h2 class="text-lg font-bold text-content-primary">Invest in Plan</h2>
                <button @click="open = false" class="p-2 rounded-lg hover:bg-surface-overlay text-content-tertiary hover:text-content-primary transition-colors">
                    <x-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>
            {{-- Livewire Component --}}
            <div class="px-6 pb-6">
                <livewire:user.investment-plan />
            </div>
        </div>
    </div>

@endsection

@section('scripts')
@parent
<script>
    function openInvestDrawer(planId) {
        Livewire.emit('selectPlanById', planId);
        window.dispatchEvent(new CustomEvent('open-invest-drawer'));
    }
</script>
@endsection
