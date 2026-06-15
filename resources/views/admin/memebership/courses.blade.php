@extends('layouts.admin-dash')
@section('title', 'Courses')

@section('content')

    <x-admin.page-header title="Courses" subtitle="Manage all courses in your learning platform.">
        <x-slot name="actions">
            <button @click="$dispatch('open-add-course-modal')" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Create New
            </button>
        </x-slot>
    </x-admin.page-header>

    @if (session('message'))
        <x-admin.alert type="danger" :dismissible="true">{{ session('message') }}</x-admin.alert>
    @endif
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- Course Cards Grid --}}
    @if ($courses->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
            @foreach ($courses as $course)
                <div class="bg-surface-card rounded-xl border border-border shadow-card overflow-hidden">
                    <img src="{{ $course->image && str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . $course->image) }}"
                         class="w-full h-44 object-cover" alt="{{ $course->title }}">
                    <div class="p-5">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-content font-semibold truncate">{{ $course->title }}</h4>
                            <x-admin.badge :type="$course->is_published ? 'success' : 'warning'">
                                {{ $course->is_published ? 'Published' : 'Draft' }}
                            </x-admin.badge>
                        </div>

                        @if ($course->category)
                            <p class="text-xs text-content-muted mb-3">{{ $course->category->name }}</p>
                        @endif

                        <div class="flex items-center justify-between text-sm text-content-secondary mb-3">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                                <span>{{ $course->lessons->count() }} {{ $course->lessons->count() === 1 ? 'Lesson' : 'Lessons' }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                <span>{{ $course->users->count() }} enrolled</span>
                            </div>
                        </div>

                        <p class="text-lg font-bold {{ $course->is_free ? 'text-success' : 'text-content' }} mb-4">
                            {{ $course->is_free ? 'Free' : $settings->currency . number_format($course->amount) }}
                        </p>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('lessons', $course->id) }}" class="flex-1 text-center px-3 py-1.5 text-sm font-medium rounded-lg bg-primary-light text-primary hover:bg-primary hover:text-primary-foreground transition-colors">
                                Lessons
                            </a>
                            <button @click="$dispatch('open-edit-course-modal-{{ $course->id }}')" class="flex-1 text-center px-3 py-1.5 text-sm font-medium rounded-lg bg-surface-alt text-content-secondary hover:text-content transition-colors">
                                Edit
                            </button>
                        </div>

                        <div class="flex items-center gap-2 mt-2">
                            <form action="{{ route('togglepublish', $course->id) }}" method="POST" class="flex-1">
                                @csrf @method('PATCH')
                                <button type="submit" class="w-full px-3 py-1.5 text-sm font-medium rounded-lg {{ $course->is_published ? 'bg-warning-light text-warning' : 'bg-success-light text-success' }} hover:opacity-80 transition-colors">
                                    {{ $course->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <button @click="$dispatch('open-delete-course-modal-{{ $course->id }}')" class="flex-1 text-center px-3 py-1.5 text-sm font-medium rounded-lg bg-danger-light text-danger hover:bg-danger hover:text-primary-foreground transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Edit Course Modal --}}
                <x-admin.modal id="edit-course-modal-{{ $course->id }}" title="Update Course" maxWidth="max-w-xl">
                    <form method="POST" action="{{ route('updatecourse') }}" enctype="multipart/form-data">
                        @csrf @method('PATCH')
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <div class="space-y-4">
                            <x-admin.form-group label="Course Category" for="category">
                                <select name="category" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="">-- No Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $course->course_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </x-admin.form-group>
                            <x-admin.form-group label="Course Title" :required="true">
                                <input type="text" name="title" value="{{ $course->title }}" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Description">
                                <textarea name="desc" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">{{ $course->description }}</textarea>
                            </x-admin.form-group>
                            <x-admin.form-group label="Amount ({{ $settings->currency }})" helper="Leave empty or 0 for a free course.">
                                <input type="number" step="0.01" name="amount" value="{{ $course->amount }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Published">
                                <select name="is_published" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="1" {{ $course->is_published ? 'selected' : '' }}>Published</option>
                                    <option value="0" {{ !$course->is_published ? 'selected' : '' }}>Draft</option>
                                </select>
                            </x-admin.form-group>
                            <x-admin.form-group label="Course Image (File)" :error="$errors->first('image')">
                                <input type="file" name="image" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content file:mr-3 file:rounded file:border-0 file:bg-primary-light file:px-3 file:py-1 file:text-sm file:text-primary">
                            </x-admin.form-group>
                            <x-admin.form-group label="Course Image (URL)" helper="File upload takes priority over URL.">
                                <input type="text" name="image_url" value="{{ $course->image }}" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                            </x-admin.form-group>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-5 py-2 rounded-lg transition-colors">Update Course</button>
                        </div>
                    </form>
                </x-admin.modal>

                {{-- Delete Course Modal --}}
                <x-admin.modal id="delete-course-modal-{{ $course->id }}" title="Delete Course">
                    <p class="text-sm text-content-secondary mb-4">Are you sure you want to delete <strong class="text-content">{{ $course->title }}</strong> and all its lessons? This action cannot be undone.</p>
                    <form action="{{ route('deletecourse', $course->id) }}" method="POST" class="flex justify-end gap-3">
                        @csrf @method('DELETE')
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium rounded-lg bg-surface-alt text-content-secondary hover:text-content transition-colors">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium rounded-lg bg-danger hover:bg-danger/90 text-primary-foreground transition-colors">Delete</button>
                    </form>
                </x-admin.modal>
            @endforeach
        </div>
    @else
        <div class="bg-surface-card rounded-xl border border-border shadow-card p-12 text-center mt-6">
            <svg class="w-12 h-12 mx-auto text-content-muted mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" /></svg>
            <h5 class="text-content font-medium mb-2">No courses yet</h5>
            <p class="text-sm text-content-secondary mb-4">Create your first course to get started.</p>
            <button @click="$dispatch('open-add-course-modal')" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                Add Course
            </button>
        </div>
    @endif

    {{-- Add Course Modal --}}
    <x-admin.modal id="add-course-modal" title="Add Course" maxWidth="max-w-xl">
        <form method="POST" action="{{ route('addcourse') }}" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <x-admin.form-group label="Course Category" for="category">
                    <select name="category" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">-- No Category --</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </x-admin.form-group>
                <x-admin.form-group label="Course Title" :required="true">
                    <input type="text" name="title" required class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Description">
                    <textarea name="desc" rows="3" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary"></textarea>
                </x-admin.form-group>
                <x-admin.form-group label="Amount ({{ $settings->currency }})" helper="Leave empty or 0 for a free course.">
                    <input type="number" step="0.01" name="amount" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Course Image (File)" :error="$errors->first('image')">
                    <input type="file" name="image" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content file:mr-3 file:rounded file:border-0 file:bg-primary-light file:px-3 file:py-1 file:text-sm file:text-primary">
                </x-admin.form-group>
                <x-admin.form-group label="Course Image (URL)" helper="File upload takes priority over URL.">
                    <input type="text" name="image_url" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2.5 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary">
                </x-admin.form-group>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-primary-hover text-primary-foreground text-sm font-medium px-5 py-2 rounded-lg transition-colors">Add Course</button>
            </div>
        </form>
    </x-admin.modal>

@endsection
