@extends('layouts.admin-dash')
@section('title', 'Assistant Chat')

@section('content')
@php
    $statusMap = ['pending' => 'bg-warning/15 text-warning', 'answered' => 'bg-success/15 text-success', 'bot' => 'bg-primary/15 text-primary', 'closed' => 'bg-content-secondary/15 text-content-secondary'];
@endphp

<div class="mb-4">
    <a href="{{ route('admin.assistant.index') }}" class="text-sm text-content-muted hover:text-content">&larr; Back to chats</a>
</div>

<div class="bg-surface-card rounded-xl border border-border shadow-card overflow-hidden">
    {{-- Header --}}
    <div class="flex items-center justify-between gap-3 px-5 py-4 border-b border-border">
        <div>
            <div class="font-semibold text-content">{{ $conversation->display_name }}</div>
            <div class="text-xs text-content-muted">
                {{ $conversation->user ? $conversation->user->email : ($conversation->guest_email ?: 'Guest — no email') }}
                · {{ $conversation->user_id ? 'Member' : 'Guest' }}
            </div>
        </div>
        <div class="flex items-center gap-3">
            <span id="statusBadge" class="px-2 py-0.5 rounded-full text-xs font-medium {{ $statusMap[$conversation->status] ?? 'bg-surface-alt' }}">{{ ucfirst($conversation->status) }}</span>
            @if ($conversation->status !== 'closed')
                <button id="closeBtn" class="px-3 py-1.5 rounded-lg bg-surface-alt text-content-secondary hover:bg-surface-alt/80 text-xs font-medium">Close chat</button>
            @endif
        </div>
    </div>

    {{-- Thread --}}
    <div id="thread" class="p-5 space-y-3 bg-surface-alt/30" style="height:60vh;overflow-y:auto">
        @foreach ($messages as $m)
            @include('admin.assistant._bubble', ['m' => $m])
        @endforeach
    </div>

    {{-- Reply --}}
    <div class="border-t border-border p-4">
        @if ($conversation->status === 'closed')
            <p class="text-sm text-content-muted text-center">This conversation is closed.</p>
        @else
            <form id="replyForm" class="flex items-end gap-2">
                <textarea id="replyInput" rows="2" placeholder="Type your reply…" class="flex-1 px-3 py-2 rounded-lg bg-surface-alt border border-border text-sm text-content placeholder-content-muted focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary resize-none"></textarea>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary hover:bg-primary-hover text-white text-sm font-medium">Send</button>
            </form>
        @endif
    </div>
</div>

<script>
(function () {
    var CSRF = '{{ csrf_token() }}';
    var thread = document.getElementById('thread');
    var pollUrl = '{{ route('admin.assistant.messages', $conversation->id) }}';
    var replyUrl = '{{ route('admin.assistant.reply', $conversation->id) }}';
    var closeUrl = '{{ route('admin.assistant.close', $conversation->id) }}';
    var lastId = {{ $messages->last()->id ?? 0 }};

    function esc(s){var d=document.createElement('div');d.textContent=s;return d.innerHTML;}
    function scroll(){thread.scrollTop=thread.scrollHeight;}

    function bubble(m){
        var wrap=document.createElement('div');
        var who, cls, align;
        if(m.sender==='admin'){who='You (agent)';cls='bg-primary text-white';align='justify-end';}
        else if(m.sender==='user'){who='Customer';cls='bg-surface-card border border-border text-content';align='justify-start';}
        else if(m.sender==='assistant'){who='AI assistant';cls='bg-surface-card border border-border text-content-secondary';align='justify-start';}
        else {who='';cls='bg-surface-alt text-content-muted text-xs mx-auto';align='justify-center';}
        wrap.className='flex '+align;
        var inner='<div class="max-w-[75%]">';
        if(who) inner+='<div class="text-[10px] text-content-muted mb-0.5 '+(m.sender==='admin'?'text-right':'')+'">'+who+(m.time?' · '+esc(m.time):'')+'</div>';
        inner+='<div class="px-3 py-2 rounded-xl text-sm whitespace-pre-wrap '+cls+'">'+esc(m.text)+'</div></div>';
        wrap.innerHTML=inner;
        thread.appendChild(wrap);
    }

    scroll();

    function poll(){
        fetch(pollUrl+'?after_id='+lastId,{headers:{'Accept':'application/json'},credentials:'same-origin'})
        .then(function(r){return r.json();}).then(function(d){
            (d.messages||[]).forEach(function(m){ if(m.id>lastId){bubble(m);lastId=m.id;} });
            if(d.messages&&d.messages.length)scroll();
        }).catch(function(){});
    }
    setInterval(poll,5000);

    var form=document.getElementById('replyForm');
    if(form){
        form.addEventListener('submit',function(e){
            e.preventDefault();
            var inp=document.getElementById('replyInput');var text=inp.value.trim();if(!text)return;
            var btn=form.querySelector('button');btn.disabled=true;
            fetch(replyUrl,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},credentials:'same-origin',body:JSON.stringify({message:text})})
            .then(function(r){return r.json();}).then(function(d){
                btn.disabled=false;
                if(d.ok&&d.message){inp.value='';bubble(d.message);lastId=Math.max(lastId,d.message.id);scroll();var sb=document.getElementById('statusBadge');if(sb){sb.textContent='Answered';sb.className='px-2 py-0.5 rounded-full text-xs font-medium bg-success/15 text-success';}}
            }).catch(function(){btn.disabled=false;});
        });
    }

    var closeBtn=document.getElementById('closeBtn');
    if(closeBtn){
        closeBtn.addEventListener('click',function(){
            if(!confirm('Close this conversation?'))return;
            fetch(closeUrl,{method:'POST',headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF,'X-Requested-With':'XMLHttpRequest'},credentials:'same-origin'})
            .then(function(r){return r.json();}).then(function(){location.reload();});
        });
    }
})();
</script>
@endsection
