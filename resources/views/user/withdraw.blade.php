@extends('layouts.dash1')
@section('title', $title)
@section('content')

    @include('user.partials.ticker-tape')
    @include('user.partials.quick-nav')
    @include('user.partials.page-header', ['title' => 'Withdrawal Verification', 'subtitle' => 'Complete security verification to process your withdrawal'])

    <x-danger-alert />
    <x-success-alert />

    @php
        $totalSteps = count($enabledSteps);
        $currentIndex = 0;
        foreach ($enabledSteps as $idx => $s) {
            if ($s['step'] == $currentStep) { $currentIndex = $idx; break; }
        }
        $progress = $totalSteps > 0 ? round(($currentIndex / $totalSteps) * 100) : 0;
        $currentLabel = $enabledSteps[$currentIndex]['label'] ?? 'Verification Code';
    @endphp

    <div class="max-w-2xl">
        {{-- Withdrawal Summary Bar --}}
        @if($withdrawal)
            <div class="bg-surface-raised border border-surface-border rounded-xl p-4 mb-6 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs text-content-tertiary">Withdrawal Amount</p>
                        <p class="text-sm font-semibold text-content-primary">@money($withdrawal->amount) via {{ $withdrawal->payment_mode }}</p>
                    </div>
                </div>
                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-warning/10 text-warning">Pending Verification</span>
            </div>
        @endif

        {{-- Step Progress --}}
        <div class="mb-6">
            <div class="flex items-center gap-2 mb-3">
                @foreach($enabledSteps as $idx => $s)
                    @php
                        $isCompleted = $idx < $currentIndex;
                        $isCurrent = $idx == $currentIndex;
                        $isFuture = $idx > $currentIndex;
                    @endphp
                    <div class="flex items-center {{ $idx < count($enabledSteps) - 1 ? 'flex-1' : '' }}">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold transition-all
                                {{ $isCompleted ? 'bg-gain text-white' : ($isCurrent ? 'bg-primary text-content-inverse ring-4 ring-primary/20' : 'bg-surface-overlay text-content-tertiary border border-surface-border') }}">
                                @if($isCompleted)
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                @else
                                    {{ $idx + 1 }}
                                @endif
                            </div>
                        </div>
                        @if($idx < count($enabledSteps) - 1)
                            <div class="flex-1 h-0.5 mx-2 rounded-full {{ $isCompleted ? 'bg-gain' : 'bg-surface-border' }}"></div>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="flex items-center justify-between">
                <p class="text-xs text-content-tertiary">Step {{ $currentIndex + 1 }} of {{ $totalSteps }}</p>
                <p class="text-xs font-medium text-primary">{{ $progress }}% complete</p>
            </div>
        </div>

        {{-- Verification Card --}}
        <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
            {{-- Card Header --}}
            <div class="px-6 py-4 border-b border-surface-border bg-surface-overlay/30">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-content-primary">{{ $currentLabel }}</h3>
                        <p class="text-xs text-content-tertiary">Enter the verification code to continue</p>
                    </div>
                </div>
            </div>

            <div class="p-6 space-y-5">
                {{-- Notice --}}
                <div class="bg-info/10 border border-info/20 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-info flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                    <div>
                        <p class="text-sm text-content-primary leading-relaxed">
                            Your <span class="font-semibold">{{ $currentLabel }}</span> is required to proceed.
                            Please enter the code provided to you. If you don't have this code, contact support for assistance.
                        </p>
                    </div>
                </div>

                {{-- Code Input Form --}}
                <form action="{{ route('brokercode') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="text-xs text-content-tertiary font-medium mb-2 block">{{ $currentLabel }}</label>
                        <input type="text" name="pin" id="pin" required
                               placeholder="Enter your verification code"
                               autocomplete="off"
                               class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-3.5 text-center text-lg text-content-primary placeholder-content-tertiary font-mono tracking-[0.3em] focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                    </div>
                    <input type="hidden" value="{{ $currentStep }}" name="step">

                    <div class="flex gap-3">
                        <a href="{{ route('withdrawalsdeposits') }}"
                           class="flex-1 inline-flex items-center justify-center gap-1.5 bg-surface-overlay border border-surface-border text-content-secondary hover:bg-surface-border rounded-lg py-3 text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                            Cancel
                        </a>
                        <button type="submit"
                                class="flex-1 inline-flex items-center justify-center gap-1.5 bg-primary hover:bg-primary-dark text-content-inverse rounded-lg py-3 text-sm font-semibold transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                            Verify & Continue
                        </button>
                    </div>
                </form>

                {{-- Steps Overview --}}
                <div class="border-t border-surface-border pt-4">
                    <p class="text-[10px] text-content-tertiary uppercase tracking-wider font-semibold mb-2">Verification Steps</p>
                    <div class="space-y-1.5">
                        @foreach($enabledSteps as $idx => $s)
                            @php
                                $isCompleted = $idx < $currentIndex;
                                $isCurrent = $idx == $currentIndex;
                            @endphp
                            <div class="flex items-center gap-2 text-xs py-1">
                                @if($isCompleted)
                                    <svg class="w-4 h-4 text-gain" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-content-tertiary line-through">{{ $s['label'] }}</span>
                                @elseif($isCurrent)
                                    <svg class="w-4 h-4 text-primary animate-pulse" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M9 9.563C9 9.252 9.252 9 9.563 9h4.874c.311 0 .563.252.563.563v4.874c0 .311-.252.563-.563.563H9.564A.562.562 0 019 14.437V9.564z" /></svg>
                                    <span class="text-primary font-medium">{{ $s['label'] }}</span>
                                @else
                                    <svg class="w-4 h-4 text-content-tertiary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span class="text-content-tertiary">{{ $s['label'] }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
