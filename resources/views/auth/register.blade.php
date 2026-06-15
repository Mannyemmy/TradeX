@php
    $captchaA = rand(1, 12);
    $captchaB = rand(1, 12);
    $captchaAnswer = (string) ($captchaA + $captchaB);
@endphp

@extends('layouts.guest1')
@section('title', 'Sign up')
@section('content')

<div class="w-full max-w-2xl mx-auto">
    {{-- Logo --}}
    <div class="text-center mb-8">
        <a href="/">
            <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" class="h-12 mx-auto">
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8">
        {{-- Status Alert --}}
        @if (Session::has('status'))
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-300">{{ session('status') }}</p>
            </div>
        @endif

        <h1 class="text-2xl font-bold text-content-primary mb-1">Sign Up for Free</h1>
        <p class="text-content-tertiary text-sm mb-6">It's free to sign up and only takes a minute.</p>

        <form method="POST" action="{{ route('register') }}" id="registerForm" onsubmit="document.getElementById('registerBtn').disabled = true; document.getElementById('registerBtnText').textContent = 'Processing...'; document.getElementById('registerBtnSpinner').classList.remove('hidden');">
            @csrf

            {{-- Row 1: Name + Username --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Enter your Name"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @error('name')
                        <p class="mt-1 text-sm text-loss">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        placeholder="Enter Preferred Username"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @error('username')
                        <p class="mt-1 text-sm text-loss">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 2: Email + Phone --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        placeholder="Enter your email"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @error('email')
                        <p class="mt-1 text-sm text-loss">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required maxlength="13"
                        placeholder="Enter your phone"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @error('phone')
                        <p class="mt-1 text-sm text-loss">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Row 3: Gender + Country --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Gender</label>
                    <select name="gender" required
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        <option value="" class="text-content-tertiary">Select Gender</option>
                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Others</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Country</label>
                    <select name="country" required
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        @include('auth.countries')
                    </select>
                </div>
            </div>

            {{-- Row 3b: Preferred Currency --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Preferred Currency</label>
                <select name="currency_code" required
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @foreach(\App\Models\ExchangeRate::where('is_active', true)->orderBy('currency_code')->get() as $rate)
                        <option value="{{ $rate->currency_code }}" style="background:#1a1d23;color:#e5e7eb" {{ old('currency_code', 'USD') == $rate->currency_code ? 'selected' : '' }}>
                            {{ $rate->currency_code }} ({{ html_entity_decode($rate->currency_symbol) }}){{ $rate->currency_name ? ' — ' . $rate->currency_name : '' }}
                        </option>
                    @endforeach
                </select>
                @error('currency_code')
                    <p class="mt-1 text-sm text-loss">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-content-tertiary">All balances and amounts will be displayed in this currency</p>
            </div>

            {{-- Row 4: Password + Confirm --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Password</label>
                    <input type="password" name="password" required autocomplete="new-password"
                        placeholder="Enter your password"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @error('password')
                        <p class="mt-1 text-sm text-loss">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        placeholder="Confirm Password"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>
            </div>

            {{-- Referral (conditional) --}}
            @if (Session::has('ref_by'))
                <div class="mb-4">
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Referral ID</label>
                    <input type="text" name="ref_by" value="{{ session('ref_by') }}" required
                        placeholder="Referral Code (Optional)"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>
            @endif

            {{-- Captcha --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Security Check</label>
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-sm font-medium text-content-primary select-none whitespace-nowrap">
                        {{ $captchaA }} + {{ $captchaB }} =
                    </div>
                    <input type="text" name="captcha" required inputmode="numeric"
                        placeholder="Answer"
                        class="w-24 bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors text-center">
                </div>
                @if ($errors->has('captcha'))
                    <p class="mt-1 text-sm text-loss">{{ $errors->first('captcha') }}</p>
                @endif
                <input type="hidden" name="captcha_confirmation" value="{{ $captchaAnswer }}">
            </div>

            {{-- Account Type --}}
            <div class="mb-6" x-data="{ selected: [] }">
                <label class="block text-sm font-medium text-content-secondary mb-2">Account Type</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([
                        'Binary Option Trading' => 'Binary Options',
                        'Forex Trading' => 'Forex',
                        'Stock Trading' => 'Stocks',
                        'CryptoCurrency Investment' => 'Crypto',
                        'NFT Trading' => 'NFTs',
                    ] as $value => $label)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="account[]" value="{{ $value }}" class="peer sr-only"
                            x-on:change="$event.target.checked ? selected.push('{{ $value }}') : selected = selected.filter(v => v !== '{{ $value }}')"
                        >
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm border transition-all
                                     border-surface-border text-content-tertiary
                                     peer-checked:border-primary/60 peer-checked:text-primary-light peer-checked:bg-primary/8
                                     hover:border-surface-border-light hover:text-content-secondary">
                            {{ $label }}
                        </span>
                    </label>
                    @endforeach
                </div>
                @error('account')
                    <p class="mt-1.5 text-xs text-loss">{{ $message }}</p>
                @else
                    <p class="mt-1.5 text-xs text-content-tertiary">Select one or more</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" id="registerBtn"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                <span id="registerBtnText">Register</span>
                <svg id="registerBtnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </form>

        {{-- Link --}}
        <div class="mt-6 text-sm text-content-tertiary">
            Already have an account? <a href="{{ route('login') }}" class="text-primary-light hover:text-primary transition-colors">Sign In</a>
        </div>
    </div>
</div>

@endsection
