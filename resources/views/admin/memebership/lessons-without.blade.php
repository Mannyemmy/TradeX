@extends('layouts.admin-dash')
@section('title', 'Standalone Lessons')

@section('content')

    <x-admin.page-header title="Standalone Lessons" subtitle="Lessons with a category but no parent course">
        <button @click="$dispatch('open-add-lesson-modal')" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-4 py-2 rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            New Lesson
        </button>
    </x-admin.page-header>

    @if (session('message'))
        <x-admin.alert type="danger" :dismissible="true">{{ session('message') }}</x-admin.alert>
    @endif
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif

    @if ($lessons->count())
        <x-admin.table-card title="All Standalone Lessons ({{ $lessons->count() }})">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">#</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Thumbnail</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Title</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Category</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Duration</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-left">Preview</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lessons as $lesson)
                        <tr class="border-b border-border hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm text-content">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3.5">
                                <img src="{{ $lesson->thumbnail && str_starts_with($lesson->thumbnail, 'http') ? $lesson->thumbnail : asset('storage/' . $lesson->thumbnail) }}"
                                     class="w-16 h-10 rounded object-cover" alt="">
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="text-sm font-medium text-content">{{ $lesson->title }}</p>
                                <p class="text-xs text-content-muted mt-0.5 line-clamp-1">{{ $lesson->description }}</p>
                            </td>
                            <td class="px-4 py-3.5">
                                <x-admin.badge type="info">{{ $lesson->courseCategory->name ?? '—' }}</x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $lesson->length ?? '—' }}</td>
                            <td class="px-4 py-3.5">
                                <x-admin.badge :type="$lesson->is_preview ? 'success' : 'neutral'">
                                    {{ $lesson->is_preview ? 'Yes' : 'No' }}
                                </x-admin.badge>
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="$dispatch('open-edit-lesson-modal-{{ $lesson->id }}')" class="px-3 py-1 text-xs font-medium rounded-lg bg-surface-alt text-content-secondary hover:text-content transition-colors">Edit</button>
                                    <button @click="$dispatch('open-delete-lesson-modal-{{ $lesson->id }}')" class="px-3 py-1 text-xs font-medium rounded-lg bg-danger-light text-danger hover:bg-danger hover:text-primary-foreground transition-colors">Delete</button>
                                </div>
                            </td>
                        </tr>

                        {{-- Edit Lesson Modal --}}
                        <x-admin.modal id="edit-lesson-modal-{{ $lesson->id }}" title="Update Lesson" maxWidth="max-w-xl">
                            <form method="POST" action="{{ route('updatedlesson') }}" enctype="multipart/form-data">
                                @csrf @method('PATCH')
                                <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                                <div class="space-y-4">
                                    <x-admin.form-group label="Lesson Title" :required="true">
                                        <input type="text" name="title" value="{{ $lesson->title }}" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Category" :required="true">
                                        <select name="category" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                            @foreach ($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ $lesson->course_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Description">
                                        <textarea name="desc" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">{{ $lesson->description }}</textarea>
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Video Link" :required="true">
                                        <input type="text" name="videolink" value="{{ $lesson->video_link }}" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Duration" helper="e.g. 10:30">
                                        <input type="text" name="length" value="{{ $lesson->length }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Allow Preview">
                                        <select name="preview" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                            <option value="true" {{ $lesson->is_preview ? 'selected' : '' }}>Yes</option>
                                            <option value="false" {{ !$lesson->is_preview ? 'selected' : '' }}>No</option>
                                        </select>
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Thumbnail (File)" :error="$errors->first('image')">
                                        <input type="file" name="image" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content file:mr-3 file:rounded file:border-0 file:bg-primary-light file:px-3 file:py-1 file:text-sm file:text-primary">
                                    </x-admin.form-group>
                                    <x-admin.form-group label="Thumbnail (URL)" helper="File upload takes priority.">
                                        <input type="text" name="image_url" value="{{ $lesson->thumbnail }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                    </x-admin.form-group>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <button type="submit" class="bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-5 py-2 rounded-lg transition-colors">Update Lesson</button>
                                </div>
                            </form>
                        </x-admin.modal>

                        {{-- Delete Lesson Modal --}}
                        <x-admin.modal id="delete-lesson-modal-{{ $lesson->id }}" title="Delete Lesson">
                            <p class="text-sm text-content-secondary mb-4">Are you sure you want to delete <strong class="text-content">{{ $lesson->title }}</strong>?</p>
                            <form action="{{ route('deletelesson', $lesson->id) }}" method="POST" class="flex justify-end gap-3">
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
            <svg class="w-12 h-12 mx-auto text-content-muted mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
            <h5 class="text-content font-medium mb-2">No standalone lessons</h5>
            <p class="text-sm text-content-secondary mb-4">Create a lesson that belongs to a category but not a specific course.</p>
            <button @click="$dispatch('open-add-lesson-modal')" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Add Lesson
            </button>
        </div>
    @endif

    {{-- Add Standalone Lesson Modal --}}
    <x-admin.modal id="add-lesson-modal" title="Add Standalone Lesson" maxWidth="max-w-xl">
        <form method="POST" action="{{ route('addlesson') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <x-admin.form-group label="Category" :required="true">
                    <select name="category" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select category…</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
                <x-admin.form-group label="Lesson Title" :required="true">
                    <input type="text" name="title" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Description">
                    <textarea name="desc" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                </x-admin.form-group>
                <x-admin.form-group label="Video Link" :required="true">
                    <input type="text" name="videolink" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Duration" helper="e.g. 10:30">
                    <input type="text" name="length" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Allow Preview">
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 text-sm text-content">
                            <input type="radio" value="true" name="preview" class="accent-primary"> Allow
                        </label>
                        <label class="flex items-center gap-2 text-sm text-content">
                            <input type="radio" value="false" name="preview" checked class="accent-primary"> Don't Allow
                        </label>
                    </div>
                </x-admin.form-group>
                <x-admin.form-group label="Thumbnail (File)" :error="$errors->first('image')">
                    <input type="file" name="image" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content file:mr-3 file:rounded file:border-0 file:bg-primary-light file:px-3 file:py-1 file:text-sm file:text-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Thumbnail (URL)" helper="File upload takes priority.">
                    <input type="text" name="image_url" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-5 py-2 rounded-lg transition-colors">Add Lesson</button>
            </div>
        </form>
    </x-admin.modal>

@endsection
