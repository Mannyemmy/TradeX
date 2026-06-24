@php
    $sender = $m->sender_type;
    if ($sender === 'admin') { $who = 'You (agent)'; $cls = 'bg-primary text-white'; $align = 'justify-end'; $whoAlign = 'text-right'; }
    elseif ($sender === 'user') { $who = 'Customer'; $cls = 'bg-surface-card border border-border text-content'; $align = 'justify-start'; $whoAlign = ''; }
    elseif ($sender === 'assistant') { $who = 'AI assistant'; $cls = 'bg-surface-card border border-border text-content-secondary'; $align = 'justify-start'; $whoAlign = ''; }
    else { $who = ''; $cls = 'bg-surface-alt text-content-muted text-xs mx-auto'; $align = 'justify-center'; $whoAlign = ''; }
    $time = optional($m->created_at)->format('M d, H:i');
@endphp
<div class="flex {{ $align }}">
    <div class="max-w-[75%]">
        @if ($who)
            <div class="text-[10px] text-content-muted mb-0.5 {{ $whoAlign }}">{{ $who }}@if($time) · {{ $time }}@endif</div>
        @endif
        <div class="px-3 py-2 rounded-xl text-sm whitespace-pre-wrap {{ $cls }}">{{ $m->message }}</div>
    </div>
</div>
