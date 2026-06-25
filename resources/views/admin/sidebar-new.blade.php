{{-- Admin Sidebar — Tailwind + Alpine.js --}}
<aside
    :class="mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed top-0 left-0 z-50 h-full w-64 bg-sidebar border-r border-sidebar-border shadow-sm flex flex-col transition-transform duration-200 ease-in-out overflow-hidden"
>
    {{-- Logo --}}
    <div class="flex items-center justify-between h-16 px-5 border-b border-sidebar-border shrink-0">
        <a href="{{ url('/admin/dashboard') }}" class="text-base font-semibold text-primary truncate">
            {{ $settings->site_name }}
        </a>
        <button @click="mobileSidebar = false" class="lg:hidden text-sidebar-muted hover:text-primary">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
    </div>

    {{-- Admin Info --}}
    <div class="px-4 py-4 border-b border-sidebar-border shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-full bg-primary text-primary-foreground text-xs font-medium flex items-center justify-center shrink-0 shadow-sm shadow-primary/20">
                {{ strtoupper(substr(Auth('admin')->User()->firstName, 0, 1)) }}{{ strtoupper(substr(Auth('admin')->User()->lastName, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-sm font-medium text-sidebar-text truncate">{{ Auth('admin')->User()->firstName }} {{ Auth('admin')->User()->lastName }}</p>
                <p class="text-xs text-sidebar-muted">{{ Auth('admin')->User()->type }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-3">

        {{-- MAIN --}}
        <p class="px-6 pt-4 pb-2 text-[0.65rem] font-semibold text-sidebar-muted uppercase tracking-widest">Main</p>

        <x-admin.sidebar-item
            href="{{ url('/admin/dashboard') }}"
            :active="request()->routeIs('admin.dashboard')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>'
        >
            Dashboard
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('manageusers') }}"
            :active="request()->routeIs('manageusers') || request()->routeIs('loginactivity') || request()->routeIs('user.plans') || request()->routeIs('viewuser')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>'
        >
            Manage Users
        </x-admin.sidebar-item>

        @if (Auth('admin')->User()->type == 'Super Admin' || Auth('admin')->User()->type == 'Admin')

        {{-- FINANCE --}}
        <p class="px-6 pt-6 pb-2 text-[0.65rem] font-semibold text-sidebar-muted uppercase tracking-widest">Finance</p>

        <x-admin.sidebar-group
            label="Investment"
            :active="request()->routeIs('plans') || request()->routeIs('newplan') || request()->routeIs('editplan') || request()->routeIs('activeinvestments')"
            id="investment"
            :badge="empty($mod['investment']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>'
        >
            <x-admin.sidebar-item href="{{ url('/admin/dashboard/plans') }}" :active="request()->routeIs('plans')" icon="">
                Investment Plans
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ url('/admin/dashboard/active-investments') }}" :active="request()->routeIs('activeinvestments')" icon="">
                Active Investments
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        <x-admin.sidebar-item
            href="{{ url('/admin/dashboard/mdeposits') }}"
            :active="request()->routeIs('mdeposits') || request()->routeIs('viewdepositimage')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>'
        >
            Manage Deposits
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ url('/admin/dashboard/mwithdrawals') }}"
            :active="request()->routeIs('mwithdrawals') || request()->routeIs('processwithdraw')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>'
        >
            Manage Withdrawals
        </x-admin.sidebar-item>

        <x-admin.sidebar-group
            label="Wallet Connect"
            :active="request()->routeIs('mwalletconnect') || request()->routeIs('mwalletsettings')"
            id="wallet-connect"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('mwalletconnect') }}" :active="request()->routeIs('mwalletconnect')" icon="">
                Connected Wallets
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('mwalletsettings') }}" :active="request()->routeIs('mwalletsettings')" icon="">
                Wallet Settings
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        {{-- TRADING --}}
        <p class="px-6 pt-6 pb-2 text-[0.65rem] font-semibold text-sidebar-muted uppercase tracking-widest">Trading</p>

        <x-admin.sidebar-group
            label="Trades"
            :active="request()->routeIs('admin.trades.*') || request()->routeIs('admin.assets.*')"
            id="trades"
            :badge="empty($mod['trading']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('admin.trades.create') }}" :active="request()->routeIs('admin.trades.create')" icon="">
                Create Trade for Clients
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.trades.index') }}" :active="request()->routeIs('admin.trades.index')" icon="">
                Clients Trades
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.assets.index') }}" :active="request()->routeIs('admin.assets.index')" icon="">
                Trading Assets
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        <x-admin.sidebar-group
            label="Signals"
            :active="request()->routeIs('signal.index') || request()->routeIs('signal-plans.*')"
            id="signals"
            :badge="empty($mod['signal']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.348 14.651a3.75 3.75 0 010-5.303m5.304 0a3.75 3.75 0 010 5.303m-7.425 2.122a6.75 6.75 0 010-9.546m9.546 0a6.75 6.75 0 010 9.546M5.106 18.894c-3.808-3.808-3.808-9.98 0-13.789m13.788 0c3.808 3.808 3.808 9.981 0 13.79M12 12h.008v.007H12V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('signal-plans.index') }}" :active="request()->routeIs('signal-plans.*')" icon="">
                Signal Plans
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('signal.index') }}" :active="request()->routeIs('signal.index')" icon="">
                Signals
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        <x-admin.sidebar-group label="Copy Trading" :open="request()->routeIs('admin.experts.*') || request()->routeIs('admin.copy-trades.*')"
            :badge="empty($mod['copy_trading']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('admin.experts.index') }}" :active="request()->routeIs('admin.experts.*')" icon="">
                Experts
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.copy-trades.index') }}" :active="request()->routeIs('admin.copy-trades.*')" icon="">
                Copy Trades
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        <x-admin.sidebar-group label="Bot Trading" :open="request()->routeIs('admin.bot-trading.*')"
            :badge="empty($mod['bot_trading']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25zm.75-12h9v9h-9v-9z" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('admin.bot-trading.index') }}" :active="request()->routeIs('admin.bot-trading.index') || request()->routeIs('admin.bot-trading.create') || request()->routeIs('admin.bot-trading.edit')" icon="">
                Bots
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.bot-trading.subscriptions') }}" :active="request()->routeIs('admin.bot-trading.subscriptions') || request()->routeIs('admin.bot-trading.subscription')" icon="">
                Subscriptions
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        {{-- PRODUCTS --}}
        <p class="px-6 pt-6 pb-2 text-[0.65rem] font-semibold text-sidebar-muted uppercase tracking-widest">Products</p>

        <x-admin.sidebar-group
            label="NFTs"
            :active="request()->routeIs('admin.nfts.*') || request()->routeIs('admin.nft.*') || request()->routeIs('admin.bids.*')"
            :badge="empty($mod['nft']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 109.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1114.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('admin.nfts.index') }}" :active="request()->routeIs('admin.nfts.index')" icon="">
                All NFTs
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.nft.categories.index') }}" :active="request()->routeIs('admin.nft.categories.*')" icon="">
                Categories
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.nft.collections.index') }}" :active="request()->routeIs('admin.nft.collections.*')" icon="">
                Collections
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.nfts.sold') }}" :active="request()->routeIs('admin.nfts.sold')" icon="">
                Sold
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.bids.index') }}" :active="request()->routeIs('admin.bids.*')" icon="">
                Bids
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('admin.nfts.transfers') }}" :active="request()->routeIs('admin.nfts.transfers')" icon="">
                Transfers
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        <x-admin.sidebar-group
            label="Membership"
            :active="request()->routeIs('courses') || request()->routeIs('lessons') || request()->routeIs('categories') || request()->routeIs('less.nocourse')"
            :badge="empty($mod['membership']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('courses') }}" :active="request()->routeIs('courses')" icon="">
                Courses
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('categories') }}" :active="request()->routeIs('categories')" icon="">
                Categories
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('less.nocourse') }}" :active="request()->routeIs('less.nocourse')" icon="">
                Standalone Lessons
            </x-admin.sidebar-item>
        </x-admin.sidebar-group>

        <x-admin.sidebar-item
            href="{{ route('admin.loans.index') }}"
            :active="request()->routeIs('admin.loans.*') || request()->routeIs('admin.loan-plans.*')"
            :badge="empty($mod['loan']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>'
        >
            Manage Loans
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('admin.pre-ipo.index') }}"
            :active="request()->routeIs('admin.pre-ipo.*')"
            :badge="empty($mod['pre_ipo']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5M2.25 9l4.5-1.636M18.75 3l-1.5.545m0 6.205l3 1m1.5.5l-1.5-.5M6.75 7.364V3h-3v18m3-13.636l10.5-3.819" /></svg>'
        >
            Pre-IPO Shares
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('admin.stocks.index') }}"
            :active="request()->routeIs('admin.stocks.*')"
            :badge="empty($mod['stocktrading']) ? 'OFF' : null"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" /></svg>'
        >
            Stock Shares
        </x-admin.sidebar-item>

        {{-- MANAGEMENT --}}
        <p class="px-6 pt-6 pb-2 text-[0.65rem] font-semibold text-sidebar-muted uppercase tracking-widest">Management</p>

        <x-admin.sidebar-item
            href="{{ route('emailservices') }}"
            :active="request()->routeIs('emailservices')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>'
        >
            Email Services
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('kyc') }}"
            :active="request()->routeIs('kyc') || request()->routeIs('viewkyc')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>'
        >
            KYC Applications
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('admin.support.index') }}"
            :active="request()->routeIs('admin.support.*')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" /></svg>'
        >
            Support Tickets
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('admin.assistant.index') }}"
            :active="request()->routeIs('admin.assistant.*')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 01-4-.8L3 20l1.3-3.9A7.96 7.96 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>'
        >
            Assistant Chats
        </x-admin.sidebar-item>

        <x-admin.sidebar-item
            href="{{ route('admin.assistant.settings') }}"
            :active="request()->routeIs('admin.assistant.settings')"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>'
        >
            Assistant Knowledge
        </x-admin.sidebar-item>

        {{-- <x-admin.sidebar-group
            label="Manage Accounts"
            :active="request()->routeIs('msubtrade') || request()->routeIs('subview')"
            id="accounts"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182M4.031 9.865l13.803-3.7" /></svg>'
        >
            <x-admin.sidebar-item href="{{ route('msubtrade') }}" :active="request()->routeIs('msubtrade')" icon="">
                Trading Accounts
            </x-admin.sidebar-item>
            <x-admin.sidebar-item href="{{ route('subview') }}" :active="request()->routeIs('subview')" icon="">
                Fee Settings
            </x-admin.sidebar-item>
        </x-admin.sidebar-group> --}}

        @endif

        {{-- TASKS --}}
        {{-- <x-admin.sidebar-group
            label="Tasks"
            :active="request()->routeIs('task') || request()->routeIs('mtask') || request()->routeIs('viewtask')"
            id="tasks"
            icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15a2.25 2.25 0 012.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z" /></svg>'
        >
            @if (Auth('admin')->User()->type == 'Super Admin')
                <x-admin.sidebar-item href="{{ url('/admin/dashboard/task') }}" :active="request()->routeIs('task')" icon="">
                    Create Task
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ url('/admin/dashboard/mtask') }}" :active="request()->routeIs('mtask')" icon="">
                    Manage Tasks
                </x-admin.sidebar-item>
            @endif
            @if (Auth('admin')->User()->type != 'Super Admin')
                <x-admin.sidebar-item href="{{ url('/admin/dashboard/viewtask') }}" :active="request()->routeIs('viewtask')" icon="">
                    View My Tasks
                </x-admin.sidebar-item>
            @endif
        </x-admin.sidebar-group> --}}

        {{-- @if (Auth('admin')->User()->type == 'Super Admin' || Auth('admin')->User()->type == 'Admin')
            <x-admin.sidebar-item
                href="{{ url('/admin/dashboard/leads') }}"
                :active="request()->routeIs('leads')"
                icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>'
            >
                Leads
            </x-admin.sidebar-item>
        @endif --}}

        @if (Auth('admin')->User()->type == 'Rentention Agent' || Auth('admin')->User()->type == 'Conversion Agent')
            <x-admin.sidebar-item
                href="{{ url('/admin/dashboard/leadsassign') }}"
                :active="request()->routeIs('leadsassign')"
                icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>'
            >
                My Leads
            </x-admin.sidebar-item>
        @endif

        @if (Auth('admin')->User()->type == 'Super Admin')
            {{-- SYSTEM --}}
            <p class="px-6 pt-6 pb-2 text-[0.65rem] font-semibold text-sidebar-muted uppercase tracking-widest">System</p>

            <x-admin.sidebar-group
                label="Administrators"
                :active="request()->routeIs('addmanager') || request()->routeIs('madmin')"
                id="admins"
                icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>'
            >
                <x-admin.sidebar-item href="{{ url('/admin/dashboard/addmanager') }}" :active="request()->routeIs('addmanager')" icon="">
                    Add Manager
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ url('/admin/dashboard/madmin') }}" :active="request()->routeIs('madmin')" icon="">
                    Manage Admins
                </x-admin.sidebar-item>
            </x-admin.sidebar-group>

            <x-admin.sidebar-group
                label="Settings"
                :active="request()->routeIs('appsettingshow') || request()->routeIs('termspolicy') || request()->routeIs('refsetshow') || request()->routeIs('paymentview') || request()->routeIs('frontpage') || request()->routeIs('allipaddress') || request()->routeIs('ipaddress') || request()->routeIs('editpaymethod') || request()->routeIs('managecryptoasset') || request()->routeIs('admin.color-settings') || request()->routeIs('admin.exchange-rates.*')"
                id="settings"
                icon='<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>'
            >
                <x-admin.sidebar-item href="{{ route('appsettingshow') }}" :active="request()->routeIs('appsettingshow')" icon="">
                    App Settings
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ route('refsetshow') }}" :active="request()->routeIs('refsetshow')" icon="">
                    Referral/Bonus
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ route('paymentview') }}" :active="request()->routeIs('paymentview')" icon="">
                    Payment Settings
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ route('managecryptoasset') }}" :active="request()->routeIs('managecryptoasset')" icon="">
                    Swap Settings
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ url('/admin/dashboard/ipaddress') }}" :active="request()->routeIs('ipaddress')" icon="">
                    IP Address
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ route('admin.color-settings') }}" :active="request()->routeIs('admin.color-settings')" icon="">
                    Color Settings
                </x-admin.sidebar-item>
                <x-admin.sidebar-item href="{{ route('admin.exchange-rates.index') }}" :active="request()->routeIs('admin.exchange-rates.*')" icon="">
                    Exchange Rates
                </x-admin.sidebar-item>
            </x-admin.sidebar-group>
        @endif



    </nav>

    {{-- Logout --}}
    <div class="border-t border-sidebar-border p-4 shrink-0">
        <form method="POST" action="{{ route('adminlogout') }}" id="admin-sidebar-logout-form">
            @csrf
        </form>
        <a href="{{ route('adminlogout') }}"
           onclick="event.preventDefault(); document.getElementById('admin-sidebar-logout-form').submit();"
           class="mx-0 flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-danger hover:bg-danger/10 transition-colors cursor-pointer">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" /></svg>
            Logout
        </a>
    </div>
</aside>
