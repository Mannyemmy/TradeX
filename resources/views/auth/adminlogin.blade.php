@extends('layouts.guest1')
@section('title', 'Login manager account')
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
        {{-- Alerts --}}
        @if (session('message'))
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-300">{{ session('message') }}</p>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-blue-500/10 border border-blue-500/20">
                <svg class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-blue-300">{{ session('success') }}</p>
            </div>
        @endif

        <h1 class="text-2xl font-bold text-content-primary mb-1">Sign In</h1>
        <p class="text-content-tertiary text-sm mb-6">Enter your email address and password to access your account.</p>

        <form method="POST" action="{{ route('adminlogin') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="Your email address"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                @error('email')
                    <p class="mt-1.5 text-sm text-loss">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-6">
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-sm font-medium text-content-secondary">Password</label>
                    <a href="{{ route('admin.forgetpassword') }}" class="text-xs text-primary-light hover:text-primary transition-colors">Forgot password</a>
                </div>
                <input type="password" name="password" autocomplete="off"
                    placeholder="Your password"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors">
                Sign In
            </button>
        </form>
    </div>
</div>

@endsection
