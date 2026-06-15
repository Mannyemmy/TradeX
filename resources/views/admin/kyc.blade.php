@extends('layouts.admin-dash')
@section('title', 'KYC Applications')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="{{ $settings->site_name }} KYC Applications" subtitle="Review and manage user KYC verification requests." />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    {{-- KYC List Table --}}
    <div class="mt-6">
        <x-admin.table-card title="KYC Applications">
            <table id="ShipTable" class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">User</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">KYC Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kycs as $list)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ optional($list->user)->name ?? 'Deleted User' }}</td>
                            <td class="px-4 py-3.5">
                                @if ($list->status == 'Verified')
                                    <x-admin.badge type="success">Verified</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">{{ $list->status }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('viewkyc', $list->id) }}"
                                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                    View Application
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection
