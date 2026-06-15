@extends('layouts.admin-dash')
@section('title', 'Support Tickets')

@section('content')
    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mb-6">
            {{ session('success') }}
        </x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mb-6">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    {{-- Page Header --}}
    <x-admin.page-header title="Support Tickets" subtitle="Manage and respond to user support requests" />

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card
            label="Total Tickets"
            :value="$counts['all']"
            icon='<svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>'
        />
        <x-admin.stat-card
            label="Open"
            :value="$counts['open']"
            icon='<svg class="w-5 h-5 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" /></svg>'
        />
        <x-admin.stat-card
            label="Answered"
            :value="$counts['answered']"
            icon='<svg class="w-5 h-5 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        />
        <x-admin.stat-card
            label="Closed"
            :value="$counts['closed']"
            icon='<svg class="w-5 h-5 text-content-secondary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>'
        />
    </div>

    {{-- Filters --}}
    <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 mb-6">
        <form method="GET" action="{{ route('admin.support.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.support.index') }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ !$currentStatus ? 'bg-primary text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">
                    All
                </a>
                <a href="{{ route('admin.support.index', ['status' => 'open']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $currentStatus === 'open' ? 'bg-warning text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">
                    Open
                </a>
                <a href="{{ route('admin.support.index', ['status' => 'answered']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $currentStatus === 'answered' ? 'bg-success text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">
                    Answered
                </a>
                <a href="{{ route('admin.support.index', ['status' => 'closed']) }}"
                   class="px-3 py-1.5 rounded-lg text-xs font-medium transition-colors {{ $currentStatus === 'closed' ? 'bg-content-secondary text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">
                    Closed
                </a>
            </div>
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ $currentSearch }}"
                       placeholder="Search tickets, users..."
                       class="w-full px-3 py-1.5 rounded-lg bg-surface-alt border border-border text-sm text-content placeholder-content-muted focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors" />
            </div>
            <button type="submit"
                    class="px-4 py-1.5 rounded-lg bg-primary hover:bg-primary-hover text-white text-sm font-medium transition-colors">
                Search
            </button>
        </form>
    </div>

    {{-- Tickets Table --}}
    <x-admin.table-card title="Tickets">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border">
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Ticket</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">User</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Subject</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Priority</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Last Update</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($tickets as $ticket)
                    <tr class="hover:bg-surface-alt/50 transition-colors">
                        <td class="px-5 py-3 font-mono text-xs text-content-secondary">{{ $ticket->ticket_id }}</td>
                        <td class="px-5 py-3">
                            <div>
                                <p class="text-sm font-medium text-content">{{ $ticket->user->name ?? 'N/A' }}</p>
                                <p class="text-xs text-content-muted">{{ $ticket->user->email ?? '' }}</p>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-content max-w-[200px] truncate">{{ $ticket->subject }}</td>
                        <td class="px-5 py-3">
                            @if($ticket->status === 'open')
                                <x-admin.badge type="warning">Open</x-admin.badge>
                            @elseif($ticket->status === 'answered')
                                <x-admin.badge type="success">Answered</x-admin.badge>
                            @else
                                <x-admin.badge type="neutral">Closed</x-admin.badge>
                            @endif
                        </td>
                        <td class="px-5 py-3">
                            @if($ticket->priority === 'high')
                                <x-admin.badge type="danger">High</x-admin.badge>
                            @elseif($ticket->priority === 'medium')
                                <x-admin.badge type="warning">Medium</x-admin.badge>
                            @else
                                <x-admin.badge type="neutral">Low</x-admin.badge>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs text-content-muted">{{ $ticket->updated_at->diffForHumans() }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.support.show', $ticket->ticket_id) }}"
                               class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:text-primary-hover transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-10 text-center text-content-muted">
                            No support tickets found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.table-card>

    @if($tickets->hasPages())
        <div class="mt-6">
            {{ $tickets->appends(request()->query())->links() }}
        </div>
    @endif
@endsection
