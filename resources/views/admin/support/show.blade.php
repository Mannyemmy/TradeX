@extends('layouts.admin-dash')
@section('title', "Ticket {$ticket->ticket_id}")

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

    {{-- Back Link --}}
    <div class="mb-4">
        <a href="{{ route('admin.support.index') }}" class="inline-flex items-center gap-1 text-sm text-content-muted hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Tickets
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main: Messages Thread --}}
        <div class="lg:col-span-2">
            <div class="bg-surface-card rounded-xl border border-border shadow-card">
                <div class="px-5 py-4 border-b border-border">
                    <h3 class="text-base font-medium text-content">{{ $ticket->subject }}</h3>
                </div>

                <div class="p-5 space-y-4 max-h-[600px] overflow-y-auto">
                    @foreach($messages as $msg)
                        <div class="flex {{ $msg->sender_type === 'admin' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[80%] {{ $msg->sender_type === 'admin' ? 'bg-primary/10 border-primary/20' : 'bg-surface-alt border-border' }} border rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($msg->sender_type === 'admin')
                                        <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-primary">Admin</span>
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-warning/20 flex items-center justify-center">
                                            <svg class="w-3.5 h-3.5 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                        <span class="text-xs font-semibold text-warning">{{ $ticket->user->name ?? 'User' }}</span>
                                    @endif
                                    <span class="text-xs text-content-muted">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                                <p class="text-sm text-content whitespace-pre-wrap">{{ $msg->message }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Reply Form --}}
                @if($ticket->status !== 'closed')
                    <div class="border-t border-border p-5">
                        <form method="POST" action="{{ route('admin.support.reply', $ticket->ticket_id) }}" class="space-y-3">
                            @csrf
                            <textarea name="message" rows="4" required minlength="5"
                                      class="w-full px-4 py-2.5 rounded-lg bg-surface-alt border border-border text-sm text-content placeholder-content-muted focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors"
                                      placeholder="Type your reply to the user..."></textarea>
                            @error('message')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-hover text-white text-sm font-medium transition-colors">
                                    Send Reply
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Sidebar: Ticket Info --}}
        <div class="space-y-4">
            {{-- Ticket Details --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-card p-5">
                <h4 class="text-sm font-medium text-content mb-4">Ticket Details</h4>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Ticket ID</dt>
                        <dd class="font-mono text-content">{{ $ticket->ticket_id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Status</dt>
                        <dd>
                            @if($ticket->status === 'open')
                                <x-admin.badge type="warning">Open</x-admin.badge>
                            @elseif($ticket->status === 'answered')
                                <x-admin.badge type="success">Answered</x-admin.badge>
                            @else
                                <x-admin.badge type="neutral">Closed</x-admin.badge>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Priority</dt>
                        <dd>
                            @if($ticket->priority === 'high')
                                <x-admin.badge type="danger">High</x-admin.badge>
                            @elseif($ticket->priority === 'medium')
                                <x-admin.badge type="warning">Medium</x-admin.badge>
                            @else
                                <x-admin.badge type="neutral">Low</x-admin.badge>
                            @endif
                        </dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Created</dt>
                        <dd class="text-content">{{ $ticket->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Updated</dt>
                        <dd class="text-content">{{ $ticket->updated_at->diffForHumans() }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Messages</dt>
                        <dd class="text-content">{{ $messages->count() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- User Info --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-card p-5">
                <h4 class="text-sm font-medium text-content mb-4">User Info</h4>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Name</dt>
                        <dd class="text-content">{{ $ticket->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-content-muted">Email</dt>
                        <dd class="text-content text-xs">{{ $ticket->user->email ?? 'N/A' }}</dd>
                    </div>
                    @if($ticket->user)
                        <div class="pt-2">
                            <a href="{{ route('viewuser', $ticket->user->id) }}"
                               class="text-xs font-medium text-primary hover:text-primary-hover transition-colors">
                                View User Profile →
                            </a>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Status Actions --}}
            <div class="bg-surface-card rounded-xl border border-border shadow-card p-5">
                <h4 class="text-sm font-medium text-content mb-4">Actions</h4>
                <div class="space-y-2">
                    @if($ticket->status !== 'closed')
                        <form method="POST" action="{{ route('admin.support.status', $ticket->ticket_id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="closed" />
                            <button type="submit"
                                    class="w-full px-4 py-2 rounded-lg bg-danger/10 text-danger hover:bg-danger/20 text-sm font-medium transition-colors">
                                Close Ticket
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.support.status', $ticket->ticket_id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="open" />
                            <button type="submit"
                                    class="w-full px-4 py-2 rounded-lg bg-warning/10 text-warning hover:bg-warning/20 text-sm font-medium transition-colors">
                                Reopen Ticket
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
