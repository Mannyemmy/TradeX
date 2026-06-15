@extends('layouts.guest1')
@section('title', 'Forgot your password')
@section('content')

<div class="w-full max-w-md mx-auto">
    {{-- Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8">
        <x-danger-alert />
        <x-success-alert />

        {{-- Status Alert --}}
        @if (session('status'))
            <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-emerald-300">{{ session('status') }}</p>
            </div>
        @endif

        {{-- Lock Icon --}}
        <div class="w-16 h-16 mx-auto bg-primary-subtle rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-content-primary text-center mb-1">Forgot Password?</h1>
        <p class="text-content-tertiary text-sm text-center mb-6">Enter your email address and we'll send you instructions to reset your password.</p>

        <form method="POST" action="{{ route('sendpasswordrequest') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    placeholder="Enter your email address"
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
                @error('email')
                    <p class="mt-1.5 text-sm text-loss">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors mb-4">
                Reset Password
            </button>

            <p class="text-sm text-center text-content-tertiary">
                Back to <a href="{{ route('adminloginform') }}" class="text-primary-light hover:text-primary transition-colors font-semibold">Sign in</a>
            </p>
        </form>
    </div>
</div>

@endsection
