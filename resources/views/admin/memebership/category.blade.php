@extends('layouts.admin-dash')
@section('title', 'Course Categories')

@section('content')

    <x-admin.page-header title="Course Categories" subtitle="Organize courses and standalone lessons into categories" />

    @if (session('message'))
        <x-admin.alert type="danger" :dismissible="true">{{ session('message') }}</x-admin.alert>
    @endif
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- Add Category --}}
    <div class="bg-surface-card rounded-xl border border-border shadow-card p-5 mb-6">
        <h3 class="text-sm font-medium text-content mb-3">Add New Category</h3>
        <form method="POST" action="{{ route('addcategory') }}" class="flex items-end gap-3">
            @csrf
            <div class="flex-1">
                <label class="block text-xs text-content-muted mb-1">Category Name</label>
                <input type="text" name="category" required placeholder="e.g. Crypto Basics" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                @error('category')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-4 py-2.5 rounded-lg transition-colors whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Category
            </button>
        </form>
    </div>

    {{-- Categories Table --}}
    @if ($categories->count())
        <x-admin.table-card title="Categories ({{ $categories->count() }})">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">#</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Courses</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Standalone Lessons</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr class="border-b border-border hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm text-content">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $cat->name }}</td>
                            <td class="px-4 py-3.5">
                                <x-admin.badge type="info">{{ $cat->courses_count }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5">
                                <x-admin.badge type="neutral">{{ $cat->standalone_lessons_count }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <button @click="$dispatch('open-delete-cat-modal-{{ $cat->id }}')" class="px-3 py-1 text-xs font-medium rounded-lg bg-danger-light text-danger hover:bg-danger hover:text-primary-foreground transition-colors">Delete</button>
                            </td>
                        </tr>

                        {{-- Delete Category Modal --}}
                        <x-admin.modal id="delete-cat-modal-{{ $cat->id }}" title="Delete Category">
                            <p class="text-sm text-content-secondary mb-2">Delete <strong class="text-content">{{ $cat->name }}</strong>?</p>
                            @if ($cat->courses_count > 0 || $cat->standalone_lessons_count > 0)
                                <p class="text-xs text-warning mb-4">This category has {{ $cat->courses_count }} course(s) and {{ $cat->standalone_lessons_count }} standalone lesson(s). Their category will be set to none.</p>
                            @endif
                            <form action="{{ route('deletecategory', $cat->id) }}" method="POST" class="flex justify-end gap-3">
                                @csrf @method('DELETE')
                                <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium rounded-lg bg-surface-alt text-content-secondary hover:text-content transition-colors">Cancel</button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-danger hover:bg-danger/90 text-primary-foreground transition-colors">Delete</button>
                            </form>
                        </x-admin.modal>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    @else
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-content-muted mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
            <h5 class="text-content font-medium mb-2">No categories yet</h5>
            <p class="text-sm text-content-secondary">Use the form above to create your first category.</p>
        </div>
    @endif

@endsection
