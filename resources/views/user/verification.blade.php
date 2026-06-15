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
    @include('user.partials.page-header', ['title' => 'ID Verification', 'subtitle' => 'Complete your KYC to unlock all platform features'])

    <div class="max-w-3xl mx-auto">
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

                <div class="p-4 rounded-lg bg-surface-overlay border border-surface-border mb-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-content-tertiary">Status</span>
                        <span class="text-warning font-medium">Pending Review</span>
                    </div>
                </div>

                <a href="{{ route('account.verify') }}" class="text-sm text-primary hover:text-primary-dark transition-colors">
                    &larr; Back to verification overview
                </a>
            </div>
        @else
        <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
            {{-- Header --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary/10 mb-4">
                    <x-icon name="shield-check" class="w-7 h-7 text-primary" />
                </div>
                <h3 class="text-xl font-bold text-content-primary">Begin Your ID Verification</h3>
                <p class="text-sm text-content-secondary mt-2 max-w-md mx-auto">
                    To comply with regulation, each participant must go through identity verification (KYC/AML) to prevent fraud.
                </p>
            </div>

            {{-- Form --}}
            <form action="{{ route('kycsubmit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Document Type Selection --}}
                <div>
                    <h4 class="text-sm font-semibold text-content-primary mb-1">Document Type</h4>
                    <p class="text-xs text-content-tertiary mb-3">Select the type of identification document</p>

                    <div x-data="{ docType: 'Int\'l Passport' }" class="flex flex-wrap gap-3">
                        <label @click="docType = 'Int\'l Passport'"
                               :class="docType === 'Int\'l Passport' ? 'bg-primary text-content-inverse border-primary' : 'bg-surface-overlay text-content-secondary border-surface-border hover:border-primary/50'"
                               class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition-colors text-sm font-medium">
                            <x-icon name="globe-alt" class="w-4 h-4" />
                            <input type="radio" name="document_type" value="Int'l Passport" class="sr-only" checked>
                            Int'l Passport
                        </label>
                        <label @click="docType = 'National ID'"
                               :class="docType === 'National ID' ? 'bg-primary text-content-inverse border-primary' : 'bg-surface-overlay text-content-secondary border-surface-border hover:border-primary/50'"
                               class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition-colors text-sm font-medium">
                            <x-icon name="identification" class="w-4 h-4" />
                            <input type="radio" name="document_type" value="National ID" class="sr-only">
                            National ID
                        </label>
                        <label @click="docType = 'Drivers License'"
                               :class="docType === 'Drivers License' ? 'bg-primary text-content-inverse border-primary' : 'bg-surface-overlay text-content-secondary border-surface-border hover:border-primary/50'"
                               class="flex items-center gap-2 px-4 py-2.5 rounded-lg border cursor-pointer transition-colors text-sm font-medium">
                            <x-icon name="credit-card" class="w-4 h-4" />
                            <input type="radio" name="document_type" value="Drivers License" class="sr-only">
                            Driver's License
                        </label>
                    </div>
                </div>

                {{-- Requirements --}}
                <div class="p-4 rounded-lg bg-info/10 border border-info/20">
                    <h5 class="text-sm font-semibold text-info mb-2">Document Requirements</h5>
                    <ul class="space-y-1.5 text-xs text-content-secondary">
                        <li class="flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-gain flex-shrink-0" />
                            Chosen credential must not have expired.
                        </li>
                        <li class="flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-gain flex-shrink-0" />
                            Document should be in good condition and clearly visible.
                        </li>
                        <li class="flex items-center gap-2">
                            <x-icon name="check-circle" class="w-4 h-4 text-gain flex-shrink-0" />
                            Make sure that there is no light glare on the document.
                        </li>
                    </ul>
                </div>

                {{-- Front Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-content-primary mb-1.5">
                        Upload Front Side <span class="text-loss">*</span>
                    </label>
                    <div class="flex items-center gap-4 p-4 rounded-lg border-2 border-dashed border-surface-border hover:border-primary/50 transition-colors">
                        <div class="flex-1">
                            <input type="file" name="frontimg" required
                                   class="block w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                        </div>
                        <x-icon name="identification" class="w-10 h-10 text-content-tertiary hidden sm:block" />
                    </div>
                </div>

                {{-- Back Image Upload --}}
                <div>
                    <label class="block text-sm font-medium text-content-primary mb-1.5">
                        Upload Back Side <span class="text-loss">*</span>
                    </label>
                    <div class="flex items-center gap-4 p-4 rounded-lg border-2 border-dashed border-surface-border hover:border-primary/50 transition-colors">
                        <div class="flex-1">
                            <input type="file" name="backimg" required
                                   class="block w-full text-sm text-content-secondary file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20 cursor-pointer">
                        </div>
                        <x-icon name="credit-card" class="w-10 h-10 text-content-tertiary hidden sm:block" />
                    </div>
                </div>

                {{-- Agreement Checkbox --}}
                <div class="flex items-start gap-3">
                    <input type="checkbox" id="kycAgree" required
                           class="mt-1 w-4 h-4 rounded border-surface-border bg-surface-overlay text-primary focus:ring-primary">
                    <label for="kycAgree" class="text-sm text-content-secondary">
                        All the information I have entered is correct.
                    </label>
                </div>

                {{-- Submit --}}
                    <button type="submit"
                            class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                        Submit Application
                    </button>
            </form>
        </div>
        @endif
    </div>

@endsection
