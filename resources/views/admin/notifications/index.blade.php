@extends('layouts.admin-dash')
@section('title', 'Notifications')

@section('content')

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-xl font-semibold text-content">Notifications</h2>
            <p class="text-sm text-content-muted mt-0.5">All user activity notifications</p>
        </div>
        @if ($unreadCount > 0)
            <button id="markAllReadBtn"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-primary-foreground text-sm font-medium rounded-lg hover:bg-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                Mark all read ({{ $unreadCount }})
            </button>
        @endif
    </div>

    {{-- Notifications Table --}}
    <div class="bg-surface-card rounded-xl border border-border shadow-card overflow-hidden">
        @if ($notifications->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <svg class="w-12 h-12 text-content-muted mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                <p class="text-sm font-medium text-content-muted">No notifications yet</p>
                <p class="text-xs text-content-muted mt-1">Activity from users will appear here</p>
            </div>
        @else
            <table class="w-full" id="notif-table">
                <thead>
                    <tr class="border-b border-border bg-surface-alt">
                        <th class="px-4 py-3 text-left text-xs font-medium text-content-muted uppercase tracking-wide w-8"></th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-content-muted uppercase tracking-wide">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-content-muted uppercase tracking-wide">Title &amp; Message</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-content-muted uppercase tracking-wide">Time</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-content-muted uppercase tracking-wide">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($notifications as $notif)
                        @php
                            $data    = $notif->data;
                            $type    = $data['type']       ?? 'general';
                            $title   = $data['title']      ?? '—';
                            $message = $data['message']    ?? '';
                            $url     = $data['action_url'] ?? null;
                            $isRead  = !is_null($notif->read_at);

                            $typeBadgeClass = match($type) {
                                'deposit'      => 'bg-success/10 text-success',
                                'withdrawal'   => 'bg-danger/10 text-danger',
                                'kyc'          => 'bg-warning/10 text-warning',
                                'registration' => 'bg-info/10 text-info',
                                'support'      => 'bg-primary/10 text-primary',
                                'loan'         => 'bg-warning/10 text-warning',
                                'trade'        => 'bg-danger/10 text-danger',
                                'investment'   => 'bg-success/10 text-success',
                                default        => 'bg-surface-alt text-content-muted',
                            };
                        @endphp
                        <tr class="{{ $isRead ? '' : 'bg-primary/5' }} hover:bg-surface-alt/50 transition-colors" data-id="{{ $notif->id }}">
                            {{-- Unread dot --}}
                            <td class="px-4 py-3.5">
                                @if (!$isRead)
                                    <span class="block w-2 h-2 rounded-full bg-primary mx-auto"></span>
                                @endif
                            </td>
                            {{-- Type badge --}}
                            <td class="px-4 py-3.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $typeBadgeClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </span>
                            </td>
                            {{-- Title + message --}}
                            <td class="px-4 py-3.5 max-w-xs">
                                @if ($url)
                                    <a href="{{ $url }}" class="text-sm font-medium text-content hover:text-primary transition-colors">{{ $title }}</a>
                                @else
                                    <p class="text-sm font-medium text-content">{{ $title }}</p>
                                @endif
                                <p class="text-xs text-content-muted mt-0.5">{{ $message }}</p>
                            </td>
                            {{-- Time --}}
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="text-xs text-content-muted" title="{{ $notif->created_at->format('d M Y H:i') }}">
                                    {{ $notif->created_at->diffForHumans() }}
                                </span>
                            </td>
                            {{-- Actions --}}
                            <td class="px-4 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if (!$isRead)
                                        <button onclick="markRead('{{ $notif->id }}')"
                                                class="text-xs text-primary hover:text-primary-hover transition-colors">
                                            Mark read
                                        </button>
                                    @endif
                                    <button onclick="deleteNotif('{{ $notif->id }}')"
                                            class="text-xs text-danger/70 hover:text-danger transition-colors">
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($notifications->hasPages())
                <div class="px-4 py-4 border-t border-border">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection

@push('scripts')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function markRead(id) {
    fetch(`/admin/notifications/${id}/read`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) {
            row.classList.remove('bg-primary/5');
            const dot = row.querySelector('span.bg-primary.rounded-full');
            if (dot) dot.remove();
            const btn = row.querySelector(`button[onclick="markRead('${id}')"]`);
            if (btn) btn.remove();
        }
    });
}

function deleteNotif(id) {
    if (!confirm('Delete this notification?')) return;
    fetch(`/admin/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
    }).then(() => {
        const row = document.querySelector(`tr[data-id="${id}"]`);
        if (row) row.remove();
    });
}

const markAllBtn = document.getElementById('markAllReadBtn');
if (markAllBtn) {
    markAllBtn.addEventListener('click', function () {
        fetch('/admin/notifications/read-all', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest' }
        }).then(() => location.reload());
    });
}
</script>
@endpush
