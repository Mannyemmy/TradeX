<div class="bg-surface-raised border border-surface-border rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <h3 class="text-sm font-semibold text-content-primary">Support</h3>
            @if($openTicketCount > 0)
                <span class="min-w-[18px] h-[18px] flex items-center justify-center bg-loss text-white text-[10px] font-bold rounded-full px-1">{{ $openTicketCount }}</span>
            @endif
        </div>
        <a href="{{ route('support.create') }}" class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:text-primary-dark transition-colors">
            <x-icon name="plus" class="w-3.5 h-3.5" /> New Ticket
        </a>
    </div>

    @if($recentTickets->count() > 0)
        <div class="space-y-2">
            @foreach($recentTickets as $ticket)
                <a href="{{ route('support.show', $ticket->ticket_id) }}"
                   class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg bg-surface-overlay hover:bg-surface-border/40 transition-colors group">
                    <div class="min-w-0 flex-1">
                        <p class="text-xs font-medium text-content-primary truncate group-hover:text-primary transition-colors">{{ $ticket->subject }}</p>
                        <p class="text-[10px] text-content-tertiary mt-0.5">{{ $ticket->ticket_id }} · {{ $ticket->updated_at->diffForHumans() }}</p>
                    </div>
                    @if($ticket->status === 'answered')
                        <span class="shrink-0 inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded-full bg-gain/10 text-gain">Reply</span>
                    @elseif($ticket->status === 'open')
                        <span class="shrink-0 inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded-full bg-warning/10 text-warning">Open</span>
                    @else
                        <span class="shrink-0 inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded-full bg-content-tertiary/10 text-content-tertiary">Closed</span>
                    @endif
                </a>
            @endforeach
        </div>
        <a href="{{ route('support') }}" class="block text-center text-xs text-primary hover:text-primary-dark mt-3 transition-colors">View all tickets</a>
    @else
        <div class="text-center py-4">
            <x-icon name="chat-bubble-left-right" class="w-8 h-8 text-content-tertiary mx-auto mb-2" />
            <p class="text-xs text-content-tertiary mb-3">Need help? Open a support ticket.</p>
            <a href="{{ route('support.create') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-xs font-semibold transition-colors">
                <x-icon name="plus" class="w-3.5 h-3.5" /> Create Ticket
            </a>
        </div>
    @endif
</div>
