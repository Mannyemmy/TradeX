@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'KYC Verification', 'subtitle' => 'Verify your identity to comply with regulations'])

    {{-- KYC Card --}}
    <div class="max-w-xl mx-auto">
        @if (Auth::user()->account_verify == 'Under review')
            {{-- Pending Review State --}}
            <div class="rounded-xl bg-surface-raised border border-warning/30 p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-warning/10 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="clock" class="w-8 h-8 text-warning" />
                </div>

                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-warning/10 text-warning text-xs font-semibold mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-warning animate-pulse"></span>
                    Under Review
                </span>

                <h3 class="text-lg font-bold text-content-primary mb-2">KYC Application Submitted</h3>
                <p class="text-sm text-content-secondary mb-4">
                    Your identity documents have been submitted successfully and are currently being reviewed by our compliance team.
                </p>
                <p class="text-sm text-content-tertiary mb-6">
                    This process typically takes 1–3 business days. You will be notified once your verification is complete.
                </p>

                <div class="p-4 rounded-lg bg-surface-overlay border border-surface-border">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-content-tertiary">Status</span>
                        <span class="text-warning font-medium">Pending Review</span>
                    </div>
                </div>
            </div>
        @else
            {{-- Not Submitted State --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-8 text-center">
                <div class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center mx-auto mb-4">
                    <x-icon name="identification" class="w-8 h-8 text-primary" />
                </div>

                <h3 class="text-lg font-bold text-content-primary mb-2">KYC Verification</h3>
                <p class="text-sm text-content-secondary mb-4">
                    To comply with regulation, each participant will have to go through identity verification (KYC/AML) to prevent fraud.
                </p>
                <p class="text-sm text-content-tertiary mb-6">
                    You have not submitted your necessary documents to verify your identity. Please verify your identity to enjoy our services.
                </p>

                <a href="{{ route('kycform') }}" class="inline-block px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    Click here to complete your KYC
                </a>
            </div>
        @endif
    </div>

@endsection
