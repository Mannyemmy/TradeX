@extends('layouts.dash1')
@section('title', $title)
@section('content')

    {{-- Alerts --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Page Header --}}
    @include('user.partials.page-header', ['title' => 'Notifications', 'subtitle' => 'Stay updated on all your account activity'])

    {{-- Actions Bar --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-content-secondary">
            <span class="font-medium text-content-primary">{{ $unreadCount }}</span> unread notification{{ $unreadCount !== 1 ? 's' : '' }}
        </p>
        @if($unreadCount > 0)
        <button type="button" onclick="markAllRead()" class="text-sm text-primary hover:text-primary transition-colors font-medium">
            Mark all as read
        </button>
        @endif
    </div>

    {{-- Notifications List --}}
    <div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden divide-y divide-surface-border">
        @forelse($notifications as $notification)
            <div id="notif-{{ $notification->id }}" class="flex items-start gap-4 px-5 py-4 transition-colors {{ $notification->read_at ? '' : 'bg-primary-subtle/30' }}">
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-full {{ $notification->read_at ? 'bg-surface-overlay' : 'bg-primary-subtle' }} flex items-center justify-center shrink-0 mt-0.5">
                    @php $icon = $notification->data['icon'] ?? 'bell'; @endphp
                    <x-icon :name="$icon" class="w-5 h-5 {{ $notification->read_at ? 'text-content-tertiary' : 'text-primary' }}" />
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-medium {{ $notification->read_at ? 'text-content-secondary' : 'text-content-primary' }} truncate">
                                {{ $notification->data['title'] ?? 'Notification' }}
                            </p>
                            <p class="text-sm text-content-tertiary mt-0.5 leading-relaxed">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <p class="text-xs text-content-tertiary mt-1.5">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-1 shrink-0">
                            @if(!$notification->read_at)
                                <button onclick="markRead('{{ $notification->id }}')" title="Mark as read"
                                        class="p-1.5 text-content-tertiary hover:text-primary rounded-lg hover:bg-surface-overlay transition-colors">
                                    <x-icon name="check-circle" class="w-4 h-4" />
                                </button>
                            @endif
                            <button onclick="deleteNotif('{{ $notification->id }}')" title="Delete"
                                    class="p-1.5 text-content-tertiary hover:text-loss rounded-lg hover:bg-surface-overlay transition-colors">
                                <x-icon name="x-mark" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    @if(!empty($notification->data['action_url']))
                        <a href="{{ $notification->data['action_url'] }}" class="inline-flex items-center gap-1 text-xs text-primary hover:text-primary mt-2 font-medium transition-colors">
                            View details <x-icon name="chevron-right" class="w-3 h-3" />
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-16">
                <x-icon name="bell" class="w-12 h-12 text-content-tertiary mx-auto mb-3" />
                <p class="text-content-secondary font-medium">No notifications yet</p>
                <p class="text-sm text-content-tertiary mt-1">You'll be notified about important account activity here.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="flex justify-center">
            {{ $notifications->links() }}
        </div>
    @endif

@endsection

@section('scripts')
<script>
    const csrfToken = '{{ csrf_token() }}';
    const headers = { 'X-CSRF-TOKEN': csrfToken, 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' };

    function markRead(id) {
        fetch(`/dashboard/notifications/${id}/read`, { method: 'POST', headers })
            .then(r => r.json())
            .then(() => {
                const el = document.getElementById('notif-' + id);
                if (el) {
                    el.classList.remove('bg-primary-subtle/30');
                    const btn = el.querySelector('button[title="Mark as read"]');
                    if (btn) btn.remove();
                }
            });
    }

    function markAllRead() {
        fetch('{{ route("notifications.readAll") }}', { method: 'POST', headers })
            .then(r => r.json())
            .then(() => location.reload());
    }

    function deleteNotif(id) {
        fetch(`/dashboard/notifications/${id}`, { method: 'DELETE', headers })
            .then(r => r.json())
            .then(() => {
                const el = document.getElementById('notif-' + id);
                if (el) el.remove();
            });
    }
</script>
@endsection
