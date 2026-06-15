@extends('layouts.guest1')
@section('title', 'Two factor authentication')
@section('content')

<div class="w-full max-w-md mx-auto">
    {{-- Alert --}}
    @if (Session::has('message'))
        <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-red-300">{{ Session::get('message') }}</p>
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

        <h1 class="text-2xl font-bold text-content-primary text-center mb-2">Two Factor Authentication</h1>
        <p class="text-content-tertiary text-sm text-center mb-6">A 2FA code has been sent to your email. Enter the code below to continue.</p>

        <form method="POST" action="{{ route('twofalogin') }}">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Code</label>
                <input type="password" name="twofa" required
                    placeholder="Enter the code you received"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                @error('twofa')
                    <p class="mt-1.5 text-sm text-loss">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors mb-4">
                Sign In
            </button>

            <p class="text-sm text-center text-content-tertiary">
                Back to
                <a href="{{ route('adminlogout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-primary-light hover:text-primary transition-colors">Sign in</a>
            </p>
        </form>

        <form id="logout-form" action="{{ route('adminlogout') }}" method="POST" class="hidden">
            {{ csrf_field() }}
        </form>
    </div>
</div>

@endsection
