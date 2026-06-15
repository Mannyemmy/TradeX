@extends('layouts.admin-dash')
@section('title', 'View Agent Clients')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Agent: {{ $agent->name }}" subtitle="View clients assigned to this agent.">
        <x-slot name="actions">
            <a href="{{ url()->previous() }}"
                class="bg-surface-alt text-content border border-border hover:bg-border rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Back
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('message'))
        <x-admin.alert type="info" :dismissible="true" class="mt-4">{{ session('message') }}</x-admin.alert>
    @endif

    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Clients Table --}}
    <div class="mt-6">
        <x-admin.table-card title="Agent Clients">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Client Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Investment Plan</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ag_r as $client)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $client->name }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">
                                {{ isset($client->dplan->name) ? $client->dplan->name : 'NULL' }}
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($client->status == 'active')
                                    <x-admin.badge type="success">{{ $client->status }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">{{ $client->status }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content">{{ $client->account_bal }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection
