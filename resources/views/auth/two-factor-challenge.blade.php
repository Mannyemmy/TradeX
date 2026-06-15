@extends('layouts.guest1')
@section('title', 'Authenticate account')
@section('content')

<div class="w-full max-w-md mx-auto" x-data="{ recovery: false }">
    {{-- Errors --}}
    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm text-red-300">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8">
        {{-- Shield Icon --}}
        <div class="w-16 h-16 mx-auto bg-primary-subtle rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-content-primary text-center mb-2">2-Step Verification</h1>

        {{-- Auth Code Description --}}
        <p class="text-content-tertiary text-sm text-center mb-6" x-show="!recovery">
            {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
        </p>
        <p class="text-content-tertiary text-sm text-center mb-6" x-show="recovery" x-cloak>
            {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
        </p>

        <form method="POST" action="{{ route('two-factor.login') }}">
            @csrf

            {{-- Auth Code Input --}}
            <div class="mb-5" x-show="!recovery">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Code</label>
                <input type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code"
                    placeholder="Enter auth code from your app"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>

            {{-- Recovery Code Input --}}
            <div class="mb-5" x-show="recovery" x-cloak>
                <label class="block text-sm font-medium text-content-secondary mb-1.5">{{ __('Recovery Code') }}</label>
                <input type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>

            {{-- Toggle Links --}}
            <div class="text-center mb-5">
                <button type="button" x-show="!recovery"
                    x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })"
                    class="text-sm text-primary-light hover:text-primary transition-colors">
                    {{ __('Use a recovery code') }}
                </button>
                <button type="button" x-show="recovery" x-cloak
                    x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })"
                    class="text-sm text-primary-light hover:text-primary transition-colors">
                    {{ __('Use an authentication code') }}
                </button>
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors">
                Verify & Sign In
            </button>
        </form>
    </div>
</div>

@endsection
