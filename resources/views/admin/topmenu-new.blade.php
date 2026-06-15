{{-- Admin Top Bar — Tailwind + Alpine.js --}}
<header class="fixed top-0 right-0 z-30 h-16 bg-header border-b border-border transition-all duration-200 lg:left-64 left-0">
    <div class="flex items-center justify-between h-full px-4 lg:px-6">
        {{-- Left: hamburger + breadcrumb --}}
        <div class="flex items-center gap-3">
            <button @click="mobileSidebar = !mobileSidebar" class="lg:hidden text-content-muted hover:text-content p-1">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
            </button>
            <h1 class="text-lg font-semibold text-content hidden sm:block">@yield('title', 'Dashboard')</h1>
        </div>

        {{-- Right: dark mode toggle + notifications + user dropdown --}}
        <div class="flex items-center gap-2">
            {{-- Dark Mode Toggle --}}
            <button @click="darkMode = !darkMode; localStorage.setItem('admin-dark-mode', darkMode); document.documentElement.classList.toggle('dark', darkMode)"
                    class="p-2 text-content-muted hover:text-content rounded-lg hover:bg-surface-alt transition-colors">
                <svg x-show="!darkMode" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" /></svg>
                <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" /></svg>
            </button>

            {{-- Notification Bell --}}
            <div class="relative"
                 x-data="{
                     notifs: [],
                     count: {{ $adminUnreadNotifCount }},
                     loaded: false,
                     loadNotifs() {
                         if (this.loaded) return;
                         fetch('{{ route('admin.notifications.unread') }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                             .then(r => r.json())
                             .then(d => { this.notifs = d.notifications; this.count = d.count; this.loaded = true; });
                     },
                     markAllRead() {
                         fetch('{{ route('admin.notifications.readAll') }}', {
                             method: 'POST',
                             headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }
                         }).then(() => { this.notifs = []; this.count = 0; });
                     }
                 }"
                 @click.away="notifDropdown = false">
                <button @click="notifDropdown = !notifDropdown; loadNotifs()"
                        class="relative p-2 text-content-muted hover:text-content rounded-lg hover:bg-surface-alt transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" /></svg>
                    <span x-show="count > 0" x-cloak
                          class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] flex items-center justify-center bg-danger text-white text-[10px] font-bold rounded-full px-1"
                          x-text="count > 99 ? '99+' : count"></span>
                </button>

                {{-- Dropdown Panel --}}
                <div x-show="notifDropdown" x-cloak x-transition
                     class="absolute right-0 mt-2 w-80 bg-surface-card border border-border rounded-xl shadow-xl z-50 overflow-hidden">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3 border-b border-border">
                        <h4 class="text-sm font-semibold text-content">Notifications</h4>
                        <button x-show="count > 0" @click="markAllRead()"
                                class="text-xs text-primary hover:text-primary-hover transition-colors">Mark all read</button>
                    </div>

                    {{-- List --}}
                    <div class="max-h-80 overflow-y-auto divide-y divide-border">
                        <template x-if="notifs.length === 0">
                            <p class="text-sm text-content-muted text-center py-6">No new notifications</p>
                        </template>
                        <template x-for="n in notifs" :key="n.id">
                            <a :href="n.action_url || '#'"
                               @click="fetch('{{ url('admin/notifications') }}/' + n.id + '/read', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' } })"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-surface-alt transition-colors">
                                {{-- Type badge dot --}}
                                <div class="w-2 h-2 rounded-full mt-2 shrink-0"
                                     :class="{
                                        'bg-success': ['deposit','investment','registration'].includes(n.type),
                                        'bg-danger':  ['withdrawal','trade'].includes(n.type),
                                        'bg-warning': ['kyc','loan','support'].includes(n.type),
                                        'bg-info':    !['deposit','investment','registration','withdrawal','trade','kyc','loan','support'].includes(n.type),
                                     }"></div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-content truncate" x-text="n.title"></p>
                                    <p class="text-xs text-content-muted mt-0.5 line-clamp-2" x-text="n.message"></p>
                                    <p class="text-[10px] text-content-muted mt-1" x-text="n.time"></p>
                                </div>
                            </a>
                        </template>
                    </div>

                    {{-- Footer --}}
                    <a href="{{ route('admin.notifications') }}"
                       class="block text-center text-xs text-primary hover:text-primary-hover py-3 border-t border-border transition-colors">
                        View all notifications
                    </a>
                </div>
            </div>

            {{-- User Dropdown --}}
            <x-admin.dropdown align="right" width="w-48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-surface-alt transition-colors">
                        <div class="w-8 h-8 rounded-full bg-primary text-primary-foreground text-xs font-medium flex items-center justify-center">
                            {{ strtoupper(substr(Auth('admin')->User()->firstName, 0, 1)) }}{{ strtoupper(substr(Auth('admin')->User()->lastName, 0, 1)) }}
                        </div>
                        <span class="text-sm font-medium text-content hidden md:block">{{ Auth('admin')->User()->firstName }}</span>
                        <svg class="w-4 h-4 text-content-muted hidden md:block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                </x-slot>

                <a href="{{ url('admin/dashboard/adminprofile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-alt hover:text-content transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    Account Settings
                </a>
                <a href="{{ url('admin/dashboard/adminchangepassword') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-content-secondary hover:bg-surface-alt hover:text-content transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" /></svg>
                    Change Password
                </a>
                <div class="border-t border-border my-1"></div>
                <a href="{{ route('adminlogout') }}"
                   onclick="event.preventDefault(); document.getElementById('admin-sidebar-logout-form').submit();"
                   class="flex items-center gap-2 px-4 py-2.5 text-sm text-danger hover:bg-surface-alt transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
                    Logout
                </a>
            </x-admin.dropdown>
        </div>
    </div>
</header>
