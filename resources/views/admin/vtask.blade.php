@extends('layouts.admin-dash')
@section('title', 'My Tasks')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="My Tasks" subtitle="View tasks assigned to you and mark them as done." />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    {{-- Tasks Table --}}
    <div class="mt-6">
        <x-admin.table-card title="My Tasks">
            <table id="ShipTable" class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Task Title</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Assigned To</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Note</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">From Date</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">To Date</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Date Created</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $task->title }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $task->tuser->firstName }} {{ $task->tuser->lastName }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary max-w-xs truncate">{{ $task->note }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $task->start_date }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $task->end_date }}</td>
                            <td class="px-4 py-3.5">
                                @if ($task->status == 'Pending')
                                    <x-admin.badge type="danger">{{ $task->status }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="success">{{ $task->status }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-muted">{{ $task->created_at->toDayDateTimeString() }}</td>
                            <td class="px-4 py-3.5">
                                @if ($task->status == 'Pending')
                                    <a href="{{ url('admin/dashboard/markdone') }}/{{ $task->id }}"
                                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                        Mark as Done
                                    </a>
                                @else
                                    <x-admin.badge type="success">No Action Needed</x-admin.badge>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection
