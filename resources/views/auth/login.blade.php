@extends('layouts.guest1')
@section('title', 'Login account')
@section('content')

<div class="w-full max-w-md mx-auto">
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
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-blue-500/10 border border-blue-500/20">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-300">{{ session('status') }}</p>
            </div>
        @endif

        <h1 class="text-2xl font-bold text-content-primary mb-1">Sign In</h1>
        <p class="text-content-tertiary text-sm mb-6">Sign in to start trading crypto, forex and stocks.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Email or Username</label>
                <input type="text" name="email" value="{{ old('email') }}" required
                    placeholder="Email or Username"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                @error('email')
                    <p class="mt-1.5 text-sm text-loss">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Password</label>
                <input type="password" name="password" required
                    placeholder="Enter your password"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                @error('password')
                    <p class="mt-1.5 text-sm text-loss">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors">
                Sign In
            </button>
        </form>

        {{-- Links --}}
        <div class="mt-6 space-y-2 text-sm">
            <p><a href="{{ route('password.request') }}" class="text-primary-light hover:text-primary transition-colors">Forgot password?</a></p>
            <p class="text-content-tertiary">Don't have an account? <a href="{{ url('register') }}" class="text-primary-light hover:text-primary transition-colors">Register Here</a></p>
        </div>
    </div>
</div>

@endsection
