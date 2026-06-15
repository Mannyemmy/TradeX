@extends('layouts.admin-dash')
@section('title', 'Manage Leads')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Manage Leads" subtitle="Leads are new users that have not made any deposit.">
        <x-slot name="actions">
            <button @click="$dispatch('open-assignmodal')"
                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" /></svg>
                Assign
            </button>
        </x-slot>
    </x-admin.page-header>

    {{-- Assign Modal --}}
    <x-admin.modal id="assignmodal" title="Assign Users to Admin for Follow Up" maxWidth="max-w-lg">
        <form method="post" action="{{ route('assignuser') }}">
            @csrf
            <div class="space-y-4">
                <x-admin.form-group label="Select User to Assign" :required="true">
                    <select name="user_name" class="form-control select2 w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" style="width:100%">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->l_name }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>

                <x-admin.form-group label="Select Admin to Assign This User To" :required="true">
                    <select name="admin"
                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <option value="">Select</option>
                        @foreach ($admin as $user)
                            <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>

                <button type="submit"
                    class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                    Assign
                </button>
            </div>
        </form>
    </x-admin.modal>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Import Section --}}
    <div class="mt-6">
        <x-admin.card>
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <p class="text-sm text-content-secondary">
                    Import leads from Excel document.
                    <a href="{{ route('downlddoc') }}" class="text-primary hover:text-primary-hover transition-colors underline">Download sample document</a>
                </p>
                <form action="{{ route('fileImport') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                    @csrf
                    <input name="file" type="file" required
                        class="bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                        Save
                    </button>
                </form>
            </div>
        </x-admin.card>
    </div>

    {{-- Leads Table --}}
    <div class="mt-6">
        <x-admin.table-card title="Leads">
            <table id="ShipTable" class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Email</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Phone</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Date Registered</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Assigned To</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $list)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $list->name }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->email }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $list->phone }}</td>
                            <td class="px-4 py-3.5">
                                @if ($list->status == 'active')
                                    <x-admin.badge type="success">Active</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">Inactive</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-muted">{{ $list->created_at->toDayDateTimeString() }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">
                                @if ($list->tuser->firstName)
                                    {{ $list->tuser->firstName }} {{ $list->tuser->lastName }}
                                @else
                                    <span class="text-info">Not assigned yet</span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <button @click="$dispatch('open-editmodal{{ $list->id }}')"
                                    class="bg-info-light text-info hover:bg-info hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                    Edit Status
                                </button>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
