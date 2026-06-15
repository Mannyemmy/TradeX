@extends('layouts.admin-dash')
@section('title', 'Manage Tasks')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Manage All Tasks" subtitle="View, edit, and delete tasks assigned to team members." />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">{{ session('success') }}</x-admin.alert>
    @endif
    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">{{ session('error') }}</x-admin.alert>
    @endif

    {{-- Tasks Table --}}
    <div class="mt-6">
        <x-admin.table-card title="All Tasks">
            <table id="ShipTable" class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Task Title</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Assigned To</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">From Date</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">To Date</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Date Created</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tasks as $task)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $task->title }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $task->tuser->firstName }} {{ $task->tuser->lastName }}</td>
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
                                <div class="flex items-center gap-2">
                                    @if ($task->status == 'Pending')
                                        <button @click="$dispatch('open-edittaskmodal{{ $task->id }}')"
                                            class="bg-success-light text-success hover:bg-success hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Edit
                                        </button>
                                    @endif
                                    <a href="{{ url('admin/dashboard/deltask') }}/{{ $task->id }}"
                                        onclick="return confirm('Are you sure you want to delete this task?')"
                                        class="bg-danger-light text-danger hover:bg-danger hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Task Modal --}}
                        <x-admin.modal id="edittaskmodal{{ $task->id }}" title="Edit Task" maxWidth="max-w-xl">
                            <form method="post" action="{{ route('updatetask') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="space-y-4">
                                    <x-admin.form-group label="Task Title" :required="true">
                                        <input type="text" name="tasktitle" value="{{ $task->title }}" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                    </x-admin.form-group>

                                    <x-admin.form-group label="Note" :required="true">
                                        <textarea name="note" rows="5" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">{{ $task->note }}</textarea>
                                    </x-admin.form-group>

                                    <x-admin.form-group label="Task Delegation" :required="true">
                                        <select name="delegation" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                            <option value="{{ $task->designation }}">{{ $task->tuser->firstName }} {{ $task->tuser->lastName }}</option>
                                            @foreach ($admin as $user)
                                                <option value="{{ $user->id }}">{{ $user->firstName }} {{ $user->lastName }}</option>
                                            @endforeach
                                        </select>
                                    </x-admin.form-group>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <x-admin.form-group label="From" :required="true">
                                            <input type="date" name="start_date" value="{{ $task->start_date }}" required
                                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        </x-admin.form-group>
                                        <x-admin.form-group label="To" :required="true">
                                            <input type="date" name="end_date" value="{{ $task->end_date }}" required
                                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                        </x-admin.form-group>
                                    </div>

                                    <x-admin.form-group label="Priority" :required="true">
                                        <select name="priority" required
                                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                            <option value="{{ $task->priority }}">{{ $task->priority }}</option>
                                            <option>Immediately</option>
                                            <option>High</option>
                                            <option>Medium</option>
                                            <option>Low</option>
                                        </select>
                                    </x-admin.form-group>

                                    <input type="hidden" name="id" value="{{ $task->id }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                    <button type="submit"
                                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                                        Apply Change
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
