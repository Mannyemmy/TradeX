@extends('layouts.guest1')
@section('title', 'Verify email address')
@section('content')

<div class="w-full max-w-md mx-auto">
    {{-- Alerts --}}
    @if (session('status'))
        <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-emerald-300">A verification link has been sent to your email address. Please click on the link to verify.</p>
        </div>
    @endif

    @if (session('message'))
        <div class="mb-6 flex items-start gap-3 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-emerald-300">{{ session('message') }}</p>
        </div>
    @endif

    {{-- Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8 text-center">
        {{-- Email Icon --}}
        <div class="w-16 h-16 mx-auto bg-primary-subtle rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-content-primary mb-2">Verify Your Email</h1>
        <p class="text-content-tertiary text-sm mb-6">We've sent a link to your email address.<br>Please follow the link inside to continue.</p>

        {{-- Resend --}}
        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <p class="text-sm text-content-tertiary mb-3">Didn't receive an email?</p>
            <button type="submit"
                class="text-sm text-primary-light hover:text-primary font-semibold transition-colors">
                {{ __('Resend Verification Email') }}
            </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full bg-surface-overlay border border-surface-border hover:border-surface-border-light text-content-secondary font-medium py-2.5 rounded-lg transition-colors">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</div>

@endsection
