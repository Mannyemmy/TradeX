@extends('layouts.admin-dash')
@section('title', 'Assistant Chats')

@section('content')
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mb-6">{{ session('success') }}</x-admin.alert>
    @endif

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-content">Assistant Chats</h1>
            <p class="text-sm text-content-muted mt-1">Live conversations handed off to a human agent.</p>
        </div>
    </div>

    {{-- Status filter --}}
    <div class="bg-surface-card rounded-xl border border-border shadow-card p-4 mb-6">
        @php $cs = request('status'); @endphp
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.assistant.index') }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ !$cs ? 'bg-primary text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">Needs reply</a>
            <a href="{{ route('admin.assistant.index', ['status' => 'pending']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $cs === 'pending' ? 'bg-warning text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">Pending</a>
            <a href="{{ route('admin.assistant.index', ['status' => 'answered']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $cs === 'answered' ? 'bg-success text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">Answered</a>
            <a href="{{ route('admin.assistant.index', ['status' => 'bot']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $cs === 'bot' ? 'bg-primary text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">AI only</a>
            <a href="{{ route('admin.assistant.index', ['status' => 'closed']) }}" class="px-3 py-1.5 rounded-lg text-xs font-medium {{ $cs === 'closed' ? 'bg-content-secondary text-white' : 'bg-surface-alt text-content-secondary hover:bg-surface-alt/80' }}">Closed</a>
        </div>
    </div>

    <div class="bg-surface-card rounded-xl border border-border shadow-card overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-border">
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Customer</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Type</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Messages</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Last activity</th>
                    <th class="text-right px-5 py-3 text-xs font-medium text-content-muted uppercase tracking-wide">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($conversations as $c)
                    <tr class="border-b border-border/60 hover:bg-surface-alt/40">
                        <td class="px-5 py-3">
                            <div class="font-medium text-content">{{ $c->display_name }}</div>
                            <div class="text-xs text-content-muted">{{ $c->user ? $c->user->email : ($c->guest_email ?: '—') }}</div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs {{ $c->user_id ? 'text-primary' : 'text-content-secondary' }}">{{ $c->user_id ? 'Member' : 'Guest' }}</span>
                        </td>
                        <td class="px-5 py-3">
                            @php
                                $map = ['pending' => 'bg-warning/15 text-warning', 'answered' => 'bg-success/15 text-success', 'bot' => 'bg-primary/15 text-primary', 'closed' => 'bg-content-secondary/15 text-content-secondary'];
                            @endphp
                            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $map[$c->status] ?? 'bg-surface-alt' }}">{{ ucfirst($c->status) }}</span>
                        </td>
                        <td class="px-5 py-3 text-content-secondary">{{ $c->messages_count }}</td>
                        <td class="px-5 py-3 text-content-muted text-xs">{{ optional($c->last_message_at)->diffForHumans() ?? '—' }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('admin.assistant.show', $c->id) }}" class="px-3 py-1.5 rounded-lg bg-primary hover:bg-primary-hover text-white text-xs font-medium">Open chat</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-10 text-center text-content-muted">No conversations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $conversations->links() }}</div>
@endsection
