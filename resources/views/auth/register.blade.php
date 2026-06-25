@php
    $captchaA = rand(1, 12);
    $captchaB = rand(1, 12);
    $captchaAnswer = (string) ($captchaA + $captchaB);
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign up | {{ $settings->site_name }}</title>
    <link rel="icon" href="{{ asset('storage/app/public/' . $settings->favicon) }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Raleway:wght@800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background-color: #f2f2f2;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            color: #333;
            min-height: 100vh;
        }

        /* ── Header nav (like /login) ── */
        .site-header {
            background: #0F3A6E;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .site-header-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }
        .site-header-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .site-header-logo img { height: 40px; width: auto; }
        .site-header-logo span {
            font-family: 'WealthWise Slab', serif;
            font-size: 26px;
            font-weight: 300;
            letter-spacing: -1px;
            color: #fff;
            white-space: nowrap;
            vertical-align: middle;
        }
        .site-header-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        .site-header-nav a {
            font-size: 0.875rem;
            color: #fff;
            text-decoration: none;
            font-weight: 500;
        }
        .site-header-nav a:hover { text-decoration: underline; }
        .site-header-nav .btn-outline {
            border: 1px solid #fff;
            border-radius: 50px;
            padding: 0.5rem 1.25rem;
            font-weight: 600;
            transition: background 0.15s, color 0.15s;
        }
        .site-header-nav .btn-outline:hover {
            background: #fff;
            color: #0F3A6E;
            text-decoration: none;
        }

        /* ── Hamburger (mobile only) ── */
        .hamburger {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.25rem;
            flex-direction: column;
            gap: 5px;
        }
        .hamburger span {
            display: block;
            width: 24px;
            height: 2.5px;
            background: #fff;
            border-radius: 2px;
            transition: transform 0.2s, opacity 0.2s;
        }
        .hamburger.open span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

        /* ── Mobile menu overlay ── */
        .mobile-menu {
            display: none;
            position: fixed;
            top: 64px;
            left: 0;
            right: 0;
            bottom: 0;
            background: #0F3A6E;
            z-index: 99;
            padding: 1.5rem;
            flex-direction: column;
            gap: 0.5rem;
            overflow-y: auto;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            color: #fff;
            text-decoration: none;
            font-size: 1.125rem;
            font-weight: 500;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }
        .mobile-menu a:last-of-type { border-bottom: none; }
        .mobile-menu .btn-outline {
            border: 1px solid #fff;
            border-radius: 50px;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            text-align: center;
            margin-top: 0.5rem;
            display: block;
        }
        .mobile-menu .btn-outline:hover {
            background: #fff;
            color: #0F3A6E;
            text-decoration: none;
        }

        /* ── Layout: centered card (wider than /login for the multi-section form) ── */
        .page {
            min-height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1rem 3rem;
        }

        .container {
            width: 100%;
            max-width: 640px;
        }

        /* ── Card ── */
        .card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            padding: 2rem 2rem 2.5rem;
        }

        /* ── Card header (logo + title) ── */
        .card-header {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding-bottom: 1.25rem;
            margin-bottom: 0;
            border-bottom: 1px solid #d6d6d6;
        }
        .card-header img { height: 32px; width: auto; flex-shrink: 0; }
        .card-header h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
        }

        /* ── Alert ── */
        .alert {
            margin-top: 1.25rem;
            padding: 0.75rem 1rem;
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            font-size: 0.875rem;
            color: #856404;
        }
        .alert-error {
            background: #fef2f2;
            border-color: #fca5a5;
            color: #b91c1c;
        }

        /* ── Sections ── */
        .section { padding: 1.5rem 0; }
        .section + .section { border-top: 1px solid #d6d6d6; }

        .section-head {
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #d6d6d6;
        }
        .section-head.no-rule { border-bottom: none; padding-bottom: 0; }
        .section-head h2 {
            font-size: 1.375rem;
            font-weight: 700;
            color: #1a1a1a;
            line-height: 1.2;
        }
        .section-head h2.sm { font-size: 1.0625rem; }
        .section-head p { margin-top: 0.25rem; font-size: 0.875rem; color: #666; }

        .group { margin-bottom: 1.25rem; }
        .group:last-child { margin-bottom: 0; }
        .group-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #666;
            margin-bottom: 0.75rem;
        }

        /* ── Grid (two columns on desktop) ── */
        .grid2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem 1.25rem;
        }

        /* ── Form fields ── */
        label.field-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.375rem;
        }

        .field-input {
            width: 100%;
            padding: 0.7rem 0.875rem;
            border: 1px solid #767676;
            border-radius: 6px;
            font-size: 1rem;
            font-family: inherit;
            color: #1a1a1a;
            background: #fff;
            outline: none;
            transition: box-shadow 0.15s, border-color 0.15s;
        }
        .field-input::placeholder { color: #888; }
        .field-input:focus {
            box-shadow: 0 0 0 2px #1a1a1a;
            border-color: #1a1a1a;
        }
        select.field-input { appearance: none; -webkit-appearance: none; -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23555' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; padding-right: 2.5rem;
        }

        .helper { margin-top: 0.375rem; font-size: 0.8125rem; color: #666; }
        .field-error { margin-top: 0.25rem; font-size: 0.8125rem; color: #c00; }

        /* ── Captcha row ── */
        .captcha-row { display: flex; align-items: center; gap: 0.75rem; }
        .captcha-box {
            flex-shrink: 0;
            background: #f2f2f2;
            border: 1px solid #767676;
            border-radius: 6px;
            padding: 0.7rem 0.875rem;
            font-size: 0.9375rem;
            font-weight: 600;
            color: #1a1a1a;
            white-space: nowrap;
            user-select: none;
        }
        .captcha-input { width: 6rem; text-align: center; }

        /* ── Account type chips ── */
        .chips { display: flex; flex-wrap: wrap; gap: 0.5rem; }
        .chip-label { cursor: pointer; }
        .chip-label input { position: absolute; opacity: 0; width: 0; height: 0; }
        .chip {
            display: inline-flex;
            align-items: center;
            padding: 0.375rem 0.875rem;
            border-radius: 50px;
            font-size: 0.875rem;
            border: 1px solid #ccc;
            color: #555;
            background: #fff;
            transition: border-color 0.15s, color 0.15s, background 0.15s;
        }
        .chip-label:hover .chip { border-color: #999; color: #333; }
        .chip-label input:checked + .chip {
            border-color: #2E5C8A;
            color: #2E5C8A;
            background: rgba(46, 92, 138, 0.08);
        }

        /* ── Submit button — pill shape (same as /login) ── */
        .btn-login {
            width: 100%;
            background: #2E5C8A;
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            font-family: inherit;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.15s;
            letter-spacing: 0.01em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-login:hover { background: #1a3a7f; }
        .btn-login:focus { outline: 2px solid #1a1a1a; outline-offset: 2px; }
        .btn-login:disabled { opacity: 0.7; cursor: default; }
        .spinner { animation: spin 0.8s linear infinite; }
        .hidden { display: none; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Sign-in link ── */
        .signin-line { margin-top: 1rem; font-size: 0.9375rem; color: #333; }
        .signin-line a { color: #2E5C8A; text-decoration: underline; }
        .signin-line a:hover { color: #1a3a7f; }

        /* ── Footer (below card) ── */
        .footer {
            margin-top: 0;
            background: #f2f2f2;
            padding: 1.25rem 2rem;
            text-align: center;
            font-size: 0.9375rem;
            color: #333;
            border-top: 1px solid #d6d6d6;
        }
        .footer a { color: #2E5C8A; text-decoration: underline; }
        .footer a:hover { color: #1a3a7f; }
        .footer-nav {
            margin-top: 0.875rem;
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }
        .footer-nav a { font-size: 0.875rem; color: #333; text-decoration: underline; }

        /* ── Mobile (≤ 640px / 40em) ── */
        @media (max-width: 40em) {
            html, body { background-color: #fff; }
            .page { padding: 0; align-items: stretch; background: #fff; }
            .container { max-width: 100%; }

            .site-header { padding: 0 0.75rem; }
            .site-header-inner { height: 56px; }
            .site-header-logo img { height: 32px; }
            .site-header-logo span { font-size: 20px; }
            .site-header-nav { display: none; }
            .hamburger { display: flex; }
            .mobile-menu { top: 56px; }

            .card { border: none; border-radius: 0; padding: 1.25rem 1rem 2rem; }
            .grid2 { grid-template-columns: 1fr; }
            .footer { border-top: 1px solid #d6d6d6; padding: 1.25rem 1rem; text-align: center; background: #f2f2f2; }
        }
    </style>
</head>
<body>

{{-- Header nav (like /login) --}}
<div class="site-header">
    <div class="site-header-inner">
        <a href="/" class="site-header-logo">
            <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}">
            <span>{{ $settings->site_name }}</span>
        </a>
        <nav class="site-header-nav">
            <a href="{{ url('/') }}">Security</a>
            <a href="{{ url('/faq') }}">FAQs</a>
            <a href="{{ route('login') }}" class="btn-outline">Log in</a>
        </nav>
        <button class="hamburger" onclick="toggleMobileMenu()" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</div>

{{-- Mobile menu overlay --}}
<div class="mobile-menu" id="mobileMenu">
    <a href="{{ url('/') }}">Security</a>
    <a href="{{ url('/faq') }}">FAQs</a>
    <a href="{{ route('login') }}" class="btn-outline">Log in</a>
</div>

<div class="page">
    <div class="container">

        {{-- Card --}}
        <div class="card">

            {{-- Card header --}}
            <header class="card-header">
                <a href="/" style="flex-shrink:0;display:flex;">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}">
                </a>
                <h1>Open an account</h1>
            </header>

            <form method="POST" action="{{ route('register') }}" id="registerForm"
                  onsubmit="var btn=document.getElementById('registerBtn'); btn.disabled=true; btn.querySelector('span').textContent='Processing...'; btn.querySelector('svg').classList.remove('hidden');">
                @csrf

                {{-- Status Alert --}}
                @if (Session::has('status'))
                    <div class="alert alert-error">{{ session('status') }}</div>
                @endif

                {{-- Section 1: Tell us about yourself --}}
                <section class="section">

                    <div class="section-head">
                        <h2>Tell us about yourself</h2>
                        <p>First, we'll need a few details.</p>
                    </div>

                    {{-- Group: Full name & username --}}
                    <div class="group">
                        <p class="group-label">Name &amp; username</p>
                        <div class="grid2">
                            <div>
                                <label class="field-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                    placeholder="Enter your full name" class="field-input">
                                @error('name')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Username</label>
                                <input type="text" name="username" value="{{ old('username') }}" required
                                    placeholder="Enter preferred username" class="field-input">
                                @error('username')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Group: Contact information --}}
                    <div class="group">
                        <p class="group-label">Contact information</p>
                        <div class="grid2">
                            <div>
                                <label class="field-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                                    placeholder="Enter your email" class="field-input">
                                @error('email')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" required maxlength="13"
                                    placeholder="Enter your phone number" class="field-input">
                                @error('phone')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </section>

                {{-- Section 2: Personal details --}}
                <section class="section">

                    <div class="section-head no-rule">
                        <h2 class="sm">Where are you from?</h2>
                    </div>

                    {{-- Group: Demographics & location --}}
                    <div class="group">
                        <p class="group-label">Demographics &amp; location</p>
                        <div class="grid2">
                            <div>
                                <label class="field-label">Gender</label>
                                <select name="gender" required class="field-input">
                                    <option value="">Select gender</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Others" {{ old('gender') == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                                @error('gender')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Country</label>
                                <select name="country" required class="field-input">
                                    @include('auth.countries')
                                </select>
                                @error('country')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Preferred Currency --}}
                    <div class="group">
                        <label class="field-label">Preferred Currency</label>
                        <select name="currency_code" required class="field-input">
                            @foreach(\App\Models\ExchangeRate::where('is_active', true)->orderBy('currency_code')->get() as $rate)
                                <option value="{{ $rate->currency_code }}"
                                    {{ old('currency_code', 'USD') == $rate->currency_code ? 'selected' : '' }}>
                                    {{ $rate->currency_code }} ({{ html_entity_decode($rate->currency_symbol) }}){{ $rate->currency_name ? ' — ' . $rate->currency_name : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('currency_code')
                            <p class="field-error">{{ $message }}</p>
                        @enderror
                        <p class="helper">All balances and amounts will be displayed in this currency.</p>
                    </div>

                </section>

                {{-- Section 3: Account security --}}
                <section class="section">

                    <div class="section-head no-rule">
                        <h2 class="sm">Secure your account</h2>
                    </div>

                    {{-- Password group --}}
                    <div class="group">
                        <p class="group-label">Password</p>
                        <div class="grid2">
                            <div>
                                <label class="field-label">Password</label>
                                <input type="password" name="password" required autocomplete="new-password"
                                    placeholder="Create a password" class="field-input">
                                @error('password')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="field-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" required
                                    placeholder="Confirm your password" class="field-input">
                            </div>
                        </div>
                    </div>

                    {{-- Referral (conditional) --}}
                    @if (Session::has('ref_by'))
                        <div class="group">
                            <label class="field-label">Referral ID</label>
                            <input type="text" name="ref_by" value="{{ session('ref_by') }}" required
                                placeholder="Referral code" class="field-input">
                        </div>
                    @endif

                    {{-- Captcha --}}
                    <div class="group">
                        <label class="field-label">Security Check</label>
                        <div class="captcha-row">
                            <div class="captcha-box">{{ $captchaA }} + {{ $captchaB }} =</div>
                            <input type="text" name="captcha" required inputmode="numeric"
                                placeholder="Answer" class="field-input captcha-input">
                        </div>
                        @if ($errors->has('captcha'))
                            <p class="field-error">{{ $errors->first('captcha') }}</p>
                        @endif
                        <input type="hidden" name="captcha_confirmation" value="{{ $captchaAnswer }}">
                    </div>

                </section>

                {{-- Section 4: Account Type --}}
                <section class="section">

                    <div class="section-head no-rule">
                        <h2 class="sm">Account Type</h2>
                        <p>Select one or more account types you're interested in.</p>
                    </div>

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

                    <div class="chips">
                        @foreach($accountTypes as $slug => $label)
                        <label class="chip-label">
                            <input type="checkbox" name="account[]" value="{{ $label }}"
                                {{ in_array($label, $oldAccounts, true) || $preselect === $label ? 'checked' : '' }}>
                            <span class="chip">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>

                    @error('account')
                        <p class="field-error" style="margin-top:0.5rem">{{ $message }}</p>
                    @enderror

                </section>

                {{-- Form Actions --}}
                <div class="section">
                    <button type="submit" id="registerBtn" class="btn-login">
                        <span>Register</span>
                        <svg class="spinner hidden" width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle style="opacity:0.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path style="opacity:0.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </button>
                    <p class="signin-line">
                        Already have an account?
                        <a href="{{ route('login') }}">Sign In</a>
                    </p>
                </div>

            </form>

        </div>{{-- /card --}}

        {{-- Footer --}}
        <div class="footer">
            <span>Already with {{ $settings->site_name }}? <a href="{{ route('login') }}">Log in</a></span>
            <div class="footer-nav">
                <a href="{{ url('/') }}">Security</a>
                <a href="{{ url('/faq') }}">FAQs</a>
            </div>
        </div>

    </div>
</div>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const btn  = document.querySelector('.hamburger');
    menu.classList.toggle('open');
    btn.classList.toggle('open');
    document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
}

document.addEventListener('DOMContentLoaded', function() {
    const menu = document.getElementById('mobileMenu');
    menu.querySelectorAll('a').forEach(function(link) {
        link.addEventListener('click', function() {
            menu.classList.remove('open');
            document.querySelector('.hamburger').classList.remove('open');
            document.body.style.overflow = '';
        });
    });
});
</script>

</body>
</html>
