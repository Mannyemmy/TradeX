@extends('layouts.guest1')
@section('title', 'Confirm your password')
@section('content')

<div class="w-full max-w-md mx-auto">
    {{-- Card --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl p-8">
        {{-- Lock Icon --}}
        <div class="w-16 h-16 mx-auto bg-primary-subtle rounded-full flex items-center justify-center mb-6">
            <svg class="w-8 h-8 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-content-primary text-center mb-2">Confirm Password</h1>
        <p class="text-content-tertiary text-sm text-center mb-6">This is a secure area. Please confirm your password before continuing.</p>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-lg bg-red-500/10 border border-red-500/20">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm text-red-300">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-medium text-content-secondary mb-1.5">Enter Password</label>
                <input type="password" name="password" required autocomplete="current-password" autofocus
                    class="w-full bg-surface-overlay border border-surface-border rounded-lg px-4 py-2.5 text-content-primary placeholder-content-tertiary focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors">
            </div>

            <button type="submit"
                class="w-full bg-primary hover:bg-primary-dark text-white font-semibold py-2.5 rounded-lg transition-colors">
                Confirm
            </button>
        </form>
    </div>
</div>

@endsection
