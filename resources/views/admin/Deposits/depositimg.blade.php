@extends('layouts.admin-dash')
@section('title', 'View Deposit Screenshot')
@section('content')
    <x-admin.page-header title="View Deposit Screenshot">
        <x-slot name="actions">
            <a class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors" href="{{ route('mdeposits') }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                back
            </a>
        </x-slot>
    </x-admin.page-header>

    <div class="mt-6 max-w-3xl mx-auto">
        <x-admin.card>
            <img src="{{ asset('storage/app/public/' . $deposit->proof) }}" alt="Proof of Payment" class="w-full rounded-lg" />
        </x-admin.card>
    </div>
@endsection
