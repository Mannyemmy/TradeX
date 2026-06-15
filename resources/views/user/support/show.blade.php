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
    @include('user.partials.page-header', ['title' => $ticket->subject, 'subtitle' => "Ticket {$ticket->ticket_id}"])

    <div class="max-w-3xl mx-auto">
        {{-- Back Link --}}
        <div class="mb-4">
            <a href="{{ route('support') }}" class="inline-flex items-center gap-1 text-sm text-content-tertiary hover:text-primary transition-colors">
                <x-icon name="arrow-left" class="w-4 h-4" />
                Back to Tickets
            </a>
        </div>

        {{-- Ticket Info Bar --}}
        <div class="rounded-xl bg-surface-raised border border-surface-border p-4 mb-4 flex flex-wrap items-center gap-4 text-sm">
            <div>
                <span class="text-content-tertiary">Status:</span>
                @if($ticket->status === 'open')
                    <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-warning/10 text-warning ml-1">Open</span>
                @elseif($ticket->status === 'answered')
                    <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-gain/10 text-gain ml-1">Answered</span>
                @else
                    <span class="inline-flex items-center text-xs font-medium px-2 py-0.5 rounded-full bg-content-tertiary/10 text-content-tertiary ml-1">Closed</span>
                @endif
            </div>
            <div>
                <span class="text-content-tertiary">Priority:</span>
                <span class="text-content-primary font-medium ml-1 capitalize">{{ $ticket->priority }}</span>
            </div>
            <div>
                <span class="text-content-tertiary">Created:</span>
                <span class="text-content-primary ml-1">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>

        {{-- Messages Thread --}}
        <div class="space-y-4 mb-6">
            @foreach($messages as $msg)
                <div class="flex {{ $msg->sender_type === 'user' ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-[80%] {{ $msg->sender_type === 'user' ? 'bg-primary/10 border-primary/20' : 'bg-surface-raised border-surface-border' }} border rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            @if($msg->sender_type === 'admin')
                                <div class="w-6 h-6 rounded-full bg-gain/20 flex items-center justify-center">
                                    <x-icon name="shield-check" class="w-3.5 h-3.5 text-gain" />
                                </div>
                                <span class="text-xs font-semibold text-gain">Support Team</span>
                            @else
                                <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center">
                                    <x-icon name="user" class="w-3.5 h-3.5 text-primary" />
                                </div>
                                <span class="text-xs font-semibold text-primary">You</span>
                            @endif
                            <span class="text-xs text-content-tertiary">{{ $msg->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                        <p class="text-sm text-content-primary whitespace-pre-wrap">{{ $msg->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Reply Form --}}
        @if($ticket->status !== 'closed')
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5">
                <h4 class="text-sm font-semibold text-content-primary mb-3">Reply</h4>
                <form method="POST" action="{{ route('support.reply', $ticket->ticket_id) }}" class="space-y-4">
                    @csrf
                    <textarea name="message" rows="4" required minlength="5"
                              class="w-full px-4 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary transition-colors"
                              placeholder="Type your reply..."></textarea>
                    @error('message')
                        <p class="text-xs text-loss mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end">
                        <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                            Send Reply
                        </button>
                    </div>
                </form>
            </div>
        @else
            <div class="rounded-xl bg-surface-raised border border-surface-border p-5 text-center">
                <p class="text-sm text-content-tertiary">This ticket has been closed. If you need further assistance, please create a new ticket.</p>
            </div>
        @endif
    </div>

@endsection
