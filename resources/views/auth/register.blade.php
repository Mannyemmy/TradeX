@php
    $captchaA = rand(1, 12);
    $captchaB = rand(1, 12);
    $captchaAnswer = (string) ($captchaA + $captchaB);
@endphp

@extends('layouts.guest2')
@section('title', 'Sign up')
@section('content')

<div class="w-full max-w-2xl mx-auto">

    {{-- Page Header --}}
    <header class="flex items-center gap-5 pb-5 mb-0 border-b border-surface-border">
        <a href="/" class="flex-shrink-0">
            <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" class="h-8">
        </a>
        <h1 class="text-lg font-bold text-content-primary">Open an account</h1>
    </header>

    <form method="POST" action="{{ route('register') }}" id="registerForm"
          onsubmit="var btn=document.getElementById('registerBtn'); btn.disabled=true; btn.querySelector('span').textContent='Processing...'; btn.querySelector('svg').classList.remove('hidden');">
        @csrf

        {{-- Status Alert --}}
        @if (Session::has('status'))
            <div class="mt-5 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-300">{{ session('status') }}</p>
            </div>
        @endif

        {{-- Section 1: Tell us about yourself --}}
        <section class="py-6">

            <div class="mb-5 pb-3 border-b border-surface-border">
                <h2 class="text-xl font-bold text-content-primary">Tell us about yourself</h2>
                <p class="mt-1 text-sm text-content-tertiary">First, we'll need a few details.</p>
            </div>

            {{-- Group: Full name & username --}}
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-content-tertiary mb-3">Name &amp; username</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Enter your full name"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        @error('name')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required
                            placeholder="Enter preferred username"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        @error('username')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Group: Contact information --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-content-tertiary mb-3">Contact information</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                            placeholder="Enter your email"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        @error('email')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required maxlength="13"
                            placeholder="Enter your phone number"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        @error('phone')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

        </section>

        {{-- Section 2: Personal details --}}
        <section class="py-6 border-t border-surface-border">

            <h2 class="text-base font-bold text-content-primary mb-5">Where are you from?</h2>

            {{-- Group: Demographics & location --}}
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-content-tertiary mb-3">Demographics &amp; location</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Gender</label>
                        <select name="gender" required
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                            <option value="">Select gender</option>
                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Country</label>
                        <select name="country" required
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                            @include('auth.countries')
                        </select>
                        @error('country')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Preferred Currency --}}
            <div>
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Preferred Currency</label>
                <select name="currency_code" required
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    @foreach(\App\Models\ExchangeRate::where('is_active', true)->orderBy('currency_code')->get() as $rate)
                        <option value="{{ $rate->currency_code }}" style="background:#1a1d23;color:#e5e7eb"
                            {{ old('currency_code', 'USD') == $rate->currency_code ? 'selected' : '' }}>
                            {{ $rate->currency_code }} ({{ html_entity_decode($rate->currency_symbol) }}){{ $rate->currency_name ? ' — ' . $rate->currency_name : '' }}
                        </option>
                    @endforeach
                </select>
                @error('currency_code')
                    <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-content-tertiary">All balances and amounts will be displayed in this currency.</p>
            </div>

        </section>

        {{-- Section 3: Account security --}}
        <section class="py-6 border-t border-surface-border">

            <h2 class="text-base font-bold text-content-primary mb-5">Secure your account</h2>

            {{-- Password group --}}
            <div class="mb-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-content-tertiary mb-3">Password</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Password</label>
                        <input type="password" name="password" required autocomplete="new-password"
                            placeholder="Create a password"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                        @error('password')
                            <p class="mt-1 text-xs text-loss">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-content-secondary mb-1.5">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            placeholder="Confirm your password"
                            class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                    </div>
                </div>
            </div>

            {{-- Referral (conditional) --}}
            @if (Session::has('ref_by'))
                <div class="mb-5">
                    <label class="block text-sm font-medium text-content-secondary mb-1.5">Referral ID</label>
                    <input type="text" name="ref_by" value="{{ session('ref_by') }}" required
                        placeholder="Referral code"
                        class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                </div>
            @endif

            {{-- Captcha --}}
            <div>
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
                    <p class="mt-1 text-xs text-loss">{{ $errors->first('captcha') }}</p>
                @endif
                <input type="hidden" name="captcha_confirmation" value="{{ $captchaAnswer }}">
            </div>

        </section>

        {{-- Section 4: Account Type --}}
        <section class="py-6 border-t border-surface-border">

            <h2 class="text-base font-bold text-content-primary mb-1">Account Type</h2>
            <p class="text-sm text-content-tertiary mb-4">Select one or more account types you're interested in.</p>

            @php
                // Keys are the slugs passed from open-account.html (?account=<slug>);
                // values are the labels stored against the user.
                $accountTypes = [
                    'cash-management' => 'Cash Management',
                    'retirement'      => 'Retirement & IRAs',
                    'crypto'          => 'Crypto Account',
                    'brokerage'       => 'Brokerage Account',
                    'hsa'             => 'Health Savings Account (HSA)',
                    '529'             => '529 College Savings',
                ];
                $oldAccounts = (array) old('account', []);
                $preselect = $accountTypes[request('account')] ?? null;
            @endphp

            <div class="flex flex-wrap gap-2">
                @foreach($accountTypes as $slug => $label)
                <label class="cursor-pointer">
                    <input type="checkbox" name="account[]" value="{{ $label }}" class="peer sr-only"
                        {{ in_array($label, $oldAccounts, true) || $preselect === $label ? 'checked' : '' }}>
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
                <p class="mt-2 text-xs text-loss">{{ $message }}</p>
            @enderror

        </section>

        {{-- Form Actions --}}
        <div class="py-6 border-t border-surface-border">
            <button type="submit" id="registerBtn"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                <span>Register</span>
                <svg class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
            <p class="mt-4 text-sm text-content-tertiary">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary-light hover:text-primary transition-colors">Sign In</a>
            </p>
        </div>

    </form>
</div>

@endsection
