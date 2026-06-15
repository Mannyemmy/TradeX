@extends('layouts.admin-dash')
@section('title', 'Assigned Members')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="New Members Assigned to Me" subtitle="Users assigned to you for conversion follow-up." />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    {{-- Assigned Members Table --}}
    <div class="mt-6">
        <x-admin.table-card title="Assigned Members">
            <table id="ShipTable" class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">ID</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Balance</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">First Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Last Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Email</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Phone</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Inv. Plan</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Date Registered</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Assigned To</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersAssigned as $list)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $list->id }}</td>
                            <td class="px-4 py-3.5 text-sm text-content">${{ $list->account_bal }}</td>
                            <td class="px-4 py-3.5 text-sm text-content">{{ $list->name }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->l_name }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->email }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->phone_number }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">
                                {{ isset($list->dplan->name) ? $list->dplan->name : 'NULL' }}
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->status }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-muted">{{ \Carbon\Carbon::parse($list->created_at)->toDayDateTimeString() }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->tuser->firstName }} {{ $list->tuser->lastName }}</td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    @if ($list->cstatus == 'Customer')
                                        <x-admin.badge type="success">Converted</x-admin.badge>
                                    @else
                                        <a href="{{ url('admin/dashboard/convert') }}/{{ $list->id }}"
                                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Convert
                                        </a>
                                    @endif
                                    <button @click="$dispatch('open-editmodal{{ $list->id }}')"
                                        class="bg-info-light text-info hover:bg-info hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                        Edit Status
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Status Modal --}}
                        <x-admin.modal id="editmodal{{ $list->id }}" title="Edit User Status">
                            <form method="post" action="{{ route('updateuser') }}">
                                @csrf
                                <x-admin.form-group label="User Status" :required="true">
                                    <textarea name="userupdate" rows="5" required placeholder="Enter here"
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">{{ $list->userupdate }}</textarea>
                                </x-admin.form-group>
                                <input type="hidden" name="id" value="{{ $list->id }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="mt-4">
                                    <button type="submit"
                                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                        Save
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
