<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | {{ $settings->site_name }}</title>
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

        /* ── Header nav (like test-homepage) ── */
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
            font-family: 'Raleway', 'Segoe UI', sans-serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 2px;
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

        /* ── Desktop layout: centered card ── */
        .page {
            min-height: calc(100vh - 64px);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 3rem 1rem 3rem;
        }

        .container {
            width: 100%;
            max-width: 448px;
        }

        /* ── Card ── */
        .card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            padding: 2rem 2rem 2.5rem;
        }

        /* ── Mobile logo inside card ── */
        .mobile-logo { display: none; margin-bottom: 1.5rem; }
        .mobile-logo img { height: 40px; width: auto; }

        /* ── Alert ── */
        .alert {
            margin-bottom: 1.25rem;
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

        /* ── Heading ── */
        .card h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        /* ── Form fields ── */
        .field { margin-bottom: 1rem; }
        .field-last { margin-bottom: 1.5rem; }

        label.field-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0.375rem;
        }

        .input-wrap { position: relative; }

        input.field-input {
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
        input.field-input:focus {
            box-shadow: 0 0 0 2px #1a1a1a;
            border-color: #1a1a1a;
        }
        input.field-input.has-toggle { padding-right: 2.75rem; }

        /* ── Password toggle ── */
        .toggle-btn {
            position: absolute;
            right: 0.65rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #555;
            display: flex;
            align-items: center;
            line-height: 0;
        }
        .toggle-btn:focus { outline: 2px solid #1a1a1a; outline-offset: 2px; border-radius: 2px; }

        /* ── Field error ── */
        .field-error { margin-top: 0.25rem; font-size: 0.8125rem; color: #c00; }

        /* ── Submit button — pill shape ── */
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
        }
        .btn-login:hover { background: #1a3a7f; }
        .btn-login:focus { outline: 2px solid #1a1a1a; outline-offset: 2px; }

        /* ── Forgot link ── */
        .forgot { margin-top: 1.25rem; }
        .forgot a {
            color: #2E5C8A;
            font-size: 0.9375rem;
            text-decoration: underline;
        }
        .forgot a:hover { color: #1a3a7f; }

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
        .footer a {
            color: #2E5C8A;
            text-decoration: underline;
        }
        .footer a:hover { color: #1a3a7f; }
        .footer-nav {
            margin-top: 0.875rem;
            display: flex;
            gap: 1.5rem;
            justify-content: center;
        }
        .footer-nav a {
            font-size: 0.875rem;
            color: #333;
            text-decoration: underline;
        }

        /* ── Mobile (≤ 512px / 32em) ── */
        @media (max-width: 32em) {
            html, body { background-color: #fff; }
            .page { padding: 0; align-items: stretch; background: #fff; }
            .container { max-width: 100%; }

            /* Header: smaller padding on mobile */
            .site-header { padding: 0 0.75rem; }
            .site-header-inner { height: 56px; }
            .site-header-logo img { height: 32px; }
            .site-header-logo span { font-size: 20px; }

            /* Hide desktop nav links on mobile, show hamburger */
            .site-header-nav { display: none; }
            .hamburger { display: flex; }

            /* Mobile menu top offset matches mobile header height */
            .mobile-menu { top: 56px; }

            /* Hide mobile logo inside card (header handles it) */
            .mobile-logo { display: none; }

            /* Card loses all borders on mobile */
            .card {
                border: none;
                border-radius: 0;
                padding: 1.25rem 1rem 2rem;
            }

            /* Footer sticks below card */
            .footer {
                border-top: 1px solid #d6d6d6;
                padding: 1.25rem 1rem;
                text-align: center;
                background: #f2f2f2;
            }
        }
    </style>
</head>
<body>

{{-- Header nav (like test-homepage) --}}
<div class="site-header">
    <div class="site-header-inner">
        <a href="/" class="site-header-logo">
            <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}">
            <span>{{ $settings->site_name }}</span>
        </a>
        <nav class="site-header-nav">
            <a href="{{ url('/') }}">Security</a>
            <a href="{{ url('/faq') }}">FAQs</a>
            <a href="{{ url('open-account.html') }}" class="btn-outline">Open an account</a>
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
    <a href="{{ url('register') }}" class="btn-outline">Open an account</a>
    <a href="{{ route('login') }}">Log in</a>
</div>

<div class="page">
    <div class="container">

        {{-- Card --}}
        <div class="card">

            {{-- Mobile logo — inside card, hidden on desktop --}}
            <div class="mobile-logo">
                <a href="/" style="display:flex;align-items:center;gap:12px;text-decoration:none;">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" style="height:40px;width:auto;">
                    <span>{{ $settings->site_name }}</span>
                </a>
            </div>

            @if (Session::has('status'))
                <div class="alert">{{ session('status') }}</div>
            @endif

            @if ($errors->any() && !$errors->has('email') && !$errors->has('password'))
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            <h1>Log in</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="field">
                    <label class="field-label" for="login-email">Email</label>
                    <input
                        class="field-input"
                        type="text"
                        id="login-email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        maxlength="100"
                    >
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field field-last">
                    <label class="field-label" for="login-password">Password</label>
                    <div class="input-wrap">
                        <input
                            class="field-input has-toggle"
                            type="password"
                            id="login-password"
                            name="password"
                            required
                            autocomplete="current-password"
                            maxlength="100"
                        >
                        <button type="button" class="toggle-btn" onclick="togglePwd()" aria-label="Show password">
                            <svg id="eye-open" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eye-closed" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-login">Log in</button>
            </form>

            <div class="forgot">
                <a href="{{ route('password.request') }}">Forgot password?</a>
            </div>

        </div>{{-- /card --}}

        {{-- Footer --}}
        <div class="footer">
            <span>New to {{ $settings->site_name }}? <a href="{{ url('open-account.html') }}">Open an account</a></span>
            <div class="footer-nav">
                <a href="{{ url('/') }}">Security</a>
                <a href="{{ url('/faq') }}">FAQs</a>
            </div>
        </div>

    </div>
</div>

<script>
function togglePwd() {
    const input  = document.getElementById('login-password');
    const open   = document.getElementById('eye-open');
    const closed = document.getElementById('eye-closed');
    const show   = input.type === 'password';
    input.type        = show ? 'text'  : 'password';
    open.style.display  = show ? 'none' : '';
    closed.style.display = show ? ''    : 'none';
}

function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const btn  = document.querySelector('.hamburger');
    menu.classList.toggle('open');
    btn.classList.toggle('open');
    document.body.style.overflow = menu.classList.contains('open') ? 'hidden' : '';
}

// Close mobile menu when a link is clicked
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