@extends('layouts.admin-dash')
@section('title', 'Managers List')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Managers List" subtitle="View and manage all admin and agent accounts.">
        <x-slot name="actions">
            <a href="{{ route('addmanager') }}"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Manager
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    {{-- Managers Table --}}
    <div class="mt-6">
        <x-admin.table-card title="Managers">
            <table id="ShipTable" class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">ID</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">First Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Last Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Email</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Phone</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Type</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admins as $admin)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $admin->id }}</td>
                            <td class="px-4 py-3.5 text-sm text-content">{{ $admin->firstName }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $admin->lastName }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $admin->email }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $admin->phone }}</td>
                            <td class="px-4 py-3.5">
                                <x-admin.badge type="neutral">{{ $admin->type }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5">
                                @if ($admin->acnt_type_active == 'blocked' || $admin->acnt_type_active == null)
                                    <x-admin.badge type="danger">{{ $admin->acnt_type_active ?? 'blocked' }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="success">{{ $admin->acnt_type_active }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                {{-- Just the trigger button — menu is rendered outside the table via @push --}}
                                <button onclick="openActionMenu(this, 'action-menu-{{ $admin->id }}')"
                                    class="bg-surface-alt text-content-secondary border border-border hover:bg-border rounded-lg px-3 py-1.5 text-xs font-medium transition-colors inline-flex items-center gap-1">
                                    Actions
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                </button>
                            </td>
                        </tr>

                        {{-- Reset Password Modal --}}
                        <x-admin.modal id="resetpswdmodal{{ $admin->id }}" title="Reset Password">
                            <p class="text-sm text-content-secondary mb-4">
                                Are you sure you want to reset password for <strong class="text-content">{{ $admin->firstName }}</strong>
                                to <span class="text-primary font-semibold">admin01236</span>?
                            </p>
                            <a href="{{ url('admin/dashboard/resetadpwd') }}/{{ $admin->id }}"
                                class="bg-danger text-white hover:bg-danger/80 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                Reset Now
                            </a>
                        </x-admin.modal>

                        {{-- Delete Modal --}}
                        <x-admin.modal id="deletemodal{{ $admin->id }}" title="Delete Manager">
                            <p class="text-sm text-content-secondary mb-4">
                                Are you sure you want to delete <strong class="text-content">{{ $admin->firstName }}</strong>?
                            </p>
                            <a href="{{ url('admin/dashboard/deleletadmin') }}/{{ $admin->id }}"
                                class="bg-danger text-white hover:bg-danger/80 rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                Yes, I'm sure
                            </a>
                        </x-admin.modal>

                        {{-- Edit Modal --}}
                        <x-admin.modal id="edituser{{ $admin->id }}" title="Edit User Details" maxWidth="max-w-xl">
                            <form method="post" action="{{ route('editadmin') }}">
                                @csrf
                                <div class="space-y-4">
                                    <x-admin.form-group label="First Name" :required="true">
                                        <input type="text" name="fname" value="{{ $admin->firstName }}" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Last Name" :required="true">
                                        <input type="text" name="l_name" value="{{ $admin->lastName }}" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Email" :required="true">
                                        <input type="email" name="email" value="{{ $admin->email }}" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Phone Number" :required="true">
                                        <input type="text" name="phone" value="{{ $admin->phone }}" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Type" :required="true">
                                        <select name="type"
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                            <option>{{ $admin->type }}</option>
                                            <option>Super Admin</option>
                                            <option>Admin</option>
                                            <option>Conversion Agent</option>
                                        </select>
                                    </x-admin.form-group>
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                    <button type="submit"
                                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                        Update Account
                                    </button>
                                </div>
                            </form>
                        </x-admin.modal>

                        {{-- Send Email Modal --}}
                        <x-admin.modal id="sendmailmodal{{ $admin->id }}" title="Send Email Message" maxWidth="max-w-lg">
                            <p class="text-sm text-content-secondary mb-4">
                                This message will be sent to <strong class="text-content">{{ $admin->firstName }} {{ $admin->lastName }}</strong>
                            </p>
                            <form method="post" action="{{ route('sendmailtoadmin') }}">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $admin->id }}">
                                <div class="space-y-4">
                                    <x-admin.form-group label="Email Subject">
                                        <input type="text" name="subject" placeholder="Enter Email Subject"
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Message" :required="true">
                                        <textarea name="message" rows="3" required placeholder="Type your message here"
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"></textarea>
                                    </x-admin.form-group>
                                    <button type="submit"
                                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                        Send
                                    </button>
                                </div>
                            </form>
                        </x-admin.modal>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection

{{-- ============================================================ --}}
{{-- Action menus: rendered at body level via @push, NOT in table  --}}
{{-- ============================================================ --}}
@push('scripts')
{{-- Action menu containers — these live in <body>, outside all tables/cards --}}
@foreach ($admins as $admin)
<div id="action-menu-{{ $admin->id }}" class="action-floating-menu" style="display:none; position:fixed; z-index:9999;">
    <div class="w-48 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg py-1">
        @if ($admin->acnt_type_active == null || $admin->acnt_type_active == 'blocked')
            <a href="{{ url('admin/dashboard/unblock') }}/{{ $admin->id }}"
                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                Unblock
            </a>
        @else
            <a href="{{ url('admin/dashboard/ublock') }}/{{ $admin->id }}"
                class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                Block
            </a>
        @endif
        <button onclick="closeAllMenus(); window.dispatchEvent(new CustomEvent('open-resetpswdmodal{{ $admin->id }}'))"
            class="w-full text-left block px-4 py-2 text-sm text-amber-600 dark:text-amber-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            Reset Password
        </button>
        <button onclick="closeAllMenus(); window.dispatchEvent(new CustomEvent('open-deletemodal{{ $admin->id }}'))"
            class="w-full text-left block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            Delete
        </button>
        <button onclick="closeAllMenus(); window.dispatchEvent(new CustomEvent('open-edituser{{ $admin->id }}'))"
            class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            Edit
        </button>
        <button onclick="closeAllMenus(); window.dispatchEvent(new CustomEvent('open-sendmailmodal{{ $admin->id }}'))"
            class="w-full text-left block px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
            Send Email
        </button>
    </div>
</div>
@endforeach

<script>
function closeAllMenus() {
    document.querySelectorAll('.action-floating-menu').forEach(function(m) {
        m.style.display = 'none';
    });
}
function openActionMenu(triggerBtn, menuId) {
    var menu = document.getElementById(menuId);
    var wasOpen = menu.style.display !== 'none';
    closeAllMenus();
    if (wasOpen) return;
    var rect = triggerBtn.getBoundingClientRect();
    var top = rect.bottom + 4;
    var left = rect.right - 192; // 192px = w-48
    // Flip up if no room below
    if (top + 250 > window.innerHeight) {
        top = rect.top - 250;
    }
    if (left < 4) left = 4;
    menu.style.top = top + 'px';
    menu.style.left = left + 'px';
    menu.style.display = 'block';
}
// Close on click outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.action-floating-menu') && !e.target.closest('[onclick*="openActionMenu"]')) {
        closeAllMenus();
    }
});
// Close on scroll
document.addEventListener('scroll', function() { closeAllMenus(); }, true);
</script>
@endpush
