<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in | {{ $settings->site_name }}</title>
    <link rel="icon" href="{{ asset('storage/app/public/' . $settings->favicon) }}" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            background-color: #f2f2f2;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Helvetica, Arial, sans-serif;
            color: #333;
            min-height: 100vh;
        }

        /* ── Desktop layout: centered card ── */
        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2.5rem 1rem 3rem;
        }

        .container {
            width: 100%;
            max-width: 448px;
        }

        /* ── Desktop: small logo above card ── */
        .desktop-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }
        .desktop-header img { height: 36px; object-fit: contain; }
        .desktop-header-nav { display: flex; gap: 1rem; }
        .desktop-header-nav a { font-size: 0.875rem; color: #333; text-decoration: none; }
        .desktop-header-nav a:hover { text-decoration: underline; }

        /* ── Card ── */
        .card {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
            padding: 2rem 2rem 2.5rem;
        }

        /* ── Mobile logo inside card ── */
        .mobile-logo { display: none; margin-bottom: 1.5rem; }
        .mobile-logo img { height: 36px; object-fit: contain; }

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

            /* Hide the above-card desktop header on mobile */
            .desktop-header { display: none; }

            /* Show logo inside card */
            .mobile-logo { display: block; }

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

<div class="page">
    <div class="container">

        {{-- Desktop header (logo + nav) — hidden on mobile --}}
        <div class="desktop-header">
            <a href="/"><img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}"></a>
            <nav class="desktop-header-nav">
                <a href="{{ url('/') }}">Security</a>
                <a href="{{ url('/faq') }}">FAQs</a>
            </nav>
        </div>

        {{-- Card --}}
        <div class="card">

            {{-- Mobile logo — inside card, hidden on desktop --}}
            <div class="mobile-logo">
                <a href="/"><img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}"></a>
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
            <span>New to {{ $settings->site_name }}? <a href="{{ url('register') }}">Open an account</a></span>
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
</script>

</body>
</html>