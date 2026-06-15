<div x-data="{ showAddUser: false, showTrading: false, showTopup: false }">
    {{-- Page Header --}}
    <x-admin.page-header title="{{ $settings->site_name }} Users" subtitle="Manage all registered users">
        <x-slot name="actions">
            @if ($checkrecord)
                <div class="flex items-center gap-2">
                    <select wire:model='action' class="bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="Delete">Delete</option>
                        <option value="Clear">Clear Account</option>
                    </select>
                    <button wire:click='delsystemuser' type="button" class="inline-flex items-center gap-1.5 bg-danger text-content-inverse hover:bg-danger/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                        Apply
                    </button>
                    <button @click="showTrading = true" type="button" class="inline-flex items-center gap-1.5 bg-info text-content-inverse hover:bg-info/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Add ROI
                    </button>
                    <button @click="showTopup = true" type="button" class="inline-flex items-center gap-1.5 bg-info text-content-inverse hover:bg-info/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Topup
                    </button>
                </div>
            @else
                <div class="flex items-center gap-2">
                    <a href="{{ route('emailservices') }}" class="inline-flex items-center gap-1.5 bg-surface-alt text-content hover:bg-surface-alt/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors border border-border">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                        Send Message
                    </a>
                    <button @click="showAddUser = true" type="button" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                        New User
                    </button>
                </div>
            @endif
        </x-slot>
    </x-admin.page-header>

    <div class="mt-6">
        {{-- Search & Filters --}}
        <div class="mb-4">
            <div class="relative max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-content-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input wire:model.debounce.500ms='searchvalue' type="search" placeholder="Search by name, username or email..."
                    class="w-full pl-10 pr-4 py-2 bg-surface-card border border-border rounded-lg text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" />
            </div>
        </div>

        {{-- Users Table --}}
        <x-admin.table-card>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-alt text-left">
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider whitespace-nowrap">
                            <input type="checkbox" wire:model='selectPage' class="rounded border-border text-primary focus:ring-primary/30" />
                        </th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Client Name</th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Username</th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Email</th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Phone</th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Status</th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Registered</th>
                        <th class="px-4 py-3 font-medium text-content-muted uppercase text-xs tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($users as $user)
                        <tr class="hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3 align-middle">
                                <input type="checkbox" wire:model='checkrecord' value="{{ $user->id }}" class="rounded border-border text-primary focus:ring-primary/30" />
                            </td>
                            <td class="px-4 py-3 text-content font-medium">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-content-secondary">{{ $user->username }}</td>
                            <td class="px-4 py-3 text-content-secondary">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-content-secondary">{{ $user->phone }}</td>
                            <td class="px-4 py-3">
                                @if ($user->status == 'active')
                                    <x-admin.badge type="success">{{ $user->status }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">{{ $user->status }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-content-secondary whitespace-nowrap">{{ $user->created_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('viewuser', $user->id) }}" class="inline-flex items-center gap-1.5 bg-secondary text-secondary-foreground hover:bg-secondary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                    Manage
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-content-muted">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-admin.table-card>

        {{-- Pagination & Controls --}}
        <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <select wire:model='pagenum' class="bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                    <option>100</option>
                    <option>200</option>
                </select>
                <select wire:model='orderby' class="bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="id">ID</option>
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="created_at">Sign up date</option>
                </select>
                <select wire:model='orderdirection' class="bg-surface-card border border-border rounded-lg px-3 py-1.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>
            <div>
                {!! $users->links() !!}
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ ADD USER MODAL ═══════════════════════ --}}
    <div x-show="showAddUser" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="showAddUser" x-transition.opacity class="absolute inset-0 bg-surface-overlay/60" @click="showAddUser = false"></div>
        <div x-show="showAddUser" x-transition class="relative w-full max-w-lg bg-surface-card rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-content">Add User</h3>
                <button @click="showAddUser = false" class="text-content-muted hover:text-content transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="px-6 py-5">
                <form method="POST" action="{{ route('createuser') }}">
                    @csrf
                    <x-admin.form-group label="Username" class="mb-4" required>
                        <input type="text" id="usernameinput" name="username" wire:model.defer='username'
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </x-admin.form-group>
                    <x-admin.form-group label="Fullname" class="mb-4" required>
                        <input type="text" name="name" wire:model.defer='fullname'
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </x-admin.form-group>
                    <x-admin.form-group label="Email" class="mb-4" required>
                        <input type="email" name="email" wire:model.defer='email'
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </x-admin.form-group>
                    <x-admin.form-group label="Password" class="mb-4" required>
                        <input type="text" name="password" wire:model.defer='password'
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </x-admin.form-group>
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Add User
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ ADD ROI MODAL ═══════════════════════ --}}
    <div x-show="showTrading" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="showTrading" x-transition.opacity class="absolute inset-0 bg-surface-overlay/60" @click="showTrading = false"></div>
        <div x-show="showTrading" x-transition class="relative w-full max-w-lg bg-surface-card rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-content">Add ROI to Selected Users</h3>
                <button @click="showTrading = false" class="text-content-muted hover:text-content transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="px-6 py-5">
                <form wire:submit.prevent='addRoi'>
                    <x-admin.form-group label="Select Investment Plan" class="mb-4" required>
                        <select wire:model.defer='plan' name="plan"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                            <option value="" selected disabled>Select Plan</option>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </x-admin.form-group>
                    <x-admin.form-group label="Date" class="mb-4" required>
                        <input type="date" wire:model.defer='datecreated'
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                    </x-admin.form-group>
                    <p class="text-xs text-content-muted mb-4">
                        The system will calculate the ROI based on users' invested amount and topup amount specified in this selected plan settings.
                        <strong class="text-content-secondary">The plan must be using % as its topup-type else the calculations will be wrong.</strong>
                    </p>
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Add History
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════ TOPUP MODAL ═══════════════════════ --}}
    <div x-show="showTopup" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="showTopup" x-transition.opacity class="absolute inset-0 bg-surface-overlay/60" @click="showTopup = false"></div>
        <div x-show="showTopup" x-transition class="relative w-full max-w-lg bg-surface-card rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-content">Credit/Debit Accounts</h3>
                <button @click="showTopup = false" class="text-content-muted hover:text-content transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="px-6 py-5">
                <form wire:submit.prevent='topup'>
                    <x-admin.form-group label="Amount" class="mb-4" required>
                        <input type="number" step="any" name="amount" wire:model.defer='topamount' placeholder="Enter amount"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                        @if($topamount)
                            <p class="text-xs text-content-muted mt-1">{{ $topamount }}</p>
                        @endif
                    </x-admin.form-group>
                    <x-admin.form-group label="Select where to Credit/Debit" class="mb-4" required>
                        <select wire:model.defer='topcolumn' name="type"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                            <option value="" selected disabled>Select Column</option>
                            <option value="Bonus">Bonus</option>
                            <option value="balance">Account Balance</option>
                        </select>
                    </x-admin.form-group>
                    <x-admin.form-group label="Select credit to add, debit to subtract" class="mb-4" required>
                        <select wire:model.defer='toptype' name="t_type"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" required>
                            <option value="">Select type</option>
                            <option value="Credit">Credit</option>
                            <option value="Debit">Debit</option>
                        </select>
                    </x-admin.form-group>
                    <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Save
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
