@extends('layouts.guest1')
@section('title', 'Login account')

@section('styles')
<style>
    html, body { background-color: #f5f6f7 !important; color: #333 !important; }

    .fid-input {
        width: 100%;
        box-sizing: border-box;
        padding: 0.625rem 0.75rem;
        border: 1px solid #767676;
        border-radius: 4px;
        font-size: 1rem;
        color: #1a1a1a;
        background: #fff;
        outline: none;
        transition: box-shadow 0.15s, border-color 0.15s;
    }
    .fid-input:focus {
        box-shadow: 0 0 0 2px #000;
        border-color: #000;
    }
    .fid-input-password {
        padding-right: 2.75rem;
    }
    .fid-btn {
        width: 100%;
        background: #2E5C8A;
        color: #fff;
        border: none;
        border-radius: 4px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
    }
    .fid-btn:hover { background: #1a3a7f; }
    .fid-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.375rem;
    }
    .fid-link {
        color: #2E5C8A;
        text-decoration: none;
    }
    .fid-link:hover { text-decoration: underline; }
    .fid-error { margin-top: 0.25rem; font-size: 0.8125rem; color: #c00; }
</style>
@endsection

@section('content')

<div style="width: 100%; max-width: 448px; margin: 0 auto;">

    {{-- Page Header: logo left, nav links right --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <a href="/">
            <img src="{{ asset('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" style="height: 38px; object-fit: contain;">
        </a>
        <div style="display: flex; gap: 1rem; align-items: center;">
            <a href="{{ url('/') }}" style="font-size: 0.875rem; color: #333; text-decoration: none;">Security</a>
            <a href="{{ url('/faq') }}" style="font-size: 0.875rem; color: #333; text-decoration: none;">FAQs</a>
        </div>
    </div>

    {{-- Card --}}
    <div style="background: #fff; border: 1px solid #ccc; border-radius: 0.5rem; padding: 2rem 2rem 3rem;">

        {{-- Status Alert --}}
        @if (Session::has('status'))
            <div style="margin-bottom: 1.25rem; padding: 0.75rem 1rem; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px; font-size: 0.875rem; color: #856404;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Title --}}
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #1a1a1a; margin: 0 0 1.5rem 0; line-height: 1.2;">Log in</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Username / Email --}}
            <div style="margin-bottom: 1rem;">
                <label for="fid-email" class="fid-label">Email</label>
                <input
                    type="text"
                    id="fid-email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="off"
                    maxlength="100"
                    class="fid-input"
                    placeholder=""
                >
                @error('email')
                    <p class="fid-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div style="margin-bottom: 1.5rem;">
                <label for="fid-password" class="fid-label">Password</label>
                <div style="position: relative;">
                    <input
                        type="password"
                        id="fid-password"
                        name="password"
                        required
                        autocomplete="off"
                        maxlength="100"
                        class="fid-input fid-input-password"
                        placeholder=""
                    >
                    <button
                        type="button"
                        onclick="fidTogglePassword()"
                        aria-label="Show password"
                        style="position: absolute; right: 0.65rem; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; color: #555; display: flex; align-items: center;"
                    >
                        <svg id="fid-eye-show" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                        <svg id="fid-eye-hide" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="fid-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit" class="fid-btn">Log in</button>

        </form>

        {{-- Forgot password --}}
        <div style="margin-top: 1.25rem;">
            <a href="{{ route('password.request') }}" class="fid-link" style="font-size: 0.875rem;">
                Forgot password?
            </a>
        </div>

    </div>

    {{-- Register link --}}
    <div style="text-align: center; margin-top: 1.5rem; font-size: 0.875rem; color: #333;">
        New to {{ $settings->site_name }}?
        <a href="{{ url('register') }}" class="fid-link" style="font-weight: 600;">Open an account</a>
    </div>

</div>

@endsection

@section('scripts')
<script>
function fidTogglePassword() {
    const input = document.getElementById('fid-password');
    const showIcon = document.getElementById('fid-eye-show');
    const hideIcon = document.getElementById('fid-eye-hide');
    const isHidden = input.type === 'password';
    input.type = isHidden ? 'text' : 'password';
    showIcon.style.display = isHidden ? 'none' : '';
    hideIcon.style.display = isHidden ? '' : 'none';
}
</script>
@endsection
