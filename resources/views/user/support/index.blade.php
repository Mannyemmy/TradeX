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
    @include('user.partials.page-header', ['title' => 'Support Tickets', 'subtitle' => 'View and manage your support conversations'])

    <div class="max-w-4xl mx-auto">
        {{-- New Ticket Button --}}
        <div class="flex justify-end mb-4">
            <a href="{{ route('support.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                <x-icon name="plus" class="w-4 h-4" />
                New Ticket
            </a>
        </div>

        @if($tickets->count() > 0)
            <div class="space-y-3">
                @foreach($tickets as $ticket)
                    <a href="{{ route('support.show', $ticket->ticket_id) }}"
                       class="block rounded-xl bg-surface-raised border border-surface-border p-5 hover:border-primary/40 transition-colors group">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-mono text-content-tertiary">{{ $ticket->ticket_id }}</span>
                                    @if($ticket->status === 'open')
                                        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-warning/10 text-warning">Open</span>
                                    @elseif($ticket->status === 'answered')
                                        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-gain/10 text-gain">Answered</span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-content-tertiary/10 text-content-tertiary">Closed</span>
                                    @endif
                                    @if($ticket->priority === 'high')
                                        <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-loss/10 text-loss">High</span>
                                    @endif
                                </div>
                                <h3 class="text-sm font-semibold text-content-primary group-hover:text-primary transition-colors truncate">
                                    {{ $ticket->subject }}
                                </h3>
                                @if($ticket->latestMessage)
                                    <p class="text-xs text-content-tertiary mt-1 truncate">
                                        {{ $ticket->latestMessage->sender_type === 'admin' ? 'Admin: ' : 'You: ' }}{{ Str::limit($ticket->latestMessage->message, 80) }}
                                    </p>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-xs text-content-tertiary">{{ $ticket->updated_at->diffForHumans() }}</p>
                                <p class="text-xs text-content-tertiary mt-0.5">{{ $ticket->messages()->count() }} {{ Str::plural('message', $ticket->messages()->count()) }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="rounded-xl bg-surface-raised border border-surface-border p-10 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-primary/10 mb-4">
                    <x-icon name="chat-bubble-left-right" class="w-7 h-7 text-primary" />
                </div>
                <h3 class="text-lg font-semibold text-content-primary mb-2">No Support Tickets</h3>
                <p class="text-sm text-content-secondary mb-4">You haven't created any support tickets yet.</p>
                <a href="{{ route('support.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                    <x-icon name="plus" class="w-4 h-4" />
                    Create Your First Ticket
                </a>
            </div>
        @endif
    </div>

@endsection
