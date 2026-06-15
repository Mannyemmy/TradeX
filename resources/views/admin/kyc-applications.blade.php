@extends('layouts.admin-dash')
@section('title', 'KYC Application')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="{{ optional($kyc->user)->name ?? 'Deleted User' }} KYC Application">
        <x-slot name="actions">
            <a href="{{ route('kyc') }}"
                class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back to KYC List
            </a>
            <div class="flex items-center gap-2">
                @if ($kyc->status == 'Verified')
                    <x-admin.badge type="success">Verified</x-admin.badge>
                @else
                    <x-admin.badge type="danger">{{ $kyc->status }}</x-admin.badge>
                @endif
                <button @click="$dispatch('open-processkyc')"
                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Action
                </button>
            </div>
        </x-slot>
    </x-admin.page-header>

    {{-- Process KYC Modal --}}
    <x-admin.modal id="processkyc" title="Process KYC" maxWidth="max-w-xl">
        <form action="{{ route('processkyc') }}" method="post">
            @csrf
            <div class="space-y-4">
                <x-admin.form-group label="Action" :required="true">
                    <select name="action" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="Accept">Accept and verify user</option>
                        <option value="Reject">Reject and remain unverified</option>
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Message" :required="true">
                    <textarea name="message" rows="4" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">This is to inform you that following the documents you submited, your account have been verified. You can now enjoy all our services without restrictions. Cheers!!</textarea>
                </x-admin.form-group>

                <x-admin.form-group label="Email Subject" :required="true">
                    <input type="text" name="subject" placeholder="Account is verified successfully" required
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                </x-admin.form-group>

                <input type="hidden" name="kyc_id" value="{{ $kyc->id }}">

                <button type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Confirm
                </button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error') || session('message'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') ?? session('message') }}</x-admin.alert>
    @endif

    {{-- KYC Details --}}
    <div class="mt-6">
        <x-admin.card>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nationality --}}
                <div>
                    <h2 class="text-xl font-semibold text-content">{{ $kyc->country }}</h2>
                    <p class="text-sm text-content-muted mt-1">Nationality</p>
                </div>

                <div class="md:col-span-2 border-b border-border pb-2">
                    <span class="text-sm text-primary font-medium">Document Information</span>
                </div>

                {{-- Document Type --}}
                <div class="md:col-span-2">
                    <h2 class="text-xl font-semibold text-content">{{ $kyc->document_type }}</h2>
                    <p class="text-sm text-content-muted mt-1">Document type</p>
                </div>

                {{-- Front View --}}
                <div>
                    <img src="{{ asset('storage/app/public/' . $kyc->frontimg) }}" alt="Front of document"
                        class="w-full max-w-xs rounded-lg border border-border">
                    <p class="text-sm text-content-muted mt-2">Front View of Document</p>
                </div>

                {{-- Back View --}}
                <div>
                    <img src="{{ asset('storage/app/public/' . $kyc->backimg) }}" alt="Back of document"
                        class="w-full max-w-xs rounded-lg border border-border">
                    <p class="text-sm text-content-muted mt-2">Back View of Document</p>
                </div>
            </div>
        </x-admin.card>
    </div>
@endsection
